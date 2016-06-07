<?php

namespace Namecheap\Command
{
    use Namecheap\Command\Domains\Ns\GetInfo\Exception;

    abstract class ACommand implements ICommand
	{
		/**
		 * @var Namecheap\Config
		 */
		protected $_config;

		/**
		 * @var string
		 */
		protected $_url;

		/**
		 * @var string
		 */
		protected $_result;

		/**
		 * @var SimpleXMLObject
		 */
		protected $_xml;

		/**
		 * @var Namecheap\Response
		 */
		protected $_response;

		/**
		 * @var string
		 */
		protected $_status;

		/**
		 * @var array
		 */
		protected $_params = array(
			'ApiUser'	=> null,
			'ApiKey'	=> null,
			'UserName'	=> null,
			'ClientIP'	=> null,
			'Command'	=> null,
		);

		/**
		 * Returns the api command to call
		 * @return string
		 */
		protected function _beforeDispatch() {}
		protected function _preConnect() {}
		protected function _postConnect() {}
		protected function _postDispatch() {}

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->setParams($this->params());
		}

		/**
		 * Store reference to config object
		 * @param Namecheap\Config $config
		 * @return ACommand
		 */
		public function config(\Namecheap\Config $config)
		{
			$this->_config = $config;
			return $this;
		}

		/**
		 * Return status from api call
		 * @return string
		 */
		public function status()
		{
			return $this->_status;
		}

		/**
		 * Set a url parameter option
		 * @param string $key
		 * @param mixed $value
		 * @return ACommand
		 */
		public function setParam($key, $value)
		{
			$this->_params[$key] = $value;
			return $this;
		}

		/**
		 * Get a url parameter option
		 * @param string $key
		 * @return mixed
		 */
		public function getParam($key)
		{
			if (!array_key_exists($key, $this->_params))
			{
				throw new ACommand\Exception('Property does not exist ' . $key);
			}
			return $this->_params[$key];
		}

		/**
		 * Return array of parameter options
		 * @return array
		 */
		public function getParams()
		{
			return $this->_params;
		}

		/**
		 * Get parameter options with values as a urlencoded string
		 * @return string
		 */
		public function getEncodedParams()
		{
			$params = array();
			foreach ($this->_params as $key => $value)
			{
				$params[] = urlencode($key) . '=' . urlencode($value);
			}
			return implode('&', $params);
		}

		/**
		 * Set multiple url parameter options at once
		 * @param array
		 * @return ACommand
		 */
		public function setParams($options = array())
		{
			foreach ($options as $key => $value)
			{
				$key = (string) $key;
				$this->_params[$key] = $value;
			}
			return $this;
		}

		/**
		 * Prepare parameters for api call. This is a separate method so commands can extend and modify this
		 */
		protected function _prepareParameters()
		{
			// Set default parameter options via config object settings
			$this->setParams(array(
				'ApiUser'	=> $this->_config->apiUser,
				'ApiKey'	=> $this->_config->apiKey,
				'UserName'	=> $this->_config->username,
				'ClientIP'	=> $this->_config->clientIp,
				'Command'	=> $this->command(),
			));
		}

		/**
		 * Execute a call to the Namecheap API
		 * @return bool success or failure
		 */
		public function dispatch()
		{
			$this->_beforeDispatch();

			// Verify config options
			$this->_config->check();

			$this->_prepareParameters();

			// Set the API url
			$this->_url = $this->_config->url . '?' . $this->getEncodedParams();

			// Perform any pre-connect code if the extending command class has defined it
			$this->_preConnect();

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, $this->_url);

			$this->_result = curl_exec($ch);

			// Perform any post-connect code if the extending command class has defined it
			$this->_postConnect();

			if (false === $this->_result)
			{
				$this->errorMessage = 'Communication error with Namecheap.';
				throw new Exception(curl_error($ch), curl_errno($ch));
				return false;
			}

			// Parse xml result
			$this->_xml = new \SimpleXMLElement($this->_result);

			// Save status
			$this->_status = strtolower($this->_xml['Status']);

			if ($this->_status == 'error')
			{
				$this->errorMessage = (string) $this->_xml->Errors->Error;
                		throw new Exception((string) $this->_xml->Errors->Error);
				return false;
			} 
			else if ($this->_status == 'ok') 
			{
				$this->_response = $this->_xml->CommandResponse;
			}

			$this->_postDispatch();

			curl_close($ch);

			return true;
		}

		public function getResult()
		{
			return $this->_result;
		}

		public function getXml()
		{
			return $this->_xml;
		}

		public function getResponse()
		{
			return $this->_response;
		}

		public function getStatus()
		{
			return $this->_status;
		}
	}
}
