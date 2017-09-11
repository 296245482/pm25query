<?php
$connection = @mysqli_connect("127.0.0.1","root","root");
mysqli_select_db($connection,"pm25");
$user=$_POST["UserId"];
$sday=$_POST["SDay"];
$eday=$_POST["EDay"];

echo "This is the result for " . $user . " from " . $sday . " to " . $eday . "<br>";
echo "Total " . ((strtotime($eday) - strtotime($sday))/86400 + 1) . " days in our results";

echo "<form class=\"form-start\" action=\"query.html\"><button type=\"submit\" id=\"return\">return</button></form>";


$sday=$sday . ' 00:00:00';
$eday=$eday . ' 23:59:59';

$sqlstr1 = "Select * from data_mobile_new where userid=$user and time_point >= '$sday' and time_point <= '$eday' order by time_point desc;";
$sqlstr2 = "show columns from data_mobile_new;";

$result1=mysqli_query($connection, $sqlstr1);

$columns = 0;
$index = 0;
$data = array();



if(!$result1){
    echo "No Data found";
}else{

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

    for($i = 0; $i < count($data);) {
        echo "<tr>";

        for($j = 0; $j < $columns; $j++) {
            echo "<td>";

            echo $data[$subIndex];
            $subIndex += 1;
            $i++;

            echo "</td>";
        }

        echo "</tr>";

    }
}



echo "</table>";
?>


