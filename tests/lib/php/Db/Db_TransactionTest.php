<?php

require_once dirname(__FILE__) . '/../../../../lib/php/Db/Transaction.php';

/**
 * Test class for Db_Transaction.
 */
class Db_TransactionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Db_Transaction
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Db_Transaction;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * setDbHandlerFactoryNameメソッドでdbHandlerFactoryNameメンバ変数を変更できる
     *
     */
    public function test_setDbHandlerFactoryName()
    {
        $this->_object->setDbHandlerFactoryName('FooFactory');
        $this->assertAttributeSame('FooFactory', '_dbHandlerFactoryName', $this->_object);
    }
    
    /**
     * 生成直後はisActiveがtrueを返す
     *
     */
    public function test_生成直後はisActiveがtrueを返す()
    {
        $this->assertTrue($this->_object->isActive());
    }

    /**
     * commit後はisActiveがfalseを返す
     *
     */
    public function test_commit後はisActiveがfalseを返す()
    {
        $this->_object->commit();
        $this->assertFalse($this->_object->isActive());
    }

    /**
     * rollback後はisActiveがfalseを返す
     *
     */
    public function test_rollback後はisActiveがfalseを返す()
    {
        $this->_object->rollback();
        $this->assertFalse($this->_object->isActive());
    }

    /**
     * 同じハンドラを何度取得しても同じインスタンスが返る
     *
     */
    public function test_同じハンドラを何度取得しても同じインスタンスが返る()
    {
        $this->assertSame(
            $this->_object->getDbHandler(), $this->_object->getDbHandler(), '同じであるはず'
        );
    }

    /**
     * 初回にgetDbHandlerを呼び出したとき取得されたDBHのbeginTransactionが呼ばれる
     *
     */
    public function test_初回にgetDbHandlerを呼び出したとき取得されたDBHのbeginTransactionが呼ばれる()
    {
        $handlerFactory = $this->getMock('Db_HandlerFactory', array(), array(), '', false);
        
        $pdo = $this->getMock('PDOMock', array('beginTransaction'));
        $pdo->expects($this->once())
            ->method('beginTransaction');
        
        $handlerFactory->staticExpects($this->once())
            ->method('getDemoDBHandler')
            ->will($this->returnValue($pdo));
        
        $this->_object->setDbHandlerFactoryName(get_class($handlerFactory));
        
        $this->_object->getDbHandler();
        $this->_object->getDbHandler(); // 2回呼んでもbeginTransactionは1度
    }
    
    /**
     * getDbHandler後にcommitを呼ぶとDBHのcommitが呼ばれる
     *
     */
    public function test_getDbHandler後にcommitを呼ぶとDBHのcommitが呼ばれる()
    {
        $handlerFactory = $this->getMock('Db_HandlerFactory', array(), array(), '', false);
        
        $pdo = $this->getMock('PDOMock', array('commit', 'beginTransaction'));
        $pdo->expects($this->once())
            ->method('commit');
        $pdo->expects($this->never())
            ->method('rollback');
        
        $handlerFactory->staticExpects($this->once())
            ->method('getDemoDBHandler')
            ->will($this->returnValue($pdo));
        
        $this->_object->setDbHandlerFactoryName(get_class($handlerFactory));
        
        $this->_object->getDbHandler();

        $this->_object->commit();
    }
    
    /**
     * getDbHandler後にrollbackを呼ぶとDBHのrollbackが呼ばれる
     *
     */
    public function test_getDbHandler後にrollbackを呼ぶとDBHのrollbackが呼ばれる()
    {
        $handlerFactory = $this->getMock('Db_HandlerFactory', array(), array(), '', false);
        
        $pdo = $this->getMock('PDOMock', array('commit', 'rollback', 'beginTransaction'));
        $pdo->expects($this->never())
            ->method('commit');
        $pdo->expects($this->once())
            ->method('rollback');
        
        $handlerFactory->staticExpects($this->once())
            ->method('getDemoDBHandler')
            ->will($this->returnValue($pdo));
        
        $this->_object->setDbHandlerFactoryName(get_class($handlerFactory));
        
        $this->_object->getDbHandler();

        $this->_object->rollback();
    }
}

?>
