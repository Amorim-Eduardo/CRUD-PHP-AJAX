<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['cpf']) && $_POST['cpf'] != '') {

        $cpf = $_POST['cpf'];

        $host = 'localhost';
        $usuario = 'root'; 
        $senha = ''; 
        $banco = 'crud_ajax';

        $conn = new mysqli($host, $usuario, $senha, $banco);

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM dados_pessoais WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);

        if (!$stmt->execute()) {
            $response = [
                'success' => false,
                'message' => 'Erro ao inserir no banco!'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);

        }else {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if($result->num_rows == 0){
                $response = [
                    'success' => false,
                    'message' => 'usuário não encontrado!!!'
                ];
                header('Content-Type: application/json');
                echo json_encode($response);

            }else{
                $response = [
                    'success' => true,
                    'message' => 'Usuário encontrado',
                    'nome' => $row['nome'],
                    'cpf' => $row['cpf'],
                    'email' => $row['email'],
                    'data_nascimento' => $row['data_nascimento']
                ];

                header('Content-Type: application/json');
                echo json_encode($response);
            }
            
        }
    } else {
    // método de requisição inválido
    $response = [
        'success' => false,
        'message' => 'Usuário não encontrado'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    }
}
?>