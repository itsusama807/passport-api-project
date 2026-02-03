<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PromoteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:promote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Users status from temporary to permanent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('PromoteUsers command started');
        $users = User::where('status', 'temporary')->where('created_at', '>=', now()->subMinutes(5))->get();
        foreach ($users as $user) {
            $user->update(['status' => 'permanent']);
            Log::info("Updated user ID {$user->id} to permanent");
            Mail::raw('Your account is now permanent', function ($msg) use ($user) {
                $msg->to($user->email)
                    ->subject('Account Status Updated');
            });
        }
        Log::info('Users promoted: ' . $users->count());
    }
}
