::
:: @desc 使用 ffmpeg 批量提取视频字幕
::

:: 关闭回显
@echo off 

:: 选择被提取的字幕格式
:: 0.ass 1.srt
echo =====请选择被提取的字幕格式
echo 0.ass
echo 1.srt

set /p str0= 请选择被提取的字幕格式:


:: 被提取的视频格式
set /p str1= 请输入要提取字幕的视频格式：
:: 被提取的字幕次序
set /p index= 请输入被提取的字幕次序(0,1,2,...)：
:: 提取出的字幕格式
set /p str3= 请输入提取出的字幕名(.chs,.cht,.eng,.jpe,...)：

echo.
echo 正在提取字幕中，请稍候……

:: 遍历提取视频
if "%str0%"=="0" (
  for %%i in (.\*.%str1%) do ffmpeg -i "%%i" -map 0:s:%index% "%%~dpni%str3%.ass"
)

if "%str0%"=="1" (
  for %%i in (.\*.%str1%) do ffmpeg -i "%%i" -vn -an -codec:s:%index% srt "%%~dpni%str3%.srt"
)


:: 结束
exit;