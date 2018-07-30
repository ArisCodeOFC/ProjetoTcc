//É chamado quando a pagina terminar de carregar o html
$(window).on("finishload", function(){

    console.log("Teste1");
    $(document).ready(function(){
        console.log("Teste2");

        //Esse trecho roda quando o botao for acionado, e pega os dados do formulario
        $('#login-user').submit(function(impedir){

        });
    });
});
