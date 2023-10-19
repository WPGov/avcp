<?php

echo '
<table style="width:100%">
<tbody><tr>
    <th></th>
    <th>Bandi<br/>registrati</th>
    <th>Aggiudicazioni<br/>totali</th>
    <th>Liquidazioni<br/>totali</th>
    <th>Durata<br/>media</th>
</tr>
';

    $terms = get_terms( 'annirif', array('hide_empty' => 0) );
    foreach ( $terms as $term ) {
        query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1', 'annirif' => $term->name) ); global $post;

        $numero_gare_{$term->name} = 0;
        $tot_agg_{$term->name} = 0;
        $tot_liq_{$term->name} = 0;
        $gap_tot_giorni_{$term->name} = 0;

        while ( have_posts() ) : the_post();
                $numero_gare_{$term->name}++;
                $tot_agg_{$term->name} += get_post_meta($post->ID, 'avcp_aggiudicazione', true);
                $tot_liq_{$term->name} += get_post_meta($post->ID, 'avcp_s_l_'.$term->name, true);

                 $datediff = strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true)) - strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true));
                 $gap_tot_giorni_{$term->name} += floor($datediff/(60*60*24));
        endwhile;

        echo '
            <tr>
                <th><a href="' . esc_url(get_term_link( $term->name, 'annirif' )) . '">' . $term->name . '</a></th>
                <td>' . $numero_gare_{$term->name} . '</td>
                <td>€<strong>' . $tot_agg_{$term->name} . '</strong></td>
                <td>€<strong>' . $tot_liq_{$term->name} . '</strong></td>
                <td>' . @intval($gap_tot_giorni_{$term->name} / $numero_gare_{$term->name}) . ' gg</td>
            </tr>
        ';

        wp_reset_query();
    }

echo '
<tr><td colspan="4"><small>NB. L\'annualità 2013 comprende le gare dal 1° dicembre 2012</small><td></tr></tbody></table>';

?>
