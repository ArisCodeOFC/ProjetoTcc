$(window).on("finishload", function() {
    $(document).ready(function() {
        $.ajax({
            type: "POST",
            url: "api/v1/tarefa/listar.php",
            success: function(tarefas) {
                for (var i = 0; i < tarefas.length; i++) {
                    var tarefa = tarefas[i];
                    listarTarefa(tarefa).appendTo("#home_block");
                }
            },

            error: function(erro) {
                console.log("Erro ao listar tarefas", erro.responseText);
            }
        });

        var frm = $("#form-tarefa");
        frm.submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var tarefa = {titulo: $("#txttitulo").val(), descricao: $("#txtdescricao").val()};

            $.ajax({
                type: frm.attr('method'),
                url: 'api/v1/tarefa/inserir.php',
                data: JSON.stringify(tarefa),
                success: function (data) {
                    console.log('SUCESSO');
                    console.log(data);
                    tarefa.id = data.id;
                    $(".add_button").trigger("click");
                    listarTarefa(tarefa).prependTo("#home_block");
                    form[0].reset();
                },
                error: function (data) {
                    console.log('ERRO');
                    console.log(data);
                },
            });
        });
    });
});

function listarTarefa(tarefa) {
    var div = $("<div class='tarefa_block'>");
    var h2 = $("<h2>").text(tarefa.titulo);
    var p = $("<p>").text(tarefa.descricao);
    var divImagens = $("<div>");
    $("<figure>").html("<img src='edit.svg'>").appendTo(divImagens);
    $("<figure>").html("<img src='delete.svg'>").appendTo(divImagens);
    div.append(h2).append(p).append(divImagens);
    div.data("tarefa", tarefa);
    return div;
}
