<div class="login">
    <div class="card shadow">
        <div class="card-header text-center">
            <img src="imagens/logo5.png" alt="Thiago Lanches" class="w-100" style="max-width: 150px;">
        </div>
        <div class="card-body">
            <form name="formLogin" method="post" action="index.php?param=admin">
                <label for="email">E-mail:</label>
                <input type="text" name="email" id="email" required class="form-control">
                
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required class="form-control">
                
                <br>
                <button type="submit" class="btn btn-success w-100">
                    Realizar Login
                </button>
            </form>
        </div>
    </div>
</div>