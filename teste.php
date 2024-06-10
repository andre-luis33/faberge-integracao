<?php

require './vendor/autoload.php';
use App\Utils\Data;

$validationArray = [];
$ufs = Data::getUfs()->keys();

$ufs->each(function($uf) use (&$validationArray) {
   $validationArray[$uf] = 'nullable|integer|gt:0';
});

dd($validationArray);
