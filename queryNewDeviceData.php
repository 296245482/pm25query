<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 2018/8/11
 * Time: 下午10:25
 */

// 常量值定义
$APPID = "d1O2GJQKCBDhuI3AbpkkdRzJufMa";
$SECRET = "fsNmKF1pWYLybmKWWxmtyBGsUc4a";
$BASE_URL = "https://180.101.147.89:8743";
$APP_AUTH = $BASE_URL . "/iocm/app/sec/v1.1.0/login";

// 获取accessToken
$post_data = array('appId' => $APPID, 'secret' => $SECRET);
function post($url,$data){
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}
echo post($APP_AUTH, $post_data);



// 查数据


// 写库
require('mysqlCon.php');
mysqli_query($connection,'set names gb2312');