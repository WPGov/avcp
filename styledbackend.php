<?php
function getJs(){
	wp_register_script( 'avcp_functions', plugins_url('avcp/includes/avcp_functions.js'));
	wp_enqueue_script( 'avcp_functions');
}

function sfondo_avcp_trasparenza() {
    wp_register_style( 'avcp_style',  plugins_url('avcp/includes/avcp_admin.css') );
    wp_enqueue_style( 'avcp_style');

}
add_filter('admin_footer', 'getJs');
add_action('admin_enqueue_scripts', 'sfondo_avcp_trasparenza');
?>