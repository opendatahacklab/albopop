<?php
/** 
 * Check if the value of miur:SITOWEBSCUOLA is a valid URI, for all the schools reported.
 * Read pairs school code, website from stdin
 */

require_once('config.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLEARL.php');
require_once('SchoolWebsiteCheck.php');

$ontologyiri='http://opendatahacklab.org/albopop/schools/isuritest.owl';
$ns="$ontologyiri#";
$testcaseiri=$ns.'isuri';

function checkUriSyntax($websitetxt){
	return !(filter_var($websitetxt, FILTER_VALIDATE_URL)===FALSE);
}

$ontology=SchoolWebsiteCheck::getOntology($ontologyiri,$ns,$testcaseiri,"checkUriSyntax");
$testCase=new RDFXMLEARLTestCase($ontology, $testcaseiri);

$checker=new SchoolWebsiteCheck($ontology, $testcaseiri, $ns, "checkUriSyntax");
$f = fopen( 'php://stdin', 'r' );
$checker->testWebsitesInFile($f);
echo $ontology->getXML()->saveXML();
?>