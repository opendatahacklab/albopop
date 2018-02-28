<?php
/** 
 * Check if the value of miur:SITOWEBSCUOLA is a valid URI, for all the schools reported.
 * Read pairs school code, website from stdin
 */

require_once('config.php');
require_once('School.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLEARL.php');

$ontologyiri='http://opendatahacklab.org/schools/urisyntaxtest.owl';
$ns="$ontologyiri#";

$ontology=new RDFXMLOntology($ontologyiri);
$ontology->addNamespaces(RDFXMLEARLAssertion::getRequiredNamespaces());
$ontology->addImports(RDFXMLEARLAssertion::getRequiredVocabularies());

$testCase=new RDFXMLEARLTestCase($ontology, $ns.'test'); 
$f = fopen( 'php://stdin', 'r' );

//skip header
fgetcsv($f);

//parse rows
while(($row = fgetcsv($f, 1000, ",")) !== FALSE){
	$schoolcode=$row[0];
	$website=$row[1];
     	$assertion=new RDFXMLEARLAssertion($ontology, $ns.'school'.$schoolcode);
	$assertion->setSubject(SCHOOLNS.$schoolcode);
	$assertion->setTest($testCase->iri);
	$outcome= filter_var($website, FILTER_VALIDATE_URL) === FALSE ? RDFXMLEARLTestResult::$FAILED_OUTCOME_IRI : 
		RDFXMLEARLTestResult::$PASSED_OUTCOME_IRI;
	$result=new RDFXMLEARLTestResult($ontology, $ns.'test'.$schoolcode,$outcome);
	echo "$website\t$outcome\n";
}
echo $ontology->getXML()->saveXML();
fclose($f); 
?>