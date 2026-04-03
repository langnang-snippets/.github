::
:: @desc 使用 ffmpeg 批量转换视频格式
::

:: 关闭回显
@echo off 

:: 被替换的字符
set /p str1= 请输入要转换的视频格式：
:: 替换的字符
set /p str2= 请输入转换后的视频格式：
echo.
echo 正在转换视频名中，请稍候……

:: 遍历转换视频
for %%i in (.\*.%str1%) do ffmpeg -i "%%i" -c copy "%%~dpni.%str2%"

:: 结束
exit;