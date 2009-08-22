<?php
require 'fluiddb.php';
require 'config.php';

define(ME, $_SERVER['PHP_SELF']);

$fldb = new FluidDB;
$fldb->setPrefix('sandbox.fluidinfo.com');
$fldb->setCredentials($username . ':' . $password);

echo '<h1>phpFluidDBExplorer</h1><p><a href="' . ME . '">home</a></p>';

echo '<form action="' . ME . '" method="get"><input type="text" name="q"> <input type="submit" value="Query!"></form>';

if (isset($_REQUEST['tag']) && !empty($_REQUEST['tag'])) {
	echo '<h2>Tag: ' . $_REQUEST['tag'] . '</h2>';
	doQuery('has ' . $_REQUEST['tag']);
	die;
}

if (isset($_REQUEST['oid']) && !empty($_REQUEST['oid'])) {
	echo '<h2>Object id: ' . $_REQUEST['oid'] . '</h2>';
	showObject($_REQUEST['oid']);
	die;
}

if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
	doQuery($_REQUEST['q']);
	die;
}

$namespace = @$_REQUEST['path'];

$out = $fldb->getNamespace($namespace, true, true, true);

echo '<h2>Namespace: ' . $out->description . '</h2><p><em>Object id: ' . $out->id . '</em></p>';

showObject($out->id);

if ($out->namespaceNames) {
	echo '<h3>Namespaces</h3>';
	echo '<ul>';
	foreach ($out->namespaceNames as $ns) {
		echo '<li><a href="' . ME  .'?path=' . (!empty($namespace)?$namespace.'/':'') . $ns . '">' . $ns . '/</a></li>';
	}
	echo '</ul>';
}

if ($out->tagNames) {
	echo '<h3>Tags</h3>';
	echo '<ul>';
	foreach ($out->tagNames as $tag) {
		echo '<li><a href="' . ME . '?tag=' . $namespace . '/' . $tag . '">' . $tag . '</a></li>';
	}
	echo '</ul>';
}

function showObject($oid) {
	global $fldb;
	$objectinfo = $fldb->getObject($oid, true);
	echo '<h3>Object infos</h3><p>about: ' . $objectinfo->about . '</p>';
	if ($objectinfo->tagPaths) {
		echo '<ul>';
		foreach ($objectinfo->tagPaths as $tag) {
			$tagvalue = $fldb->getObjectTag($oid, $tag);
			echo '<li>' . $tag . ': ' . $tagvalue . '</li>';
		}
		echo '</ul>';
	}
}

function doQuery($q) {
	global $fldb;
	$out = $fldb->query($q);
	if ($out->ids) {
		echo '<h2>' . count($out->ids) . ' result(s) for: ' . $q . '</h2>';
		echo '<ul>';
		foreach ($out->ids as $oid) {
			echo '<li><a href="' . ME . '?oid=' . $oid . '">' . $oid . '</a></li>';
		}
		echo '</ul>';
	}
	else {
		echo '<p><em>No results</em></p>';
	}
}
