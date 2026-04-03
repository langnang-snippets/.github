<?php

$params_array = array();

$urls = array(
    "https://www.busfan.bid/",
    "https://www.javbus.com/",
    "https://www.busdmm.club/",
    "https://www.buscdn.blog/",
    "https://www.dmmbus.blog/",
);

$url = $urls[0];

$params = $_GET;

$res = array(
    "title" => null,
    "prev" => null,
    "next" => null,
    "list" => array(),
);

//print_r($params);

$html = file_get_contents($url);
// 乱码解决办法，把其他编码格式通过 mb_convert_encoding 函数统一转为 UTF-8 格式
$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
// 页面源码由多行变单行
$html = str_replace(array("\r\n", "\r", "\n", "/\r|\n|\t/"), "", $html);
// 连续空格变单个空格
$html = preg_replace("/\s(?=\s)/", "\\1", $html);

// 提取获取页面的标题信息
preg_match("/<title>(.*)<\/title>/i", $html, $matchArray);

$res["title"] = $matchArray[1];

preg_match('/<a id="pre" href="\/(.*)">上一頁<\/a>/i', $html, $matchArray);
if (!empty($matchArray)) {
    $this["prev"] = $url . $matchArray[1];
}

preg_match('/<a id="next" href="\/(.*)">下一頁<\/a>/i', $html, $matchArray);
if (!empty($matchArray)) {
    $res["next"] = $url . $matchArray[1];
}


//echo '<textarea rows="25" cols="5"  style="width: 1200px;">' . $html . '<\/textarea><\/br>';


preg_match_all('/<div class="item"> <a class="movie-box" href="(.*?)"> <div class="photo-frame"> <img src="(.*?)" title="(.*?)"> <\/div>	<div class="photo-info">	<span>(.*?)<br \/>	<div class="item-tag">(.*?)<\/div>	<date>(.*?)<\/date> \/ <date>(.*?)<\/date><\/span>	<\/div> <\/a> <\/div>/i', $html, $matchArray);

//var_dump($matchArray);

foreach ($matchArray[0] as $k => $v) {
    $item = array(
        "jav_url" => $matchArray[1][$k],
        "jav_cover" => $matchArray[2][$k],
        "jav_name" => $matchArray[3][$k],
        "jav_code" => $matchArray[6][$k],
        "jav_date" => $matchArray[7][$k],
        "jav_hd" => strpos($matchArray[5][$k], '高清') !== false,
        "jav_subtitle" => strpos($matchArray[5][$k], '字幕') !== false
    );
    array_push($res["list"], $item);
}

echo json_encode($res, JSON_UNESCAPED_UNICODE);

//class jasModel
//{
//    private $url;
//    private $title;
//    public $name;
//    public $cover;
//    public $code;
//    public $date;
//
//    function __construct($url)
//    {
//        $this->url = $url;
//        // $html = file_get_contents($this->url);
//        // // 乱码解决办法，把其他编码格式通过 mb_convert_encoding 函数统一转为 UTF-8 格式
//        // $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
//        // // 页面源码由多行变单行
//        // $html = str_replace(array("\r\n", "\r", "\n", "/\r|\n|\t/"), "", $html);
//        // // 连续空格变单个空格
//        // $this->html = preg_replace("/\s(?=\s)/", "\\1", $html);
//        // // 通过 preg_match 函数提取获取页面的标题信息
//        // preg_match("/<title>(.*)<\/title>/i", $html, $titleArr);
//        // $this->title = $titleArr[1];
//
//        // self::get_info();
//    }
//
//    function get_info()
//    {
//    }
//}
//class jasModule
//{
//
//    private $host = "https://www.busfan.bid/";
//    private $url;
//    private $html;
//    private $title;
//    private $prev;
//    private $next;
//    public $list = array();
//
//    function __construct($query)
//    {
//        $this->url = $this->host . $query;
//    }
//
//    function run()
//    {
//        $html = file_get_contents($this->url, false, stream_context_create(array(
//            'http' => array(
//                'method' => 'GET',
//                'header' =>
//                    "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\r\n" .
//                    "Cookie:existmag=all; \r\n" .
//                    "Pragma:no-cache\r\n",
//            )
//        )));
//        // 乱码解决办法，把其他编码格式通过 mb_convert_encoding 函数统一转为 UTF-8 格式
//        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
//        // 页面源码由多行变单行
//        $html = str_replace(array("\r\n", "\r", "\n", "/\r|\n|\t/"), "", $html);
//        // 连续空格变单个空格
//        $this->html = preg_replace("/\s(?=\s)/", "\\1", $html);
//        // 通过 preg_match 函数提取获取页面的标题信息
//        preg_match("/<title>(.*)<\/title>/i", $html, $titleArr);
//        $this->title = $titleArr[1];
//
//        // echo '<textarea rows="25" cols="5"  style="width: 1200px;">' . $html . '<\/textarea><\/br>';
//
//        self::get_prev();
//        self::get_next();
//        self::get_list();
//
//        if (!is_null($this->next)) {
//            // var_dump(new jasModule($this->next));
//            var_dump($this->next);
//            $this->list = array_merge($this->list, (new jasModule($this->next))->list);
//        }
//    }
//
//    function get_next()
//    {
//        preg_match('/<a id="next" href="\/(.*)">下一頁<\/a>/i', $this->html, $matchArray);
//        if (!empty($matchArray)) {
//            $this->next = $matchArray[1];
//        }
//    }
//
//    function get_prev()
//    {
//        preg_match('/<a id="pre" href="\/(.*)">上一頁<\/a>/i', $this->html, $matchArray);
//        if (!empty($matchArray)) {
//            $this->prev = $matchArray[1];
//        }
//    }
//
//    function get_list()
//    {
//        preg_match_all('/<div class="item"> <a class="movie-box" href="(.*?)"> <div class="photo-frame"> <img src="(.*?)" title="(.*?)"> <\/div>	<div class="photo-info">	<span>(.*?)<br \/>	<div class="item-tag">(.*?)<\/div>	<date>(.*?)<\/date> \/ <date>(.*?)<\/date><\/span>	<\/div> <\/a> <\/div>/i', $this->html, $matchArray);
//
//        // var_dump($matchArray);
//        foreach ($matchArray[0] as $k => $v) {
//            $jas = new jasModel($matchArray[1][$k]);
//            $jas->cover = $matchArray[2][$k];
//            $jas->name = $matchArray[3][$k];
//            $jas->code = $matchArray[6][$k];
//            $jas->date = $matchArray[7][$k];
//            array_push($this->list, $jas);
//        }
//    }
//}
//
//
//if ($_SERVER["QUERY_STRING"] != "") {
//    $jas = new jasModule($_SERVER["QUERY_STRING"]);
//    var_dump($jas);
//} else {
//    echo json_encode(
//        array(
//            "status" => 404
//        )
//    );
//}