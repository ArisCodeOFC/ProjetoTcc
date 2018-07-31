//É chamado quando a pagina terminar de carregar o html
$(window).on("finishload", function(){

    console.log("Teste1");
    $(document).ready(function(){
        console.log("Teste2");

        //Esse trecho roda quando o botao for acionado, e pega os dados do formulario
        $('#login-user').submit(function(impedir){
            //Função para impedir de recaregar a pag
            impedir.preventDefault();

            //pegando os valores dos campos que o usuario digitou

            //val -> valor que o usuário digitou nas caixas
            var usuario =  $('#user').val();
            var senha = $('#pass').val();

            var login_user ={
                email: usuario, senha: senha
            };

            //Criando um objeto chamando pela URL
            $.ajax({
                //link da api
                url:"api/v1/usuario/login.php",
                type:"POST",
                dataType:"json",
                data:JSON.stringify(login_user),
                success:function(){
                    window.location.href = "index.html";
                },
                error:function(erro){
                    $('#erro').texte(erro.responseText);
                }
            });

        });
    });
});
