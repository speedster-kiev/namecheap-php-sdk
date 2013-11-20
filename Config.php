<?php

namespace Namecheap\Config
{
	class Exception extends \Exception {};
}

namespace Namecheap
{
	class Config
	{
		const ENV_SANDBOX = 'https://api.sandbox.namecheap.com/xml.response';
		const ENV_PRODUCTION = 'https://api.namecheap.com/xml.response';

		protected $_data = array(
			'apiUser'	=> null,
			'apiKey'	=> null,
			'username'	=> null,
			'clientIp'	=> null,
			'url'	=> self::ENV_PRODUCTION,
			'sandbox'	=> false,
		);

		/**
		 * Class constructor
		 */
		public function __construct($config = array())
		{
			foreach ($config as $key => $value)
			{
				if (array_key_exists($key, $this->_data))
				{
					$method = 'set' . ucfirst($key);
					$this->$method($value);
				}
			}
		}

		/**
		 * Magic get method. Calls getKey if key is a valid property
		 * @param string $key
		 * @return mixed
		 */
		public function __get($key)
		{
			$key = $this->_checkKey($key);

			$method = 'get' . ucfirst($key);
			return $this->$method();
		}

		/**
		 * Magic set method. Calls setKey(value) if key is a valid property
		 * @param string $key
		 * @param mixed $value
		 * @return mixed
		 */
		public function __set($key, $value)
		{
			$key = $this->_checkKey($key);

			$method = 'set' . ucfirst($key);
			$this->$method($value);
		}

		/**
		 * Magic isset(). Returns true/false for if _data[key] is set
		 * @param string $key
		 * @return bool
		 */
		public function __isset($key)
		{
			return isset($this->_data[$key]);
		}

		/**
		 * Verify key is valid
		 * @param string $key
		 * @return string
		 * @throws Config\Exception
		 */
		protected function _checkKey($key)
		{
			$key = (string) $key;
			if (!array_key_exists($key, $this->_data))
			{
				throw new Config\Exception(__CLASS__ . ' does not have the property ' . $key);
			}
			return $key;
		}

		/**
		 * Performs self check on options. Command classes should call this before making api call
		 * @return Config
		 */
		public function check()
		{
			// If username is null, assign apiUser
			if (null === $this->_data['username']) { $this->setUsername($this->getApiUser()); }

			// If client ip was not set, use 0.0.0.0
			if (null === $this->_data['clientIp']) { $this->setClientIp('0.0.0.0'); }

			// If url is null, set it to sandbox environment
			if (null === $this->_data['url']) { $this->setUrl(self::ENV_SANDBOX); }

			return $this;
		}

		/**
		 * Set the api user
		 * @param string $value
		 * @return Config
		 */
		public function setApiUser($value)
		{
			$this->_data['apiUser'] = (string) substr($value, 0, 20);
			return $this;
		}

		/**
		 * Set the api key
		 * @param string $value
		 * @return Config
		 */
		public function setApiKey($value)
		{
			$this->_data['apiKey'] = (string) strtolower(substr($value, 0, 50));
			return $this;
		}

		/**
		 * Set the username
		 * @param string $value
		 * @return Config
		 */
		public function setUsername($value)
		{
			$this->_data['username'] = (string) substr($value, 0, 20);
			return $this;
		}

		/**
		 * Set client ip
		 * @param string $value
		 * @return Config
		 */
		public function setClientIp($value)
		{
			if (!filter_var($value, FILTER_VALIDATE_IP))
			{
				throw new Config\Exception('Invalid client ip ' . $value);
			}

			$this->_data['clientIp'] = (string) substr($value, 0, 15);
			return $this;
		}

		/**
		 * Set sandbox option
		 * @param bool $value
		 * @return Config
		 */
		public function setSandbox($value)
		{
			if (!is_bool($value))
			{
				throw new Config\Exception('Property sandbox must be bool (true/false)');
			}

			$this->_data['sandbox'] = (bool) $value;

			if ($this->_data['sandbox'] === true)
			{
				$this->_data['url'] = self::ENV_SANDBOX;
			}

			return $this;
		}

		/**
		 * Set url
		 * @param string $value
		 * @return Config
		 */
		public function setUrl($value)
		{
			$this->_data['url'] = (string) $value;
			return $this;
		}

		/**
		 * Get api user
		 * @return string
		 */
		public function getApiUser()
		{
			return (string) $this->_data['apiUser'];
		}

		/**
		 * Get api key
		 * @return string
		 */
		public function getApiKey()
		{
			return (string) $this->_data['apiKey'];
		}

		/**
		 * Get username
		 * @return string
		 */
		public function getUsername()
		{
			return (string) $this->_data['username'];
		}

		/**
		 * Get client ip
		 * @return string
		 */
		public function getClientIp()
		{
			return (string) $this->_data['clientIp'];
		}

		/**
		 * Get sandbox
		 * @return bool
		 */
		public function getSandbox()
		{
			return (bool) $this->_data['sandbox'];
		}

		/**
		 * Get url
		 * @return string
		 */
		public function getUrl()
		{
			return (string) $this->_data['url'];
		}

		/**
		 * Fluid interface to get/set api user
		 * @param string $value
		 * @return mixed
		 */
		public function apiUser($value = null)
		{
			if (null !== $value)
			{
				return $this->setApiUser($value);
			}

			return $this->getApiUser();
		}

		/**
		 * Fluid interface to get/set api key
		 * @param string $value
		 * @return mixed
		 */
		public function apiKey($value = null)
		{
			if (null !== $value)
			{
				return $this->setApiKey($value);
			}

			return $this->getApiKey();
		}

		/**
		 * Fluid interface to get/set username
		 * @param string $value
		 * @return mixed
		 */
		public function username($value = null)
		{
			if (null !== $value)
			{
				return $this->setUsername($value);
			}

			return $this->getUsername();
		}

		/**
		 * Fluid interface to get/set client ip
		 * @param string $value
		 * @return mixed
		 */
		public function clientIp($value = null)
		{
			if (null !== $value)
			{
				return $this->setClientIp($value);
			}

			return $this->getClientIp();
		}

		/**
		 * Fluid interface to get/set sandbox
		 * @param bool $value
		 * @return mixed
		 */
		public function sandbox($value = null)
		{
			if (null !== $value)
			{
				return $this->setSandbox($value);
			}

			return $this->getSandbox();
		}

		/**
		 * Fluid interface to get/set url
		 * @param string $value
		 * @return mixed
		 */
		public function url($value = null)
		{
			if (null !== $value)
			{
				return $this->setUrl($value);
			}

			return $this->getUrl();
		}
	}
}
