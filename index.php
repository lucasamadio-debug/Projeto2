<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão com o banco de dados
require_once "include/coneçao.php";

// PROCESSA O LOGIN QUANDO O FORMULÁRIO É ENVIADO
if (!isset($_SESSION["thiagolanche"]) && $_POST) {
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");

    if (!empty($email) && !empty($senha)) {
        $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha' LIMIT 1";
        $res = $conn->query($sql);

        if ($res && $res->num_rows > 0) {
            $usuario = $res->fetch_assoc();
            $_SESSION["thiagolanche"] = $usuario;
            
            // Redireciona para recarregar e abrir a home do admin
            header("Location: index.php?param=admin");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imagens/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Oswald:wght@200..700&family=Outfit:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Thiago Lanches</title>
</head>

<body>

<?php include "include/header.php"; ?>

<?php
if (isset($_GET["param"])) {
    $p = explode("/", $_GET["param"]);

    if ($p[0] == "sobre") {
        include "pagina/sobre.php";
    } else if ($p[0] == "cardapio") {
        include "pagina/cardapio.php";
    } else if ($p[0] == "admin" || $p[0] == "login") {
        
        // SE NÃO ESTIVER LOGADO -> EXIBE LOGIN
        if (!isset($_SESSION["thiagolanche"])) {
            include "pagina/login.php";
        } 
        // SE ESTIVER LOGADO -> EXIBE A HOME DO ADMIN
        else {
            include "pagina/home.php";
        }

    } else {
        include "pagina/erro.php";
    }

} else { ?>

    <section class="banner-fundo">
     <h1>Thiago lanches o melhor de Araruna!</h1>
      <p>Os melhores lanches da cidade, com ingredientes frescos e sabor inconfundível que aquece o coração!</p>
     <button><a href="index.php?param=cardapio">Cardápio</a></button>
   </section>

    <main>
        <div id="grid">
            <div class="texto1">
                <h2>🥖<br>Ingredientes frescos</h2>
                <p>Utilizamos apenas os melhores ingredientes para garantir o sabor e a qualidade dos nossos lanches.</p>
            </div>
            <div class="texto2">
                <h2>🔥<br>Prensados na hora</h2>
                <p>Seus lanches são preparados e prensados fresquinhos para você se deliciar com um pedaço do paraiso</p>
            </div>
            <div class="texto3">
                <h2>🏍️<br>Entrega</h2>
                <p>Entregamos seus lanches com rapidez e eficiência, garantindo que cheguem frescos e saborosos.</p>
            </div>
        </div>

        <div id="pedido">
            <h2>Veja seu pedido agora e aproveite!</h2>
            <p>Os melhores lanches da cidade estao aqui no Thiago Lanches!</p>
            <button><a href="index.php?param=cardapio">Cardápio</a></button>
        </div>

        <!-- Carrossel de Lanches -->
  <div id="carrosselLanches" class="carousel slide" data-bs-ride="carousel">
  
  <!-- Slides -->
  <div class="carousel-inner">
    
    <!-- fotos 1 -->
    <div class="carousel-item active">
      <div class="d-flex justify-content-center gap-4 py-4">
        <div class="card" style="width: 200px;">
          <img src="imagens/x-salada.jpg" class="card-img-top" alt="X-Salada">
          <div class="card-body text-center">
            <h5 class="card-title">X-Salada</h5>
            <p class="card-text text-orange fw-bold">R$ 22,00</p>
          </div>
        </div>
        <div class="card" style="width: 200px;">
          <img src="imagens/x-bacon.png" class="card-img-top" alt="X-Bacon">
          <div class="card-body text-center">
            <h5 class="card-title">X-Bacon</h5>
            <p class="card-text text-orange fw-bold">R$ 30,00</p>
          </div>
        </div>
        <div class="card" style="width: 200px;">
          <img src="imagens/x-tudo.png" class="card-img-top" alt="X-Tudo">
          <div class="card-body text-center">
            <h5 class="card-title">X-Tudo</h5>
            <p class="card-text text-orange fw-bold">R$ 55,00</p>
          </div>
        </div>
      </div>
    </div>

    <!-- fotos 2 -->
    <div class="carousel-item">
      <div class="d-flex justify-content-center gap-4 py-4">
        <div class="card" style="width: 200px;">
          <img src="imagens/dog-carne.png" class="card-img-top" alt="Dog Carne">
          <div class="card-body text-center">
            <h5 class="card-title">Dog Carne</h5>
            <p class="card-text text-orange fw-bold">R$ 22,00</p>
          </div>
        </div>
        <div class="card" style="width: 200px;">
          <img src="imagens/dog-bacon.png" class="card-img-top" alt="Dog Bacon">
          <div class="card-body text-center">
            <h5 class="card-title">Dog Bacon</h5>
            <p class="card-text text-orange fw-bold">R$ 27,00</p>
          </div>
        </div>
        <div class="card" style="width: 200px;">
          <img src="imagens/smash-burguer.png" class="card-img-top" alt="Smash Burguer">
          <div class="card-body text-center">
            <h5 class="card-title">Smash Burguer</h5>
            <p class="card-text text-orange fw-bold">R$ 16,00</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Setas -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carrosselLanches" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carrosselLanches" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>

</main>

<?php } ?>

<?php include "include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>