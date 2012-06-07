<?php
require_once dirname(__FILE__) . '/../Validator.php';

class FormValidator_ContactFormValidator extends Validator
{
    public $labels = array(
        'contact_title' => 'タイトル',
	'contact_body' => '内容',
	'contact_content_url' => '商品URL',
	'contact_purchase_num' => '購入番号',
	'contact_privacy' => 'プライバシー'
    );

    public function __construct()
    {
        parent::__construct();

        $this->addRule('contact_title', 'required', $this->labels['contact_title'])
	  ->addRule('contact_title', 'str_count', $this->labels['contact_title'],20)
	  ->addRule('contact_body', 'required', $this->labels['contact_body'])
	  ->addRule('contact_body', 'str_count', $this->labels['contact_body'],400)
	  ->addRule('contact_privacy', 'required', $this->labels['contact_privacy'])
	  ->addRule('contact_content_url', 'is_url', $this->labels['contact_content_url'])
	  ->addRule('contact_purchase_num', 'is_purchase_id', $this->labels['contact_purchase_num']);
	  //	  ->addRule('contact_content_url', 'required', $this->labels['contact_content_url'])
	  //->addRule('contact_purchase_num', 'required', $this->labels['contact_purchase_num']);

    }


    public function isContentUrl()
    {

      $this->addRule('contact_content_url', 'required', $this->labels['contact_content_url']);




    }

    public function isPurchaseNum()
    {

      $this->addRule('contact_purchase_num', 'required', $this->labels['contact_purchase_num']);



    }









    
}

