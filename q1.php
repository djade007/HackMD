<?php
require __DIR__.'/vendor/autoload.php';


use HMO\Monnify;



$m = new Monnify();

//$m->reserveAccount("456", "hello@test.com");

//$m->deactivateAccount("7381142151");

$m->transactions("456");
