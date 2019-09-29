<?php 
/**
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
require ('../phpparsing/AlboParserFactory.php');
require ('../phpparsing/HtmlDownloader.php');
require ('AlboCittaDellaSaluteParser.php');

class AlboCittaDellaSaluteParserFactory implements AlboParserFactory {
	public static $alboPageUri = 'https://www.cittadellasalute.to.it/albo/pubblicazione.xml';

	/**
	 * The landing page of the Official Albo
	 */
	public function getAlboPretorioLandingPage() {
		return AlboCittaDellaSaluteParserFactory::$alboPageUri;
	}

	/**
	 * Read all the entries in the albo web page.
	 *
	 * @return the AlboUnictParser instance obtained by parsing the specified page.
	 */
	public function createFromWebPage() {
		$page = new DOMDocument();
		$page->load('https://www.cittadellasalute.to.it/albo/pubblicazione.xml');
		//$page=(new HTMLDownloader())->getXML(AlboCittaDellaSaluteParserFactory::$alboPageUri);
		return new AlboCittaDellaSaluteParser($page);
	}

	/**
	 * Create a parser with the solely entry with the specified year and number, if
	 * exists, empty otherwise.
	 */
	public function createByYearAndNumber($year, $number) {
		$page = new DOMDocument();
		$page->load(SELECTION_FORM_URL."&numeroRegistrazioneDa=$number&annoRegistrazioneDa=$year");
		return new AlboBelpassoParser($page);
	}
}
?>