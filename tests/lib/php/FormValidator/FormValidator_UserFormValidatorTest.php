<?php
define('PROJECT_DIR', dirname(__FILE__) . '/../../../..');
require_once PROJECT_DIR . '/config/const.php';
require_once LIB_DIR . '/FormValidator/LoginFormValidator.php';

class FormValidator_LoginFormValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $form_validator;

    public function setUp()
    {
        $this->form_validator = new FormValidator_LoginFormValidator();
    }

    public function test_acountNameは半角英数1〜15文字の場合はtrueそうでない場合はfalse()
    {
        $this->assertTrue($this->form_validator->acountName('test', '1', ''), '英数1文字の場合true');
        $this->assertTrue($this->form_validator->acountName('test', '123456789012345', ''), '英数15文字の場合true');
        $this->assertTrue($this->form_validator->acountName('test', '1deiof254DIOJFD', ''), '英数混在の場合true');
        $this->assertFalse($this->form_validator->acountName('test', '', ''), '0文字の場合false');
        $this->assertFalse($this->form_validator->acountName('test', 'abcde67890abcde1', ''), '英数16文字の場合false');
        $this->assertFalse($this->form_validator->acountName('test', 'abe&', ''), '英数以外が含まれたの場合false');
        $this->assertFalse($this->form_validator->acountName('test', 'a\%$b"#$%&(e&', ''), '英数以外が含まれたの場合false');
    }

    public function test_passwordは半角英数と記号6〜64文字の場合はtrueそうでない場合はfalse()
    {
        $this->assertTrue($this->form_validator->password('test', '1bcde6', ''), '英数6文字の場合true');
        $this->assertTrue($this->form_validator->password('test', '1bcde678ij1bcde678ij1bcde678ij1bcde678ij1bcde678ij1bcde678ij&%$#', ''), '英数記号64文字の場合true');
        $this->assertTrue($this->form_validator->password('test', '!#$%&?_', ''), '記号');
        $this->assertFalse($this->form_validator->password('test', '1bcde', ''), '英数5文字の場合false');
        $this->assertFalse($this->form_validator->password('test', '1bcde678ij1bcde678ij1bcde678ij1bcde678ij1bcde678ij1bcde678ij&%$#!', ''), '英数記号65文字の場合false');
        $this->assertFalse($this->form_validator->password('test', '!#$%&?_^~', ''), '記号');
    }
}
