<?php

Route::get('/package-manager','Asif\PackageManager\PackageManagerController@index');
Route::get('/run',function (){
//    $last_line = system('composer require asif/laravel-moduler', $retval);
//    echo '<pre>';
//    passthru('composer dumpautoload');
//    echo '</pre>';

    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen("composer require asif/laravel-moduler 2>&1", 'r');
    echo '<pre>';
    while (!feof($proc))
    {
        echo fread($proc, 409000006);
        @ flush();
    }
    echo '</pre>';

//    echo "composer: " . shell_exec('cd .. ; composer install --no-interaction --no-dev --prefer-dist 2>&1');

//    echo exec('composer dumpautoload');

});
