<?php
define('PROJECT_DIR', dirname(__FILE__) . '/../../../..');
require_once PROJECT_DIR . '/config/const.php';
require_once LIB_DIR . '/FormValidator/RegisterMemberFormValidator.php';

class FormValidator_RegisterMemberFormValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $form_validator;

    public function setUp()
    {
        $this->form_validator = new FormValidator_RegisterMemberFormValidator();
    }

    public function test_ユーザー名にはrequiredとacountNameのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'ユーザー名',
                'opts' => array()
            ),
            array(
                'filter' => 'acountName',
                'label' => 'ユーザー名',
                'opts' => array()
            ),
        );

        $this->assertEquals($expected, $this->form_validator->getRule('user_name'));
    }

    public function test_パスワードにはrequiredとpasswordのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'パスワード',
                'opts' => array()
            ),
            array(
                'filter' => 'password',
                'label' => 'パスワード',
                'opts' => array()
            ),
        );

        $this->assertEquals($expected, $this->form_validator->getRule('password'));
    }

    public function test_emailにはrequiredとemailのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'メールアドレス',
                'opts' => array()
            ),
            array(
                'filter' => 'email',
                'label' => 'メールアドレス',
                'opts' => array()
            )
        );

        $this->assertEquals($expected, $this->form_validator->getRule('email'));
    }

    public function test_誕生日にはrequiredとdateのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => '誕生日',
                'opts' => array()
            ),
            array(
                'filter' => 'date',
                'label' => '誕生日',
                'opts' => array()
            )
        );

        $this->assertEquals($expected, $this->form_validator->getRule('birthday'));
    }

    public function test_dateは日付として妥当の場合trueそうでない場合faseを返すこと()
    {
        $this->assertTrue($this->form_validator->date('test', '2000-01-01', ''), '2000-01-01の場合true');
        $this->assertTrue($this->form_validator->date('test', '2000-11-11', ''), '2000-11-11の場合true');
        $this->assertTrue($this->form_validator->date('test', '2000-11-21', ''), '2000-12-21の場合true');
        $this->assertTrue($this->form_validator->date('test', '2000-12-31', ''), '2000-12-31の場合true');
        $this->assertTrue($this->form_validator->date('test', '2004-02-29', ''), '2004-02-29の場合true');
        $this->assertFalse($this->form_validator->date('test', '2002-02-29', ''), '2002-02-29の場合false');
        $this->assertFalse($this->form_validator->date('test', '2002-13-29', ''), '2002-13-29の場合false');
        $this->assertFalse($this->form_validator->date('test', '2002-12-32', ''), '2002-12-32の場合false');
        $this->assertFalse($this->form_validator->date('test', '2000/01/01', ''), '2000/01/01の場合false');
        $this->assertFalse($this->form_validator->date('test', '2000/1/4', ''), '2000/1/4の場合false');
        $this->assertFalse($this->form_validator->date('test', '2000/12/4', ''), '2000/12/4の場合false');
        $this->assertFalse($this->form_validator->date('test', '2000-12-1a', ''), '2000-12-1aの場合false');
        $this->assertFalse($this->form_validator->date('test', '2000-1a-12', ''), '2000-1a-12の場合false');
        $this->assertFalse($this->form_validator->date('test', '200a-11-12', ''), '200a-11-12の場合false');
        $this->assertFalse($this->form_validator->date('test', '2000-12-12a', ''), '2000-12-12aの場合false');
        $this->assertFalse($this->form_validator->date('test', '2000-11a-12', ''), '2000-11a-12の場合false');
        $this->assertFalse($this->form_validator->date('test', '2001a-11-12', ''), '2001a-11-12の場合false');
        $this->assertFalse($this->form_validator->date('test', '', ''), '""の場合false');
        $this->assertFalse($this->form_validator->date('test', 'aaaaaaaaaaaa', ''), 'aaaaaaaaaaaaの場合false');
        $this->assertFalse($this->form_validator->date('test', '20011221', ''), '20011221の場合false');
    }
}
