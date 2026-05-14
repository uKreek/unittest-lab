<?php
class Student
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function add($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Name cannot be empty");
        }
        
        if ($this->pdo !== null) {
            $stmt = $this->pdo->prepare("INSERT INTO students (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
        }
        
        return "Student {$name} added";
    }

    public function getCount()
    {
        if ($this->pdo === null) return 0;
        
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM students");
        return (int) $stmt->fetchColumn();
    }

    public function getAll()
    {
        if ($this->pdo === null) return [];
        $stmt = $this->pdo->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
