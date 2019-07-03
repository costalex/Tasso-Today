<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test_one extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {file_path? : Emplacement du fichier a tester}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Lancement d'un test unitaire cible.";

	/**
	 * Emplacement du fichier a tester
	 * @var string
	 */
    protected $file_path = "";

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
    	if (empty($this->argument('file_path')))
	    {
		    $this->info("Lancement de tous les tests en cours...");
		    exec('vendor\bin\phpunit --stderr && php artisan config:cache');
	    }
    	else
	    {
		    $this->info("Lancement du test: " . $this->argument('file_path') . " en cours...");
		    exec('vendor\bin\phpunit ' .  $this->argument('file_path') . ' --stderr && php artisan config:cache');
	    }
	    $this->info("-----------------------------------------------------------------------");
    }
}
