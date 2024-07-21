<?php

include '../../classes/pessoa_juridica.php'; // Inclua a classe PessoaJuridica
include '../../classes/database.php'; // Inclua a classe Database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['cnpj']) && !empty($_POST['cnpj'])) {

        $cnpj = $_POST['cnpj'];

        $database = new Database();
        $conn = $database->getConnection();

        // Verifica a conexão
        if ($conn->connect_error) {
            $response = [
                'success' => false,
                'message' => 'Falha na conexão: ' . $conn->connect_error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM pessoa_juridica WHERE cnpj = ?");

        
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

        $stmt->bind_param("s", $cnpj);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao buscar dados no banco: ' . $stmt->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
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
                $pessoa = new PessoaJuridica($row['nome'], $row['email'], $row['data_nascimento'], $row['cnpj']);
                $response = [
                    'success' => true,
                    'message' => 'Usuário encontrado',
                    'nome' => $pessoa->getNome(),
                    'cnpj' => $pessoa->getCnpj(),
                    'email' => $pessoa->getEmail(),
                    'data_nascimento' => $pessoa->getDataNascimento()
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        $stmt->close();
        $conn->close();
        
    } else {
        // CNPJ não foi fornecido na requisição
        $response = [
            'success' => false,
            'message' => 'CNPJ não informado!'
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
