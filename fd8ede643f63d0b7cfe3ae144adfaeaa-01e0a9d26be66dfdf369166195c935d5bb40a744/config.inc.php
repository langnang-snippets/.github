<?php return [
  "www_36dm_club" => [
    "name" => "",
    'domains' => [
      'www.36dm.club',
    ],
    'scan_urls' => ['https://www.36dm.club'],
    'list_url_regexes' => ['https://www.36dm.club/\d+.html', 'https://www.36dm.club/[a-zA-Z]+-\d+.html'],
    'content_url_regexes' => ['https://www.36dm.club/show-[0-9a-z]+.html'],
    'export' => [
      'type' => 'csv', // csv、sql、db
      'file' => 'csv', // csv、sql
      'table' => 'csv', // db、sql
    ],
    'fields' => [
      [
        "name" => "title",
        "find" => "head>title",
        "func" => "text",
      ],
      [
        "name" => "keywords",
        "find" => "head>meta[name='keywords']",
        "func" => "attr",
        "args" => 'content',
      ],
      [
        "name" => "description",
        "find" => "head>meta[name='description']",
        "func" => "attr",
        "args" => 'content',
      ],
      [
        "name" => "metas",
        "find" => "head>meta",
        "func" => "attrs",
        "args" => 'content',
      ],
    ],
  ],
  "www_txt80_com" => [
    "name" => "",
    'domains' => [
      'www.txt80.com',
    ],
    'scan_urls' => ['https://www.txt80.com/'],
    'list_url_regexes' => [
      'https://www.txt80.com/[a-z]+\/',
      'https://www.txt80.com/[a-z]+\/index_[0-9]+.html', // 目录页
      'https://www.txt80.com/search-[0-9]+-[0-9]+.html', // 搜索结果页
      'https://www.txt80.com/[a-z]+\/txt[0-9]+.html', // 信息页
    ],
    'content_url_regexes' => [
      'https://www.txt80.com/down\/txt[0-9a-zA-Z]+.html', // 下载页
    ],
    'export' => [
      'type' => 'csv', // csv、sql、db
      'file' => 'csv', // csv、sql
      'table' => 'csv', // db、sql
    ],
    'fields' => [
      [
        "name" => "title",
        "find" => "head>title",
        "func" => "text",
      ],
      [
        "name" => "keywords",
        "find" => "head>meta[name='keywords']",
        "func" => "attr",
        "args" => 'content',
      ],
      [
        "name" => "description",
        "find" => "head>meta[name='description']",
        "func" => "attr",
        "args" => 'content',
      ],
      [
        "name" => "metas",
        "find" => "head>meta",
        "func" => "attrs",
        "args" => 'content',
      ],
      [
        "name" => "down_urls",
        "find" => ".downlist a",
        "func" => "attrs",
        "args" => 'href',
      ]
    ],
  ]
];
