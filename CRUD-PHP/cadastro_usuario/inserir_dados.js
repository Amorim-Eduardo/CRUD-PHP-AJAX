$(document).ready(function() {
    $('#cadastrar').click(function(e) {
        e.preventDefault();
  
        var nome = document.getElementById('nome').value.trim();
        var cpf = document.getElementById('cpf').value.trim();
        var email = document.getElementById('email').value.trim();
        var data_nascimento = document.getElementById('data_nascimento').value.trim();
  
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
            var ajax = new XMLHttpRequest();
            ajax.overrideMimeType('application/json');
        
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4) { // Quando a requisição estiver concluída
                if (ajax.status === 200) { // Se a resposta for OK
                    try {
                        var response = JSON.parse(ajax.responseText); // Converte a resposta para JSON
                        alert(response.message);

                        if(response.success){
                            document.getElementById('form_cadastro').reset();
                        }
        
                    } catch (e) {
                    alert('Erro ao processar a resposta do servidor');
                    }
                } else {
                    alert('Erro ao buscar o endereço. Status' + ajax.status);
                }
                }
            };
        
            ajax.open('POST', 'processa_cadastro.php', true);
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            ajax.send('nome=' + encodeURIComponent(nome) + '&cpf=' + encodeURIComponent(cpf) + '&email=' + encodeURIComponent(email) + '&data_nascimento=' + encodeURIComponent(data_nascimento));
        }
    });
  });

   
        