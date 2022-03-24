<?php

require 'funciones.php';
require 'database.php';
require __DIR__ . '/../vendor/autoload.php';


// conection database
use Model\ActiveRecord;

$db = conectarDB();
ActiveRecord::setDB($db);