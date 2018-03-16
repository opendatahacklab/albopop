<?php
/**
 * Some utility functions to generate test reports
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

require_once(PHPRDFGenerationToolsPATH.'RDFXMLEARL.php');

/**
  * Convert an EARL outcome to $passedstr if the outcome is earl:passed and to $failedstr if the outcome is earl:failed. Otherwise, return an empty string.
 */
function getOutcomeString($outcomeuri, $passedstr, $failedstr){
	if (strcmp(RDFXMLEARLTestResult::$PASSED_OUTCOME_IRI,$outcomeuri)==0)
		return $passedstr;
	if (strcmp(RDFXMLEARLTestResult::$FAILED_OUTCOME_IRI,$outcomeuri)==0)
		return $failedstr;
	return '';
}

function getIsWorkingString($isuri, $isdomainname, $isworkinguri, $isworkingdomainname,$passedstr, $failedstr){
	if (strcmp($passedstr,$isuri)===0)
		return strcmp($passedstr,$isworkinguri)===0 ? $passedstr : $failedstr;
	else if (strcmp($passedstr,$isdomainname)===0)
		return strcmp($passedstr,$isworkingdomainname)===0 ? $passedstr : $failedstr;
	else return '';
}

function isOk($website, $isuri, $isdomainname, $isworking ,$passedstr, $failedstr){
	if (strcmp('Non Disponibile', $website)===0)
		return false;
	if (strcmp($isuri,$failedstr)===0 && strcmp($isdomainname,$failedstr)===0)
		return false;
	if (strcmp($isworking,$failedstr)===0)
		return false;
	return true;
}

?>
