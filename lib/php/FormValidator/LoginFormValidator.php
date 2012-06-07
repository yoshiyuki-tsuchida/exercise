<?php
require_once dirname(__FILE__) . '/../Validator.php';

class FormValidator_LoginFormValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ユーザー名フィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function acountName($field, $val, $label)
    {
        $pattern = array(
            'pattern' => '/\A[0-9a-zA-Z]{1,15}\z/',
            'pattern_name' => '半角英数'
        );
        return $this->regExp($field, $val, $label, $pattern);
    }

    /**
     * パスワードフィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function password($field, $val, $label)
    {
        $pattern = array(
            'pattern' => '/\A[0-9a-zA-Z&%$#!?_]{6,64}\z/',
            'pattern_name' => '半角英数&%$#!?_6文字から64文字'
        );
        return $this->regExp($field, $val, $label, $pattern);
    }
}

