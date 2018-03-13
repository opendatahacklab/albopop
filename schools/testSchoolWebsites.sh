#!/bin/sh

currentDir=$(pwd);
dirName=$(date +%d%m%Y-%H:%M:%S);
dirPath=$currentDir/$dirName;
echo Created directory $dirName with path $dirPath;
mkdir $dirName;

echo downloading SCUANAGRAFESTAT20171820170901.rdf
curl http://dati.istruzione.it/opendata/opendata/catalogo/elements1/leaf/SCUANAGRAFESTAT20171820170901.rdf >$dirName/SCUANAGRAFESTAT20171820170901.rdf

echo selecting schools in Catania
cat schools-in-catania.sparql | \
java -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/SCUANAGRAFESTAT20171820170901.rdf | \
php backup2NTriples.php >$dirName/schools-in-catania.ttl

#echo Creating mapping;
#cat school-codes.sparql | \
#java -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/schools-in-catania.ttl | \
#php createMapping.php >$dirName/mapping.owl

echo automated test 
cat school-websites.sparql  |  \
java  -Xms2G -Xmx2G -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/schools-in-catania.ttl  | \
php webSiteTest.php >$dirName/websitetest.owl

echo generating report
cat test-results.sparql | \
java -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/schools-in-catania.ttl file://$dirPath/websitetest.owl | \
php report.php >$dirName/websitetestreport.html

#echo getting iris
#cat isUri.sparql | \
#java  -Xms2G -Xmx2G -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/SCUANAGRAFESTAT20171820170901.rdf file://$dirPath/mapping.owl file://$dirPath/syntaxTest.owl >$dirName/isUri.csv

#echo getting domain names
#cat isDomainName.sparql | \
#java  -Xms2G -Xmx2G -jar semanticoctopus-0.2.2-jar-with-dependencies.jar -q file://$dirPath/SCUANAGRAFESTAT20171820170901.rdf file://$dirPath/mapping.owl file://$dirPath/syntaxTest.owl  >$dirName/isDomainName.csv

