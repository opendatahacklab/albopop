<?php 
require ('./AlboCittaDellaSaluteParserFactory.php');

$testDateStr='16/09/2019';
$testDate=DateTimeImmutable::createFromFormat('d/m/Y',$testDateStr);
$url = "https://www.cittadellasalute.to.it/albo/pubblicazione.xml";
$parser = (new AlboCittaDellaSaluteParserFactory())->createFromWebPage();
$i=0;
foreach($parser as $atto){
	$i++;	
	echo "Atto $i\n";
	echo "ndelibera $atto->ndelibera\n";
	echo "ddelibera $atto->ddelibera\n";
	echo "dpubblicazione $atto->dpubblicazione\n";
	echo "allegato $atto->allegato\n";
	echo "\n\n";
}
?>