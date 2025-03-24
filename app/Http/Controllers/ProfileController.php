<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chercheur; // Import the Chercheur model
use Illuminate\Support\Facades\Auth; // To get the authenticated user

class ProfileController extends Controller
{
    // Show the profile page
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch the profile data from the `chercheurs` table
        $profile = Chercheur::where('user_id', $user->id)->first();

        // Pass the profile data to the view
        return view('profile', compact('profile'));
    }
}
