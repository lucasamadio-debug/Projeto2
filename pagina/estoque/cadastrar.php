<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="m-0">Editar Estoque</h3>
    </div>
    <div class="card-body">
        <?php
        $resProd = $conn->query("SELECT nome_lanches FROM produto WHERE id_produto = " . intval($itemEditar['id_produto']));
        $nomeProduto = $resProd ? ($resProd->fetch_assoc()['nome_lanches'] ?? '') : '';
        ?>
        <form method="post" action="index.php?param=admin&aba=estoque">
            <input type="hidden" name="id_estoque" value="<?php echo $itemEditar['id_estoque']; ?>">
            <div class="mb-3">
                <label class="form-label">Produto:</label>
                <input type="text" class="form-control" value="<?php echo $nomeProduto; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantidade em estoque:</label>
                <input type="number" name="quantidade" class="form-control" min="0" required value="<?php echo $itemEditar['quantidade']; ?>">
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php?param=admin&aba=estoque" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>