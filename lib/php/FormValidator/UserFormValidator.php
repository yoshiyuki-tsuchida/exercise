<?php
require_once dirname(__FILE__) . '/LoginFormValidator.php';

class FormValidator_UserFormValidator extends FormValidator_LoginFormValidator
{
    public $labels = array(
        'user_name' => 'ユーザー名',
        'password' => 'パスワード'
    );

    public function __construct()
    {
        parent::__construct();

        $this->addRule('user_name', 'required', $this->labels['user_name'])
            ->addRule('user_name', 'acountName', $this->labels['user_name'])
            ->addRule('password', 'required', $this->labels['password'])
            ->addRule('password', 'password', $this->labels['password']);
    }
}

