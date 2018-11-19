<?php
session_start();
header("Content-Type: text/html;charset=utf-8"); 
$dbconn = pg_connect("dbname=test user=dbms password=dbms")
or die('Could not connect: ' . pg_last_error());

$user = $_SESSION['loginname'];
$ifchange =$_REQUEST['ifchange'];
if ($ifchange=='0'){
  $OrderID = $_REQUEST['orderid'];
  $trainid = $_REQUEST['trainid'];
  $startdate = $_REQUEST['startdate'];
  $Trainstartdate = $_REQUEST['TrainStartDate'];
  $beginid = $_REQUEST['beginid'];
  $endid = $_REQUEST['endid'];
  $beginnum = $_REQUEST['beginnum'];
  $endnum = $_REQUEST['endnum'];
  $seattype = $_REQUEST['seattype'];
  $totalprice = $_REQUEST['totalprice'];

  $query = "Insert into Orders
  values('$user',
  '$OrderID',
  '$trainid',
  '$startdate',
  '$Trainstartdate',
  '$beginid',
  '$endid',
  '$beginnum',
  '$endnum',
  '$seattype',
  '$totalprice',1);";
  $result = pg_query($query) or die('Query failed: '.pg_last_error());

  $seatquery = "update SeatCount set ";
  switch ($seattype) {
    case 0:$seatquery .="YZCount = YZCount - 1 ";break;
    case 1:$seatquery .="RZCount = RZCount - 1";break;
    case 2:$seatquery .="YWSCount = YWSCount - 1";break;
    case 3:$seatquery .="YWZCount = YWZCount - 1";break;
    case 4:$seatquery .="YWXCount = YWXCount - 1";break;
    case 5:$seatquery .="RWSCount = RWSCount - 1";break;
    case 6:$seatquery .="RWXCount = RWXCount - 1";break;
    default:echo "<script>alert('error!'); window.location.href='homepage.php';</script>";break;
  }
  $seatquery .= " where TrainID = '$trainid' and startdate = '$startdate' and StationNum > '$beginnum' and StationNum <= '$endnum';";
  pg_query($seatquery);
  echo $seatquery;
}elseif($ifchange=='1')
{
  $OrderID = $_REQUEST['orderid'];

  $trainid1 = $_REQUEST['trainid1'];
  $startdate1 = $_REQUEST['startdate1'];
  $Trainstartdate1 = $_REQUEST['TrainStartDate1'];
  $beginid1 = $_REQUEST['beginid1'];
  $endid1 = $_REQUEST['endid1'];
  $beginnum1 = $_REQUEST['beginnum1'];
  $endnum1 = $_REQUEST['endnum1'];
  $seattype1 = $_REQUEST['seattype1'];
  $totalprice1 = $_REQUEST['totalprice1'];

  $trainid2 = $_REQUEST['trainid2'];
  $startdate2 = $_REQUEST['startdate2'];
  $Trainstartdate2 = $_REQUEST['TrainStartDate2'];
  $beginid2 = $_REQUEST['beginid2'];
  $endid2 = $_REQUEST['endid2'];
  $beginnum2 = $_REQUEST['beginnum2'];
  $endnum2 = $_REQUEST['endnum2'];
  $seattype2 = $_REQUEST['seattype2'];
  $totalprice2 = $_REQUEST['totalprice2'];

  $query = "Insert into Orders
  values('$user','$OrderID','$trainid1','$startdate1','$Trainstartdate1','$beginid1','$endid1','$beginnum1','$endnum1','$seattype1','$totalprice1',1);
  Insert into Orders
  values('$user','$OrderID','$trainid2','$startdate2','$Trainstartdate2','$beginid2','$endid2','$beginnum2','$endnum2','$seattype2','$totalprice2',1);";
  $result = pg_query($query) or die('Query failed: '.pg_last_error());

  $seatquery1 = "update SeatCount set ";
  switch ($seattype1) {
    case 0:$seatquery1 .="YZCount = YZCount - 1 ";break;
    case 1:$seatquery1 .="RZCount = RZCount - 1";break;
    case 2:$seatquery1 .="YWSCount = YWSCount - 1";break;
    case 3:$seatquery1 .="YWZCount = YWZCount - 1";break;
    case 4:$seatquery1 .="YWXCount = YWXCount - 1";break;
    case 5:$seatquery1 .="RWSCount = RWSCount - 1";break;
    case 6:$seatquery1 .="RWXCount = RWXCount - 1";break;
    default:echo "<script>alert('error!'); window.location.href='homepage.php';</script>";break;
  }
  $seatquery1 .= " where TrainID = '$trainid1' and startdate = '$startdate1' and StationNum > '$beginnum1' and StationNum <= '$endnum1';";
  pg_query($seatquery1);
  echo $seatquery1;

  $seatquery2 = "update SeatCount set ";
  switch ($seattype2) {
    case 0:$seatquery2 .="YZCount = YZCount - 1 ";break;
    case 1:$seatquery2 .="RZCount = RZCount - 1";break;
    case 2:$seatquery2 .="YWSCount = YWSCount - 1";break;
    case 3:$seatquery2 .="YWZCount = YWZCount - 1";break;
    case 4:$seatquery2 .="YWXCount = YWXCount - 1";break;
    case 5:$seatquery2 .="RWSCount = RWSCount - 1";break;
    case 6:$seatquery2 .="RWXCount = RWXCount - 1";break;
    default:echo "<script>alert('error!'); window.location.href='homepage.php';</script>";break;
  }
  $seatquery2 .= " where TrainID = '$trainid2' and startdate = '$startdate2' and StationNum > '$beginnum2' and StationNum <= '$endnum2';";
  pg_query($seatquery2);
  echo $seatquery2;
}else{
  echo "<script>alert('ifchange error in success'); window.location.href='homepage.php';</script>";
}
  echo "<script>alert('生成订单成功'); window.location.href='homepage.php';</script>";

?>
