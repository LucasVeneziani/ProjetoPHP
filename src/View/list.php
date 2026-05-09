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
        li.done strong, li.done small { text-decoration: line-through; color: #9ca3af; }
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
        <input type="text" name="title" placeholder="O titulo" autocomplete="off" class="flex border border-gray-300 w-50 p-2 rounded">
        <input type="text" name="descricao" placeholder="Qual a descrição" autocomplete="off" class="flex border border-gray-300 w-50 p-2 rounded">
        <input type="date" name="dataVenc" placeholder="Data de vencimento" autocomplete="off" class="flex border border-gray-300 w-50 p-2 rounded">
        <input type="text" name="responsavel" placeholder="Quem é o responsavel" autocomplete="off" class="flex border border-gray-300 w-50 p-2 rounded">
        <button type="submit" class="bg-blue-700 hover:bg-blue-800 hover:shadow-lg hover:cursor-pointer text-white py-2 px-4 w-40 rounded">Adicionar</button>
    </form>

    <ul class="divide-y divide-gray-200">
        <?php foreach ($tasks as $task): ?>
            <li class="flex justify-between items-center p-3 <?php echo $task['done'] ? 'done' : ''; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($task['title']); ?></strong> | Responsavel: <?php echo $task['responsavel']; ?><br>
                    <small><?php echo htmlspecialchars($task['descricao']); ?> | Vence em: <?php echo $task['dataVenc']; ?></small>
                </div>
                <div>
                    <?php if (!$task['done']): ?>
                        <a class="border-2 border-green-500 bg-green-100 hover:bg-green-200 p-2 ml-2 rounded-full" href="index.php?action=complete&id=<?php echo $task['id']; ?>">✅</a>
                    <?php endif; ?>
                    <a class="border-2 border-red-500 bg-red-100 hover:bg-red-200 p-2 ml-2 rounded-full" href="index.php?action=delete&id=<?php echo $task['id']; ?>">❌</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>