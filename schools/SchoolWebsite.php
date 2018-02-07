<?php
/**
 *  A school and its website 
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
//CODICESCUOLA, SITOWEBSCUOLA

class SchoolWebsite{
	public $codicescuola;
	public $sitowebscuola;

	function __construct($row){
		$this->codicescuola=$row[0];
		$this->sitowebscuola=$row[1];
	}

	/**
	 * Create an iterator which will parse the specified CSV
	 */
	public static function parse($handle){
		return new CSVParser($handle, function($row){
				return new SchoolWebsite($row);
			});
	}

	/**
	 * Transform the web site field in order to remove typo errors and armonize all the fields.
	 * Note that the object field $this->sitowebscuola is not changed by the execution of this method. 
	 * The refined web site address is just returned.
	 */
	public function getRefinedWebsite(){
		$webSiteField=$this->sitowebscuola;
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