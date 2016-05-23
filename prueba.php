<?php

$mysqli = new mysqli("localhost", "jobscodi_usr", "T]D_O]5gV8,p", "jobscodi_db");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

echo $mysqli->host_info . "\n";

