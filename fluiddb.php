<?php

class FluidDB
{
	private $prefix = 'http://fluiddb.fluidinfo.com';
	private $credentials = '';

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	public function setCredentials($creds)
	{
		$this->credentials = $creds;
	}


	/* Namespaces */

	public function createNamespace($path, $namespace, $description)
	{
		$payload = array(
			'name' => $namespace,
			'description' => $description
		);

		return $this->post('/namespaces/' . $path, $payload);
		//TODO:check status
	}

	public function getNamespace($namespace, $returnDescription = false, $returnNamespaces = false, $returnTags = false)
	{
		$params = array();
		if ($returnDescription)
			$params['returnDescription'] = 'true';
		if ($returnNamespaces)
			$params['returnNamespaces'] = 'true';
		if ($returnTags)
			$params['returnTags'] = 'true';

		list($status, $infos) = $this->get('/namespaces/' . $namespace, $params);

		//TODO:check status
		return $infos;
	}

	public function updateNamespace($namespace, $description)
	{
		$payload = array(
			'description' => $description
		);

		return $this->put('/namespaces/' . $namespace, $payload);
		//TODO:check status
	}

	public function deleteNamespace($namespace)
	{
		return $this->delete('/namespaces/' . $namespace);
		//TODO:check status
	}

	/* Objects */

	public function createObject($about = null)
	{
		$payload = array(
			'about' => $about
		);

		return $this->post('/objects', $payload);
		//TODO:check status
	}

	public function query($query)
	{
		list($status, $result) = $this->get('/objects', array('query' => $query));
		//TODO:check status
		return $result;
	}

	public function getObject($id, $showAbout = false)
	{
		$params = array();
		if ($showAbout)
			$params['showAbout'] = 'true';

		list($status, $infos) = $this->get('/objects/' . $id, $params);
		//TODO:check status
		return $infos;
	}

	public function getObjectTag($id, $tag, $format = null)
	{
		$params = array();
		if ($format)
			$params['format'] = $format;

		list($status, $infos) = $this->get('/objects/' . $id . '/' . $tag, $params);
		//TODO:check status
		return $infos;
	}

	//TODO:OBJECT HEAD

	public function tagObject($id, $tag, $value, $valueEncoding = null, $valueType = null)
	{
		$payload = array(
			'value' => $value
		);

		if ($valueEncoding)
			$payload['valueEncoding'] = $valueEncoding;

		if ($valueType)
			$payload['valueType'] = $valueType;

		return $this->put('/objects/' . $id . '/' . $tag, $payload, array('format' => 'json'));
		//TODO:check status
	}

	public function deleteObjectTag($id, $tag)
	{
		return $this->delete('/objects/' . $id . '/' . $tag);
		//TODO:check status
	}

	/* Permissions */

	//TODO:DO IT
	
	/* Policies */

	//TODO:DO IT

	/* Tags */
	
	public function createTag($path, $tag, $description, $indexed = 'false')
	{
		$payload = array(
			'name' => $tag,
			'description' => $description,
			'indexed' => $indexed
		);

		return $this->post('/tags/' . $path, $payload);
		//TODO:check status
	}

	public function getTag($tag, $returnDescription = false)
	{
		$params = array();
		if ($returnDescription)
			$params['returnDescription'] = 'true';

		list($status, $infos) = $this->get('/tags/' . $tag, $params);
		//TODO:check status
		return $infos;
	}

	public function updateTag($tag, $description)
	{
		$payload = array(
			'description' => $description
		);

		return $this->put('/tags/' . $tag, $payload);
		//TODO:check status
	}

	public function deleteTag($tag)
	{
		return $this->delete('/tags/' . $tag);
		//TODO:check status
	}

	/* Users */
	
	public function getUser($username)
	{
		list($status, $infos) = $this->get('/users/' . $username);
		//TODO:check status
		return $infos;
	}

	/* Utils */

	public function get($path, $params = null)
	{
		$url = $this->prefix . $path;

		if ($params) {
			$url .= '?' . $this->array2url($params);
		}

		#echo 'URL: ', $url, "\n";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		$output = curl_exec($ch);
		$infos = curl_getinfo($ch);
		curl_close($ch);

		if ($infos['content_type'] == 'application/json') {
			$output = json_decode($output);
		}

		return array($infos['http_code'], $output);
	}

	public function post($path, $payload, $params = null)
	{
		$url = $this->prefix . $path;

		if ($params) {
			$url .= '?' . $this->array2url($params);
		}

		$value = json_encode($payload);

		#echo 'URL: ', $url, "\n";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
		$output = curl_exec($ch);
		$infos = curl_getinfo($ch);
		curl_close($ch);

		if ($infos['content_type'] == 'application/json') {
			$output = json_decode($output);
		}

		return array($infos['http_code'], $output);
	}

	public function put($path, $payload, $params = null)
	{
		$url = $this->prefix . $path;

		if ($params) {
			$url .= '?' . $this->array2url($params);
		}

		$value = json_encode($payload);

		#echo 'URL: ', $url, "\n";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
		$output = curl_exec($ch);
		$infos = curl_getinfo($ch);
		curl_close($ch);

		if ($infos['content_type'] == 'application/json') {
			$output = json_decode($output);
		}

		return array($infos['http_code'], $output);
	}

	public function delete($path)
	{
		$url = $this->prefix . $path;

		#echo 'URL: ', $url, "\n";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
		$output = curl_exec($ch);
		$infos = curl_getinfo($ch);
		curl_close($ch);

		if ($infos['content_type'] == 'application/json') {
			$output = json_decode($output);
		}

		return array($infos['http_code'], $output);
	}

	private function array2url($params)
	{
		$q = array();
		foreach ($params as $k=>$v) {
			$q[] = $k . '=' . urlencode($v);
		}
		return implode('&', $q);
	}

};

