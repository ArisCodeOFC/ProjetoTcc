// Nesta varíavel será armazenado os dados do usuário, a partir dela é possível pegar o id do usuário para adicionar uma nova tarefa para ele, ou então exibir seu nome e e-mail na tela de forma decorativa
let usuarioAtual = undefined;

$(window).on("load", function() {
    // Qual arquivo .html o usuário está tentando acessar ?
    let arquivoAtual = window.location.pathname.split("/").pop();

    // Faz uma requisição ajax que autoriza ou não o acesso a página
    $.ajax({
        type: "POST",
        url: "api/v1/usuario/sessao.php",
        data: JSON.stringify({arquivo: arquivoAtual}),
        dataType: "json",
        success: function(usuario) {
            // O usuário foi autorizado a entrar na página
            if (usuario) {
                // Caso o usuário esteja logado, seus dados são armazenados em uma variável para uso futuro
                usuarioAtual = usuario;
            }

            $(window).trigger("finishload");
        },

        error: function(erro) {
            // Caso o erro retornado pelo ajax seja 401 (não autorizado), redireciona o usuário para uma página que ele pode acessar
            if (erro.status == 401) {
                window.location.href = erro.statusText;
            }
        }
    });
});

$(window).on("finishload", function() {
    // Todo código aqui dentro será executado caso a página tenha sido autorizada para o usuário e ele não tenha sido redirecionado. Escrever neste trecho de código todo o javascript das páginas e as chamadas AJAX das APIs para as funções do aplicativo
});
