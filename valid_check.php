<?php
function avcp_valid_check() {
	// Enable user error handling 
	libxml_use_internal_errors(true);
	
	$terms = get_terms( 'annirif', array('hide_empty' => 0) );
	foreach ( $terms as $term ) {
		$xml = new DOMDocument(); 
		$xml->load(ABSPATH . 'avcp/' . $term->name. '.xml');
		if (!$xml->schemaValidate(ABSPATH . 'wp-content/plugins/avcp/includes/datasetAppaltiL190.xsd')) {
			$errori .= $term->name . ' ';
		}
	}
	if ($errori) {
		update_option('avcp_invalid', '1');
	} else {
		update_option('avcp_invalid', '0');
	}
}
?>