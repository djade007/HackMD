<?php
require __DIR__.'/vendor/autoload.php';

use HMO\Monnify;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();





$m = new Monnify();

//var_dump($m->reserveAccount("456", "hello@test.com"));

//var_dump($m->deactivateAccount("7381142151"));

//var_dump($m->transactions("456"));
