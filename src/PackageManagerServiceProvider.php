<?php
namespace Asif\PackageManager;
use Illuminate\Support\ServiceProvider;

class PackageManagerServiceProvider extends ServiceProvider
{
    public function register()
    {

    }
    public function boot()
    {
        include 'Routes.php';
        $this->loadViewsFrom(__DIR__.'/resources/views/', 'PackageManager');
    }
}