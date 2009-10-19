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

		list($status, $response, $header) = $this->post('/namespaces/' . $path, $payload);

		return ($status == 201) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->get('/namespaces/' . $namespace, $params);

		return ($status == 200) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->put('/namespaces/' . $namespace, $payload);

		return ($status == 204) ? $response : array($status, $header);
	}

	/**
	 * Delete namespace
	 *
	 * @param string $namespace
	 * @return object
	 */
	public function deleteNamespace($namespace)
	{
		list($status, $response, $header) = $this->delete('/namespaces/' . $namespace);

		return ($status == 204) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->post('/objects', $payload);

		return ($status == 201) ? $response : array($status, $header);
	}

	/**
	 * Send a query to FluidDB
	 *
	 * @param string $query
	 * @return object
	 */
	public function query($query)
	{
		list($status, $response, $header) = $this->get('/objects', array('query' => $query));

		return ($status == 200) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->get('/objects/' . $id, $params);

		return ($status == 200) ? $response : array($status, $header);
	}

	/**
	 * Get value of object's tag
	 *
	 * @param string $id - UUID of the object
	 * @param string $tag
	 * @return object
	 */
	public function getObjectTag($id, $tag)
	{
		list($status, $response, $header) = $this->get('/objects/' . $id . '/' . $tag);
		return ($status == 200) ? $response : array($status, $header);
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
		list($status, $response, $header) = $this->put('/objects/' . $id . '/' . $tag, $value, null, 'application/vnd.fluiddb.value+json');

		return ($status == 204) ? $response : array($status, $header);
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
		list($status, $response, $header) = $this->delete('/objects/' . $id . '/' . $tag);

		return ($status == 204) ? $response : array($status, $header);
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
	public function createTag($path, $tag, $description, $indexed = false)
	{
		$payload = array(
			'name' => $tag,
			'description' => $description,
			'indexed' => $indexed
		);

		list($status, $response, $header) = $this->post('/tags/' . $path, $payload);

		return ($status == 201) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->get('/tags/' . $tag, $params);

		return ($status == 200) ? $response : array($status, $header);
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

		list($status, $response, $header) = $this->put('/tags/' . $tag, $payload);

		return ($status == 204) ? $response : array($status, $header);
	}

	/**
	 * Delete a tag
	 *
	 * @param string $tag
	 * @return object
	 */
	public function deleteTag($tag)
	{
		list($status, $response, $header) = $this->delete('/tags/' . $tag);

		return ($status == 204) ? $response : array($status, $header);
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
		list($status, $response, $header) = $this->get('/users/' . $username);

		return ($status == 200) ? $response : array($status, $header);
	}

	/* Utils */

	/**
	 * Make a GET call
	 *
	 * @param $path
	 * @param $params
	 * @return object
	 */
	public function get($path, $params = null)
	{
		return $this->call('GET', $path, $params);
	}

	/**
	 * Make a POST call
	 *
	 * @param $path
	 * @param $payload
	 * @param $params
	 * @return object
	 */
	public function post($path, $payload, $params = null)
	{
		return $this->call('POST', $path, $params, $payload);
	}

	/**
	 * Make a PUT call
	 *
	 * @param $path
	 * @param $payload
	 * @param $params
	 * @return object
	 */
	public function put($path, $payload, $params = null, $contenttype = 'application/json')
	{
		return $this->call('PUT', $path, $params, $payload, $contenttype);
	}

	/**
	 * Make a DELETE call
	 *
	 * @param $path
	 * @return object
	 */
	public function delete($path)
	{
		return $this->call('DELETE', $path);
	}

	/**
	 * Make a request to FluidDB API
	 *
	 * @param $method
	 * @param $path
	 * @param $params
	 * @param $payload
	 * @return object
	 */
	public function call($method, $path, $params = null, $payload = null, $contenttype = 'application/json')
	{
		$url = $this->prefix . $path;

		if ($params) {
			$url .= '?' . $this->array2url($params);
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		
		if ($this->credentials) {
			curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
		}

		$headers = array();

		if ($method != 'GET') {
			if ($payload) {
				$headers[] = 'Content-Type: ' . $contenttype;
				$payload = json_encode($payload);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			}
			if ($method == 'POST') {
				curl_setopt($ch, CURLOPT_POST, true);
			}
			else {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			}
		}


		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$output = substr($response, $header_size);
		$infos = curl_getinfo($ch);
		curl_close($ch);

		if ($infos['content_type'] == 'application/json'
			OR $infos['content_type'] == 'application/vnd.fluiddb.value+json') {
			$output = json_decode($output);
		}

		return array($infos['http_code'], $output, $header);
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
