<?php
require_once dirname(__FILE__) . '/Abstract.php';

class Db_Dao_Summary extends Db_Dao_Abstract
{
    public function select_summary_by_date($from_date = NULL, $to_date = NULL)
    {
        $dbh = $this->getDbHandler();

        $query = 'SELECT date(created_at) AS date, SUM(purchase_price) as sales ' .
            'FROM user_purchase ' .
            'GROUP BY date(created_at) ';

        $having_from_to = 'HAVING created_at BETWEEN :FD AND :TD';
        $having_from = 'HAVING created_at >= :FD';
        $having_to = 'HAVING created_at <= :TD';

        if (!is_null($from_date) && !is_null($to_date)) {
            $query .= $having_from_to;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else if (!is_null($from_date)) {
            $query .= $having_from;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
        } else if (!is_null($to_date)) {
            $query .= $having_to;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else {
            $statement = $dbh->prepare($query);
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_sales_summary($from_date = NULL, $to_date = NULL)
    {
        $dbh = $this->getDbHandler();

        $query = 'SELECT SUM(purchase_price) as sales_summary ' .
            'FROM user_purchase ';
        $where_from_to = 'WHERE created_at BETWEEN :FD AND :TD';
        $where_from = 'WHERE created_at >= :FD';
        $where_to = 'WHERE created_at <= :TD';
        if (!is_null($from_date) && !is_null($to_date)) {
            $query .= $where_from_to;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else if (!is_null($from_date)) {
            $query .= $where_from;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
        } else if (!is_null($to_date)) {
            $query .= $where_to;
            $statement = $dbh->prepare($query);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else {
            $statement = $dbh->prepare($query);
        }
        $statement->execute();
        $ret = $statement->fetch(PDO::FETCH_ASSOC);
        return $ret['sales_summary'];
    }

    public function select_summary_by_content($category = NULL, $from_date = NULL, $to_date = NULL)
    {
        $dbh = $this->getDbHandler();

        $query = 'SELECT c.title, c.author, c.price, c.category, c.description, c.image_path, c.created_at, c.updated_at, SUM(purchase_price) as sales ' .
            'FROM user_purchase up INNER JOIN content c ON up.content_id = c.id ';
		if (!is_null($category)) $query .= 'WHERE c.category = :CATEGORY ';
        $query .= 'GROUP BY content_id ';
        $having_from_to = 'HAVING up.created_at BETWEEN :FD AND :TD';
        $having_from = 'HAVING up.created_at >= :FD';
        $having_to = 'HAVING up.created_at <= :TD';
        if (!is_null($from_date) && !is_null($to_date)) {
            $query .= $having_from_to;
            $statement = $dbh->prepare($query);
			if (!is_null($category)) $statement->bindValue(':CATEGORY', $category, PDO::PARAM_STR);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else if (!is_null($from_date)) {
            $query .= $having_from;
            $statement = $dbh->prepare($query);
			if (!is_null($category)) $statement->bindValue(':CATEGORY', $category, PDO::PARAM_STR);
            $statement->bindValue(':FD', $from_date, PDO::PARAM_STR);
        } else if (!is_null($to_date)) {
            $query .= $having_to;
            $statement = $dbh->prepare($query);
			if (!is_null($category)) $statement->bindValue(':CATEGORY', $category, PDO::PARAM_STR);
            $statement->bindValue(':TD', $to_date, PDO::PARAM_STR);
        } else {
            $statement = $dbh->prepare($query);
			if (!is_null($category)) $statement->bindValue(':CATEGORY', $category, PDO::PARAM_STR);
        }
        $statement->execute();
        $ret = $statement->fetchAll(PDO::FETCH_ASSOC);
        $sum = 0;
        foreach ($ret as $item) {
            $sum += $item['sales'];
        }
        return array(
            'summary_by_content' => $ret,
            'sales_summary' => $sum,
        );
    }

}
