<?php

function debug($variable) : string 
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// sintetize HTML
function sanitize($html) : string
{
    return htmlspecialchars($html);
}