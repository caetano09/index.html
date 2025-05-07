<?php
if (isset($_POST['id'])) {
    // Conectar ao banco de dados SQLite
    $db = new PDO("sqlite:base_dados.sqlite");

    // Preparar a consulta SQL para excluir a marcação
    $stmt = $db->prepare("DELETE FROM marcacoes WHERE id = :id");
    $stmt->bindParam(':id', $_POST['id']); // Vincula o ID que será deletado
    $stmt->execute(); // Executa a exclusão
}

// Redirecionar para a página de visualização das marcações
header("Location: ver_marcacoes.php");
exit;
?>
