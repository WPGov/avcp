<?php
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

// Handle form submission securely
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // Check nonce and user capability
    if (
        ! isset( $_POST['avcp_settings_nonce'] ) ||
        ! wp_verify_nonce( $_POST['avcp_settings_nonce'], 'avcp_save_settings' ) ||
        ! current_user_can( 'manage_options' )
    ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Errore di sicurezza: riprova.', 'avcp' ) . '</p></div>';
        return;
    }

    // Sanitize and save options
    update_option( 'avcp_denominazione_ente', sanitize_text_field( filter_input( INPUT_POST, 'avcp_denominazione_ente_n', FILTER_SANITIZE_STRING ) ) );
    update_option( 'avcp_codicefiscale_ente', sanitize_text_field( filter_input( INPUT_POST, 'avcp_codicefiscale_ente_n', FILTER_SANITIZE_STRING ) ) );
    update_option( 'avcp_dataset_capability', sanitize_text_field( filter_input( INPUT_POST, 'avcp_dataset_capability_n', FILTER_SANITIZE_STRING ) ) );

    update_option( 'avcp_autopublish', isset( $_POST['avcp_autopublish_n'] ) ? '1' : '0' );
    update_option( 'avcp_dis_archivioditte', isset( $_POST['avcp_dis_archivioditte_n'] ) ? '0' : '1' );
    update_option( 'avcp_dis_archivioanni', isset( $_POST['avcp_dis_archivioanni_n'] ) ? '0' : '1' );
    update_option( 'avcp_dis_styledbackend', isset( $_POST['avcp_dis_styledbackend_n'] ) ? '0' : '1' );
    update_option( 'avcp_centricosto', isset( $_POST['avcp_centricosto_n'] ) ? '0' : '1' );
    update_option( 'avcp_abilita_ruoli', isset( $_POST['avcp_abilita_ruoli_n'] ) ? '1' : '0' );
    update_option( 'wpgov_show_love', isset( $_POST['wpgov_show_love_n'] ) ? '1' : '0' );

    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Impostazioni aggiornate.', 'avcp' ) . '</p></div>';
}

// Get current values
$denominazione_ente   = get_option( 'avcp_denominazione_ente', '' );
$codicefiscale_ente   = get_option( 'avcp_codicefiscale_ente', '' );
$dataset_capability   = get_option( 'avcp_dataset_capability', 'manage_options' );
$autopublish          = get_option( 'avcp_autopublish', '0' );
$centricosto          = get_option( 'avcp_centricosto', '1' );
$dis_archivioditte    = get_option( 'avcp_dis_archivioditte', '1' );
$dis_archivioanni     = get_option( 'avcp_dis_archivioanni', '1' );
$abilita_ruoli        = get_option( 'avcp_abilita_ruoli', '0' );
$wpgov_show_love      = get_option( 'wpgov_show_love', '0' );

?>

<div class="wrap">
    <h1><?php esc_html_e( 'ANAC XML Bandi di Gara', 'avcp' ); ?></h1>
    <div class="card" style="margin-bottom: 20px;">
        <p>
            <?php
            printf(
                /* translators: 1: Marco Milesi link, 2: WPGov.it link */
                esc_html__( 'Software sviluppato da %1$s nell\'ambito del progetto %2$s', 'avcp' ),
                '<a href="' . esc_url( 'https://www.marcomilesi.com' ) . '" target="_blank" rel="noopener">Marco Milesi</a>',
                '<a href="' . esc_url( 'https://www.wpgov.it' ) . '" target="_blank" rel="noopener">WPGov.it</a>'
            );
            ?>
        </p>
        <p>
            <strong><?php esc_html_e( 'Risorse utili:', 'avcp' ); ?></strong><br>
            📖 <a href="<?php echo esc_url( 'https://github.com/WPGov/avcp/wiki' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Documentazione e Wiki', 'avcp' ); ?></a><br>
            💻 <a href="<?php echo esc_url( 'https://github.com/WPGov/avcp' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Repository GitHub', 'avcp' ); ?></a>
        </p>
    </div>

    <form method="post" action="">
        <?php wp_nonce_field( 'avcp_save_settings', 'avcp_settings_nonce' ); ?>

        <h2 class="title"><?php esc_html_e( 'Impostazioni Ente', 'avcp' ); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="avcp_denominazione_ente_n"><?php esc_html_e( 'Denominazione Ente', 'avcp' ); ?></label></th>
                <td>
                    <input type="text" id="avcp_denominazione_ente_n" name="avcp_denominazione_ente_n" value="<?php echo esc_attr( $denominazione_ente ); ?>" class="regular-text" maxlength="250" />
                    <p class="description"><?php esc_html_e( "Inserire la denominazione dell'Ente (max 250 caratteri)", 'avcp' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="avcp_codicefiscale_ente_n"><?php esc_html_e( 'Codice Fiscale Ente', 'avcp' ); ?></label></th>
                <td>
                    <input type="text" id="avcp_codicefiscale_ente_n" name="avcp_codicefiscale_ente_n" value="<?php echo esc_attr( $codicefiscale_ente ); ?>" class="regular-text" maxlength="16" />
                    <p class="description"><?php esc_html_e( "Inserire il codice fiscale/partita IVA dell'ente (max 16 caratteri)", 'avcp' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="avcp_autopublish_n"><?php esc_html_e( '.XML Automatico', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="avcp_autopublish_n" name="avcp_autopublish_n" <?php checked( $autopublish, '1' ); ?> />
                    <span class="description"><?php esc_html_e( "Spunta questa casella se vuoi generare e aggiungere automaticamente le gare al file .xml (in base all'anno di riferimento impostato).", 'avcp' ); ?><br/>
                    <small><?php esc_html_e( "Attenzione! Con questa funzione ad ogni nuova pubblicazione viene ricreato solo il file .xml relativo all'anno di riferimento della gara. Per creare i file .xml relativi ad ogni anno di riferimento è comunque necessario cliccare sul pulsante di questa pagina, in alto.", 'avcp' ); ?></small></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="avcp_centricosto_n"><?php esc_html_e( 'Abilita Centri di Costo', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="avcp_centricosto_n" name="avcp_centricosto_n" <?php checked( $centricosto, '0' ); ?> />
                    <span class="description"><?php esc_html_e( "La funzionalità Centri di Costo è abilitata di default e il campo relativo sarà scritto nel file XML. Se l'ente non li prevede, puoi disabilitarne la scrittura nel file xml. Saranno comunque utilizzabili nel sito come tassonomia.", 'avcp' ); ?></span>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e( 'Visualizzazioni Archivio', 'avcp' ); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="avcp_dis_archivioditte_n"><?php esc_html_e( 'Archivio Ditte', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="avcp_dis_archivioditte_n" name="avcp_dis_archivioditte_n" <?php checked( $dis_archivioditte, '0' ); ?> />
                    <span class="description"><?php esc_html_e( "Abilita i link con la visualizzazione archivio delle ditte con i bandi a cui la ditta ha partecipato", 'avcp' ); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="avcp_dis_archivioanni_n"><?php esc_html_e( 'Archivio Annuale', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="avcp_dis_archivioanni_n" name="avcp_dis_archivioanni_n" <?php checked( $dis_archivioanni, '0' ); ?> />
                    <span class="description"><?php esc_html_e( "Abilita i link con la visualizzazione archivio su base annuale dei bandi", 'avcp' ); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpgov_show_love_n"><?php esc_html_e( 'Mostra Logo WPGov', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="wpgov_show_love_n" name="wpgov_show_love_n" <?php checked( $wpgov_show_love, '1' ); ?> />
                    <span class="description"><?php esc_html_e( "Mostra il logo WPGov nelle pagine dei bandi per supportare il progetto", 'avcp' ); ?></span>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e( 'Ruoli & Permessi', 'avcp' ); ?></h2>
        <p><?php esc_html_e( 'Queste impostazioni sono riservate a utenti avanzati.', 'avcp' ); ?></p>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="avcp_abilita_ruoli_n"><?php esc_html_e( 'Mappa Meta-Capacità', 'avcp' ); ?></label></th>
                <td>
                    <input type="checkbox" id="avcp_abilita_ruoli_n" name="avcp_abilita_ruoli_n" <?php checked( $abilita_ruoli, '1' ); ?> />
                    <span class="description">
                        <?php esc_html_e( 'Le voci del plugin ereditano i permessi degli articoli.', 'avcp' ); ?><br>
                        <?php
                        printf(
                            /* translators: %s: tutorial link */
                            esc_html__( 'Se vuoi avere un maggior controllo abilita questa opzione e segui %s', 'avcp' ),
                            '<a href="' . esc_url( 'http://supporto.marcomilesi.ml/?p=571' ) . '" target="_blank" rel="noopener">' . esc_html__( 'questo tutorial', 'avcp' ) . '</a>'
                        );
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="avcp_dataset_capability_n"><?php esc_html_e( 'Meta-Capacità per Dataset', 'avcp' ); ?></label></th>
                <td>
                    <input type="text" id="avcp_dataset_capability_n" name="avcp_dataset_capability_n" value="<?php echo esc_attr( $dataset_capability ); ?>" class="regular-text" />
                    <span class="description"><?php esc_html_e( 'Inserire la capacità richiesta per la visualizzazione del menù "Dataset XML" (default "manage_options")', 'avcp' ); ?></span>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button button-primary" name="Submit" value="<?php esc_attr_e( 'Aggiorna Impostazioni', 'avcp' ); ?>" />
        </p>
    </form>
</div>
