<?php

require_once dirname(__FILE__) . '/../../../../../lib/php/Db/Dao/Abstract.php';

/**
 * Test class for Db_Dao_Abstract
 */
class Db_Dao_AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Db_Dao_Abstract
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('Db_Dao_Abstract');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * ハンドラは使いまわされる
     *
     */
    public function testハンドラは使いまわされる()
    {
        $this->assertSame($this->object->getDbHandler(), $this->object->getDbHandler());
    }
    
    /**
     * useTransactionでアクティブなトランザクションクラスを設定してからgetDbHandlerを呼び出すとトランザクションクラスからDBHを取得する
     *
     */
    public function test_useTransactionでアクティブなトランザクションクラスを設定してからgetDbHandlerを呼び出すとトランザクションクラスからDBHを取得する()
    {
        $dbh = new stdClass();
        $transaction = $this->getMock('Db_Transaction', array('getDbHandler'));
        $transaction->expects($this->once())->method('getDbHandler')->will($this->returnValue($dbh));
        
        $this->object->useTransaction($transaction);
        $this->assertSame($dbh, $this->object->getDbHandler());
    }
    
    /**
     * useTransactionで設定したトランザクションが非アクティブの場合トランザクションのgetDbHandlerは呼び出されない
     *
     */
    public function test_useTransactionで設定したトランザクションが非アクティブの場合トランザクションのgetDbHandlerは呼び出されない()
    {
        $transaction = $this->getMock('Db_Transaction', array('getDbHandler', 'isActive'));
        $transaction->expects($this->never())->method('getDbHandler');
        $transaction->expects($this->once())->method('isActive')->will($this->returnValue(false));
        
        $this->object->useTransaction($transaction);
        $this->object->getDbHandler();
    }

    /**
     * isInTransactionはトランザクションがセットされていなければfalseを返す
     */
    public function test_isInTransactionはトランザクションがセットされていなければfalseを返す()
    {
        $condition = $this->object->isInTransaction();
        $this->assertFalse($condition, "トランザクションオブジェクトがセットされていなければfalseが返るはず");
    }

    /**
     * isInTransactionはトランザクションがセットされていてもDbTransactionが非アクティブ状態であればfalseを返す
     */
    public function test_isInTransactionはトランザクションがセットされていてもDbTransactionが非アクティブ状態であればfalseを返す()
    {
        $transaction = $this->getMock('Db_Transaction', array('isActive'));
        $transaction->expects($this->once())->method('isActive')->will($this->returnValue(false));
        $this->object->useTransaction($transaction);

        // isActiveがfalseを返したらisInTransaction()もfalseを返すはず
        $condition = $this->object->isInTransaction();
        $this->assertFalse($condition, "isActiveがfalseを返したらisInTransaction()もfalseを返すはず");
    }

    /**
     * isInTransactionはアクティブなトランザクションがセットされていればtrueを返す
     */
    public function test_isInTransactionはアクティブなトランザクションがセットされていればtrueを返す()
    {
        // 初期状態ではDbTransactionはアクティブでisActive()はtruを返す
        $transaction = new Db_Transaction();
        $this->assertTrue($transaction->isActive(), "初期状態ではDbTransactionはアクティブなはず");

        $this->object->useTransaction($transaction);
        $condition = $this->object->isInTransaction();
        $this->assertTrue($condition, "トランザクションがセットされていてかつアクティブであればisInTransactionはtrueを返すはず");
    }
}
