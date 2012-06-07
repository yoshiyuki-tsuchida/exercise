<?php
/**
 * Validator_Filterクラス
 *
 * @version $Id$
 */
class Validator
{
    /**
     * @var array エラーメッセージ
     */
    protected $_errors;

    /**
     * @var array filterルール
     */
    private $_rules;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->_errors = array();
        $this->_rules = array();
    }

    /**
     * エラーメッセージを取得する
     *
     * @return array エラーメッセージ
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * ルールを追加する
     *
     * @param string $field フィールド名
     * @param string $filter フィルター名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     */
    public function addRule($field, $filter, $label='', $opts=array())
    {
        if (!isset($this->_rules[$field])) {
            $this->_rules[$field] = array();
        }
        $label = ($label === '') ? $field : $label;
        $this->_rules[$field][] = array(
            'filter' => $filter,
            'label' => $label,
            'opts' => $opts
        );

        return $this;
    }

    /**
     * ルールを取得する
     */
    public function getRule($field = null)
    {
        if (is_null($field)) {
            return $this->_rules;
        }
        if (isset($this->_rules[$field])) {
            return $this->_rules[$field];
        }
        return false;
    }

    /**
     * バリデーションを実行する
     *
     * @param array バリデーション対象データ
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function run($datas)
    {
        foreach ($this->_rules as $field => $rules)
        {
            foreach ($rules as $rule)
            {
                $result = call_user_func_array(
                    array($this, $rule['filter']),
                    array($field, $datas[$field], $rule['label'], $rule['opts'])
                );
                if (!$result) {
                    break;
                }
            }
        }
        return count($this->_errors) === 0;
    }

    /**
     * 正規表現フィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function regExp($field, $val, $label, $opts)
    {
        if (preg_match($opts['pattern'], $val) <= 0)
        {
            $this->_errors[$field] = sprintf("%sは%sで入力してください", $label, $opts['pattern_name']);
            return false;
        }
        return true;
    }

    /**
     * 必須チェックフィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
1     */
    public function required($field, $val, $label)
    {
        if (trim($val) === '') {
            $this->_errors[$field] = sprintf("%sは必須項目です", $label);
            return false;
        }
        return true;
    }

    /**
     * e-mailフィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function email($field, $val, $label)
    {
        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->_errors[$field] = sprintf("%sはe-mail形式で入力してください", $label);
            return false;
        }
        return true;
    }


    /**
     * 文字数フィルタ
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function str_count($field, $val, $label, $limit)
    {
      
      if( mb_strlen($val,"utf-8") > $limit){
	    $this->_errors[$field] = sprintf("%sは文字数制限を超えています", $label);
	    return false;
        }
	return true;
      

    }

    /**
     * URLチェック
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function is_url($field, $val, $label)
    {
      $pattern = array(
		       'pattern' => '/\A(http:\/\/([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+))?\z/',
		       'pattern_name' => '正しいURL',
		       );
      return $this->regExp($field, $val, $label, $pattern);
            
    }

    /**
     * 購入番号チェック
     *
     * @param string $field フィールド名
     * @param string $label メッセージ用ラベル名
     * @param string $opts オプション
     * @return boolean バリデーションでエラーがない場合true, ある場合false
     */
    public function is_purchase_id($field, $val, $label)
    {
      $pattern = array(
		       'pattern' => '/\A(\d+)?\z/',
		       'pattern_name' => '整数',
		       );
      return $this->regExp($field, $val, $label, $pattern);
            
    }





}





