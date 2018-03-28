<?php
/**
 *
 * Perform a sparql query to the sparql endpoint of the MIUR SCUANAGRAFESTAT dataset. 
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

 
/**
 * Read  a sparql query from standard input
 */
function readQueryFromStdIn(){
	$in=fopen( 'php://stdin', 'r' );
	$query='';
	while(($row = fgets($in)) !== FALSE)
		$query.=$row;
	return $query;
}

/**
 * Perform a multiple queries using LIMIT and OFFSET  to get chunks of specified size for each query. Send output to the specified handle
 *
 * @return
*/
function performChunkedQuery($endpoint, $query,$limit){
	$handle=fopen('php://stdout','w+');
	performQueryChunk($endpoint, $query,$limit,$handle,0);
	fflush($handle);
}

/**
 * Perform a multiple queries using LIMIT and OFFSET  to get chunks of specified size for each query. Send output to the specified handle
 *
 * @return
*/
function performQueryChunk($endpoint, $query,$limit,$handle,$offset){
	$ret=performQuery($endpoint,$query." LIMIT $limit OFFSET $offset");
	if ($ret!==FALSE){
		echo "--------- size ".sizeof($ret);
		fwrite($handle,$ret);
		performQueryChunk($endpoint, $query,$limit,$handle,$offset+$limit);
	}
}

/**
 * Perform a sparql query, write the output on stdout
 * 
 * @return string containing a csv 
 */
function performQuery($endpoint, $query){
	$err=fopen( 'php://stderr', 'w+' );
	echo "$query\n";
	$url = $endpoint.'?query='.urlencode( $query ).'&dataType=csv';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array (
		"Accept: text/csv"
	));

	$output = curl_exec($ch);      
	$info = curl_getinfo($ch);
	if(curl_errno($ch))
	{
		fwrite($err,'Curl error: ' . curl_error($ch));
		return FALSE;
	}
	if( $output === '' )
	{
		fwrite($err,'URL returned no data');
		return FALSE;
	}
	if( $info['http_code'] != 200) 
	{
   		fwrite($err,'Bad response, '.$info['http_code'].': '.$output);
		return FALSE;
	}
	curl_close($ch);
	return $output;
}

//$db=setupConnection();
$query = readQueryFromStdIn();
//$result = performChunkedQuery('http://dati.istruzione.it/opendata/SCUANAGRAFESTAT/query', $query,50);
$result = performQuery('http://dati.istruzione.it/opendata/SCUANAGRAFESTAT/query', $query);
if ($result!==FALSE) echo $result;
?>