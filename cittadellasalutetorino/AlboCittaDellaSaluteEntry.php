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


define('DATE_FORMAT','d/m/Y');
define('CONTENT_SEPARATOR','/');

class AlboCittaDellaSaluteEntry{
	
	var $tipologia;
	var $ndelibera;
	var $ddelibera;
	var $oggetto;
	var $dpubblicazione;
	var $d_pubblicazionea1;
	var $ufficio;
	var $allegato; //just the first attachment
	
	/**
	  *
	  * @param $add DOMElement
	  */
	public function __construct($atto) {
		$this->tipologia=$this->getSubElementContent($atto,'tipologia');
		$this->ndelibera=$this->getSubElementContent($atto,'ndelibera');
		$this->ddelibera=$this->getSubElementContent($atto,'ddelibera');
		$this->oggetto=$this->getSubElementContent($atto,'oggetto');
		$this->dpubblicazione=$this->getSubElementContent($atto,'dpubblicazione');
		$this->d_pubblicazionea1=$this->getSubElementContent($atto,'d_pubblicazionea1');
		$this->ufficio=$this->getSubElementContent($atto,'ufficio');

		$allegati=$atto->getElementsByTagName('allegati')->item(0);
		$allegato=$allegati->getElementsByTagName('allegato')->item(0);
		$allegatoId=$allegato->getAttribute('id');
		$this->allegato='https://www.cittadellasalute.to.it/albo/'.$allegatoId;
	}	
	
	/**
	  *  Return the text content of the (first) child of the specified type, null if no such subelement exists
	  */	
	private function getSubElementContent($element, $subElementType){
		$children=$element->getElementsByTagName($subElementType);
		if ($children->length==0) 
			return NULL;
		return $children->item(0)->textContent;
	}	
}

?>