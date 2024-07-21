<?php 

class DadosPessoais {
    protected $nome;
    protected $email;
    protected $dataNascimento;

    public function __construct($nome, $email, $dataNascimento) {
        $this->nome = $nome;
        $this->email = $email;
        $this->dataNascimento = $dataNascimento;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }
}

?>




