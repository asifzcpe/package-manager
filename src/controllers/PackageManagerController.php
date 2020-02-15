<?php
namespace Asif\PackageManager;
use Symfony\Component\HttpFoundation\StreamedResponse;
class PackageManagerController
{
    public function index()
    {
        return view('PackageManager::package-manager-dashboard');
    }

    public function installPackage($packageName)
    {

        $package=str_replace('^','/',$packageName);
        $proc = popen("composer require -d ".base_path()." $package 2>&1", 'r');
        $response = new StreamedResponse(function() use($proc){
            while(!feof($proc)) {
                echo "event: process_running\n";
                echo 'data: ' . fread($proc, 409000006) . "\n\n";

                ob_flush();
                flush();
                usleep(200000);
            }
            echo "event: process_ended\n";
            echo "data: installation completed \n\n";

        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        return $response;
    }
}
