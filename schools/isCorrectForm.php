<?php
/**
 *  Generate a form to report the result of the manual test isCorrect
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
require_once('reportUtils.php');

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="report.css"/>
	</head>
	<body>
		<h1>IsCorrect Form</h1>
		<p>Form utilizzata per riportare i risultati del test manuale <em>isCorrect</em>, che verifica se alla URL o al nome di dominio indicati corrisponda effettivamente la pagina della 
		scuola associata.</p>
		<form action="generateIsCorrect.php" method="POST">
		<table>
			<thead>
				<tr>
					<th>scuola</th>
					<th>sito web</th>
					<th>isCorrect</th>
				</tr>
			<thead>
			<tbody>
<?php

$f = fopen( 'php://stdin', 'r' );
$err = fopen( 'php://stderr', 'w+' );
//skip header
fgetcsv($f);

$passedstr='Si';
$failedstr='No';
$iccorrecturitest=BASE.'iscorrecturi';
$iscorrectdomainnametest=BASE.'iscorrectdomainname';

//parse rows
while(($row = fgetcsv($f, 1000, ",")) !== FALSE)
	//here we ignore blank nodes
	if ($row[0]!==null) {
		$school=$row[0]; 
		$description="$row[2] / $row[1]";
		$website=$row[3];
		$testcase=$row[4];
		$isuri= (strcmp($testcase,BASE.'websitetest.owl/isworkingdomainname')===FALSE);
		$url=  $isuri ?  $website : "http://$website";
		
		echo "\t\t\t\t<tr>\n";
		echo "\t\t\t\t\t<td>$description</td>\n";
		echo "\t\t\t\t\t<td><a href=\"$url\" target=\"schoolwebsite\">$website</a></td>\n";

		$test= $isuri ? urlencode($iscorrecturitest) : urlencode($iscorrectdomainnametest);
		$schoolencoded=urlencode($school);
		
		echo "\t\t\t\t\t<td>\n";
     		echo "\t\t\t\t\t\t<label> Yes <input type=\"radio\" name=\"$schoolencoded\" value=\"P$test\" /></label>\n";
		echo "\t\t\t\t\t\t<label> No <input type=\"radio\" name=\"$schoolencoded\" value=\"F$test\" /></label>\n";
		echo "\t\t\t\t\t</td>\n";
		echo "\t\t\t\t</tr>\n";
	}		
?>
			</tbody>
		</table>
	</body>
</html>

