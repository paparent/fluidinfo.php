<?php
require 'FluidDB.php';
require 'config.php';

define(ME, $_SERVER['PHP_SELF']);

$fdb = new FluidDB;
$fdb->setPrefix($config['prefix']);
$fdb->setCredentials($config['username'], $config['password']);

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
	case 'removeobjecttag':
		$fdb->deleteObjectTag($_REQUEST['oid'], $_REQUEST['tag']);
		break;
	case 'tagobject':
		$value = $_REQUEST['value'];
		if (preg_match('/^-?\d+$/', $value)) {
			$value = 0 + $value;
		}
		$fdb->tagObject($_REQUEST['oid'], $_REQUEST['tag'], $value);
		break;
	default:
		break;
	}
	die;
}

page_header();

if (isset($_REQUEST['tag']) && !empty($_REQUEST['tag'])) {
	echo '<h2>Tag: ' . $_REQUEST['tag'] . '</h2>';
	doQuery('has ' . $_REQUEST['tag']);
}
else if (isset($_REQUEST['oid']) && !empty($_REQUEST['oid'])) {
	echo '<h2>Object id: ' . $_REQUEST['oid'] . '</h2>';
	showObject($_REQUEST['oid']);
}
else if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
	doQuery($_REQUEST['q']);
}
else {
	$namespace = @$_REQUEST['path'];
	showNamespace($namespace);
}

page_footer();
die;

function showNamespace($namespace)
{
	global $fdb;

	$out = $fdb->getNamespace($namespace, true, true, true);

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
}

function showObject($oid)
{
	global $fdb;
	$objectinfo = $fdb->getObject($oid, true);
	echo '<h3>Object infos</h3><p>about: ' . $objectinfo->about . '</p>';
	if ($objectinfo->tagPaths) {
		echo '<ul>';
		foreach ($objectinfo->tagPaths as $tag) {
			$tagvalue = $fdb->getObjectTag($oid, $tag);
			echo '<li>' . $tag . ': ';
			if (is_string($tagvalue)) {
				echo $tagvalue;
			}
			else {
				echo '<em>to be parsed</em>';
				print_r($tagvalue);
			}
			echo ' <a href="' . ME . '?action=tagobject&amp;oid=' . $oid . '&amp;tag=' . $tag . '" class="ajax update">update</a> <a href="' . ME . '?action=removeobjecttag&amp;oid=' . $oid . '&amp;tag=' . $tag .'" class="ajax" style="color:red">remove</a></li>';
		}
		echo '<li><a href="' . ME . '?action=tagobject&amp;oid=' . $oid . '" class="ajax newtag">add new tag</a></li>';
		echo '</ul>';
	}
}

function doQuery($q)
{
	global $fdb;
	$out = $fdb->query($q);
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

function page_header()
{
	echo '<!doctype html><html><head><title>phpFluidDBExplorer</title><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script></head><body>';
	echo '<h1>phpFluidDBExplorer</h1><div id="nav"><a href="' . ME . '">home</a></div>';
	echo '<form action="' . ME . '" method="get" id="formquery"><input type="text" name="q" id="q"> <input type="submit" value="Query!"></form>';
	echo '<div id="content">';
}

function page_footer()
{
?>
</div>
<script type="text/javascript">
$(function(){
	$('a.ajax').click(function(){
		var t = $(this);
		if (t.hasClass('newtag')) {
			var tag = prompt('Enter the tag name', '');
			var value = prompt('Enter the value', '');
			$.post(t.attr('href'), {tag:tag, value:value}, function(){location.reload();});
		}
		if (t.hasClass('update')) {
			var value = prompt('Enter the value', '');
			$.post(t.attr('href'), {value:value}, function(){location.reload();});
		}
		else {
			$.get(t.attr('href'), {}, function(){location.reload();});
		}
		return false;
	});
});
</script>
</body></html>
<?php
}

