<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Task Master - Spaghetti</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #333; display: flex; justify-content: center; padding-top: 50px; }
        .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h1 { font-size: 1.5rem; text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .error { color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 4px; font-size: 0.9rem; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #2563eb; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        ul { list-style: none; padding: 0; }
        li { display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid #eee; }
        li.done strong, small { text-decoration: line-through; color: #9ca3af; }
        .actions a { text-decoration: none; margin-left: 10px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <h1>Task Master (MVC Edition)</h1>
   
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- O formulário agora aponta para a action 'create' -->

    <form method="POST" action="index.php?action=create" class="flex flex-wrap justify-center items-center gap-2 mt-5 mb-5">
        <input type="text" name="title" placeholder="O titulo" autocomplete="off" class="flex p-2 border border-gray-300 rounded">
        <input type="text" name="descricao" placeholder="Qual a descrição" autocomplete="off" class="flex p-2 border border-gray-300 rounded">
        <input type="date" name="dataVenc" placeholder="Data de vencimento" autocomplete="off" class="flex p-2 border border-gray-300 rounded">
        <input type="text" name="responsavel" placeholder="Quem é o responsavel" autocomplete="off" class="flex p-2 border border-gray-300 rounded">
        <button type="submit">Adicionar</button>
    </form>

    <ul>
        <?php foreach ($tasks as $task): ?>
            <li class="<?php echo $task['done'] ? 'done' : ''; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($task['title']); ?></strong> | Responsavel: <?php echo $task['responsavel']; ?><br>
                    <small><?php echo htmlspecialchars($task['descricao']); ?> | Vence em: <?php echo $task['dataVenc']; ?></small>
                
                </div>
                <div class="actions">
                    <?php if (!$task['done']): ?>
                        <a href="index.php?action=complete&id=<?php echo $task['id']; ?>">✅</a>
                    <?php endif; ?>
                    <a href="index.php?action=delete&id=<?php echo $task['id']; ?>">❌</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>