<?php
/**
 * Trustly_Data_JSONRPCRequest class.
 *
 * @license https://opensource.org/licenses/MIT
 * @copyright Copyright (c) 2014 Trustly Group AB
 */

/* The MIT License (MIT)
 *
 * Copyright (c) 2014 Trustly Group AB
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/**
 * Class implementing the structure for data used in the signed API calls
 */
class Trustly_Data_JSONRPCRequest extends Trustly_Data_Request {

	/**
	 * Constructor.
	 *
	 * @throws Trustly_DataException If the combination of $data and
	 *		$attributes is invalid
	 *
	 * @param ?string $method Outgoing call API method
	 *
	 * @param ?array<string, mixed> $data Outputgoing call Data (if any). This can be either an
	 *		array or a simple non-complex value.
	 *
	 * @param ?array<string, mixed> $attributes Outgoing call attributes if any. If attributes
	 *		is set then $data needs to be an array.
	 */
	public function __construct($method=NULL, $data=NULL, $attributes=NULL) {
		$payload = NULL;

		if($data !== NULL || $attributes !== NULL) {
			if($data === NULL) {
				$data = array();
			}
			if($attributes !== NULL) {
				$data['Attributes'] = $attributes;
			}
			$payload = array('params' => array('Data' => $data));
		}

		parent::__construct($method, $payload);

		if($method !== NULL) {
			$this->payload['method'] = $method;
		}

		if(!isset($this->payload['params'])) {
			$this->payload['params'] = array();
		}

		$this->set('version', '1.1');

	}


	/**
	 * Set a value in the params section of the request
	 *
	 * @param string $name Name of parameter
	 *
	 * @param ?string $value Value of parameter
	 *
	 * @return ?string $value
	 */
	public function setParam($name, $value) {
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Params is not an array');
		}
		$this->payload['params'][$name] = Trustly_Data::ensureUTF8($value);
		return $value;
	}


	/**
	 * Get the value of a params parameter in the request
	 *
	 * @param string $name Name of parameter of which to obtain the value
	 *
	 * @return mixed The value
	 */
	public function getParam($name) {
		$params = $this->get('params');
		if($params !== NULL) {
			if(!is_array($params)) {
				throw new Trustly_DataException('Params is not an array');
			}
			return $params[$name];
		}
		return NULL;
	}


	/**
	 * Pop the value of a params parameter in the request. I.e. get the value
	 * and then remove the value from the params.
	 *
	 * @param string $name Name of parameter of which to obtain the value
	 *
	 * @return mixed The value
	 */
	public function popParam($name) {
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Params is not an array');
		}
		$v = NULL;
		if(isset($this->payload['params'][$name])) {
			$v = $this->payload['params'][$name];
		}
		unset($this->payload['params'][$name]);
		return $v;
	}


	/**
	 * Set the UUID value in the outgoing call.
	 *
	 * @param string $uuid The UUID
	 *
	 * @return string $uuid
	 */
	public function setUUID($uuid) {
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Params is not an array');
		}
		$this->payload['params']['UUID'] = Trustly_Data::ensureUTF8($uuid);
		return $uuid;
	}


	/**
	 * Get the UUID value from the outgoing call.
	 *
	 * @return ?string The UUID value
	 */
	public function getUUID() {
		$uuid = $this->getParam('UUID');
		if($uuid !== NULL) {
			if(!is_string($uuid)) {
				throw new Trustly_DataException('UUID is not a string');
			}
			return $uuid;
		}
		return NULL;
	}

	/**
	 * Set the Method value in the outgoing call.
	 *
	 * @param string $method The name of the API method this call is for
	 *
	 * @return void
	 */
	public function setMethod($method) {
		$this->set('method', $method);
	}


	/**
	 * Get the Method value from the outgoing call.
	 *
	 * @return ?string The Method value.
	 */
	public function getMethod() {
		$method = $this->get('method');
		if($method !== NULL) {
			if(!is_string($method)) {
				throw new Trustly_DataException('Method is not a string');
			}
			return $method;
		}
		return NULL;
	}


	/**
	 * Set a value in the params->Data part of the payload.
	 *
	 * @param string $name The name of the Data parameter to set
	 *
	 * @param ?string $value The value of the Data parameter to set
	 *
	 * @return ?string $value
	 */
	public function setData($name, $value) {
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Data is not an array');
		}
		if(!isset($this->payload['params']['Data'])) {
			$this->payload['params']['Data'] = array();
		}
		$this->payload['params']['Data'][$name] = Trustly_Data::ensureUTF8($value);
		return $value;
	}


	/**
	 * Get the value of one parameter in the params->Data section of the
	 * request. Or the entire Data section if no name is given.
	 *
	 * @param ?string $name Name of the Data param to obtain. Leave as NULL to
	 *		get the entire structure.
	 *
	 * @return mixed The value or the entire Data depending on $name
	 */
	public function getData($name=NULL) {
		$data = $this->getParam('Data');
		if($name !== NULL) {
			return $data;
		}
		if($data !== NULL) {
			if(!is_array($data)) {
				throw new Trustly_DataException('Data is not an array');
			}
			if(isset($data[$name])) {
				return $data[$name];
			}
		}
		return NULL;
	}


	/**
	 * Set a value in the params->Data->Attributes part of the payload.
	 *
	 * @param string $name The name of the Attributes parameter to set
	 *
	 * @param ?string $value The value of the Attributes parameter to set
	 *
	 * @return ?string $value
	 */
	public function setAttribute($name, $value) {
		if(!isset($this->payload['params'])) {
			$this->payload['params'] = array();
		}
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Params is not an array');
		}
		if(!isset($this->payload['params']['Data'])) {
			$this->payload['params']['Data'] = array();
		}
		if(!is_array($this->payload['params'])) {
			throw new Trustly_DataException('Params is not an array');
		}
		if(!is_array($this->payload['params']['Data'])) {
			throw new Trustly_DataException('Data is not an array');
		}

		$value = Trustly_Data::ensureUTF8($value);
		if(!isset($this->payload['params']['Data']['Attributes'])) {
			$this->payload['params']['Data']['Attributes'] = array($name => $value);
		} else {
			$this->payload['params']['Data']['Attributes'][$name] = $value;
		}
		return $value;
	}


	/**
	 * Get the value of one parameter in the params->Data->Attributes section
	 * of the request. Or the entire Attributes section if no name is given.
	 *
	 * @param string $name Name of the Attributes param to obtain. Leave as NULL to
	 *		get the entire structure.
	 *
	 * @return mixed The value or the entire Attributes depending on $name
	 */
	public function getAttribute($name) {
		$attributes = $this->getData('Attributes');
		if(!is_array($attributes) || !isset($attributes[$name])) {
			return NULL;
		}
		return $attributes[$name];
	}
}
/* vim: set noet cindent sts=4 ts=4 sw=4: */
