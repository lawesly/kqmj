# _*_ coding:utf-8 _*_

"""
    同步指纹考勤机记录
    author: zj
"""

import win32com.client
import MySQLdb
import sys
import os
import logging
import time

# 记录日志 
logger = logging.getLogger()  
logger.setLevel(logging.INFO)        
logfile = "C:\kqmj\update\logs\zk_update.log"   
fh = logging.FileHandler(logfile, mode='a')  
fh.setLevel(logging.DEBUG)      
ch = logging.StreamHandler()  
ch.setLevel(logging.WARNING)      
formatter = logging.Formatter("%(asctime)s - %(levelname)s: %(message)s")  
fh.setFormatter(formatter)  
ch.setFormatter(formatter)      
logger.addHandler(fh)  
logger.addHandler(ch)

host = '172.16.190.100'
port = 4370

dbhost = '172.16.150.2'
dbuser = 'zk'
dbpass = 'zk2016'
dbname = 'zk'
dbport = 3306

zk = win32com.client.Dispatch('zkemkeeper.ZKEM')
if not zk.Connect_Net(host, port):
    logger.error("Connect Error")
    sys.exit(1)
    
# check lock
lockfile = 'c:/kqmj/update/zk.lock'
now = time.time()
if os.path.exists(lockfile):
    logger.warning("lockfile exist")
    locktime = os.path.getctime(lockfile)
    filetime = now - locktime
    if filetime > 1200:
        logger.warning("old lock file")
        os.remove(lockfile)
        f = open(lockfile, 'w+')
        f.close()
    else:
        sys.exit(2)
else:
    f = open(lockfile, 'w+')
    f.close()
    
logger.info("connected to zk")


dbconn = MySQLdb.connect(host=dbhost, user=dbuser, passwd=dbpass, db=dbname,
                         port=dbport, use_unicode=True, charset="utf8")
cursor = dbconn.cursor()

# 获取用户信息
sql_select_userinfo = "select dwEnrollNumber,Name,depname,phoneNum from userinfo"
cursor.execute(sql_select_userinfo)
data_userinfo = cursor.fetchall()
dic_userinfo = {}
for user in data_userinfo:
    uid = user[0]
    dic_userinfo[uid] = (user[1], user[2], user[3])
    
# 同步考勤数据表
logger.info("start add attendance")
attendanceVals = []
cursor.execute("select * from attendance order by id desc limit 1")
lastdata = cursor.fetchall()
lastdata = lastdata[0]

while 1:
    line = zk.SSR_GetGeneralLogData(1)
    if not line[0]:
        break    
    dwdate = "%d%s%s" % (line[4], str(line[5]).zfill(2), str(line[6]).zfill(2))
    dwtime = "%s:%s:%s" % (str(line[7]).zfill(2), str(line[8]).zfill(2), str(line[9]).zfill(2))
    if dwdate > lastdata[4] or (dwdate == lastdata[4] and dwtime > lastdata[5]):
        dwEnrollNumber = line[1]
        try:
            Name = dic_userinfo[dwEnrollNumber][0]
            depname = dic_userinfo[dwEnrollNumber][1]
            phoneNum = dic_userinfo[dwEnrollNumber][2]
        except:
            Name = ''
            depname = ''
            phoneNum = ''
        attendanceVals.append((line[1], line[2], line[3], dwdate, dwtime, line[10], Name, depname, phoneNum))
        # attendanceVals.append((line[1],line[2],line[3],dwdate,dwtime,line[10],dic_userinfo[line[1]][0],dic_userinfo[line[1]][1],dic_userinfo[line[1]][2]))
if attendanceVals:
    cursor.executemany('insert into attendance(dwEnrollNumber, dwVerifyMode, dwInOutMode, dwDate, dwTime, dwWorkCode, '
                       'Name, depname, phoneNum) values(%s, %s, %s, %s, %s, %s, %s, %s, %s)', attendanceVals)
    dbconn.commit()
    logger.info("attendance add success")
else:
    logger.info("no new attendance add")

cursor.close()
dbconn.close()
zk.Disconnect()
logger.info("disconnect from zk")
logger.info("transfer data success")
os.remove(lockfile)
logger.info("remove lockfile success")
