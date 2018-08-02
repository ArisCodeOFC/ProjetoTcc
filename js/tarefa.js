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

        $("#form-tarefa").submit(function(e) {
            e.preventDefault();
            if ($(this).data("tarefa")) {
                editarTarefa($(this).data("tarefa").id);
            } else {
                adicionarTarefa();
            }

            $(this).removeData("tarefa");
        });

        $("body").on("click", ".editar", function() {
            var tarefa = $(this).parent().parent().data("tarefa");
            $("#txttitulo").val(tarefa.titulo);
            $("#txtdescricao").val(tarefa.descricao);
            $(".btnenviar").text("Salvar");
            $("#form-tarefa").data("tarefa", tarefa);
            $(".btncancelar").show();
            $(".tarefa_block.content").css("max-height", "350px");
        });

        $("body").on("click", ".excluir", function() {
            var id = $(this).parent().parent().attr("data-id");
            excluirTarefa(id);
        });

        $(".btncancelar").click(function() {
            $("#form-tarefa").removeData("tarefa");
            $("#form-tarefa")[0].reset();
            $(".btnenviar").text("Enviar");
            $(".btncancelar").hide();
            $(".tarefa_block.content").css("max-height", "0");
        });

        $(".add_button").click(function(event) {
            $("#form-tarefa").removeData("tarefa");
            $("#form-tarefa")[0].reset();
            $(".btncancelar").hide();
            $(".btnenviar").text("Enviar");
            $(".tarefa_block.content").css("max-height", "350px");
        });
    });
});

function listarTarefa(tarefa) {
    var div = $("<div class='tarefa_block'>").attr("data-id", tarefa.id);
    var h2 = $("<h2>").text(tarefa.titulo);
    var p = $("<p>").text(tarefa.descricao);
    var divImagens = $("<div>");
    $("<figure class='editar'>").html("<img src='edit.svg'>").appendTo(divImagens);
    $("<figure class='excluir'>").html("<img src='delete.svg'>").appendTo(divImagens);
    div.append(h2).append(p).append(divImagens);
    div.data("tarefa", tarefa);
    return div;
}

function adicionarTarefa() {
    var form = $("#form-tarefa");
    var url = form.attr('action');
    var tarefa = {titulo: $("#txttitulo").val(), descricao: $("#txtdescricao").val()};

    $.ajax({
        type: form.attr('method'),
        url: 'api/v1/tarefa/inserir.php',
        data: JSON.stringify(tarefa),
        success: function (data) {
            console.log('SUCESSO');
            console.log(data);
            tarefa.id = data.id;
            $(".tarefa_block.content").css("max-height", "0");
            listarTarefa(tarefa).prependTo("#home_block");
            form[0].reset();
        },
        error: function (data) {
            console.log('ERRO');
            console.log(data);
        },
    });
}

function editarTarefa(idTarefa) {
    var form = $("#form-tarefa");
    var tarefa = {id: idTarefa, titulo: $("#txttitulo").val(), descricao: $("#txtdescricao").val()};

    $.ajax({
        type: "POST",
        url: "api/v1/tarefa/atualizar.php",
        data: JSON.stringify(tarefa),
        dataType: "json",
        success: function() {
            $(".tarefa_block[data-id='" + tarefa.id + "']").replaceWith(listarTarefa(tarefa));
            $(".tarefa_block.content").css("max-height", "0");
            form[0].reset();
        },

        error: function(erro) {
            console.log("Erro ao atualizar tarefa", erro.responseText);
        }
    });
}

function excluirTarefa(idTarefa) {
    $.ajax({
        type: "POST",
        url: "api/v1/tarefa/excluir.php",
        data: JSON.stringify({id: idTarefa}),
        dataType: "json",
        success: function() {
            $(".tarefa_block[data-id='" + idTarefa + "']").remove();
        },

        error: function(erro) {
            console.log("Erro ao excluir tarefa", erro.responseText);
        }
    });
}
