# _*_ coding:utf-8 _*_

import httplib
import urllib

httpClient = None
try:
    params = urllib.urlencode({'key': 'fccs2017'})
    headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}

    httpClient = httplib.HTTPConnection("127.0.0.1", 5000, timeout=30)
    httpClient.request("POST", "/update_zk/", params, headers)

    response = httpClient.getresponse()
    print response.status
    print response.reason
    print response.read()
    print response.getheaders()  # 获取头信息
except Exception, e:
    print e
finally:
    if httpClient:
        httpClient.close()
