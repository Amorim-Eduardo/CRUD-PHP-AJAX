<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['nome']) && !empty($_POST['cpf']) && !empty($_POST['email']) && !empty($_POST['data_nascimento'])) {

        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];
        $data_nascimento = $_POST['data_nascimento'];

        $host = 'localhost';
        $usuario = 'root'; 
        $senha = ''; 
        $banco = 'crud_ajax';

        $conn = new mysqli($host, $usuario, $senha, $banco);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE dados_pessoais SET nome = ?, cpf = ?, email = ?, data_nascimento = ? WHERE cpf = ?");
        $stmt->bind_param("sssss", $nome, $cpf, $email, $data_nascimento, $cpf);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao alterar dados no banco!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);

        }else {

            $response = [
                'success' => true,
                'message' => 'Dados alterados com sucesso!!!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            
        }
    } else {
    // método de requisição inválido
    $response = [
        'success' => false,
        'message' => 'Dados incompletos!!!'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    }
}

?>