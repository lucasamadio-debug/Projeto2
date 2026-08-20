<?php
$id = intval($_POST["id_estoque"] ?? 0);
$quantidade = intval($_POST["quantidade"] ?? 0);

$stmt = $conn->prepare("UPDATE estoque SET quantidade = ? WHERE id_estoque = ?");
$stmt->bind_param("ii", $quantidade, $id);
$stmt->execute();

header("Location: index.php?param=admin&aba=estoque&msg=salvo");
exit;