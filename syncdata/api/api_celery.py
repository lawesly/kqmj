# coding:utf-8

from flask import Flask
from celery import Celery,task
#import commands
import os


app = Flask(__name__)
 

def make_celery(app):
    celery = Celery('api',  
                     broker=app.config['CELERY_BROKER_URL'],
                     backend=app.config['CELERY_RESULT_BACKEND']
    )
    celery.conf.update(app.config)
    TaskBase = celery.Task
    class ContextTask(TaskBase):
        abstract = True
        def __call__(self, *args, **kwargs):
            with app.app_context():
                return TaskBase.__call__(self, *args, **kwargs)
    celery.Task = ContextTask
    return celery
 
app.config.update(
    CELERY_BROKER_URL='redis://172.16.150.2:6379/0',
    CELERY_RESULT_BACKEND='redis://172.16.150.2:6379/1'
)
celery = make_celery(app)

@task
def do_commands(script):
    task_id = do_commands.request.id
    #(status, output) = commands.getstatusoutput(script)
    #return output
    os.system(script)
