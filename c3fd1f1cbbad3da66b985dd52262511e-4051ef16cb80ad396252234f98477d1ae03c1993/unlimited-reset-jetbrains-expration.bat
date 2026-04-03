
::关闭输出
@echo off

@echo 无限重置JetBrains软件试用期限

::选择软件

@echo 1\:PhpStorm

set exename=phpstorm

@echo %exename%

set dirname=PhpStorm2020.3

@echo %dirname%

::关闭指定程序

taskkill /f /im phpstorm64.exe

::清除程序数据

del %UserProfile%\AppData\Roaming\JetBrains\%dirname%\eval

del %UserProfile%\AppData\Roaming\JetBrains\%dirname%\options

::清除注册表

reg delete HKEY_CURRENT_USER\SOFTWARE\JavaSoft\Prefs\jetbrains\%exename%

pause

exit