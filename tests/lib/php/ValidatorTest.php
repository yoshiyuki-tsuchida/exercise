<?php
require_once dirname(__FILE__) . '/../../../lib/php/Validator.php';

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $filter;

    public function setUp()
    {
        $this->filter = new Validator();
    }

    public function test_必須チェック()
    {
        $this->assertTrue($this->filter->required('test', 'a', ''), '入力値あり');
        $this->assertFalse($this->filter->required('test', '', ''), '空文字');
        $this->assertFalse($this->filter->required('test', '      ', ''), 'スペース');
    }

    public function test_email()
    {
        $this->assertTrue($this->filter->email('test', 'test@hoge.com', 'test'));
        $this->assertFalse($this->filter->email('test', 'test', 'test'));
    }

    public function test_str_count()
    {
		
        $this->assertTrue($this->filter->str_count('test', 'こんにちは', 'test', '26'));
        $this->assertFalse($this->filter->str_count('test', '123', 'test', '2'));
        $this->assertFalse($this->filter->str_count('test', 'おはようございます', 'test', '3'));
    }

    public function test_エラーの場合エラーを取得できる()
    {
        $expected = array(
            'test' => 'testは必須項目です',
            'test2' => 'test2は必須項目です',
            'test3' => 'test3は文字数制限を超えています'
        );
        $this->filter->required('test', '', 'test');
        $this->filter->required('test2', '', 'test2');
        $this->filter->str_count('test3', 'おはよう', 'test3', '1');

        $result = $this->filter->getErrors();

        $this->assertEquals($expected, $result);
    }

    public function test_1つ目のルールで引っかかった場合は同じfieldのチェックはされないこと()
    {
        $expected = array(
            'test' => 'testは必須項目です'
        );

        $result = $this->filter
            ->addRule('test', 'required', 'test')
            ->addRule('test', 'regExp', 'test', array('pattern' => '/\Aa/', 'pattern_name' => 'a'))
            ->run(array('test' => ''));

        $this->assertFalse($result);
        $this->assertEquals($expected, $this->filter->getErrors());
    }

    public function test_1つ目のルールが通った場合次のルールでチェックされること()
    {
        $expected = array(
            'test' => 'testはtestで入力してください'
        );

        $result = $this->filter
            ->addRule('test', 'required', 'test')
            ->addRule('test', 'regExp', 'test', array('pattern' => '/\Aa/', 'pattern_name' => 'test'))
            ->run(array('test' => 'b'));

        $this->assertFalse($result);
        $this->assertEquals($expected, $this->filter->getErrors());
    }

    public function test_複数項目設定した場合()
    {
        $expected = array(
            'test' => 'testはtestで入力してください',
            'test2' => 'test2は必須項目です'
        );

        $result = $this->filter
            ->addRule('test', 'required', 'test')
            ->addRule('test', 'regExp', 'test', array('pattern' => '/\Aa/', 'pattern_name' => 'test'))
            ->addRule('test2', 'required', 'test2')
            ->run(array('test' => 'b', 'test2' => ''));

        $this->assertFalse($result);
        $this->assertEquals($expected, $this->filter->getErrors());
    }

    public function test_設定したルールを取得できること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'test',
                'opts' => array()
            )
        );
        $this->filter->addRule('test', 'required', 'test');
        $this->assertEquals($expected, $this->filter->getRule('test'));
    }

    public function test_引数を指定しない場合すべての設定したルールを取得できること()
    {
        $expected = array(
            'test' => array(
                array(
                    'filter' => 'required',
                    'label' => 'test',
                    'opts' => array()
                )
            ),
            'test2' => array(
                array(
                    'filter' => 'required',
                    'label' => 'test2',
                    'opts' => array()
                )
            )
        );
        $this->filter->addRule('test', 'required', 'test');
        $this->filter->addRule('test2', 'required', 'test2');
        $this->assertEquals($expected, $this->filter->getRule());
    }

    public function test_指定した引数のルールがない場合falseになること()
    {
        $this->filter->addRule('test', 'required', 'test');
        $this->assertFalse($this->filter->getRule('test2'));
    }
}
