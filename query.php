<?php

if(file_exists('export.xls')){
    unlink('export.xls');
}


$connection = @mysqli_connect("127.0.0.1","root","root");
mysqli_select_db($connection,"pm25");
$user=$_POST["UserId"];
$sday=$_POST["SDay"];
$eday=$_POST["EDay"];
$days = ((strtotime($eday) - strtotime($sday))/86400 + 1);

echo "Here is the result for " . $user . " from " . $sday . " to " . $eday . "<br>";
echo "Total " . $days . " days in our results<br>";

$userId="";
$idsql = "SELECT id FROM pm25.user where name = '$user';";
$idResult = mysqli_query($connection, $idsql);
while($row = mysqli_fetch_array($idResult)){
    $userId = $row['id'];
}


if($userId){
    $startDay=$sday . ' 00:00:00';
    $endDay=$eday . ' 23:59:59';
    $sqlstr1 = "Select * from data_mobile_new where userid=$userId and time_point >= '$startDay' and time_point <= '$endDay' order by time_point desc;";
    $sqlstr2 = "show columns from data_mobile_new;";

    $result1=mysqli_query($connection, $sqlstr1);
    $result_num=mysqli_num_rows($result1);

    echo $result_num . " records found, about " . number_format(($result_num)/(7.2*$days), 2) . "% data was recorded in these days";
    echo "<br><form class=\"form-start\" action=\"query.html\"><button type=\"submit\" id=\"return\">return</button></form>";

    echo "<form method=\"get\" action=\"export.xls\"><button type=\"submit\">Download the Excel File!</button></form>";

    $columns = 0;
    $index = 0;
    $data = array();


    require_once('./Classes/PHPExcel.php');
    $excel = new PHPExcel();
    $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q');
    $tableheader = array('id', 'userid', 'database_access_token', 'time_point', 'longitude', 'latitude', 'outdoor', 'status', 'steps', 'heart_rate', 'ventilation_rate', 'ventilation_vol', 'pm25_concen', 'pm25_intake', 'pm25_datasource', 'pm25_monitor', 'APP_version
');
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
    $write->save('export.xls');

    echo "</table>";
}else{
    echo "Didn't find this user";
}

?>


