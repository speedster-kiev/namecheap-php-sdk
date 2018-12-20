<?php

namespace Namecheap\Command\Domains\GetInfo
{
    class Exception extends \Exception{}
}

namespace Namecheap\Command\Domains {

    class GetInfo extends \Namecheap\Command\ACommand
    {
        public $attributes = array();
        public $domainDetails = array();
        public $lockDetails = array();
        public $whoIsGuard = array();
        public $premiumDnsSubscription = array();
        public $dnsDetails = array();
        public $modificationRights = array();

        public function command()
        {
            return 'namecheap.domains.getInfo';
        }

        public function params()
        {
            return array(
                'DomainName' => null,
                'HostName'   => null,
            );
        }

        /**
         * Process domains array
         */
        protected function _postDispatch()
        {
            $this->attributes = array();
            foreach ($this->_response->DomainGetInfoResult->attributes() as $key => $value) {
                $this->attributes[$key] = (string) $value;
            }

            $this->domainDetails = array();
            foreach ($this->_response->DomainGetInfoResult->DomainDetails as $entry) {
                foreach ($entry as $key => $value) {
                    $this->domainDetails[$key] = (string) $value;
                }
            }

            $this->lockDetails = array();
            foreach ($this->_response->DomainGetInfoResult->LockDetails as $entry) {
                foreach ($entry as $key => $value) {
                    $this->lockDetails[$key] = (string) $value;
                }
            }

            $this->whoIsGuard = array();
            foreach ($this->_response->DomainGetInfoResult->Whoisguard->attributes() as $key => $value) {
                $this->whoIsGuard[$key] = (string) $value;
            }
            foreach ($this->_response->DomainGetInfoResult->Whoisguard as $entry) {
                foreach ($entry as $key => $value) {
                    if ($key === 'EmailDetails') {
                        foreach ($value->attributes() as $details_key => $details_value) {
                            $this->whoIsGuard[$details_key] = (string) $details_value;
                        }

                        continue;
                    }

                    $this->whoIsGuard[$key] = (string) $value;
                }
            }

            $this->premiumDnsSubscription = array();
            foreach ($this->_response->DomainGetInfoResult->PremiumDnsSubscription as $entry) {
                foreach ($entry as $key => $value) {
                    $this->premiumDnsSubscription[$key] = (string) $value;
                }
            }

            $this->dnsDetails = array();
            $this->dnsDetails['Nameserver'] = array();
            foreach ($this->_response->DomainGetInfoResult->DnsDetails->attributes() as $key => $value) {
                $this->dnsDetails[$key] = (string) $value;
            }
            foreach ($this->_response->DomainGetInfoResult->DnsDetails as $entry) {
                foreach ($entry as $key => $value) {
                    if ($key === 'Nameserver') {
                        $this->dnsDetails['Nameserver'][] = (string) $value;

                        continue;
                    }

                    $this->dnsDetails[$key] = (string) $value;
                }
            }

            $this->modificationRights = array();
            foreach ($this->_response->DomainGetInfoResult->Modificationrights->attributes() as $key => $value) {
                $this->modificationRights[$key] = (string) $value;
            }
            foreach ($this->_response->DomainGetInfoResult->Modificationrights as $entry) {
                foreach ($entry as $key => $value) {
                    $this->modificationRights[$key] = (string) $value;
                }
            }
        }

        /**
         * Get/set method for domain name
         *
         * @param string $value
         *
         * @return mixed
         */
        public function domainName($value = null)
        {
            if (null !== $value) {
                $this->setParam('DomainName', (string) substr($value, 0, 70));
                return $this;
            }
            $this->getParam('DomainName');
        }

        /**
         * Get/set method for host name
         *
         * @param string $value
         *
         * @return mixed
         */
        public function hostName($value = null)
        {
            if (null !== $value) {
                $this->setParam('HostName', (string) substr($value, 0, 255));
                return $this;
            }
            $this->getParam('HostName');
        }

    }
}
