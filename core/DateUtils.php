<?php


class Core_DateUtils
{

    function diffDateDhis($startDate, $endDate)
    {
        $date = floor((strtotime($endDate) - strtotime($startDate)) / 86400);
        $vHour = (strtotime($endDate) - strtotime($startDate)) % 86400 / 3600;
        list($hour, $vMinute) = explode('.', $vHour);
        $minute = ceil(substr($vMinute, 0, 2) / 100 * 60);
        $second = ceil(substr($vMinute, 2, 4) / 10000 * 60);//*warning 秒计算有问题
        $return = array($date, $hour, $minute, $second);
        return $return;
    }
    function diffDateHis($startDate, $endDate)
    {
        list($date, $hour, $minute, $second) = $this->diffDateDhis($startDate, $endDate);
        $hour += $date * 24;
        $return = array($hour, $minute, $second);
        return $return;
    }
}