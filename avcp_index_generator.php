<?php

function creafileindice() {
$XML_data_aggiornamento =  date("Y-m-d");
$avcp_denominazione_ente = get_option('avcp_denominazione_ente');

	$XML_FILE .= '<?xml version="1.0" encoding="UTF-8"?>
		<indici xsi:noNamespaceSchemaLocation="datasetIndiceAppaltiL190.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<metadata>
		<titolo>Pubblicazione Indice 1 legge 190</titolo>
		<abstract>Pubblicazione legge 190 - 2012 + 2013 - Wordpress Plugin AVCP XML di Marco Milesi</abstract>
		<dataPubblicazioneIndice>2014-01-01</dataPubblicazioneIndice>
		<entePubblicatore>' . $avcp_denominazione_ente . '</entePubblicatore>
		<dataUltimoAggiornamentoIndice>' . $XML_data_aggiornamento . '</dataUltimoAggiornamentoIndice>
		<annoRiferimento>2013</annoRiferimento>
		<urlFile>' . site_url() . '/avcp/indice.xml</urlFile>
		<licenza>IODL</licenza>
		</metadata>
		<indice>
		<dataset id="ID_1">
		<linkDataset>' . site_url() . '/avcp/2012.xml</linkDataset>
		<dataUltimoAggiornamento>' . $XML_data_aggiornamento . '</dataUltimoAggiornamento>
		</dataset>
		<dataset id="ID_2">
		<linkDataset>' . site_url() . '/avcp/2013.xml</linkDataset>
		<dataUltimoAggiornamento>' . $XML_data_aggiornamento . '</dataUltimoAggiornamento>
		</dataset>
		</indice>
		</indici>';

	// Open or create a file (this does it in the same dir as the script)
	$XML_PATH = ABSPATH . 'avcp/indice.xml';
	$my_file = fopen($XML_PATH, "w");

	// Write the string's contents into that file
	fwrite($my_file, $XML_FILE);

	// Close 'er up
	fclose($my_file);
}

?>