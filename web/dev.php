<?php

//Check if localhost
if (substr($_SERVER['HTTP_HOST'], 0, strlen('localhost')) != 'localhost') {
    header("HTTP/1.1 403 Forbidden");
    die("Nope");
}

//Devmode on
$devMode = true;

//Include index
include(__DIR__ . '/index.php');

/* EOF: dev.php */
