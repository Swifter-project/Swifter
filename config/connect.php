<?php
    include_once '/xampp/htdocs/deli/env.php';

    $db_server = $_ENV['DB_HOST'];
    $db_username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    
    if($_ENV['DB_ENV'] == 'local'){
        $_ENV['DB_NAME'] = 'deli';
    }
    $db_name = $_ENV['DB_NAME'];

    try {
        $conn = new PDO("mysql:host=$db_server;dbname=$db_name", $db_username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
