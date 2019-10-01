#!/bin/sh

currentDir=$(pwd);
dirName=$(date +%d%m%Y-%H:%M:%S);
dirPath=$currentDir/$dirName;
echo Created directory $dirName with path $dirPath;
mkdir $dirName;

echo getting schools in catania
php schoolsInCatania.php | php getSchoolsByCode.php >$dirName/schools-in-catania.ttl

echo skolemizing
cat $dirName/schools-in-catania.ttl | php skolemizeSubjects.php http://www.miur.it/ns/miur/ >$dirName/schools-in-catania-sk.ttl 

echo automated test 
cat school-websites.sparql  |  \
java  -Xms2G -Xmx2G -XX:-UseGCOverheadLimit -jar octopusquery-1.0.0-jar-with-dependencies.jar file://$dirPath/schools-in-catania-sk.ttl  | \
php webSiteTest.php >$dirName/websitetest.owl

echo generating report
cp report.css $dirName/report.css
cat test-results-simple.sparql | \
java  -Xms2G -Xmx2G -XX:-UseGCOverheadLimit -jar octopusquery-1.0.0-jar-with-dependencies.jar file://$dirPath/schools-in-catania-sk.ttl file://$dirPath/websitetest.owl | \
php report.php >$dirName/websitetestreport.html

echo generate isCorrect reporting csv
cat school-working-websites.sparql | \
java  -Xms3G -Xmx3G -XX:-UseGCOverheadLimit -jar octopusquery-1.0.0-jar-with-dependencies.jar file://$dirPath/schools-in-catania-sk.ttl file://$dirPath/websitetest.owl | \
php isCorrectEmptyCSVReport.php >$dirName/iscorrect.csv

#echo getting iris
#cat isUri.sparql | \
#java  -Xms2G -Xmx2G -jar octopusquery-1.0.0-jar-with-dependencies.jar file://$dirPath/SCUANAGRAFESTAT20171820170901.rdf file://$dirPath/mapping.owl file://$dirPath/syntaxTest.owl >$dirName/isUri.csv

#echo getting domain names
#cat isDomainName.sparql | \
#java  -Xms2G -Xmx2G -jar octopusquery-1.0.0-jar-with-dependencies.jar file://$dirPath/SCUANAGRAFESTAT20171820170901.rdf file://$dirPath/mapping.owl file://$dirPath/syntaxTest.owl  >$dirName/isDomainName.csv

