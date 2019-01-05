<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2018/10/25
 * Time: 10:54 AM
 */

if(file_exists('Bio3GearV2Export.xls')){
    unlink('Bio3GearV2Export.xls');
}

require('mysqlCon.php');

//$DeviceId=$_POST["DeviceId"];

$DeviceId = "PM_SH8";

if($DeviceId){
    $sqlstr1 = "Select * from Bio3GearV2_Data where shortDeviceId='$DeviceId' order by timePoint desc;";
    $sqlstr2 = "show columns from Bio3GearV2_Data;";

    $result1=mysqli_query($connection, $sqlstr1);
    $result_num=mysqli_num_rows($result1);
//echo $result_num;
//    $percent = ($result_num)/(7.2*$days);
//    if(!$userType){
//        $percent = $percent / 2;
//    }
//    echo $result_num . " records found, about " . number_format($percent, 2) . "% data was recorded in these days";
    echo "<br><form class=\"form-start\" action=\"Bio3GearV2Query.html\"><button type=\"submit\" id=\"return\">return</button></form>";

    echo "<form method=\"get\" action=\"Bio3GearV2Export.xls\"><button type=\"submit\">Download the Excel File!</button></form>";

    $columns = 0;
    $index = 0;
    $data = array();


    require_once('./Classes/PHPExcel.php');
    $excel = new PHPExcel();
    $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
    $tableheader = array('id', 'deviceId', 'pm25', 'temp', 'RH', 'CO', 'CO2', 'NO2', 'O3', 'VOC', 'timePoint', 'shortDeviceId');
    for($i = 0; $i < count($tableheader); $i++) {
        $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
    }

    if($result_num){
        echo "result found";
        echo "<table id='data' ><tr>";

        $field = mysqli_query(@$connection, $sqlstr2);
        while($row = mysqli_fetch_array($field)){
            echo "<th>" . $row['Field'] . "</th>";
            $columns += 1;
        }

        echo "</tr>";

        while($row = mysqli_fetch_assoc($result1)){
            foreach ( $row as $key => $val){
                $data[$index] = $val;
                $index += 1;
            }
        }

        $subIndex = 0;
        $excelRowIndex = 2;
        for($i = 0; $i < count($data);) {
            echo "<tr>";
            $excelIndex = 0;
            for($j = 0; $j < $columns; $j++) {
                echo "<td>";
                echo $data[$subIndex];
                $excel->getActiveSheet()->setCellValue("$letter[$excelIndex]$excelRowIndex","$data[$subIndex]");
                $subIndex += 1;
                $i++;
                echo "</td>";
                $excelIndex ++;
            }
            $excelRowIndex ++;
            echo "</tr>";
        }
    }else{
        echo "No Data found";
    }

    $write=new PHPExcel_Writer_Excel5($excel);
    $write->save('Bio3GearV2Export.xls');

    echo "</table>";
}else{
    echo "Didn't find this device";
    echo "<br><form class=\"form-start\" action=\"Bio3GearV2Query.html\"><button type=\"submit\" id=\"return\">return</button></form>";
}


?>