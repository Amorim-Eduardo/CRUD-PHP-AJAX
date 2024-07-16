<?php

include '../classes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['cpf'])) {

        $cpf = $_POST['cpf'];

        $database = new Database();
        $conn = $database->getConnection();

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("DELETE FROM dados_pessoais WHERE cpf = ?");
        $stmt->bind_param("s",$cpf);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao excluir dados!!!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);

        }else {

            $response = [
                'success' => true,
                'message' => 'Usuário excluido com sucesso!!!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            
        }
    } else {
    // método de requisição inválido
    $response = [
        'success' => false,
        'message' => 'Erro no CPF!!!'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    }
}

?>