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
    
    $stmt = $pdo->query("SELECT * FROM sections LIMIT 5");
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Sections count: " . count($sections) . "\n";
    if (count($sections) > 0) {
        print_r($sections[0]);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
