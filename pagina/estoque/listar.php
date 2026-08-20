<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h3 class="m-0">Controle de Estoque</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Produto</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th class="text-end pe-3">Opções</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT e.id_estoque, e.quantidade, p.nome_lanches, c.nome_categoria
                        FROM estoque e
                        JOIN produto p ON e.id_produto = p.id_produto
                        JOIN categoria c ON p.id_categoria = c.id_categoria
                        WHERE UPPER(c.nome_categoria) = 'BEBIDAS'
                        ORDER BY p.nome_lanches";
                $res = $conn->query($sql);
                if ($res && $res->num_rows > 0):
                    while ($row = $res->fetch_assoc()):
                        $corBadge = $row['quantidade'] < 5 ? 'bg-danger' : 'bg-success';
                ?>
                    <tr>
                        <td class="ps-3 fw-bold"><?php echo $row['nome_lanches']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $row['nome_categoria']; ?></span></td>
                        <td><span class="badge <?php echo $corBadge; ?>"><?php echo $row['quantidade']; ?> un.</span></td>
                        <td class="text-end pe-3">
                            <a href="index.php?param=admin&aba=estoque&acao=editar&id=<?php echo $row['id_estoque']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>