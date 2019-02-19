# attendance

考勤系统 PHP

## 准备工作
- linux
- php版本大于5.5，小于7.0
- mysql
- nginx
- mysqli扩展
- CodeIgniter

## 导入数据库结构
```
mysql> CREATE DATABASE `zk` DEFAULT CHARACTER SET utf8;

bash> mysql -uroot zk < sql/zk_tables.sql
```

## 注意事项
为保证不出错，安装目录为/www/attendance(可能有些程序写了绝对路径漏改了)
