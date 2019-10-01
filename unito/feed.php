<?php
/**
 * Generate the feed of Albo POP of the University of Torino
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
require ('AlboUnitoParserFactory.php');
require ('AlboUnitoItemForSharerConverter.php');

error_reporting(E_ERROR | E_PARSE);

$generator = new AlboPopGenerator ( new AlboUnitoParserFactory (), new AlboUnitoItemForSharerConverter ( "https://www.opendatahacklab.org/albopop/unito/sharer.php" ) );
$generator->outputFeed ( "Albo POP del Universita` di Torino", "Versione POP dell'Albo Ufficiale dell'Universita` di Torino", "https://www.opendatahacklab.org/albopop/unito/feed.php" );
?>