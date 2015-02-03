<?php
add_filter('plugin_action_links', 'avcp_plugin_action_links', 10, 2);

function avcp_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '&copy; <b>wpgov.it</b>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

add_filter( 'plugin_row_meta', 'avcp_plugin_meta_links', 10, 2 );
function avcp_plugin_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);
	
	// create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2">Donazione</a>' )
		);
	}
	return $links;
}
?>