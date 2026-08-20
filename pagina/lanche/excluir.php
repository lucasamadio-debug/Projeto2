<?php
// Exclui um lanche (o estoque relacionado, se houver, cai junto por causa
// do ON DELETE CASCADE lá no banco)
$id = intval($_GET["id"] ?? 0);

$stmt = $conn->prepare("DELETE FROM produto WHERE id_produto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php?param=admin&aba=lanches&msg=excluido");
exit;