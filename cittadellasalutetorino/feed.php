<?php 
require ('../phpalbogenerator/AlboPopGenerator.php');
require ('AlboCittaDellaSaluteParserFactory.php');
require ('AlboCittaDellaSaluteItemConverter.php');
$generator = new AlboPopGenerator ( new AlboCittaDellaSaluteParserFactory (), new AlboCittaDellaSaluteItemConverter () );
$generator->outputFeed ("Albo POP della Citta` della Salute di Torino", "Versione POP dell'Albo Pretorio della Citta` della Salute di Torino", "https://www.opendatahacklab.org/albopop/cittadellasalutetorino/feed.php",
		"https://www.opendatahacklab.org/albopop/cittadellasalutetorino/feed.php");
?>