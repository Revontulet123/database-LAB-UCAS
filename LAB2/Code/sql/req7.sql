/*查询历史订单*/
select OrderID,
          Orders.StartDate, 
          s1.StationName, 
          s2.StationName, 
          Sum(Orders.Price), 
          Orders.Status, 
          Orders.TrainID,
          s1.StartTime, 
          s2.ArriveTime, 
          Orders.SeatType, 
          Orders.price,
          Orders.StartStationNum,
          Orders.EndStationNum,
          Orders.TrainStartDate
        from Orders, Stations as s1, Stations as s2
        where s1.StationID = Orders.StartStationID and
        s2.StationID = Orders.EndStationID and
        s1.TrainID = Orders.TrainID and/*限定是orders里才有的车次*/
        s2.TrainID = Orders.TrainID and
        Orders.Username = 'bob' and
        Orders.StartDate >= '2018-11-23' and/*查询日期范围*/
        Orders.StartDate <= '2018-11-25'
        group by OrderID, 
          Orders.StartDate, 
          s1.StationName, 
          s2.StationName,
          Orders.Status, 
          Orders.TrainID,
          s1.StartTime, 
          s2.ArriveTime, 
          Orders.SeatType, 
          Orders.price,
          Orders.StartStationNum,
          Orders.EndStationNum,
          Orders.TrainStartDate       
        order by OrderID;


