<?php
/**
 * Trustly_Data_JSONRPCNotificationResponse class.
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
 * Class implementing the interface to data in the response to a notification
 * from the Trustly API.
 */
class Trustly_Data_JSONRPCNotificationResponse extends Trustly_Data {

	/**
	 * Constructor.
	 *
	 * @param Trustly_Data_JSONRPCNotificationRequest $request Incoming
	 *		notification request to which we are responding
	 *
	 * @param ?boolean $success Set to true to indicate that the notification
	 *		was successfully processed.
	 */
	public function __construct($request, $success=NULL) {
		$uuid = $request->getUUID();
		$method = $request->getMethod();

		if($uuid !== NULL) {
			$this->setResult('uuid', $uuid);
		}
		if($method !== NULL) {
			$this->setResult('method', $method);
		}

		if($success !== NULL) {
			$this->setSuccess($success);
		}

		$this->set('version', '1.1');
	}


	/**
	 * Set the success status in the response.
	 *
	 * @param ?boolean $success Set to true to indicate that the notification
	 *		was successfully processed.
	 *
	 * @return ?boolean $success
	 */
	public function setSuccess($success=NULL) {
		$status = 'OK';

		if($success !== NULL && $success !== TRUE) {
			$status = 'FAILED';
		}
		$this->setData('status', $status);
		return $success;
	}


	/**
	 * Set the signature in the response.
	 *
	 * @param string $signature Signature of the outgoing data.
	 *
	 * @return void
	 */
	public function setSignature($signature) {
		$this->setResult('signature', $signature);
	}


	/**
	 * Set a parameter in the result section of the notification response.
	 *
	 * @param string $name The name of the parameter to set
	 *
	 * @param mixed $value The value of the parameter
	 *
	 * @return mixed $value
	 */
	public function setResult($name, $value) {
		if(!isset($this->payload['result'])) {
			$this->payload['result'] = array();
		}
		if(!is_array($this->payload['result'])) {
			throw new Trustly_DataException('Result is not an array');
		}
		$this->payload['result'][$name] = $value;
		return $value;
	}


	/**
	 * Get the value of a parameter in the result section of the notification
	 * response.
	 *
	 * @param ?string $name The name of the parameter. Leave as NULL to get the
	 *		entire payload.
	 *
	 * @return mixed The value sought after or the entire payload depending on
	 *		$name.
	 */
	public function getResult($name=NULL) {
		$result = $this->get('result');
		if($name !== NULL) {
			return $result;
		}
		if($result !== NULL) {
			if(!is_array($result)) {
				throw new Trustly_DataException('Result is not an array');
			}
			if(isset($result[$name])) {
				return $result[$name];
			}
		}
		return NULL;
	}


	/**
	 * Get the value of a parameter in the result->data section of the
	 * notification response.
	 *
	 * @param ?string $name The name of the parameter. Leave as NULL to get the
	 *		entire payload.
	 *
	 * @return mixed The value sought after or the entire payload depending on
	 *		$name.
	 */
	public function getData($name=NULL) {
		$data = $this->getResult('data');
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
	 * Set a parameter in the result->data section of the notification
	 * response.
	 *
	 * @param string $name The name of the parameter to set
	 *
	 * @param mixed $value The value of the parameter
	 *
	 * @return mixed $value
	 */
	public function setData($name, $value) {
		if(!isset($this->payload['result'])) {
			$this->payload['result'] = array();
		}
		if(!is_array($this->payload['result'])) {
			throw new Trustly_DataException('Result is not an array');
		}
		if(!isset($this->payload['result']['data'])) {
			$this->payload['result']['data'] = array($name => $value);
		} else {
			$this->payload['result']['data'][$name] = $value;
		}
		return $value;
	}


	/**
	 * Get the Method value from the response.
	 *
	 * @return ?string The Method value.
	 */
	public function getMethod() {
		$method = $this->getResult('method');
		if($method !== NULL) {
			if(!is_string($method)) {
				throw new Trustly_DataException('Method is not a string');
			}
			return $method;
		}
		return NULL;
	}


	/**
	 * Get the UUID value from the response.
	 *
	 * @return ?string The UUID value
	 */
	public function getUUID() {
		$uuid = $this->getResult('uuid');
		if($uuid !== NULL) {
			if(!is_string($uuid)) {
				throw new Trustly_DataException('UUID is not a string');
			}
			return $uuid;
		}
		return NULL;
	}
}
/* vim: set noet cindent sts=4 ts=4 sw=4: */
