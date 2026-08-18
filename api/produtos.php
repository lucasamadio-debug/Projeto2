<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../include/coneçao.php";

try {
    if (!isset($conn) || !$conn) {
        throw new Exception("Falha na conexão com a base de dados.");
    }

    // Consulta simples para retornar a lista de produtos
    // (agora incluindo "popular", usado pelo dashboard.ts)
    $res = $conn->query("SELECT id_produto, nome_lanches, preco, id_categoria, popular FROM produto");
    $produtos = [];

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $produtos[] = $row;
        }
    }

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