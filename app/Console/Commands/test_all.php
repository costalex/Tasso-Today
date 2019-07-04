<?php

namespace App\Console\Commands;

use function exec;
use Illuminate\Console\Command;
use Collective\Remote\RemoteFacade as SSH;

class test_all extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Lancement de tous les tests unitaire.";

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
	    exec('vendor/bin/phpunit --stderr && php artisan config:cache');
    }
}
