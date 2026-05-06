<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Model/Task.php';

class TaskModelTest extends TestCase {
    public function testNaoPodeSalvarTarefaSemTitulo() {
        $pdoMock = $this->createMock(PDO::class); // Fingimos que o banco existe
        $model = new Task($pdoMock);
       
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Título e Data são obrigatórios.");
       
        $model->save("Comprando no Lopes", "Fazer compras", "Rodrigo", "2026-12-31"); // Deve disparar exceção
    }
}
?>