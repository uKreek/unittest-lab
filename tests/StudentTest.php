<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../www/Student.php';

class StudentTest extends TestCase
{
    private $pdoMock;
    private $student;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->student = new Student($this->pdoMock);
    }

    public function testAdd()
    {
        $student = new Student(null);
        $result = $student->add("Ivan");
        $this->assertEquals("Student Ivan added", $result);
    }

    public function testAddThrowsExceptionOnEmptyName()
    {
        $this->expectException(InvalidArgumentException::class);
        $student = new Student(null);
        $student->add("");
    }
    
    public function testAddWithMock()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);

        $this->pdoMock->expects($this->once())
                      ->method('prepare')
                      ->willReturn($stmtMock);

        $result = $this->student->add("Maria");
        $this->assertEquals("Student Maria added", $result);
    }
}
