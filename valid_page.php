<?php

function avcp_v_dataset_load()
{
    if(isset($_POST['XMLgenBUTTON'])) {
            $terms = get_terms( 'annirif', array('hide_empty' => 0) );
            $count = count($terms);
            if ( $count > 0 ){
                foreach ( $terms as $term ) {
                  creafilexml ($term->name);
                  $verificafilecreati = $term->name . ' - ' . $verificafilecreati;
                  echo '<div class="updated"><p>';
                  printf(__('Il seguente file .xml è generato: <b>' . $term->name . '</b>'));
                  echo "</p></div>";
                }
            } else {
                echo '<div class="error"><p>ERRORE CRITICO--VEDERE FILE DI LOG</p></div>';
                anac_add_log('Impossibile generare i file xml a causa della tassonomia vuota. Contattare il supporto', 1);
            }
        }

    echo '<div class="wrap">';
    screen_icon();
    echo '<h2><strong>Validazione dataset</strong> XML<br><small>In questa pagina puoi verificare lo stato dei dataset generati</small></h2>';

    // SYSTEM CHECK
    echo '<form method="post" name="options" target="_self">';
    settings_fields('avcp_options');
    echo '<div id="welcome-panel" style="margin:10px;width:50%;float:left;" class="welcome-panel">
    <h3><span>Generazione file .xml</span></h3>
    I dataset generati sono salvati nella cartella <b><a href="' . get_site_url() . '/avcp' . '" target="_blank">' . get_site_url() . '/avcp' . '</a></b>';

    echo'<p style="text-align:center;" class="submit"><input type="submit" class="button-primary" name="XMLgenBUTTON" value="Genera dataset" /><br/><hr/><font style="color:red;">Link dei dataset da comunicare ad AVCP:</font>
    <a href="' . get_site_url() . '/avcp/2013.xml' . '" target="_blank">2012+2013</a> &bull;
    <a href="' . get_site_url() . '/avcp/2014.xml' . '" target="_blank">2014</a> &bull;
    <a href="' . get_site_url() . '/avcp/2015.xml' . '" target="_blank">2015</a> &bull;
    <a href="' . get_site_url() . '/avcp/2016.xml' . '" target="_blank">2016</a> &bull;
    <a href="' . get_site_url() . '/avcp/2017.xml' . '" target="_blank">2017</a> &bull;
    <a href="' . get_site_url() . '/avcp/2018.xml' . '" target="_blank">2018</a>
    </p>';

    echo '</div>';
    echo '
    <div id="alert" style="margin:10px;width:40%;float:left;" class="welcome-panel">
        <h3><span>Compatibilità Server</span></h3>';

    $dir = ABSPATH . 'avcp';
    $file = $dir . '/index.php';
    echo '<br/>';
    $system_ok = true;
    if(is_dir($dir)) {
        echo 'Presenza cartella /avcp<font style="color:green;font-weight:bold;"> ==> OK</font>';
    } else {
        echo 'Presenza cartella /avcp<font style="color:red;font-weight:bold;"> ==> NON TROVATA</font>';
        $system_ok = false;
    }
    echo '<br/>';
    if (is_writeable($dir)) {
        echo 'Permessi scrittura cartella /avcp<font style="color:green;font-weight:bold;"> ==> OK</font>';
    } else {
        echo 'Permessi scrittura cartella /avcp<font style="color:red;font-weight:bold;"> ==> NON CORRISPONDENTI</font>';
        $system_ok = false;
    }
    echo '<br/>';

    if (file_exists($file)) {
        echo 'Presenza index.php /avcp<font style="color:green;font-weight:bold;"> ==> OK</font>';
    } else {
        echo 'Presenza index.php /avcp<font style="color:red;font-weight:bold;"> ==> NON TROVATO</font>';
        $system_ok = false;
    }
    echo '<br/>';

    $urlcheck = get_site_url() . '/avcp/index.php';

    $agent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; pt-pt) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27";

    if(is_callable('curl_init')){

         // initializes curl session
         $ch=curl_init();

         // sets the URL to fetch
         curl_setopt ($ch, CURLOPT_URL,$urlcheck );

         // sets the content of the User-Agent header
         curl_setopt($ch, CURLOPT_USERAGENT, $agent);

         // return the transfer as a string
         curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

         // disable output verbose information
         curl_setopt ($ch,CURLOPT_VERBOSE,false);

         // max number of seconds to allow cURL function to execute
         curl_setopt($ch, CURLOPT_TIMEOUT, 5);

         curl_exec($ch);

         // get HTTP response code
         $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

         curl_close($ch);

        if($httpcode==200) {
            echo 'Test Accesso pubblico /avcp<font style="color:green;font-weight:bold;"> ==> OK [200]</font>';
        } else if($httpcode==500) {
            echo 'Test Accesso pubblico /avcp<font style="color:red;font-weight:bold;"> ==> ERRORE 500 ISE</font>';
            $headers = get_headers($urlcheck);
            echo ' - ' . $headers[0];
            $system_ok = false;
        } else {
            echo 'Test Accesso pubblico /avcp<font style="color:red;font-weight:bold;"> ==> ERRORE ' . $httpcode . '</font>';
            $headers = get_headers($urlcheck);
            echo ' - ' . $headers[0];
            $system_ok = false;
        }
    } else {
        echo 'Test Accesso pubblico /avcp<font style="color:red;font-weight:bold;"> ==> CURL_INIT MANCANTE</font>';
        $system_ok = false;
    }


    if ($system_ok) {
        echo '<br/><center><code>Nessun problema trovato.</code></center>';
    } else {
        echo '
        <style>
        #alert {
        background: white url(' . plugin_dir_url(__FILE__) . 'includes/alert.jpg) no-repeat center;
        }
        </style>';
        echo 'Sono stati trovati alcuni problemi <b>critici</b>. Affinchè AVCP funzioni correttamente è necessario risolvere al più presto questi problemi. Consultare la documentazione del plugin per conoscere le cause più probabili di questo problema!';
    }
    echo '</div>
    <div class="clear"></div>';

    echo'<div class="updated"><p>Su questa pagina puoi controllare eventuali problemi con i dataset richiesti da ANAC (ex.AVCP)<br/>
    Questo test non garantisce, e anzi <b>ignora</b>, la completezza e veridicita\' dei dati inseriti o omessi</p></div>';

    echo '<div class="wpgov-box">';
    check_annoimpostato();
    $terms = get_terms( 'annirif', array('hide_empty' => 0) );
    foreach ( $terms as $term ) {
        $xml = new DOMDocument();
        $xml->load(ABSPATH  . '/avcp/' . $term->name. '.xml');
        echo '<hr/><h3 style="margin-bottom: 0px;">Dataset Anno ' . $term->name . '</h3><small style="float:right;"><a target="_blank" href="' . get_site_url()  . '/avcp/' . $term->name. '.xml"><code>' . get_site_url()  . '/avcp/' . $term->name. '.xml</code></a></small>';
        if (!$xml->schemaValidate(ABSPATH  . '/wp-content/plugins/avcp/includes/datasetAppaltiL190.xsd')) {
            libxml_display_errors();
            echo '<br/><font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;f">Errore validazione ANAC su tracciato XSD: risolvere i problemi segnalati e rigenerare i dataset!</font><br/>';
            check_software($term->name);
        } else {
            echo '<br/><font style="background-color:lime;padding:2px;border-radius:3px;">Validazione su tracciato XSD passata!</font>';
            check_software($term->name);
        }
    }
    echo '</div><br/>
    Puoi controllare online i dataset anche con il validatore gratuito offerto da <b><a href="https://avcp.centrosistema.it/validator">CentroSistema e SoftCare</a></b>';

}

function libxml_display_error($error)
{
$return = "<br/>\n";
switch ($error->level) {
case LIBXML_ERR_WARNING:
$return .= "<b>Warning $error->code</b>: ";
break;
case LIBXML_ERR_ERROR:
$return .= "<b>Error $error->code</b>: ";
break;
case LIBXML_ERR_FATAL:
$return .= "<b>Fatal Error $error->code</b>: ";
break;
}
$return .= trim($error->message);
if ($error->file) {
$return .= " in <b>$error->file</b>";
}
$return .= " on line <b>$error->line</b>\n";

return $return;
}

function libxml_display_errors() {
$errors = libxml_get_errors();
foreach ($errors as $error) {
print libxml_display_error($error);
}
libxml_clear_errors();
}
// Enable user error handling
libxml_use_internal_errors(true);

function check_software($anno) {
query_posts( array( 'post_type' => 'avcp', 'annirif' => $anno, 'posts_per_page' => '-1') ); global $post;
    $erroredate = false;
    if ( have_posts() ) : while ( have_posts() ) : the_post();
            $datainizio = get_post_meta($post->ID, 'avcp_data_inizio', true);
            $datafine = get_post_meta($post->ID, 'avcp_data_fine', true);
            if ($datainizio == '' || $datafine == '' ) {
                $erroredate = true;
                $logerrori .= '[id <b>' . $post->ID . '</b> // ' . get_the_title() . '] -';
            }
    endwhile; else:
    endif;
    if ($erroredate == true) {
        echo '<br/><font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;">ERRORE VALIDAZIONE SOFTWARE: data_inizio / data_fine<br/>' . $logerrori . '</font><br/>';
    } else {
        echo '<br/><font style="background-color:lime;padding:2px;border-radius:3px;">Validazione software completata per i campi data_inizio e data_fine</font></center>';
    }
}

function check_annoimpostato() {
    query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1') ); global $post;
    $erroreanno = false;
    $ng = 0;
    if ( have_posts() ) : while ( have_posts() ) : the_post();
            $ng++;
            if(!( has_term( '', 'annirif' ) )) {
                $erroreanno = true;
            }
    endwhile; else:
    endif;
    if ($erroreanno == true) {
        echo '<center><font style="background-color:red;color:white;padding:2px;border-radius:3px;font-weight:bold;">Una o più gare sono registrate senza anno di riferimento. Correggere!</font></center><br/>';
    } else {
        echo '<center><font style="background-color:lime;padding:2px;border-radius:3px;">Tutte le ' . $ng . ' gare hanno un anno di riferimento e saranno incluse nel loro dataset!</font></center>';
    }
}

?>
