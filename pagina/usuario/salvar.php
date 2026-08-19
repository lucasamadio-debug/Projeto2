<?php
// Salva um usuário admin
$id = $_POST["id_usuario"] ?? "";
$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senhaPura = trim($_POST["senha"] ?? "");

if (empty($id)) {
    //senha é obrigatória
    $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);
} else {
    if (!empty($senhaPura)) {
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nome, $email, $senhaHash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $nome, $email, $id);
    }
}

$stmt->execute();
header("Location: index.php?param=admin&aba=usuarios&msg=salvo");
exit;