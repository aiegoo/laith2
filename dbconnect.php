<?php
    // define database password and username
    $username = 'ajay'; //"id12433974_showyoutube";
    $password = "12345678";

    // define the database connection dns
    $dsn = "mysql:host=localhost;dbname=id12433974_youtube";

    // initiate pdo mysql database connectio 
    $pdo = new PDO($dsn, $username, $password);
?>
