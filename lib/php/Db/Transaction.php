<?php

  //require_once PROJECT_DIR . '/lib/php/Db/HandlerFactory.php';
require_once dirname(__FILE__) . '/HandlerFactory.php';

/**
 * Db_Transactionクラス
 *
 * @package lib/php/Db
 * 
 * @version $Id$
 */
class Db_Transaction
{
    const DEFAULT_DB_HANDLER_FACTORY_NAME = 'Db_HandlerFactory';

    /**
     * dbHandlerFactoryクラス名
     * 
     * @var string
     */
    protected $_dbHandlerFactoryName = self::DEFAULT_DB_HANDLER_FACTORY_NAME;

    /**
     * アクティブであるかどうか
     * 
     * @var bool
     */
    protected $_activeFlag = true;

    /**
     * データベースハンドラ
     * 
     * @var PDO
     */
    protected $_handler;

    /**
     * データベースハンドラの名前
     * 
     * @var string 
     */
    protected $_handlerName;

    /**
     * データベースハンドラファクトリーのクラス名を設定する
     * 
     * @param string $name 新しい名前
     */
    public function setDbHandlerFactoryName($name)
    {
        $this->_dbHandlerFactoryName = $name;
    }

    /**
     * トランザクションが生きているかを取得する
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->_activeFlag;
    }

    /**
     * トランザクションをコミットする
     * 
     */
    public function commit()
    {
        if ($this->_handler) {
            $this->_handler->commit();
        }
        $this->_activeFlag = false;
    }

    /**
     * トランザクションをロールバックする
     */
    public function rollback()
    {
        if ($this->_handler) {
            $this->_handler->rollBack();
        }
        $this->_activeFlag = false;
    }

    /**
     * データベースハンドラを取得する
     * o
     * @return PDO
     */
    public function getDbHandler()
    {
        if ($this->_handler) {
            return $this->_handler;
        }
        $methodName = $this->_dbHandlerFactoryName . '::getDemoDBHandler';
        $this->_handler = call_user_func($methodName);
        $this->_handlerName = 'demo';

        $this->_handler->beginTransaction();
        return $this->_handler;
    }
}
