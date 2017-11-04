<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2017/10/31
 * Time: 下午4:22
 */
//error_reporting( E_ALL&~E_NOTICE );
require('mysqlCon.php');
mysqli_query($connection,'set names gb2312');

$ID = 1;

// 一个管理员的账号list
$sqlIDs = "SELECT user_id FROM pm25.manager_user where manager_id = $ID;";
$IDresults = mysqli_query($connection, $sqlIDs);
$IDdata = array();
$index = 0;
while($row = mysqli_fetch_assoc($IDresults)){
    $IDdata[$index] = $row['user_id'];
//    echo $IDdata[$index].'<br>';
    $index ++;
}


// 所有人，存入data
$sqlAll = "SELECT * FROM
      (SELECT user.id, name, email, firstname, lastname, 
        phone, last_login, max(time_point) as last_upload 
       FROM user left join data_mobile_new
       ON data_mobile_new.userid=user.id
       GROUP BY user.id) as t
    WHERE TIMESTAMPDIFF(HOUR, last_upload, now())>24 OR last_upload is NULL
    ORDER BY last_upload DESC;";
$allResults = mysqli_query($connection, $sqlAll);
$data = array();
$index = 0;
while($row = mysqli_fetch_assoc($allResults)){
    foreach ( $row as $key => $val){
        $data[$index] = $val;
//        echo $data[$index].'<br>';
        $index += 1;
    }
}


$allResultsStr = "";
$allResultsStr = $allResultsStr . "Your unrunning list is shown below:<br>";
$allResultsStr = $allResultsStr . "<table id='data' border=\"1\">";
$allResultsStr = $allResultsStr . "<tr><td>ID</td><td>name</td><td>email</td><td>first name</td><td>last name</td><td>phone</td><td>last login</td><td>last upload</td></tr>";

$subIndex = 0;
for($i = 0; $i < count($data);) {
//    echo $i." ".$data[$i]." - ";
    $firstIndex = $i;
    if(in_array($data[$firstIndex], $IDdata)){
        $allResultsStr = $allResultsStr . "<tr>";
        for($j = 0; $j < 8; $j++) {
            $allResultsStr = $allResultsStr .  "<td>";
            $allResultsStr = $allResultsStr .  $data[$subIndex];
            $subIndex += 1;
            $i++;
            $allResultsStr = $allResultsStr .  "</td>";
        }
        $allResultsStr = $allResultsStr .  "</tr>";
    }else{
        $i+=8;
        $subIndex += 8;
    }
}

$allResultsStr = $allResultsStr .  "</table>";
//echo $allResultsStr;

// 发邮件
$to = "965805291@qq.com";
$subject = "Bio3Air unrunning list";
$from = "296245482@qq.com";
//$headers = "From: $from";
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=gb2312' . "\r\n";
$headers .= 'From: ' . $from . "\r\n";
mail($to,$subject,$allResultsStr,$headers);
//echo "Mail Sent.";