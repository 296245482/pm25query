<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2018/4/26
 * Time: 下午8:20
 */
require ('mysqlCon.php');
mysqli_query($connection,'set names gb2312');

// urban air网站上的cityID
// 注：和服务器上urban_air_station中ID一致，与urban_air中不一致
// 武汉，宁波
$cityID = [3,5];
$cityIndex = 0;
$stationIndex = 0;
$stationIDs = array();
$stationLats = array();
$stationLngs = array();
while($cityIndex < sizeof($cityID)) {
    $city = $cityID[$cityIndex];
    $sqlStaIDs = "SELECT station_id,latitude,longitude FROM pm25.urban_air_station WHERE city = $city;";
    $staIDsResult = mysqli_query($connection, $sqlStaIDs);
    while($row = mysqli_fetch_assoc($staIDsResult)){
        $stationIDs[$stationIndex] = $row['station_id'];
        $stationLats[$stationIndex] = $row['latitude'];
        $stationLngs[$stationIndex] = $row['longitude'];
        $stationIndex++;
    }
    $cityIndex ++;
}

$insertIndex = 0;
date_default_timezone_set('PRC');
while($insertIndex < sizeof($stationIDs)){
    $handle = fopen("http://urbanair.msra.cn/U_Air/SearchGeoPoint?Culture=zh-CN&Standard=0&longitude=$stationLngs[$insertIndex]&latitude=$stationLats[$insertIndex]","rb");

    $content = "";
    while (!feof($handle)) {
        $content .= fread($handle, 10000);
    }
    fclose($handle);
    $content = json_decode($content);


    $stationId=$stationIDs[$insertIndex];
    $latitude = $stationLats[$insertIndex];
    $longitude = $stationLngs[$insertIndex];
    $aqi = $content->AQI;
    $pm25 = $content->PM25;
    $pm10 = $content->PM10;
    $no2 = $content->NO2;
    $co = $content->CO;
    $o3 = $content->O3;
    $so2 = $content->SO2;
    $update_time = $content->UpdateTime;
    $update_time = date("Y-m-d H:i:s", strtotime($update_time));
    $timePoint = date("Y-m-d H:i:s");
    $sqlRecord = "INSERT INTO 
pm25.urban_air_station_data(station_id, latitude, longitude, aqi, pm25, pm10, no2, co, o3, so2, update_time, time_point) 
VALUES ('$stationId', '$latitude','$longitude',$aqi,'$pm25','$pm10','$no2','$co','$o3','$so2','$update_time','$timePoint');";

//    echo $sqlRecord;
    mysqli_query($connection, $sqlRecord);
    $insertIndex ++;



}