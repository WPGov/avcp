<?php
/**
 * AVCP Single Post Content Filter
 * 
 * Enhances the content display for AVCP post types with additional metadata
 * and contractor information.
 * 
 * @package AVCP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AVCP Single Post Content Filter
 * 
 * Enhances the content display for AVCP post types with additional metadata
 * and contractor information.
 * 
 * @package AVCP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates the basic contract information table
 * 
 * @param int $post_id The post ID
 * @param array $options Cached options array
 * @return string HTML table content
 */
function avcp_generate_contract_info_table($post_id, $options) {
    $html = '<table>';
    
    // CIG
    $html .= '<tr><td><acronym title="' . esc_attr__('Codice Identificativo Gara', 'avcp') . '">CIG:</acronym></td><td>' . esc_html(get_post_meta($post_id, 'avcp_cig', true)) . '</td></tr>';
    
    // Proposing structure
    $html .= '<tr><td>' . esc_html__('Struttura proponente:', 'avcp') . '</td><td>' . esc_html($options['denominazione_ente']);
    
    // Area sectors
    $html .= avcp_generate_area_sectors($post_id);
    
    $html .= '<br/>' . esc_html($options['codicefiscale_ente']) . '</td></tr>';
    
    // Contract details
    $html .= '<tr><td>' . esc_html__('Oggetto del bando:', 'avcp') . '</td><td>' . esc_html(get_the_title($post_id)) . '</td></tr>';
    
    // Check if avcp_get_contraente function exists
    if (function_exists('avcp_get_contraente')) {
        $contraente = avcp_get_contraente(get_post_meta($post_id, 'avcp_contraente', true));
        $html .= '<tr><td>' . esc_html__('Procedura di scelta del contraente:', 'avcp') . '</td><td>' . esc_html(strtolower($contraente)) . '</td></tr>';
    }
    
    // Award amount
    $aggiudicazione_amount = get_post_meta($post_id, 'avcp_aggiudicazione', true);
    $html .= '<tr><td>' . esc_html__('Importo di aggiudicazione:', 'avcp') . '</td><td>€ <strong>' . esc_html($aggiudicazione_amount) . '</strong></td></tr>';
    
    // Dates
    $html .= avcp_generate_contract_dates($post_id);
    
    // Liquidated amounts
    $html .= avcp_generate_liquidated_amounts($post_id);
    
    // Reference year
    $html .= avcp_generate_reference_year($post_id, $options);
    
    $html .= '</table>';
    
    return $html;
}

/**
 * Generates area sectors information
 * 
 * @param int $post_id The post ID
 * @return string HTML content for area sectors
 */
function avcp_generate_area_sectors($post_id) {
    $html = '';
    $terms = get_the_terms($post_id, 'areesettori');
    
    if ($terms && !is_wp_error($terms)) {
        foreach($terms as $term) {
            if (!is_object($term) || empty($term->name)) {
                continue;
            }
            
            $get_term = get_term_by('name', $term->name, 'areesettori');
            if (!$get_term || is_wp_error($get_term)) {
                continue;
            }
            
            $tsr_id = absint($get_term->term_id);
            
            // Check if get_tax_meta function exists before using it
            if (function_exists('get_tax_meta')) {
                $id_sec_red_var = get_tax_meta($tsr_id, 'aree_settori_cc_url');
                
                if ($id_sec_red_var) {
                    $permalink = get_permalink(absint($id_sec_red_var));
                    if ($permalink) {
                        $html .= ' - <a href="' . esc_url($permalink) . '">' . esc_html($term->name) . '</a>';
                    }
                }
                
                $id_sec_cc_var = get_tax_meta($tsr_id, 'aree_settori_cc_responsabile');
                if (!empty($id_sec_cc_var)) {
                    $html .= ' - [<acronym title="' . esc_attr__('Responsabile del Centro di Costo', 'avcp') . '">resp. <b>' . esc_html($id_sec_cc_var) . '</b></acronym>]';
                }
            } else {
                // Fallback if get_tax_meta doesn't exist
                $html .= ' - ' . esc_html($term->name);
            }
        }
    }
    
    return $html;
}

/**
 * Generates contract dates section
 * 
 * @param int $post_id The post ID
 * @return string HTML content for dates
 */
function avcp_generate_contract_dates($post_id) {
    $d_inizio = sanitize_text_field(get_post_meta($post_id, 'avcp_data_inizio', true));
    $d_fine = sanitize_text_field(get_post_meta($post_id, 'avcp_data_fine', true));

    $avcp_data_inizio = $d_inizio ? date_i18n("d/m/Y", strtotime($d_inizio)) : esc_html__('n.d.', 'avcp');
    $avcp_data_fine = $d_fine ? date_i18n("d/m/Y", strtotime($d_fine)) : esc_html__('n.d.', 'avcp');

    $html = '<tr><td>' . esc_html__('Data di effettivo inizio:', 'avcp') . '</td><td>' . esc_html($avcp_data_inizio) . '</td></tr>';
    $html .= '<tr><td>' . esc_html__('Data di ultimazione:', 'avcp') . '</td><td>' . esc_html($avcp_data_fine) . '</td></tr>';
    
    return $html;
}

/**
 * Generates liquidated amounts section
 * 
 * @param int $post_id The post ID
 * @return string HTML content for liquidated amounts
 */
function avcp_generate_liquidated_amounts($post_id) {
    $html = '<tr><td>' . esc_html__('Importo delle somme liquidate:', 'avcp') . '</td><td>';

    $current_year = date('Y');
    for ($i = 2013; $i <= $current_year + 10; $i++) {
        $amount = get_post_meta($post_id, 'avcp_s_l_' . absint($i), true);
        if ($amount && floatval($amount) > 0) {
            $html .= '<strong>' . absint($i) . '</strong>: ' . esc_html($amount) . '<br>';
        }
    }
    $html .= '</td></tr>';
    
    return $html;
}

/**
 * Generates reference year section
 * 
 * @param int $post_id The post ID
 * @param array $options Cached options array
 * @return string HTML content for reference year
 */
function avcp_generate_reference_year($post_id, $options) {
    global $post;
    
    $html = '<tr><td>' . esc_html__('Anno di riferimento:', 'avcp') . '</td><td>';
    
    if ($options['dis_archivioanni'] == '1') {
        $term_list = get_the_term_list($post->ID, 'annirif', '', ' - ', '');
        $html .= wp_strip_all_tags($term_list);
    } else {
        $html .= get_the_term_list($post->ID, 'annirif', '', ' - ', '');
    }
    
    $html .= '</td></tr>';
    
    return $html;
}

/**
 * Generates the participating operators table
 * 
 * @param int $post_id The post ID
 * @param array $options Cached options array
 * @return string HTML table content
 */
function avcp_generate_participating_operators($post_id, $options) {
    global $post;
    
    $html = '<h3>' . esc_html__('Elenco degli operatori partecipanti', 'avcp') . '</h3>';
    $html .= '<table>';
    
    $terms = get_the_terms($post->ID, 'ditte');
    if ($terms && !is_wp_error($terms)) {
        foreach($terms as $term) {
            if (!is_object($term) || empty($term->name)) {
                continue;
            }
            
            $get_term = get_term_by('name', $term->name, 'ditte');
            if (!$get_term || is_wp_error($get_term)) {
                continue;
            }
            
            $t_id = absint($get_term->term_id);
            $term_meta = get_option("taxonomy_$t_id");
            $term_return = '';
            
            if (is_array($term_meta) && isset($term_meta['avcp_codice_fiscale'])) {
                $term_return = esc_attr($term_meta['avcp_codice_fiscale']);
            }
            
            $is_estera = '<acronym title="' . esc_attr__('Identificativo Fiscale Italiano', 'avcp') . '">IT</acronym>';
            
            // Check if get_tax_meta function exists before using it
            if (function_exists('get_tax_meta')) {
                $stato_var = get_tax_meta($t_id, 'avcp_is_ditta_estera');
                $is_estera = empty($stato_var) ? 
                    '<acronym title="' . esc_attr__('Identificativo Fiscale Italiano', 'avcp') . '">IT</acronym>' : 
                    '<acronym title="' . esc_attr__('Identificativo Fiscale Estero', 'avcp') . '">EE</acronym>';
            }
                
            $html .= '<tr><td>';
            
            if ($options['dis_archivioditte'] == '1') {
                $html .= esc_html($term->name);
            } else {
                $term_link = get_term_link($t_id, 'ditte');
                if (!is_wp_error($term_link)) {
                    $html .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '">' . esc_html($term->name) . '</a>';
                } else {
                    $html .= esc_html($term->name);
                }
            }

            $html .= '</td><td>' . esc_html($term_return) . ' - <b>' . $is_estera . '</b></td></tr>';
        }
    }
    
    $html .= '</table>';
    
    return $html;
}

/**
 * Generates the winning operators table
 * 
 * @param int $post_id The post ID
 * @param array $options Cached options array
 * @return string HTML table content
 */
function avcp_generate_winning_operators($post_id, $options) {
    global $post;
    
    $html = '<h3>' . esc_html__('Elenco degli operatori aggiudicatari', 'avcp') . '</h3>';
    $html .= '<table>';

    $dittepartecipanti = get_the_terms($post->ID, 'ditte');
    $cats = get_post_meta($post->ID, 'avcp_aggiudicatari', true);
    
    if (is_array($dittepartecipanti) && !is_wp_error($dittepartecipanti)) {
        $has_winners = false;
        
        foreach ($dittepartecipanti as $term) {
            if (!is_object($term) || empty($term->name)) {
                continue;
            }
            
            $cterm = get_term_by('name', $term->name, 'ditte');
            if (!$cterm || is_wp_error($cterm)) {
                continue;
            }
            
            $cat_id = absint($cterm->term_id);
            $term_meta = get_option("taxonomy_$cat_id");
            $term_return = '';
            
            if (is_array($term_meta) && isset($term_meta['avcp_codice_fiscale'])) {
                $term_return = esc_attr($term_meta['avcp_codice_fiscale']);
            }
            
            $checked = (in_array($cat_id, (array)$cats) ? ' checked="checked"' : "");
            
            $is_estera = '<acronym title="' . esc_attr__('Identificativo Fiscale Italiano', 'avcp') . '">IT</acronym>';
            
            // Check if get_tax_meta function exists before using it
            if (function_exists('get_tax_meta')) {
                $stato_var = get_tax_meta($cat_id, 'avcp_is_ditta_estera');
                $is_estera = empty($stato_var) ? 
                    '<acronym title="' . esc_attr__('Identificativo Fiscale Italiano', 'avcp') . '">IT</acronym>' : 
                    '<acronym title="' . esc_attr__('Identificativo Fiscale Estero', 'avcp') . '">EE</acronym>';
            }
                
            if ($checked) {
                $has_winners = true;
                $html .= '<tr><td>';
                
                if ($options['dis_archivioditte'] != '1') {
                    $term_link = get_term_link($cterm->term_id, 'ditte');
                    if (!is_wp_error($term_link)) {
                        $html .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($term->name) . '">';
                    }
                }
                
                $html .= esc_html($term->name);
                
                if ($options['dis_archivioditte'] != '1' && !is_wp_error($term_link)) {
                    $html .= '</a>';
                }
                
                $html .= '</td><td>' . esc_html($term_return) . ' - <b>' . $is_estera . '</b></td></tr>';
            }
        }
        
        if (!$has_winners) {
            $html .= '<tr><td>' . esc_html__('Nessun aggiudicatario...', 'avcp') . '</td></tr>';
        }
    } else {
        $html .= '<tr><td>' . esc_html__('Nessun aggiudicatario...', 'avcp') . '</td></tr>';
    }

    $html .= '</table>';
    
    return $html;
}

/**
 * Generates the WPGov footer if enabled
 * 
 * @param array $options Cached options array
 * @return string HTML content for footer
 */
function avcp_generate_footer($options) {
    $html = '';
    
    if ($options['wpgov_show_love']) {
        $html .= '<center><a href="http://www.wpgov.it" target="_blank" title="' . esc_attr__('Software © WPGov', 'avcp') . '"><img style="margin:5px;" src="' . esc_url(plugin_dir_url(__FILE__) . 'images/wpgov.png') . '" alt="' . esc_attr__('WPGov Logo', 'avcp') . '" /></a></center>';
    }
    
    return $html;
}

add_filter('the_content', function($content) {
    global $post;
    
    // Validate post object and type
    if (!is_object($post) || empty($post->post_type) || $post->post_type !== 'avcp') {
        return $content;
    }

    // Validate post ID
    $post_id = absint($post->ID);
    if (!$post_id || !get_post($post_id)) {
        return $content;
    }

    // Cache options to avoid multiple queries
    $options = array(
        'denominazione_ente' => get_option('avcp_denominazione_ente', ''),
        'codicefiscale_ente' => get_option('avcp_codicefiscale_ente', ''),
        'dis_archivioanni' => get_option('avcp_dis_archivioanni'),
        'dis_archivioditte' => get_option('avcp_dis_archivioditte'),
        'wpgov_show_love' => get_option('wpgov_show_love')
    );

    // Build the content
    $prev = '<br/>';
    $prev .= avcp_generate_contract_info_table($post_id, $options);
    $prev .= avcp_generate_participating_operators($post_id, $options);
    $prev .= avcp_generate_winning_operators($post_id, $options);
    $prev .= avcp_generate_footer($options);

    return $prev . $content;
  }
  );
?>
