<?php
// ==========================================
// AULA 01: O CÓDIGO SPAGHETTI (Tudo misturado)
// ==========================================

// 1. CONEXÃO COM O BANCO DE DADOS E CRIAÇÃO DA TABELA (Acoplamento de Infraestrutura) MODEL
$dbFile = __DIR__ . '/tasks.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
//     id INTEGER PRIMARY KEY AUTOINCREMENT,
//     title TEXT NOT NULL,
//     done INTEGER DEFAULT 0
// )");

$pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    descricao TEXT,
    dataVenc DATE,
    responsavel VARCHAR,
    done INTEGER DEFAULT 0
)");

// 2. LÓGICA DE NEGÓCIO E CONTROLE DE REQUISIÇÕES MISTURADOS CONTROLLER
$error = '';

// Criar nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']))
{
    $title = trim($_POST['title']);
    $descricao = trim($_POST['descricao']);
    $dataVenc = trim($_POST['dataVenc']);
    $responsavel = trim($_POST['responsavel']);
    
    // Regra de negócio solta no meio do arquivo 
    if (empty($title)) {
        $error = "O título da tarefa não pode estar vazio!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, descricao, dataVenc, responsavel) VALUES (:title, :descricao, :dataVenc, :responsavel)");
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindValue(':dataVenc', $dataVenc, PDO::PARAM_STR);
        $stmt->bindValue(':responsavel', $responsavel, PDO::PARAM_STR);
        // $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
        // $stmt->bindValue(':title', $title);
        $stmt->execute();
        
        // Redirecionamento misturado com a lógica
        header("Location: index.php");
        exit;
    }
}

// Concluir ou excluir tarefa
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    if ($_GET['action'] === 'complete') {
        $pdo->exec("UPDATE tasks SET done = 1 WHERE id = $id");
    } elseif ($_GET['action'] === 'delete') {
        $pdo->exec("DELETE FROM tasks WHERE id = $id");
    }
    
    header("Location: index.php");
    exit;
}

// 3. BUSCA DE DADOS MISTURADA COM A VISUALIZAÇÃO
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($tasks)
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Task Master - Spaghetti</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h1 { font-size: 1.5rem; text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .error { color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 4px; font-size: 0.9rem; }
        ul { list-style: none; padding: 0; }
        li { display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #eee; }
        li.done span { text-decoration: line-through; color: #9ca3af; }
        .actions a { flex: 1; text-decoration: none; margin-left: 2px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h1>Task Master (Spaghetti Edition)</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php" class="flex flex-wrap sm:flex-nowrap dm: items-center justify-center gap-2 p-1 mt-2 mb-2">
        <input type="text" name="title" placeholder="O que precisa ser feito?" autocomplete="off" class="flex p-2 w-50 rounded border">
        <input type="text" name="descricao" placeholder="O que a tarefa faz" autocomplete="off" class="flex p-2 w-50 rounded border">
        <input type="date" name="dataVenc" placeholder="O dia que vence" autocomplete="off" class="hover:cursor-text flex p-2 w-50 rounded border">
        <input type="text" name="responsavel" placeholder="O que precisa ser feito?" autocomplete="off" class="flex p-2 w-50 rounded border">
        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white pt-2 pb-2 pl-4 pr-4 w-40 rounded">Adicionar</button>
    </form>

    <ul class="mt-4">
        <?php foreach ($tasks as $task): ?>
            <li class="border-2 border-gray-300 <?php echo $task['done'] ? 'done' : ''; ?>">
                <span class="border-1 border-gray-300 p-2"><?php echo htmlspecialchars($task['title']); ?></span>
                <span class="border-1 border-gray-300 p-2"><?php echo htmlspecialchars($task['descricao']); ?></span>
                <span class="border-1 border-gray-300 p-2"><?php echo htmlspecialchars($task['dataVenc']); ?></span>
                <span class="border-1 border-gray-300 p-2"><?php echo htmlspecialchars($task['responsavel']); ?></span>

                <div class="actions">
                    <?php if (!$task['done']): ?>
                        <a class="border-1 border-green-500 p-2 rounded-full" href="?action=complete&id=<?php echo $task['id']; ?>" title="Concluir">✅</a>
                    <?php endif; ?>
                    <a class="border-1 border-red-500 p-2 rounded-full" href="?action=delete&id=<?php echo $task['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');" title="Excluir">❌</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>