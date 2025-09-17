<?php
/**
 * AVCP Table Generator
 * 
 * Generates a filterable table of AVCP tender data with export functionality
 * 
 * @package AVCP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Validate and sanitize the year parameter
if (!isset($anno)) {
    $anno = 'all';
}

// Enhanced validation for year parameter
$anno = sanitize_text_field($anno);
if ($anno !== 'all' && !empty($anno)) {
    // Validate year format (should be 4 digits)
    if (!preg_match('/^\d{4}$/', $anno)) {
        $anno = 'all';
    } else {
        // Ensure year is within reasonable range
        $year_int = intval($anno);
        $current_year = date('Y');
        if ($year_int < 2000 || $year_int > ($current_year + 10)) {
            $anno = 'all';
        }
    }
}
?>
<script type="text/javascript" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'includes/excellentexport.min.js'); ?>"></script>
<div class="avcp-table-container">
    <table class="order-table table" id="gare" role="table" aria-label="<?php esc_attr_e('Tabella bandi di gara', 'avcp'); ?>">
        <caption class="screen-reader-text">
            <?php esc_html_e('Elenco dei bandi di gara pubblicati', 'avcp'); ?>
        </caption>
        <thead>
            <tr>
                <td colspan="6" class="table-header-info">
                    <div class="table-title">
                        <?php esc_html_e('Bandi di gara', 'avcp'); ?> - <strong><?php 
                            echo ($anno !== 'all') ? esc_html($anno) : esc_html__('Tutti gli anni', 'avcp'); 
                        ?></strong>
                    </div>
                    <div class="table-search">
                        <label for="s" class="screen-reader-text"><?php esc_html_e('Cerca nei bandi:', 'avcp'); ?></label>
                        <input type="search" id="s" class="light-table-filter" 
                               data-table="order-table" 
                               placeholder="<?php esc_attr_e('Cerca...', 'avcp'); ?>" 
                               maxlength="100" 
                               autocomplete="off"
                               aria-describedby="search-help">
                        <span id="search-help" class="screen-reader-text">
                            <?php esc_html_e('Digita per filtrare i risultati della tabella', 'avcp'); ?>
                        </span>
                    </div>
                </td>
            </tr>
            <tr role="row">
                <th scope="col" colspan="2"><?php esc_html_e('Oggetto', 'avcp'); ?></th>
                <th scope="col"><?php esc_html_e('CIG', 'avcp'); ?></th>
                <th scope="col"><?php esc_html_e('Importo aggiudicazione', 'avcp'); ?></th>
                <th scope="col"><?php esc_html_e('Durata lavori', 'avcp'); ?></th>
                <th scope="col"><?php esc_html_e('Modalità affidamento', 'avcp'); ?></th>
            </tr>
        </thead>
        <tbody role="rowgroup">

<?php
// Prepare query arguments with validation
$args = array(
    'post_type'      => 'avcp',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(),
    'fields'         => 'ids', // Only get IDs first for better performance
    'no_found_rows'  => true,  // Skip pagination count for better performance
);

// Add taxonomy filter if specific year is requested
if ($anno !== '' && $anno !== 'all') {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'annirif',
            'field'    => 'slug',
            'terms'    => sanitize_title($anno),
        ),
    );
}

// Execute query with error handling
$post_ids = get_posts($args);

if (!empty($post_ids) && !is_wp_error($post_ids)) :
    
    // Pre-load all meta data in one query for better performance
    $meta_keys = array('avcp_data_inizio', 'avcp_data_fine', 'avcp_cig', 'avcp_aggiudicazione', 'avcp_contraente');
    $meta_cache = array();
    
    foreach ($post_ids as $post_id) {
        $post_id = absint($post_id);
        if ($post_id) {
            $meta_cache[$post_id] = get_post_meta($post_id);
        }
    }
    
    foreach ($post_ids as $post_id) :
        $post_id = absint($post_id);
        if (!$post_id) {
            continue;
        }
        
        $post = get_post($post_id);
        if (!$post || !is_object($post)) {
            continue;
        }

        // Get meta data from cache
        $post_meta = isset($meta_cache[$post_id]) ? $meta_cache[$post_id] : array();
        
        $d_i = isset($post_meta['avcp_data_inizio'][0]) ? sanitize_text_field($post_meta['avcp_data_inizio'][0]) : '';
        $d_f = isset($post_meta['avcp_data_fine'][0]) ? sanitize_text_field($post_meta['avcp_data_fine'][0]) : '';
        $cig = isset($post_meta['avcp_cig'][0]) ? sanitize_text_field($post_meta['avcp_cig'][0]) : '';
        $aggiudicazione = isset($post_meta['avcp_aggiudicazione'][0]) ? sanitize_text_field($post_meta['avcp_aggiudicazione'][0]) : '';
        $contraente = isset($post_meta['avcp_contraente'][0]) ? sanitize_text_field($post_meta['avcp_contraente'][0]) : '';

        // Format dates safely
        $d_i_fmt = '-';
        $d_f_fmt = '-';
        
        if ($d_i && strtotime($d_i)) {
            $d_i_fmt = date_i18n("d/m/Y", strtotime($d_i));
        }
        
        if ($d_f && strtotime($d_f)) {
            $d_f_fmt = date_i18n("d/m/Y", strtotime($d_f));
        }

        // Output table row with proper escaping and accessibility
        echo '<tr role="row">';
        
        // Title column with link
        $post_title = get_the_title($post_id);
        $post_permalink = get_permalink($post_id);
        
        if ($post_title && $post_permalink) {
            echo '<td colspan="2" role="gridcell">';
            echo '<a href="' . esc_url($post_permalink) . '" title="' . esc_attr(sprintf(__('Visualizza dettagli di: %s', 'avcp'), $post_title)) . '">';
            echo esc_html($post_title);
            echo '</a></td>';
        } else {
            echo '<td colspan="2" role="gridcell">' . esc_html__('Oggetto non disponibile', 'avcp') . '</td>';
        }
        
        // CIG column
        echo '<td role="gridcell">' . esc_html($cig) . '</td>';
        
        // Amount column
        echo '<td role="gridcell" style="text-align: center;">€<strong>' . esc_html($aggiudicazione) . '</strong></td>';
        
        // Duration column
        echo '<td role="gridcell" style="text-align: center;">';
        echo '<div class="date-info">';
        echo '<span class="start-date">' . esc_html($d_i_fmt) . '</span><br/>';
        echo '<span class="end-date">' . esc_html($d_f_fmt) . '</span><br/>';

        // Calculate duration if both dates are valid
        if (class_exists('DateTime') && $d_i && $d_f && strtotime($d_i) && strtotime($d_f)) {
            try {
                $date1 = new DateTime($d_i);
                $date2 = new DateTime($d_f);
                $diff = $date2->diff($date1);
                if ($diff && $diff->days !== false) {
                    echo '<small class="duration"><strong>' . esc_html($diff->days) . '</strong> ' . esc_html__('gg', 'avcp') . '</small>';
                }
            } catch (Exception $e) {
                // Log error if needed, but don't display to user
                error_log('AVCP Date calculation error: ' . $e->getMessage());
            }
        }
        echo '</div></td>';

        // Contract type column
        $contraente_display = '';
        if ($contraente && strlen($contraente) > 3) {
            $contraente_display = strtolower(substr($contraente, 3));
        }
        echo '<td role="gridcell">' . esc_html($contraente_display) . '</td>';
        
        echo '</tr>';

    endforeach;
else :
    // No posts found
    echo '<tr role="row"><td colspan="6" role="gridcell" style="text-align: center; padding: 20px;">' . esc_html__('Nessun bando trovato per i criteri selezionati.', 'avcp') . '</td></tr>';
endif;

echo '</tbody>
<tfoot role="rowgroup">
    <tr role="row">
        <td colspan="6" role="gridcell" class="table-footer">';

        // Export buttons section
        echo '<div class="export-buttons" style="float:right;" role="group" aria-label="' . esc_attr__('Opzioni di esportazione', 'avcp') . '">';
        echo '<span class="export-label">' . esc_html__('Scarica in', 'avcp') . ':</span> ';

        // XML export
        $xml_url = get_site_url() . '/avcp';
        echo '<a href="' . esc_url($xml_url) . '" target="_blank" rel="noopener" title="' . esc_attr__('Scarica file XML', 'avcp') . '" class="export-btn">';
        echo '<button type="button" aria-label="' . esc_attr__('Esporta in formato XML', 'avcp') . '">' . esc_html__('XML', 'avcp') . '</button></a> ';

        // Excel export
        $site_name = sanitize_file_name(get_bloginfo('name'));
        $excel_filename = $site_name . '-gare' . sanitize_file_name($anno) . '.xls';
        echo '<a download="' . esc_attr($excel_filename) . '" href="#" onclick="return ExcellentExport.excel(this, \'gare\', \'Gare\');" class="export-btn">';
        echo '<button type="button" aria-label="' . esc_attr__('Esporta in formato Excel', 'avcp') . '">' . esc_html__('EXCEL', 'avcp') . '</button></a> ';

        // CSV export  
        $csv_filename = $site_name . '-gare' . sanitize_file_name($anno) . '.csv';
        echo '<a download="' . esc_attr($csv_filename) . '" href="#" onclick="return ExcellentExport.csv(this, \'gare\');" class="export-btn">';
        echo '<button type="button" aria-label="' . esc_attr__('Esporta in formato CSV', 'avcp') . '">' . esc_html__('CSV', 'avcp') . '</button></a>';

        echo '</div>';

        // WPGov logo section
        if (get_option('wpgov_show_love')) {
            $logo_url = plugin_dir_url(__FILE__) . 'images/wpgov.png';
            echo '<div class="wpgov-credit">';
            echo '<a href="' . esc_url('https://www.wpgov.it') . '" target="_blank" rel="noopener" title="' . esc_attr__('Software sviluppato da WPGov', 'avcp') . '">';
            echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr__('Logo WPGov', 'avcp') . '" style="max-height: 30px;" /></a>';
            echo '</div>';
        }

        echo '</td>
    </tr>
</tfoot>
</table>
</div>';

echo '<div class="clear"></div>';
?>

<script type="text/javascript">
/**
 * AVCP Table Filter
 * Provides client-side filtering functionality for the tender table
 */
(function(document) {
    'use strict';

    var AVCPTableFilter = (function(Arr) {
        var _input;

        /**
         * Handle input events for table filtering
         * @param {Event} e - The input event
         */
        function _onInputEvent(e) {
            _input = e.target;
            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            
            if (!tables || tables.length === 0) {
                return;
            }
            
            Arr.forEach.call(tables, function(table) {
                if (table.tBodies) {
                    Arr.forEach.call(table.tBodies, function(tbody) {
                        if (tbody.rows) {
                            Arr.forEach.call(tbody.rows, _filter);
                        }
                    });
                }
            });
        }

        /**
         * Filter individual table rows based on search input
         * @param {HTMLElement} row - The table row to filter
         */
        function _filter(row) {
            if (!row || !row.textContent || !_input) {
                return;
            }
            
            var text = row.textContent.toLowerCase();
            var val = _input.value.toLowerCase().trim();
            
            // Show all rows if search is empty
            if (val === '') {
                row.style.display = 'table-row';
                return;
            }
            
            // Hide/show row based on text match
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }

        /**
         * Initialize the table filter functionality
         */
        function _init() {
            var inputs = document.getElementsByClassName('light-table-filter');
            
            if (!inputs || inputs.length === 0) {
                return;
            }
            
            Arr.forEach.call(inputs, function(input) {
                if (input) {
                    input.oninput = _onInputEvent;
                    // Also handle paste events
                    input.onpaste = function() {
                        setTimeout(_onInputEvent.bind(null, {target: input}), 10);
                    };
                }
            });
        }

        return {
            init: _init
        };
    })(Array.prototype);

    /**
     * Initialize when DOM is ready
     */
    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete') {
            AVCPTableFilter.init();
        }
    });

    // Fallback for older browsers
    if (document.readyState === 'complete') {
        AVCPTableFilter.init();
    }

})(document);
</script>
