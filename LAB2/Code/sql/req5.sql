/*Nonstopticket*/
/*查询直达的火车票*/
select  ns.MinPrice, 
        ns.Tripmin, 
		ns.BeginCityName,
		ns.DestCityName, 
		ns.TrainID,
		(sc.StartDate + ns.BeginGap) :: date as StartDate, 
		(sc.StartDate + ns.EndGap ):: date as EndDate,
		ns.BeginStationName,
		ns.DestStationName,
		ns.BeginTime, 
		ns.EndTime, 
		min(sc.YZCount) as YZCount,  
		min(sc.RZCount) as RZCount, 
		min(sc.YWSCount) as YWSCount,
		min(sc.YWZCount) as YWZCount, 
		min(sc.YWXCount) as YWXCount, 
		min(sc.RWSCount) as RWSCount,
		min(sc.RWXCount) as RWXCount, 
		ns.YZprice,  
		ns.RZprice, 
		ns.YWSprice, 
		ns.YWZprice, 
		ns.YWXprice, 
		ns.RWSprice, 
		ns.RWXprice,
		ns.YZflag,   
		ns.RZflag , 
		ns.YWSflag , 
		ns.YWZflag,
		ns.YWXflag, 
		ns.RWSflag , 
		ns.RWXflag,   
        ns.BeginStationID, 
        ns.DestStationID, 
        ns.BeginStationNum, 
        ns.DestStationNum
into NonstopTicket
from  	Nonstop as ns, SeatCount as sc
where 	(ns.BeginTime - time '00:00') > '0 min' and /*默认用户要求时间为00:00*/
		(sc.StartDate + ns.BeginGap) = '2018-11-23'and/*默认时间为18/11/23*/
		ns.BeginCityName = '北京'and ns.DestCityName = '南通' and /*默认北京到南通*/
		ns.TrainID = sc.TrainID and sc.StationNum > ns.BeginStationNum and 
		sc.StationNum <= ns.DestStationNum	               
group by 
		ns.Minprice,ns.Tripmin,
		ns.BeginCityName,ns.DestCityName, 		
		ns.TrainID, ns.BeginGap,ns.EndGap,
		sc.StartDate, 
		ns.BeginStationName,ns.DestStationName,
		ns.BeginTime, ns.EndTime,
		ns.YZprice,ns.RZprice, ns.YWSprice, ns.YWZprice, ns.YWXprice, ns.RWSprice, ns.RWXprice,
		ns.YZflag, ns.RZflag , ns.YWSflag , ns.YWZflag,ns.YWXflag, ns.RWSflag , ns.RWXflag,
		ns.BeginStationID, 
        ns.DestStationID, 
        ns.BeginStationNum, 
        ns.DestStationNum
order by MinPrice, Tripmin, BeginTime/*根据票价，总时间，起始时间排序*/
asc limit  10 offset  0;


/*查询一次换乘的火车票*/
/*从Nonstop中挑选两个直达，组成换乘 */

		(select     
        (ns1.MinPrice + ns2.MinPrice )as FinalMinPrice,
        (ns1.Tripmin + ns2.Tripmin) as FinalTripMin,
        
        ns1.TrainID as Train1ID,    
        (sc1.StartDate + ns1.BeginGap) :: date as Train1StartDate, 
        (sc1.StartDate + ns1.EndGap) :: date as Train1EndDate, 
        ns1.BeginStationName,
        ns1.BeginCityName, 
        ns1.MinPrice as Train1MinPrice, 
        ns1.Tripmin as Train1TripMin,
        ns1.BeginTime as Train1BeginTime, 
        ns1.EndTime as Train1EndTime,   
        ns1.DestStationName as Train1DestStationName, 
        ns1.DestCityName as TransferCityName,
        
        ns2.TrainID as Train2ID, 
        (sc2.StartDate + ns2.BeginGap):: date as Train2StartDate,
        (sc2.StartDate + ns2.EndGap):: date as Train2EndDate,
        ns2.BeginStationName as Train2BeginStationName,
        ns2.MinPrice as Train2MinPrice, 
        ns2.Tripmin as Train2TripMin,
        ns2.DestStationName,  
        ns2.DestCityName,
        ns2.BeginTime as Train2BeginTime,
        ns2.EndTime as Train2EndTime,   

        ns1.YZprice  as Train1YZprice,  
        ns2.YZprice as Train2YZprice, 
        ns1.RZprice as Train1RZprice, 
        ns2.RZprice as Train2RZprice, 
        ns1.YWSprice as Train1YWSprice, 
        ns2.YWSprice as Train2YWSprice, 
        ns1.YWZprice as Train1YWZprice, 
        ns2.YWZprice as  Train2YWZprice, 
        ns1.YWXprice as Train1YWXprice,
        ns2.YWXprice as Train2YWXprice, 
        ns1.RWSprice as Train1RWSprice,
        ns2.RWSprice as Train2RWSprice, 
        ns1.RWXprice as Train1RWXprice, 
        ns2.RWXprice as Train2RWXprice,  

        min(sc1.YZCount)  as Train1YZCount,  
        min(sc1.RZCount) as Train1RZCount,
        min(sc1.YWSCount) as Train1YWSCount, 
        min(sc1.YWZCount)  as Train1YWZCount, 
        min(sc1.YWXCount) as Train1YWXCount, 
        min(sc1.RWSCount) as Train1RWSCount, 
        min(sc1.RWXCount) as Train1RWXCount,   
        min(sc2.YZCount)  as  Train2YZCount,  
        min(sc2.RZCount) as Train2RZCount,
        min(sc2.YWSCount) as Train2YWSCount, 
        min(sc2.YWZCount)  as Train2YWZCount, 
        min(sc2.YWXCount) as Train2YWXCount, 
        min(sc2.RWSCount) as Train2RWSCount, 
        min(sc2.RWXCount) as Train2RWXCount,   

        ns1.YZflag as Train1YZflag,           
        ns1.RZflag as Train1RZflag, 
        ns1.YWSflag as Train1YWSflag, 
        ns1.YWZflag as Train1YWZflag,
        ns1.YWXflag as Train1YWXflag ,
        ns1.RWSflag as Train1RWSflag, 
        ns1.RWXflag as Train1RWXflag,
        ns2.YZflag as Train2YZflag, 
        ns2.RZflag as Train2RZflag, 
        ns2.YWSflag as Train2YWSflag, 
        ns2.YWZflag as Train2YWZflag,
        ns2.YWXflag as Train2YWXflag, 
        ns2.RWSflag as Train2RWSflag, 
        ns2.RWXflag as Train2RWXflag,             
        (ns2.BeginTime - ns1.EndTime) :: time as WaitTime
into TransferTickets
from      Nonstop as ns1, Nonstop as ns2 , SeatCount as sc1, SeatCount sc2
where   
        ns1.BeginCityName = '北京'and ns2.DestCityName = '南通' and /*默认地点为北京到南通 */
        ns1.DestCityName = ns2.BeginCityName and 

        ((   (ns1.DestStationID = ns2.BeginStationID  /*当日换乘*/
        and (  ((ns2.BeginTime - ns1.EndTime)> '0 min' and ((ns2.BeginTime - ns1.EndTime) between interval'1 hour' and  interval'4 hour')) 
            ))  or
            (ns1.DestStationID <> ns2.BeginStationID  
        and (  ((ns2.BeginTime - ns1.EndTime)> '0 min' and ((ns2.BeginTime - ns1.EndTime) between interval'2 hour' and  interval'4 hour')) 
        ))and  ( sc2.StartDate + ns2.BeginGap) =(sc1.StartDate + ns1.EndGap) ) /*换乘时间关系*/
        or
        (   (ns1.DestStationID = ns2.BeginStationID  /*隔日换乘*/
        and (  ((ns2.BeginTime - ns1.EndTime)< '0 min'and ((ns2.BeginTime - ns1.EndTime+ interval'1 day') between interval'1 hour' and  interval'4 hour'))
            ))  or
            (ns1.DestStationID <>ns2.BeginStationID  
        and (   ((ns2.BeginTime - ns1.EndTime)< '0 min'and ((ns2.BeginTime - ns1.EndTime+ interval'1 day') between interval'2 hour' and  interval'4 hour'))
            ) )and  ( sc2.StartDate + ns2.BeginGap) =(sc1.StartDate + ns1.EndGap + '1 day')))
        and 
        (ns1.BeginTime - time'00:00:00') >= '0 min' and /*起始时间默认为00:00*/
        (sc1.StartDate + ns1.BeginGap) = '2018/11/23'  and
        ns1.TrainID = sc1.TrainID and sc1.StationNum > ns1.BeginStationNum and sc1.StationNum <= ns1.DestStationNum and/*从Nonstop中挑选*/
        ns2.TrainID = sc2.TrainID and 
        (sc2.StationNum > ns2.BeginStationNum and sc2.StationNum <= ns2.DestStationNum  ) 

group by
        ns1.BeginCityName,ns1.DestCityName ,ns2.DestCityName,
        ns1.TrainID ,sc1.StartDate, ns1.BeginGap,ns2.BeginGap,ns2.BeginGap,ns1.EndGap,ns2.EndGap,
        ns1.BeginStationName, ns1.DestStationName , Train1MinPrice, Train1TripMin,
        ns1.BeginTime, ns1.EndTime ,

        ns2.TrainID , sc2.StartDate,
        ns2.BeginStationName ,ns2.DestStationName, Train2MinPrice, Train2TripMin,
        ns2.BeginTime , ns2.EndTime,    

        ns1.YZprice ,  ns1.RZprice ,ns1.YWSprice ,ns1.YWZprice ,ns1.YWXprice, ns1.RWSprice,ns1.RWXprice,
        ns2.YZprice,ns2.RZprice ,  ns2.YWSprice ,  ns2.YWZprice, ns2.YWXprice,ns2.RWSprice,  ns2.RWXprice  ,
        Train1YZflag, Train1RZflag, Train1YWSflag, Train1YWZflag,
        Train1YWXflag , Train1RWSflag, Train1RWXflag,
        Train2YZflag, Train2RZflag,  Train2YWSflag, Train2YWZflag,
        Train2YWXflag , Train2RWSflag,  Train2RWXflag,
        WaitTime
 )
order by FinalMinPrice, FinalTripMin, Train1BeginTime, Train2BeginTime/*根据票价，总时间，起始时间排序*/
asc limit 10 offset 0;

