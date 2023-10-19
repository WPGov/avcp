<?php
function creafilexml ($anno) {
    anac_add_log('Inizio generazione XML ' . $anno, 0);
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

    $XML_FILE = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $XML_FILE .= '
    <legge190:pubblicazione xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:legge190="legge190_1_0" xsi:schemaLocation="legge190_1_0 datasetAppaltiL190.xsd">
    <metadata>
    <titolo>Pubblicazione 1 legge 190</titolo>
    <abstract>Pubblicazione 1 legge 190 rif. 2013 M - ' . $ng . ' gare - ' . $XML_data_completa_aggiornamento . ' - Generato con WPGov ANAC XML ' . get_option('avcp_version_number') . ' di Marco Milesi</abstract>';
    
    if ( $anno > 2018 ) {
        $XML_FILE .= '
        <dataPubblicazioneDataset>' . $anno . '-12-31</dataPubblicazioneDataset>';
    } else {
        $XML_FILE .= '
        <dataPubbicazioneDataset>' . $anno . '-12-31</dataPubbicazioneDataset>';
    }

    $XML_FILE .= '<entePubblicatore>' . $avcp_denominazione_ente . '</entePubblicatore>
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

    $d_inizio = get_post_meta(get_the_ID(), 'avcp_data_inizio', true);
    $d_fine = get_post_meta(get_the_ID(), 'avcp_data_fine', true);

    $avcp_data_inizio = $d_inizio ? date("Y-m-d", strtotime( $d_inizio ) ) : '0000-00-00';
    $avcp_data_ultimazione = $d_fine ? date("Y-m-d", strtotime( $d_fine ) ) : '0000-00-00';

    $XML_FILE .= '<lotto>
    <cig>' . $avcp_cig . '</cig>
    <strutturaProponente>
    <codiceFiscaleProp>' . $avcp_codicefiscale_ente . '</codiceFiscaleProp>';

    $XML_FILE .= '<denominazione>' . $avcp_denominazione_ente;

    $terms = get_the_terms( $post->ID, 'areesettori' );
    if ($terms) {
      foreach($terms as $term) {
        $XML_FILE .= ' - ' . $term->name;
      }
    }
    $XML_FILE .= '</denominazione></strutturaProponente>
    <oggetto>' . get_the_title() . '</oggetto>';
    
    if ( $anno >= 2019 ) { // Sostituzioni
        $tipi_contraente = array(
            array(
                '03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE DEL BANDO',
                '03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE'
            ),
            array(
                '04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE DEL BANDO',
                '04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE'
            ),
            array(
                '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA ART. 221 D.LGS. 163/2006',
                '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)'
            ),
            array(
                '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI  GARA ART. 221 D.LGS. 163/2006',
                '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)'
            ),
            array(
                '17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE N.381/91',
                '17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE 381/91'
            ),
            array(
                '22-PROCEDURA NEGOZIATA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA',
                '22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)'
            ),
            array(
                '23-AFFIDAMENTO IN ECONOMIA - AFFIDAMENTO DIRETTO',
                '23-AFFIDAMENTO DIRETTO'
            ),
            array(
                '25-AFFIDAMENTO DIRETTO A SOCIETA\' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP',
                '25-AFFIDAMENTO DIRETTO A SOCIETA\' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI E NEI PARTENARIATI            '
            ),

            array( '29', '29-PROCEDURA RISTRETTA SEMPLIFICATA'),
            array( '30', '30-PROCEDURA DERIVANTE DA LEGGE REGIONALE'),
            array( '31', '31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL\'IMPORTO CONTRATTUALE'),
            array( '32', '32-AFFIDAMENTO RISERVATO'),
            array( '33', '33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA'),
            array( '34', '34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA'),
            array( '35', '35-PARTERNARIATO PER L’INNOVAZIONE'),
            array( '36', '36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI'),
            array( '37', '37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE'),
            array( '38', '38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI')
        );
        foreach ( $tipi_contraente as $tc ) {
            if ( $avcp_contraente == $tc[0] ) {
                $avcp_contraente = $tc[1];
                break;
            }
        }
    }

    $XML_FILE .= '<sceltaContraente>' . $avcp_contraente . '</sceltaContraente>
    <partecipanti>';
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
    $somme_liquidate[2019] = get_post_meta($post->ID, 'avcp_s_l_2019', true);
    $somme_liquidate[2020] = get_post_meta($post->ID, 'avcp_s_l_2020', true);
    $somme_liquidate[2021] = get_post_meta($post->ID, 'avcp_s_l_2021', true);
    $somme_liquidate[2022] = get_post_meta($post->ID, 'avcp_s_l_2022', true);
    $somme_liquidate[2023] = get_post_meta($post->ID, 'avcp_s_l_2023', true);
    $somme_liquidate[2024] = get_post_meta($post->ID, 'avcp_s_l_2024', true);
    $somme_liquidate[2025] = get_post_meta($post->ID, 'avcp_s_l_2025', true);

    for ($i = 2013; $i < 2026; $i++) {
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
    $XML_PATH = avcp_get_basexmlpath( $anno );
    $my_file = fopen($XML_PATH, "w");

    // Write the string's contents into that file
    fwrite($my_file, $XML_FILE);

    // Close 'er up
    fclose($my_file);

    anac_add_log('Fine generazione', 0);
}
?>
