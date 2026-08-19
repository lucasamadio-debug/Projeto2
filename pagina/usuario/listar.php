<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h3 class="m-0">Cadastro de Usuários Admin</h3>
        <a href="index.php?param=admin&aba=usuarios&acao=novo" class="btn btn-success btn-sm">Novo Usuário</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th class="text-end pe-3">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM usuarios ORDER BY id_usuario DESC");
                if ($res && $res->num_rows > 0):
                    while ($row = $res->fetch_assoc()):
                ?>
                    <tr>
                        <td class="ps-3 fw-bold"><?php echo $row['id_usuario']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td class="text-end pe-3">
                            <a href="index.php?param=admin&aba=usuarios&acao=editar&id=<?php echo $row['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm ms-1 btn-deletar" data-nome="<?php echo $row['nome']; ?>" data-url="index.php?param=admin&aba=usuarios&acao=excluir&id=<?php echo $row['id_usuario']; ?>">Excluir</button>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>