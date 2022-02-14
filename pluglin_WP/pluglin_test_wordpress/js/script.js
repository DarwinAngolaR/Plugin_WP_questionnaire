jQuery(document).ready(function ($) {

    $("#btn_nuevo").click(function () {

        $("#modalnuevo").modal("show");

    });

    var i = 1;
    $("#add").click(function () {
        i++;
        $("#camposdinamicos").append('<tr id="row' + i + '"><td><label for="txtnombre" class="col-form-label">Pregunta' + i + '</label></td><td><input type="text" name="name[]" id="name" class="form-control name_list"></td><td><select name="type[]" id="tipe" class="col-form-label type-list"><option value="1" select>SI -NO</option><option value="2">Rango 0 - 5</option></select></td><td><button name="remove" id="' + i + '" class="btn btn-success btn-remove">X</button></td></tr>')
    });

    $(document).on('click', '.btn_remove', function () {
        var butto_id = $(this).attr('id');
        $("#row" + button_id + "").remove();
    })

    $(document).on('click', "a[data-id]", function () {
        var id = this.dataset.id;
        var url = "";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                action: "peticioneliminar",
                nonce: SolicitudesAjax.seguridad,
                id: id,
            },
            success: function () {
                alert("Datos borrados");
                location.reload();
            }
        });
    });
});