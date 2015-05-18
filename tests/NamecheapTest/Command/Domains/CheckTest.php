<?php
/**
 * @author Maksym Karazieiev <mk@sunnyrentals.com>
 */
namespace NamecheapTest\Command\Domains {

    use Namecheap\Command\Domains\Check;

    class CheckTest extends \PHPUnit_Framework_TestCase {

        /**
         * @var Check
         */
        protected $_command;

        public function testCorrectParameterReturned() {
            $domains = ['test.com', 'dummy.net'];
            $this->_command->domainList($domains);
            $this->assertEquals($this->_command->getParam('DomainList'), $this->_command->domainList());
        }

        protected function setUp() {
            $this->_command = new Check();
        }

        public function testCommandName() {
            $this->assertEquals('namecheap.domains.check', $this->_command->command());
        }

        /**
         * @expectedException \Namecheap\Command\Domains\Check\Exception
         */
        public function testDomainWasNotRequested() {
            $this->_command->isAvailable('test.com');
        }

        public function testCheckedDomainAvailable() {
            $domain = 'test.com';
            $this->_command->domains = [$domain => true];
            $this->assertTrue($this->_command->isAvailable($domain));
        }

        public function testCheckedDomainNotAvailable() {
            $domain = 'test.com';
            $this->_command->domains = [$domain => false];
            $this->assertFalse($this->_command->isAvailable($domain));
        }

        public function testSetDomainList() {
            $domains = ['test.com', 'dummy.net'];
            $this->_command->domainList($domains);
            foreach ($domains as $domain) {
                $this->assertContains($domain, $this->_command->getParam('DomainList'));
            }
        }



    }

}
