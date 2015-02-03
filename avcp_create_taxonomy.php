<?php

add_action( 'admin_footer-edit-tags.php', 'wpse_56569_remove_cat_tag_description' );

function wpse_56569_remove_cat_tag_description(){
    global $current_screen;
    switch ( $current_screen->id )
    {
        case 'edit-ditte': ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('#tag-description').parent().remove();
    });
    </script>
    <?php  case 'edit-areesettori': ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('#tag-description').parent().remove();
    });
    </script>
    <?php  break;
    }
    ?>
    <?php
}

add_action( 'admin_head-edit-tags.php', 'avcp_58799_remove_parent_category' );

function avcp_58799_remove_parent_category()
{
    // don't run in the Tags screen
    if ( 'ditte' != $_GET['taxonomy'] && 'areesettori' != $_GET['taxonomy'])
        return;

    // Screenshot_1 = New Category
    // http://example.com/wp-admin/edit-tags.php?taxonomy=category
    $parent = 'parent()';

    // Screenshot_2 = Edit Category
    // http://example.com/wp-admin/edit-tags.php?action=edit&taxonomy=category&tag_ID=17&post_type=post
    if ( isset( $_GET['action'] ) )
        $parent = 'parent().parent()';

    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                $('label[for=parent]').<?php echo $parent; ?>.remove();
            });
        </script>
    <?php
}

// RIMUOVE LA COLONNA "DESCRIZIONE"
add_filter("manage_edit-ditte_columns", 'avcp_ditte_theme_columns');
function avcp_ditte_theme_columns($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'cfiscale' => 'Codice Fiscale',
//      'description' => __('Description'),
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}
//AGGIUNGE LA COLONNA "CODICE FISCALE"
// Add to admin_init function

function add_ditte_column_content($content,$column_name,$term_id){
    switch ($column_name) {
        case 'cfiscale':
            //do your stuff here with $term or $term_id
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
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
