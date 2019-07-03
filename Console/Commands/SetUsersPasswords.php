<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;

use App\User;

class SetUsersPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwords:set {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set users passwords to given value';

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
        $users = User::all();

        foreach ($users as $user) {
            $user->password = Hash::make($this->argument('password'));
            $user->save();
        }
    }
}
