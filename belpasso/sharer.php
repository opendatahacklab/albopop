<?php 
/**
 * A page to share feed items of facebook.
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
 */

require ('../jCityGov/AlbojCityGovParserFactory.php');
require ('AlboBelpassoEntryParser.php');
define('ALBO_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio?p_auth=92oCQYZB&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiPaginazione&hidden_page_size=200');
define('SELECTION_FORM_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio;jsessionid=A7AAB8DEA03B8B38A523391514236713?p_auth=8GWyser9&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiFiltro');

error_reporting(E_ERROR | E_PARSE);

$factory=new AlbojCityGovParserFactory (ALBO_URL, SELECTION_FORM_URL, 
		new AlboBelpassoEntryParser() );

$year=$_GET['year'];
$number=$_GET['number'];

if (!isset($year) || !isset($number))
	die("E' necessario specificare anno e numero di gistro.");

$entryList = $factory->createByYearAndNumber($year, $number);
if (!$entryList->valid())
  die("Nessun elemento col numero anno registro $year e numero registro $number");
$entry=$entryList->current();
$date=$entry->data_inizio_pubblicazione->format(DATE_FORMAT);  
$title="Albo POP Comune di Belpasso - Avviso $year / $number del $date";
$logo="logo.png";
$description='Tipologia:'.$entry->tipo_atto.','.$entry->sottotipo_atto
.'. Oggetto:'.$entry->oggetto;

$link=$entry->url;
$css="../RSS/sharer.css";
$supporter_name="David Montenegro";
$supporter_img="montenegro.png";
$credits="Il logo di questo albo pop &egrave; stato ottenuto dallo stemma del comune di Belpasso
			riportato sulla <a href=\"http://turismo.provincia.ct.it/il-territorio/i-58-comuni/belpasso.aspx\">pagina del sito della citt&agrave; metropolitana di catania</a>,
			elaborandolo poi con il tool <a href=\"https://photofunia.com/effects/popart\">PhotoFunia</a>.";
$news="";

require("../RSS/sharer-template.php");
?>
