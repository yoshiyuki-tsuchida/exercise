<?php

require_once dirname(__FILE__) . '/../../../../lib/php/Db/HandlerFactory.php';

/**
 * Test class for Db_HandlerFactory.
 */
class Db_HandlerFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Db_HandlerFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * getDemoDBHandlerでPDOインスタンスが取得できる
     *
     */
    public function test_getDemoHandlerでPDOインスタンスが取得できる()
    {
        $dbHandler = Db_HandlerFactory::getDemoDBHandler();
        $this->assertInstanceOf('PDO', $dbHandler);
    }
}

?>
