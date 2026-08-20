<?php
$id = $_POST["id_categoria"] ?? "";
$nome = $_POST["nome"] ?? "";

if (empty($id)) {
    $stmt = $conn->prepare("INSERT INTO categoria (nome_categoria) VALUES (?)");
    $stmt->bind_param("s", $nome);
} else {
    $stmt = $conn->prepare("UPDATE categoria SET nome_categoria = ? WHERE id_categoria = ?");
    $stmt->bind_param("si", $nome, $id);
}

$stmt->execute();
header("Location: index.php?param=admin&aba=categorias&msg=salvo");
exit;