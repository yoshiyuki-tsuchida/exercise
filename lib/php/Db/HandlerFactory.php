<?php
/**
 *
 * @package  lib/php/Db
 *
 * @version $_ID
 */
class Db_HandlerFactory
{
    const MYSQL_SERVER_HAS_GONE_AWAY_MESSAGE = 'MySQL server has gone away';

    /**
     * シングルトンインスタンス
     */
    private static $obj = null;

    /**
     * DSN+User単位でPDOをプールする入れ物
     *
     * @var array
     */
    protected static $_pdoPool = array();

    /**
     * コンストラクタ
     *
     * @return void
     */
    private function __construct()
    {
      return true;
    }

    /**
     * シングルトンインスタンスを返却する.
     *
     * @return void
     */
    private static function getInstance()
    {
        return self::$obj ? self::$obj : self::$obj = new self;
    }

    /**
     * DB設定からDBハンドラを返す
     *
     * @return PDO DBハンドラ
     */
    private static function _makeDBH()
    {
        require dirname(__FILE__) . '/../../../config/database.php';
        $db_config = $db[$active_group];
        $dsn = 'mysql:dbname=' . $db_config['dbname']
            . ';host=' . $db_config['hostname']
            . ';port=' . $db_config['port'];

        // DSNとユーザー名に基づいてPDOをプールする
        $key = $dsn . ';' . $db_config['user'];

        if (!self::hasAvaiableConnectionForKey($key)) {
            self::$_pdoPool[$key] = new PDO($dsn, $db_config['user'], $db_config['password']);
        }

        return self::$_pdoPool[$key];
    }

    /**
     * キーに対して有効なDB接続がプールされていることを確認する
     *
     * @param string $key
     */
    private static function hasAvaiableConnectionForKey($key)
    {
        return isset(self::$_pdoPool[$key])
            && self::$_pdoPool[$key]->getAttribute(PDO::ATTR_SERVER_INFO) != self::MYSQL_SERVER_HAS_GONE_AWAY_MESSAGE;
    }

    /**
     * DBハンドラ
     *
     * @return PDO DBハンドラ
     */
    public static function getDemoDBHandler()
    {
        $dbh = self::getSingleHandler();
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }

    /**
     * 登録された単一のDBを返す
     *
     * @return PDO DBハンドラ
     */
    protected static function getSingleHandler()
    {
        $dbh = self::_makeDBH();
        if (!empty($dbh)) {
            return $dbh;
        }
        return null;
    }
}
