<?php
//require_once PROJECT_DIR . '/lib/php/db/HandlerFactory.php';
//require_once PROJECT_DIR . '/lib/php/db/Transaction.php';
require_once dirname(__FILE__) . '/../HandlerFactory.php';
require_once dirname(__FILE__) . '/../Transaction.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Db_Dao_Abstractクラス
 *
 * @package Db
 * 
 * @version $Id$
 */
abstract class Db_Dao_Abstract
{

    /**
     * ハンドラ使い回し用プール
     * 
     * @var array 
     */
    protected $handlerPool;

    /**
     * トランザクション
     * @var Db_Transaction
     */
    protected $_transaction;

    /**
     * データベースハンドラを取得する
     *  
     * @return PDO
     * @throws RuntimeException
     */
    public function getDbHandler()
    {
        if ($this->isInTransaction()) {
            return $this->_transaction->getDbHandler();
        }
        $handlerName = 'demo';
        if (!isset($this->handlerPool[$handlerName])) {
            $methodName = 'Db_HandlerFactory::getDemoDBHandler';
            $this->handlerPool[$handlerName] = call_user_func($methodName);
        }
        return $this->handlerPool[$handlerName];
    }

    /**
     * トランザクションが有効かを返す
     *
     * @return bool
     */
    public function isInTransaction()
    {
        if ($this->_transaction) {
            if ($this->_transaction->isActive()) {
                return true;
            }
            // トランザクションがアクティブでなければ
            // もう不要なのでnullをセットする
            $this->_transaction = null;
        }

        return false;
    }

    /**
     * トランザクションを利用する
     * 
     * @param Db_Transaction $transaction
     */
    public function useTransaction(Db_Transaction $transaction)
    {
        $this->_transaction = $transaction;
    }
}