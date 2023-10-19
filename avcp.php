<?php
/*
Plugin Name: ANAC XML Bandi di Gara
Plugin URI: https://wordpress.org/plugins/avcp
Description: Generatore XML per ANAC (ex AVCP) e gestione bandi di gara e contratti (Legge 190/2012 art. 1.32 & D.Lgs 33/2013)
Author: Marco Milesi
Version: 7.5
Author URI: https://www.marcomilesi.com
*/

add_action( 'init', 'register_cpt_avcp' );

function register_cpt_avcp() {

    $labels = array(
        'name' => 'Bandi di gara',
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
        'menu_name' => 'Bandi'
    );

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
        'supports' => array( 'title', 'custom-fields', 'editor', 'revisions', 'post_tag' ),
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

add_action( 'init',  function() {
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
} );

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
add_filter('enter_title_here', function($title) {
    $screen = get_current_screen();
    if ('avcp' == $screen->post_type) {
        $title = 'Oggetto della Gara';
    }
    return $title;
});

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
    require_once(plugin_dir_path(__FILE__) . 'valid_page.php');
    global $typenow;
    if ($typenow == 'avcp') {
        add_filter( 'manage_posts_custom_column', 'avcp_modify_post_table_row', 10, 2 );
        add_filter( 'manage_posts_custom_column', 'avcp_modify_post_table' );
    }
}


add_action('admin_enqueue_scripts', function( $hook ) {

    global $post;
    if ( ('post-new.php' == $hook || 'post.php' == $hook) && 'avcp' === $post->post_type ) {
		wp_register_style( 'avcp_style',  plugins_url('avcp/includes/avcp_admin.css') );
        wp_enqueue_style( 'avcp_style');

        wp_enqueue_script( 'avcp_functions', plugins_url('avcp/includes/avcp_functions.js'), array( 'jquery' ) );


        wp_register_script('avcp_searchTaxonomyGT_at_js', plugins_url('includes/searchTaxonomyGT.js', __FILE__));
        wp_enqueue_script('avcp_searchTaxonomyGT_at_js');
    }
});

add_action( 'admin_init', 'AVCP_ADMIN_LOAD');
function AVCP_ADMIN_LOAD () {

    require_once(plugin_dir_path(__FILE__) . 'taxfilteringbackend.php');

    require_once(plugin_dir_path(__FILE__) . 'alerts.php');
    require_once(plugin_dir_path(__FILE__) . 'register_setting.php');
    require_once(plugin_dir_path(__FILE__) . 'pannelli/import.php');


    //Controllo Versione e Utilità
    $arraya_anac_v = get_plugin_data ( __FILE__ );
    $nuova_versione_anac = $arraya_anac_v['Version'];

    $versione_attuale_anac = get_option('avcp_version_number');
    if ($versione_attuale_anac == '') {
        update_option( 'avcp_version_number', $nuova_versione_anac );
        crea_anni();
        avcp_activate();
    } else if (version_compare($versione_attuale_anac, $nuova_versione_anac, '<') == '1') {
        crea_anni();
        avcp_activate();
        require_once(plugin_dir_path(__FILE__) . 'update.php');
        update_option( 'avcp_version_number', $nuova_versione_anac );
        anac_add_log('Aggiornato plugin ' . $nuova_versione_anac, 0);
    }
    delete_option('avcp_invalid');
}

function crea_anni() {

    for( $x = 2013; $x <= 2025; $x++) {
        $termcheck = term_exists( $x, 'annirif');
        if ($termcheck == 0 || $termcheck == null) {
            wp_insert_term( $x, 'annirif');
            anac_add_log('Aggiunto anno di riferimento <strong>'.$x.'</strong>', 0);
        }
    }
}

add_action('admin_menu', function() {

    if ( !wp_count_posts( 'avcp' ) || wp_count_posts( 'avcp')->publish == 0 ) {
        add_submenu_page('edit.php?post_type=avcp', 'Importa', 'Importa', 'manage_options', 'anac_import', 'anac_import_load');
    }

    if (!get_option('avcp_dataset_capability')) {
         $anac_menu_cap = 'manage_options';
    } else {
         $anac_menu_cap = get_option('avcp_dataset_capability');
    }

    add_submenu_page('edit.php?post_type=avcp', 'Validazione', 'Validazione',  $anac_menu_cap, 'avcp_v_dataset', 'avcp_v_dataset_load'); //valid_page.php

    add_submenu_page( 'edit.php?post_type=avcp', 'Impostazioni', 'Impostazioni', 'publish_posts', 'wpgov_avcp', function() { include(plugin_dir_path(__FILE__) . 'settings.php'); } );

});

function avcp_activate() {
    $srcfile= ABSPATH . 'wp-content/plugins/avcp/includes/index.php.null';
    $dstfile= avcp_get_basexmlpath() . 'index.php';
    mkdir(dirname($dstfile), 0755, true);
    copy($srcfile, $dstfile);
    chmod($dstfile, 0755);
}

function avcp_deactivate() {
    unlink(ABSPATH . 'avcp/index.php');
}
register_deactivation_hook( __FILE__, 'avcp_deactivate' );


function avcp_get_contraente($input) {
    $tipi_contraente = array(
        array('01','01-PROCEDURA APERTA'),
        array('02','02-PROCEDURA RISTRETTA'),
        array('03','03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE'),
        array('04','04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE'),
        array('05','05-DIALOGO COMPETITIVO'),
        array('06','06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)'),
        array('07','07-SISTEMA DINAMICO DI ACQUISIZIONE'),
        array('08','08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO'),
        array('14','14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006'),
        array('17','17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE 381/91'),
        array('21','21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA'),
        array('22','22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)'),
        array('23','23-AFFIDAMENTO DIRETTO'),
        array('24','24-AFFIDAMENTO DIRETTO A SOCIETA\' IN HOUSE'),
        array('25','25-AFFIDAMENTO DIRETTO A SOCIETA\' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI E NEI PARTENARIATI'),
        array('26','26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'),
        array('27','27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'),
        array('28','28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI'),
        array('29','29-PROCEDURA RISTRETTA SEMPLIFICATA'),
        array('30','30-PROCEDURA DERIVANTE DA LEGGE REGIONALE'),
        array('31','31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL\'IMPORTO CONTRATTUALE'),
        array('32','32-AFFIDAMENTO RISERVATO'),
        array('33','33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA'),
        array('34','34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA'),
        array('35','35-PARTERNARIATO PER L’INNOVAZIONE'),
        array('36','36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI'),
        array('37','37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE'),
        array('38','38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI'),
    );
    foreach ( $tipi_contraente as $tc ) {
        if ( substr($input, 0, 2) == $tc[0] ) {
            return $tc[1];
        }
    }
    return "Non definito";
}
function avcp_get_basexmlurl($year = 0) {
    $base = apply_filters( 'anac_filter_basexmlurl', get_site_url() . '/avcp/' );
    if ( $year ) {
        return $base . $year.'.xml';
    } else {
        return $base;
    }
}
function avcp_get_basexmlpath($year = 0) {
    $base = apply_filters( 'anac_filter_basexmlpath', ABSPATH . 'avcp/' );
    if ( $year ) {
        return $base . $year . '.xml';
    } else {
        return $base;
    }
}


?>
