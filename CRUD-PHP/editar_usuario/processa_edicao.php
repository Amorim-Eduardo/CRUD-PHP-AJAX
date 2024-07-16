<?php
session_start();

include '../classes/pessoa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Verifica se todos os campos necessários foram recebidos
    if (!empty($_POST['nome']) && !empty($_POST['cpf']) && !empty($_POST['cpf_antigo']) && !empty($_POST['email']) && !empty($_POST['data_nascimento'])) {

        $nome = $_POST['nome'];
        $cpf_novo = $_POST['cpf'];
        $cpf_antigo = $_POST['cpf_antigo'];
        $email = $_POST['email'];
        $data_nascimento = $_POST['data_nascimento'];

        $pessoa = new Pessoa($nome, $cpf_novo, $email, $data_nascimento);

        // Validação dos campos usando métodos da classe Pessoa
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
                'errors' => $errors,
                'message' => 'Dados incompletos!!!'
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // Todos os dados estão válidos, proceder com a atualização no banco de dados
            $host = 'localhost';
            $usuario = 'root';
            $senha = '';
            $banco = 'crud_ajax';

            $conn = new mysqli($host, $usuario, $senha, $banco);

            // Verifica a conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            // Preparação da query SQL para UPDATE
            $stmt = $conn->prepare("UPDATE dados_pessoais SET nome = ?, cpf = ?, email = ?, data_nascimento = ? WHERE cpf = ?");
            $stmt->bind_param("sssss", $nome, $cpf_novo, $email, $data_nascimento, $cpf_antigo);

            // Executa a query
            if (!$stmt->execute()) {
                $response = [
                    'success' => false,
                    'message' => 'Erro ao alterar dados no banco!'
                ];
            } else {
                $response = [
                    'success' => true,
                    'message' => 'Dados alterados com sucesso!!!'
                ];
            }

            // Retorna resposta JSON para o AJAX
            header('Content-Type: application/json');
            echo json_encode($response);

            exit;
        }

    } else {
        // Dados não recebidos corretamente via POST
        $response = [
            'success' => false,
            'message' => 'Dados incompletos!!!'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Método de requisição não é POST
    $response = [
        'success' => false,
        'message' => 'Método de requisição não é POST'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
