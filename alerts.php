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

    $get_avcp_invalid = get_option('avcp_invalid');
    if ($get_avcp_invalid == '1') {
        echo '<div class="error"><p><strong>ANAC XML</strong>: alcuni dataset .xml non hanno superato il controllo di validazione</p><p><a href="' . admin_url() . 'edit.php?post_type=avcp&page=avcp_v_dataset">Clicca qui vedere i dettagli degli errori</a></div>';
    }

    if (get_option('avcp_denominazione_ente')  == null || get_option('avcp_codicefiscale_ente') == null) {
        echo '<div class="error"><p><strong>ANAC XML</strong>: alcuni dati dell\'ente non sono stati specificati in <strong>WPGov.it >> XML Bandi di Gara</strong></b></a></div>';
    }
}


//add_action('admin_init', 'avcp_admin_messages_ignore');
//function avcp_admin_messages_ignore() {
    global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */

        if ( isset($_GET['avcp_upgrade_3']) && '0' == $_GET['avcp_upgrade_3'] ) { // 19 gennaio 2014
            query_posts( array( 'post_type' => 'avcp',  'posts_per_page' => '-1', 'annirif' => '2012') ); global $post;
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    wp_remove_object_terms( $post->ID, '2012', 'annirif' );
                    wp_add_object_terms( $post->ID, '2013', 'annirif' );
                endwhile; else:
            endif;
            $get_term = get_term_by('name', '2012', 'annirif');
            $t_id = $get_term->term_id;
            wp_delete_term( $t_id, 'annirif' );
            add_user_meta($user_id, 'avcp_upgrade_3', 'true', true);
            unlink(ABSPATH . 'avcp/2012.xml');
        }

        if ( isset($_GET['avcp_upgrade_2date_ignore']) && '0' == $_GET['avcp_upgrade_2date_ignore'] ) {
             add_user_meta($user_id, 'avcp_upgrade_2date', 'true', true);
        }
//}

?>
