<?php
session_start();

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializa um array para armazenar os erros
    $errors = [];

    // Coleta os dados enviados pelo formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];

    // Validação dos campos
    if (empty($nome)) {
        $errors['nome'] = "O campo Nome é obrigatório.";
    }

    if (empty($cpf)) {
        $errors['cpf'] = "O campo CPF é obrigatório.";
    }

    if (empty($email)) {
        $errors['email'] = "O campo E-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido.";
    }

    if (empty($data_nascimento)) {
        $errors['data_nascimento'] = "O campo Data de Nascimento é obrigatório.";
    }

    // Se houver erros, retorna uma resposta com os erros
    if (!empty($errors)) {
        $_SESSION['nome'] = $nome;
        $_SESSION['cpf'] = $cpf;
        $_SESSION['email'] = $email;
        $_SESSION['data_nascimento'] = $data_nascimento;

        // Armazena os erros na sessão
        $_SESSION['errors'] = $errors;

        // Retorna uma resposta em JSON para o AJAX
        $response = [
            'success' => false,
            'errors' => $errors
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {

        $host = 'localhost';
        $usuario = 'root'; 
        $senha = ''; 
        $banco = 'crud_ajax';

        $conn = new mysqli($host, $usuario, $senha, $banco);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO dados_pessoais (nome, cpf, email, data_nascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cpf, $email, $data_nascimento);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao inserir no banco!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'success' => true,
                'message' => 'Dados inseridos com sucesso!!!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
   
        exit;
    }
}
?>
