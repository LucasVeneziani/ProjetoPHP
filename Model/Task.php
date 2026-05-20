<?php

class Task {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Busca todas as tarefas
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM tasks ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Salva uma nova tarefa (agora com os campos do desafio!)
    public function save($title, $descricao,$responsavel, $dataVenc ) {
        if (empty(trim($title)) || empty(trim($dataVenc))) {
            throw new Exception("Título e Data são obrigatórios.");
        }
       
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, descricao, responsavel, dataVenc) VALUES (:title, :descricao, :responsavel, :dataVenc)");
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindValue(':responsavel', $responsavel, PDO::PARAM_STR);
        $stmt->bindValue(':dataVenc', $dataVenc, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function complete($id) {
        return $this->pdo->exec("UPDATE tasks SET done = 1 WHERE id = " . (int)$id);
        
    }

    public function delete($id) {
        return $this->pdo->exec("DELETE FROM tasks WHERE id = " . (int)$id);
    }
}
?>