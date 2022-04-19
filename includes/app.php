<?php

require 'functions.php';
require 'database.php';
require __DIR__ . '/../vendor/autoload.php';


// conection database
use Model\ActiveRecord;

$db = conectDB();
ActiveRecord::setDB($db);