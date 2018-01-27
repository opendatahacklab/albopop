<?php
/** 
 * select columns CODICEISTITUTORIFERIMENTO,CODICESCUOLA,SITOWEBSCUOLA
 *  from an Anagrafica Scuole file read from standard input. 
 */

require_once('config.php');
require_once('School.php');
require_once('SchoolParser.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOrganization.php');

$ontologyiri='http://opendatahacklab.org/schools/scuolecatania.owl';
$ns="$ontologyiri#";

$ontology=new RDFXMLOntology($ontologyiri);
$ontology->addNamespaces(RDFXMLOrganization::getRequiredNamespaces());
$ontology->addImports(RDFXMLOrganization::getRequiredVocabularies());

$f = fopen( 'php://stdin', 'r' );
$parser=new SchoolParser($f);
foreach($parser as $s){
	if ($s->codicescuola!==null){
		$organization=new RDFXMLOrganization($ontology->getXML(), $ns.$s->codicescuola);
		$organization->addName($s->denominazionescuola);
		if ($s->codicescuola!=$s->codiceistitutoriferimento)
			$organization->setAsSuborganization($ns.$s->codiceistitutoriferimento);
	}
}
fclose($f); 

echo $ontology->getXML()->saveXML();
?>