<?php

/**
 * Plugin Name: Example 3
 * Plugin URI: https://...
 * Description: Plugin for..
 * Version: 1.0.0
 * Autho: Darwin Angola 
 * Author URI: http://...
 * License: ...
 */

//requires 
require_once dirname(__FILE__) . '/clases/codigocorto.class.php';


function Activar()
{
    global $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas(
        `EncuestaId` INT NOT NULL AUTO_INCREMENT,
        `Nombre` VARCHAR(45) NULL,
        `ShortCode` VARCHAR(45) NULL,
        PRIMARY KEY (`EncuestaId`));";

    $wpdb->query($sql);

    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_detalle(
            `DetalleId` INT NOT NULL AUTO_INCREMENT,
            `EncuestaId` INT NULL,
            `Pregunta` VARCHAR(150) NULL,
            `Tipo` VARCHAR(45) NULL,
            PRIMARY KEY (`DetalleId`));";

    $wpdb->query($sql2);

    $sql3 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}encuestas_respuesta(
        `RespuestaId` INT NOT NULL AUTO_INCREMENT,
        `DetalleId` INT NULL,
        `Codigo` VACHAR(45) NULL,
        `Respuesta` VACHAR(45) NULL,
        PRIMARY KEY (`DetalleId`));";

    $wpdb->query($sql3);
}

function Desactivar()
{
    flush_rewrite_rules();
}

function Borrar()
{
}

register_activation_hook(__FILE__, 'Activar');
register_desactivation_hook(__FILE__, 'Desactivar');
register_uninstall_hook(__FILE__, 'Borrar'); // se recomienda realizar a partir de un archivo uninstall.php


add_action('admin_menu', 'crearMenu'); // funcion de WP para crear menu adm de plugin

function crearMenu()
{
    add_menu_page(
        'Encuestas', // Titulo de pagina
        'Encuestas menu', // Titulo de menu
        'manage_options', // Capability
        plugin_dir_path(__FILE__) . 'lista_encuestas.php', //slug
        null, // Funcion para mostrar contenido
        '1' //prioridad dentrod el rango del menu
    );

    add_submenu_page(
        'sp_menu', //slug
        'Ajustes', //titulo pagina
        'Ajustes', // titulo menu
        'manage_options',
        'sp_menu_ajustes', //slug
        'SubMenu'
    );
}

function MostrarContenido()
{
}

// Agregado JS BOOTS

function EncolarBootstrap($hook)
{
    //echo "<script>console.log('$hook')</script>";
    if ($hook != "pluglin_test_wordpress/lista_encuestas.php") {
        return;
    }

    wp_enqueue_script('bootstrapJs', plugins_url('bootstrap/bootstrap.min.js', __FILE__), array('jquery'));
}

add_action('admin_enqueue_scripts', 'EncolarBootstrapJS');


// Agregado CSS Boots

function EncolarBootstrapCss($hook)
{
    if ($hook != "pluglin_test_wordpress/lista_encuestas.php") {
        return;
    }

    wp_enqueue_script('bootstrapCss', plugins_url('bootstrap/bootstrap.min.css', __FILE__), array('jquery'));
}

add_action('admin_enqueue_scripts', 'EncolarBootstrapCss');

// Agragado archivo JS propio

function EncolarScriptJs($hook)
{
    if ($hook != "pluglin_test_wordpress/lista_encuestas.php") {
        return;
    }

    wp_enqueue_script('scriptJs', plugins_url('js/script.js', __FILE__), array('jquery'));
    wp_localize_script('scriptJs', 'SolicitudesAjax', [
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'EncolarScriptJs');

//ajax

function EliminarEncuesta()
{
    $nonce = $_POST['nonce'];
    if (!wp_verifify_nonce($nonce, 'seg')) {
        die('No tienes permiso');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}encuestas";
    $tabla2 = "{$wpdb->prefix}encuestas_detalles";
    $wpdb->delete($tabla, array('EncuestaId' => $id));
    $wpdb->delete($tabla2, array('EncuestaId' => $id));
    return true;
}

add_action('wp_ajax_peticioneliminar', 'EliminarEncuesta');

//Shortcode 

function imprimirShortCode($atts)
{
    $_short = new codigocorto; //nombre de la class 
    //obtener el id por parmetro
    $id = $atts['id'];
    //Programar acciones del boton
    if (isset($_POST['btnguardar'])) {
        $listadePreguntas = $_short->ObtenerEncuestaDetalle($id);
        $codigo = uniqid();
        foreach ($listadePreguntas as $key => $value) {
            $idpregunta = $value['DetalleId'];
            if (isset($_POST[$idpregunta])) {
                $valortxt = $_POST[$idpregunta];
                $datos = [
                    'DetalleId' => $idpregunta,
                    'Codigo' => $codigo,
                    'Respuesta' => $valortxt
                ];
                $_short->GuardarDetalle($datos);
            }
        }
        return "Encuesta enviada !";
    }

    $html = $_short->Armador($id);
    return $html;
}

add_shortcode("ENC", "imprimirShortCode");




// function MostrarContenido()
// {
// echo "<h1> Contenido de Pagina</h1>";
// }

// function SubMenu()
// {
// echo "<h2> Submenu </h2>";
// }