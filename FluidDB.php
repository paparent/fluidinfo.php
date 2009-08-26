<?php

/**
 * phpFluidDB
 *
 * PHP library to communicate with FluidDB API.
 *
 * @package phpFluidDB
 * @author PA Parent <paparent@gmail.com>
 */

/**
 * Main class for FluidDB handling
 *
 * @package phpFluidDB
 * @author PA Parent <paparent@gmail.com>
 */
class FluidDB
{
	/**
	 * Default prefix
	 *
	 * @var string
	 */
	private $prefix = 'http://fluiddb.fluidinfo.com';

	/**
	 * User credentials
	 *
	 * @var string
	 */
	private $credentials = '';

	/**
	 * Change the prefix
	 *
	 * @param string $prefix URL of the API
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	/**
	 * Set user credentials
	 *
	 * @param string $username
	 * @param string $password
	 * @return void
	 */
	public function setCredentials($username, $password)
	{
		$this->credentials = $username . ':' . $password;
	}

	/**
	 * Set anonymous credentials
	 *
	 * @return void
	 */
	public function setAnonymous()
	{
		$this->credentials = '';
	}

	/* Namespaces */

	/**
	 * Create a namespace
	 *
	 * @param string $path
	 * @param string $namespace
	 * @param string $description
	 */
	public function createNamespace($path, $namespace, $description)
	{
		$payload = array(
			'name' => $namespace,
			'description' => $description
		);

		return $this->post('/namespaces/' . $path, $payload);
		//TODO:check status
	}

	/**
	 * Get information of namespace
	 *
	 * @param string $namespace
	 * @param string $returnDescription
	 * @param string $returnNamespaces
	 * @param string $returnTags
	 * @return object
	 */
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

	/**
	 * Update namespace's description
	 *
	 * @param string $namespace
	 * @param string $description
	 * @return object
	 */
	public function updateNamespace($namespace, $description)
	{
		$payload = array(
			'description' => $description
		);

		return $this->put('/namespaces/' . $namespace, $payload);
		//TODO:check status
	}

	/**
	 * Delete namespace
	 *
	 * @param string $namespace
	 * @return object
	 */
	public function deleteNamespace($namespace)
	{
		return $this->delete('/namespaces/' . $namespace);
		//TODO:check status
	}

	/* Objects */

	/**
	 * Create an object
	 *
	 * @param string $about
	 * @return object
	 */
	public function createObject($about = null)
	{
		$payload = array(
			'about' => $about
		);

		return $this->post('/objects', $payload);
		//TODO:check status
	}

	/**
	 * Send a query to FluidDB
	 *
	 * @param string $query
	 * @return object
	 */
	public function query($query)
	{
		list($status, $result) = $this->get('/objects', array('query' => $query));
		//TODO:check status
		return $result;
	}

	/**
	 * Get object
	 *
	 * @param string $id - UUID of the object
	 * @param string $showAbout
	 * @return object
	 */
	public function getObject($id, $showAbout = false)
	{
		$params = array();
		if ($showAbout)
			$params['showAbout'] = 'true';

		list($status, $infos) = $this->get('/objects/' . $id, $params);
		//TODO:check status
		return $infos;
	}

	/**
	 * Get value of object's tag
	 *
	 * @param string $id - UUID of the object
	 * @param string $tag
	 * @param string $format
	 * @return object
	 */
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

	/**
	 * Tag an object
	 *
	 * @param string $id - UUID of the object
	 * @param string $tag
	 * @param string $value
	 * @param string $valueEncoding
	 * @param string $valueType
	 * @return object
	 */
	public function tagObject($id, $tag, $value = null, $valueEncoding = null, $valueType = null)
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

	/**
	 * Remove tag of an object
	 *
	 * @param string $id - UUID of the object
	 * @param string $tag
	 * @return object
	 */
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

	/**
	 * Create/declare a tag
	 *
	 * @param string $path
	 * @param string $tag
	 * @param string $description
	 * @param bool $indexed
	 * @return object
	 */
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

	/**
	 * Get information about a tag
	 *
	 * @param string $tag
	 * @param string $returnDescription
	 * @return object
	 */
	public function getTag($tag, $returnDescription = false)
	{
		$params = array();
		if ($returnDescription)
			$params['returnDescription'] = 'true';

		list($status, $infos) = $this->get('/tags/' . $tag, $params);
		//TODO:check status
		return $infos;
	}

	/**
	 * Update description of a tag
	 *
	 * @param string $tag
	 * @param string $description
	 * @return object
	 */
	public function updateTag($tag, $description)
	{
		$payload = array(
			'description' => $description
		);

		return $this->put('/tags/' . $tag, $payload);
		//TODO:check status
	}

	/**
	 * Delete a tag
	 *
	 * @param string $tag
	 * @return object
	 */
	public function deleteTag($tag)
	{
		return $this->delete('/tags/' . $tag);
		//TODO:check status
	}

	/* Users */

	/**
	 * Get user's information
	 *
	 * @param string $username
	 * @return object
	 */
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

	/**
	 * Utility function to convert array into URI string
	 *
	 * @param array $params
	 * @return string
	 */
	private function array2url($params)
	{
		$q = array();
		foreach ($params as $k=>$v) {
			$q[] = $k . '=' . urlencode($v);
		}
		return implode('&', $q);
	}

};

