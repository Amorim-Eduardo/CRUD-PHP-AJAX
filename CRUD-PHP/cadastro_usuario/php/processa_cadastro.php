<?php

session_start();

// Ativa a exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $tipo = '';
    $pessoa = null;

    // Cria a instância da pessoa
    if (isset($_POST['cpf'])) {
        $pessoa = new PessoaFisica($_POST['nome'], $_POST['email'], $_POST['data_nascimento'], $_POST['cpf']);
        $tipo = 'fisica';
    } elseif (isset($_POST['cnpj'])) {
        $pessoa = new PessoaJuridica($_POST['nome'], $_POST['email'], $_POST['data_nascimento'], $_POST['cnpj']);
        $tipo = 'juridica';
    } else {
        $errors['tipo'] = "Nenhum CPF ou CNPJ fornecido.";
    }

    // Validação dos campos
    $nome = $pessoa->getNome();
    $email = $pessoa->getEmail();
    $data_nascimento = $pessoa->getDataNascimento();
    $cpf_cnpj = $tipo === 'fisica' ? $pessoa->getCpf() : $pessoa->getCnpj();

    if (empty($nome)) {
        $errors['nome'] = "O campo Nome é obrigatório.";
    }
    if (empty($cpf_cnpj)) {
        $errors[$tipo === 'fisica' ? 'cpf' : 'cnpj'] = "O campo " . ($tipo === 'fisica' ? "CPF" : "CNPJ") . " é obrigatório.";
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
        $_SESSION['cpf_cnpj'] = $cpf_cnpj;
        $_SESSION['email'] = $email;
        $_SESSION['data_nascimento'] = $data_nascimento;
        $_SESSION['errors'] = $errors;

        $response = [
            'success' => false,
            'errors' => $errors
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Função para inserir os dados no banco de dados
    function inserirPessoa($tipo, $nome, $cpf_cnpj, $email, $data_nascimento) {
        $database = new Database();
        $conn = $database->getConnection();
        if ($conn->connect_error) {
            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . $conn->connect_error
            ];
        }

        $table = $tipo === 'fisica' ? 'pessoa_fisica' : 'pessoa_juridica';
        $idField = $tipo === 'fisica' ? 'cpf' : 'cnpj';

        // Verifica se o CPF/CNPJ já está cadastrado
        $stmt = $conn->prepare("SELECT * FROM $table WHERE $idField = ?");
        $stmt->bind_param("s", $cpf_cnpj);

        if (!$stmt->execute()) {
            return [
                'success' => false,
                'message' => 'Erro ao comunicar com o banco: ' . $stmt->error
            ];
        } else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                return [
                    'success' => false,
                    'message' => 'CPF ou CNPJ já cadastrado!!!'
                ];
            }
        }

        $stmt->close();

        // Insere a pessoa no banco de dados
        $stmt = $conn->prepare("INSERT INTO $table (nome, $idField, email, data_nascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cpf_cnpj, $email, $data_nascimento);

        if (!$stmt->execute()) {
            return [
                'success' => false,
                'message' => 'Erro ao inserir no banco: ' . $stmt->error
            ];
        }

        $stmt->close();
        $conn->close();

        return [
            'success' => true,
            'message' => 'Dados inseridos com sucesso!!!'
        ];
    }

    // Insere os dados e retorna a resposta
    $response = inserirPessoa($tipo, $nome, $cpf_cnpj, $email, $data_nascimento);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

?>