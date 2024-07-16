<?php
class Pessoa {
    private $nome;
    private $cpf;
    private $email;
    private $data_nascimento;

    public function __construct($nome, $cpf, $email, $data_nascimento) {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->data_nascimento = $data_nascimento;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDataNascimento() {
        return $this->data_nascimento;
    }
}
?>