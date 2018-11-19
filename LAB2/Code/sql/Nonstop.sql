/*各城市之间的直达信息*/
select 	s1.TrainID as TrainID,
        c1.CityName as BeginCityName, 
        c2.CityName as DestCityName,
		s1.StartTime BeginTime,
		s2.ArriveTime as EndTime,
		s1.StationName as BeginStationName,
		s1.StationID as BeginStationID, 
		s1.StationNum as BeginStationNum, 
		s2.StationName as DestStationName,
		s2.StationID as DestStationID, 
		s2.StationNum as DestStationNum,  
		(s2.ArriveMin - s1.StartMin) as TripMin,
		(s1.Gap || 'day')  :: interval as BeginGap,
		(s2.Gap || 'day')  :: interval as EndGap,
		(s2.YZprice -  s1.YZprice) as YZprice, 
		(s2.RZprice - s1.RZprice) as RZprice,
		(s2.YWSprice - s1.YWSprice) as YWSprice, 
		(s2.YWZprice - s1.YWZprice) as YWZprice,
		(s2.YWXprice - s1.YWXprice) as YWXprice, 
		(s2.RWSprice - s1.RWSprice) as RWSprice,
		(s2.RWXprice - s1.RWXprice) as RWXprice,
		t1.YZflag, 
		t1.RZflag , 
		t1.YWSflag , 
		t1.YWZflag,
		t1.YWXflag  , 
		t1.RWSflag , 
		t1.RWXflag

into Nonstop
from	Stations as s1, Stations as s2, StationCity as c1, StationCity as c2, Trains as t1
where  	s1.TrainID = s2.TrainID and s1.TrainID = t1.TrainID and	
		s1.StationID = c1.StationID 	and s1.Forbid = 0 and
		s2.StationID = c2.StationID  and s2.Forbid = 0 and
		s1.StationNum < s2.StationNum ;
		/*保证出发站在前，到达站在后*/

/*加一列记录最小价格*/
alter table Nonstop add MinPrice decimal(5,1) default 9999.0;

update Nonstop
set MinPrice = YZprice
where MinPrice > YZprice and YZprice > 0;


update Nonstop
set MinPrice = YWSprice
where MinPrice > YWSprice and YWSprice> 0;


update Nonstop
set MinPrice = YWZprice
where MinPrice > YWZprice and YWZprice > 0;

update Nonstop
set MinPrice = YWXprice
where MinPrice > YWXprice and YWXprice > 0;

update Nonstop
set MinPrice = RZprice
where MinPrice > RZprice and RZprice > 0;


update Nonstop
set MinPrice = RWSprice
where MinPrice > RWSprice and RWSprice > 0;


update Nonstop
set MinPrice = RWXprice
where MinPrice > RWXprice and RWXprice > 0;

/*可能有些站某个种类的票不售，导致计算得负，需要修正*/
update Nonstop
	set YZprice = 0
	where YZprice < 0;

update Nonstop
	set YWSprice = 0
	where YWSprice < 0;

update Nonstop
	set YWZprice = 0
	where YWZprice < 0;

update Nonstop
	set YWXprice = 0
	where YWXprice < 0;

update Nonstop
	set RZprice = 0
	where RZprice < 0;

update Nonstop
	set RWSprice = 0
	where RWSprice < 0;

update Nonstop
	set RWXprice = 0
	where RWXprice < 0;

