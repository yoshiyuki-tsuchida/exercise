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
     * �ϥ�ɥ�ϻȤ��ޤ蘆���
     *
     */
    public function test�ϥ�ɥ�ϻȤ��ޤ蘆���()
    {
        $this->assertSame($this->object->getDbHandler(), $this->object->getDbHandler());
    }
    
    /**
     * useTransaction�ǥ����ƥ��֤ʥȥ�󥶥�����󥯥饹�����ꤷ�Ƥ���getDbHandler��ƤӽФ��ȥȥ�󥶥�����󥯥饹����DBH���������
     *
     */
    public function test_useTransaction�ǥ����ƥ��֤ʥȥ�󥶥�����󥯥饹�����ꤷ�Ƥ���getDbHandler��ƤӽФ��ȥȥ�󥶥�����󥯥饹����DBH���������()
    {
        $dbh = new stdClass();
        $transaction = $this->getMock('Db_Transaction', array('getDbHandler'));
        $transaction->expects($this->once())->method('getDbHandler')->will($this->returnValue($dbh));
        
        $this->object->useTransaction($transaction);
        $this->assertSame($dbh, $this->object->getDbHandler());
    }
    
    /**
     * useTransaction�����ꤷ���ȥ�󥶥�������󥢥��ƥ��֤ξ��ȥ�󥶥�������getDbHandler�ϸƤӽФ���ʤ�
     *
     */
    public function test_useTransaction�����ꤷ���ȥ�󥶥�������󥢥��ƥ��֤ξ��ȥ�󥶥�������getDbHandler�ϸƤӽФ���ʤ�()
    {
        $transaction = $this->getMock('Db_Transaction', array('getDbHandler', 'isActive'));
        $transaction->expects($this->never())->method('getDbHandler');
        $transaction->expects($this->once())->method('isActive')->will($this->returnValue(false));
        
        $this->object->useTransaction($transaction);
        $this->object->getDbHandler();
    }

    /**
     * isInTransaction�ϥȥ�󥶥�����󤬥��åȤ���Ƥ��ʤ����false���֤�
     */
    public function test_isInTransaction�ϥȥ�󥶥�����󤬥��åȤ���Ƥ��ʤ����false���֤�()
    {
        $condition = $this->object->isInTransaction();
        $this->assertFalse($condition, "�ȥ�󥶥�����󥪥֥������Ȥ����åȤ���Ƥ��ʤ����false���֤�Ϥ�");
    }

    /**
     * isInTransaction�ϥȥ�󥶥�����󤬥��åȤ���Ƥ��Ƥ�DbTransaction���󥢥��ƥ��־��֤Ǥ����false���֤�
     */
    public function test_isInTransaction�ϥȥ�󥶥�����󤬥��åȤ���Ƥ��Ƥ�DbTransaction���󥢥��ƥ��־��֤Ǥ����false���֤�()
    {
        $transaction = $this->getMock('Db_Transaction', array('isActive'));
        $transaction->expects($this->once())->method('isActive')->will($this->returnValue(false));
        $this->object->useTransaction($transaction);

        // isActive��false���֤�����isInTransaction()��false���֤��Ϥ�
        $condition = $this->object->isInTransaction();
        $this->assertFalse($condition, "isActive��false���֤�����isInTransaction()��false���֤��Ϥ�");
    }

    /**
     * isInTransaction�ϥ����ƥ��֤ʥȥ�󥶥�����󤬥��åȤ���Ƥ����true���֤�
     */
    public function test_isInTransaction�ϥ����ƥ��֤ʥȥ�󥶥�����󤬥��åȤ���Ƥ����true���֤�()
    {
        // ������֤Ǥ�DbTransaction�ϥ����ƥ��֤�isActive()��tru���֤�
        $transaction = new Db_Transaction();
        $this->assertTrue($transaction->isActive(), "������֤Ǥ�DbTransaction�ϥ����ƥ��֤ʤϤ�");

        $this->object->useTransaction($transaction);
        $condition = $this->object->isInTransaction();
        $this->assertTrue($condition, "�ȥ�󥶥�����󤬥��åȤ���Ƥ��Ƥ��ĥ����ƥ��֤Ǥ����isInTransaction��true���֤��Ϥ�");
    }
}
