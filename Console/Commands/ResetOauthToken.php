<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\User;

class ResetOauthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:reset-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset users api-token';

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
        $userTable = (new User())->getTable();

        DB::table($userTable)->update([
            'refresh_token' => null,
            'api_token' => null,
            'remember_token' => null
        ]);

        DB::table('oauth_access_tokens')->truncate();
        DB::table('oauth_refresh_tokens')->truncate();
    }
}
