<?php

/**
 * BANCO DE DADOS
 */
require_once "include/coneçao.php";
require_once "include/funcoes.php";

$cardapio_completo = buscarTodosProdutos($conn);

/**
 * FUNÇÃO DE FILTRO
 */
function filtrarPopulares(array $secoes): array {
    if (empty($secoes)) {
        return [];
    }
    $resultado = [];
    foreach ($secoes as $secao) {
        $populares = [];
        foreach ($secao['lanches'] as $lanche) {
            // Aceita tanto booleano true quanto 1 ou "1"
            if (isset($lanche['popular']) && ($lanche['popular'] === true || $lanche['popular'] == 1)) {
                $populares[] = $lanche;
            }
        }
        if (!empty($populares)) {
            $secao['lanches'] = $populares;
            $resultado[] = $secao;
        }
    }
    return $resultado;
}

$mostrarPopulares = false;
$cardapioParaExibir = $cardapio_completo;

if (isset($_GET['populares']) && $_GET['populares'] === 'sim') {
    $mostrarPopulares = true;
    $cardapioParaExibir = filtrarPopulares($cardapio_completo);
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imagens/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cardapio.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400..700&family=Inter:wght@100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Thiago Lanches - Cardápio</title>
</head>

<body>

<div class="container menu-conteudo my-5">

    <!-- Botão de filtro -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <?php if (!$mostrarPopulares): ?>
            <a href="?populares=sim" class="btn-filtrar">⭐ Favoritos</a>
        <?php else: ?>
            <span style="font-family:'Oswald'; color:#ff6600;">⭐ Lanches favoritos</span>
            <a href="?" class="btn btn-sm btn-outline-secondary" style="border-radius:10px; background-color:#fff0e6; color:#ff6600;">
                ✖ Ver todos
            </a>
        <?php endif; ?>
    </div>

    <!-- DADOS DO CARDÁPIO PUXADOS DO BANCO -->
    <?php foreach ($cardapioParaExibir as $secao): ?>
        <div class="menu-secao mb-5">
            <div class="secao-header d-flex align-items-center mb-4">
                <span class="secao-icon"><?php echo $secao['secao_icon']; ?></span>
                <h2 class="secao-title ms-3"><?php echo $secao['secao_titulo']; ?></h2>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($secao['lanches'] as $lanche): ?>
                    <div class="col">
                        <div class="card card-menu h-100 shadow-sm border-0 position-relative">
                            <div class="card-body d-flex flex-column justify-content-between p-4">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="lanche-num"><?php echo $lanche['num']; ?></span>
                                    <?php if (isset($lanche['popular']) && ($lanche['popular'] === true || $lanche['popular'] == 1)): ?>
                                        <span class="badge badge-popular">POPULAR</span>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-4">
                                    <h4 class="lanche-nome"><?php echo $lanche['nome']; ?></h4>
                                    <p class="lanche-ingredientes card-text"><?php echo $lanche['ingredientes']; ?></p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                    <span class="lanche-preco">R$ <?php echo number_format($lanche['preco'], 2, ',', '.'); ?></span>
                                    <a href="https://wa.me/5544998340641?text=Olá, gostaria de pedir <?php echo urlencode($lanche['nome']); ?>"
                                       target="_blank" class="seta-link text-decoration-none">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>