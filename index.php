<?php

    require './vendor/autoload.php';

    use JobImporter\Indeed;

    $indeed = new Indeed();
    
    $job = $indeed
        ->whereJobId('8ddb45e938de85a6')
        ->find();





    $jobs = $indeed
        ->whereCompanyId('British-Heart-Foundation')
        ->get();

    // echo(json_encode($job, JSON_PRETTY_PRINT));
    // die();
    var_dump($jobs);
    die();