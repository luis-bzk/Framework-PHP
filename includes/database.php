<?php

function conectarDB(): mysqli{
    $db = new mysqli('localhost', 'root', '', 'bienes_raices');

    if (!$db) {
        echo "No se pudo conectar a MySQL";
        exit;
    }
    return $db;
}