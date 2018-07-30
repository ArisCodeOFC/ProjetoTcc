//Quando a página for iniciada
$(window).on("finishload", function(){

    //Função = Passando uma mensagem para ela ex: "Alerta"
    //console.log("Teste");

    $(document).ready(function(){

        console.log("Teste");

            //Pegando o formulário, submetendo um formulário
            $('#cadastro').submit(function(impedir){
                //Função para impedir de recaregar a pag
                impedir.preventDefault();
                //Pegando o valor das ID do HTML (cadastroUser)
                //Modo de leitura: Valor que vem do campo nome
                //Val -> valor que o usuário digitou nas caixas
               var nome_html =  $('#nome').val();
               var email_html = $('#email').val();
                var senha_html = $('#senha').val();

                //Console para confirmar que esta pegando os dados
                console.log(nome_html, email_html, senha_html);

                //Criando váriavel 'modelo usuário', da qual
                //estou passando as variaveis que criei ali em cima
                var model_user = {
                    nome: nome_html, email: email_html, senha: senha_html
                };

                //Ajax
                //Criando um objeto chamando pela URL
                $.ajax({
                    url:"api/v1/usuario/inserir.php", //Passando o link da API
                    type:"POST", //Método
                    dataType:"json", //Tipo de dado
                    data: JSON.stringify(model_user), //Objeto que criei ali em cima
                    success:function(){
                        window.location.href = "login.html";
                    }, //Se der certo
                    error:function(erro){
                        $('#teste').text(erro.responseText);
                    } //Se der errado
                });

            });

    });




});
