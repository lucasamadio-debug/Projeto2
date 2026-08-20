<?php
// Salva (cria ou atualiza) um lanche
$id = $_POST["id_produto"] ?? "";
$nome = $_POST["nome_lanches"] ?? "";
$preco = $_POST["preco"] ?? "";
$categoria = $_POST["categoria_id"] ?? 1;

if (empty($id)) {
    $stmt = $conn->prepare("INSERT INTO produto (nome_lanches, preco, id_categoria) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $nome, $preco, $categoria);
} else {
    $stmt = $conn->prepare("UPDATE produto SET nome_lanches = ?, preco = ?, id_categoria = ? WHERE id_produto = ?");
    $stmt->bind_param("sdii", $nome, $preco, $categoria, $id);
}

$stmt->execute();
header("Location: index.php?param=admin&aba=lanches&msg=salvo");
exit;