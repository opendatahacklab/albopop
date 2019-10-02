<?php
/**
 * Factory methods to get entries of the Albo of the Municipality of Belpasso
 * 
 * Copyright 2016 Cristiano Longo
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
require ('../phpparsing/AlboParserFactory.php');
require ('AlbojCityGovParser.php');

class AlbojCityGovParserFactory implements AlboParserFactory {
	private $alboPageUri;
	private $selectionFormUri;
	private $entryParser;
	
	/**
	 * 
	 * @param unknown $alboPageUri TODO write me
	 * @param unknown $selectionFormUri TODO write me
	 */
	public function __construct($alboPageUri, $selectionFormUri, $entryParser){
		$this->alboPageUri=$alboPageUri;
		$this->selectionFormUri=$selectionFormUri;
		$this->entryParser=$entryParser;
	}
	
	/**
	 * The landing page of the Official Albo
	 */
	public function getAlboPretorioLandingPage() {
		return $this->alboPageUri;
	}

	/**
	   * just download the page using CURL.
	   */
	private function downloadPage($url){
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0');
		
		$responsetxt = curl_exec($ch);
		$error=curl_error($ch);
		curl_close($ch);
		if ($error!==''){
			die("Unable to load page  $url: $error\n");
		}
		return $responsetxt;
	}
	
	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnictParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() {
		$pageStr=$this->downloadPage($this->alboPageUri);
		$page = new DOMDocument();
		$page->loadHTML($pageStr);
		return new AlbojCityGovParser($page, $this->entryParser);
	}
	
	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		$pageStr=$this->downloadPage($this->selectionFormUri."&numeroRegistrazioneDa=$number&annoRegistrazioneDa=$year");
		$page = new DOMDocument();
		$page->loadHTML($pageStr);
		return new AlbojCityGovParser($page, $this->entryParser);
	}
}
?>