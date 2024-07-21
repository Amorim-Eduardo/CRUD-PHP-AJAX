<?php 

class PessoaJuridica extends DadosPessoais {
    private $cnpj;

    public function __construct($nome, $email, $dataNascimento, $cnpj) {
        parent::__construct($nome, $email, $dataNascimento);
        $this->cnpj = $cnpj;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }
}

?>
