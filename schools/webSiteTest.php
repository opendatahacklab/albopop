<?php
/**
 *  Perform some tests to the website associated to a school
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
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLEARL.php');
require_once('SchoolWebsiteCheck.php');

$ontologyiri='http://opendatahacklab.org/albopop/schools/websitetest.owl';
$ns="$ontologyiri/";
$ontology=SchoolWebsiteCheck::getOntology($ontologyiri,$ns);

function isUriCheck($websitetxt){
	return !(filter_var($websitetxt, FILTER_VALIDATE_URL)===FALSE);
}
$isUriTestCase=new RDFXMLEARLTestCase($ontology, $ns.'isuri');
$isUriChecker=new SchoolWebsiteCheck($ontology, $isUriTestCase->iri, $ns.'isuri/', 'isUriCheck');

function isDomainNameCheck($websitetxt){
	return !(strpos(':',$websitetxt) ||
	strpos('/',$websitetxt) ||
	strpos('?',$websitetxt) ||
	(filter_var("http://$websitetxt", FILTER_VALIDATE_URL)===FALSE));
}
$isDomainNameTestCase=new RDFXMLEARLTestCase($ontology, $ns.'isdomainname');
$isDomainNameChecker=new SchoolWebsiteCheck($ontology, $isDomainNameTestCase->iri, $ns.'isdomainname/', 'isDomainNameCheck');

$f = fopen( 'php://stdin', 'r' );
//skip header
fgetcsv($f);

//parse rows
while(($row = fgetcsv($f, 1000, ",")) !== FALSE)
	if ($row[0]!==null && strpos($row[0],'http://')===0) //here we ignore blank nodes
		$isUriChecker->testWebsite($row[0], $row[1]);			
echo $ontology->getXML()->saveXML();

?>