var global_identificador;

$(document).ready(function() {
    document.getElementById('excluir').disabled = true;
    $('#excluir').removeClass('estilo_submissao');

    function buscarDados(tipo, identificador) {
        var ajax = new XMLHttpRequest();
        ajax.overrideMimeType('application/json');
    
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4) { // Quando a requisição estiver concluída
                if (ajax.status === 200) { // Se a resposta for OK
                    try {
                        var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
                        
                        if(response.success){
                            document.getElementById('excluir').disabled = false;
                            $('#excluir').addClass('estilo_submissao');
                            document.getElementById('excluir').style.backgroundColor = 'rgb(100, 100, 100)';
                            document.getElementById('nome').value = response.nome;
                            if (tipo === 'cpf') {
                                document.getElementById('cpf').value = response.cpf;
                                global_identificador = response.cpf;
                            } else if (tipo === 'cnpj') {
                                document.getElementById('cnpj').value = response.cnpj;
                                global_identificador = response.cnpj;
                            }
                            document.getElementById('email').value = response.email;
                            document.getElementById('data_nascimento').value = response.data_nascimento;
                        } else {
                            document.getElementById('excluir').disabled = true;
                            document.getElementById('nome').disabled = true;
                            document.getElementById('email').disabled = true;
                            document.getElementById('data_nascimento').disabled = true;
                            $('#excluir').removeClass('estilo_submissao');
                            document.getElementById('excluir').style.backgroundColor = 'rgb(192, 192, 192)';
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

    $('#pesquisar_cpf').click(function(e) {
        e.preventDefault();
        var cpf = document.getElementById('cpf').value.trim();
        if (cpf) {
            buscarDados('cpf', cpf);
        } else {
            alert('Digite um CPF para buscar.');
        }
    });

    $('#pesquisar_cnpj').click(function(e) {
        e.preventDefault();
        var cnpj = document.getElementById('cnpj').value.trim();
        if (cnpj) {
            buscarDados('cnpj', cnpj);
        } else {
            alert('Digite um CNPJ para buscar.');
        }
    });

    $('#excluir').click(function(e) {
        e.preventDefault();
    
        if(document.getElementById('cpf'))
            var tipo = 'cpf';
        else    
            var tipo = 'cnpj';
    
        // Iniciar uma requisição AJAX
        var ajax = new XMLHttpRequest();
    
        // Configurar a requisição AJAX
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4) { // Quando a requisição estiver concluída
                if (ajax.status === 200) { // Se a resposta for OK (sucesso)
                    try {
                        var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
    
                        // Exibir mensagem de sucesso ou erro, dependendo da resposta
                        if (response.success) {
                            alert(response.message); // Exibe mensagem de sucesso
    
                            // Limpar os campos do formulário após o sucesso
                            document.getElementById('nome').value = '';
                            if (document.getElementById('cpf')) {
                                document.getElementById('cpf').value = '';
                            } else if (document.getElementById('cnpj')) {
                                document.getElementById('cnpj').value = '';
                            }
                            document.getElementById('email').value = '';
                            document.getElementById('data_nascimento').value = '';
                            document.getElementById('excluir').disabled = true;
                            document.getElementById('nome').disabled = true;
                            document.getElementById('email').disabled = true;
                            document.getElementById('data_nascimento').disabled = true;
                            $('#excluir').removeClass('estilo_submissao');
                            document.getElementById('excluir').style.backgroundColor = 'rgb(192, 192, 192)';
                        } else {
                            alert(response.message); // Exibe mensagem de erro
                        }
                    } catch (e) {
                        alert('Erro ao processar a resposta do servidor');
                    }
                } else {
                    alert('Erro ao buscar dados. Status: ' + ajax.status);
                }
            }
        };
    
        // Configurar e enviar a requisição AJAX
        ajax.open('POST', '../php/processa_exclusao.php', true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        ajax.send(tipo + '=' + encodeURIComponent(global_identificador));
    });
    
});
