<?php
require_once "include/coneçao.php";

$aba = $_GET["aba"] ?? "dashboard";
$mensagem = "";

// CAPTURA MENSAGEM DE PARÂMETRO DA URL PARA ALERTA
$msgStatus = $_GET["msg"] ?? "";

// LANCHES
if ($aba == "lanches") {
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        $id = intval($_GET["id"]);
        $conn->query("DELETE FROM produto WHERE id_produto = $id");
        header("Location: index.php?param=admin&aba=lanches&msg=excluido");
        exit;
    }

    if ($_POST) {
        $id = $_POST["id_produto"] ?? "";
        $nome = $_POST["nome_lanches"] ?? "";
        $preco = $_POST["preco"] ?? "";
        $categoria = $_POST["categoria_id"] ?? 1;

        if (empty($id)) {
            $sql = "INSERT INTO produto (nome_lanches, preco, id_categoria) VALUES ('$nome', '$preco', '$categoria')";
        } else {
            $sql = "UPDATE produto SET nome_lanches = '$nome', preco = '$preco', id_categoria = '$categoria' WHERE id_produto = $id";
        }
        $conn->query($sql);
        header("Location: index.php?param=admin&aba=lanches&msg=salvo");
        exit;
    }
}


// USUÁRIOS
if ($aba == "usuarios") {
    if (isset($_GET["action"]) && $_GET["action"] == "excluir") {
        $id = intval($_GET["id"]);
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: index.php?param=admin&aba=usuarios&msg=excluido");
        exit;
    }

    if ($_POST) {
        $id = $_POST["id_usuario"] ?? "";
        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $senhaPura = trim($_POST["senha"] ?? "");

        if (empty($id)) {
            $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $senhaHash);
        } else {
            $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id_usuario = ?");
            $stmt->bind_param("sssi", $nome, $email, $senhaHash, $id);
        }
        $stmt->execute();
        header("Location: index.php?param=admin&aba=usuarios&msg=salvo");
        exit;
    }
}


// CATEGORIAS
if ($aba == "categorias") {
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        $id = intval($_GET["id"]);
        $conn->query("DELETE FROM categoria WHERE id_categoria = $id");
        header("Location: index.php?param=admin&aba=categorias&msg=excluido");
        exit;
    }

    if ($_POST) {
        $id = $_POST["id_categoria"] ?? "";
        $nome = $_POST["nome"] ?? "";

        if (empty($id)) {
            $sql = "INSERT INTO categoria (nome_categoria) VALUES ('$nome')";
        } else {
            $sql = "UPDATE categoria SET nome_categoria = '$nome' WHERE id_categoria = $id";
        }
        $conn->query($sql);
        header("Location: index.php?param=admin&aba=categorias&msg=salvo");
        exit;
    }
}


// ESTOQUE
if ($aba == "estoque") {
    if ($_POST) {
        $id = intval($_POST["id_estoque"] ?? 0);
        $quantidade = intval($_POST["quantidade"] ?? 0);

        $stmt = $conn->prepare("UPDATE estoque SET quantidade = ? WHERE id_estoque = ?");
        $stmt->bind_param("ii", $quantidade, $id);
        $stmt->execute();
        header("Location: index.php?param=admin&aba=estoque&msg=salvo");
        exit;
    }
}

// BUSCA ITEM PARA EDITAR
$itemEditar = null;
if (isset($_GET["acao"]) && $_GET["acao"] == "editar") {
    $id = intval($_GET["id"]);
    if ($aba == "lanches") {
        $res = $conn->query("SELECT * FROM produto WHERE id_produto = $id");
    } else if ($aba == "usuarios") {
        $res = $conn->query("SELECT * FROM usuarios WHERE id_usuario = $id");
    } else if ($aba == "categorias") {
        $res = $conn->query("SELECT * FROM categoria WHERE id_categoria = $id");
    } else if ($aba == "estoque") {
        $res = $conn->query("SELECT * FROM estoque WHERE id_estoque = $id");
    }
    if ($res) $itemEditar = $res->fetch_assoc();
}

$modoNovo = isset($_GET["acao"]) && $_GET["acao"] == "novo";
?>

<!-- IMPORTAÇÃO DO SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- MENU ADMIN -->
<div class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-warning text-dark fs-6 me-3">Thiago Lanches</span>
            <a href="index.php?param=admin&aba=dashboard" class="text-white text-decoration-none me-3 <?php echo $aba == 'dashboard' ? 'fw-bold border-bottom' : ''; ?>">Dashboard</a>
            <a href="index.php?param=admin&aba=lanches" class="text-white text-decoration-none me-3 <?php echo $aba == 'lanches' ? 'fw-bold border-bottom' : ''; ?>">Lanches</a>
            <a href="index.php?param=admin&aba=categorias" class="text-white text-decoration-none me-3 <?php echo $aba == 'categorias' ? 'fw-bold border-bottom' : ''; ?>">Categorias</a>
            <a href="index.php?param=admin&aba=estoque" class="text-white text-decoration-none me-3 <?php echo $aba == 'estoque' ? 'fw-bold border-bottom' : ''; ?>">Estoque</a>
            <a href="index.php?param=admin&aba=usuarios" class="text-white text-decoration-none <?php echo $aba == 'usuarios' ? 'fw-bold border-bottom' : ''; ?>">Usuários</a>
        </div>
        <div>
            <span class="me-3">Olá, <strong><?php echo $_SESSION["thiagolanche"]["nome"] ?? "Admin"; ?></strong></span>
            <a href="pagina/sair.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</div>

<div class="container mb-5">

    <!-- DASHBOARD -->
    <?php if ($aba == "dashboard"): ?>
        <div class="row g-3 mb-4">
          <div class="col-md-2">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">TOTAL DE LANCHES</small>
              <h3 id="dash-total" class="text-primary mt-2">0</h3>
            </div>
          </div>

          <div class="col-md-2">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">PREÇO MÉDIO</small>
              <h3 id="dash-media" class="text-success mt-2">R$ 0,00</h3>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">MAIS CARO</small>
              <h3 id="dash-mais-caro" class="text-danger mt-2">R$ 0,00</h3>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">MAIS BARATO</small>
              <h3 id="dash-mais-barato" class="text-info mt-2">R$ 0,00</h3>
            </div>
          </div>

          <div class="col-md-2">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">CATEGORIAS</small>
              <h3 id="dash-categorias" class="text-warning mt-2">0</h3>
            </div>
          </div>

          <div class="col-md-2">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">BEBIDAS CADASTRADAS</small>
              <h3 id="dash-total-bebidas" class="text-primary mt-2">0</h3>
            </div>
          </div>

          <div class="col-md-2">
            <div class="card p-3 text-center shadow-sm">
              <small class="text-muted fw-bold">ESTOQUE DE BEBIDAS</small>
              <h3 id="dash-estoque-bebidas" class="text-success mt-2">0</h3>
            </div>
          </div>
        </div>


        <script src="dist/dashboard.js"></script>

    <!-- GERENCIAMENTO DE LANCHES -->
    <?php elseif ($aba == "lanches"): ?>
        <?php if ($modoNovo || $itemEditar): ?>
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
        <?php else: ?>
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
        <?php endif; ?>

    <!-- GERENCIAMENTO DE CATEGORIAS -->
    <?php elseif ($aba == "categorias"): ?>
        <?php if ($modoNovo || $itemEditar): ?>
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
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="m-0">Cadastro de Categorias</h3>
                    <a href="index.php?param=admin&aba=categorias&acao=novo" class="btn btn-success btn-sm">Nova Categoria</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Nome da Categoria</th>
                                <th class="text-end pe-3">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM categoria ORDER BY id_categoria DESC");
                            if ($res && $res->num_rows > 0):
                                while ($row = $res->fetch_assoc()):
                                    $nomeCat = $row['nome'] ?? $row['nome_categoria'] ?? 'Sem nome';
                            ?>
                                <tr>
                                    <td class="ps-3 fw-bold"><?php echo $row['id_categoria']; ?></td>
                                    <td><?php echo $nomeCat; ?></td>
                                    <td class="text-end pe-3">
                                        <a href="index.php?param=admin&aba=categorias&acao=editar&id=<?php echo $row['id_categoria']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <button class="btn btn-danger btn-sm ms-1 btn-deletar" data-nome="<?php echo $nomeCat; ?>" data-url="index.php?param=admin&aba=categorias&acao=excluir&id=<?php echo $row['id_categoria']; ?>">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    <!-- GERENCIAMENTO DE ESTOQUE -->
    <?php elseif ($aba == "estoque"): ?>
        <?php if ($itemEditar): ?>
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
        <?php else: ?>
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
        <?php endif; ?>

    <!-- GERENCIAMENTO DE USUÁRIOS -->
    <?php elseif ($aba == "usuarios"): ?>
        <?php if ($modoNovo || $itemEditar): ?>
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
                            <input type="password" name="senha" class="form-control" required value="<?php echo $itemEditar['senha'] ?? ''; ?>">
                        </div>
                        <button type="submit" class="btn btn-success">Salvar</button>
                        <a href="index.php?param=admin&aba=usuarios" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        <?php else: ?>
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
        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- CONFIRMAÇÃO DE EXCLUSÃO E NOTIFICAÇÕES-->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const botoesDeletar = document.querySelectorAll('.btn-deletar');
    
    botoesDeletar.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const urlDestino = this.getAttribute('data-url');
            const itemNome = this.getAttribute('data-nome') || 'este registro';

            Swal.fire({
                title: 'Tem certeza?',
                text: `Deseja realmente excluir "${itemNome}"? Esta ação não pode ser desfeita!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = urlDestino;
                }
            });
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if (msg === 'salvo') {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: 'Registro salvo com sucesso.',
            timer: 2000,
            showConfirmButton: false
        });
    } else if (msg === 'excluido') {
        Swal.fire({
            icon: 'success',
            title: 'Excluído!',
            text: 'Registro removido com sucesso.',
            timer: 2000,
            showConfirmButton: false
        });
    }

});
</script>