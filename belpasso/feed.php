<?php 
/**
 * This script produce the rss feed from the web page of the albo of the municipality of
 * Belpasso.
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

require ('../phpalbogenerator/AlboPopGenerator.php');
require ('../jCityGov/AlbojCityGovParserFactory.php');
require ('../jCityGov/AlbojCityGovItemConverter.php');
require ('AlboBelpassoEntryParser.php');

define('ALBO_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio?p_auth=92oCQYZB&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiPaginazione&hidden_page_size=200');
define('SELECTION_FORM_URL','http://belpasso.trasparenza-valutazione-merito.it/web/trasparenza/albo-pretorio;jsessionid=A7AAB8DEA03B8B38A523391514236713?p_auth=8GWyser9&p_p_id=jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_jcitygovalbopubblicazioni_WAR_jcitygovalbiportlet_action=eseguiFiltro');

error_reporting(E_ERROR | E_PARSE);

$generator = new AlboPopGenerator ( new AlbojCityGovParserFactory (ALBO_URL, SELECTION_FORM_URL, new AlboBelpassoEntryParser() ), 
		new AlbojCityGovItemConverter ("http://www.opendatahacklab.org/albopop/belpasso/sharer.php"));
$generator->outputFeed ("Albo POP del Comune di Belpasso", "Versione POP dell'Albo Pretorio del Comune di Belpasso", 
		"http://www.opendatahacklab.org/albopop/belpasso/albofeed.php");
?>