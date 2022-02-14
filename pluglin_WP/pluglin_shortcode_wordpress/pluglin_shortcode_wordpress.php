<?php

/**
 * Plugin Name: Example 1
 * Plugin URI: https://...
 * Description: Plugin for banner
 * Version: 1.0.0
 * Autho: Darwin Angola 
 * Author URI: http://...
 * License: ...
 */

add_shortcode('banner', function () {
    $output = '<div style="background-color: ##00A9FF; font-size: 14px; line-height: 24px; color: #070707; text-align: center; padding: 6px 18px;"> Prueba aca
    <a style="display:inline-block; background-color: #FF7020; border: 1px sold #fff; border-radius: 6px; font-size: 14px; font-weight: normal; color:#fff; padding: 3px 8px;
    text-decoration: none;>Click-ici</a></div>';
    return $output;
});
