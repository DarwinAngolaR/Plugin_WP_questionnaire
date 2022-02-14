<?php
global $wpdb;

$tabla = "{$wpdb->prefix}encuestas";
$tabla2 = "{$wpdb->prefix}encuestas_detalles";

if (isset($_POST['btnguardar'])) {
    $nombre = $_POST['txtnombre'];
    $query = "SELECT EncuestaId FROM $tabla ORDER BY EncuestaId DESC limit 1";
    $resultado = $wpdb->get_results($query, ARRAY_A);
    $proximoId = $resultado[0]['EncuestaId'] + 1;
    $shortcode = "[ENC id='$proximoId']";

    $datos = [
        'EncuestaId' => null,
        'Nombre' => $nombre,
        'Shortcode' => $shortcode
    ];

    $respuesta = $wpbd->insert($tabla, $datos);

    if ($respuesta) {
        $listapreguntas = $_POST['name'];
        $i = 0;
        foreach ($listapreguntas as $key => $value) {
            $tipo = $_POST['type'][$i];
            $datos2 = [
                'DetalleId' => null,
                'EncuestaId' => $proximoId,
                'Pregunta' => $value,
                'Tipo' => $tipo
            ];

            $wpdb->insert($tabla2, $datos2);

            $i++;
        }
    }
}

$query = "SELECT * FROM $tabla";
$lista_encuestas = $wpdb->get_results($query, ARRAY_A);
if (empty($lista_encuestas)) {
    $lista_encuestas = array();
}

?>

<div class="wrap">

    <?php
    echo "<h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>";
    ?>
    <a id="btn_nuevo" class="page-title-action">Añadir nueva</a>

    <table class="wp-list-table widefat fixed strip pages">
        <thead>
            <th>Nom de l'enquête</th>
            <th>ShortCode</th>
            <th>Acciones</th>
        </thead>
        <tbody id="the-list">
            <?php
            foreach ($lista_encuestar as $key => $value) {
                $id = $value['EncuestaId'];
                $nombre = $value['Nombre'];
                $shortcode = $value['ShortCode'];

                echo "
                        <tr>
                            <td>$nombre</td>
                            <td>$shortcode</td>
                            <td>
                                <a class='page-title-action'>Estadisticas</a>
                                <a data-id='$id' class='page-title-action'>Borrar</a>
                            </td>
                        </tr>
                    ";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalnuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Encuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group"></div>
                    <label for="txtnombre" class="col-sm-4 col-form-label">Nombre de encuesta</label>
                    <div class="col-sm-8">
                        <input type="text" id="txtnombre" name="txtnombre" style="width:100%">
                    </div>
                </div>
                <br>
                <hr>
                <h4>Preguntas</h4>
                <hr>
                <br>
                <table id="camposdinamicos">
                    <tr>
                        <td>
                            <label for="txtnombre" class="col-form-label">Pregunta 1</label>
                        </td>
                        <td>
                            <input type="text" name="name[]" id="name" class="form-control name_list">
                        </td>
                        <td>
                            <select name="type[]" id="tipe" class="col-form-label type-list">
                                <option value="1" select>SI -NO</option>
                                <option value="2">Rango 0 - 5</option>
                                <option value="3">Respuesta breve</option>
                            </select>
                        </td>
                        <td>
                            <button name="add" id="add" class="btn btn-success">Agregar mas</button>
                        </td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" name="btnguardar" id="btnguardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>