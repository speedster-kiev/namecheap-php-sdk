<?php

namespace Namecheap\Command
{
	interface ICommand
	{
		/**
		 * @param Namecheap\Config $config
		 */
		public function config(\Namecheap\Config $config);

		/**
		 * Should return the command string
		 * @return string
		 */
		public function command();

		/**
		 * Should return an array of parameters that the extending command is adding along with default values
		 * @return array
		 */
		public function params();
	}
}
