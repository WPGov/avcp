<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Hide parent selector and description field for our custom taxonomies
// Handle both edit-tags.php (list view) and term.php (individual edit)
function avcp_hide_taxonomy_fields() {
    // Verify we're in the admin area and have proper permissions
    if ( ! current_user_can( 'manage_categories' ) ) {
        return;
    }
    
    // Sanitize and validate the taxonomy parameter
    $taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_key( $_GET['taxonomy'] ) : '';
    
    // Only run for our specific taxonomies
    if ( ! in_array( $taxonomy, array( 'ditte', 'areesettori' ), true ) ) {
        return;
    }

    // Check what page we're on
    global $pagenow;
    $is_term_edit_page = ( 'term.php' === $pagenow );
    $is_edit_tags_page = ( 'edit-tags.php' === $pagenow );
    
    if ( ! $is_term_edit_page && ! $is_edit_tags_page ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            <?php if ( $is_term_edit_page ) : ?>
                // On term.php (individual term edit page)
                $('label[for="parent"]').closest('tr').hide();
                $('label[for="description"]').closest('tr').hide();
                $('#description').closest('tr').hide();
                $('.term-parent-wrap, .term-description-wrap').hide();
            <?php elseif ( $is_edit_tags_page ) : ?>
                <?php $is_edit_action = isset( $_GET['action'] ) && 'edit' === sanitize_key( $_GET['action'] ); ?>
                <?php if ( $is_edit_action ) : ?>
                    // On edit-tags.php with edit action
                    $('label[for="parent"]').closest('tr').hide();
                    $('label[for="description"]').closest('tr').hide();
                    $('#description').closest('tr').hide();
                <?php else : ?>
                    // On edit-tags.php add new page
                    $('label[for="parent"]').closest('.form-field').hide();
                    $('label[for="tag-description"], label[for="description"]').closest('.form-field').hide();
                    $('#tag-description, #description').closest('.form-field').hide();
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
    
    <style type="text/css">
        /* Hide parent and description fields via CSS as backup */
        .term-parent-wrap,
        .term-description-wrap,
        .form-field.term-parent-wrap,
        .form-field.term-description-wrap {
            display: none !important;
        }
    </style>
    <?php
}

// Hook to multiple admin pages
add_action( 'admin_head-edit-tags.php', 'avcp_hide_taxonomy_fields' );
add_action( 'admin_head-term.php', 'avcp_hide_taxonomy_fields' );



// RIMUOVE LA COLONNA "DESCRIZIONE"
add_filter("manage_edit-ditte_columns", 'avcp_ditte_theme_columns');
function avcp_ditte_theme_columns($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'cfiscale' => 'Codice Fiscale',
        'destera' => 'Ditta estera',
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}
//AGGIUNGE LA COLONNA "CODICE FISCALE"
// Add to admin_init function

function add_ditte_column_content($content,$column_name,$term_id){
    switch ($column_name) {
        case 'destera':
            //do your stuff here with $term or $term_id
            $t_id = $term_id;
            $term_meta = get_tax_meta($t_id,'avcp_is_ditta_estera');
            if ( $term_meta ) {
                $term_return = 'Sì';
            } else {
                $term_return = 'No';
            }
            $content = $term_return;
            break;
        case 'cfiscale':
            //do your stuff here with $term or $term_id
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            if ( $term_meta && isset( $term_meta['avcp_codice_fiscale'] ) ) {
                $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
            } else {
                $term_return = '<font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;">Assente</font>';
            }
            $content = $term_return;
            break;
        default:
            break;
    }
    return $content;
}
add_filter('manage_ditte_custom_column', 'add_ditte_column_content',10,3);

// Add term page
function ditte_taxonomy_add_new_meta_field() {
    // this will add the custom meta field to the add new term page
    ?>
    <div class="form-field">
        <label for="term_meta[avcp_codice_fiscale]">Codice Fiscale</label>
        <input type="text" onblur="this.value=rimuoviSpaziBianchi(this.value)"; name="term_meta[avcp_codice_fiscale]" id="term_meta[avcp_codice_fiscale]" value="">
        <p class="description"><?php _e( 'Inserire il Codice Fiscale o la Partita Iva della ditta','pippin' ); ?></p>
    </div>
    <script language="javascript" type="text/javascript">
function rimuoviSpaziBianchi(string) {
 return string.split(' ').join('');
}
</script>
<?php
}
add_action( 'ditte_add_form_fields', 'ditte_taxonomy_add_new_meta_field', 10, 2 );

// Edit term page
function ditte_taxonomy_edit_meta_field($term) {

    // put the term ID into a variable
    $t_id = $term->term_id;

    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[avcp_codice_fiscale]"><?php _e( 'Codice Fiscale', 'pippin' ); ?></label></th>
        <td>
            <input type="text" name="term_meta[avcp_codice_fiscale]" id="term_meta[avcp_codice_fiscale]" value="<?php echo esc_attr( $term_meta['avcp_codice_fiscale'] ) ? esc_attr( $term_meta['avcp_codice_fiscale'] ) : ''; ?>">
            <p class="description"><?php _e( 'Inserire il Codice Fiscale/Partita IVA della ditta','pippin' ); ?></p>
        </td>
    </tr>
<?php
}
add_action( 'ditte_edit_form_fields', 'ditte_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}
add_action( 'edited_ditte', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_ditte', 'save_taxonomy_custom_meta', 10, 2 );
?>
