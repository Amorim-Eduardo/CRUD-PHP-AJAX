<?php 

session_start();
$_SESSION = [];
session_destroy();

?>

<!DOCTYPE html>
<html lang="ptbr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <meta http-equiv="Cache-Control" content="no-store" />
    <link rel="stylesheet" href="index_style.css">
</head>


<body>
    
    <div class="button-container">
        <a href="../cadastro_usuario/cadastro.php"><div class="btn" id="cadastrar">Cadastrar</div></a>
        <a href="../editar_usuario/editar.php"><div class="btn" id="editar">Editar dados</div></a>
       <a href="../excluir_usuario/excluir.php"><div class="btn" id="remover">Remover</div></a>
    </div>

</body>
</html>