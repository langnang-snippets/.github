<?php

$dir_path = __DIR__ . "/images/";

if (!is_dir($dir_path)) {
  mkdir($dir_path);
}
$file_urls = file_get_contents("file_urls.txt");
$file_names = file_get_contents("file_names.txt");
$file_urls = str_replace(array("\r\n", "\r", "\n", "/\r|\n|\t/"), ";", $file_urls);
$file_urls = explode(";", $file_urls);

$file_names = str_replace(array("\r\n"), ";", $file_names);
$file_names = explode(";", $file_names);

// var_dump($file_urls);
// $file_name = basename($file_url);

// $content = file_get_contents($file_url);

// file_put_contents($dir_path . $file_name, $content);

set_time_limit(0);  //设置程序执行时间
ignore_user_abort(true);    //设置断开连接继续执行
header('X-Accel-Buffering: no');    //关闭buffer
header('Content-type: text/html;charset=utf-8');    //设置网页编码
ob_start(); //打开输出缓冲控制
echo str_repeat(' ', 1024 * 4);    //字符填充
$width = 1000;
$html = '
<div style="margin:100px auto; padding: 8px; border: 1px solid gray; background: #EAEAEA; width: %upx">
  <div style="padding: 0; background-color: white; border: 1px solid navy; width: %upx">
    <div id="progress" style="padding: 0; background-color: #FFCC66; border: 0; width: 0px; text-align: center; height: 16px">
    </div>
  </div>
  <div id="msg" style="font-family: Tahoma; font-size: 9pt;">
    正在处理...
  </div>
  <div id="percent" style="position: relative; top: -34px; text-align: center; font-weight: bold; font-size: 8pt">0%%</div>
</div>
';
echo sprintf($html, $width + 8, $width);
echo ob_get_clean();    //获取当前缓冲区内容并清除当前的输出缓冲
flush();   //刷新缓冲区的内容，输出
$length = sizeof($file_names);
foreach ($file_names as $i => $file) {
  $proportion = ($i + 1) / $length;

  // var_dump($file_info);
  // var_dump($file);
  if ($i + 1 == $length) {
    $msg = '下载完成';
  } else {
    $msg = '正在下载第' . ($i + 1) . "/$length" . '张图片';
  }
  $script = '<script>document.getElementById("percent").innerText="%u%%";document.getElementById("progress").style.width="%upx";document.getElementById("msg").innerText="%s";</script>';
  echo sprintf($script, $proportion * 100, ($i + 1) / $length * $width, $msg);


  $file = explode("\t", $file);
  $file_info = pathinfo($file[0]);
  $file_info["rename"] = $file[1];
  $content = file_get_contents($file[0]);

  $index = null;
  if (file_exists($dir_path . $file[1] . "." . $file_info["extension"])) {
    $index = 1;
  } else {
    file_put_contents($dir_path . $file[1] . "." . $file_info["extension"], $content);
  }

  if (!is_null($index)) {
    while (file_exists($dir_path . $file[1] . "（" . $index . "）." . $file_info["extension"])) {
      $index++;
    }
    file_put_contents($dir_path . $file[1] . "（" . $index . "）." . $file_info["extension"], $content);
  }


  echo ob_get_clean();    //获取当前缓冲区内容并清除当前的输出缓冲
  flush();   //刷新缓冲区的内容，输出
}
