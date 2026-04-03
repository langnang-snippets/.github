<?php

//防止执行超时
set_time_limit(0);
//清空并关闭输出缓存
ob_end_clean();

// $filePath = __DIR__ . "/../querylist.langnang/storage/www_txt80_com/collected_content_fields.csv";
// $handle = fopen($filePath, "rb");
// $data = [];

// while (!feof($handle)) {
//   $data[] = fgetcsv($handle);
// }

// fclose($handle);
// // $data = iconv('gb2312', 'utf-8', var_export($data, true));
// $data = eval('return ' . iconv('gb2312', 'utf-8', var_export($data, true)) . ';');  //字符转码操作

if (isset($_POST['fileUrl']) && isset($_POST['fileName'])) {
  $_POST['fileUrl'] = urldecode($_POST['fileUrl']);
  $file = pathinfo($_POST['fileUrl']);
  $file['remoteurl'] = $_POST['fileUrl'];
  $file['filename'] = $_POST['fileName'];
  $file["localpath"] = __DIR__ . "/storage/" . $file['filename'] . "." . $file['extension'];
  var_dump($file);
  $remotefp = fopen($file['remoteurl'], 'rb');
  $localfp = fopen($file['localpath'], 'a'); //opens file in append mode  
  // fseek($remotefp, 0);

  while (!feof($remotefp)) {
    $chunk_size = 1024 * 1024 * 2;
    fwrite($localfp, fread($remotefp, $chunk_size));
    ob_flush();
    flush();
  }
  ob_end_clean();
  fclose($remotefp);
  fclose($localfp);
  // $file['content'] = file_get_contents($file['remoteurl']);
  // $file["encoding"] = mb_detect_encoding($file['content'], ['ASCII', 'UTF-8', 'GBK', 'GB2312']);
  if (in_array(strtolower($file['extension']), ['txt', 'pdf', 'md'])) {
    // 判断文件内容编码 ['UTF-8', 'GBK', 'GB2312']
    if ($file['encoding'] !== 'UTF-8') {
      file_put_contents($file['localpath'], iconv($file["encoding"], 'UTF-8', file_get_contents($file['localpath'])));
    }
  }
  var_dump($file);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Download</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="jumbotron text-center" style="background-color: #292D33;color: #FFF;">
    <h1><strong>PHP Download</strong></h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form method="post" name="config" action="?config">
          <div class="form-group">
            <label for="fileUrl">远程文件地址</label>
            <input class="form-control" type="text" name="fileUrl" value="<?php echo $file['remoteurl'] ?>">
          </div>
          <div class="form-group">
            <label for="fileName">文件名称</label>
            <input class="form-control" type="text" name="fileName" value="<?php echo $file['filename'] ?>">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">确认, 开始下载 »</button>
          </div>
        </form>
        <script>
          var fileUrl = document.config.fileUrl;
          var fileName = document.config.fileName;
          fileUrl.onchange = function() {
            filename_array = decodeURI(this.value).split(/\/|\.|\&|\=|\?/);
            fileName.value = filename_array[filename_array.length - 2];
          }
        </script>
      </div>
    </div>
  </div>
</body>

</html>