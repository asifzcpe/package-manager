<?php
namespace Asif\PackageManager;

class PackageManagerController
{
    public function index()
    {
        return view('PackageManager::package-manager-dashboard');
    }
}