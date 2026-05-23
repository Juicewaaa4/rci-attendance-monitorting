<?php
$host = 'your_database_host';
$port = 3306;
$dbname = 'your_database_name';
$user = 'your_database_user';
$pass = 'your_database_password';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $options = [
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $stmt = $pdo->query("SELECT * FROM sections");
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($sections, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
