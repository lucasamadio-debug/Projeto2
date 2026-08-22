<?php
require_once "include/coneçao.php";

$aba = $_GET["aba"] ?? "dashboard";
$mensagem = "";

// CAPTURA MENSAGEM DE PARÂMETRO DA URL PARA ALERTA
$msgStatus = $_GET["msg"] ?? "";

// LANCHES
if ($aba == "lanches") {
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        include "pagina/lanche/excluir.php";
    }

    if ($_POST) {
        include "pagina/lanche/salvar.php";
    }
}


// USUÁRIOS 
if ($aba == "usuarios") {
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        include "pagina/usuario/excluir.php";
    }

    if ($_POST) {
        include "pagina/usuario/salvar.php";
    }
}


// CATEGORIAS
if ($aba == "categorias") {
    if (isset($_GET["acao"]) && $_GET["acao"] == "excluir") {
        include "pagina/categoria/excluir.php";
    }

    if ($_POST) {
        include "pagina/categoria/salvar.php";
    }
}


// ESTOQUE
if ($aba == "estoque") {
    if ($_POST) {
        include "pagina/estoque/salvar.php";
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
        <?php
        if ($modoNovo || $itemEditar) {
            include "lanche/cadastrar.php";
        } else {
            include "lanche/listar.php";
        }
        ?>

    <!-- GERENCIAMENTO DE CATEGORIAS -->
    <?php elseif ($aba == "categorias"): ?>
        <?php
        if ($modoNovo || $itemEditar) {
            include "pagina/categoria/cadastrar.php";
        } else {
            include "pagina/categoria/listar.php";
        }
        ?>

    <!-- GERENCIAMENTO DE ESTOQUE -->
    <?php elseif ($aba == "estoque"): ?>
        <?php
        if ($itemEditar) {
            include "pagina/estoque/cadastrar.php";
        } else {
            include "pagina/estoque/listar.php";
        }
        ?>

    <!-- GERENCIAMENTO DE USUÁRIOS -->
    <?php elseif ($aba == "usuarios"): ?>
        <?php
        if ($modoNovo || $itemEditar) {
            include "pagina/usuario/cadastrar.php";
        } else {
            include "pagina/usuario/listar.php";
        }
        ?>

    <?php endif; ?>

</div>

<!-- CONFIRMAÇÃO DE EXCLUSÃO-->
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