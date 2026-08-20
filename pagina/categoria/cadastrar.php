<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="m-0"><?php echo $itemEditar ? "Editar Categoria" : "Cadastrar Nova Categoria"; ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="index.php?param=admin&aba=categorias">
            <input type="hidden" name="id_categoria" value="<?php echo $itemEditar['id_categoria'] ?? ''; ?>">
            <div class="mb-3">
                <label class="form-label">Nome da Categoria:</label>
                <input type="text" name="nome" class="form-control" required value="<?php echo $itemEditar['nome'] ?? $itemEditar['nome_categoria'] ?? ''; ?>" placeholder="Ex: Bebidas, Sobremesas...">
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php?param=admin&aba=categorias" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>