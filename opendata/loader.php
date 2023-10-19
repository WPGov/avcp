<?php

    function opengare_func($atts) {

        extract(shortcode_atts(array('tipo' => '1', 'anno' => ''), $atts));
            // 1 torta di tipo di affidamento
            // 2 grafico agglomerato di inizio gare
            // 3 tempi e costi medi
            // 4 timeline

        ob_start();
        switch($tipo) {
            case 1:
                include(plugin_dir_path(__FILE__) . '1.php');
                break;
            case 2:
                include(plugin_dir_path(__FILE__) . '2.php');
                break;
            case 3:
                include(plugin_dir_path(__FILE__) . '3.php');
                break;
            case 4:
                include(plugin_dir_path(__FILE__) . '4.php');
                break;
        }
        $shortcode = ob_get_clean();
            return $shortcode;
    }
    add_shortcode('opengare', 'opengare_func');

?>
