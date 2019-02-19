# _*_ coding:utf-8 _*_

"""
    update menjin controller time
    author: zj
"""

import socket
import sys
import struct
import binascii
import logging
from datetime import datetime

doorDic = {
    1: ('9aac5607', '172.16.190.3', '二楼北移门', 1200),
    2: ('b7ac5607', '172.16.190.4', '运营拓展部(单移门)', 111),
    3: ('89ac5607', '172.16.190.5', '二楼嘉兴运营走道', 111),
    4: ('cba15607', '172.16.190.6', '嘉兴站(双开门)', 234),
    5: ('7cac5607', '172.16.190.7', '二楼南楼梯', 345),
    6: ('8aac5607', '172.16.190.8', '技术部北', 111),
    7: ('9eac5607', '172.16.190.9', '技术部南', 111),
    8: ('87ac5607', '172.16.190.11', '三楼北移门', 111),
    9: ('7aa25607', '172.16.190.12', '三楼露台', 111),
    10: ('18c45607', '172.16.190.10', '三楼南楼梯', 111),
    11: ('d1a15607', '172.16.190.2', '一楼南楼梯', 111),
    12: ('936a5707', '172.16.190.13', '一楼电商部', 111),
    13: ('74534e0d', '172.16.190.14', '一楼北移门', 111),
    14: ('9eb15707', '172.16.190.15', '一楼大厅移门', 111)
}
port = 60000

# 记录日志 
logger = logging.getLogger()
logger.setLevel(logging.INFO)  # Log等级总开关
# 输出到文件  
logfile = "C:\kqmj\update\logs\mj_time.log"
fh = logging.FileHandler(logfile, mode='a')
fh.setLevel(logging.DEBUG)  # 输出到file的log等级的开关
# 输出到控制台  
ch = logging.StreamHandler()
ch.setLevel(logging.WARNING)  # 输出到console的log等级的开关
# 定义handler的输出格式  
formatter = logging.Formatter("%(asctime)s - %(levelname)s: %(message)s")
fh.setFormatter(formatter)
ch.setFormatter(formatter)
# 将logger添加到handler里面  
logger.addHandler(fh)
logger.addHandler(ch)

for i in xrange(1, 15):
    SN = doorDic[i][0]
    doorIP = doorDic[i][1]
    doorName = doorDic[i][2]
    indexId = doorDic[i][3]

    logger.info("开始同步%s" % doorName)
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    try:
        sock.connect((doorIP, port))
        timea = datetime.now().strftime('%Y%m%d%H%M%S')
        logger.info(timea)
        sendStr = "19300000%s%s00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" % (SN, timea)
        sendStr1 = ''
        sendStr2 = ''

        while sendStr:
            sendStr1 = sendStr[0:2]
            sendS = int(sendStr1, 16)
            sendStr2 += struct.pack('B', sendS)
            sendStr = sendStr[2:]
        sock.sendall(sendStr2)
        buff = sock.recv(1024)
        if not buff:
            logger.warning("error")
        resultStr = binascii.b2a_hex(buff)
        resultStr1 = ""
        resultStrArr = []
        resultStrLen = len(resultStr)
        for j in xrange(resultStrLen / 8):
            resultStr1 = resultStr[j * 8:(j + 1) * 8]
            resultStrArr.append(resultStr1)
            # print resultStrArr[2]
    except KeyboardInterrupt, e:
        logger.warning("keboard stop")
        sys.exit(1)
    except:
        logger.warning("connect to %s error" % doorIP)
    finally:
        sock.close()
