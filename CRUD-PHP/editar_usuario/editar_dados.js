var cpf_antigo;

$(document).ready(function() {
    document.getElementById('atualizar').disabled = true;
    $('#atualizar').removeClass('estilo_submissao');

    $('#pesquisar_cpf').click(function(e) {
        e.preventDefault();

        var cpf = document.getElementById('cpf').value;
        var ajax = new XMLHttpRequest();
        ajax.overrideMimeType('application/json');
    
        ajax.onreadystatechange = function() {
            
            if (ajax.readyState === 4) { // Quando a requisição estiver concluída
            if (ajax.status === 200) { // Se a resposta for OK
                try {
                    var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
                    
                    if(response.success){

                        document.getElementById('atualizar').disabled = false;
                        $('#atualizar').addClass('estilo_submissao');
                        document.getElementById('atualizar').style.backgroundColor = 'rgb(100, 100, 100)';

                        document.getElementById('nome').value = response.nome;
                        document.getElementById('nome').disabled = false;

                        document.getElementById('cpf').value = response.cpf;
                        cpf_antigo = response.cpf;
                        document.getElementById('cpf').disabled = false;

                        document.getElementById('email').value = response.email;
                        document.getElementById('email').disabled = false;

                        document.getElementById('data_nascimento').value = response.data_nascimento;
                        document.getElementById('data_nascimento').disabled = false;
                    }else{
                        document.getElementById('atualizar').disabled = true;
                        document.getElementById('nome').disabled = true;
                        document.getElementById('email').disabled = true;
                        document.getElementById('data_nascimento').disabled = true;
                        $('#atualizar').removeClass('estilo_submissao');
                        document.getElementById('atualizar').style.backgroundColor = 'rgb(192, 192, 192)';
                        alert(response.message);
                    }
                        
    
                } catch (e) {
                alert('Erro ao busca dados');
                }
            } else {
                alert('Erro ao buscar dados. Status' + ajax.status);
            }
            }
        };
    
        ajax.open('POST', 'processa_cpf.php', true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        ajax.send('cpf=' + encodeURIComponent(cpf));
    });






    
    $('#atualizar').click(function(e){
        e.preventDefault();

        var nome = document.getElementById('nome'). value;
        var cpf_novo = document.getElementById('cpf'). value;
        var email = document.getElementById('email'). value;
        var data_nascimento = document.getElementById('data_nascimento'). value;

        if(!nome || !cpf || !email || !data_nascimento){
            if (!nome) {
                document.getElementById('nome').style.border = '2px solid red';
            } else {
                document.getElementById('nome').style.border = '2px solid green';
            }
    
            if (!cpf) {
                document.getElementById('cpf').style.border = '2px solid red';
            } else {
                document.getElementById('cpf').style.border = '2px solid green';
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
        }else{

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
                                document.getElementById('cpf').value = '';
                                document.getElementById('email').value = '';
                                document.getElementById('data_nascimento').value = '';
                                document.getElementById('atualizar').disabled = true;
                                document.getElementById('nome').disabled = true;
                                document.getElementById('email').disabled = true;
                                document.getElementById('data_nascimento').disabled = true;
                                $('#atualizar').removeClass('estilo_submissao');
                                document.getElementById('atualizar').style.backgroundColor = 'rgb(192, 192, 192)';
                            } else {
                                alert(response.message);
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
            ajax.open('POST', 'processa_edicao.php', true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            ajax.send('nome=' + encodeURIComponent(nome) + '&cpf=' + encodeURIComponent(cpf_novo) + '&cpf_antigo=' + encodeURIComponent(cpf_antigo) + '&email=' + encodeURIComponent(email) + '&data_nascimento=' + encodeURIComponent(data_nascimento));
        }

    });

});

   
        