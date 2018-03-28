<?php
/**
 * Read on the standard input the  school-working-websites.sparql query results.
 * Create a CSV of working sites but with the last column to be manually filled with the isDirectReport results (P for passed, F for failed).
 * The CSV created has the following columns : school URI,  test (iscorrecturi or iscorrectdomainname), parent description, school description, the website and the empty one for the isCorrect resuls
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
$isworkinguriTestIRI = BASE.'websitetest.owl/isworkinguri';
$isworkingdomainnameTestIRI = BASE.'websitetest.owl/isworkingdomainname';

$in = fopen( 'php://stdin', 'r' );
$out = fopen( 'php://stdout', 'w' );
$err = fopen( 'php://stderr', 'w' );

//skip header in query results
fgetcsv($in);

//add heade to results
fputcsv($out, array('school','test','parentdescription','schooldescription','website','date','isCorrect'));

$date=new DateTime();

//parse rows
while(($row = fgetcsv($in, 1000, ",")) !== FALSE)
	if ($row[0]!==null) {
		$school=$row[0];
		$schooldescription=$row[1];
		$parentdescription=$row[2];
		$website=$row[3];
		$testcase=$row[4];
		$test = strcmp($isworkinguriTestIRI,$testcase)===0 ? 'iscorrecturi' : 'iscorrectdomainname';
		fputcsv($out, array($school, $test, $parentdescription, $schooldescription,$website,$date->format('Y-m-d'),''));
	}		
fflush($out);
?>

