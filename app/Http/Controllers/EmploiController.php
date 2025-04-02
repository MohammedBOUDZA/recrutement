<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\JobCategory;
use App\Models\Skill;
use App\Models\JobView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmploiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:recruteur')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $query = Emploi::with(['entreprise', 'categories', 'skills'])
            ->active();

        if ($request->has('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%')
                  ->orWhereHas('entreprise', function($q) use ($request) {
                      $q->where('company_name', 'like', '%' . $request->keyword . '%');
                  });
            });
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->has('salary_range')) {
            $parts = explode('-', $request->salary_range);
            if (count($parts) === 2) {
                [$min, $max] = $parts;
                if ($max === '+') {
                    $query->where('salary_min', '>=', $min);
                } else {
                    $query->where('salary_min', '>=', $min)
                          ->where('salary_max', '<=', $max);
                }
            }
        }

        if ($request->boolean('remote')) {
            $query->remote();
        }
        if ($request->boolean('hybrid')) {
            $query->hybrid();
        }
        if ($request->boolean('urgent')) {
            $query->urgent();
        }

        if ($request->has('categories')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('id', $request->categories);
            });
        }

        if ($request->has('skills')) {
            $query->whereHas('skills', function($q) use ($request) {
                $q->whereIn('id', $request->skills);
            });
        }

        switch ($request->sort) {
            case 'salary_high':
                $query->orderBy('salary_max', 'desc');
                break;
            case 'salary_low':
                $query->orderBy('salary_min', 'asc');
                break;
            case 'relevance':
                if ($request->has('keyword')) {
                    $query->orderByRaw('CASE 
                        WHEN title LIKE ? THEN 1
                        WHEN description LIKE ? THEN 2
                        ELSE 3
                    END', ['%' . $request->keyword . '%', '%' . $request->keyword . '%']);
                }
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $emplois = $query->paginate(10)->withQueryString();
        $categories = JobCategory::all();
        $skills = Skill::all();

        return view('emploi.index', compact('emplois', 'categories', 'skills'));
    }

    public function show(Emploi $emploi)
    {
        JobView::create([
            'emploi_id' => $emploi->id,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $similarJobs = Emploi::where('emplois.id', '!=', $emploi->id)
            ->where('expires_at', '>', now())
            ->where('status', 'active')
            ->where(function($query) use ($emploi) {
                $query->whereHas('categories', function($q) use ($emploi) {
                    $q->whereIn('job_categories.id', $emploi->categories->pluck('id'));
                })
                ->orWhereHas('skills', function($q) use ($emploi) {
                    $q->whereIn('skills.id', $emploi->skills->pluck('id'));
                });
            })
            ->with(['entreprise', 'categories', 'skills'])
            ->take(5)
            ->get();

        return view('emplois.show', compact('emploi', 'similarJobs'));
    }

    public function create()
    {
        $categories = JobCategory::all();
        $skills = Skill::all();
        return view('emplois.create',compact("categories","skills"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'salary_min' => ['required', 'numeric', 'min:0'],
            'salary_max' => ['required', 'numeric', 'gt:salary_min'],
            'salary_type' => ['required', Rule::in(['hourly', 'daily', 'weekly', 'monthly', 'yearly'])],
            'employment_type' => ['required', Rule::in(['full-time', 'part-time', 'contract', 'temporary', 'internship'])],
            'requirements' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'remote' => ['boolean'],
            'hybrid' => ['boolean'],
            'urgent' => ['boolean'],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:job_categories,id'],
            'skills' => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
            'questions' => ['nullable', 'array'],
            'questions.*' => ['string'],
            'expires_at' => ['required', 'date', 'after:today'],
        ]);

        $emploi = new Emploi($validated);
        $emploi->entreprise_id = Auth::user()->entreprise->id;
        $emploi->questions = $request->questions ? json_encode($request->questions) : null;
        $emploi->save();

        $emploi->categories()->attach($request->categories);
        foreach ($request->skills as $skillId => $level) {
            $emploi->skills()->attach($skillId, ['level' => $level]);
        }

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Job posted successfully!');
    }

    public function edit(Emploi $emploi)
    {
        $this->authorize('update', $emploi);
        
        $categories = JobCategory::all();
        $skills = Skill::all();
        return view('emplois.edit', compact('emploi', 'categories', 'skills'));
    }

    public function update(Request $request, Emploi $emploi)
    {
        $this->authorize('update', $emploi);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'salary_min' => ['required', 'numeric', 'min:0'],
            'salary_max' => ['required', 'numeric', 'gt:salary_min'],
            'salary_type' => ['required', Rule::in(['hourly', 'daily', 'weekly', 'monthly', 'yearly'])],
            'employment_type' => ['required', Rule::in(['full-time', 'part-time', 'contract', 'temporary', 'internship'])],
            'requirements' => ['required', 'string'],
            'benefits' => ['required', 'string'],
            'remote' => ['boolean'],
            'hybrid' => ['boolean'],
            'urgent' => ['boolean'],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:job_categories,id'],
            'skills' => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
            'questions' => ['nullable', 'array'],
            'questions.*' => ['string'],
            'expires_at' => ['required', 'date', 'after:today'],
        ]);

        $emploi->update($validated);
        $emploi->questions = $request->questions ? json_encode($request->questions) : null;
        $emploi->save();

        $emploi->categories()->sync($request->categories);
        $emploi->skills()->sync(
            collect($request->skills)->map(function($level, $skillId) {
                return ['level' => $level];
            })->toArray()
        );

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(Emploi $emploi)
    {
        $this->authorize('delete', $emploi);

        $emploi->delete();

        return redirect()->route('entreprise.dashboard')
            ->with('success', 'Job deleted successfully!');
    }

    public function saveJob(Emploi $emploi)
    {
        $user = Auth::user();
        
        if ($user->hasSavedJob($emploi)) {
            return back()->with('error', 'You have already saved this job.');
        }

        $user->savedJobs()->attach($emploi->id);

        return back()->with('success', 'Job saved successfully!');
    }

    public function unsaveJob(Emploi $emploi)
    {
        $user = Auth::user();
        
        if (!$user->hasSavedJob($emploi)) {
            return back()->with('error', 'This job is not in your saved jobs.');
        }

        $user->savedJobs()->detach($emploi->id);

        return back()->with('success', 'Job removed from saved jobs.');
    }

    public function apply(Emploi $emploi)
    {
        $user = auth()->user();
        
        if ($user->applications()->where('emplois_id', $emploi->id)->exists()) {
            return redirect()->route('emplois.show', $emploi)
                ->with('error', 'You have already applied for this job.');
        }

        $user->applications()->attach($emploi->id);

        return redirect()->route('emplois.show', $emploi)
            ->with('success', 'Your application has been submitted successfully!');
    }
}
