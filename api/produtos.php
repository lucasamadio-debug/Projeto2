<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../include/coneçao.php";

try {
    if (!isset($conn) || !$conn) {
        throw new Exception("Falha na conexão com a base de dados.");
    }

    $categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;

    $stmt = $conn->prepare("CALL sp_listar_produtos(?)");
    $stmt->bind_param("i", $categoria);
    $stmt->execute();

    $res = $stmt->get_result();
    $produtos = [];

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $produtos[] = $row;
        }
    }

    $stmt->close();
    $conn->next_result();

    http_response_code(200);
    echo json_encode([
        'sucesso' => true,
        'dados'   => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}