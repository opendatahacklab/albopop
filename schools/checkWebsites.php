<?php
/**
 * Check which of the websites in school - website CSV are actually downloadable. Produces 
 * on the standard output an OWL ontolgy websites.owl reporting just associations between schools
 * and working websites, and on standard error a CSV websites-wrong.csv containing the subset of the school-website
 * associations such that the download test of the reported some error.
 * 
 * Copyright 2018 Cristiano Longo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Cristiano Longo 
 */
require_once('config.php');
require_once('SchoolWebsite.php');
require_once('URLChecker.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOrganization.php');

//TODO move to config
$ontologyiri='http://opendatahacklab.org/schools/scuolecatania.owl';
$ns="$ontologyiri#";

$in = fopen( 'php://stdin', 'r' );
$out = fopen($argv[1], 'w+' );
$err = fopen( $argv[2], 'w+' );

$parser=SchoolWebsite::parse($in);
$checker=new URLChecker();
$ontology=new RDFXMLOntology($ontologyiri);
$ontology->addNamespaces(RDFXMLOrganization::getRequiredNamespaces());
$ontology->addImports(RDFXMLOrganization::getRequiredVocabularies());

foreach($parser as $s){
	echo "chegking $s->codicescuola,$s->sitowebscuola\n";
	if ($checker->check($s->sitowebscuola)){
		$organization=new RDFXMLOrganization($ontology->getXML(), $ns.$s->codicescuola);
		$organization->addWebPage($s->sitowebscuola);
	} else
		fwrite($err, "$s->codicescuola,$s->sitowebscuola\n");
}
fwrite($out,$ontology->getXML()->saveXML());
fclose($out);
fclose($err);
?>
