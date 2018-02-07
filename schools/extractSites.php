<?php
/** 
 * select columns CODICESCUOLA,SITOWEBSCUOLA
 *  from an Anagrafica Scuole file read from standard input. 
 */

require_once('config.php');
require_once('School.php');

$f = fopen( 'php://stdin', 'r' );
$parser=School::parse($f);
foreach($parser as $s)
	if (strcmp($s->sitowebscuola,'Non Disponibile'))
		echo "$s->codicescuola,$s->sitowebscuola\n";
fclose($f); 
?>