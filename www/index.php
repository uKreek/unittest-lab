<?php
header('Content-Type: application/json');

require_once __DIR__ . '/Student.php';

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'test_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))");
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection error: " . $e->getMessage()]));
}

$student = new Student($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    try {
        $msg = $student->add($name);
        echo json_encode(["status" => "success", "message" => $msg]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        "status" => "success", 
        "data" => $student->getAll(), 
        "count" => $student->getCount()
    ]);
}
