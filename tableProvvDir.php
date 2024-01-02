<?php
/*
 * Description: Aggiunto shortcode "provvDir" per generazione tabella Provvedimenti Dirigenti
 * Author: Maurizio Rosso
 * Date: 21/12/2023
 */
?>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>includes/excellentexport.min.js"></script>
<table class="order-table table" id="provvDir">
        <thead>
            <tr><td colspan="7">
            Provvedimenti dirigenti - <strong><?php if ($anno != 'all') { echo $anno; } else { echo 'Tutti gli anni'; } ?></strong>
            <input style="float:right;" type="search" id="s" class="light-table-filter" data-table="order-table" placeholder="Cerca...">
                </td></tr>
            <tr>
                <th>Ufficio</th>
                <th>Società<br/>aggiudicatrice</th>
                <th>Codice<br/>fiscale</th>
                <th>Oggetto</th>
                <th>Importo<br/>di aggiudicazione</th>
                <th>Modalità<br/>affidamento</th>
            </tr>
        </thead>
        <tbody>

<?php

    if ($anno == "all") {
        $anno = '';
    }

    query_posts(
        array( 'post_type' => 'avcp', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => -1, 'annirif' => $anno)
    );

    while ( have_posts() ) : the_post();

        global $post;
			
        echo '<tr style="display: table-row;">';
        echo '<td>' . get_option('avcp_denominazione_ente')  . '</td>';        

		$dittepartecipanti = get_the_terms( $post->ID, 'ditte' );
		$cats = get_post_meta($post->ID,'avcp_aggiudicatari',true);
		if(is_array($dittepartecipanti)) {
						
			foreach ($dittepartecipanti as $term) {
				$cterm = get_term_by('name',$term->name,'ditte');
				$cat_id = $cterm->term_id; 
				$term_meta = get_option( "taxonomy_$cat_id" );
				$term_return = esc_attr( $term_meta['avcp_codice_fiscale'] );
				$checked = (in_array($cat_id,(array)$cats)? ' checked="checked"': "");
				if ($checked) {
					echo '<td>' . $term->name . '</td>';
					echo '<td>' . $term_return . '</td>';
				}	
			}
		}

        echo '<td>' . get_the_title() . '</td>';
        echo '<td align="center">€<strong>' . get_post_meta($post->ID, 'avcp_aggiudicazione', true) . '</strong></td>';
        echo '<td>' . strtolower(substr(get_post_meta(get_the_ID(), 'avcp_contraente', true), 3)) . '</td>';
        echo '</tr>';

    endwhile;
    wp_reset_query();

    echo '</tbody>

    <tfoot>
        <tr>
            <td colspan="6">';

            echo '<div style="float:right;">
                        Scarica in

            <a href="' . get_site_url() . '/avcp" target="_blank" title="File .xml"><button>XML</button></a>
            <a download="' . get_bloginfo('name') . '-provvedimenti' . $anno . '.xls" href="#" onclick="return ExcellentExport.excel(this, \'provvedimenti\', \'Provvedimenti\');"><button>EXCEL</button></a>
            <a download="' . get_bloginfo('name') . '-provvedimenti' . $anno . '.csv" href="#" onclick="return ExcellentExport.csv(this, \'provvedimenti\');"><button>CSV</button></a>
            </div>';

                if (get_option('wpgov_show_love')) {
                    echo '<a href="http://www.wpgov.it" target="_blank" title="Software &copy; WPGov"><img src="' . plugin_dir_url(__FILE__) . 'images/wpgov.png" /></a>';
                }

            echo '</td>
        </tr>
    </tfoot>
    </table>';

    echo '<div class="clear"></div>';

		


?>

<script>

(function(document) {
    'use strict';

    var LightTableFilter = (function(Arr) {

        var _input;

        function _onInputEvent(e) {
            _input = e.target;
            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            Arr.forEach.call(tables, function(table) {
                Arr.forEach.call(table.tBodies, function(tbody) {
                    Arr.forEach.call(tbody.rows, _filter);
                });
            });
        }

        function _filter(row) {
            var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }

        return {
            init: function() {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function(input) {
                    input.oninput = _onInputEvent;
                });
            }
        };
    })(Array.prototype);

    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete') {
            LightTableFilter.init();
        }
    });

})(document);

</script>
