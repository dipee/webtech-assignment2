<?php

    // Connect to the database
    $servername   = 'localhost'; // 127.0.0.1 // local machine
    $username     = 'root';
    $password     = '';
    $dbname       = 'ecom_asn2';

    // create the connection using pdo
    $dsn = "mysql:host=$servername;dbname=$dbname";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
?>