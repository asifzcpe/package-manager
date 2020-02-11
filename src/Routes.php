<?php

use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/package-manager','Asif\PackageManager\PackageManagerController@index');
Route::get('/run/{packageName}',function ($packageName){

//
//    $process=new Process('ping -c 4 google.com');
//    $process->run(function ($type, $buffer) {
//        if (Process::ERR === $type) {
//            echo 'ERR > '.$buffer;
//        } else {
//            echo 'OUT > '.$buffer;
//        }
//    });
//    $last_line = system('composer require asif/laravel-moduler', $retval);
//    echo '<pre>';
//    passthru('composer dumpautoload');
//    echo '</pre>';
//        echo base_path();
//     while (@ ob_end_flush()); // end all output buffers if any
//     // composer require asif/laravel-moduler 2>&1
//
//     $proc = popen("composer require -d ".base_path()." asif/laravel-moduler 2>&1", 'r');
//     echo '<pre>';
//     while (!feof($proc))
//     {
//         echo fread($proc, 409000006);
//
//         @ flush();
//     }
//     echo '</pre>';
//    $proc = popen("composer dumpautoload 2>&1", 'r');
    $proc = popen("composer remove -d ".base_path()." asif/laravel-moduler 2>&1", 'r');
    $response = new StreamedResponse(function() use($proc){
        while(!feof($proc)) {
            echo "event: process_running\n";
            echo 'data: ' . fread($proc, 409000006) . "\n\n";

            ob_flush();
            flush();
            usleep(200000);
        }
        echo "event: process_ended\n";
        echo "data: ddddddd \n\n";

    });

    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('X-Accel-Buffering', 'no');
    $response->headers->set('Cach-Control', 'no-cache');
    return $response;

//   echo "composer: " . shell_exec('composer dumpautoload 2>&1');
//        echo base_path();
//    echo exec('composer dumpautoload');

});
