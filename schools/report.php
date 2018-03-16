<?php
/**
 *  Genera un report in html sui risultati di alcuni test letti dallo standard input.
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
		<h1>Test sul Dataset delle Scuole Statali</h1>
		<p>Questa pagina riporta i risultati di alcuni test eseguiti sul dataset <a href="http://dati.istruzione.it/opendata/opendata/catalogo/elements1/?area=Scuole">Informazioni anagrafiche scuole statali</a> (distribuzione RDF)
		del  <em>Ministero dell'Istruzione dell'Universit&agrave; e della Ricerca</em>. &Egrave; stato eseguito il <?php echo (new DateTime())->format('d/m/Y'); ?> e ha riguardato solo le scuole nel comune di Catania.</p>
		<p>Tutti i test sono eseguiti automaticamente e riguardano il valore del campo <em>SITOWEBSCUOLA</em>:</p>
		<dl>
			<dt>isUri</dt><dd>controlla se &egrave; conforme alla specifica <a href="https://www.rfc-editor.org/rfc/rfc3986.txt" title="RFC 3986">URI</a>;</dd>
			<dt>isDomainName</dt><dd>eseguito solo se <em>isUri</em> &egrave; fallito, controlla se &egrave; un nome di dominio;</dd>
			<dt>isWorking</dt><dd>controlla che al valore corrisponda una pagina web effettivamente raggiungibile.</dd>
		</dl>
		<table>
			<thead>
				<tr>
					<th>codice</th>
					<th>scuola</th>
					<th>sito web</th>
					<th>isUri</th>
					<th>isDomainName</th>
					<th>isWorking</th>
				</tr>
			<thead>
			<tbody>
<?php

$f = fopen( 'php://stdin', 'r' );
$err = fopen( 'php://stderr', 'w+' );
//skip header
fgetcsv($f);

$total=0;
$isuricount=0;
$isdomainnamecount=0;
$isworkingcount=0;
$missing=0;

$passedstr='Si';
$failedstr='No';

//parse rows
while(($row = fgetcsv($f, 1000, ",")) !== FALSE)
	//here we ignore blank nodes
	if ($row[0]!==null && strcmp($row[1], $row[3])) {
		$code=$row[1]; 
		$description="$row[4] / $row[2]";
		$parentcode=$row[3];
		$website=$row[5];
		$isuri=getOutcomeString($row[6],$passedstr,$failedstr);
		$isdomainname=getOutcomeString($row[7],$passedstr,$failedstr);
		$isworkinguri=getOutcomeString($row[8],$passedstr,$failedstr);
		$isworkingdomainname=getOutcomeString($row[9],$passedstr,$failedstr);
		$isworking=getIsWorkingString($isuri, $isdomainname, $isworkinguri, $isworkingdomainname,$passedstr, $failedstr);
		$cssclass=isOk($website, $isuri, $isdomainname, $isworking ,$passedstr, $failedstr) ? 'passed' : 'failed';

		echo "\t\t\t\t<tr class=\"$cssclass\">\n";
		echo "\t\t\t\t\t<td>$code</td>\n";
		echo "\t\t\t\t\t<td>$description</td>\n";
		echo "\t\t\t\t\t<td>$website</td>\n";
		echo "\t\t\t\t\t<td>$isuri</td>\n";
		echo "\t\t\t\t\t<td>$isdomainname</td>\n";
		echo "\t\t\t\t\t<td>$isworking</td>\n";
		echo "\t\t\t\t</tr>\n";

		$total++;
		if (strcmp('Non Disponibile',$website)===0){
			$missing++;
		}
		else{
			if (strcmp($isuri,$passedstr)===0) $isuricount++;
			if (strcmp($isdomainname,$passedstr)===0) $isdomainnamecount++;
			if (strcmp($isworking,$passedstr)===0) $isworkingcount++;
		}
	}		
?>
			</tbody>
		</table>

		<h2>Sommario</h2>
		<ul>
			<li>Totali <?php echo $total;?></li>
			<li>Non Disponibili <?php echo $missing;?></li>
			<li>isUri <?php echo $isuricount;?></li>
			<li>isDomainName <?php echo $isdomainnamecount;?></li>
			<li>isWorking <?php echo $isworkingcount;?></li>
		</ul>

		<h2>Dati</h2>
		<p>I dati si intendono rilasciati con licenza CC-BY-0.4 (attribuzione).</p>
		<ul>
			<li>Selezione delle <a href="schools-in-catania.ttl">scuole nel comune di catania</a> in formato RDF Turtle e con IRI Skolemizzate.</li>
			<li><a href="websitetest.owl">Risultati dei test</a> in formato RDF/XML (vocabolario EARL).</li>
		</ul>
	</body>
</html>

