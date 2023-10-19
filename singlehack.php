<?php
add_filter('the_content', function($content) {
    global $post;
    if ($post->post_type != 'avcp') { return $content; }

    global $wp_query;
    $jobID = $wp_query->post->ID;

    $prev .= '<br/>';
    $prev .= '<table>';
    $prev .= '<tr><td><acronym title="Codice Identificativo Gara">CIG:</acronym></td><td>' . get_post_meta(get_the_ID(), 'avcp_cig', true) . '</td></tr>';
    $prev .= '<tr><td>Struttura proponente:</td><td>' . get_option('avcp_denominazione_ente');

    $terms = get_the_terms( get_the_ID(), 'areesettori' );
    if ($terms) {
      foreach($terms as $term) {
        $get_term = get_term_by('name', $term->name, 'areesettori');
        $tsr_id = $get_term->term_id;
        $id_sec_red_var = get_tax_meta($tsr_id,'aree_settori_cc_url');
        $prev .= ' - <a href="' . get_permalink($id_sec_red_var) . '">' . $term->name . '</a>';
        $id_sec_cc_var = get_tax_meta($tsr_id,'aree_settori_cc_responsabile');
        if (!($id_sec_cc_var == '')) {
            $prev .= ' - [<acronym title="Responsabile del Centro di Costo">resp. <b>' . $id_sec_cc_var . '</b></acronym>]';
        }
      }
    }

    $prev .= '<br/>' . get_option('avcp_codicefiscale_ente') . '</td></tr>';
    $prev .= '<tr><td>Oggetto del bando:</td><td>' . get_the_title(get_the_ID()) . '</td></tr>';
    $prev .= '<tr><td>Procedura di scelta del contraente:</td><td>' . strtolower(avcp_get_contraente(get_post_meta(get_the_ID(), 'avcp_contraente', true))) . '</td></tr>';
    $prev .= '<tr><td>Importo di aggiudicazione:</td><td>€ <strong>' .  get_post_meta(get_the_ID(), 'avcp_aggiudicazione', true) . '</strong></td></tr>';

    $d_inizio = get_post_meta(get_the_ID(), 'avcp_data_inizio', true);
    $d_fine = get_post_meta(get_the_ID(), 'avcp_data_fine', true);

    $avcp_data_inizio = $d_inizio ? date("d/m/Y", strtotime( $d_inizio ) ) : 'n.d.';
    $avcp_data_fine = $d_fine ? date("d/m/Y", strtotime( $d_fine ) ) : 'n.d.';

    $prev .= '<tr><td>Data di effettivo inizio:</td><td>' .  $avcp_data_inizio . '</td></tr>';
    $prev .= '<tr><td>Data di ultimazione:</td><td>' .  $avcp_data_fine . '</td></tr>';



    $prev .= '<tr><td>Importo delle somme liquidate:</td><td>';

    for ($i = 2013; $i < 2025; $i++) {
        if ( get_post_meta(get_the_ID(), 'avcp_s_l_'.$i, true) > 0) {
            $prev .= '<strong>'.$i.'</strong>: '.get_post_meta(get_the_ID(), 'avcp_s_l_'.$i, true).'<br>';
        }
    }
    $prev .= '</td>';

    $prev .= '</tr>';


    $prev .= '<tr><td>Anno di riferimento:</td><td>';
    $get_avcp_dis_archivioanni = get_option('avcp_dis_archivioanni');
    if ($get_avcp_dis_archivioanni == '1') {
        $prev .= strip_tags (
            get_the_term_list( $post->ID, 'annirif', '', ' - ', '' )
        );
    } else {
        $prev .= get_the_term_list( $post->ID, 'annirif', '', ' - ', '' );
    }
    $prev .= '</td></tr>';
    $prev .= '</table>';
    $prev .= '<h3>Elenco degli operatori partecipanti</h3>';

    $prev .= '<table>';
    $terms = get_the_terms( $post->ID, 'ditte' );
    if ($terms) {
      foreach($terms as $term) {
        $get_term = get_term_by('name', $term->name, 'ditte');
        $t_id = $get_term->term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
        $stato_var = get_tax_meta($t_id,'avcp_is_ditta_estera');
        if (empty($stato_var)) {$is_estera = '<acronym title="Identificativo Fiscale Italiano">IT</acronym>';}else{$is_estera = '<acronym title="Identificativo Fiscale Estero">EE</acronym>';}
        $prev .= '<tr>
            <td>';
$get_avcp_dis_archivioditte = get_option('avcp_dis_archivioditte');
if ($get_avcp_dis_archivioditte == '1') {
    $prev .= $term->name;
} else {
    $prev .= '<a href="' . get_term_link( $t_id, 'ditte' ) . '" title="' . $term->name . '">' . $term->name . '</a>';
}

    $prev .='</td>
            <td>' . $term_return . ' - <b>' . $is_estera . '</b></td>
            </tr>';
      }
    }
    $prev .= '</table>';

    $prev .= '<h3>Elenco degli operatori aggiudicatari</h3>';
    $prev .= '<table>';

    $dittepartecipanti = get_the_terms( $post->ID, 'ditte' );
    $cats = get_post_meta($post->ID,'avcp_aggiudicatari',true);
    if(is_array($dittepartecipanti)) {
        foreach ($dittepartecipanti as $term) {
            $cterm = get_term_by('name',$term->name,'ditte');
            $cat_id = $cterm->term_id; //Prende l'id del termine
            $term_meta = get_option( "taxonomy_$cat_id" );
            $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
            $checked = (in_array($cat_id,(array)$cats)? ' checked="checked"': "");
            $stato_var = get_tax_meta($cat_id,'avcp_is_ditta_estera');
            if (empty($stato_var)) {$is_estera = '<acronym title="Identificativo Fiscale Italiano">IT</acronym>';}else{$is_estera = '<acronym title="Identificativo Fiscale Estero">EE</acronym>';}
            if ($checked) {
                $prev .= '<tr><td>';
                $get_avcp_dis_archivioditte = get_option('avcp_dis_archivioditte');
                    if ($get_avcp_dis_archivioditte != '1') {
                        $prev .= '<a href="' . get_term_link( $cterm->term_id, 'ditte' ) . '" title="' . $term->name . '">';
                    }
                    $prev .= $term->name;
                    if ($get_avcp_dis_archivioditte != '1') { $prev .= '</a>'; }
                $prev .= '</td><td>' . $term_return . ' - <b>' . $is_estera . '</b></td>
                </tr>';
            }
        }
        if (empty($cats)) {
            $prev .= '<tr><td>Nessun aggiudicatario...</td></tr>';
        }
    }

    $prev .= '</table>';

    if (get_option('wpgov_show_love')) {
        $prev .= '<center><a href="http://www.wpgov.it" target="_blank" title="Software &copy; WPGov"><img style="margin:5px;" src="' . plugin_dir_url(__FILE__) . 'images/wpgov.png" /></a></center>';
    }

    return $prev . $content;
  }
  );
?>
