<?php
// Exclui um usuário admin
$id = intval($_GET["id"] ?? 0);

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php?param=admin&aba=usuarios&msg=excluido");
exit;