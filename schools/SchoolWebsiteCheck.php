<?php
/** 
 * Generic test on rows of a CSV containing school code and website 
 * Read pairs school code, website from stdin create an ontology from tests
 */

require_once('config.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOntology.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLEARL.php');
require_once(PHPRDFGenerationToolsPATH.'RDFXMLOrganization.php');

class SchoolWebsiteCheck{

	private $ontology;
	private $testcaseiri;
	private $ns;
	private $testingFunction;

	private $testN=0;
	private $cache=array();

	/**
	 * @param $ontology the ontology where test results will be placed
	 * @param $testcaseiri IRI of the resource representing the test case
	 * @ns namespace which will be used for test results
	 * @param $testingFunction the test, takes a string in input and return a boolean value
	 * 
	 */
	public function __construct($ontology, $testcaseiri, $ns, $testingFunction){
		$this->ontology=$ontology;
		$this->testcaseiri=$testcaseiri;
		$this->ns=$ns;
		$this->testingFunction=$testingFunction;
	} 

	/**
	 * Create an ontology for tests  with a single test case
	 *
	 */
	public static function getOntology($ontologyiri, $ns){
		$ontology=new RDFXMLOntology($ontologyiri);
		$ontology->addNamespaces(RDFXMLEARLAssertion::getRequiredNamespaces());
		$ontology->addImports(RDFXMLEARLAssertion::getRequiredVocabularies());
		$ontology->addNamespaces(RDFXMLOrganization::getRequiredNamespaces());
		$ontology->addImports(RDFXMLOrganization::getRequiredVocabularies());
		$ontology->addNamespaces(array('miur' => MIURNS));
		return $ontology;
	}

	/**
	 * perform a test on a specific web site, add the test result to the ontology
	 *
	 * @param $testsubjectiri the item in test, usually the one the website refers to
	 * @param $websitetxt a string intended to represent a website, usually a IRI
	 *
	 * @return the RDFXMLEARLTestResult instance
	 */
	public function testWebsite($schooliri, $websitetxt){
		$testsubjectiri=$schooliri;
	     	$assertion=new RDFXMLEARLAssertion($this->ontology, $this->ns.'test'.$this->testN);
		$assertion->setSubject($testsubjectiri);
		$assertion->setTest($this->testcaseiri);
		$testresult = $this->getTestResult($websitetxt);
		$assertion->setResult($testresult->iri);
		$this->testN++;
		return $testresult;
	}


	/**
	 * Retrieve the earl:TestResult resource for the specified $websitetxt string. Reuse an existing one 
	 * if such a string has been tested yet. Otherwise, perform the test and add the earl:TestResult instance.
	 *
	 * @return the EARLTestResult instance
	 */
	private function getTestResult($websitetxt){
		if (array_key_exists($websitetxt, $this->cache))
			return $this->cache[$websitetxt];
		$testresult = ($this->testingFunction)($websitetxt);
		$outcome= $testresult === FALSE ? RDFXMLEARLTestResult::$FAILED_OUTCOME_IRI : 
			RDFXMLEARLTestResult::$PASSED_OUTCOME_IRI;
		$result=new RDFXMLEARLTestResult($this->ontology, $this->ns.'testresult'.$this->testN,$outcome);
		$this->cache[$websitetxt]=$result;
		return $result;
	}

    	/**
	 * Test all the pairs testsubjectiri - websitetxt read by the given handle one by one and add test results to the ontology.
	 *
	 */
	public function testWebsitesInFile($handle){
		//skip header
		fgetcsv($handle);

		//parse rows
		while(($row = fgetcsv($handle, 1000, ",")) !== FALSE)
			if ($row[0]!==null && strpos('http://',$row[0])==0)
				$this->testWebsite($row[0], $row[1]);			
	}

} 
?>