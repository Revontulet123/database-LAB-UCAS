/*总订单数*/
select Count(distinct OrderID) 
from Orders
where Status=1;

/*总票价*/
select Sum(OrderPrice)
from Orders
where Status=1;

/*最热点车次排序*/
select TrainID, Count(*) as OrderTimes
from Orders
where Status=1
group by TrainID
order by OrderTimes desc limit 10;

/*当前注册用户列表*/
select *
from users;




