<?php
session_start();
header("Content-Type: text/html;charset=utf-8"); 
$dbconn = pg_connect("dbname=test user=dbms password=dbms")
or die('Could not connect: ' . pg_last_error());

$orderid = $_REQUEST['orderid'];
$query = "update orders set Status=0 where orderid='$orderid';";
pg_query($query);
$query2 = "select trainid,startdate,StartStationNum,EndStationNum,SeatType from orders where orderid ='$orderid';";
pg_query($query2);
$result = pg_query($query2) or die('Query2 failed: '.pg_last_error());
while ( $line = pg_fetch_array( $result, null, PGSQL_BOTH ) ){
  $trainid = $line[0];
  $startdate = $line[1];//?!
  $beginnum = $line[2];
  $endnum = $line[3];
  $seattype = $line[4];

  $seatquery = "update SeatCount set ";
  switch ($seattype) {
    case 0:$seatquery .="YZCount = YZCount + 1 ";break;
    case 1:$seatquery .="RZCount = RZCount + 1";break;
    case 2:$seatquery .="YWSCount = YWSCount + 1";break;
    case 3:$seatquery .="YWZCount = YWZCount + 1";break;
    case 4:$seatquery .="YWXCount = YWXCount + 1";break;
    case 5:$seatquery .="RWSCount = RWSCount + 1";break;
    case 6:$seatquery .="RWXCount = RWXCount + 1";break;
    default:echo "<script>alert('error!'); window.location.href='homepage.php';</script>";break;
  }
  $seatquery .= " where TrainID = '$trainid' and StartDate = '$startdate' and StationNum > '$beginnum' and StationNum <= '$endnum';";
  pg_query($seatquery);
  echo $seatquery;
}
pg_free_result($result);
pg_close($dbconn);
echo "<script>alert('取消订单成功'); window.location.href='homepage.php';</script>";
?>