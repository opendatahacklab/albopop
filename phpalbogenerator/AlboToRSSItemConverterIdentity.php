<?php 
/**
 * Identity function for RSSFeedItem objects. It can be userd for albo row
 * parsers which outputs RSSFeedItem instances.
 * 
 * @author Cristiano Longo
 *
 * Copyright 2017 Cristiano Longo
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
require('AlboToRSSItemConverter.php');
class AlboToRSSItemConverterIdentity implements AlboToRSSItemConverter{
	
	/**
	 * Convet an item from a specific albo into an RSS Item
	 * @return RSSFeedItem
	 */
	public function getRSSItem($alboItem){
		return $alboItem;
	}
}