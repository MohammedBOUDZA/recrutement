<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdminRole extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Set a user as admin by email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("User {$user->name} has been set as admin!");
        return 0;
    }
} 