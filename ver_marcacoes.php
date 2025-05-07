<?php
// Conectar ao banco de dados SQLite
$db = new PDO("sqlite:base_dados.sqlite");

// Buscar todas as marcações do banco de dados
$dados = $db->query("SELECT * FROM marcacoes ORDER BY data, hora")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Marcações - Barbearia</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>CaetanoBarberShop</h1>
<h2>Lista de Marcações</h2>

<?php if (count($dados) > 0): ?>

<table>
<tr>
<th>Nome</th>
<th>Serviço</th>
<th>Data</th>
<th>Hora</th>
<th>Contacto</th>
<th>Ação</th>
</tr>
<?php foreach ($dados as $linha): ?>
<tr>
<td><?= htmlspecialchars($linha['nome']) ?></td>
<td><?= htmlspecialchars($linha['servico']) ?></td>
<td><?= htmlspecialchars($linha['data']) ?></td>
<td><?= htmlspecialchars($linha['hora']) ?></td>
<td><?= htmlspecialchars($linha['contacto']) ?></td>
<td>
    <form action="eliminar.php" method="post" onsubmit="return confirm('Tens a certeza que queres eliminar esta marcação?');">
        <input type="hidden" name="id" value="<?= $linha['id'] ?>">
        <button type="submit" style="background-color:#e74c3c;">Eliminar</button>
    </form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>Sem marcações registadas.</p>
<?php endif; ?>
<br>
<a href="index.html"><button>Nova Marcação</button></a>
</div>
</body>
</html>
