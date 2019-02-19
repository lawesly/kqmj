Set ws = CreateObject("Wscript.Shell") 
ws.run "cmd /c startCelery.bat",vbhide
ws.run "cmd /c startApi.bat",vbhide
 