<?php
header('Content-Type: application/json; charset=utf-8');


require_once "../include/coneçao.php";
require_once "../include/funcoes.php";

try {
    if (!isset($conn) || !$conn) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }


    $produtos = buscarTodosProdutos($conn);

    http_response_code(200);
    echo json_encode([
        'sucesso' => true,
        'dados'   => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao carregar produtos: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}