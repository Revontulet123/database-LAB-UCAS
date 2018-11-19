/*按车次查车站余票信息*/
/*起始站需要单独处理余座，输出为'-'*/(
select  s1.StationNum, s1.StationName, s1.ArriveTime, s1.StartTime, 
		s1.YZprice, (sc.YZCount) as YZCount, 
		s1.RZprice, (sc.RZCount) as RZCount, 
		s1.YWSprice, (sc.YWSCount) as YWSCount, 
		s1.YWZprice, (sc.YWZCount) as YWZCount, 
		s1.YWXprice, (sc.YWXCount) as YWXCount,
		s1.RWSprice, (sc.RWSCount) as RWSCount, 
		s1.RWXprice, (sc.RWXCount) as RWXCount, 
		t1.YZflag, t1.RZflag, t1.YWSflag, t1.YWZflag, t1.YWXflag, t1.RWSflag, t1.RWXflag, /* 输出这列车是否开了某座位类型 */
		s1.Forbid /* 这站是否不售票 */
from 	Stations as s1, SeatCount as sc , Trains as t1
where ( s1.TrainID = 'G101' and
		sc.TrainID = 'G101' and
		t1.TrainID = 'G101' and 
		sc.StartDate = '2018/11/23' and
		sc.StationNum = 1 and
		s1.StationNum = 1
		)
order by s1.StationNum
) 
union
(
select  s1.StationNum, s1.StationName, s1.ArriveTime, s1.StartTime, 
		s1.YZprice, min(sc.YZCount) as YZCount, 
		s1.RZprice, min(sc.RZCount) as RZCount, 
		s1.YWSprice, min(sc.YWSCount) as YWSCount, 
		s1.YWZprice, min(sc.YWZCount) as YWZCount, 
		s1.YWXprice, min(sc.YWXCount) as YWXCount,
		s1.RWSprice, min(sc.RWSCount) as RWSCount, 
		s1.RWXprice, min(sc.RWXCount) as RWXCount, /*在余座表大于起点站小于该站，求余座最小值,为正确余票数*/
		t1.YZflag, t1.RZflag, t1.YWSflag, t1.YWZflag, t1.YWXflag, t1.RWSflag, t1.RWXflag,
		s1.Forbid
from Station as s1, SeatCount as sc , Trains as t1
where ( s1.TrainID = 'G101' and
		sc.TrainID = 'G101' and
		t1.TrainID = 'G101' and 
		sc.StartDate = '2018/11/23' and
		((sc.StationNum <= s1.StationNum and 
		sc.InnerStationID > 1)
		))
group by s1.InnerStationID, s1.StationName, s1.ArriveTime, s1.StartTime,
		 s1.YZprice, s1.RZprice, s1.YWSprice, s1.YWZprice, s1.YWXprice,
		 s1.RWSprice, s1.RWXprice, 
		 t1.YZflag, t1.RZflag, t1.YWSflag, t1.YWZflag, t1.YWXflag, t1.RWSflag, t1.RWXflag,
		 s1.Forbid
order by s1.StationNum
);
