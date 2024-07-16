<?php
session_start();

include '../classes/pessoa.php';

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializa um array para armazenar os erros
    $errors = [];

    // Cria um objeto Pessoa
    $pessoa = new Pessoa($_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['data_nascimento']);

    $nome = $pessoa->getNome();
    $cpf = $pessoa->getCpf();
    $email = $pessoa->getEmail();
    $data_nascimento = $pessoa->getDataNascimento();

    // Validação dos campos
    if (empty($pessoa->getNome())) {
        $errors['nome'] = "O campo Nome é obrigatório.";
    }

    if (empty($pessoa->getCpf())) {
        $errors['cpf'] = "O campo CPF é obrigatório.";
    }

    if (empty($pessoa->getEmail())) {
        $errors['email'] = "O campo E-mail é obrigatório.";
    } elseif (!filter_var($pessoa->getEmail(), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail inválido.";
    }

    if (empty($pessoa->getDataNascimento())) {
        $errors['data_nascimento'] = "O campo Data de Nascimento é obrigatório.";
    }

    // Se houver erros, retorna uma resposta com os erros
    if (!empty($errors)) {
        $_SESSION['nome'] = $pessoa->getNome();
        $_SESSION['cpf'] = $pessoa->getCpf();
        $_SESSION['email'] = $pessoa->getEmail();
        $_SESSION['data_nascimento'] = $pessoa->getDataNascimento();

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
            $response = [
                'success' => false,
                'message' => 'Falha na conexão: ' . $conn->connect_error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM dados_pessoais WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao comunicar com o banco: ' . $stmt->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Verifica se encontrou algum registro
            if ($row) {
                $response = [
                    'success' => false,
                    'message' => 'CPF já cadastrado!!!'
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }

        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO dados_pessoais (nome, cpf, email, data_nascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cpf, $email, $data_nascimento);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao inserir no banco: ' . $stmt->error
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $response = [
                'success' => true,
                'message' => 'Dados inseridos com sucesso!!!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }

        $stmt->close();
        $conn->close();
        exit;
    }
}
?>
