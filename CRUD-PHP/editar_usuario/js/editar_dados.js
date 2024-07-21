var cpf_antigo;
var cnpj_antigo;

$(document).ready(function() {
    // Inicialmente, desabilita o botão de atualização
    document.getElementById('atualizar').disabled = true;
    $('#atualizar').removeClass('estilo_submissao');

    // Função para buscar dados
    function buscarDados(tipo, identificador) {
        var ajax = new XMLHttpRequest();
        ajax.overrideMimeType('application/json');
        
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4) { // Quando a requisição estiver concluída
                if (ajax.status === 200) { // Se a resposta for OK
                    try {
                        var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
                        
                        if (response.success) {
                            // Habilita o botão de atualização e os campos de formulário
                            document.getElementById('atualizar').disabled = false;
                            $('#atualizar').addClass('estilo_submissao');
                            document.getElementById('atualizar').style.backgroundColor = 'rgb(100, 100, 100)';
                            
                            // Atualiza os campos de formulário com os dados recebidos
                            document.getElementById('nome').value = response.nome;
                            document.getElementById('nome').disabled = false;
                            document.getElementById('email').value = response.email;
                            document.getElementById('email').disabled = false;
                            document.getElementById('data_nascimento').value = response.data_nascimento;
                            document.getElementById('data_nascimento').disabled = false;
                            
                            // Atualiza o campo CPF ou CNPJ e a variável antiga
                            if (tipo === 'cpf') {
                                document.getElementById('cpf').value = response.cpf;
                                cpf_antigo = response.cpf;
                                document.getElementById('cpf').disabled = false;
                            } else if (tipo === 'cnpj') {
                                document.getElementById('cnpj').value = response.cnpj;
                                cnpj_antigo = response.cnpj;
                                document.getElementById('cnpj').disabled = false;
                            }
                        } else {
                            // Desabilita o botão de atualização e os campos de formulário em caso de erro
                            document.getElementById('atualizar').disabled = true;
                            document.getElementById('nome').disabled = true;
                            document.getElementById('email').disabled = true;
                            document.getElementById('data_nascimento').disabled = true;
                            $('#atualizar').removeClass('estilo_submissao');
                            document.getElementById('atualizar').style.backgroundColor = 'rgb(192, 192, 192)';
                            alert(response.message);
                        }
                    } catch (e) {
                        alert('Erro ao buscar dados');
                    }
                } else {
                    alert('Erro ao buscar dados. Status: ' + ajax.status);
                }
            }
        };
        
        ajax.open('POST', tipo === 'cpf' ? '../php/processa_cpf.php' : '../php/processa_cnpj.php', true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        ajax.send(tipo === 'cpf' ? 'cpf=' + encodeURIComponent(identificador) : 'cnpj=' + encodeURIComponent(identificador));
    }

    // Manipulador para o botão de busca de CPF
    $('#pesquisar_cpf').click(function(e) {
        e.preventDefault();
        var cpf = document.getElementById('cpf').value.trim();
        if (cpf) {
            buscarDados('cpf', cpf);
        } else {
            alert('Digite um CPF para buscar.');
        }
    });

    // Manipulador para o botão de busca de CNPJ
    $('#pesquisar_cnpj').click(function(e) {
        e.preventDefault();
        var cnpj = document.getElementById('cnpj').value.trim();
        if (cnpj) {
            buscarDados('cnpj', cnpj);
        } else {
            alert('Digite um CNPJ para buscar.');
        }
    });

    // Manipulador para o botão de atualização
    $('#atualizar').click(function(e) {
        e.preventDefault();

        var nome = document.getElementById('nome').value.trim();
        var email = document.getElementById('email').value.trim();
        var data_nascimento = document.getElementById('data_nascimento').value.trim();
        var cpf_novo = document.getElementById('cpf') ? document.getElementById('cpf').value.trim() : '';
        var cnpj_novo = document.getElementById('cnpj') ? document.getElementById('cnpj').value.trim() : '';

        if (!nome || !email || !data_nascimento || (!cpf_novo && !cnpj_novo)) {
            if (!nome) {
                document.getElementById('nome').style.border = '2px solid red';
            } else {
                document.getElementById('nome').style.border = '2px solid green';
            }

            if (!email) {
                document.getElementById('email').style.border = '2px solid red';
            } else {
                document.getElementById('email').style.border = '2px solid green';
            }

            if (!data_nascimento) {
                document.getElementById('data_nascimento').style.border = '2px solid red';
            } else {
                document.getElementById('data_nascimento').style.border = '2px solid green';
            }

            if (!cpf_novo && !cnpj_novo) {
                if (document.getElementById('cpf')) {
                    document.getElementById('cpf').style.border = '2px solid red';
                }
                if (document.getElementById('cnpj')) {
                    document.getElementById('cnpj').style.border = '2px solid red';
                }
            }
        } else {
            var ajax = new XMLHttpRequest();
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4) { // Quando a requisição estiver concluída
                    if (ajax.status === 200) { // Se a resposta for OK
                        try {
                            var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
                            if (response.success) {
                                alert(response.message); // Exibe mensagem de sucesso
                                // Limpar os campos do formulário após o sucesso
                                document.getElementById('nome').value = '';
                                if(document.getElementById('cpf'))
                                    document.getElementById('cpf').value = '';
                                else
                                    document.getElementById('cnpj').value = '';
                                document.getElementById('email').value = '';
                                document.getElementById('data_nascimento').value = '';
                                document.getElementById('atualizar').disabled = true;
                                document.getElementById('nome').disabled = true;
                                document.getElementById('email').disabled = true;
                                document.getElementById('data_nascimento').disabled = true;
                                $('#atualizar').removeClass('estilo_submissao');
                                document.getElementById('atualizar').style.backgroundColor = 'rgb(192, 192, 192)';
                            } else {
                                alert(response.errors);
                            }
                        } catch (e) {
                            alert('Erro ao processar a resposta do servidor');
                        }
                    } else {
                        alert('Erro ao buscar dados. Status: ' + ajax.status);
                    }
                }
            };

            var postData = 'nome=' + encodeURIComponent(nome) +
                           '&email=' + encodeURIComponent(email) +
                           '&data_nascimento=' + encodeURIComponent(data_nascimento);

            if (cpf_novo) {
                postData += '&cpf=' + encodeURIComponent(cpf_novo) + '&cpf_antigo=' + encodeURIComponent(cpf_antigo);
            } else if (cnpj_novo) {
                postData += '&cnpj=' + encodeURIComponent(cnpj_novo) + '&cnpj_antigo=' + encodeURIComponent(cnpj_antigo);
            }

            ajax.open('POST', '../php/processa_edicao.php', true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            ajax.send(postData);
        }
    });
});
