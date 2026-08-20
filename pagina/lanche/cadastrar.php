<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="m-0"><?php echo $itemEditar ? "Editar Lanche" : "Cadastrar Novo Lanche"; ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="index.php?param=admin&aba=lanches">
            <input type="hidden" name="id_produto" value="<?php echo $itemEditar['id_produto'] ?? ''; ?>">
            <div class="mb-3">
                <label class="form-label">Nome do Lanche:</label>
                <input type="text" name="nome_lanches" class="form-control" required value="<?php echo $itemEditar['nome_lanches'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria:</label>
                <select name="categoria_id" class="form-control" required>
                    <?php
                    $resCat = $conn->query("SELECT * FROM categoria");
                    if ($resCat && $resCat->num_rows > 0) {
                        while ($cat = $resCat->fetch_assoc()) {
                            $idCat = $cat['id_categoria'] ?? $cat['id'] ?? 1;
                            $nomeCat = $cat['nome'] ?? $cat['nome_categoria'] ?? 'Categoria';
                            $selected = (isset($itemEditar['id_categoria']) && $itemEditar['id_categoria'] == $idCat) ? 'selected' : '';
                            echo "<option value='{$idCat}' {$selected}>{$nomeCat}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Preço (R$):</label>
                <input type="text" name="preco" class="form-control" required value="<?php echo $itemEditar['preco'] ?? ''; ?>">
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php?param=admin&aba=lanches" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>