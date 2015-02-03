<?php
add_action('template_redirect', 'avcp_job_cpt_template');
function avcp_job_cpt_template()
{
        global $wp, $wp_query;
        if (isset($wp->query_vars['post_type']) && $wp->query_vars['post_type'] == 'avcp') {
            if (have_posts()) {
                add_filter('the_content', 'avcp_job_cpt_template_filter');
            } else {
                $wp_query->is_404 = true;
            }
        }
}
function avcp_job_cpt_template_filter($content)
{
    global $wp_query;
    $jobID = $wp_query->post->ID;
    //echo get_post_meta(get_the_ID(), 'ammap_wysiwyg', true) . '<br/>';

    echo $content;

    $mesi = array(1=>'gennaio', 'febbraio', 'marzo', 'aprile',
                'maggio', 'giugno', 'luglio', 'agosto',
                'settembre', 'ottobre', 'novembre','dicembre');
    list($giorno,$mese,$anno) = explode(' ',date('j n Y',strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true))));
    $avcp_data_inizio = $giorno . ' ' . $mesi[$mese] . ' ' . $anno;
    list($giorno1,$mese1,$anno1) = explode(' ',date('j n Y',strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true))));
    $avcp_data_fine = $giorno1 . ' ' . $mesi[$mese1] . ' ' . $anno1;

    echo '<br/>';
    echo '<table>';
    echo '<tr><td><acronym title="Codice Identificativo Gara">CIG:</acronym></td><td>' . get_post_meta(get_the_ID(), 'avcp_cig', true) . '</td></tr>';
    echo '<tr><td>Struttura proponente:</td><td>' . get_option('avcp_denominazione_ente');

    $terms = get_the_terms( get_the_ID(), 'areesettori' );
    if ($terms) {
      foreach($terms as $term) {
        $get_term = get_term_by('name', $term->name, 'areesettori');
        $tsr_id = $get_term->term_id;
        $id_sec_red_var = get_tax_meta($tsr_id,'aree_settori_cc_url');
        echo ' - <a href="' . get_permalink($id_sec_red_var) . '">' . $term->name . '</a>';
        $id_sec_cc_var = get_tax_meta($tsr_id,'aree_settori_cc_responsabile');
        if (!($id_sec_cc_var == '')) {
            echo ' - [<acronym title="Responsabile del Centro di Costo">resp. <b>' . $id_sec_cc_var . '</b></acronym>]';
        }
      }
    }

    echo '<br/>' . get_option('avcp_codicefiscale_ente') . '</td></tr>';
    echo '<tr><td>Oggetto del bando:</td><td>' . get_the_title(get_the_ID()) . '</td></tr>';
    echo '<tr><td>Procedura di scelta del contraente:</td><td>' . strtolower(substr(get_post_meta(get_the_ID(), 'avcp_contraente', true), 3)) . '</td></tr>';
    echo '<tr><td>Importo di aggiudicazione:</td><td>€ <strong>' .  get_post_meta(get_the_ID(), 'avcp_aggiudicazione', true) . '</strong></td></tr>';
    echo '<tr><td>Data di effettivo inizio:</td><td>' .  $avcp_data_inizio . '</td></tr>';
    echo '<tr><td>Data di ultimazione:</td><td>' .  $avcp_data_fine . '</td></tr>';



    echo '<tr><td>Importo delle somme liquidate:</td><td>';

    for ($i = 2013; $i < 2019; $i++) {
        if ( get_post_meta(get_the_ID(), 'avcp_s_l_'.$i, true) > 0) {
            echo '<strong>'.$i.'</strong>: '.get_post_meta(get_the_ID(), 'avcp_s_l_'.$i, true).'<br>';
        }
    }
    echo '</td>';

    echo '</tr>';


    echo '<tr><td>Anno di riferimento:</td><td>';
    $get_avcp_dis_archivioanni = get_option('avcp_dis_archivioanni');
    if ($get_avcp_dis_archivioanni == '1') {
        echo strip_tags (
            get_the_term_list( $post->ID, 'annirif', '', ' - ', '' )
        );
    } else {
        echo get_the_term_list( $post->ID, 'annirif', '', ' - ', '' );
    }
    echo '</td></tr>';
    echo '</table>';
    echo '<h3>Elenco degli operatori partecipanti</h3>';

    echo '<table>';
    $terms = get_the_terms( $post->ID, 'ditte' );
    if ($terms) {
      foreach($terms as $term) {
        $get_term = get_term_by('name', $term->name, 'ditte');
        $t_id = $get_term->term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
        $stato_var = get_tax_meta($t_id,'avcp_is_ditta_estera');
        if (empty($stato_var)) {$is_estera = '<acronym title="Identificativo Fiscale Italiano">IT</acronym>';}else{$is_estera = '<acronym title="Identificativo Fiscale Estero">EE</acronym>';}
        echo '<tr>
            <td>';
$get_avcp_dis_archivioditte = get_option('avcp_dis_archivioditte');
if ($get_avcp_dis_archivioditte == '1') {
    echo $term->name;
} else {
    echo '<a href="' . get_term_link( $t_id, 'ditte' ) . '" title="' . $term->name . '">' . $term->name . '</a>';
}

    echo'</td>
            <td>' . $term_return . ' - <b>' . $is_estera . '</b></td>
            </tr>';
      }
    }
    echo '</table>';

    echo '<h3>Elenco degli operatori aggiudicari</h3>';
    echo '<table>';
    global $post;
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
                echo '<tr><td>';
                $get_avcp_dis_archivioditte = get_option('avcp_dis_archivioditte');
                    if ($get_avcp_dis_archivioditte != '1') {
                        echo '<a href="' . get_term_link( $cterm->term_id, 'ditte' ) . '" title="' . $term->name . '">';
                    }
                    echo $term->name;
                    if ($get_avcp_dis_archivioditte != '1') { echo '</a>'; }
                echo '</td><td>' . $term_return . ' - <b>' . $is_estera . '</b></td>
                </tr>';
            }
        }
        if (empty($cats)) {
            echo '<tr><td>Nessun aggiudicatario...</td></tr>';
        }
    }

    echo '</table>';

    //echo '<h3>Statistiche</h3>';

    if (get_option('avcp_showlove') == '1') {
        echo '<center><a href="http://www.wpgov.it" target="_blank" title="Software &copy; WPGov"><img style="margin:5px;" src="' . plugin_dir_url(__FILE__) . 'images/wpgov.png" /></a></center>';
    }
}
?>
