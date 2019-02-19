from flup.server.fcgi import WSGIServer
from api import app

if __name__ == '__main__':
    #WSGIServer(app).run()
    WSGIServer(app, bindAddress='C:\kqmj\api\fcgi.sock').run()