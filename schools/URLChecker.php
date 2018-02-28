<?php
/**
 * Check if certain urls corresponds to existing and "working" (HTTP STATUS CODE 200) web resources. It provides a results cache
 * to avoid checking the same address more times.  
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
class URLChecker{
	private $checks = array();

	/**
	 * return true if the site corresponding to this url exists and it is working, false otherwise
	 */
	public function check($url){
		if (array_key_exists($url, $this->checks))
			return $this->checks[$url];
		$r=$this->attemptDownload($url);
		$this->checks[$url]=$r;
		return $r;
	}

	/**
	 * Effectively attempt to connect to the url and download the corresponding resource. Return true if all its OK
	 */
	private function attemptDownload($url){
		$handle=curl_init($url);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		$result=curl_exec($handle);
		if ($result===FALSE){
			curl_close($handle);
			return FALSE;
		}
		$code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if (strcmp($code,'200')){
			curl_close($handle);
			return FALSE;
		}
		return TRUE;
	}
}