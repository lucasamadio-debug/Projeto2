<?php
require_once "include/coneçao.php";

/** 
 * Busca todos os produtos agrupados por categoria
*/
function buscarTodosProdutos(mysqli $conn): array
{
    if (!$conn) {
        return [];
    }

    $cardapio = [];
    $icones = [
        'PRENSADOS' => '🥖',
        'HOT DOGS' => '🌭',
        'LANCHES GOURMET' => '✨'
    ];

    $sql = "SELECT p.id_produto, p.nome_lanches, p.preco, 
                   p.popular, p.ingredientes, c.nome_categoria
            FROM produto p
            JOIN categoria c ON p.id_categoria = c.id_categoria
            ORDER BY c.id_categoria, p.id_produto";

    $res = $conn->query($sql);

    while ($linha = $res->fetch_assoc()) {
        $categoria = strtoupper($linha['nome_categoria']);

        if (!isset($cardapio[$categoria])) {
            $cardapio[$categoria] = [
                'secao_titulo' => $categoria,
                'secao_icon'   => $icones[$categoria] ?? '🍔',
                'lanches'      => []
            ];
        }

        // Converte o campo 'popular' do banco para verdadeiro (true) ou falso (false) de fato
        $ehPopular = ($linha['popular'] == 1 || $linha['popular'] === '1' || $linha['popular'] === true);

        $cardapio[$categoria]['lanches'][] = [
            'num'          => $categoria,
            'nome'         => $linha['nome_lanches'],
            'preco'        => (float) $linha['preco'],
            'ingredientes' => $linha['ingredientes'] ?? '',
            'popular'      => $ehPopular
        ];
    }

    return array_values($cardapio);
}
