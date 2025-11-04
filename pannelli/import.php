<?php

function anac_import_load()
{
    // Additional security: verify user capabilities
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'), __('Access Denied'), array('response' => 403));
    }
    
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
        
        // CSRF protection - verify nonce
        if (!isset($_POST['anac_import_nonce']) || !wp_verify_nonce($_POST['anac_import_nonce'], 'anac_import_action')) {
            wp_die(__('Security check failed. Please try again.'), __('Security Error'), array('response' => 403));
        }

        if (isset($_POST['importafinale']) && $_POST['importafinale'] != null) {

            $stored_xml = stripslashes(get_option('anac_import_xml'));
            $parsing_options = LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_PARSEHUGE;
            $gare_xml = new SimpleXMLElement($stored_xml, $parsing_options);
            
            $dfx = (string) $gare_xml->metadata->annoRiferimento;
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
                        echo '<span style="color:red;weight:bold;">Gara ' . esc_html((string) $lotto->oggetto) . ' già presente</span><br>';
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
                    // Handle both codiceFiscale and identificativoFiscaleEstero
                    $codice_fiscale = isset($aggiudicatario->codiceFiscale) ? 
                        (string) $aggiudicatario->codiceFiscale : 
                        (string) $aggiudicatario->identificativoFiscaleEstero;
                    array_push( $stack_aggiudicatari, $codice_fiscale);
                }

                foreach ($lotto->partecipanti->partecipante as $partecipante) {
                    // Handle both codiceFiscale and identificativoFiscaleEstero
                    $codice_fiscale = isset($partecipante->codiceFiscale) ? 
                        (string) $partecipante->codiceFiscale : 
                        (string) $partecipante->identificativoFiscaleEstero;
                    echo esc_html($partecipante->ragioneSociale) . ' &bull; ' . esc_html($codice_fiscale);

                    $terms         = get_terms('ditte', array(
                        'hide_empty' => 0
                    ));
                    $dittapresente = false;
                    foreach ($terms as $term) {

                        $get_ditta   = get_term_by('name', $term->name, 'ditte');
                        $t_id        = $get_ditta->term_id;
                        $term_meta   = get_option("taxonomy_$t_id");
                        $term_return = esc_attr($term_meta['avcp_codice_fiscale']);
                        if ($term_return == $codice_fiscale) {
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
                            $term_meta['avcp_codice_fiscale'] = $codice_fiscale;
                            update_option("taxonomy_$t_id", $term_meta);
                        }
                    }

                    //Aggiudicatario?
                    $stack_aggiudicatari_finali = array();
                    if (in_array( $codice_fiscale, $stack_aggiudicatari ) ) {
                        array_push( $stack_aggiudicatari_finali, $id_ditta_presente );
                        echo  ' &bull; <strong>(aggiudicatario)</strong>)';
                    }
                    echo '<br>';
                }

                update_post_meta($id_gara, 'avcp_aggiudicatari', $stack_aggiudicatari_finali);

                echo esc_html((string) $lotto->cig) . ' &bull; <strong>' . esc_html((string) $lotto->oggetto) . '</strong>
                <br>' . esc_html((string) $lotto->sceltaContraente) . ' &bull; ' . esc_html((string) $lotto->tempiCompletamento->dataInizio) . ' &bull; ' . esc_html((string) $lotto->tempiCompletamento->dataUltimazione) . '<br><br>';

                anac_add_log('Aggiunto ' . $lotto->oggetto . ' (id ' . $id_gara . ') con anno ' . $gare_xml->metadata->annoRiferimento, 0);
            }
            echo '<hr>OK<hr>';
            delete_option('anac_import_xml');

        } else if (isset($_POST['datasetimporta']) && $_POST['datasetimporta'] != null) {

            // Sanitize and validate XML input - preserve XML structure
            $xml_input = wp_unslash($_POST['datasetimporta']);
            
            // Basic security: check for obvious malicious content without breaking XML structure
            if (stripos($xml_input, '<script') !== false || 
                stripos($xml_input, 'javascript:') !== false || 
                stripos($xml_input, 'vbscript:') !== false) {
                echo '<div class="error"><p><strong>Errore:</strong> Il contenuto XML contiene codice potenzialmente pericoloso.</p></div>';
                return;
            }
            
            // Validate input length to prevent DoS attacks
            if (strlen($xml_input) > 5000000) { // 5MB limit
                echo '<div class="error"><p><strong>Errore:</strong> Il file XML è troppo grande (massimo 5MB).</p></div>';
                return;
            }
            
            // Additional security: disable external entity loading
            libxml_disable_entity_loader(true);
            
            // Use previous errors to catch XML parsing issues
            $use_errors = libxml_use_internal_errors(true);
            
            try {
                // Try different parsing approaches
                $parsing_options = LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_PARSEHUGE;
                
                // First attempt: standard parsing
                $gare_xml = new SimpleXMLElement($xml_input, $parsing_options);
                
                // Check if we have metadata as a direct child
                $metadata = $gare_xml->metadata;
                
                // Validate required elements exist
                if (!isset($metadata) || !isset($metadata->entePubblicatore) || 
                    !isset($metadata->annoRiferimento)) {
                    throw new Exception('XML structure is invalid - missing required metadata elements');
                }
                
            } catch (Exception $e) {
                $libxml_errors = libxml_get_errors();
                $error_messages = array($e->getMessage());
                
                if (!empty($libxml_errors)) {
                    foreach ($libxml_errors as $error) {
                        $error_messages[] = "Linea {$error->line}: {$error->message}";
                    }
                }
                
                echo '<div class="error"><p><strong>Errore XML:</strong><br>' . esc_html(implode('<br>', $error_messages)) . '</p></div>';
                
                // Debug: show a sample of the XML input
                echo '<div class="error"><p><strong>Inizio XML ricevuto:</strong><br><pre>' . esc_html(substr($xml_input, 0, 300)) . '...</pre></p></div>';
                
                libxml_use_internal_errors($use_errors);
                return;
            }
            
            libxml_use_internal_errors($use_errors);

            echo '<h3>Controlla i dati.</h3><h4>' . esc_html($metadata->entePubblicatore) . '<br>Anno ' . esc_html($metadata->annoRiferimento) . '<br>Aggiornato al ' . esc_html($metadata->dataUltimoAggiornamentoDataset) . '</h4>
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

            $a = ''; // Initialize alternating row variable
            foreach ($gare_xml->xpath('//lotto') as $lotto) {
                if ($a == '') {
                    $a = ' class="alternate"';
                } else {
                    $a = '';
                }
                echo '<tr' . esc_attr($a) . '>
            <td class="row-title"><label for="tablecell">' . esc_html($lotto->cig) . '</label></td>
            <td>' . esc_html($lotto->oggetto) . '
            <table>';
                foreach ($lotto->partecipanti->partecipante as $partecipante) {
                    // Handle both codiceFiscale and identificativoFiscaleEstero
                    $codice_fiscale = isset($partecipante->codiceFiscale) ? 
                        (string) $partecipante->codiceFiscale : 
                        (string) $partecipante->identificativoFiscaleEstero;
                    echo '<tr><td>' . esc_html($partecipante->ragioneSociale) . ' &bull; ' . esc_html($codice_fiscale) . '</td></tr>';
                }
                echo '</table></td>
            <td>€ ' . esc_html($lotto->importoAggiudicazione) . '</td>
            <td>€ ' . esc_html($lotto->importoSommeLiquidate) . '</td>
            <td>' . esc_html($lotto->tempiCompletamento->dataInizio) . ' &bull; ' . esc_html($lotto->tempiCompletamento->dataUltimazione) . '</td>

            </tr>';
            }

            echo '</tbody></table>';

            echo '<form method="post" name="options" target="_self">';
            wp_nonce_field('anac_import_action', 'anac_import_nonce');
            add_option('anac_import_xml', $xml_input);
            echo '
        <textarea name="importafinale" cols="80" rows="10" class="large-text">' . esc_textarea($xml_input) . '</textarea>
        <hr>
        <p class="submit"><input type="submit" class="button-primary" name="Submit" value="Conferma" /></p>
        </form>';

        } else {
            echo 'Errore.';
            anac_add_log('Si è verificato un errore nel pannello di importazione. Parametri non validi', 1);
        }

    } else {

        echo '<form method="post" name="options" target="_self">';
        wp_nonce_field('anac_import_action', 'anac_import_nonce');
        echo '<hr>Incolla qui il contenuto del file xml che vuoi importare:<br><br><textarea name="datasetimporta" cols="80" rows="10" class="large-text"></textarea>';
        echo '<hr>
            <p class="submit"><input type="submit" class="button-primary" name="Submit" value="Importa" /></p>
            </form>';
        echo '</div>';

    }
}
?>
