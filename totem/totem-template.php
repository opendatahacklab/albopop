<?php
/**
 * A template to show a random notice from a notice board.
 * Just set the following variables and import this file in your own sharer.php page:
 * 
 * - feedUrl url of the feed
 * - title of the albo
 * - logo an url of the page logo
 * - credits html code to be put in the Credits section
 * 
 * We are using https://github.com/dg/rss-php/ to parse rss feeds
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
require_once '../rss-php/Feed.php';

/**
 * Get a random entry from a feed.
 *
 * @param string $feedUrl        	
 */
function getRandomFeedEntry($feedUrl) {
	$feed = Feed::load ( $feedUrl );
	
	if (count ( $feed->entry ) > 0)
		$entries = $feed->entry;
	else if (count ( $feed->item ) > 0)
		$entries = $feed->item;
	else {
		echo "No entries";
		return false;
	}
	
	$totalEntries = count ( $entries );
	$selectedEntryNumber = rand ( 0, $totalEntries );
	return $entries [$selectedEntryNumber];
}

if (isset ( $ente )) {
	$donationTxt = "SOSTIENI ALBO POP $ente CON UNA DONAZIONE AD HACKSPACE CATANIA";
	$motivation = "Supporto per opendatahacklab e Albo POP $ente";
} else {
	$donationTxt = "SOSTIENI QUESTO ALBO POP CON UNA DONAZIONE AD HACKSPACE CATANIA";
	$motivation = "Supporto per opnedatahacklab";
}
$feed = Feed::load ( $feedUrl );
$entry = getRandomFeedEntry ( $feedUrl );
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $feed->title;?></title>
<link rel="stylesheet" type="text/css" href="totem.css" />
<meta http-equiv="refresh" content="10">
</head>
<body>
	<header class="main-header" id="top">
		<img class="logo" src="http://albopop.it/images/logo.png" alt="logo albo pop" />
		<h1>
			<?php echo $feed->title;?>
		</h1>
	</header>
	<?php
	
	if (isset ( $news ))
		echo "\t<section id=\"news\">\n\t\t<p>$news</p>\n\t</section>\n";
	?>
	<section id="avviso">
		<blockquote cite="<?php echo $entry->link?>">
			<p>
				<?php echo $entry->description; ?>
			</p>
		</blockquote>
	</section>

	<section id="credits">
		<img
				src="http://opendatahacklab.org/commons/imgs/logo_cog4_ter.png"
				alt="logo opendatahacklab" />
		<p>
			Per saperne di pi&ugrave visita il sito
			<code>albopop.it</code>.

			Questo albo pop &egrave; stato realizzato 
			nell'ambito del progetto <a href="http://opendatahacklab.org"
				target="_blank"><code>opendatahacklab</code> </a>
		</p>
		<p class="links">
			 <a href="http://opendatahacklab.org" target="_blank"> 
			</a>
		</p>
	</section>

</body>
</html>

