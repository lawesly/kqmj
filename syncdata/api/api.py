#_*_ coding:utf-8 _*_

from flask import Flask,request
from flask_restful import Resource,Api
import os
import sys


from api_celery import do_commands

reload(sys)
sys.setdefaultencoding("utf-8")


app = Flask(__name__)
api = Api(app)



class HelloWorld(Resource):
    def get(self):
        return {'hello': 'world1'}


class Update_MJtime(Resource):
    def post(self):
        key = request.form['key']
        if key != 'fccs2017':
            return {'authorized': 'failed'}
            sys.exit(1)
        command = "python C:\kqmj\update\mj_time.py"
        job = do_commands.delay(command)
        task_id = job.task_id
        #os.system(command)
        return {"res": task_id}

    
class Update_Userinfo(Resource):
    def post(self):
        key = request.form['key']
        if key != 'fccs2017':
            return {'authorized': 'failed'}
            sys.exit(1)
        command = "python C:\kqmj\update\userinfo_update.py"
        job = do_commands.delay(command)
        task_id = job.task_id
        return {"res": task_id}


class Update_Menjin(Resource):
    def post(self):
        key = request.form['key']
        if key != 'fccs2017':
            return {'authorized': 'failed'}
            sys.exit(1)
        command = "python C:\kqmj\update\mj_update.py"
        job = do_commands.delay(command)
        task_id = job.task_id
        return {"res": task_id}


class Update_ZK(Resource):
    def post(self):
        key = request.form['key']
        if key != 'fccs2017':
            return {'authorized': 'failed'}
            sys.exit(1)
        command = "python C:\kqmj\update\zk_update.py"
        job = do_commands.delay(command)
        task_id = job.task_id
        return {"res": task_id}


class Open_BG(Resource):
    def post(self):
        key = request.form['key']
        if key != 'zhimakaimen':
            return {'authorized': 'failed'}
            sys.exit(1)
        command = "python C:\kqmj\api\bg.py"
        os.system(command)
        return {"res": "ok"}


api.add_resource(HelloWorld, '/')
api.add_resource(Update_MJtime, '/update_mjtime/')
api.add_resource(Update_Userinfo, '/update_userinfo/')
api.add_resource(Update_Menjin, '/update_mj/')
api.add_resource(Update_ZK, '/update_zk/')
api.add_resource(Open_BG, '/open_bg/')


if __name__ == '__main__':
    app.run(debug=True,host='0.0.0.0')
