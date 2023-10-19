<?php

    function get_somme_liquidate ($ID, $YEAR) {

        // 3 CAMPI SOMME LIQUIDATE (ANNO, ANNOPRIMA, ANNOPRIMAPRIMA)
        $avcp_somme_liquidate = get_post_meta($ID, 'avcp_somme_liquidate', true);
        $avcp_somme_liquidate_annoprima =  get_post_meta($ID, 'avcp_somme_liquidate_prev', true);
        $avcp_somme_liquidate_annoprimaprima =  get_post_meta($ID, 'avcp_somme_liquidate_prevprev', true);

        if ($avcp_somme_liquidate == '') { $avcp_somme_liquidate = '0.00'; }
        if ($avcp_somme_liquidate_annoprima == '') { $avcp_somme_liquidate_annoprima = '0.00'; }
        if ($avcp_somme_liquidate_annoprimaprima == '') { $avcp_somme_liquidate_annoprimaprima = '0.00'; }


        $counter_terms = 0;
        $ultimo_anno_gara = 0;

        $terms = get_terms( 'annirif', array('hide_empty' => 0) );
        foreach ( $terms as $term ) {
            if (has_term( $term->name, 'annirif', $ID )) {
                $counter_terms++;
                if ($term->name > $ultimo_anno_gara) {
                    $ultimo_anno_gara = $term->name;
                }
            }
        }

        if ($counter_terms == 1) {
            return $avcp_somme_liquidate;
        } else if ($counter_terms == 2) {
            //Stampa liquidate se $anno è
            if ($anno == $ultimo_anno_gara) {
                return number_format((float)($avcp_somme_liquidate + $avcp_somme_liquidate_annoprima), 2, '.', '');
            } else if ($anno == $ultimo_anno_gara -1) {
                return $avcp_somme_liquidate_annoprima;
            }
        } else if ($counter_terms == 3) {
            if ($anno == $ultimo_anno_gara) {
                return number_format((float)($avcp_somme_liquidate + $avcp_somme_liquidate_annoprima + $avcp_somme_liquidate_annoprimaprima), 2, '.', '');
            } else if ($anno == $ultimo_anno_gara -1) {
                return number_format((float)($avcp_somme_liquidate_annoprima + $avcp_somme_liquidate_annoprimaprima), 2, '.', '');
            } else if ($anno == $ultimo_anno_gara -2) {
                return $avcp_somme_liquidate_annoprimaprima;
            }
        }

        return false;
    }
?>
