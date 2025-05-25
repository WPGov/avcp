<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Use shortcode_atts in the parent file, so $anno is already set and sanitized
if (!isset($anno)) {
    $anno = 'all';
}
$anno = sanitize_text_field($anno);
?>
<script type="text/javascript" src="<?php echo esc_url( plugin_dir_url(__FILE__) . 'includes/excellentexport.min.js' ); ?>"></script>
<table class="order-table table" id="gare">
        <thead>
            <tr><td colspan="7">
            Bandi di gara - <strong><?php echo ($anno !== 'all') ? esc_html($anno) : 'Tutti gli anni'; ?></strong>
            <input style="float:right;" type="search" id="s" class="light-table-filter" data-table="order-table" placeholder="Cerca...">
                </td></tr>
            <tr>
                <th colspan="2">Oggetto</th>
                <th>CIG</th>
                <th>Importo<br/>agg.</th>
                <th>Durata<br/>lavori</th>
                <th>Modalità<br/>affidamento</th>
            </tr>
        </thead>
        <tbody>

<?php

$args = array(
    'post_type'      => 'avcp',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'posts_per_page' => -1,
);

if ($anno !== '' && $anno !== 'all') {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'annirif',
            'field'    => 'slug',
            'terms'    => sanitize_title($anno),
        ),
    );
}

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

        global $post;

        $d_i = get_post_meta(get_the_ID(), 'avcp_data_inizio', true);
        $d_f = get_post_meta(get_the_ID(), 'avcp_data_fine', true);

        $d_i_fmt = $d_i ? date("d/m/Y", strtotime($d_i)) : '-';
        $d_f_fmt = $d_f ? date("d/m/Y", strtotime($d_f)) : '-';

        echo '<tr style="display: table-row;">';
        echo '<td colspan="2"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></td>';
        echo '<td>' . esc_html(get_post_meta($post->ID, 'avcp_cig', true)) . '</td>';
        echo '<td align="center">€<strong>' . esc_html(get_post_meta($post->ID, 'avcp_aggiudicazione', true)) . '</strong></td>';
        echo '<td align="center">' . esc_html($d_i_fmt) . '<br/>' . esc_html($d_f_fmt) . '<br/>';

        if (class_exists('DateTime') && $d_i && $d_f) {
            try {
                $date1 = new DateTime($d_i);
                $date2 = new DateTime($d_f);
                $diff = $date2->diff($date1)->format("%a");
                echo '<small><strong>' . esc_html($diff) . '</strong> gg</small>';
            } catch (Exception $e) {
                // Do nothing
            }
        }

        echo '</td>';

        $contraente = get_post_meta(get_the_ID(), 'avcp_contraente', true);
        echo '<td>' . esc_html(strtolower(substr($contraente, 3))) . '</td>';
        echo '</tr>';

    endwhile;
    wp_reset_postdata();
endif;

echo '</tbody>

<tfoot>
    <tr>
        <td colspan="6">';

        echo '<div style="float:right;">
                    Scarica in

        <a href="' . esc_url(get_site_url() . '/avcp') . '" target="_blank" title="File .xml"><button>XML</button></a>
        <a download="' . esc_attr(get_bloginfo('name')) . '-gare' . esc_attr($anno) . '.xls" href="#" onclick="return ExcellentExport.excel(this, \'gare\', \'Gare\');"><button>EXCEL</button></a>
        <a download="' . esc_attr(get_bloginfo('name')) . '-gare' . esc_attr($anno) . '.csv" href="#" onclick="return ExcellentExport.csv(this, \'gare\');"><button>CSV</button></a>
        </div>';

            if (get_option('wpgov_show_love')) {
                echo '<a href="http://www.wpgov.it" target="_blank" title="Software &copy; WPGov"><img src="' . esc_url(plugin_dir_url(__FILE__) . 'images/wpgov.png') . '" /></a>';
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
