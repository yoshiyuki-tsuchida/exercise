<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_Content extends Db_Dao_Abstract
{
    /**
     * 一件のレコードを連想配列で返す
     *
     * @return int レコード数
     */
    public function findById($content_id)
    {
        $dbh = $this->getDbHandler();

        $query  = "SELECT * FROM content WHERE id = ?";
        $bind = array($content_id);

        $statement = $dbh->prepare($query);
        $statement->execute($bind);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 全レコード数を取得する
     *
     * @return int レコード数
     */
    public function countAll()
    {
        $dbh = $this->getDbHandler();
        $query  = "SELECT count(id) cnt FROM content";
        $statement = $dbh->prepare($query);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return intval($result['cnt']);
    }

    /**
     * 作成日が新しい順でコンテンツ情報の一覧を取得する
     *
     * @param int $offset 取得位置
     * @param int $limit 取得件数
     */
    public function findLatestList($offset = 0, $limit = 5)
    {
        $dbh = $this->getDbHandler();
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

        $query = "SELECT id, title, author, price, image_path, description, category FROM content ORDER BY created_at DESC LIMIT :OFFSET, :LIMIT";
        $statement = $dbh->prepare($query);
        $statement->bindValue(':OFFSET', $offset, PDO::PARAM_INT);
        $statement->bindValue(':LIMIT', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
