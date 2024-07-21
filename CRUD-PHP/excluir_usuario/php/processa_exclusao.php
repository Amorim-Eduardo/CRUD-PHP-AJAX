<?php

include '../../classes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo = isset($_POST['cpf']) ? 'cpf' : (isset($_POST['cnpj']) ? 'cnpj' : '');

    if ($tipo === 'cpf' && !empty($_POST['cpf'])) {
        $identificador = $_POST['cpf'];
    } elseif ($tipo === 'cnpj' && !empty($_POST['cnpj'])) {
        $identificador = $_POST['cnpj'];
    } else {
        $response = [
            'success' => false,
            'message' => 'Erro no CPF ou CNPJ!!!'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    if ($tipo === 'cpf') {
        $stmt = $conn->prepare("DELETE FROM pessoa_fisica WHERE cpf = ?");
    } elseif ($tipo === 'cnpj') {
        $stmt = $conn->prepare("DELETE FROM pessoa_juridica WHERE cnpj = ?");
    }

    if (!$stmt) {
        $response = [
            'success' => false,
            'message' => 'Erro ao preparar a consulta SQL!'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("s", $identificador);

    if (!$stmt->execute()) {
        $response = [
            'success' => false,
            'message' => 'Erro ao excluir dados!!!'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = [
            'success' => true,
            'message' => 'Usuário excluído com sucesso!!!'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>
