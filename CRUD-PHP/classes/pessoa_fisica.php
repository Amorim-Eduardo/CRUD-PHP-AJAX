<?php
include_once 'dados_pessoais.php';

class PessoaFisica extends DadosPessoais {
    private $cpf;

    public function __construct($nome, $email, $dataNascimento, $cpf) {
        parent::__construct($nome, $email, $dataNascimento);
        $this->cpf = $cpf;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }
}

?>