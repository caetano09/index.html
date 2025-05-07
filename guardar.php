<?php
// Ativar mensagens de erro (útil para testes locais)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'], $_POST['servico'], $_POST['data'], $_POST['hora'], $_POST['contacto'])) {

    // Conectar ao banco de dados SQLite
    $db = new PDO("sqlite:base_dados.sqlite");

    // Criar tabela se não existir
    $db->exec("CREATE TABLE IF NOT EXISTS marcacoes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT,
        servico TEXT,
        data TEXT,
        hora TEXT,
        contacto TEXT
    )");

    // Obter os dados do formulário
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    // Verificar conflitos de horário
    $verificar = $db->prepare("SELECT COUNT(*) FROM marcacoes WHERE data = :data AND hora = :hora");
    $verificar->bindParam(':data', $data);
    $verificar->bindParam(':hora', $hora);
    $verificar->execute();

    $existe = $verificar->fetchColumn(); // Verifica se já existe uma marcação nesse horário

    // HTML para exibir o resultado
    ?>

    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Resultado do Agendamento</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h1>CaetanoBarberShop</h1>

            <?php if ($existe > 0): ?>
                <h2 style="color: #e74c3c;">Horário Ocupado</h2>
                <p>Já existe uma marcação para <strong><?= htmlspecialchars($data) ?> às <?= htmlspecialchars($hora) ?></strong>.</p>
                <a href="index.html"><button>Tentar Outro Horário</button></a>

            <?php else:
                // Se o horário estiver livre, inserir a marcação na base de dados
                $stmt = $db->prepare("INSERT INTO marcacoes (nome, servico, data, hora, contacto)
                                      VALUES (:nome, :servico, :data, :hora, :contacto)");
                $stmt->bindParam(':nome', $_POST['nome']);
                $stmt->bindParam(':servico', $_POST['servico']);
                $stmt->bindParam(':data', $data);
                $stmt->bindParam(':hora', $hora);
                $stmt->bindParam(':contacto', $_POST['contacto']);
                $stmt->execute();
            ?>
                <h2 style="color: #2ecc71;">Marcação Confirmada</h2>
                <p>Serviço: <strong><?= htmlspecialchars($_POST['servico']) ?></strong></p>
                <p>Data: <strong><?= htmlspecialchars($data) ?></strong> às <strong><?= htmlspecialchars($hora) ?></strong></p>
                <a href="index.html"><button>Nova Marcação</button></a>
                <a href="ver_marcacoes.php"><button>Ver Marcações</button></a>
            <?php endif; ?>
        </div>
    </body>
    </html>

<?php
} else {
    // Se alguém tentar acessar diretamente sem submeter o formulário
    echo "<p style='color:red;'>Acesso inválido. Por favor submeta o formulário primeiro.</p>";
}
?>
