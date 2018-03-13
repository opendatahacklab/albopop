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
	return !(strpos($websitetxt,':') || 
		strpos($websitetxt, '/') ||
		strpos($websitetxt,'?') ||
		strpos($websitetxt,'@') ||
		filter_var("http://$websitetxt", FILTER_VALIDATE_URL)===FALSE);
}

$isDomainNameTestCase=new RDFXMLEARLTestCase($ontology, $ns.'isdomainname');
$isDomainNameChecker=new SchoolWebsiteCheck($ontology, $isDomainNameTestCase->iri, $ns.'isdomainname/', 'isDomainNameCheck');

/**
 * Effectively attempt to connect to the url and download the corresponding resource. Return true if all its OK
 */
function isWorkingURICheck($url){
	$handle=curl_init($url);
	curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
	$result=curl_exec($handle);
	if ($result===FALSE){
		curl_close($handle);
		return FALSE;
	}
	$code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if (strcmp($code,'200')){
		curl_close($handle);
		return FALSE;
	}
	return TRUE;
}
$isWorkingURITestCase=new RDFXMLEARLTestCase($ontology, $ns.'isworkinguri');
$isWorkingURIChecker=new SchoolWebsiteCheck($ontology, $isWorkingURLTestCase->iri, $ns.'isisworkinguri/', 'isWorkingURICheck');

/**
 * Same as isWorkingURICheck but just prepend the http schema to the domain name before check
 */
function isWorkingDomainNameCheck($domainName){
	return isWorkingURICheck("http://$domainName");
}
$isWorkingDomainNameTestCase=new RDFXMLEARLTestCase($ontology, $ns.'isworkingdomainname');
$isWorkingDomainNameURLChecker=new SchoolWebsiteCheck($ontology, $isWorkingDomainNameTestCase->iri, $ns.'isworkingdomainname/', 'isWorkingDomainNameCheck');

$f = fopen( 'php://stdin', 'r' );
$out = fopen( 'php://stderr', 'w+' );
//skip header
fgetcsv($f);
$count=0;
//parse rows
while(($row = fgetcsv($f, 1000, ",")) !== FALSE)
	//here we ignore blank nodes
	if ($row[0]!==null && isUriCheck($row[0])) {
		fwrite($out, "$count\n");
		$count++;
		$schooliri=$row[0];
		$websitetxt=$row[1];
		$isUriTest=$isUriChecker->testWebsite($schooliri, $websitetxt);
		if ($isUriTest->isPassed())
			$isWorkingURLChecker->testWebsite($schooliri, $websitetxt);
		else if ($isUriTest->isFailed()){
			$isDomainNameTest=$isDomainNameChecker->testWebsite($schooliri, $websitetxt);
   			if ($isDomainNameTest->isPassed())
				$isWorkingDomainNameURLChecker->testWebsite($schooliri, $websitetxt);
		}
	}		
echo $ontology->getXML()->saveXML();

?>