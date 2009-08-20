<?php
require 'fluiddb.php';
require 'config.php';

$fldb = new FluidDB;
$fldb->setCredentials($username.':'.$password);

/*
$userinfos = $fldb->getUser('paparent');
echo 'User ID: ', $userinfos->id, "\n";

$username = $fldb->getObjecttag($userinfos->id, 'fluiddb/users/username');
echo 'Username: ', $username, "\n";

echo "Infos of fluiddb namespace\n";
$out = $fldb->getNamespace('fluiddb', true, true, true);
print_r($out);

echo "Infos of fluiddb/users/name tag\n";
$out = $fldb->getTag('fluiddb/users/name', true);
print_r($out);

/*
$out = $fldb->query('has fluiddb/users/username');
print_r($out);

$out = $fldb->query('fluiddb/users/username="paparent"');
print_r($out);

$out = $fldb->get('/objects/afe0ac12-c0be-494a-adcd-0d192f248c7e/fluiddb/users/name');
print_r($out);
 */

/*echo "Creating 1st object!\n";
$out = $fldb->createObject('First object created by paparent');
print_r($out);
*/

/*$out = $fldb->createNamespace('paparent', 'test', 'Test namespace of paparent');
print_r($out);
*/

/*$out = $fldb->createTag('paparent/test', 'first', 'First tag of paparent');
print_r($out);
*/

/*$out = $fldb->deleteNamespace('paparent/test2');
print_r($out);
*/

/*$out = $fldb->deleteTag('paparent/test/first');
print_r($out);
*/

/*$out = $fldb->updateNamespace('paparent/test', 'Testing namespace of paparent');
print_r($out);
*/

$out = $fldb->getNamespace('paparent/test', true, true, true);
print_r($out);




