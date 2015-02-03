<?php

function extend_wysiwyg_height() {
    echo '<style type="text/css">
    #content{ height:100px; }
    </style>';
}

add_action('admin_head', 'extend_wysiwyg_height');


function avcp_remove_metaboxes() {
 remove_meta_box( 'postcustom' , 'avcp' , 'normal' ); //removes custom fields metabox for avcp
}
add_action( 'admin_menu' , 'avcp_remove_metaboxes' );
    $prefix = 'avcp_';

    $config = array(
        'id'             => 'avcp_metabox1',          // meta box id, unique per meta box
        'title'          => 'Dettagli Gara',          // meta box title
        'pages'          => array('avcp'),      // post types, accept custom post types as well, default is array('post'); optional
        'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
        'priority'       => 'high',            // order of meta box: high (default), low; optional
        'fields'         => array(),            // list of meta fields (can be added by field arrays)
        'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
        'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
    );

    $my_meta =  new AT_Meta_Box($config);

    //text field
    $my_meta->addText($prefix.'cig',array('name'=> 'Codice Identificativo Gara (C.I.G.)', 'desc' => 'Identificativo della gara (10 caratteri alfanumerici)', 'std' => '0000000000'));

    //select field
    $my_meta->addSelect($prefix.'contraente',array(
      '01-PROCEDURA APERTA'=>'1. Procedura Aperta',
      '02-PROCEDURA RISTRETTA'=>'2. Procedura Ristretta',
      '03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE DEL BANDO'=>'3. Procedura negoziata previa pubblicazione del bando',
      '04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE DEL BANDO'=>'4. Procedura negoziata senza previa pubblicazione del bando',
      '05-DIALOGO COMPETITIVO'=>'5. Dialogo Competitivo',
      '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI  GARA ART. 221 D.LGS. 163/2006'=>'6. Procedura negoziata senza previa indizione di gara (art. 221 D.LGS. 163/2006)',
      '07-SISTEMA DINAMICO DI ACQUISIZIONE'=>'7. Sistema dinamico di acquisizione',
      '08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO'=>'8. Affidamento in economia - cottimo fiduciario',
      '14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006'=>'14. Procedura selettiva (ex art. 238 C.7 D.LGS. 163/2006)',
      '17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE N.381/91'=>'17. Affidamento diretto (ex art. 5 legge 381/91)',
      '21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA'=>'21. Procedura ristretta derivante da avvisi con cui si indice la gara',
      '22-PROCEDURA NEGOZIATA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA'=>'22. Procedura negoziata derivante da avvisi con cui si indice la gara',
      '23-AFFIDAMENTO IN ECONOMIA - AFFIDAMENTO DIRETTO'=>'23. Affidamento in economia - Affidamento diretto',
      '24-AFFIDAMENTO DIRETTO A SOCIETA&apos; IN HOUSE'=>'24. Affidamento diretto a Società in-house',
      '25-AFFIDAMENTO DIRETTO A SOCIETA&apos; RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP'=>'25. Affidamento diretto a Società raggruppate/consorziate o controllate nelle concessioni di LL.PP',
      '26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'=>'26. Affidamento diretto in adesione ad accordo quadro/convenzione',
      '27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'=>'27. Confronto competitivo in adesione ad accordo quadro/convenzione',
      '28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI'=>'28. Procedura ai sensi dei regolamenti degli organi costituzionali'
      ),array('name'=> 'Scelta Contraente', 'std'=> array('selectkey2')));

      //date field
    $my_meta->addDate($prefix.'data_inizio',array('name'=> 'Data Inizio', 'format' => 'd-m-yy'));
    //'desc' => 'Lasciare vuoto il campo se non è nota. <button id="clearinizio">pulisci</button>

      //date field
    $my_meta->addDate($prefix.'data_fine',array('name'=> 'Data Fine', 'format' => 'd-m-yy'));

      $my_meta->addText($prefix.'aggiudicazione',array('name'=> 'Importo aggiudicazione <b>€</b>', 'desc' => 'Importo di aggiudicazione', 'std' => '0.00'));
//$my_meta->addText($prefix.'somme_liquidate_prevprev',array('name'=> 'Importo somme liquidate PREVPREV', 'desc' => 'campo rimuovendo', 'std' => '0.00'));
//$my_meta->addText($prefix.'somme_liquidate_prev',array('name'=> 'Importo somme liquidate PREV', 'desc' => 'campo rimuovendo', 'std' => '0.00'));
//$my_meta->addText($prefix.'somme_liquidate',array('name'=> 'Importo somme liquidate', 'campo rimuovendo' => 'Formato: 12345.12', 'std' => '0.00'));

      //wysiwyg field
      //$my_meta->addWysiwyg($prefix.'wysiwyg_note',array('name'=> 'Allegati e note libere '));

    $my_meta->addText($prefix.'s_l_2013',array('name'=> 'Somme liquidate <strong>2013</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));
    $my_meta->addText($prefix.'s_l_2014',array('name'=> 'Somme liquidate <strong>2014</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));
    $my_meta->addText($prefix.'s_l_2015',array('name'=> 'Somme liquidate <strong>2015</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));
    $my_meta->addText($prefix.'s_l_2016',array('name'=> 'Somme liquidate <strong>2016</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));
    $my_meta->addText($prefix.'s_l_2017',array('name'=> 'Somme liquidate <strong>2017</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));
    $my_meta->addText($prefix.'s_l_2018',array('name'=> 'Somme liquidate <strong>2018</strong>', 'desc' => 'Formato: 12345.12', 'std' => '0.00'));

    $my_meta->Finish();

function avcp_pages_inner_custom_box3( $post ) { //Inizializzazione Metabox 2, senza api bainternet
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'pages_noncename' );

    // The actual fields for data entry
    echo 'Le ditte inserite nel riquadro "Ditte partecipanti" compariranno qui solo dopo avere aggiornato/pubblicato questa gara.';
    $dittepartecipanti = get_the_terms( $post->ID, 'ditte' );
    $cats = get_post_meta($post->ID,'avcp_aggiudicatari',true);
    echo '<ul>';
    if(is_array($dittepartecipanti)) {
        foreach ($dittepartecipanti as $term) {
            $cterm = get_term_by('name',$term->name,'ditte');
            $cat_id = $cterm->term_id; //Prende l'id del termine
            $checked = (in_array($cat_id,(array)$cats)? ' checked="checked"': "");
            echo'<li id="cat-'.$cat_id.'"><input type="checkbox" name="avcp_aggiudicatari[]" id="'.$cat_id.'" value="'.$cat_id.'"'.$checked.'> <label for="'.$cat_id.'">'.__($term->name, 'pages_textdomain' ).'</label></li>';
        }
    } else {
        echo '<code>Nessuna ditta partecipante collegata a questa gara.</code>';
    }
    echo '</ul><input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Pubblica/Aggiorna Gara" accesskey="p">';
}

add_action( 'add_meta_boxes', 'avcp_meta_box_add' );
function avcp_meta_box_add() {
    add_meta_box( 'my-meta-box-id', 'Ditte aggiudicatarie', 'avcp_pages_inner_custom_box3', 'avcp', 'normal', 'high' );
}

add_action( 'save_post_avcp', 'avcp_custom_save_post' );
function avcp_custom_save_post( $post_id ) {
    update_post_meta($post_id,'avcp_aggiudicatari',$_POST['avcp_aggiudicatari']);
}


add_action('add_meta_boxes','avcp_add_meta_boxes',10,2);
function avcp_add_meta_boxes($post_type, $post) {
  ob_start();
}
add_action('dbx_post_sidebar','avcp_dbx_post_sidebar');
function avcp_dbx_post_sidebar() {
  $html = ob_get_clean();
  $html = str_replace('type="checkbox" name="tax_input[areesettori][]"','type="radio" name="tax_input[areesettori][]"',$html);
  echo $html;
}

 ?>
