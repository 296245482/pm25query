<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2017/10/31
 * Time: 下午2:40
 */

$connection = @mysqli_connect("127.0.0.1","root","root");
//echo $connection;
mysqli_select_db($connection,"pm25");