<?php
class Date
{
  public static function relative($time, $dateformat = 'd.m.Y, H:i')
  {
    $peroid = array('minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800);
    $time = time()-strtotime($time);

    if(($time/$peroid['minute']) < 60)
    {
      if(round($time/$peroid['minute']) == 1)
          return 'minutę temu';

      if(round($time/$peroid['minute']) >= 2 and round($time/$peroid['minute']) <= 4)
          return round($time/$peroid['minute']) .' minuty temu';

      return round($time/$peroid['minute']) . ' minut temu';
    }

    elseif(($time/$peroid['hour']) < 24)
    {
      if(round($time/$peroid['hour']) == 1)
          return 'godzinę temu';

      if(round($time/$peroid['hour']) >= 2 and round($time/$peroid['hour']) <= 4)
          return round($time/$peroid['hour']) . ' godziny temu';

      return round($time/$peroid['hour']) . ' godzin temu';
    }

    elseif(($time/$peroid['day']) < 7)
    {
      if(floor(($time/$peroid['day'])) == 1)
          return 'wczoraj';

      if(floor(($time/$peroid['day'])) == 2)
          return 'przedwczoraj';

      return floor(($time/$peroid['day'])) . ' dni temu';
    }

    elseif(($time/$peroid['week']) < 4)
    {
      if(floor(($time/$peroid['week'])) == 1)
          return 'tydzień temu';

      return floor(($time/$peroid['week'])) . ' tygodnie temu';
    }
    else
    {
      return date($dateformat, time()+$time);
    }
  }

  /* Convert count secund to text time */
  public static function secund($sec)
  {
    $time = round($sec);
    return sprintf('%02d godz. %02d min. %02d sek.', ($time/3600),($time/60%60), $time%60);
  }

}
?>
