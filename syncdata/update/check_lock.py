import os
import sys
import time

lockfile = 'c:/kqmj/update/zk.lock'
now = time.time()
if os.path.exists(lockfile):
    print "lock file exist"
    locktime = os.path.getctime(lockfile)
    filetime = now - locktime
    if filetime > 1200:
        print "old lock file"
        os.remove(lockfile)
        f = open(lockfile, 'w+')
        f.close()
        
    else:    
        sys.exit(2)
else:
    f = open(lockfile, 'w+')
    f.close()
