<?php
    if (!(is_plugin_active( 'avcp/avcp.php' ))) { echo 'Plugin non installato!'; return;}

    if(isset($_POST['Submit'])) { //Salvataggio Impostazioni
        $get_avcp_denominazione_ente = $_POST["avcp_denominazione_ente_n"];
        update_option( 'avcp_denominazione_ente', $get_avcp_denominazione_ente );
        $get_avcp_codicefiscale_ente = $_POST["avcp_codicefiscale_ente_n"];
        update_option( 'avcp_codicefiscale_ente', $get_avcp_codicefiscale_ente );
        if (isset($_POST['avcp_autopublish_n'])){
                update_option('avcp_autopublish', '1');
            } else {
                update_option('avcp_autopublish', '0');
        }
        if (isset($_POST['avcp_dis_archivioditte_n'])){
                update_option('avcp_dis_archivioditte', '0'); //Invertito
            } else {
                update_option('avcp_dis_archivioditte', '1');
        }
        if (isset($_POST['avcp_dis_archivioanni_n'])){
                update_option('avcp_dis_archivioanni', '0'); //Invertito
            } else {
                update_option('avcp_dis_archivioanni', '1');
        }
        if (isset($_POST['avcp_dis_styledbackend_n'])){
                update_option('avcp_dis_styledbackend', '0'); //Invertito
            } else {
                update_option('avcp_dis_styledbackend', '1');
        }
        if (isset($_POST['avcp_centricosto_n'])){
                update_option('avcp_centricosto', '0'); //Invertito
            } else {
                update_option('avcp_centricosto', '1');
        }
        if (isset($_POST['avcp_enable_editor_n'])){
                update_option('avcp_enable_editor', '1');
            } else {
                update_option('avcp_enable_editor', '0');
        }
        if (isset($_POST['avcp_showlove_n'])){
                update_option('avcp_showlove', '1');
            } else {
                update_option('avcp_showlove', '0');
        }
        if (isset($_POST['avcp_abilita_ruoli_n'])){
                update_option('avcp_abilita_ruoli', '1');
            } else {
                update_option('avcp_abilita_ruoli', '0');
        }
    }

    //Qui inizia la sezione delle impostazioni

    echo '<form method="post" name="options" target="_self">';
    settings_fields( 'avcp_options' );

    echo '<h2>Impostazioni Ente</h2>
    <table class="form-table"><tbody>';

    echo '<tr>';
    echo '<th><label>Denominazione Ente</label></th>';
    echo '<td><input type="text" name="avcp_denominazione_ente_n" value="'; echo get_option('avcp_denominazione_ente'); echo '" class="regular-text">';
    echo '<span class="description"> Inserire la denominazione dell\'Ente # massimo 250 caratteri</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>Codice Fiscale Ente</label></th>';
    echo '<td><input type="text" name="avcp_codicefiscale_ente_n" value="'; echo get_option('avcp_codicefiscale_ente'); echo '" class="regular-text">';
    echo '<span class="description"> Inserire il codice fiscale/partita iva dell\'ente # 9 caratteri.</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>.XML Automatico</label></th>';
    echo '<td><input type="checkbox" name="avcp_autopublish_n" ';
    $get_avcp_autopublish = get_option('avcp_autopublish');
        if ($get_avcp_autopublish == '1') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Spunta questa casella se vuoi generare aggiungere automaticamente le gare al corrispettivo file .xml (in base all\'anno di riferimento impostato).<br/><small>Attenzione! Con questa funzione ad ogni nuova pubblicazione viene ricreato solo file .xml relativo all\'anno di riferimento della gara. Per creare i file .xml relativi ad ogni anno di riferimento è comunque necessario cliccare sul pulsante di questa pagina, in alto.</small></span></td>';
    echo '</tr>';

    echo'</tbody></table>';

    echo '<h2>Impostazioni Varie</h2>
    <table class="form-table"><tbody>';

    echo '<tr>';
    echo '<th><label>Abilita Editor WYSIWYG</label></th>';
    echo '<td><input type="checkbox" name="avcp_enable_editor_n" ';
    $get_avcp_enable_editor = get_option('avcp_enable_editor');
        if ($get_avcp_enable_editor == '1') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Spunta questa casella se vuoi abilitare l\'editor <b>WYSIWYG</b> per le gare, con la possibilità di inserire testo a piacere e documenti allegati al bando.</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>Abilita Centri di Costo</label></th>';
    echo '<td><input type="checkbox" name="avcp_centricosto_n" ';
    $get_avcp_centricosto = get_option('avcp_centricosto');
        if ($get_avcp_centricosto == '0') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">La funzionalità Centri di Costo è abilitata di default e il campo relativo sarà scritto nel file XML. Se l\'ente non li prevede, puoi disabilitarne la scrittura nel file xml. Saranno comunque utilizzabili nel sito come tassonomia.</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>Ruoli & Permessi</label></th>';
    echo '<td><input type="checkbox" name="avcp_abilita_ruoli_n" ';
    $get_avcp_abilita_ruoli = get_option('avcp_abilita_ruoli');
        if ($get_avcp_abilita_ruoli == '1') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Le voci di AVCP ereditano i permessi di pubblicazione/modifica/eliminazione degli articoli. Se vuoi avere un maggior controllo abilita questa opzione e segui <a href="http://supporto.marcomilesi.ml/?p=571" target="_blank" title="Istruzioni per la configurazione di Ruoli & Permessi">questo tutorial</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>Mostra un po\' di Amore</label></th>';
    echo '<td><input type="checkbox" name="avcp_showlove_n" ';
    $get_avcp_showlove = get_option('avcp_showlove');
        if ($get_avcp_showlove == '1') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Spunta questa casella se vuoi mostrare il link al plugin sotto alla tabella generata</span></td>';
    echo '</tr>';

    echo '</tbody></table>';

    echo '<h2>Visualizzazioni Archivio</h2>
    <table class="form-table"><tbody>';

    echo '<tr>';
    echo '<th><label>Archivio Ditte</label></th>';
    echo '<td><input type="checkbox" name="avcp_dis_archivioditte_n" ';
    $get_avcp_dis_archivioditte = get_option('avcp_dis_archivioditte');
        if ($get_avcp_dis_archivioditte == '0') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Abilita i link con la visualizzazione archivio delle ditte con i bandi a cui la ditta ha partecipato</span></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th><label>Archivio Annuale</label></th>';
    echo '<td><input type="checkbox" name="avcp_dis_archivioanni_n" ';
    $get_avcp_dis_archivioanni = get_option('avcp_dis_archivioanni');
        if ($get_avcp_dis_archivioanni == '0') {
            echo 'checked="checked" ';
        }
    echo '><span class="description">Abilita i link con la visualizzazione archivio su base annuale dei bandi</span></td>';
    echo '</tr>';

    echo '</tbody></table>';

    echo '<p class="submit"><input type="submit" class="button-primary" name="Submit" value="AGGIORNA IMPOSTAZIONI" /></p>';

    echo '</form>';

    //Qui finisce la sezione delle impostazione

?>
