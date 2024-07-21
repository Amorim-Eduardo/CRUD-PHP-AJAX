<?php 

session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <meta http-equiv="Cache-Control" content="no-store" />

    <link rel="stylesheet" href="../css/cadastro_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/inserir_dados.js"></script>

    <style>
        /* Estilos para borda vermelha */
        <?php
        // Verifica se há erros passados na URL
        if (isset($_GET['errors'])) {
            // Decodifica os estilos CSS codificados em JSON
            $styles = json_decode(urldecode($_GET['errors']), true);

            // Aplica os estilos de borda vermelha aos campos com erro
            foreach ($styles as $campo => $style) {
                echo "#$campo { $style }";
            }
        }
        ?>
    </style>
</head>

<body>
    
 <div class="container">

 <form class="form_dados_pessoais" id="form_cadastro">
        <fieldset>

            <div>
                <label for="nome">Nome</label>
                <input type="text" class="inputs_texto" id="nome" name="nome"  value="<?php echo isset($_SESSION['valid_values']['nome']) ? $_SESSION['valid_values']['nome'] : ''; ?>">
            </div>

            <div>
                <label for="cpf">CPF</label>
                <input type="text" class="inputs_texto" id="cpf" name="cpf"  value="<?php echo isset($_SESSION['valid_values']['cpf']) ? $_SESSION['valid_values']['cpf'] : ''; ?>">
            </div>

            <div>
                <label for="email">E-mail</label>
                <input type="text" class="inputs_texto" id="email" name="email"  value="<?php echo isset($_SESSION['valid_values']['email']) ? $_SESSION['valid_values']['email'] : ''; ?>">
            </div>

            <div>
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" class="inputs_texto" id="data_nascimento" name="data_nascimento"  value="<?php echo isset($_SESSION['valid_values']['data_nascimento']) ? $_SESSION['valid_values']['data_nascimento'] : ''; ?>">
            </div>

            <div id="btn-op">
                <button type="submit" id="cadastrar">Cadastrar</button>
                <a href="../../index/index.php"><div id="voltar">Voltar</div></a>
            </div>

        </fieldset>
    </form> 

 </div>

</body>


</html>