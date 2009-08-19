<?php
require 'fluiddb.php';

$fldb = new FluidDB;

#$out = $fldb->get('/objects', array('query'=>'has fluiddb/users/username'));
#print_r($out);

$out = $fldb->get('/objects', array('query'=>'fluiddb/users/username="paparent"'));
print_r($out);

$out = $fldb->get('/objects/afe0ac12-c0be-494a-adcd-0d192f248c7e/fluiddb/users/name');
print_r($out);

