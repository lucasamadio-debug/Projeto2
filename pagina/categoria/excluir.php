<?php
$id = intval($_GET["id"] ?? 0);

$stmt = $conn->prepare("DELETE FROM categoria WHERE id_categoria = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php?param=admin&aba=categorias&msg=excluido");
exit;