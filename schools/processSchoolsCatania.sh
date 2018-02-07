#!/bin/sh

echo 'extracting schools of Catania'
grep ',C351,' >schools-catania.csv
echo 'created schools-catania.csv - now generating the ontology of schools'
php generateOntology.php <schools-catania.csv >nowebsites.owl
echo 'created nowebsites.owl - now extracting just web sites'
php extractSites.php <schools-catania.csv >websites.csv
echo 'created wsbsites.csv - now performing some refinements'
php refineWebsites.php <websites.csv >websites-refined.csv 
echo 'generated websites-refined.csv'
