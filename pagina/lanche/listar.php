<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h3 class="m-0">Cadastro de Lanches</h3>
        <a href="index.php?param=admin&aba=lanches&acao=novo" class="btn btn-success btn-sm">Cadastrar Novo</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Nome do Lanche</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th class="text-end pe-3">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.*, c.* FROM produto p LEFT JOIN categoria c ON p.id_categoria = c.id_categoria ORDER BY p.id_produto DESC";
                $res = $conn->query($sql);
                if ($res && $res->num_rows > 0):
                    while ($row = $res->fetch_assoc()):
                        $nomeCat = $row['nome'] ?? $row['nome_categoria'] ?? 'Geral';
                ?>
                    <tr>
                        <td class="ps-3 fw-bold"><?php echo $row['id_produto']; ?></td>
                        <td><?php echo $row['nome_lanches']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $nomeCat; ?></span></td>
                        <td>R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                        <td class="text-end pe-3">
                            <a href="index.php?param=admin&aba=lanches&acao=editar&id=<?php echo $row['id_produto']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm ms-1 btn-deletar" data-nome="<?php echo $row['nome_lanches']; ?>" data-url="index.php?param=admin&aba=lanches&acao=excluir&id=<?php echo $row['id_produto']; ?>">Excluir</button>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>