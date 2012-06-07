<?php
require_once dirname(__FILE__) . '/../../../lib/php/Factory.php';
require_once dirname(__FILE__) . '/../../data/DummyClass.php';
require_once dirname(__FILE__) . '/../../data/DummyAbstractClass.php';
require_once dirname(__FILE__) . '/../../data/DummyImplFactoryInjector.php';

class FactoryTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new Factory();
    }

    public function test_インスタンスを取得できる()
    {
        $instance = $this->factory->getDummyClass();
        $this->assertTrue($instance instanceof DummyClass);
    }

    public function test_FactoryInjectorを継承してる場合ファクトリが設定されること()
    {
        $instance = $this->factory->getDummyImplFactoryInjector();
        $this->assertTrue($instance->getFactory() instanceof Factory);
    }

    public function test_命名規則にのっとらないメソッド名の場合NULLが返ること()
    {
        $instance = $this->factory->test();
        $this->assertNull($instance);
    }

    public function test_指定したクラスがない場合NULLが返ること()
    {
        $instance = $this->factory->getNotExistsClass();
        $this->assertNull($instance);
    }

    public function test_インスタンス化できないクラスの場合NULLが返ること()
    {
        $instance = $this->factory->getDummyAbstractClass();
        $this->assertNull($instance);
    }
}

