<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2017/10/31
 * Time: 下午1:48
 */


session_start();
if(empty($_SESSION['userinfo']['uemail'])){
    $user=$_POST["UserId"];
    $password=$_POST["Password"];
}else{
    $user = $_SESSION['userinfo']['uemail'];
    $password=$_SESSION['userinfo']['upass'];
}



require('mysqlCon.php');
$DBpassword = '';
$DBPDsql = "select password from pm25.manager where email = '$user';";
$DBpasswordResult = mysqli_query($connection, $DBPDsql);
while($row = mysqli_fetch_array($DBpasswordResult)){
    $DBpassword = $row['password'];
}
if($DBpassword == md5($password)){
    $_SESSION['userinfo'] = [
        'uemail' => $user,
        'upass' => $password
    ];



    echo "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Bio3Air Query</title>
</head>
<body>
<div id=\"main\">
    <div id=\"dynamic\">
        <form class=\"form-start\" action=\"query.php\" method=\"post\">
            <br>
            输入ID以及开始结束时间，下载该段时间内该用户数据
            <br><br>
            用户名:
            <input type=\"text\" name=\"UserId\" id=\"UserId\" placeholder=\"请输入用户名\">
            <br><br>
            开始日期:
            <input type=\"date\" name=\"SDay\" id=\"SDay\">
            结束日期:
            <input type=\"date\" name=\"EDay\" id=\"EDay\">
            <br><br>
            <button type=\"submit\" id=\"connect\">查询</button>
        </form>
    </div>

</div>

</body>

";
}else{
    echo "login error, please recheck your account<br><br><form class=\"form-start\" action=\"query.html\"><button type=\"submit\" id=\"return\">RETURN TO LOGIN</button></form>";
};
echo "</html>";




