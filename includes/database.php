<?php

function conectarDB(): mysqli{
    $db = new mysqli('localhost', 'root', '', '');
    // location, user, password, nameDB

    if (!$db) {
        echo "No se pudo conectar a MySQL";
        exit;
    }
    return $db;
}