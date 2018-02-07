<?php
/**
 *  A school in the sense of http://dati.istruzione.it/opendata/opendata/catalogo/elements1/leaf/?datasetId=DS0400SCUANAGRAFESTAT
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
require_once('CSVParser.php');

//ANNOSCOLASTICO, AREAGEOGRAFICA, REGIONE, PROVINCIA, CODICEISTITUTORIFERIMENTO, DENOMINAZIONEISTITUTORIFERIMENTO, CODICESCUOLA, DENOMINAZIONESCUOLA, INDIRIZZOSCUOLA, CAPSCUOLA, CODICECOMUNESCUOLA, DESCRIZIONECOMUNE, DESCRIZIONECARATTERISTICASCUOLA, DESCRIZIONETIPOLOGIAGRADOISTRUZIONESCUOLA, INDICAZIONESEDEDIRETTIVO, INDICAZIONESEDEOMNICOMPRENSIVO, INDIRIZZOEMAILSCUOLA, INDIRIZZOPECSCUOLA, SITOWEBSCUOLA

class School{
	public $annoscolastico;
	public $codiceistitutoriferimento;
	public $codicescuola;
	public $denominazione;
	public $sitowebscuola;

	function __construct($row){
		$this->annoscolastico=$row[0];
		$this->codiceistitutoriferimento=$row[4];
		$this->codicescuola=$row[6];
		$this->denominazionescuola=$row[7];
		$this->sitowebscuola=$row[18];
	}

	/**
	 * Create an iterator which will parse the specified handle
	 */
	public static function parse($handle){
		return new CSVParser($handle, function($row){
				return new School($row);
			});
	}

	/**
	 *  Process the web site field in order to armonize all the fields
	 */
	private function refineWebSiteField($webSiteField){
		if (strcmp($webSiteField, 'Non Disponibile')===0)
			return null;
		$clearedbytypo=preg_replace('/\s+/', '', $webSiteField);
		$lowered=strtolower($clearedbytypo);
		$removed_schema=str_replace('http://','',$lowered);
		$removed_final_slash=str_replace('/','',$removed_schema);
		$withschema='http://'.str_replace('http://','',$removed_final_slash);
		return $withschema;
	}
}
?>