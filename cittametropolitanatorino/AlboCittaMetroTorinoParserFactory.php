<?php
/**
 * Facotry methods to get entries of the Albo of the University of Torino
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
require ('AlboCittaMetroTorinoParser.php');

class AlboCittaMetroTorinoParserFactory implements AlboParserFactory 
{
	public static $alboPageUri = 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/';
	
	/**
	 * The landing page of the Official Albo
	 */
	function getAlboPretorioLandingPage() 
	{
		return 'http://www.provincia.torino.gov.it/cgi-bin/attiweb/';
	}
	
	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnitoParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() 
	{
		return new AlboCittaMetroTorinoParser();
	}
	
	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		die("Not implemented");
	}
}
?>