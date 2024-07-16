<?php

include '../classes/pessoa.php';
include '../classes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['cpf']) && $_POST['cpf'] != '') {

        $cpf = $_POST['cpf'];

        
        $database = new Database();
        $conn = $database->getConnection();

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM dados_pessoais WHERE cpf = ?");
        
        // Verifica se a preparação da consulta ocorreu sem erros
        if ($stmt === false) {
            $response = [
                'success' => false,
                'message' => 'Erro na preparação da consulta: ' . $conn->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("s", $cpf);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao buscar dados no banco: ' . $stmt->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);

        } else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Verifica se encontrou algum registro
            if (!$row) {
                $response = [
                    'success' => false,
                    'message' => 'Usuário não encontrado!'
                ];
            } else {
                $pessoa = new Pessoa($row['nome'], $row['cpf'], $row['email'], $row['data_nascimento']);
                $response = [
                    'success' => true,
                    'message' => 'Usuário encontrado',
                    'nome' => $pessoa->getNome(),
                    'cpf' => $pessoa->getCpf(),
                    'email' => $pessoa->getEmail(),
                    'data_nascimento' => $pessoa->getDataNascimento()
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        // CPF não foi fornecido na requisição
        $response = [
            'success' => false,
            'message' => 'CPF não informado!'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Método de requisição inválido
    $response = [
        'success' => false,
        'message' => 'Método de requisição inválido!'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
