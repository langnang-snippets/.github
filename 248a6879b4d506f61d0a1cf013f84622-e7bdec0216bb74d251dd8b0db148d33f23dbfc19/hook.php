<?php
/**
 * 该文件默认放置于仓库根目录
 */

// 密钥
$secret="123456";

// 检测函数是否被禁用
if(!function_exists("shell_exec")){
	echo "shell_exec() has been disabled for security reasons";
	return;
}

// 获取webhook发送过来的签名
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];

// 检测签名
if (!$signature) {
	echo "HTTP_X_HUB_SIGNATURE has been disabled for security reasons";
	return;
}

// 获取webhook发送的内容
$json = json_decode(file_get_contents('php://input'),true);

$path = __DIR__;

// 获取本地分支
$HEAD = file_get_contents($path."/.git/HEAD");
$local_ref = substr($HEAD, 5, strlen($HEAD) - 6);

// 获取webhook分支
$origin_ref=$json["ref"];
// 匹配分支
if($local_ref==$origin_ref){
	$out = shell_exec("cd $path && git pull 2>&1");
	echo ($out);
	return;
}else{
	echo "refs matching failure";
	return;
}