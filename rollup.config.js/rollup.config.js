// rollup.config.js

import json from 'rollup-plugin-json';// 从 JSON 文件中读取数据
import cleanup from 'rollup-plugin-cleanup';// 删除注释，修剪尾随空格，压缩空行，并规范化行结束符
import typescript from '@rollup/plugin-typescript';// 支持 TypeScript
import scss from 'rollup-plugin-scss';// 支持 Scss

import { version } from './package.json';

export default {
  // 核心选项
  /**
   * 输入(input -i/--input)
   * String 这个包的入口点 (例如：你的 main.js 或者 app.js 或者 index.js)
   */
  input: "main.ts",     // 必须
  // external,
  // plugins,
  plugins: [
    json(),
    cleanup(),
    typescript(),
    scss({
      output: true,

      // Filename to write all styles to
      output: './main.css',
    }),
  ],

  // // 额外选项
  // onwarn,

  // // danger zone
  // acorn,
  // context,
  // moduleContext,
  // legacy,
  output: [
    ...[
      {
        file: './dist/js/dist.js',
        format: 'umd',
      },
      {
        file: './dist/js/dist.amd.js',
        format: 'amd',
      },
      {
        file: './dist/js/dist.cjs.js',
        format: 'cjs',
      },
      {
        file: './dist/js/dist.es.js',
        format: 'es',
      }
    ].map(v => {
      return {
        ...v,
        // 
        name: 'ln',
        // String 是要使用的缩进字符串，对于需要缩进代码的格式（amd，iife，umd）。也可以是false（无缩进）或true（默认 - 自动缩进）
        indent: false,
        // true或false（默认为true） - 是否在生成的非ES6软件包的顶部包含'use strict'pragma。严格来说（geddit？），ES6模块始终都是严格模式，所以你应该没有很好的理由来禁用它。
        strict: true,
        // 路径(paths)#
        // Function，它获取一个ID并返回一个路径，或者id：path对的Object。在提供的位置，这些路径将被用于生成的包而不是模块ID，从而允许您（例如）从CDN加载依赖关系
        paths: {
          // d3: 'https://d3js.org/d3.v4.min'
        },
        // String 字符串以 前置/追加 到文件束(bundle)。(注意:“banner”和“footer”选项不会破坏sourcemaps)
        banner: '/* my-library version ' + version + ' */',
        footer: '/* follow me on Twitter! @rich_harris */',
        // String类似于 banner和footer，除了代码在内部任何特定格式的包装器(wrapper)
        intro: 'var ENVIRONMENT = "production";',
        outro: 'var ENVIRONMENT = "production";',
      }
    }),
  ]
  // output: {  // 必须 (如果要输出多个，可以是一个数组)
  //   // 核心选项
  //   file,    // 必须
  //   format,  // 必须
  //   name,
  //   globals,

  //   // 额外选项
  //   paths,
  //   banner,
  //   footer,
  //   intro,
  //   outro,
  //   sourcemap,
  //   sourcemapFile,
  //   interop,

  //   // 高危选项
  //   exports,
  //   amd,
  //   indent,
  //   strict
  // },
};