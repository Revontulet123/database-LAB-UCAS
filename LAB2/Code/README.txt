网页文件目录
|----homepage.php          网站主页
|----userlogin.php         用户登录
|----adminlogin.php        管理员登录
|----logincheck.php        检查能否成功登录
|----adminlogincheck.php
|----signup.php            注册页面
|----signupcheck.php       检查是否符合注册条件
|----quit.php              退出
|----req4.php              需求4,按车次查列车
|----req5.php              需求5,按城市查列车
|----req6.php              订票界面
|----req6-success.php      成功生成订单
|----myorder.php           查询订单页面
|----req7.php              查询结果页面
|----req7-detail.php       订单详情
|----req7-cancel.php       取消订单界面
|----adminloginok.php      管理员登录后的页面
|----req8-userorder.php    管理员状态下查看用户的订单

使用流程
0.预处理
Preprocess文件夹中datawash.py
获得Trains.csv、Stations.csv、StationCity.csv、SeatCount.csv
1.建库建表
$ psql
  dbms=# create database test;
  CREATE DATABASE
  dbms=# \q
$ psql -d test
  test=# create table Users (......);
  test=# create table Orders (......);
  test=# create table Admins (......);
  test=# create table Trains (......);
  test=# create table Stations (......);
  test=# create table StationCity (......);
  test=# create table SeatCount (......);

2.运行datawash.py 并导入数据,路径名自己改！！！
  test=# copy Trains from '/mnt/hgfs/share/train-2016-10/Trains.csv' with (format csv, delimiter ',');
  test=# copy Stations from '/mnt/hgfs/share/train-2016-10/Stations.csv' with (format csv, delimiter ',');
  test=# copy StationCity from '/mnt/hgfs/share/train-2016-10/StationCity.csv' with (format csv, delimiter ',');
  test=# copy SeatCount from '/mnt/hgfs/share/train-2016-10/SeatCount.csv' with (format csv, delimiter ',');

3.产生nonstop表
  把onetrip里的输进test=#去，不然没法用需求5的

4.自己设置管理员
  INSERT INTO admins VALUES ('admin', '123');

5.把网页文件全考到/var/www/html/那个文件夹下面，在火狐里输http://localhost/homepage.php开始测