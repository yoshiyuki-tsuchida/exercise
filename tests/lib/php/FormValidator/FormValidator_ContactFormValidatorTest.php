<?php
define('PROJECT_DIR', dirname(__FILE__) . '/../../../..');
require_once PROJECT_DIR . '/config/const.php';
require_once LIB_DIR . '/FormValidator/ContactFormValidator.php';

class FormValidator_ContactFormValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $form_validator;

    public function setUp()
    {
        $this->form_validator = new FormValidator_ContactFormValidator();
    }

    public function test_タイトルにはrequiredとstr_countのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'タイトル',
                'opts' => array()
            ),
            array(
                'filter' => 'str_count',
                'label' => 'タイトル',
                'opts' => 20
            )
        );

        $this->assertEquals($expected, $this->form_validator->getRule('contact_title'));
    }

    public function test_内容にはrequiredとstr_countのルールが適用されていること()
    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => '内容',
                'opts' => array()
            ),
            array(
                'filter' => 'str_count',
                'label' => '内容',
                'opts' => 400
            )
        );

        $this->assertEquals($expected, $this->form_validator->getRule('contact_body'));
    }


    public function test_商品に関するが選択されていたら商品URLにrequiredのルールが適用されていること()
    {
		$this->form_validator->isContentUrl();	
        $expected = array(
            array(
                'filter' => 'is_url',
                'label' => '商品URL',
                'opts' => array()
            ),
            array(
                'filter' => 'required',
                'label' => '商品URL',
                'opts' => array()
            ),
        );
        $this->assertEquals($expected, $this->form_validator->getRule('contact_content_url'));
    }

    public function test_商品に関するが選択されていたら購入番号にはルールを適用しない()
    {
		$this->form_validator->isContentUrl();	
        $expected = array(
            array(
                'filter' => 'required',
                'label' => '購入番号',
                'opts' => array()
            ),
        );
        $this->assertNotEquals($expected, $this->form_validator->getRule('contact_purchase_num'));
    }


    public function test_購入に関するが選択されていたら購入番号にrequiredのルールが適用されていること()
    {
		$this->form_validator->isPurchaseNum();	
        $expected = array(
            array(
                'filter' => 'is_purchase_id',
                'label' => '購入番号',
                'opts' => array()
            ),
            array(
                'filter' => 'required',
                'label' => '購入番号',
                'opts' => array()
            ),
        );

        $this->assertEquals($expected, $this->form_validator->getRule('contact_purchase_num'));
    }

    public function test_購入に関するが選択されていたら商品URLにrequiredのルールを適用しない()
    {
		$this->form_validator->isPurchaseNum();	
        $expected = array(
            array(
                'filter' => 'required',
                'label' => '商品URL',
                'opts' => array()
            ),
        );

        $this->assertNotEquals($expected, $this->form_validator->getRule('contact_content_url'));
    }

    public function test_privacyにはrequiredのルールが適用されていること()

    {
        $expected = array(
            array(
                'filter' => 'required',
                'label' => 'プライバシー',
                'opts' => array()
            ),
        );

        $this->assertEquals($expected, $this->form_validator->getRule('contact_privacy'));
    }
	
}
