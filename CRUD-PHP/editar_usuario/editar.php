<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar dados</title>
    <meta http-equiv="Cache-Control" content="no-store" />
    <link rel="stylesheet" href="editar_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="editar_dados.js"></script>
</head>


<body>
    
   
<div class="container">

    <form class="form_dados_pessoais" id="form_editar">
        <fieldset >

            <div>
                <label for="nome">Nome</label>
                <input disabled  type="text" class="inputs_texto" id="nome" name="nome"  value="<?php echo isset($_SESSION['valid_values']['nome']) ? $_SESSION['valid_values']['nome'] : ''; ?>">
            </div>

            <div>
                <label for="cpf">CPF</label>
                <div id="busca_cep">
                    <input style="margin-left: 40px;" type="text" class="inputs_texto" id="cpf" name="cpf"  value="<?php echo isset($_SESSION['valid_values']['cpf']) ? $_SESSION['valid_values']['cpf'] : ''; ?>">
                    <button id="pesquisar_cpf">🔍</button>
                </div>
            </div>

            <div>
                <label for="email">E-mail</label>
                <input disabled type="text" class="inputs_texto" id="email" name="email"  value="<?php echo isset($_SESSION['valid_values']['email']) ? $_SESSION['valid_values']['email'] : ''; ?>">
            </div>

            <div>
                <label for="data_nascimento">Data de Nascimento</label>
                <input disabled type="date" class="inputs_texto" id="data_nascimento" name="data_nascimento"  value="<?php echo isset($_SESSION['valid_values']['data_nascimento']) ? $_SESSION['valid_values']['data_nascimento'] : ''; ?>">
            </div>

            <div id="btn-op">
                <button type="submit" id="atualizar" class="estilo_submissao">Atualizar</button>
                <a href="../index/index.php"><div id="voltar">Voltar</div></a>
            </div>

        </fieldset>
    </form> 

    </div>

</body>


</html>