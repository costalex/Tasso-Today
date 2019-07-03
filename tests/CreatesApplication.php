<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{
	protected $app;

	/**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
	    $app = require __DIR__.'/../bootstrap/app.php';
	    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

//	    $app->loadEnvironmentFrom('../.env.testing');
//	    app('config')->set(['database.mysql.database' => 'bobby_test']);

	    Artisan::call('config:cache');

	    return $app;
    }

    public function loadEnv($env)
    {
	    if ($env == 'testing')
	    {
		    $app = require __DIR__.'/../bootstrap/app.php';
		    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		    $app->loadEnvironmentFrom('../.env.testing');
//			app('config')->set(['database.mysql.database' => 'bobby_test']);
//		    app('config')->set(['app.env' => 'testing']);
		    Artisan::call('config:cache');
		    return $app;
	    }
	    else
	    {
		    $app = require __DIR__.'/../bootstrap/app.php';
		    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		    $app->loadEnvironmentFrom('../.env');
//		    app('config')->set(['database.mysql.database' => 'bobby']);
//		    app('config')->set(['app.env' => 'development']);
		    Artisan::call('config:cache');
		    return $app;
	    }

    }

	public function setUp()
	{
		parent::setUp();
//		$this->loadEnv('testing');
		Artisan::call('migrate:reset');
		Artisan::call('migrate:refresh');
		$this->seed('UnitTestsSeeder');
	}

	public function tearDown()
	{
		parent::tearDown();
		Artisan::call('migrate:reset');
		$this->loadEnv('restore');
	}
}
