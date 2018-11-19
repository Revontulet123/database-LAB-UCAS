create table Users (
	Realname varchar(20),             
	UserID varchar(18) primary key,
	Phone char(11) unique,
	CreditCard char(16),
	Username varchar(20),
	password varchar(20)
	);

create table Orders (
	Username varchar(20),
	OrderID varchar(20),
	TrainID varchar(5),
	StartDate date,
	TrainStartDate date,
	StartStationID integer,
	EndStationID integer,
	StartStationNum integer,
	EndStationNum integer,
	SeatType integer,
	Price decimal(5,1),
	Status integer,
	primary key (OrderID, TrainID)
	);
/* SeatType  0 硬座  1 软座  2 硬卧上  3 硬卧中  4 硬卧下  5 软卧上  6 软卧下 */
/* Status  0 = 取消    1 = 付款*/

create table Trains (
	TrainID varchar(5) primary key,
	YZflag integer,
	RZflag integer,
	YWSflag integer,
	YWZflag integer,
	YWXflag integer,
	RWSflag integer,
	RWXflag integer
	);

create table Stations (
	TrainID varchar(5),
	StationNum integer,
	StationID integer,
	StationName varchar(20),
	Gap integer,
	ArriveTime Time,
	StartTime Time,
	ArriveMin integer,
	StartMin integer,
	YZprice decimal(5,1),
	RZprice decimal(5,1),
	YWSprice decimal(5,1),
	YWZprice decimal(5,1),
	YWXprice decimal(5,1),
	RWSprice decimal(5,1),
	RWXprice decimal(5,1),
	Forbid integer,
	primary key(TrainID, StationID)
	);

create table StationCity (
	StationID integer not null,
	StationName varchar(20),
	Cityname varchar(20),
	primary key(StationID)
	);

create table SeatCount (
	TrainID varchar(5),
	StationNum integer,
	StartDate date, 
	YZCount integer,
	RZCount integer,
	YWSCount integer,
	YWZCount integer,
	YWXCount integer,
	RWSCount integer,
	RWXCount integer,
	primary key (TrainID, StartDate, StationNum)
	);

create table Admins (
	Adminname varchar(20),
	password varchar(20),
	primary key(Adminname)
	);
---------------------------------------------------
$ psql
  dbms=# create database test;
  CREATE DATABASE
  dbms=# \q

$ psql -d test
  test=# create table Trains (......);
  test=# create table Stations (......);
  test=# create table StationCity (......);
  test=# create table SeatCount (......);
  test=# copy Trains from '/mnt/hgfs/share/train-2016-10-v4/train-2016-10/Trains.csv' with (format csv, delimiter ',');
  test=# copy Stations from '/mnt/hgfs/share/train-2016-10-v4/train-2016-10/Stations.csv' with (format csv, delimiter ',');
  test=# copy StationCity from '/mnt/hgfs/share/train-2016-10-v4/train-2016-10/StationCity.csv' with (format csv, delimiter ',');
  test=# copy SeatCount from '/mnt/hgfs/share/train-2016-10-v4/train-2016-10/SeatCount.csv' with (format csv, delimiter ',');
