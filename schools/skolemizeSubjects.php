<?php
/**
 *
 * Replace blank nodes in triple subjects to well known IRIs in a ntriples file. The base prefix for such well known IRIs is passed as parameter, whereas the ntriples file is read
 * from stdin. We assume that triples are exactly one per line.
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
$err=fopen( 'php://stderr', 'w+' );
if ($argc<1){
	fwrite($err, "Missing base IRI for skolemization");
	exit(-1);
}
$ns=$argv[1];
$in=fopen( 'php://stdin', 'r' );
while(($row = fgets($in)) !== FALSE && sizeof($row)!==0){
	$subject=explode(' ',$row)[0];
	if (preg_match('#_:.*#', $subject)){
		$encoded=urlencode($subject);
		$newsubject='<'.$ns.'.well-known/bnode/'.$encoded.'>';
		$newrow=preg_replace ("#^$subject#" , $newsubject , $row);
		echo $newrow;
	} else
		echo $row;
}
?>