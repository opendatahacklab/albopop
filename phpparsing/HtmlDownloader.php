<?php
/**
 * Helper class to download Albo Pretorio HTML pages
 *  
 * Copyright 2019 Cristiano Longo
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
class HtmlDownloader{
	/**
	   * just download the page using CURL
	   *
 	   * @return the web page as string
	   */
	public function get($url){
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0');		
		$responsetxt = curl_exec($ch);
		$error=curl_error($ch);
		curl_close($ch);
		if ($error!=='')
			die("Unable to load page  $url: $error\n");
		return $responsetxt;
	}

	/**
	   * just download the page using CURL, assuming that it is an HTML page
	   *
 	   * @return a DOMDocument representing the TML page
	   */
	public function getHtml($url){
		$doc = new DOMDocument();
		$doc->loadHTML($this->get($url));
		return $doc;
	}

	/**
	  * Load an XML file by downloading it
	  *
	  * @return a DOMDomdocument, FALSE if parsing failed
	  */ 
	public function getXML($url){
	        libxml_use_internal_errors(true);
		$page=DOMDocument::load($url);
		
		if (!$page){
			foreach(libxml_get_errors() as $e)
			   echo "$e->message \n";
			return false;
		}
		return $page;	
	}
}

?>