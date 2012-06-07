<?php
require_once dirname(__FILE__) . '/LoginFormValidator.php';

class FormValidator_AdminFormValidator extends FormValidator_LoginFormValidator
{
    public $labels = array(
        'admin_name' => 'ユーザー名',
        'password' => 'パスワード'
    );

    public function __construct()
    {
        parent::__construct();

        $this->addRule('admin_name', 'required', $this->labels['admin_name'])
            ->addRule('admin_name', 'acountName', $this->labels['admin_name'])
            ->addRule('password', 'required', $this->labels['password'])
            ->addRule('password', 'password', $this->labels['password']);
    }
}

