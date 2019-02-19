# _*_ coding:utf-8 _*_

"""
    同步门禁系统数据
    author: zj
"""

import socket
import sys
import struct
import binascii
import MySQLdb
import logging

# 记录日志 
logger = logging.getLogger()
logger.setLevel(logging.INFO)
logfile = "C:\kqmj\update\logs\mj_update.log"
fh = logging.FileHandler(logfile, mode='a')
fh.setLevel(logging.DEBUG)
ch = logging.StreamHandler()
ch.setLevel(logging.WARNING)
formatter = logging.Formatter("%(asctime)s - %(levelname)s: %(message)s")
fh.setFormatter(formatter)
ch.setFormatter(formatter)
logger.addHandler(fh)
logger.addHandler(ch)

# 数据库
dbhost = '172.16.150.2'
dbuser = 'zk'
dbpass = 'zk2016'
dbname = 'zk'
dbport = 3306
dbconn = MySQLdb.connect(host=dbhost, user=dbuser, passwd=dbpass, db=dbname, port=dbport, use_unicode=True,
                         charset="utf8")
cursor = dbconn.cursor()
sql_select_userinfo = "select cardNum,Name,depname,phoneNum from userinfo"
cursor.execute(sql_select_userinfo)
data_userinfo = cursor.fetchall()
dic_userinfo = {}
for user in data_userinfo:
    uid = user[0]
    if uid != 'NULL':
        dic_userinfo[uid] = (user[1], user[2], user[3])

doorDic = {1: ('9aac5607', '172.16.190.3', '二楼北移门', 1),
           2: ('b7ac5607', '172.16.190.4', '运营拓展部(单移门)', 1),
           3: ('89ac5607', '172.16.190.5', '二楼嘉兴运营走道', 1),
           4: ('cba15607', '172.16.190.6', '嘉兴站(双开门)', 1),
           5: ('7cac5607', '172.16.190.7', '二楼南楼梯', 1),
           6: ('8aac5607', '172.16.190.8', '技术部北', 1),
           7: ('9eac5607', '172.16.190.9', '技术部南', 1),
           8: ('87ac5607', '172.16.190.11', '三楼北移门', 1),
           9: ('7aa25607', '172.16.190.12', '三楼露台', 1),
           10: ('18c45607', '172.16.190.10', '三楼南楼梯', 1),
           11: ('d1a15607', '172.16.190.2', '一楼南楼梯', 1),
           12: ('936a5707', '172.16.190.13', '一楼电商部', 1),
           # 13:('d0d85707','172.16.190.14','一楼北移门',1),
           13: ('65ea3819', '172.16.190.14', '一楼北移门', 1),
           14: ('9eb15707', '172.16.190.15', '一楼大厅移门', 1)}
port = 60000

menjinVals = []
for i in xrange(1, 15):
    SN = doorDic[i][0]
    doorIP = doorDic[i][1]
    doorName = doorDic[i][2]
    logger.info("start to update %s" % doorIP)
    # indexId = doorDic[i][3]
    sql_lastindex = "select indexId from menjin where doorId=%s order by id desc limit 1" % i
    cursor.execute(sql_lastindex)
    lastindex = cursor.fetchall()
    indexId = int(lastindex[0][0])
    #    if i == 13:
    #        indexId = 1
    # sendStr = '194000008aac56070100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
    # sendStr = '19B000008aac5607c144000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    try:
        sock.connect((doorIP, port))
        k = 1
        while True:
            indexId_16 = hex(indexId)[2:]
            indexId_fo = indexId_16.zfill(8)
            indexId_for = indexId_fo[6:8] + indexId_fo[4:6] + indexId_fo[2:4] + indexId_fo[0:2]

            sendStr = "19B00000%s%s00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" % (SN, indexId_for)
            # print sendStr
            indexId = indexId + 1
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
            if resultStrArr[4] == '00000000':
                break
            # print resultStrArr[4]
            cardNum1 = resultStrArr[4]
            cardNum2 = "%s%s%s%s" % (cardNum1[6:8], cardNum1[4:6], cardNum1[2:4], cardNum1[0:2])
            cardNum3 = int(cardNum2, 16)
            cardNum = str(cardNum3)
            action = resultStrArr[3][6:8]
            swipeDate = resultStrArr[5]
            swipeTime = "%s:%s:%s" % (resultStrArr[6][0:2], resultStrArr[6][2:4], resultStrArr[6][4:6])
            reasonNo = resultStrArr[6][6:8]
            try:
                Name = dic_userinfo[cardNum][0]
                depname = dic_userinfo[cardNum][1]
                phoneNum = dic_userinfo[cardNum][2]
            except:
                Name = cardNum
                depname = cardNum
                phoneNum = 'None'
            menjinVals.append(
                (str(indexId), cardNum, action, i, doorName, swipeDate, swipeTime, reasonNo, Name, depname, phoneNum))
            logger.info(indexId)
    except KeyboardInterrupt, e:
        logger.warning("keyboard stop")
        sys.exit(1)
    except:
        logger.warning("connect to %s error" % doorIP)
    finally:
        sock.close()

# if len(menjinVals) > 0:
cursor.executemany('insert into menjin(indexId,cardNum,action,doorId,doorSN,swipeDate,swipeTime,reasonNo,Name,depname,phoneNum) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)', menjinVals)
dbconn.commit()
cursor.close()
dbconn.close()
