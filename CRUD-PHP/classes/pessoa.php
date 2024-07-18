<?php
include_once 'dados_pessoais.php';

class Pessoa extends DadosPessoais{
  
    public function mostrarInformacoes() {
        echo "Nome: {$this->nome}, CPF: {$this->cpf}, Email: {$this->email}, Data de Nascimento: {$this->dataNascimento}";
    }

}
?>