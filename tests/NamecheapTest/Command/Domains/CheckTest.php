<?php
/**
 * @author Maksym Karazieiev <mk@sunnyrentals.com>
 */
namespace NamecheapTest\Command\Domains {

    include_once '../src/Namecheap/Api.php';
    use Namecheap\Command\Domains\Check;

    class CheckTest extends \PHPUnit_Framework_TestCase {

        public function testCommandName() {
            $command = new Check();
            $this->assertEquals('namecheap.domains.check', $command->command());
        }
    }

}
