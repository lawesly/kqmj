# _*_ coding:utf-8 _*_

import win32com.client
import MySQLdb
import pyodbc
import sys
import os
import logging

# 记录日志 
logger = logging.getLogger()  
logger.setLevel(logging.INFO)        
logfile = "C:\kqmj\update\logs\userinfo_update.log"   
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

# check lock
lockfile = 'c:/kqmj/update/zk.lock'
if os.path.exists(lockfile):
    logger.warning("lockfile exist")
    sys.exit(2)
else:
    pass

zk = win32com.client.Dispatch('zkemkeeper.ZKEM')
if not zk.Connect_Net(host, port):
    logger.error("Connect Error")
    sys.exit(1)
logger.info("connected to zk")

# 同步用户信息表
zk.ReadAllUserID(1)
logger.info("start add userinfo")
userinfoVals = []
while 1:
    line = zk.SSR_GetAllUserInfo(1)
    if not line[0]:
        break
    name_list = line[2].split('\x00') 
    userinfoVals.append((line[1], name_list[0], line[3], line[4], line[5]))
    #print line[1],name_list[0]
if len(userinfoVals) > 0:
    dbconn = MySQLdb.connect(host=dbhost, user=dbuser ,passwd=dbpass, db=dbname,
                             port=dbport, use_unicode=True, charset="utf8")
    cursor = dbconn.cursor()
    cursor.execute("truncate table userinfo")  # 先清空用户表
    cursor.executemany('insert into userinfo(dwEnrollNumber,Name,Password,Privilege,Enabled) values(%s,%s,%s,%s,%s)', userinfoVals)
    dbconn.commit()
    logger.info("userinfo add success")
else:
    # cursor.close()
    # dbconn.close()
    zk.Disconnect()
    logger.error("failed to get userinfo")
    sys.exit()

# 同步门禁用户信息表
logger.info("start update mj_user")

mdbconn = pyodbc.connect('DSN=menjin')
mdbcursor = mdbconn.cursor()
SQL_group = "select * from t_b_Group"
mdbcursor.execute(SQL_group)
results = mdbcursor.fetchall()
groupDic = {}
for result in results:
    gid = result[0]
    groupDic[gid] = result[1]
# print groupDic

SQL = 'SELECT * from t_b_Consumer'
mdbcursor.execute(SQL)
results = mdbcursor.fetchall()
for result in results:
    uid = result[0]
    Name = result[2]
    cardNum = result[3]
    gid = result[4]
    try:
        depname = groupDic[gid]
    except:
        depname = 'NULL'
    SQL_other = "select f_Mobile from t_b_Consumer_Other where f_ConsumerID=%s" % uid
    mdbcursor.execute(SQL_other)
    res = mdbcursor.fetchall()
    try:
        phoneNum = res[0][0]
    except:
        phoneNum = 'NULL'
    update_sql = "update userinfo set cardNum='%s',depname='%s',phoneNum='%s' where Name='%s'"\
                 % (cardNum, depname, phoneNum, Name)
    # print update_sql
    cursor.execute(update_sql)
    dbconn.commit()    
mdbcursor.close()
mdbconn.close()


logger.info("update mj_user success")


cursor.close()
dbconn.close()
zk.Disconnect()
logger.info("disconnect from zk")
logger.info("transfer data success")
