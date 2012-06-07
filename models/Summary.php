<?php
require_once dirname(__FILE__) . '/Model.php';
require_once dirname(__FILE__) . '/../lib/php/Db/Dao/Summary.php';

class Summary extends Model
{
    public function getSalesSummaryByDate($from_date = NULL, $to_date = NULL)
    {
        $summary_dao = $this->getFactory()->getDb_Dao_Summary();
	//	var_dump($summary_dao);
        $summary = $summary_dao->select_summary_by_date($from_date, $to_date);

        return $summary;
    }

    public function getSalesSummary($from_date = NULL, $to_date = NULL)
    {
        $summary_dao = $this->getFactory()->getDb_Dao_Summary();
        $summary = $summary_dao->select_sales_summary($from_date, $to_date);

        return $summary;
    }

    public function getSalesSummaryByContent($category = NULL, $from_date = NULL, $to_date = NULL)
    {
        $summary_dao = $this->getFactory()->getDb_Dao_Summary();
        $summary = $summary_dao->select_summary_by_content($category, $from_date, $to_date);

        return $summary;
    }
}
