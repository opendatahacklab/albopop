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

//TODO remove
//define ("RSSPATH","http://dev.opendatasicilia.it/albopop/catania/");

$feeds=array(
	"http://dev.opendatasicilia.it/albopop/unict/unict2RSS.php",
	"http://dev.opendatasicilia.it/albopop/catania/alboct2RSS.php",
	"http://dev.opendatasicilia.it/albopop/cittametropolitanacatania/albofeed.php");

//choose a feed random
$selectedFeedNumber = rand ( 0, count($feeds)-0.1 );
	
$feedUrl=$feeds[$selectedFeedNumber];

require("totem-template.php");
?>
