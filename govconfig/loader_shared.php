<?php

add_action( 'admin_menu', 'register_avcp_wpgov_menu_page' );

function register_avcp_wpgov_menu_page(){
    if (is_plugin_active( 'amministrazione-trasparente/amministrazionetrasparente.php' )) { return; }
    add_menu_page('Impostazioni soluzioni WPGOV.IT', 'WPGov.it', 'manage_options', 'impostazioni-wpgov', 'avcp_wpgov_settings', 'dashicons-palmtree', 40);
}

function avcp_wpgov_settings () {

    echo '<div class="wrap">';
    echo '<style>.box {width:90%;position:relative;height: 100px;margin: 50px auto; } </style>';
    echo '<div class="box">

    <div style="float:right;position:relative;">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="F2JK36SCXKTE2">
    <input type="image" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - Il metodo rapido, affidabile e innovativo per pagare e farsi pagare.">
    <img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
    </form>
    </div>

    <a style="float:left;padding: 5px 20px;" href="http://www.wpgov.it" target="_blank" title="WordPress per la Pubblica Amministrazione"><img src="' . plugin_dir_url(__FILE__) . 'panels/includes/wpa_black.png' . '" alt=""></a>';
    echo '<h4>Novità:</h4>';
    include_once( ABSPATH . WPINC . '/feed.php' );
    $rss = fetch_feed( 'http://www.wpgov.it/feed' );
    if ( ! is_wp_error( $rss ) ) :
    $maxitems = $rss->get_item_quantity( 3 );
    $rss_items = $rss->get_items( 0, $maxitems );
    endif;?>

<ul>
    <?php if ( $maxitems == 0 ) : ?>
        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
    <?php else : ?>
        <?php // Loop through each feed item and display each item as a hyperlink. ?>
        <?php foreach ( $rss_items as $item ) : ?>
            <li>
                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank"
                    title="<?php printf( __( 'Posted %s', 'my-text-domain' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?php echo esc_html( $item->get_title() ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
    <?php
    echo '</div>';

    global $pagenow;
    $settings = get_option( "wpgov_theme_settings" );

    //generic HTML and code goes here

    if(isset($_POST['FlushButton'])) {
        flush_rewrite_rules();
    }
    if ( isset ( $_GET['tab'] ) ) { avcp_wpgov_admin_tabs($_GET['tab']);  } else { avcp_wpgov_admin_tabs('general');}
    if ( isset ( $_GET['tab'] ) ) { $tab = $_GET['tab']; } else { $tab = 'general'; }

   echo '<table class="form-table">';
   switch ( $tab ){
      case 'general' :
        include(plugin_dir_path(__FILE__) . 'panels/p_wpgov_settings.php');
      break;
      case 'at' :
        if (is_plugin_active( 'amministrazione-trasparente/amministrazionetrasparente.php' )) {
            include(ABSPATH . 'wp-content/plugins/amministrazione-trasparente/govconfig/panels/p_at_settings.php');
        } else {
            echo 'Plugin non installato!';
        }
      break;
      case 'avcp' :
        if (is_plugin_active( 'avcp/avcp.php' )) {
            include(ABSPATH . 'wp-content/plugins/avcp/govconfig/panels/p_avcp_settings.php');
        } else {
            echo 'Plugin non installato!';
        }
      break;
      case 'aa' :
        if (is_plugin_active( 'amministrazione-aperta/amministrazioneaperta.php' )) {
            include(ABSPATH . 'wp-content/plugins/amministrazione-aperta/govconfig/panels/p_aa_settings.php');
        } else {
            echo 'Plugin non installato!';
        }
      break;
   }
   echo '</table>';

    echo '<center>
    <hr/>
    Sviluppo e ideazione a cura di <a href="http://marcomilesi.ml" target="_blank" title="www.marcomilesi.ml">Marco Milesi</a><br/><small>Per la preparazione di questo programma sono state impiegate diverse ore a titolo volontario.<br>Se vuoi, puoi effettuare una piccola donazione utilizzando il link che trovi in alto a destra.</small><br/><a href="http://www.comune.sanpellegrinoterme.bg.it" title="Made in San Pellegrino Terme">+ Proudly made in San Pellegrino Terme</a></center></div>';
}

function avcp_wpgov_admin_tabs( $current = 'homepage' ) {
    $tabs = array( 'general' => '<span class="dashicons dashicons-admin-home"></span>', 'at' => 'Amministrazione Trasparente', 'avcp' => 'XML Bandi di Gara', 'aa' => 'Amministrazione Aperta' );
    echo '<h2 class="nav-tab-wrapper">';
    echo '<div style="float:right;">
        <a class="add-new-h2" href="http://wpgov.it/soluzioni/">Documentazione</a>
        <a class="add-new-h2" href="http://wpgov.it/supporto/">Supporto</a>
    </div>';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=impostazioni-wpgov&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}
?>
