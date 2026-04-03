<?php
header('X-Accel-Buffering: no');

require_once __DIR__ . "/vendor/autoload.php";

use QL\QueryList;
//防止执行超时
set_time_limit(0);
//清空并关闭输出缓存
ob_end_clean();

if (!file_exists(__DIR__ . "/config.inc.php")) exit;

$log_path = "/storage";
$config = require_once __DIR__ . "/config.inc.php";

if (!isset($_GET['slug']) || empty($_GET['slug']) || empty($config[$_GET['slug']])) exit;
$slug = $_GET['slug'];
$config = $config[$slug];
$config['slug'] = $slug;
$config['log_path'] = "$log_path/$slug";
// 基础配置
if (isset($_GET['restart'])) {
}
// 检测文件夹是否存在，不存在则创建
if (!file_exists(__DIR__ . $config['log_path'] . "/collected_contents")) {
  $res = array_reduce(explode('/', $config['log_path'] . "/collected_contents"), function ($parent, $dir) {
    if (!empty($parent)) {
      $dir = $parent . "/" . $dir;
    }
    if (!file_exists(__DIR__ . '/' . $dir)) mkdir(__DIR__ . '/' . $dir);
    return $dir;
  }, '');
}
// 待请求页面的 url
$collect_scan_urls = $config['scan_urls'];
$collected_scan_urls_num = 0;
if (file_exists(__DIR__ . $config['log_path'] . "/collected_scan_urls.txt") && ($fp = fopen(__DIR__ . $config['log_path'] . "/collected_scan_urls.txt", "r")) !== FALSE) {
  while (fgetcsv($fp) !== FALSE) {
    $collected_scan_urls_num++;
  }
}
// 待请求列表页的 url
$collect_list_urls = [];
if (file_exists(__DIR__ . $config['log_path'] . "/collect_list_urls.txt"))
  $collect_list_urls = explode("\n", file_get_contents(__DIR__ . $config['log_path'] . "/collect_list_urls.txt"));

$collected_list_urls_num = 0;
if (file_exists(__DIR__ . $config['log_path'] . "/collected_list_urls.txt") && ($fp = fopen(__DIR__ . $config['log_path'] . "/collected_list_urls.txt", "r")) !== FALSE) {
  while (fgetcsv($fp) !== FALSE) {
    $collected_list_urls_num++;
  }
}
// 待请求内容页的 url
$collect_content_urls = [];
if (file_exists(__DIR__ . $config['log_path'] . "/collect_content_urls.txt"))
  $collect_content_urls = explode("\n", file_get_contents(__DIR__ . $config['log_path'] . "/collect_content_urls.txt"));
$collected_content_urls_num = 0;
if (file_exists(__DIR__ . $config['log_path'] . "/collected_contents")) {
  $collected_content_urls_num = sizeof(scandir(__DIR__ . $config['log_path'] . "/collected_contents")) - 2;
}

// 已抽取内容页
$collected_content_fields_num = 0;
if (file_exists(__DIR__ . $config['log_path'] . "/collected_content_fields.csv"))
  $collected_content_fields_num = sizeof(explode("\n", file_get_contents(__DIR__ . $config['log_path'] . "/collected_content_fields.csv"))) - 1;


function get_scan_url($url, $html = null)
{
  if (empty($url)) return;
  global $config;
  $url_parse = parse_url($url);
  if (in_array($url_parse['host'], $config['domains'])) {
    // 请求页面
    if (empty($html)) $html = QueryList::get($url);

    $links = $html->find('a')->attrs('href')->all();
    foreach ($links as $link) {
      $link_parse = parse_url($link);
      // 匹配域名: 存在域名且域名不在域名数组中，结束本次循环
      if (isset($link_parse['host']) && !in_array($link_parse['host'], $config['domains'])) continue;
      $link_parse["scheme"] = isset($link_parse["scheme"]) ? $link_parse["scheme"] : $url_parse['scheme'];
      $link_parse["host"] = isset($link_parse["host"]) ? $link_parse["host"] : $url_parse['host'];
      $link = $link_parse["scheme"] . "://" . $link_parse["host"] . $link_parse["path"];
      // 匹配列表页
      get_list_url($link);
      // 匹配内容页
      get_content_url($link);
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Querylist</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="jumbotron text-center" style="background-color: #292D33;color: #FFF;">
    <h1><strong>Querylist</strong></h1>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered table-hover text-center">
          <caption><?php echo $config['name'] ?></caption>
          <thead>
            <tr>
              <th class="text-center">入口页</th>
              <th class="text-center">列表页</th>
              <th class="text-center">内容页</th>
              <th class="text-center">抽取页</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <span name="collected_scan_urls"><?php echo $collected_scan_urls_num ?></span>
                /
                <span name="collect_scan_urls"><?php echo sizeof($collect_scan_urls) ?></span>
              </td>
              <td>
                <span name="collected_list_urls"><?php echo $collected_list_urls_num ?></span>
                /
                <span name="collect_list_urls"><?php echo sizeof($collect_list_urls) ?></span>
              </td>
              <td>
                <span name="collected_content_urls"><?php echo $collected_content_urls_num ?></span>
                /
                <span name="collect_content_urls"><?php echo sizeof($collect_content_urls) ?></span>
              </td>
              <td>
                <span name="collected_content_fields"><?php echo $collected_content_fields_num ?></span>
              </td>
            </tr>
          </tbody>
        </table>
        <ul>
          <li>https://packagist.org/packages/jaeger/querylist</li>
          <li><a href="https://github.com/jae-jae/QueryList/blob/HEAD/README-ZH.md">Querylist</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php
  function get_list_url($link)
  {
    if (empty($link)) return;
    global $config, $collect_list_urls;
    foreach ($config['list_url_regexes'] as $list_url_regex) {
      $list_url_regex_parse = parse_url($list_url_regex);
      $list_url_regex = "/^" . $list_url_regex_parse["scheme"] . ":\/\/" . preg_quote($list_url_regex_parse["host"]) . "\\" . $list_url_regex_parse["path"] . "$/";
      if (preg_match($list_url_regex, $link) && !in_array($link, $collect_list_urls)) {
        array_push($collect_list_urls, $link);
        echo "<script>$('[name=collect_list_urls]').text(" . sizeof($collect_list_urls) . ")</script>";
        $fp = fopen(__DIR__ . "{$config['log_path']}/collect_list_urls.txt", 'a'); //opens file in append mode  
        fwrite($fp, $link . "\n");
        fclose($fp);
        flush();
        break;
      }
    }
  }
  function get_content_url($link)
  {
    if (empty($link)) return;
    global $config, $collect_content_urls;
    foreach ($config['content_url_regexes'] as $content_url_regex) {
      $content_url_regex_parse = parse_url($content_url_regex);
      $content_url_regex = "/" . $content_url_regex_parse["scheme"] . ":\/\/" . preg_quote($content_url_regex_parse["host"]) . "\\" . $content_url_regex_parse["path"] . "/";
      if (preg_match($content_url_regex, $link) && !in_array($link, $collect_content_urls)) {
        array_push($collect_content_urls, $link);
        echo "<script>$('[name=collect_content_urls]').text(" . sizeof($collect_content_urls) . ")</script>";
        $fp = fopen(__DIR__ . "{$config['log_path']}/collect_content_urls.txt", 'a'); //opens file in append mode  
        fwrite($fp, $link . "\n");
        fclose($fp);
        flush();
        flush();
        break;
      }
    }
  }
  function get_content_fields($link)
  {
    if (empty($link)) return;
    global $config;
    if (file_exists(__DIR__ . $config['log_path'] . "/collected_contents/" . basename($link))) {
      var_dump($link);
      $html = file_get_contents(__DIR__ . $config['log_path'] . "/collected_contents/" . basename($link));
      $html = QueryList::html($html);
    } else {
      $html = QueryList::get($link);
      file_put_contents(__DIR__ . $config['log_path'] . "/collected_contents/" . basename($link), $html->getHtml());
    }
    get_scan_url($link, $html);
    $content = [];
    try {
      foreach ($config['fields'] as $field) {
        if (!empty($field['find']) && !empty($field['func']))
          $content[$field['name']] = $html->find($field['find'])->{$field['func']}($field['args']);
      }
    } catch (Exception $e) {
      return false;
    }
    return $content;
  }
  while ($collected_scan_urls_num < sizeof($collect_scan_urls)) {
    $url = $collect_scan_urls[$collected_scan_urls_num];
    get_scan_url($url);
    echo "<script>$('[name=collected_scan_urls]').text(" . ($collected_scan_urls_num + 1) . ")</script>";
    $fp = fopen(__DIR__ . "{$config['log_path']}/collected_scan_urls.txt", 'a'); //opens file in append mode  
    fwrite($fp, $url . "\n");
    fclose($fp);
    flush();
    $collected_scan_urls_num++;
  }
  while ($collected_content_urls_num < sizeof($collect_content_urls)) {
    $url = $collect_content_urls[$collected_content_urls_num];
    echo "<script>$('[name=collected_content_urls]').text(" . ($collected_content_urls_num + 1) . ")</script>";
    $fields = get_content_fields($url);
    $fp = fopen(__DIR__ . "{$config['log_path']}/collected_content_fields.csv", 'a'); //opens file in append mode  
    if ($collected_content_urls_num == 0) {
      fputcsv($fp, array_merge(['url'], array_keys($fields)));
    }
    if (!empty($fields)) {
      fputcsv($fp, array_merge([$url], array_values($fields)));
      echo "<script>$('[name=collected_content_fields]').text(" . ($collected_content_fields_num + 1) . ")</script>";
      $collected_content_fields_num++;
    }
    fclose($fp);
    flush();
    $collected_content_urls_num++;
  }
  while ($collected_list_urls_num < sizeof($collect_list_urls)) {
    $url = $collect_list_urls[$collected_list_urls_num];
    get_scan_url($url);
    echo "<script>$('[name=collected_list_urls]').text(" . ($collected_list_urls_num + 1) . ")</script>";
    $collected_list_urls_num++;
    $fp = fopen(__DIR__ . "{$config['log_path']}/collected_list_urls.txt", 'a'); //opens file in append mode  
    fwrite($fp, $url . "\n");
    fclose($fp);
    flush();
  }
  ?>
</body>

</html>