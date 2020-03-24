<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Illuminate\Console\Command;

class GenerateTodayAttendanceForEnrollers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Attendance For All Enrolled Students In Courses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            User::with('playlists')->get()->map(function ($user){
                $attendances = $user->playlists->map(function ($pUser){
                    $pUser->pivot->attendance()->create(['attended'=>false]);
                });
                return $attendances;
            });
            $this->info('New Attendance Generated');
        }
        catch (Exception $exception){
            $this->error('Something Wrong Happened');
        }
    }
}
