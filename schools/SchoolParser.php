<?php
/**
 *  Parse a file in the format of  http://dati.istruzione.it/opendata/opendata/catalogo/elements1/leaf/?datasetId=DS0400SCUANAGRAFESTAT
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
require_once('School.php');

class SchoolParser implements Iterator{
	private $handle;
	private $current = null;

	/**
	 * Create an iterator which will parse the specified handle
	 */
	public function __construct($handle){
		$this->handle=$handle;
		$this->readNext();
	}

	/**
	  * read the next row and set it as current
	  */
	private function readNext(){
		if (feof($this->handle))
			$this->current=null;
		else
			$this->current=new School(fgetcsv($this->handle, 1000, ","));	
	}

	public function current ( ){
		return $this->current;
	}

	public function key ( ){
		return $this->current->codice;
	}

	public function next(){
		$this->readNext();
		return $this->current;
	}

	public function rewind (){
		try{
			fseek($this->handle, 0);
		}catch(Exception $e){
			//empty, just ignore if stream does not suppor fseek
		}
		$this->readNext();
	}

	public function valid (){
		return $this->current!==null;
	}
}
?>