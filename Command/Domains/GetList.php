<?php

namespace Namecheap\Command\Domains\GetList
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains
{
	class GetList extends \Namecheap\Command\ACommand
	{
		public $domains = array();

		public function command()
		{
			return 'namecheap.domains.getList';
		}

		public function params()
		{
			return array(
				'ListType'	=> 'all',
				'Page'		=> 1,
				'Pagesize'	=> 100,
				'SortBy'	=> 'NAME',
				'SearchTerm' => null,
			);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			$this->domains = array();

			foreach ($this->_response->DomainGetListResult->Domain as $entry)
			{
				$domain = array();
				foreach ($entry->attributes() as $key => $value)
				{
					$domain[$key] = (string) $value;
				}
				$this->domains[] = $domain;
			}
		}

		/**
		 * Get/set method for list type
		 * @param string $value
		 * @return mixed
		 */
		public function listType($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('ListType', (string) substr($value, 0, 10));
				return $this;
			}
			$this->getParam('ListType');
		}

		/**
		 * Get/set method for page
		 * @param int $value
		 * @return mixed
		 */
		public function page($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('Page', (int) substr($value, 0, 10));
				return $this;
			}
			$this->getParam('Page');
		}

		/**
		 * Get/set method for page size
		 * @param int $value
		 * @return mixed
		 */
		public function pageSize($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('PageSize', (int) substr($value, 0, 10));
				return $this;
			}
			$this->getParam('PageSize');
		}

		/**
		 * Get/set method for sort by
		 * @param string $value
		 * @return mixed
		 */
		public function sortBy($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('SortBy', (string) substr($value, 0, 50));
				return $this;
			}
			$this->getParam('SortBy');
		}

		/**
		 * Get/set method for search term
		 * @param string $value
		 * @return mixed
		 */
		public function searchTerm($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('SearchTerm', (string) substr($value, 0, 70));
				return $this;
			}
			$this->getParam('SearchTerm');
		}
	}
}
