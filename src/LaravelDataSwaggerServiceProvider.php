<?php

namespace Carlin\LaravelDataSwagger;

use Illuminate\Support\ServiceProvider;
use Carlin\LaravelDataSwagger\Commands\GenerateDocsCommand;

class LaravelDataSwaggerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any package services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		$this->registerCommands();

		$this->registerPublishing();
	}

	public function register(): void
	{
		$configPath = __DIR__.'/../config/laravel-data-swagger.php';
		$this->mergeConfigFrom($configPath, 'laravel-data-swagger');

	}



	protected function registerCommands(): void
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				GenerateDocsCommand::class,
			]);
		}
	}

	private function registerPublishing(): void
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__.'/../config/laravel-data-swagger.php' => config_path('laravel-data-swagger.php'),
			], 'config');
		}
	}

}
