<?php

class codigocorto
{

    public function ObtenerEncuesta($encuestaid)
    {
        global $wpdb;
        $table = "{$wpdb->prefix}encuestas";
        $query = "SELECT * FROM $tabla WHERE EncuestaId = '$encuestaid'";
        $datos = $wpdb->get_results($query, ARRAY_A);
        if (empty($datos)) {
            $datos = array();
        }
        return $datos[0];
    }

    public function ObtenerEncuestaDetalle($encuestaid)
    {
        global $wpdb;
        $table = "{$wpdb->prefix}encuestas_detalle";
        $query = "SELECT * FROM $tabla WHERE EncuestaId = '$encuestaid'";
        $datos = $wpdb->get_results($query, ARRAY_A);
        if (empty($datos)) {
            $datos = array();
        }
        return $datos;
    }

    public function formOpen($titulo)
    {
        $html = "
            <div class='wrap>
            <h4> $titulo</h4>
            <br>
            <form method='POST'>
    ";

        return $html;
    }

    public function formClose()
    {
        $html = "
           <br>
           <input type='submit' id='btnguardar' name='btnguardar' class='page-title-action' value='enviar'>
            </form>
            </div>
           ";

        return $html;
    }

    function fromInput($detalleId, $pregunta, $tipo)
    {
        $html = "";
        if ($tipo == 1) {
            $html = "
            <div class='form-group'>
                    <p><b>$pregunta</b></p>
                <div class='col-sm-8'>
                    <select class='form-control' id='$detalleid' name='$detalleid'>
                        <option value='SI'>SI</option>
                        <option value='NO'>NO</option>
                    </select>
            </div> 
        ";
        } elseif ($tipo == 2) {
            $html = "
        <div class='form-group'>
                <p><b>$pregunta</b></p>
            <div class='col-sm-8'>
                <select class='form-control' id='$detalleid' name='$detalleid'>
                    <option value='SI'>SI</option>
                    <option value='NO'>NO</option>
                </select>
        </div>  
        ";
        } else {
        }
        return $html;
    }

    function Armador($encuestaid)
    {
        $enc = $this->ObtenerEncuesta($encuestaid);
        $nombre = $enc['Nombre'];
        //obtener las preguntas
        $preguntas = "";
        $listapreguntas = $this->ObtenerEncuestaDetalle($encuestaid);
        foreach ($listapreguntas as $key => $value) {
            $detalleId = $value['DetalleId'];
            $pregunta = $value['Pregunta'];
            $tipo = $value['Tipo'];
            $encid = $value['EncuestaId'];

            if ($encid == $encuestaid) {
                $pregunta .= $this->fromInput($detalleId, $pregunta, $tipo);
            }
        }

        $html = $this->formOpen($nombre);
        $html .= $preguntas;
        $html .= $this->formClose();


        return $html;
    }

    function GuardarDetalle($datos)
    {
        global $wpdb;
        $tabla = "{$wpdb->prefix}encuestas_respuesta";
        return $wpdb->insert($tabla, $datos);
    }
};
