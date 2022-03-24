<?php

function debug($variable) : string 
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// sintetize HTML
function s($html) : string
{
    return htmlspecialchars($html);
}