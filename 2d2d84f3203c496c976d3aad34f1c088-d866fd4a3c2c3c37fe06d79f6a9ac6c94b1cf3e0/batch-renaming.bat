::
:: @desc 批量修改当前文件夹下文件（夹）名中字符
::

:: 关闭回显
@echo off 

:: 被替换的字符
set /p str1= 请输入要替换的文件(文件夹)名字符串（可替换空格）：
:: 替换的字符
set /p str2= 请输入替换后的文件(文件夹)名字符串（去除则直接回车）：
echo.
echo 正在修改文件(夹)名中，请稍候……

:: 遍历替换字符
for /f "delims=" %%a in ('dir /s /b ^|sort /+65535') do (
	if "%%~nxa" neq "%~nx0" (
	set "file=%%a"
	set "name=%%~na"
	set "extension=%%~xa"
	call set "name=%%name:%str1%=%str2%%%"
	setlocal enabledelayedexpansion
	ren "!file!" "!name!!extension!" 2>nul
	endlocal
	)
)

:: 结束
exit;
