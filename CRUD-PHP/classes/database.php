<?php 

class Database {
    // Propriedades para armazenar as credenciais do banco de dados
    private $host = "localhost";
    private $db_name = "crud_ajax";
    private $username = "root";
    private $password = "";
    public $conn;

    // Método para obter a conexão com o banco de dados
    public function getConnection() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        // Verificar conexão
        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}

?>