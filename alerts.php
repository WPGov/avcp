<?php

add_action('admin_notices', 'avcp_custom_notice');
function avcp_custom_notice()
{
    global $current_screen;

    if ( 'ditte' == $current_screen->taxonomy ) {
        echo '<div class="updated"><p>Attenzione: ogni modifica effettuata a una ditta si applica anche alle gare già pubblicate!<br/>Nel caso di cambio partita iva o denominazione, creare una nuova ditta.</p></div>';
    } else if ( 'areesettori' == $current_screen->taxonomy ) {
        echo '<div class="updated"><p>Attenzione: ogni modifica effettuata a un settore-centro di costo si applica anche alle gare già pubblicate!</p></div>';
    } else if ( 'post' == $current_screen->base && 'avcp' == $current_screen->post_type  ) {
        echo '<noscript><div class="error"><p><center><h3>!!! ATTENZIONE !!!<br/><small>JAVASCRIPT NON ABILITATO</small></h3><br/><br/>IL CORRETTO FUNZIONAMENTO DI QUESTA SCHERMATA E\' VINCOLATO DALL\'ESECUZIONE DI ALCUNI SCRIPT DA PARTE DEL TUO BROWSER<br/>
            Purtroppo sembra che il tuo browser non supporti queste operazioni, o l\'esecuzione di javascript è disattivata.<h3>Prova con un altro programma o un altro pc.</h3><br/>
            Questo avviso non può essere nascosto.<br/><br/></center></p></div>

            <div class="error"><p>AVCP XML Bandi di Gara >>> <strong> || PROBLEMA DI COMPATIBILITA\' RILEVATO - Risolvere i problemi prima di inserire dati || </strong></p></div>
            </noscript>';
    }
}

add_action('admin_notices', 'avcp_admin_messages');
function avcp_admin_messages() {
    global $current_user ;
        $user_id = $current_user->ID;

    if (get_option('avcp_denominazione_ente')  == null || get_option('avcp_codicefiscale_ente') == null) {
        echo '<div class="error"><p><strong>ANAC XML</strong>: alcuni dati dell\'ente non sono stati specificati in <strong>WPGov.it >> XML Bandi di Gara</strong></b></a></div>';
    }
}

?>
