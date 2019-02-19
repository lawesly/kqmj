var xmlHttp
function Show(url,Html)
{
        xmlHttp=GetXmlHttpObject()
                if (xmlHttp==null)
                {
                        alert ("Browser does not support HTTP Request")
                                return
                }
        var url=url
                xmlHttp.onreadystatechange=stateChanged(Html)
                xmlHttp.open("GET",url,true)
                xmlHttp.send(null)
}

function stateChanged(Html)
{
        if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
        {
          //      document.getElementById("sor").innerHTML=xmlHttp.responseText
          	   Html=xmlHttp.responseText
        }
}

function GetXmlHttpObject()
{
        var xmlHttp=null;
        try
        {
                // Firefox, Opera 8.0+, Safari
                xmlHttp=new XMLHttpRequest();
        }
        catch (e)
        {
                //Internet Explorer
                try
                {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
        }
        return xmlHttp;
}

