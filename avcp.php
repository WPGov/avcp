<?php
/*
Plugin Name: ANAC XML Bandi di Gara (AVCP)
Plugin URI: http://www.wpgov.it
Description: Generatore XML per ANAC (ex AVCP) e gestione bandi di gara e contratti (Legge 190/2012 art. 1.32 & D.Lgs 33/2013)
Author: Marco Milesi
Version: 6.1
Author URI: http://www.marcomilesi.ml
*/

add_action( 'init', 'register_cpt_avcp' );

function register_cpt_avcp() {

    $labels = array(
        'name' => _x( 'Bandi di Gara', 'avcp' ),
        'singular_name' => _x( 'Gara', 'avcp' ),
        'add_new' => _x( 'Nuova voce', 'avcp' ),
        'add_new_item' => _x( 'Nuova Gara', 'avcp' ),
        'edit_item' => _x( 'Modifica Gara', 'avcp' ),
        'new_item' => _x( 'Nuova Gara', 'avcp' ),
        'view_item' => _x( 'Vedi Gara', 'avcp' ),
        'search_items' => _x( 'Cerca Gara', 'avcp' ),
        'not_found' => _x( 'Nessuna voce trovata', 'avcp' ),
        'not_found_in_trash' => _x( 'Nessuna voce trovata', 'avcp' ),
        'parent_item_colon' => _x( 'Parent Gara:', 'avcp' ),
        'menu_name' => _x( 'Bandi di Gara', 'avcp' ),
    );

    $get_avcp_enable_editor = get_option('avcp_enable_editor');
    if ($get_avcp_enable_editor == '1') {
        $cp_support = array( 'title', 'custom-fields', 'editor', 'revisions', 'post_tag' );
    } else {
        $cp_support = array( 'title', 'custom-fields', 'revisions', 'post_tag' );
    }

    $get_avcp_abilita_ruoli = get_option('avcp_abilita_ruoli');
    if ($get_avcp_abilita_ruoli == '1') {
        $avcp_capability_type = 'gare_avcp';
        $avcp_map_meta_cap_var = 'true';
        $avcp_capabilities_array = array(
                'publish_posts' => 'pubblicare_gara_avcp',
                'edit_posts' => 'modificare_propri_gara_avcp',
                'edit_others_posts' => 'modificare_altri_gara_avcp',
                'delete_posts' => 'eliminare_propri_gara_avcp',
                'delete_others_posts' => 'modificare_altri_gara_avcp',
                'read_private_posts' => 'read_private_avcp',
                'edit_post' => 'modificare_gara_avcp',
                'delete_post' => 'eliminare_gara_avcp',
                'read_post' => 'leggere_gara_avcp',
                );
    } else {
        $avcp_capability_type = 'post';
        $avcp_map_meta_cap_var = 'false';
    }

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Gare AVCP',
        'supports' => $cp_support,
        'taxonomies' => array( 'ditte' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 37,
        'menu_icon'    => 'dashicons-portfolio',
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => $avcp_capability_type,
        'map_meta_cap' => $avcp_map_meta_cap_var
    );

    register_post_type( 'avcp', $args );
}

add_action( 'init', 'register_taxonomy_ditte' );

function register_taxonomy_ditte() {

    $labels = array(
        'name' => _x( 'Ditte partecipanti', 'ditte' ),
        'singular_name' => _x( 'Ditte', 'ditte' ),
        'search_items' => _x( 'Cerca Ditta', 'ditte' ),
        'popular_items' => _x( 'Ditte più usate', 'ditte' ),
        'all_items' => _x( 'Tutte le ditte', 'ditte' ),
        'parent_item' => _x( 'Parent Ditte', 'ditte' ),
        'parent_item_colon' => _x( 'Parent Ditte:', 'ditte' ),
        'edit_item' => _x( 'Edit Ditte', 'ditte' ),
        'update_item' => _x( 'Update Ditte', 'ditte' ),
        'add_new_item' => _x( 'Nuova Ditta', 'ditte' ),
        'new_item_name' => _x( 'Nuova Ditte', 'ditte' ),
        'separate_items_with_commas' => _x( 'Separate ditta with commas', 'ditte' ),
        'add_or_remove_items' => _x( 'Add or remove ditta', 'ditte' ),
        'choose_from_most_used' => _x( 'Choose from the most used ditta', 'ditte' ),
        'menu_name' => _x( 'Ditte', 'ditte' ),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'ditte', array('avcp'), $args );
}

add_action( 'init', 'register_taxonomy_annirif' );

function register_taxonomy_annirif() {

    $labels2 = array(
        'name' => _x( 'Anno Riferimento', 'annirif' ),
        'singular_name' => _x( 'Anno Riferimento', 'annirif' ),
        'search_items' => _x( 'Cerca Anno', 'annirif' ),
        'popular_items' => _x( 'Anni più Usati', 'annirif' ),
        'all_items' => _x( 'Tutti gli anni', 'annirif' ),
        'parent_item' => _x( 'Parent Anni', 'annirif' ),
        'parent_item_colon' => _x( 'Parent Anno:', 'annirif' ),
        'edit_item' => _x( 'Modifica Anno', 'annirif' ),
        'update_item' => _x( 'Aggiorna Anno', 'annirif' ),
        'add_new_item' => _x( 'Nuovo Anno', 'annirif' ),
        'new_item_name' => _x( 'Nuovo Anno', 'annirif' ),
        'separate_items_with_commas' => _x( 'Separate anno with commas', 'annirif' ),
        'add_or_remove_items' => _x( 'Add or remove anno', 'annirif' ),
        'choose_from_most_used' => _x( 'Choose from the most used years', 'annirif' ),
        'menu_name' => _x( 'Anni', 'annirif' ),
    );

    $args = array(
        'labels' => $labels2,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => true,
        'capabilities' => array('manage_terms' => 'utentealieno','edit_terms'   => 'utentealieno','delete_terms' => 'utentealieno'),
        'query_var' => true
    );

    register_taxonomy( 'annirif', array('avcp'), $args );

}
if(!(function_exists('wpgov_register_taxonomy_areesettori'))){
    add_action( 'init', 'wpgov_register_taxonomy_areesettori' );

    function wpgov_register_taxonomy_areesettori() {

        $labels = array(
            'name' => _x( 'Uffici - Settori - Centri di costo', 'areesettori' ),
            'singular_name' => _x( 'Settore - Centro di costo', 'areesettori' ),
            'search_items' => _x( 'Cerca in Settori - Centri di costo', 'areesettori' ),
            'popular_items' => _x( 'Settori - Centri di costo Più usati', 'areesettori' ),
            'all_items' => _x( 'Tutti i Centri di costo', 'areesettori' ),
            'parent_item' => _x( 'Parent Settore - Centro di costo', 'areesettori' ),
            'parent_item_colon' => _x( 'Parent Settore - Centro di costo:', 'areesettori' ),
            'edit_item' => _x( 'Modifica Settore - Centro di costo', 'areesettori' ),
            'update_item' => _x( 'Aggiorna Settore - Centro di costo', 'areesettori' ),
            'add_new_item' => _x( 'Aggiungi Nuovo Settore - Centro di costo', 'areesettori' ),
            'new_item_name' => _x( 'Nuovo Settore - Centro di costo', 'areesettori' ),
            'separate_items_with_commas' => _x( 'Separate settori - centri di costo with commas', 'areesettori' ),
            'add_or_remove_items' => _x( 'Add or remove settori - centri di costo', 'areesettori' ),
            'choose_from_most_used' => _x( 'Choose from the most used settori - centri di costo', 'areesettori' ),
            'menu_name' => _x( 'Uffici & Settori', 'areesettori' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_tagcloud' => false,
            'show_admin_column' => true,
            'hierarchical' => true,

            'rewrite' => true,
            'query_var' => true
        );
        register_taxonomy( 'areesettori', array('incarico', 'spesa',  'avcp', 'amm-trasparente'), $args );
    }
}

add_action('save_post', 'save_at_gara_posts',10,2);
function save_at_gara_posts($post_id) {

    if ( (get_option('avcp_autopublish') == '1') && (get_post_type($post_id ) == 'avcp') ) {

        $terms = wp_get_post_terms($post_id, 'annirif');

        if ( count($terms) > 0 ) {
            //require_once(plugin_dir_path(__FILE__) . 'avcp_xml_generator.php');
            foreach ( $terms as $term ) {
                creafilexml ($term->name);
                $verificafilecreati = $term->name . ' &bull; ' . $verificafilecreati;
            }
            anac_add_log('Generazione automatica: '.$verificafilecreati, 0);

        }
    }
}

/* =========== Cambio Titolo Custom Post =========== */
function avcp_default_title($title)
{
    $screen = get_current_screen();
    if ('avcp' == $screen->post_type) {
        $title = 'Inserire Oggetto della Gara';
    }
    return $title;
}
add_filter('enter_title_here', 'avcp_default_title');

/* =========== SHORTCODE ============ */

function avcp_func($atts)
{
    extract(shortcode_atts(array('anno' => 'all'), $atts));

    ob_start();
    include(plugin_dir_path(__FILE__) . 'tablegen.php');
    $atshortcode = ob_get_clean();
    return $atshortcode;
}
add_shortcode('avcp', 'avcp_func');
add_shortcode('anac', 'avcp_func');
add_shortcode('gare', 'avcp_func');

function anac_add_log($azione, $errore) {
    if ($errore) { $err = ' style="color:red;"'; } else { $err = ''; }
    update_option('anac_log', get_option('anac_log') . '<br>' . current_time('d/m/Y - H:i') . ' <strong'.$err.'>'.$azione.'</strong>');
}

add_action( 'init', 'atg_caricamoduli' );
function atg_caricamoduli() {
    require_once(plugin_dir_path(__FILE__) . 'avcp_create_taxonomy.php');
    require_once(plugin_dir_path(__FILE__) . 'meta-box-class/metabox.php');
    require_once(plugin_dir_path(__FILE__) . 'tax-meta-class/Tax-meta-class.php');
    require_once(plugin_dir_path(__FILE__) . 'avcp_taxonomy_fields.php');
    require_once(plugin_dir_path(__FILE__) . 'avcp_metabox_generator.php');
    require_once(plugin_dir_path(__FILE__) . 'avcp_index_generator.php');
    require_once(plugin_dir_path(__FILE__) . 'singlehack.php');
    require_once(plugin_dir_path(__FILE__) . 'avcp_xml_generator.php');
    require_once(plugin_dir_path(__FILE__) . 'opendata/loader.php');

    //Funzioni
    require_once(plugin_dir_path(__FILE__) . 'getsommeliquidate.php');

    //Include sistemi di validazione
    require_once(plugin_dir_path(__FILE__) . 'valid_check.php');
    require_once(plugin_dir_path(__FILE__) . 'valid_page.php');
    require_once(plugin_dir_path(__FILE__) . 'searchTaxonomy/searchTaxonomyGT.php');
    global $typenow;
    if ($typenow == 'avcp') {
        add_filter( 'manage_posts_custom_column', 'avcp_modify_post_table_row', 10, 2 );
        add_filter( 'manage_posts_custom_column', 'avcp_modify_post_table' );
    }
}

add_action( 'admin_init', 'AVCP_ADMIN_LOAD');
function AVCP_ADMIN_LOAD () {
    require_once(plugin_dir_path(__FILE__) . 'taxfilteringbackend.php');

    require_once(plugin_dir_path(__FILE__) . 'alerts.php');
    require_once(plugin_dir_path(__FILE__) . 'styledbackend.php');
    require_once(plugin_dir_path(__FILE__) . 'register_setting.php');
    require_once(plugin_dir_path(__FILE__) . 'pannelli/log.php');
    require_once(plugin_dir_path(__FILE__) . 'pannelli/import.php');


    //Controllo Versione e Utilità
    $arraya_anac_v = get_plugin_data ( __FILE__ );
    $nuova_versione_anac = $arraya_anac_v['Version'];

    $versione_attuale_anac = get_option('avcp_version_number');
    if ($versione_attuale_anac == '') {
        update_option( 'avcp_version_number', $nuova_versione_anac );
        crea_anni();
    } else if (version_compare($versione_attuale_anac, $nuova_versione_anac, '<') == '1') {
        crea_anni();
        require_once(plugin_dir_path(__FILE__) . 'update.php');
        update_option( 'avcp_version_number', $nuova_versione_anac );
        anac_add_log('Aggiornato plugin ' . $nuova_versione_anac, 0);
    }
}

function crea_anni() {
    $array_anni = array("2013", "2014", "2015", "2016", "2017", "2018");
    $array_anni_dim = count($array_anni);

    for($x = 0; $x < $array_anni_dim; $x++) {
        $termcheck = term_exists($array_anni[$x], 'annirif');
        if ($termcheck == 0 || $termcheck == null) {
            wp_insert_term($array_anni[$x], 'annirif');
            anac_add_log('Aggiunto anno di riferimento <strong>'.$array_anni[$x].'</strong>', 0);
        }
    }
}

add_action('admin_menu', 'avcp_menu');
function avcp_menu() {

    add_submenu_page('edit.php?post_type=avcp', 'Importa', 'Importa', 'manage_options', 'anac_import', 'anac_import_load');

     if (!get_option('avcp_dataset_capability')) {
         $anac_menu_cap = 'manage_options';
    } else {
         $anac_menu_cap = get_option('avcp_dataset_capability');
     }

    add_submenu_page('edit.php?post_type=avcp', 'Dataset XML AVCP', 'Dataset XML AVCP',  $anac_menu_cap, 'avcp_v_dataset', 'avcp_v_dataset_load'); //valid_page.php
    add_submenu_page('edit.php?post_type=avcp', 'Log', 'Log',  $anac_menu_cap, 'anac_log', 'anac_log_load'); //pannelli/log.php
}

function avcp_activate() {
    $srcfile= ABSPATH . 'wp-content/plugins/avcp/includes/index.php.null';
    $dstfile= ABSPATH . 'avcp/index.php';
    mkdir(dirname($dstfile), 0755, true);
    copy($srcfile, $dstfile);
    chmod($dstfile, 0755);
}
register_activation_hook( __FILE__, 'avcp_activate' );

function avcp_deactivate() {
    unlink(ABSPATH . 'avcp/index.php');
}
register_deactivation_hook( __FILE__, 'avcp_deactivate' );

require_once(plugin_dir_path(__FILE__) . 'govconfig/loader_shared.php'); //Caricatore impostazioni wpgov.it

?>
