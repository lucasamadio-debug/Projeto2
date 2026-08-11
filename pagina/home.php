<?php
require_once "include/coneçao.php";

$aba = $_GET["aba"] ?? "lanches";
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
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        $id = intval($_GET["id"]);
        $conn->query("DELETE FROM usuarios WHERE id_usuario = $id");
        header("Location: index.php?param=admin&aba=usuarios&msg=excluido");
        exit;
    }

    if ($_POST) {
        $id = $_POST["id_usuario"] ?? "";
        $nome = $_POST["nome"] ?? "";
        $email = $_POST["email"] ?? "";
        $senha = $_POST["senha"] ?? "";

        if (empty($id)) {
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
        } else {
            $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha' WHERE id_usuario = $id";
        }
        $conn->query($sql);
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
            <a href="index.php?param=admin&aba=lanches" class="text-white text-decoration-none me-3 <?php echo $aba == 'lanches' ? 'fw-bold border-bottom' : ''; ?>">Lanches</a>
            <a href="index.php?param=admin&aba=categorias" class="text-white text-decoration-none me-3 <?php echo $aba == 'categorias' ? 'fw-bold border-bottom' : ''; ?>">Categorias</a>
            <a href="index.php?param=admin&aba=usuarios" class="text-white text-decoration-none <?php echo $aba == 'usuarios' ? 'fw-bold border-bottom' : ''; ?>">Usuários</a>
        </div>
        <div>
            <span class="me-3">Olá, <strong><?php echo $_SESSION["thiagolanche"]["nome"] ?? "Admin"; ?></strong></span>
            <a href="pagina/sair.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </div>
</div>

<div class="container mb-5">

  
    <!--DASHBOARD-->
   
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <div class="card bg-white border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Total de Lanches</small>
                        <h2 id="dash-total" class="m-0 text-primary">0</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-2">
            <div class="card bg-white border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Preço Médio Lanche</small>
                        <h2 id="dash-media" class="m-0 text-success">R$ 0,00</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--GERENCIAMENTO DE LANCHES -->
    <?php if ($aba == "lanches"): ?>
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
                                        <!-- BOTÃO ALTERADO PARA DISPARAR O SWEETALERT2 -->
                                        <button class="btn btn-danger btn-sm ms-1 btn-deletar" data-nome="<?php echo $row['nome_lanches']; ?>" data-url="index.php?param=admin&aba=lanches&acao=excluir&id=<?php echo $row['id_produto']; ?>">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    
    <!--GERENCIAMENTO DE CATEGORIAS -->
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

    <!--GERENCIAMENTO DE USUÁRIOS -->
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


<!--DASHBOARD e SWEETALERT2-->

<script>
document.addEventListener('DOMContentLoaded', function() {

    //CONSUMO DA API VIA FETCH + ASYNC/AWAIT + REDUCE / FILTER 
    async function carregarDashboard() {
        try {
        // LINHA CORRIGIDA:
            const resposta = await fetch('api/produtos.php');
            const json = await resposta.json();

            if (json.sucesso && json.dados) {
                // flatMap unifica todos os lanches das seções num único Array
                const todosLanches = json.dados.flatMap(secao => secao.lanches);

                // USO DO REDUCE: Calcula a soma total dos preços para obter a média
                const somaPrecos = todosLanches.reduce((acumulador, item) => acumulador + item.preco, 0);
                const media = todosLanches.length > 0 ? (somaPrecos / todosLanches.length) : 0;

                // Filtra os produtos marcados como populares
                const populares = todosLanches.filter(item => item.popular === true);

                // Atualização dos cards no DOM
                document.getElementById('dash-total').innerText = todosLanches.length;
                document.getElementById('dash-media').innerText = 'R$ ' + media.toFixed(2).replace('.', ',');
            }
        } catch (erro) {
            console.error("Erro ao carregar os dados do Dashboard:", erro);
        }
    }

    carregarDashboard();

    //CONFIRMAÇÃO DE EXCLUSÃO ESTILIZADA COM SWEETALERT2 
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

    // RETORNO DE SUCESSO AO SALVO OU EXCLUÍDO
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