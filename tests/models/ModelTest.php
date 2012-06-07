<?php
require_once dirname(__FILE__) . '/../../models/Model.php';

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $model;

    public function setUp()
    {
        $this->model = $this->getMockForAbstractClass('Model');
    }

    public function test_ファクトリオブジェクトを設定して取得できること()
    {
        $factory = 1;
        $this->model->setFactory($factory);
        $this->assertEquals($factory, $this->model->getFactory());
    }
}
