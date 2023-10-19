<?php
//FILTRI
add_action( 'restrict_manage_posts', function() {
    global $typenow;
    $taxonomy = 'ditte';
    if ($typenow == 'avcp') {
        $filters = array($taxonomy);
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Tutte le ditte</option>";
            foreach ($terms as $term) {
                $label = (isset($_GET[$tax_slug])) ? $_GET[$tax_slug] : ''; // Fix
                echo '<option value='. $term->slug, $label == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
            }
            echo "</select>";
        }
    }
    $taxonomy = 'annirif';
    if ($typenow == 'avcp') {
        $filters = array($taxonomy);
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Tutti gli Anni di Riferimento</option>";
            foreach ($terms as $term) {
                $label = (isset($_GET[$tax_slug])) ? $_GET[$tax_slug] : ''; // Fix
                echo '<option value='. $term->slug, $label == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
            }
            echo "</select>";
        }
    }
} );

add_filter( 'manage_edit-avcp_columns', function( $column ) {
    $column['avcp_CIG'] = 'CIG';
    $column['avcp_AGG'] = 'Aggiudicatari';
    $column['date'] = 'Data pubblicazione sul sito';
    return $column;
} );

function avcp_modify_post_table_row( $column_name, $post_id ) {
    $custom_fields = get_post_custom( $post_id );

    switch ($column_name) {
        case 'avcp_CIG' :
            if ( isset($custom_fields['avcp_cig'][0]) ) {
                echo $custom_fields['avcp_cig'][0];
            } else {
                echo '<center><font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;">Nessuno</font></center>';
            
            }
            break;
        case 'avcp_AGG' :
            $checkok = 0;
            $dittepartecipanti = get_the_terms( $post_id, 'ditte' );
            $cats = get_post_meta($post_id,'avcp_aggiudicatari',true);
            if(is_array($dittepartecipanti)) {
                foreach ($dittepartecipanti as $term) {
                    $cterm = get_term_by('name',$term->name,'ditte');
                    $cat_id = $cterm->term_id; //Prende l'id del termine
                    $term_meta = get_option( "taxonomy_$cat_id" );
                    $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
                    $checked = (in_array($cat_id,(array)$cats)? ' checked="checked"': "");
                    if ($checked) {
                        echo $term->name . ', ';
                        $checkok++;
                    }
                }
            }
            if ($checkok == 0) {
                echo '<center><font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;">Nessuno</font></center>';
            }
            break;

        default:
    }
}
add_filter( 'manage_posts_custom_column', 'avcp_modify_post_table_row', 10, 2 );

function my_sortable_cake_column( $columns ) {
    $column['avcp_CIG'] = 'CIG';
    $column['avcp_AGG'] = 'AGG';

    return $columns;
}
add_filter( 'manage_edit-cake_sortable_columns', 'my_sortable_cake_column' );

/*
 * ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
 * https://gist.github.com/906872
 */
add_filter("manage_edit-avcp_sortable_columns", 'avcp_date_sort');
function avcp_date_sort($columns) {
    $column['avcp_CIG'] = 'CIG';
    return $column;
}
?>
