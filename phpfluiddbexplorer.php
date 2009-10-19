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
		$ret = $fdb->deleteObjectTag($_REQUEST['oid'], $_REQUEST['tag']);
		if (!is_array($ret)) {
			echo '{"success":true}';
		}
		else {
			$json = array("success" => false);
			switch ($ret[0]) {
			case 401: $message = 'Unauthorized'; break;
			case 404: $message = 'Not found'; break;
			}
			$json['message'] = $message;
			echo json_encode($json);
		}
		break;
	case 'tagobject':
		$value = $_REQUEST['value'];
		if (is_numeric($value)) {
			$value = 0 + $value;
		}
		$ret = $fdb->tagObject($_REQUEST['oid'], $_REQUEST['tag'], $value);
		if (!is_array($ret)) {
			$json = array("success" => true);
			$json['html'] = '<li>' . $_REQUEST['tag'] . ': ' . $value . ' ' . button_tagvalue($_REQUEST['oid'], $_REQUEST['tag']) . '</li>';
			echo json_encode($json);
		}
		else {
			$json = array("success" => false);
			switch ($ret[0]) {
			case 401: $message = 'Unauthorized'; break;
			case 404: $message = 'Tag not found'; break;
			case 406: $message = 'Not acceptable'; break;
			case 400: $message = 'Bad request'; break;
			}
			$json['message'] = $message;
			echo json_encode($json);
		}
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
		echo '<ul class="tagvalues">';
		foreach ($objectinfo->tagPaths as $tag) {
			$tagvalue = $fdb->getObjectTag($oid, $tag);
			echo '<li>' . $tag . ': ';
			if (is_string($tagvalue) OR is_numeric($tagvalue)) {
				echo $tagvalue;
			}
			else {
				echo '<em>to be parsed</em>';
				print_r($tagvalue);
			}
			echo ' ', button_tagvalue($oid, $tag);
		}
		echo '<li class="last"><a href="' . ME . '?action=tagobject&amp;oid=' . $oid . '" class="add">add new tag</a></li>';
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
	echo '<!doctype html><html><head><title>phpFluidDBExplorer</title>';
	echo '<link rel="stylesheet" type="text/css" href="cssjs/jquery.jgrowl.css">';
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';
	echo '<script type="text/javascript" src="cssjs/jquery.jgrowl.js"></script>';
	echo '</head><body>';
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
	$('.tagvalues a').live('click', function(){
		var t = $(this);
		if (t.hasClass('add')) {
			var tag = prompt('Enter the tag name', '');
			var value = prompt('Enter the value', '');
			$.post(t.attr('href'), {tag:tag, value:value},
				function(data) {
					if (data.success) {
						$(data.html).insertBefore(".tagvalues .last");
					}
					else {
						$.jGrowl(data.message);
					}
				}, 'json');
		}
		else if (t.hasClass('update')) {
			var value = prompt('Enter the value', '');
			$.post(t.attr('href'), {value:value},
				function(data) {
					if (data.success) {
						t.parent().replaceWith(data.html);
					}
					else {
						$.jGrowl(data.message);
					}
				}, 'json');
		}
		else if (t.hasClass('remove')) {
			$.get(t.attr('href'), {},
				function(data) {
					if (data.success) {
						t.parent().remove();
					}
					else {
						$.jGrowl(data.message);
					}
				}, 'json');
		}
		return false;
	});
});
</script>
</body></html>
<?php
}

function button_tagvalue($oid, $tag)
{
	return '<a href="' . ME . '?action=tagobject&amp;oid=' . $oid . '&amp;tag=' . $tag . '" class="update">update</a> <a href="' . ME . '?action=removeobjecttag&amp;oid=' . $oid . '&amp;tag=' . $tag .'" class="remove" style="color:red">remove</a>';
}

