<?php

function anac_import_load()
{
    if (ini_set('max_execution_time', 300) === false) {
        set_time_limit(0);
    }

    if ( wp_count_posts( 'avcp' ) && wp_count_posts( 'avcp')->publish > 0 ) {
        echo '<br><h2>Questa funzione è al momento utilizzabile solo in assenza di gare registrate.</h2>
                <br>Se stai cercando una soluzione per visualizzare file xml esterni, abbiamo <a href="https://wordpress.org/plugins/anac-xml-viewer/">un\'altra soluzione</a>!';
        return;
    }

    echo '<div class="wrap"><h2><strong>Utilità di importazione</strong> iniziale <em>(beta)</em><br><small>Carica i dati da un file xml precompilato</small></h2>';

    if (isset($_POST['Submit'])) {

        if ($_POST['importafinale'] != null) {

            $gare_xml = new SimpleXMLElement(stripslashes(get_option('anac_import_xml')));
            $dfx      = (string) $gare_xml->metadata->annoRiferimento;
            foreach ($gare_xml->xpath('//lotto') as $lotto) {

                $esiste = false;
                query_posts(array(
                    'post_type' => 'avcp',
                    'posts_per_page' => '-1',
                    'meta_key' => 'avcp_cig',
                    'meta_value' => (string) $lotto->cig,
                    'annirif' => $dfx
                ));
                global $post;
                if (have_posts()):
                    while (have_posts()):
                        the_post();
                        echo '<span style="color:red;weight:bold;">Gara ' . (string) $lotto->oggetto . ' già presente</span><br>';
                        continue;
                    endwhile;
                else:
                endif;

                $post = array(
                    'post_title' => $lotto->oggetto,
                    'post_status' => 'publish',
                    'post_type' => 'avcp'
                );

                $id_gara = wp_insert_post($post);
                add_post_meta($id_gara, 'avcp_cig', (string) $lotto->cig, true);
                add_post_meta($id_gara, 'avcp_contraente', (string) $lotto->sceltaContraente, true);
                add_post_meta($id_gara, 'avcp_data_inizio', (string) $lotto->tempiCompletamento->dataInizio, true);
                add_post_meta($id_gara, 'avcp_data_fine', (string) $lotto->tempiCompletamento->dataUltimazione, true);
                add_post_meta($id_gara, 'avcp_aggiudicazione', (string) $lotto->importoAggiudicazione, true);
                add_post_meta($id_gara, 'avcp_s_l_' . $dfx, (string) $lotto->importoSommeLiquidate, true);
                wp_set_object_terms($id_gara, $dfx, 'annirif');

                //Crea un array di aggiudicatari, basando sul C.F.
                //Poi ciclo le ditte. Se il C.F. della ditta è in $stack_aggiudicatari aggiungo l'id della ditta
                //-in un array che sarà salvato in avcp_aggiudicatari
                $stack_aggiudicatari = array();
                foreach ($lotto->aggiudicatari->aggiudicatario as $aggiudicatario) {
                    array_push( $stack_aggiudicatari, (string) $aggiudicatario->codiceFiscale);
                }

                foreach ($lotto->partecipanti->partecipante as $partecipante) {
                    echo $partecipante->ragioneSociale . ' &bull; ' . $partecipante->codiceFiscale;

                    $terms         = get_terms('ditte', array(
                        'hide_empty' => 0
                    ));
                    $dittapresente = false;
                    foreach ($terms as $term) {

                        $get_ditta   = get_term_by('name', $term->name, 'ditte');
                        $t_id        = $get_ditta->term_id;
                        $term_meta   = get_option("taxonomy_$t_id");
                        $term_return = esc_attr($term_meta['avcp_codice_fiscale']);
                        if ($term_return == (string) $partecipante->codiceFiscale) {
                            $dittapresente     = true;
                            $id_ditta_presente = $term->term_id;
                            $nome_ditta_presente = $term->name;
                            break;
                        }
                    }
                    if ($dittapresente) {

                        wp_set_object_terms($id_gara, $nome_ditta_presente, 'ditte');

                    } else {
                        $id_ditta_aggiunta = wp_insert_term($partecipante->ragioneSociale, 'ditte');
                        wp_set_object_terms($id_gara, $nome_ditta_presente, 'ditte');
                        $dfx2 = get_term_by('name', $partecipante->ragioneSociale, 'ditte');
                        if (!empty($dfx2) && !is_wp_error($dfx2)) {
                            $t_id                             = $dfx2->term_id;
                            $term_meta                        = get_option("taxonomy_$t_id");
                            $term_meta['avcp_codice_fiscale'] = (string) $partecipante->codiceFiscale;
                            update_option("taxonomy_$t_id", $term_meta);
                        }
                    }

                    //Aggiudicatario?
                    $stack_aggiudicatari_finali = array();
                    if (in_array( (string) $partecipante->codiceFiscale, $stack_aggiudicatari ) ) {
                        array_push( $stack_aggiudicatari_finali, $id_ditta_presente );
                        echo  ' &bull; <strong>(aggiudicatario)</strong>)';
                    }
                    echo '<br>';
                }

                update_post_meta($id_gara, 'avcp_aggiudicatari', $stack_aggiudicatari_finali);

                echo (string) $lotto->cig . ' &bull; <strong>' . (string) $lotto->oggetto . '</strong>
                <br>' . (string) $lotto->sceltaContraente . ' &bull; ' . (string) $lotto->tempiCompletamento->dataInizio . ' &bull; ' . (string) $lotto->tempiCompletamento->dataUltimazione . '<br><br>';

                anac_add_log('Aggiunto ' . $lotto->oggetto . ' (id ' . $id_gara . ') con anno ' . $gare_xml->metadata->annoRiferimento, 0);
            }
            echo '<hr>OK<hr>';
            delete_option('anac_import_xml');

        } else if ($_POST['datasetimporta'] != null) {

            $gare_xml = new SimpleXMLElement(stripslashes($_POST['datasetimporta']));

            echo '<h3>Controlla i dati.</h3><h4>' . $gare_xml->metadata->entePubblicatore . '<br>Anno ' . $gare_xml->metadata->annoRiferimento . '<br>Aggiornato al ' . $gare_xml->metadata->dataUltimoAggiornamentoDataset . '</h4>
<table class="widefat">
    <thead>
        <tr>
            <th class="row-title">CIG</th>
            <th>Oggetto</th>
            <th>Importo aggiudicazione</th>
            <th>Importo somme liquidate</th>
            <th>Tempi di completamento</th>
        </tr>
    </thead>
    <tbody>';

            foreach ($gare_xml->xpath('//lotto') as $lotto) {
                if ($a == '') {
                    $a = ' class="alternate"';
                } else {
                    $a = '';
                }
                echo '<tr' . $a . '>
            <td class="row-title"><label for="tablecell">' . $lotto->cig . '</label></td>
            <td>' . $lotto->oggetto . '
            <table>';
                foreach ($lotto->partecipanti->partecipante as $partecipante) {
                    echo '<tr><td>' . $partecipante->ragioneSociale . ' &bull; ' . $partecipante->codiceFiscale . '</td></tr>';
                }
                echo '</table></td>
            <td>€ ' . $lotto->importoAggiudicazione . '</td>
            <td>€ ' . $lotto->importoSommeLiquidate . '</td>
            <td>' . $lotto->tempiCompletamento->dataInizio . ' &bull; ' . $lotto->tempiCompletamento->dataUltimazione . '</td>

            </tr>';
            }

            echo '</tbody></table>';

            echo '<form method="post" name="options" target="_self">';
            add_option('anac_import_xml', $_POST['datasetimporta']);
            echo '
        <textarea name="importafinale" cols="80" rows="10" class="large-text">' . $_POST['datasetimporta'] . '</textarea>
        <hr>
        <p class="submit"><input type="submit" class="button-primary" name="Submit" value="Conferma" /></p>
        </form>';

        } else {
            echo 'Errore.';
            anac_add_log('Si è verificato un errore nel pannello di importazione. Parametri non validi', 1);
        }

    } else {

        echo '<form method="post" name="options" target="_self">';
        echo '<hr>Incolla qui il contenuto del file xml che vuoi importare:<br><br><textarea name="datasetimporta" cols="80" rows="10" class="large-text"></textarea>';
        echo '<hr>
            <p class="submit"><input type="submit" class="button-primary" name="Submit" value="Importa" /></p>
            </form>';
        echo '</div>';

    }
}
?>
