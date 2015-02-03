<?php
function creafilexml ($anno) {
    anac_add_log('Inizio generazione file XML ' . $anno, 0);
    //creafileindice();
    $avcp_denominazione_ente = get_option('avcp_denominazione_ente');
    $XML_data_aggiornamento =  date("Y-m-d");
    $XML_data_completa_aggiornamento = date('d/m/y - H:i'); //Utile essenzialmente per i test
    $XML_anno_riferimento =  $anno;

    query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1', 'annirif' => $anno) ); global $post;
    $ng = 0;
    if ( have_posts() ) : while ( have_posts() ) : the_post();
            $ng++;
    endwhile; else:
    endif;

    $XML_FILE .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $XML_FILE .= '
    <legge190:pubblicazione xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:legge190="legge190_1_0" xsi:schemaLocation="legge190_1_0 datasetAppaltiL190.xsd">
    <metadata>
    <titolo>Pubblicazione 1 legge 190</titolo>
    <abstract>Pubblicazione 1 legge 190 rif. 2013 M - ' . $ng . ' gare - ' . $XML_data_completa_aggiornamento . ' - Generato con WPGov ANAC XML ' . get_option('avcp_version_number') . ' di Marco Milesi</abstract>
    <dataPubbicazioneDataset>' . $anno . '-12-31</dataPubbicazioneDataset>
    <entePubblicatore>' . $avcp_denominazione_ente . '</entePubblicatore>
    <dataUltimoAggiornamentoDataset>' . $XML_data_aggiornamento . '</dataUltimoAggiornamentoDataset>
    <annoRiferimento>' . $XML_anno_riferimento . '</annoRiferimento>
    <urlFile>' . site_url() . '/avcp/' . $anno . '.xml' . '</urlFile>
    <licenza>IODL</licenza>
    </metadata>
    <data>';

    query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1', 'annirif' => $anno) ); global $post;
    if ( have_posts() ) : while ( have_posts() ) : the_post();
    //Ottieni le variabili per la voce corrente
    $avcp_cig = get_post_meta($post->ID, 'avcp_cig', true);
    $avcp_codicefiscale_ente = get_option('avcp_codicefiscale_ente');
    $avcp_contraente = get_post_meta($post->ID, 'avcp_contraente', true);
    $avcp_importo_aggiudicazione = get_post_meta($post->ID, 'avcp_aggiudicazione', true);

    $avcp_data_inizio = date("Y-m-d", strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true)));
    $avcp_data_ultimazione = date("Y-m-d", strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true)));
    //$avcp_data_inizio = get_post_meta(get_the_ID(), 'avcp_data_inizio', true);
    //if ($avcp_data_inizio == '') { $avcp_data_inizio = '0000-00-00'; }
    //$avcp_data_ultimazione = get_post_meta(get_the_ID(), 'avcp_data_fine', true);
    //if ($avcp_data_ultimazione == '') { $avcp_data_ultimazione = '0000-00-00'; }
    $XML_FILE .= '<lotto>
    <cig>' . $avcp_cig . '</cig>
    <strutturaProponente>
    <codiceFiscaleProp>' . $avcp_codicefiscale_ente . '</codiceFiscaleProp>';

    $XML_FILE .= '<denominazione>' . $avcp_denominazione_ente;

    $queried_term = get_query_var($taxonomy);
    $terms = get_the_terms( $post->ID, 'areesettori' );
    if ($terms) {
      foreach($terms as $term) {
        $XML_FILE .= ' - ' . $term->name;
      }
    }
    $XML_FILE .= '</denominazione></strutturaProponente>
    <oggetto>' . get_the_title() . '</oggetto>
    <sceltaContraente>' . $avcp_contraente . '</sceltaContraente>
    <partecipanti>';
    $queried_term = get_query_var($taxonomy);
    $terms = get_the_terms( $post->ID, 'ditte' );
    if ($terms) {
      foreach($terms as $term) {
        $get_term = get_term_by('name', $term->name, 'ditte');
        $t_id = $get_term->term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
        $stato_var = get_tax_meta($t_id,'avcp_is_ditta_estera');
        if (empty($stato_var)) {$is_estera = 'codiceFiscale';}else{$is_estera = 'identificativoFiscaleEstero';}
        $XML_FILE .= '<partecipante>
            <' . $is_estera . '>' . $term_return . '</' . $is_estera . '>
            <ragioneSociale>' . $term->name . '</ragioneSociale>
            </partecipante>';
      }
    }
    $XML_FILE .= '</partecipanti>
    <aggiudicatari>';

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
        if (empty($stato_var)) {$is_estera = 'codiceFiscale';}else{$is_estera = 'identificativoFiscaleEstero';}
            if ($checked) {
                $XML_FILE .= '<aggiudicatario>';
                $XML_FILE .= '<' . $is_estera . '>' . $term_return . '</' . $is_estera . '>';
                $XML_FILE .= '<ragioneSociale>' . $term->name . '</ragioneSociale>';
                $XML_FILE .= '</aggiudicatario>';
            }
        }
    }

    $XML_FILE .= '</aggiudicatari>
    <importoAggiudicazione>' . $avcp_importo_aggiudicazione . '</importoAggiudicazione>
    <tempiCompletamento>
    <dataInizio>' . $avcp_data_inizio . '</dataInizio>
    <dataUltimazione>' . $avcp_data_ultimazione . '</dataUltimazione>
    </tempiCompletamento>
    <importoSommeLiquidate>';

    $somme_liquidate[2013] = get_post_meta($post->ID, 'avcp_s_l_2013', true);
    $somme_liquidate[2014] = get_post_meta($post->ID, 'avcp_s_l_2014', true);
    $somme_liquidate[2015] = get_post_meta($post->ID, 'avcp_s_l_2015', true);
    $somme_liquidate[2016] = get_post_meta($post->ID, 'avcp_s_l_2016', true);
    $somme_liquidate[2017] = get_post_meta($post->ID, 'avcp_s_l_2017', true);
    $somme_liquidate[2018] = get_post_meta($post->ID, 'avcp_s_l_2018', true);

    for ($i = 2013; $i < 2019; $i++) {
        if ($somme_liquidate[$i] == '') {
            $somme_liquidate[$i] = '0.00';
        }
    }

    $importo_liquidato_scalare = 0.00;
    for ($i = 2013; $i < $anno + 1; $i++) {
        $importo_liquidato_scalare = number_format((float)($importo_liquidato_scalare + $somme_liquidate[$i]), 2, '.', '');
    }
    $XML_FILE .= $importo_liquidato_scalare;

    $XML_FILE .= '</importoSommeLiquidate></lotto>';
    endwhile; else:
    _e('Sorry, no posts matched your criteria.');
    endif;

    $XML_FILE .= '</data>
    </legge190:pubblicazione>';

    // Open or create a file (this does it in the same dir as the script)
    $XML_PATH = ABSPATH . 'avcp/' . $anno . '.xml';
    $my_file = fopen($XML_PATH, "w");

    // Write the string's contents into that file
    fwrite($my_file, $XML_FILE);

    // Close 'er up
    fclose($my_file);

    anac_add_log('Fine generazione', 0);

    avcp_valid_check();
}
?>
