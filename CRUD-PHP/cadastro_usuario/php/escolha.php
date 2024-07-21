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
    <link rel="stylesheet" href="../css//escolha_style.css">
</head>


<body>
    
    <div class="button-container">
        <a href="cadastroF.php"><div class="btn" id="cadastrar">Pessoa física</div></a>
        <a href="cadastroJ.php"><div class="btn" id="editar">Pessoa jurídica</div></a>
    </div>

</body>
</html>