<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * The base URL to use while testing the application.
	 *
	 * @var string
	 */
	protected $baseUrl = 'http://restbook.loc';

	/**
	 * Prepare for test
	 *
	 */
	public function setUp()
	{
		parent::setUp();
		$this->prepareForTests();
	}

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	/**
	 * Migrate database
	 *
	 */
	private function prepareForTests()
	{
		Artisan::call('db:seed', ['--class'=>'TestSeeder']);
	}

}
