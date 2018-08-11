<?php
/**
 * Created by vscode.
 * User: jackie
 * Date: 2019/08/11
 * Time: 下午8:18
 */

require('mysqlCon.php');
mysqli_query($connection,'set names gb2312');

$yestoday = date('Y-m-d',strtotime('+'.($template-1).' days')).' 12:00:00';
$cityID = [4,6];
$cityIndex = 0;
$stationIndex = 0;
$cityRecordCount = array();
while($cityIndex < sizeof($cityID)) {
    $city = $cityID[$cityIndex];
    $sql = "select count(*) from pm25.urban_air where city = $city and time_point > '$yestoday';";
    $result = mysqli_query($connection,$sql);
    $rows = mysqli_fetch_row($result);
    $cityRecordCount[$cityIndex] = $rows[0];
    $cityIndex ++;
}

$allResultsStr = "";
$allResultsStr = $allResultsStr . "The data missing percent for the last day are: <br>";
$allResultsStr = $allResultsStr . "1. Wuhan:    " . (1 - $cityRecordCount[0]/734400) * 100 . "% <br>";
$allResultsStr = $allResultsStr . "2. Shiyan:   " . (1 - $cityRecordCount[1]/777600) * 100 . "% <br>";

//echo $allResultsStr;


// 发邮件
$to = "bio3air.test@hotmail.com, 296245482@qq.com";
$subject = "UrbanAir data MissingPercent for the last day";
$from = "296245482@qq.com";
//$headers = "From: $from";
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=gb2312' . "\r\n";
$headers .= 'From: ' . $from . "\r\n";
mail($to,$subject,$allResultsStr,$headers);
//echo "Mail Sent.";