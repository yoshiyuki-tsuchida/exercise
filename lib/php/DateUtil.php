<?php

class DateUtil
{
   public static function dateCompare($date1, $date2)
   {
      $day1 = strtotime($date1);
      $day2 = strtotime($date2);

      return abs($day1 - $day2) / 86400; //60s*60min*24h

   }

}



