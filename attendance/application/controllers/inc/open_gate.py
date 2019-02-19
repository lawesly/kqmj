# _*_ coding:utf-8 _*_

"""
    开门程序
    author: zj
    2017.06.27
"""

import socket
import sys
import struct
import binascii
import MySQLdb
import os
import logging

# 记录日志
logger = logging.getLogger()
logger.setLevel(logging.INFO)
logfile = "/www/attendance/logs/open_gate.log"
fh = logging.FileHandler(logfile, mode='a')
fh.setLevel(logging.DEBUG)
ch = logging.StreamHandler()
ch.setLevel(logging.WARNING)
formatter = logging.Formatter("%(asctime)s - %(levelname)s: %(message)s")
fh.setFormatter(formatter)
ch.setFormatter(formatter)
logger.addHandler(fh)
logger.addHandler(ch)

# 判断是否传入门ID
try:
    doorId = sys.argv[1]
except IOError:
    logger.error("no doorId input!")
    sys.exit()

doorDic = {
    1: ('xxxxxxxx', '172.16.190.3', '二楼北移门', 1),
    2: ('xxxxxxxx', '172.16.190.4', '运营拓展部(单移门)', 1),
    3: ('xxxxxxxx', '172.16.190.5', '二楼嘉兴运营走道', 1),
    4: ('xxxxxxxx', '172.16.190.6', '嘉兴站(双开门)', 1),
    5: ('xxxxxxxx', '172.16.190.7', '二楼南楼梯', 1),
    6: ('xxxxxxxx', '172.16.190.8', '技术部北', 1),
    7: ('xxxxxxxx', '172.16.190.9', '技术部南', 1),
    8: ('xxxxxxxx', '172.16.190.11', '三楼北移门', 1),
    9: ('xxxxxxxx', '172.16.190.12', '三楼露台', 1),
    10: ('xxxxxxxx', '172.16.190.10', '三楼南楼梯', 1),
    11: ('xxxxxxxx', '172.16.190.2', '一楼南楼梯', 1),
    12: ('xxxxxxxx', '172.16.190.13', '一楼电商部', 1),
    13: ('xxxxxxxx', '172.16.190.14', '一楼北移门', 1),
    14: ('xxxxxxxx', '172.16.190.15', '一楼大厅移门', 1),
    15: ('xxxxxxxx', '172.16.190.14', '电梯', 1)
}
port = 60000
try:
    door = doorDic[int(doorId)]
    SN = door[0]
    doorIP = door[1]
    doorName = door[2]
except KeyError, e:
    logger.error("no door found!")
    sys.exit()
except:
    sys.exit()
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
try:
    sock.connect((doorIP, port))

    # sendStr = "19400000%s0200000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" %(SN)
    if int(doorId) == 15:
        sendStr = "19400000%s0300000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" % (SN)
    else:
        sendStr = "19400000%s0100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" % (SN)
    # print sendStr
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
        logger.error("error")
    logger.info("%s open success" % doorName)
    sock.close()
except KeyboardInterrupt, e:
    sys.exit(1)
except:
    logger.error("connect to %s error" % doorIp)
finally:
    sock.close()
