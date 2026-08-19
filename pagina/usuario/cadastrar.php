<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h3 class="m-0"><?php echo $itemEditar ? "Editar Usuário" : "Cadastrar Novo Usuário"; ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="index.php?param=admin&aba=usuarios">
            <input type="hidden" name="id_usuario" value="<?php echo $itemEditar['id_usuario'] ?? ''; ?>">
            <div class="mb-3">
                <label class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" required value="<?php echo $itemEditar['nome'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail:</label>
                <input type="email" name="email" class="form-control" required value="<?php echo $itemEditar['email'] ?? ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Senha:</label>
                <?php if ($itemEditar): ?>
                    <input type="password" name="senha" class="form-control" placeholder="Deixe em branco pra manter a senha atual">
                <?php else: ?>
                    <input type="password" name="senha" class="form-control" required>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="index.php?param=admin&aba=usuarios" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>