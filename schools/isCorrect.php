<?php
/**
 *  Generate isCorrect test reports in EARL from a isCorrect csv report
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

function createAssertion($ontology, $ns, $testN, $school, $test, $date, $passed){
     	$assertion=new RDFXMLEARLAssertion($ontology, $ns.'test'.$testN);
	$assertion->setSubject($school);
	$assertion->setTest($ns.$test);
	$outcome=$passed ? RDFXMLEARLTestResult::$PASSED_OUTCOME_IRI : RDFXMLEARLTestResult::$FAILED_OUTCOME_IRI; 
	$testresult=new RDFXMLEARLTestResult($ontology,$ns.'testresult'.$testN, $outcome, $date);
	$assertion->setResult($testresult->iri);
}		

$ontologyiri=BASE.'iscorrectwebsitetest.owl';
$ns=BASE;
$ontology=SchoolWebsiteCheck::getOntology($ontologyiri,$ns);
$isCorrectUriTestCase=new RDFXMLEARLTestCase($ontology, $ns.'iscorrecturi');
$isCorrectDomainNameTestCase=new RDFXMLEARLTestCase($ontology, $ns.'iscorrectdomainname');

$in = fopen( 'php://stdin', 'r' );

//skip header
fgetcsv($in);
$testN=0;

//parse rows
while(($row = fgetcsv($in, 1000, ",")) !== FALSE)
	if ($row[0]!==null) {
		$school=$row[0];
		$test=$row[1];
		$parentdescription=$row[2];
		$schooldescription=$row[3];
		$website=$row[4];
		$date=new DateTime($row[5]);
		$isCorrectTxt=$row[6];
		
		if (strcmp('P',$isCorrectTxt)==0){
			createAssertion($ontology,$ns,$testN,$school,$test,$date,TRUE);
			$testN++;
		} else if (strcmp('F',$isCorrectTxt)==0){
			createAssertion($ontology,$ns,$testN,$school,$test,$date,FALSE);
			$testN++;
		}
	}
echo $ontology->getXML()->saveXML();

?>