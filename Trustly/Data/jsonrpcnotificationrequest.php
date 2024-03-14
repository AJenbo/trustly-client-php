<?php
/**
 * Trustly_Data_JSONRPCNotificationRequest class.
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
 * Class implementing the interface to the data in a notification request from
 * the Trustly API.
 */
class Trustly_Data_JSONRPCNotificationRequest extends Trustly_Data {

	/**
	 * The RAW incoming notification body
	 * @var string
	 */
	public $notification_body;


	/**
	 * Constructor.
	 *
	 * @throws Trustly_DataException When the incoming data is invalid.
	 *
	 * @throws Trustly_JSONRPCVersionException When the incoming notification
	 *		request seems to be valid but is for a JSON RPC version we do not
	 *		support.
	 *
	 * @param string $notification_body RAW incoming notification body
	 */
	public function __construct($notification_body) {
		$this->notification_body = $notification_body;

		if(empty($notification_body)) {
			throw new Trustly_DataException('Empty notification body');
		}

		$payload = json_decode($notification_body, TRUE);

		if($payload === NULL) {
			$error = '';
			if(function_exists('json_last_error_msg')) {
				$error = ': ' . json_last_error_msg();
			}
			throw new Trustly_DataException('Failed to parse JSON' . $error);
		}

		if (is_array($payload)) {
			$this->payload = $payload;
		}

		if($this->getVersion() != '1.1') {
			throw new Trustly_JSONRPCVersionException('JSON RPC Version '. $this->getVersion() .'is not supported');
		}
	}


	/**
	 * Get value from or the entire params payload.
	 *
	 * @param ?string $name Name of the params parameter to obtain. Leave blank
	 *		to get the entire payload
	 *
	 * @return mixed The value for the params parameter or the entire payload
	 *		depending on $name
	 */
	public function getParams($name=NULL) {
		$params = $this->get('params');
		if($name !== NULL) {
			return $params;
		}
		if($params !== NULL) {
			if(!is_array($params)) {
				throw new Trustly_DataException('Params is not an array');
			}
			if(isset($params[$name])) {
				return $params[$name];
			}
		}
		return NULL;
	}


	/**
	 * Get the value of a parameter in the params->data section of the
	 * notification response.
	 *
	 * @param ?string $name The name of the parameter. Leave as NULL to get the
	 *		entire payload.
	 *
	 * @return mixed The value sought after or the entire payload depending on
	 *		$name.
	 */
	public function getData($name=NULL) {
		$data = $this->getParams('data');
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
	 * Get the UUID from the request.
	 *
	 * @return ?string The UUID value
	 */
	public function getUUID() {
		$uuid = $this->getParams('uuid');
		if($uuid !== null) {
			if(!is_string($uuid)) {
				throw new Trustly_DataException('UUID is not a string');
			}
			return $uuid;
		}
		return NULL;
	}


	/**
	 * Get the Method from the request.
	 *
	 * @return ?string The Method value.
	 */
	public function getMethod() {
		$method = $this->get('method');
		if($method !== null) {
			if(!is_string($method)) {
				throw new Trustly_DataException('Method is not a string');
			}
			return $method;
		}
		return NULL;
	}


	/**
	 * Get the Signature from the request.
	 *
	 * @return ?string The Signature value.
	 */
	public function getSignature() {
		$signature = $this->getParams('signature');
		if($signature !== null) {
			if(!is_string($signature)) {
				throw new Trustly_DataException('Signature is not a string');
			}
			return $signature;
		}
		return NULL;
	}


	/**
	 * Get the JSON RPC version from the request.
	 *
	 * @return ?string The Version.
	 */
	public function getVersion() {
		$version = $this->get('version');
		if($version !== null) {
			if(!is_string($version)) {
				throw new Trustly_DataException('Version is not a string');
			}
			return $version;
		}
		return NULL;
	}
}
/* vim: set noet cindent sts=4 ts=4 sw=4: */
