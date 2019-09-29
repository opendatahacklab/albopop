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

require ('../phpalbogenerator/AlboToRSSItemConverter.php');
require ('../RSS/RSSFeedItem.php');

class AlboCittaDellaSaluteItemConverter implements AlboToRSSItemConverter {
	/**
	 *
	 * @param $alboItem
	 */
	function getRSSItem($alboItem) {
		$rssItem = new RSSFeedItem ();
		$rssItem->title=$alboItem->oggetto;
		$rssItem->description="nr.atto:" . $alboItem->ndelibera . " data atto:" . $alboItem->ddelibera . " oggetto:" . $alboItem->oggetto;
		$rssItem->pubDate=DateTimeImmutable::createFromFormat('d/m/Y',$alboItem->dpubblicazione);
		$d=$rssItem->pubDate->format('d/m/Y');
		$rssItem->link=$alboItem->allegato;
		$rssItem->guid=$rssItem->link;
		return $rssItem;
	}
}


?>