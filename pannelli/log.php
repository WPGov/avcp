<?php

function anac_log_load() {
    echo '<div class="wrap"><h2><strong>ANAC/AVCP XML BANDI DI GARA</strong> LOG<br><small>In questa pagina puoi verificare le ultime operazioni rilevanti effettuate dal plugin</small></h2>';

    if(isset($_POST['cancellalog'])) {
        delete_option('anac_log');
        echo 'Cancellazione effettuata<hr>';
    }

    if (get_option('anac_log') == '') {
        echo 'Nessun risultato';
    } else {
        echo get_option('anac_log');
    }

    echo '<hr>
        <form method="post" name="options" target="_self">
            <input type="submit" class="button-primary" name="cancellalog" value="Cancella LOG" />
        </form>';
    echo '</div>';
}

?>
