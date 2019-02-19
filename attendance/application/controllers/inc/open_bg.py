# _*_ coding:utf-8 _*_

import socket
import sys
import struct
import binascii
import os


port = 60000
doorIP='172.16.190.14'
# SN = '74534e0d'
SN= '65ea3819'
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
try:
    sock.connect((doorIP,port))

    #sendStr = "19400000%s0200000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" %(SN)
    sendStr = "19400000%s0200000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" %(SN)
    print sendStr
    sendStr1 = ''
    sendStr2 = ''
    while sendStr:  
        sendStr1 = sendStr[0:2] 
        sendS = int( sendStr1, 16) 
        sendStr2 += struct.pack('B', sendS)   
        sendStr = sendStr[2:]          
    sock.sendall(sendStr2)
    buff = sock.recv(1024)
    if not buff:
        print "error"
    print buff
    sock.close()
except KeyboardInterrupt,e:
    print "stop"
    sys.exit(1)
except:
    print "connect to %s error" % doorIP
finally:
    sock.close()
