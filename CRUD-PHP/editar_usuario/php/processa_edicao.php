<?php
session_start();

include_once '../../classes/database.php';
include_once '../../classes/pessoa_fisica.php';
include_once '../../classes/pessoa_juridica.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Verifica se todos os campos necessários foram recebidos
    if (!empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['data_nascimento'])) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $data_nascimento = $_POST['data_nascimento'];
        $cpf_novo = isset($_POST['cpf']) ? $_POST['cpf'] : null;
        $cpf_antigo = isset($_POST['cpf_antigo']) ? $_POST['cpf_antigo'] : null;
        $cnpj_novo = isset($_POST['cnpj']) ? $_POST['cnpj'] : null;
        $cnpj_antigo = isset($_POST['cnpj_antigo']) ? $_POST['cnpj_antigo'] : null;

        // Determine se é uma pessoa física ou jurídica
        $isPessoaFisica = !empty($cpf_novo);

        if ($isPessoaFisica) {
            $pessoa = new PessoaFisica($nome, $email, $data_nascimento, $cpf_novo);
            $identificador_novo = $cpf_novo;
            $identificador_antigo = $cpf_antigo;
            $tabela = "pessoa_fisica";
            $coluna_identificador = "cpf";
        } else {
            $pessoa = new PessoaJuridica($nome, $email, $data_nascimento, $cnpj_novo);
            $identificador_novo = $cnpj_novo;
            $identificador_antigo = $cnpj_antigo;
            $tabela = "pessoa_juridica";
            $coluna_identificador = "cnpj";
        }

        // Validação dos campos usando métodos da classe Pessoa
        if (empty($pessoa->getNome())) {
            $errors['nome'] = "O campo Nome é obrigatório.";
        }

        if (empty($identificador_novo)) {
            $errors['identificador'] = "O campo CPF/CNPJ é obrigatório.";
        }

        if (empty($pessoa->getEmail())) {
            $errors['email'] = "O campo E-mail é obrigatório.";
        } 
        if (empty($pessoa->getDataNascimento())) {
            $errors['data_nascimento'] = "O campo Data de Nascimento é obrigatório.";
        }

        // Se houver erros, retorna uma resposta com os erros
        if (!empty($errors)) {
            $_SESSION['nome'] = $pessoa->getNome();
            $_SESSION['identificador'] = $identificador_novo;
            $_SESSION['email'] = $pessoa->getEmail();
            $_SESSION['data_nascimento'] = $pessoa->getDataNascimento();

            // Armazena os erros na sessão
            $_SESSION['errors'] = $errors;

            // Retorna uma resposta em JSON para o AJAX
            $response = [
                'success' => false,
                'errors' => $errors,
                'message' => 'Dados incompletos ou inválidos!'
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // Todos os dados estão válidos, proceder com a atualização no banco de dados
            $database = new Database();
            $conn = $database->getConnection();

            // Verifica a conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            // Preparação da query SQL para UPDATE
            $query = "UPDATE $tabela SET nome = ?, $coluna_identificador = ?, email = ?, data_nascimento = ? WHERE $coluna_identificador = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $nome, $identificador_novo, $email, $data_nascimento, $identificador_antigo);

            // Executa a query
            if (!$stmt->execute()) {
                $response = [
                    'success' => false,
                    'message' => 'Erro ao alterar dados no banco!'
                ];
            } else {
                $response = [
                    'success' => true,
                    'message' => 'Dados alterados com sucesso!'
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
            'message' => 'Dados incompletos!'
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
