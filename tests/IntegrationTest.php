<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../www/Student.php';

class IntegrationTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $db   = $_ENV['DB_NAME'] ?? 'test_db';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';

        try {
            $this->pdo = new PDO("mysql:host=$host", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
            $this->pdo->exec("USE `$db`");
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS students (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))");
            $this->pdo->exec("TRUNCATE TABLE students");
        } catch (PDOException $e) {
            $this->markTestSkipped("БД недоступна: " . $e->getMessage());
        }
    }

    public function testRealDatabaseIntegration()
    {
        $student = new Student($this->pdo);

        $this->assertEquals(0, $student->getCount());

        $student->add("Maxim");
        $student->add("Ruslan");

        $this->assertEquals(2, $student->getCount());
    }
}
