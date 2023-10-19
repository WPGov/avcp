<?php

if ( ! defined( 'ABSPATH' ) ) exit;

        $pluginversion = get_option('avcp_version_number');

        //In questo blocco sono impostati i vari aggiornamenti ad alcuni campi del database che vanno modificati per poter eseguire la versione relativa all'aggiornamento stesso...

        if (version_compare($pluginversion, "3.1", "<")) {
            avcp_activate();
        }

        if (version_compare($pluginversion, "3.2", "<")) {
            query_posts( array( 'post_type' => 'avcp', 'posts_per_page' => '-1') ); global $post;
            if ( have_posts() ) : while ( have_posts() ) : the_post();
                $tip_contraente = get_post_meta($post->ID, 'avcp_contraente', true);
                if ($tip_contraente == '25-AFFIDAMENTO DIRETTO A SOCIETA') {
                    update_post_meta($post->ID, 'avcp_contraente', '25-AFFIDAMENTO DIRETTO A SOCIETA&apos; RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP');
                } else if ($tip_contraente == '24-AFFIDAMENTO DIRETTO A SOCIETA') {
                    update_post_meta($post->ID, 'avcp_contraente', '24-AFFIDAMENTO DIRETTO A SOCIETA&apos; IN HOUSE');
                }
            endwhile; else:
            endif;
            creafilexml ('2013');
            creafilexml ('2014');
        }

        if (version_compare($pluginversion, "4.2", "<")) {
            global $current_user;
            $user_id = $current_user->ID;
            delete_user_meta($user_id, 'avcp_upgrade_3', 'true', true);
            avcp_activate();
        }

        if (version_compare($pluginversion, "5.1", "<")) {
            creafilexml('2013');
            creafilexml('2014');
            creafilexml('2015');
        }

        if (version_compare($pluginversion, "5.2", "<")) {
            delete_option('avcp_showxml');
            delete_option('avcp_tab_jqueryui');
            delete_option('avcp_export');
        }

        if (version_compare($pluginversion, "6", "<")) {
            avcp_activate();
            require_once(plugin_dir_path(__FILE__) . 'utilities/update_6.php');
        }

        if (version_compare($pluginversion, "6.4", "<")) {
            delete_option('avcp_showlove');
            delete_option('avcp_enable_editor');
        }

        anac_add_log('Script di aggiornamento '.$pluginversion.' richiamato', 0);
?>
