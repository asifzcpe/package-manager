<?php

Route::get('/package-manager','Asif\PackageManager\PackageManagerController@index');
Route::get('/install-package/{packageName}','Asif\PackageManager\PackageManagerController@installPackage');
Route::get('/packages',function (){

    $file = base_path().'/composer.lock';
    $packages = json_decode(file_get_contents($file), true)['packages'];
    foreach ($packages as $package) {
        echo $package['name']."</br>";
    }

});
