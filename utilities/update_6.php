<?php

    query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1') ); global $post;
    if ( have_posts() ) : while ( have_posts() ) : the_post();

        $anno_inizio = date('Y',strtotime(get_post_meta(get_the_ID(), 'avcp_data_inizio', true)));
        $anno_fine = date('Y',strtotime(get_post_meta(get_the_ID(), 'avcp_data_fine', true)));

        //ex  2013 2013 -- 1     2014 2013 --2
        if ( $anno_inizio == '2012' ) { $anno_inizio = '2013'; }
        if ( $anno_fine == '2012' ) { $anno_fine = '2013'; }

        $anni_gara = $anno_fine - $anno_inizio + 1;

        if ($anni_gara == 1) {
            update_post_meta($post->ID, 'avcp_s_l_' .  $anno_fine, get_post_meta($post->ID, 'avcp_somme_liquidate', true));
        } else if ($anni_gara == 2) {
            update_post_meta($post->ID, 'avcp_s_l_' .  $anno_fine, get_post_meta($post->ID, 'avcp_somme_liquidate', true));
            update_post_meta($post->ID, 'avcp_s_l_' .  $anno_inizio, get_post_meta($post->ID, 'avcp_somme_liquidate_prev', true));
        } else if ($anni_gara == 3) {
            update_post_meta($post->ID, 'avcp_s_l_' .  $anno_fine, get_post_meta($post->ID, 'avcp_somme_liquidate', true));
            update_post_meta($post->ID, 'avcp_s_l_' .  --$anno_fine, get_post_meta($post->ID, 'avcp_somme_liquidate_prev', true));
            update_post_meta($post->ID, 'avcp_s_l_' .  $anno_inizio, get_post_meta($post->ID, 'avcp_somme_liquidate_prevprev', true));

        } else {
            anac_add_log('Update_6 - La gara '.$post->ID.' ha ritornato un valore diverso da {1, 2, 3}. Controllarla interamente!!', 1);
        }

    endwhile; else:
    endif;

    anac_add_log('L\'aggiornamento del database alla versione 6 è stato completato. Verificare le gare...', 0);

?>
