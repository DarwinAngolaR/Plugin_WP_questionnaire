<?php

/**
 * Plugin Name: Example 2
 * Plugin URI: https://...
 * Description: Plugin for add shortcode video
 * Version: 1.0.0
 * Autho: Darwin Angola 
 * Author URI: http://...
 * License: ...
 */

add_shortcode('mivideo', function ($atts, $content) {
    $id = $atts['id']; //variable para sustituir en el shortcode
    return '<div class="resposinveContent"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '?rel-0" frameborder="0" allow="accelerometer; autoplay; picture-in-picture" allowfullscreen></iframe></div>';
});
