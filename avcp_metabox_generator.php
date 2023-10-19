<?php

add_action( 'admin_menu' , function() {
  remove_meta_box( 'postcustom' , 'avcp' , 'normal' );
} );

add_action( 'add_meta_boxes', function() {
  add_meta_box( 'avcp_metabox', 'Dettagli', 'avcp_metabox_details', 'avcp' );
} );

add_action('save_post', function($post_id) {
  if (array_key_exists('avcp_contraente', $_POST)) {
    update_post_meta(
        $post_id,
        'avcp_contraente',
        $_POST['avcp_contraente']
    );
  }
  if (array_key_exists('avcp_cig', $_POST)) {
    update_post_meta(
        $post_id,
        'avcp_cig',
        $_POST['avcp_cig']
    );
  }
  if (array_key_exists('avcp_aggiudicazione', $_POST)) {
    update_post_meta(
        $post_id,
        'avcp_aggiudicazione',
        $_POST['avcp_aggiudicazione']
    );
  }
  $terms = get_terms( 'annirif', array('hide_empty' => 0) );
  foreach ( $terms as $term ) {
    if (array_key_exists('avcp_s_l_'.$term->name, $_POST)) {
      if ( $_POST['avcp_s_l_'.$term->name] > 0 || ( $term->name > date("Y", strtotime( $_POST['avcp_data_inizio'] )) && $term->name < date("Y", strtotime( $_POST['avcp_data_fine'] )) )) {
        wp_set_object_terms( $post_id, $term->name, 'annirif', true );
      }
      update_post_meta(
          $post_id,
          'avcp_s_l_'.$term->name,
          $_POST['avcp_s_l_'.$term->name]
      );
    }
  }
  if (array_key_exists('avcp_data_inizio', $_POST)) {
    if ( date("Y", strtotime( $_POST['avcp_data_inizio'] )) > 2012 && date("Y", strtotime( $_POST['avcp_data_inizio'] )) < 2030  ) {
      wp_set_object_terms( $post_id, date("Y", strtotime( $_POST['avcp_data_inizio'] ) ), 'annirif', true );
    }
    update_post_meta(
        $post_id,
        'avcp_data_inizio',
        $_POST['avcp_data_inizio']
    );
  }
  if (array_key_exists('avcp_data_fine', $_POST)) {
    if ( date("Y", strtotime( $_POST['avcp_data_fine'] )) > 2012 && date("Y", strtotime( $_POST['avcp_data_fine'] )) < 2030 ) {
      wp_set_object_terms( $post_id, date("Y", strtotime( $_POST['avcp_data_fine'] ) ), 'annirif', true );
    }
    update_post_meta(
        $post_id,
        'avcp_data_fine',
        $_POST['avcp_data_fine']
    );
  }
});

function avcp_metabox_details( $post ) {
  ?>
  <div class="hcf_box">
    <style scoped>
        .hcf_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .hcf_field{
            display: contents;
        }
    </style>
    <p class="meta-options hcf_field">
        <label for="avcp_cig">Codice CIG (10 caratteri)</label>
        <input id="avcp_cig" type="text" name="avcp_cig" minlength="10" maxlength="10" value="<?php echo ( get_post_meta($post->ID, 'avcp_cig', true) ? get_post_meta($post->ID, 'avcp_cig', true) : '0000000000'); ?>">
    </p>
    <p class="meta-options hcf_field">
      <label for="avcp_contraente">Scelta contraente</label>
      <select name="avcp_contraente" id="avcp_contraente" class="postbox">
        <option value="">Seleziona...</option>
        <?php
        $contraente = get_post_meta($post->ID, 'avcp_contraente', true);
        $tipi_contraente = array(
          array('01-PROCEDURA APERTA','1. Procedura Aperta'),
          array('02-PROCEDURA RISTRETTA','2. Procedura Ristretta'),
          array('03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE DEL BANDO','3. Procedura negoziata previa pubblicazione del bando'),
          array('04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE DEL BANDO','4. Procedura negoziata senza previa pubblicazione del bando'),
          array('05-DIALOGO COMPETITIVO','5. Dialogo Competitivo'),
          array('06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI  GARA ART. 221 D.LGS. 163/2006','6. Procedura negoziata senza previa indizione di gara (art. 221 D.LGS. 163/2006)'),
          array('07-SISTEMA DINAMICO DI ACQUISIZIONE','7. Sistema dinamico di acquisizione'),
          array('08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO','8. Affidamento in economia - cottimo fiduciario'),
          array('14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006','14. Procedura selettiva (ex art. 238 C.7 D.LGS. 163/2006)'),
          array('17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE N.381/91','17. Affidamento diretto (ex art. 5 legge 381/91)'),
          array('21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA','21. Procedura ristretta derivante da avvisi con cui si indice la gara'),
          array('22-PROCEDURA NEGOZIATA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA','22. Procedura negoziata derivante da avvisi con cui si indice la gara'),
          array('23-AFFIDAMENTO IN ECONOMIA - AFFIDAMENTO DIRETTO','23. Affidamento in economia - Affidamento diretto'),
          array('24-AFFIDAMENTO DIRETTO A SOCIETA&apos; IN HOUSE','24. Affidamento diretto a Società in-house'),
          array('25-AFFIDAMENTO DIRETTO A SOCIETA&apos; RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP','25. Affidamento diretto a Società raggruppate/consorziate o controllate nelle concessioni di LL.PP'),
          array('26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE','26. Affidamento diretto in adesione ad accordo quadro/convenzione'),
          array('27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE','27. Confronto competitivo in adesione ad accordo quadro/convenzione'),
          array('28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI','28. Procedura ai sensi dei regolamenti degli organi costituzionali'),

          array( '29', '29-PROCEDURA RISTRETTA SEMPLIFICATA'),
          array( '30', '30-PROCEDURA DERIVANTE DA LEGGE REGIONALE'),
          array( '31', '31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL\'IMPORTO CONTRATTUALE'),
          array( '32', '32-AFFIDAMENTO RISERVATO'),
          array( '33', '33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA'),
          array( '34', '34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA'),
          array( '35', '35-PARTERNARIATO PER L’INNOVAZIONE'),
          array( '36', '36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI'),
          array( '37', '37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE'),
          array( '38', '38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI')
        );
        foreach ( $tipi_contraente as $tc ) {
          echo '<option value="'.$tc[0].'" '.selected( $contraente, $tc[0] ) .'>'.$tc[1].'</option>';
        }
        ?>
      </select>
    </p>
    <p class="meta-options hcf_field">
        <label for="avcp_aggiudicazione">Importo aggiudicazione</label>
        <input id="avcp_aggiudicazione" type="text" name="avcp_aggiudicazione" value="<?php echo get_post_meta($post->ID, 'avcp_aggiudicazione', true); ?>">
    </p>
    <p class="meta-options hcf_field" style="width:40%;">
        <label for="avcp_data_inizio">Data inizio</label>
        <input id="avcp_data_inizio" type="date" name="avcp_data_inizio" value="<?php echo ( get_post_meta($post->ID, 'avcp_data_inizio', true) ? date("Y-m-d", strtotime( get_post_meta($post->ID, 'avcp_data_inizio', true) ) ) : '') ?>">
    </p>
    <p class="meta-options hcf_field">
        <label for="avcp_data_fine">Data fine</label>
        <input id="avcp_data_fine" type="date" name="avcp_data_fine" value="<?php echo ( get_post_meta($post->ID, 'avcp_data_fine', true) ? date("Y-m-d", strtotime( get_post_meta($post->ID, 'avcp_data_fine', true) ) ) : '') ?>">
    </p>
</div>
<h4>Prospetto liquidazioni</h4>

  <?php
  $terms = get_terms( 'annirif', array('hide_empty' => 0) );
  $years = array();
  foreach( $terms as $term ) {
    $years[] = $term->name;
  }
  $active_years = array();
  $fields = array();
  foreach( $terms as $term ) {
    if ( get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true) ) {
      $active_years[$term->name] = get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true);
      $fields[] = '"avcp_s_l_'.$term->name.'"';
    }
  }

  function avcp_metabox_details_money_getRow($year, $val) {
    return '<tr>
    <td><b>'.$year.'</b></td>
    <td><input id="avcp_s_l_'.$year.'" onchange="formattaimporto(\'#avcp_s_l_'.$year.'\');updateLiqTot();" placeholder="0.00" type="text" name="avcp_s_l_'.$year.'" id="avcp_s_l_'.$year.'" value="'.$val.'"></td>
    </tr>';
  }

  echo '<table class="widefat fixed" cellspacing="0" id="tab_liquidazioni">
  <thead>
  <tr>
          <th id="columnname" class="manage-column column-columnname" scope="col">Annualità</th>
          <th id="columnname" class="manage-column column-columnname num" scope="col">Somma liquidata<br><span style="font-size:0.8em;">Formato da utilizzare: <b>1234.67</b></span></th>
  </tr>
  </thead>
  <tfoot>
    <tr>

            <th class="manage-column column-columnname" scope="col"><select name="avcp_liquidazioni_add_year" id="avcp_liquidazioni_add_year">';
            foreach( $years as $y ) {
              if ( !in_array( $y, array_keys( $active_years  ) ) ) {
                echo '<option value="'.$y.'">'.$y.'</option>';

              }
            }
            echo '</select><span id="addMoneyRow" class="button-secondary">Aggiungi</span>
            <th class="manage-column column-columnname num" scope="col">TOTALE: € <span style="font-weight:bold;" id="slTot">0.00</span></th>
    </tr>
    </tfoot>
  <tbody style="text-align:center;" id="tab_liquidazioni_body">';

  $active_years = array();
  foreach( $terms as $term ) {
    $val = '';
    if ( get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true) ) {
      $active_years[ $term->name ] = get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true);
      $val = get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true);
      echo avcp_metabox_details_money_getRow( $term->name, $val );
    }
  }
  echo '</tbody></table>';

  echo '<script>
  updateLiqTot();
  function updateLiqTot() {
    var fields = ['.implode(", ", $fields).'];
    var totale = 0;
    fields.forEach(function(entry) {
      totale += +document.getElementById( entry ).value;
    });
    document.getElementById("slTot").textContent= totale.toFixed(2);
  }
  </script>
  ';
}

function avcp_pages_inner_custom_box3( $post ) { //Inizializzazione Metabox 2, senza api bainternet

    echo '<div>';
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
    echo '</ul><input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Pubblica/Aggiorna Gara" accesskey="p" />';
    echo '</div>';
}

add_action( 'add_meta_boxes', 'avcp_meta_box_add' );
function avcp_meta_box_add() {
    add_meta_box( 'anac-metabox-ditte', 'Ditte aggiudicatarie', 'avcp_pages_inner_custom_box3', 'avcp', 'normal', 'high' );
}

add_action( 'save_post_avcp', 'avcp_custom_save_post' );
function avcp_custom_save_post( $post_id ) {
    if ( isset($_POST['avcp_aggiudicatari']) ) {
        update_post_meta($post_id,'avcp_aggiudicatari',$_POST['avcp_aggiudicatari']);
    }
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
