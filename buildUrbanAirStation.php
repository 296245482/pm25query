<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2018/4/27
 * Time: 下午3:20
 */
require ('mysqlCon.php');
mysqli_query($connection,'set names gb2312');

// urban air网站上的cityID
// 注：和服务器上ID不一致
// 武汉，宁波
$cityID = 5;

$handle = fopen("http://urbanair.msra.cn/U_Air/ChangeCity?CityId=005&Standard=0","rb");
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);
//echo $content;
$content = json_decode($content);


$index = 0;


while($index < sizeof($content->ENName)){
    $cityId = "5";
    $city_name = "宁波";
    $stationENName = $content->ENName[$index];
    $stationCNName = $content->CNName[$index];
    $stationId = $cityId.($index+1);
    $latitude = $content->Stations[$index]->lat;
    $longitude = $content->Stations[$index]->lng;
    $sqlRecord = "INSERT INTO pm25.urban_air_station(city,city_name, station_enname,station_cnname,station_id, latitude, longitude) 
VALUES ('$cityId', '$city_name','$stationENName','$stationCNName','$stationId', '$latitude','$longitude');";

    echo $sqlRecord;
//    mysqli_query($connection, $sqlRecord);


    $index++;
}