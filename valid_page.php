<?php

function avcp_v_dataset_load() {
    // Handle XML generation and log reset with nonce checks
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if ( isset( $_POST['avcp_xmlgen_nonce'] ) && wp_verify_nonce( $_POST['avcp_xmlgen_nonce'], 'avcp_xmlgen_action' ) ) {
            $terms = get_terms( 'annirif', array('hide_empty' => 0) );
            foreach ( $terms as $term ) {
                if ( isset( $_POST['XMLGEN_' . $term->name] ) ) {
                    creafilexml( $term->name );
                    echo '<div class="notice notice-success is-dismissible"><p>';
                    printf( esc_html__( 'Il seguente file .xml è generato: %s', 'avcp' ), '<b>' . esc_html( $term->name ) . '</b>' );
                    echo "</p></div>";
                    break;
                }
            }
        }
        if ( isset( $_POST['avcp_reset_log_nonce'] ) && wp_verify_nonce( $_POST['avcp_reset_log_nonce'], 'avcp_reset_log_action' ) && isset( $_POST['AVCP-RESET-LOG'] ) ) {
            delete_option( 'anac_log' );
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Log eliminati', 'avcp' ) . '</p></div>';
        }
    }

    $basexmlurl = avcp_get_basexmlurl();
    ?>
    <style>
        /* Table header improvements */
        table.widefat thead th {
            background: #f3f4f5;
            color: #23282d;
            font-weight: 600;
            text-align: left;
            border-bottom: 2px solid #ccd0d4;
            padding: 10px 8px;
        }
        table.widefat td {
            padding: 8px;
            vertical-align: top;
        }
        /* Responsive table */
        @media (max-width: 900px) {
            table.widefat, table.widefat thead, table.widefat tbody, table.widefat th, table.widefat td, table.widefat tr {
                display: block;
            }
            table.widefat thead {
                display: none;
            }
            table.widefat tr {
                margin-bottom: 15px;
            }
            table.widefat td {
                border: none;
                position: relative;
                padding-left: 50%;
            }
            table.widefat td:before {
                position: absolute;
                left: 10px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
            }
            table.widefat td:nth-of-type(1):before { content: "<?php esc_html_e('Anno', 'avcp'); ?>"; }
            table.widefat td:nth-of-type(2):before { content: "<?php esc_html_e('Validazione XSD', 'avcp'); ?>"; }
            table.widefat td:nth-of-type(3):before { content: "<?php esc_html_e('Versione XSD', 'avcp'); ?>"; }
            table.widefat td:nth-of-type(4):before { content: "<?php esc_html_e('Validazione software', 'avcp'); ?>"; }
            table.widefat td:nth-of-type(5):before { content: "<?php esc_html_e('Data generazione', 'avcp'); ?>"; }
            table.widefat td:nth-of-type(6):before { content: "<?php esc_html_e('Azione', 'avcp'); ?>"; }
        }
        /* Log styling */
        .avcp-log-list {
            background: #fff;
            border: 1px solid #ccd0d4;
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 13px;
            margin: 0;
            white-space: pre-line;
        }
    </style>
    <div class="wrap">
        <h1><?php esc_html_e( 'Validazione', 'avcp' ); ?></h1>
        <div class="notice notice-info" style="padding:20px;">
            <?php esc_html_e( 'Nota: il controllo tecnico non attesta la correttezza dei dati.', 'avcp' ); ?><br>
            <?php esc_html_e( 'Puoi controllare online i dataset con un', 'avcp' ); ?>
            <a href="https://anac.softcare.it/Validator" target="_blank" rel="noopener"><?php esc_html_e( 'validatore esterno', 'avcp' ); ?></a>
            <br><br>
            <?php esc_html_e( "L'archivio dei documenti XML generati è disponibile qui:", 'avcp' ); ?>
            <a href="<?php echo esc_url( $basexmlurl ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $basexmlurl ); ?></a>
            <br><br>
            <strong><?php esc_html_e( 'Risorse utili:', 'avcp' ); ?></strong><br>
            📖 <a href="<?php echo esc_url( 'https://github.com/WPGov/avcp/wiki' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Documentazione e Wiki', 'avcp' ); ?></a> | 
            💻 <a href="<?php echo esc_url( 'https://github.com/WPGov/avcp' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Repository GitHub', 'avcp' ); ?></a>
        </div>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="meta-box-sortables">
                        <div class="inside">
                            <?php
                            // Check for posts without year (annirif) and collect them
                            $offending_posts = array();
                            $post_ids = get_posts( array(
                                'post_type'      => 'avcp',
                                'posts_per_page' => -1,
                                'fields'         => 'ids',
                                'post_status'    => 'any',
                            ) );
                            if ( ! empty( $post_ids ) ) {
                                foreach ( $post_ids as $post_id ) {
                                    if ( !has_term( '', 'annirif', $post_id ) ) {
                                        $offending_posts[] = $post_id;
                                    }
                                }
                            }
                            if ( count( $offending_posts ) > 0 ) {
                                $message = sprintf( esc_html__( 'Ci sono %d gare registrate senza anno di riferimento da correggere!', 'avcp' ), number_format_i18n( count( $offending_posts ) ) );
                                echo '<div class="notice notice-error"><p><strong>' . esc_html( $message ) . '</strong></p>';
                                echo '<ul style="margin:8px 0 12px 18px;">';
                                foreach ( $offending_posts as $id ) {
                                    $title_esc = esc_html( get_the_title( $id ) );
                                    if ( !$title_esc ) {
                                        $title_esc = esc_html__( '(Nessun titolo)', 'avcp' );
                                    }
                                    echo '<li><a href="' . get_edit_post_link( $id ) . '">' . $title_esc . '</a></li>';
                                }
                                echo '</ul></div>';
                            }

                            // Check for 'ditte' terms missing 'avcp_codice_fiscale' term meta and collect them                            $offending_ditte = array();
                            $ditte_terms = get_terms( array(
                                'taxonomy'   => 'ditte',
                                'hide_empty' => false,
                            ) );
                            if ( ! is_wp_error( $ditte_terms ) && ! empty( $ditte_terms ) ) {
                                foreach ( $ditte_terms as $dterm ) {
                                    $codice = get_term_meta( $dterm->term_id, 'avcp_codice_fiscale', true );
                                    if ( empty( $codice ) ) {
                                        $offending_ditte[] = $dterm->term_id;
                                    }
                                }
                            }
                            if ( count( $offending_ditte) > 0 ) {
                                $msg2 = sprintf( esc_html__( 'Ci sono %d ditte senza codice fiscale da correggere!', 'avcp' ), number_format_i18n( count( $offending_ditte) ) );
                                echo '<div class="notice notice-error"><p><strong>' . esc_html( $msg2 ) . '</strong></p>';
                                echo '<ul style="margin:8px 0 12px 18px;">';
                                foreach ( $offending_ditte as $id ) {
                                    $name_esc = esc_html( get_term( $id )->name );
                                    if ( !$name_esc ) {
                                        $name_esc = esc_html__( '(Nessun nome)', 'avcp' );
                                    }
                                    $link_esc = esc_url( get_edit_term_link( $id, 'ditte' ) );
                                    echo '<li><a href="' . $link_esc . '">' . $name_esc . '</a></li>';
                                }
                                echo '</ul></div>';
                            }
                            $terms = get_terms( 'annirif', array('hide_empty' => 0) );
                            ?>
                            <form method="post" style="overflow-x: auto;">
                                <?php wp_nonce_field( 'avcp_xmlgen_action', 'avcp_xmlgen_nonce' ); ?>
                                <table class="widefat striped">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e( 'Anno', 'avcp' ); ?></th>
                                            <th><?php esc_html_e( 'Validazione XSD', 'avcp' ); ?> <span class="dashicons dashicons-info" title="<?php esc_attr_e( 'Verifica che il file xml sia tecnicamente corretto alle specifiche XSD', 'avcp' ); ?>"></span></th>
                                            <th><?php esc_html_e( 'Versione XSD', 'avcp' ); ?></th>
                                            <th><?php esc_html_e( 'Validazione software', 'avcp' ); ?> <span class="dashicons dashicons-info" title="<?php esc_attr_e( 'Verifiche aggiuntive del plugin WordPress per il controllo di alcuni campi, come ad esempio le date', 'avcp' ); ?>"></span></th>
                                            <th><?php esc_html_e( 'Data generazione', 'avcp' ); ?></th>
                                            <th><?php esc_html_e( 'Azione', 'avcp' ); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ( $terms as $term ) {
                                        $file_exists = file_exists( avcp_get_basexmlpath( $term->name ) );
                                        if ( ! $file_exists && $term->name < date('Y') - 2 ) {
                                            continue;
                                        }
                                        echo '<tr>';
                                        echo '<td>' . esc_html( $term->name ) . ' <a href="' . esc_url( avcp_get_basexmlurl( $term->name ) ) . '" target="_blank" title="' . esc_attr( $term->name ) . '"><span class="dashicons dashicons-external"></span></a></td>';

                                        // XSD version
                                        if ( $term->name >= 2021 ) {
                                            $xsd_dir = '1_3';
                                            $xsd_ver = 'v 1.3';
                                        } elseif ( $term->name >= 2019 ) {
                                            $xsd_dir = '1_1';
                                            $xsd_ver = 'v 1.1';
                                        } else {
                                            $xsd_dir = '1_0';
                                            $xsd_ver = 'v 1.0';
                                        }

                                        // XSD validation
                                        echo '<td>';
                                        $errors = false;
                                        if ( $file_exists ) {
                                            $xml = new DOMDocument();
                                            $xml->load( avcp_get_basexmlpath( $term->name ) );
                                            if ( $xml->schemaValidate( ABSPATH . '/wp-content/plugins/avcp/includes/XSD/' . $xsd_dir . '/datasetAppaltiL190.xsd' ) ) {
                                                echo '<span class="dashicons dashicons-yes" title="' . esc_attr__( 'Corretto', 'avcp' ) . '"></span>';
                                            } else {
                                                echo '<span class="dashicons dashicons-dismiss" title="' . esc_attr__( 'Trovati errori', 'avcp' ) . '"></span>';
                                                $errors = libxml_get_errors();
                                            }
                                        }
                                        echo '</td>';

                                        echo '<td>' . esc_html( $xsd_ver ) . '</td>';

                                        // Software validation
                                        echo '<td>';
                                        if ( $file_exists ) {
                                            $args = array(
                                                'post_type'      => 'avcp',
                                                'tax_query'      => array(
                                                    array(
                                                        'taxonomy' => 'annirif',
                                                        'field'    => 'name',
                                                        'terms'    => $term->name,
                                                    ),
                                                ),
                                                'posts_per_page' => -1,
                                            );
                                            $query = new WP_Query( $args );
                                            $error = false;
                                            if ( $query->have_posts() ) {
                                                foreach ( $query->posts as $post ) {
                                                    $datainizio = get_post_meta( $post->ID, 'avcp_data_inizio', true );
                                                    $datafine = get_post_meta( $post->ID, 'avcp_data_fine', true );
                                                    if ( empty( $datainizio ) || empty( $datafine ) ) {
                                                        $error = true;
                                                        echo '<span class="notice-error" style="background-color:red;color:white;padding:2px;">' . esc_html__( 'Errore campo data', 'avcp' ) . '</span><br><a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '">' . esc_html( get_the_title( $post->ID ) ) . '</a><hr>';
                                                    }
                                                    if ( strlen( get_the_title( $post->ID ) ) >= 250 ) {
                                                        $error = true;
                                                        echo '<span class="notice-error" style="background-color:red;color:white;padding:2px;">' . esc_html__( 'Titolo oltre 250 caratteri', 'avcp' ) . '</span><br><a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '">' . esc_html( get_the_title( $post->ID ) ) . '</a><hr>';
                                                    }
                                                }
                                            }
                                            if ( ! $error ) {
                                                echo '<span class="dashicons dashicons-yes"></span>';
                                            }
                                        }
                                        echo '</td>';

                                        // Generation date
                                        echo '<td>' . ( $file_exists && filemtime( avcp_get_basexmlpath( $term->name ) ) ? esc_html( date( "d/m/Y H:i", filemtime( avcp_get_basexmlpath( $term->name ) ) ) ) : '-' ) . '</td>';

                                        // Action
                                        echo '<td>';
                                        if ( $term->name >= date('Y') - 2 ) {
                                            echo '<input type="submit" class="button button-primary" name="XMLGEN_' . esc_attr( $term->name ) . '" value="' . esc_attr__( 'Genera', 'avcp' ) . '" />';
                                        }
                                        echo '</td>';
                                        echo '</tr>';

                                        // XSD errors
                                        if ( $errors ) {
                                            echo '<tr><td colspan="6">';
                                            foreach ( $errors as $err ) {
                                                print esc_html( libxml_display_error( $err ) );
                                            }
                                            libxml_clear_errors();
                                            echo '</td></tr>';
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h2><span><?php esc_html_e( 'Stato server', 'avcp' ); ?></span></h2>
                            <div class="inside">
                                <p>
                                <?php
                                $dir = avcp_get_basexmlpath();
                                $file = $dir . '/index.php';
                                $system_ok = true;
                                if ( is_dir( $dir ) ) {
                                    echo esc_html__( 'Presenza cartella /avcp', 'avcp' ) . '<span style="color:green;font-weight:bold;">: OK</span>';
                                } else {
                                    echo esc_html__( 'Presenza cartella /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: NON TROVATA</span>';
                                    $system_ok = false;
                                }
                                echo '<br/>';
                                if ( is_writable( $dir ) ) {
                                    echo esc_html__( 'Permessi scrittura cartella /avcp', 'avcp' ) . '<span style="color:green;font-weight:bold;">: OK</span>';
                                } else {
                                    echo esc_html__( 'Permessi scrittura cartella /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: NON CORRISPONDENTI</span>';
                                    $system_ok = false;
                                }
                                echo '<br/>';
                                if ( file_exists( $file ) ) {
                                    echo esc_html__( 'Presenza index.php /avcp', 'avcp' ) . '<span style="color:green;font-weight:bold;">: OK</span>';
                                } else {
                                    echo esc_html__( 'Presenza index.php /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: NON TROVATO</span>';
                                    $system_ok = false;
                                }
                                echo '<br/>';

                                $urlcheck = get_site_url() . '/avcp/index.php';
                                $agent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; pt-pt) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27";
                                if ( is_callable( 'curl_init' ) ) {
                                    $ch = curl_init();
                                    curl_setopt( $ch, CURLOPT_URL, $urlcheck );
                                    curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
                                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                                    curl_setopt( $ch, CURLOPT_VERBOSE, false );
                                    curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
                                    curl_exec( $ch );
                                    $httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                                    curl_close( $ch );
                                    if ( $httpcode == 200 ) {
                                        echo esc_html__( 'Test Accesso pubblico /avcp', 'avcp' ) . '<span style="color:green;font-weight:bold;">: OK [200]</span>';
                                    } elseif ( $httpcode == 500 ) {
                                        echo esc_html__( 'Test Accesso pubblico /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: ERRORE 500 ISE</span>';
                                        $headers = @get_headers( $urlcheck );
                                        echo ' - ' . esc_html( @$headers[0] );
                                        $system_ok = false;
                                    } else {
                                        echo esc_html__( 'Test Accesso pubblico /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: ERRORE ' . esc_html( $httpcode ) . '</span>';
                                        $headers = @get_headers( $urlcheck );
                                        echo ' - ' . esc_html( @$headers[0] );
                                        $system_ok = false;
                                    }
                                } else {
                                    echo esc_html__( 'Test Accesso pubblico /avcp', 'avcp' ) . '<span style="color:red;font-weight:bold;">: CURL_INIT MANCANTE</span>';
                                    $system_ok = false;
                                }
                                if ( $system_ok ) {
                                    echo '<hr><center>' . esc_html__( 'Nessun problema trovato.', 'avcp' ) . '</center>';
                                } else {
                                    echo '<div class="notice notice-error"><p>' . esc_html__( 'Sono stati trovati alcuni problemi critici. Affinchè AVCP funzioni correttamente è necessario risolvere al più presto questi problemi.', 'avcp' ) . '</p></div>';
                                }
                                ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h2>
                                <form method="post" style="display:inline;">
                                    <?php wp_nonce_field( 'avcp_reset_log_action', 'avcp_reset_log_nonce' ); ?>
                                    <input type="submit" class="button button-primary" name="AVCP-RESET-LOG" value="<?php esc_attr_e( 'Pulisci', 'avcp' ); ?>" style="float:right;" />
                            </form>
                            <?php esc_html_e( 'Log', 'avcp' ); ?>
                        </h2>
                        <div class="inside">
                            <?php
                            $log = get_option( 'anac_log' );
                            if ( ! empty( $log ) ) {
                                // Show log as HTML, allowing only safe tags (b, strong, em, a, br)
                                $allowed_tags = array(
                                    'b'      => array(),
                                    'strong' => array(),
                                    'em'     => array(),
                                    'a'      => array(
                                        'href'   => array(),
                                        'title'  => array(),
                                        'target' => array(),
                                        'rel'    => array(),
                                    ),
                                    'br'     => array(),
                                );
                                echo '<div class="avcp-log-list">' . wp_kses( $log, $allowed_tags ) . '</div>';
                            } else {
                                echo '<div class="avcp-log-list">' . esc_html__( 'Nessun log presente.', 'avcp' ) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #postbox-container-1 .postbox-container -->
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div>
<?php
}

function libxml_display_error( $error ) {
    $return = "\n";
    switch ( $error->level ) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }
    $return .= trim( $error->message );
    if ( $error->file ) {
        $return .= " in $error->file";
    }
    $return .= " on line $error->line";
    return $return;
}

// Enable user error handling
libxml_use_internal_errors( true );
?>
