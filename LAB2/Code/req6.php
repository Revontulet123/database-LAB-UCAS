<?php session_start();?>
<!doctype html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Light Theme</title>
<link href="css/singlePageTemplate.css" rel="stylesheet" type="text/css">
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/source-sans-pro:n2:default.js" type="text/javascript"></script>
</head>
<body>
<div class="container"> 
  <header> <a href="homepage.php">
    <h4 class="logo">12306主页</h4>
    </a>
    <nav>
      <ul>
        <?php
        $user = $_SESSION['loginname'];
        if($user==""){
        echo "<li><a href=userlogin.php>用户登录</a></li><li><a href=adminlogin.php>管理员登录</a></li>";
        }else{
        echo "<li>欢迎!".$_SESSION['loginname']."</li>"
        ."<li><a href=myorder.php>我的订单</a></li>"
        ."<li><a href=quit.php>退出</a></li>";
        }
        ?>
      </ul>
    </nav>
  </header>
  <section class="banner">
    <h2 class="parallax"><?php echo $_SESSION['loginname']."您好!订单如下:" ?></h2>
    <div class="parallax_description">
<?php
  $user = $_SESSION['loginname'];
  if($user=="")
  {
    echo "<script>alert('先登录再订票！');window.location.href='userlogin.php';</script>"; 
  }
  $ifchange = $_REQUEST['ifchange'];
  if($ifchange == '0'){
    //需求4,5单次订票
    $OrderID = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    
    $trainid = $_REQUEST['trainid'];
    $startdate = $_REQUEST['startdate'];
    $TrainStartDate = $_REQUEST['realstartdate'];
    $beginid = $_REQUEST['beginoutid'];
    $endid = $_REQUEST['endoutid'];
    $beginnum = $_REQUEST['beginnum'];
    $endnum = $_REQUEST['endnum'];
    $seattype = $_REQUEST['seattype'];
    $price = $_REQUEST['price'];$totalprice = $price + 5;

    $startstationname = $_REQUEST['startstation']; 
    $arrivestationname = $_REQUEST['arrivestation']; 
    $arrivetime = $_REQUEST['arrivetime']; 
    $starttime = $_REQUEST['starttime']; 

    $buf .= "";
    $buf .= "订单号:".$OrderID."</br>";
    $buf .= "列车号:".$trainid."</br>";
    $buf .= "起点站:".$startstationname."</br>";
    $buf .= "终点站:".$arrivestationname."</br>";
    $buf .= "出发日期:".$startdate."</br>";
    $buf .= "出发时间:".$starttime."</br>";
    $buf .= "座位类型:";
    switch($seattype)
    {
      case 0 : $buf .= "硬座</br>"; break;
      case 1 : $buf .= "软座</br>"; break;
      case 2 : $buf .= "硬卧上</br>"; break;
      case 3 : $buf .= "硬卧中</br>"; break;
      case 4 : $buf .= "硬卧下</br>"; break;
      case 5 : $buf .= "软卧上</br>"; break;
      case 6 : $buf .= "软卧下</br>"; break;            
    }
    $buf .= "票价:".$price."</br>";
    $buf .= "订票费: 5元/张 </br>";
    $buf .= "总价：".$totalprice."</br>";
   
    $buf .= "<a href='req6-success.php"
      ."?orderid=".$OrderID
      ."&ifchange=0"
      ."&trainid=".$trainid
      ."&startdate=".$startdate
      ."&TrainStartDate=".$TrainStartDate
      ."&beginid=".$beginid
      ."&endid=".$endid
      ."&beginnum=" .$beginnum
      ."&endnum=".$endnum
      ."&seattype=".$seattype
      ."&totalprice=".$totalprice
      ."'><button type='button'>确认生成订单</button></a>";
    $buf .= "<br>";
    $buf .= "<a href='homepage.php'><button type='button'>取消</button></a>";
    echo $buf;
  }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  elseif($ifchange == '1')
  {
    //需求5换乘订票
    $OrderID = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $city1 = $_REQUEST['city1'];
    $city2 = $_REQUEST['city2'];
    $city3 = $_REQUEST['city3'];

    $trainid1 = $_REQUEST['trainid1'];
    $startdate1 = $_REQUEST['startdate1'];
    $TrainStartDate1 = $_REQUEST['realstartdate1'];
    $beginid1 = $_REQUEST['beginoutid1'];
    $endid1 = $_REQUEST['endoutid1'];
    $beginnum1 = $_REQUEST['beginnum1'];
    $endnum1 = $_REQUEST['endnum1'];
    $seattype1 = $_REQUEST['seattype1'];
    
    $startstationname1 = $_REQUEST['startstation1']; 
    $arrivestationname1 = $_REQUEST['arrivestation1']; 
    $arrivetime1 = $_REQUEST['arrivetime1']; 
    $starttime1 = $_REQUEST['starttime1']; 

    $trainid2 = $_REQUEST['trainid2'];
    $startdate2 = $_REQUEST['startdate2'];
    $TrainStartDate2 = $_REQUEST['realstartdate2'];
    $beginid2 = $_REQUEST['beginoutid2'];
    $endid2 = $_REQUEST['endoutid2'];
    $beginnum2 = $_REQUEST['beginnum2'];
    $endnum2 = $_REQUEST['endnum2'];
    $seattype2 = $_REQUEST['seattype2'];
    
    $startstationname2 = $_REQUEST['startstation2']; 
    $arrivestationname2 = $_REQUEST['arrivestation2']; 
    $arrivetime2 = $_REQUEST['arrivetime2']; 
    $starttime2 = $_REQUEST['starttime2'];

    $buf .= "";
    $buf .= "订单号:".$OrderID."</br>";
    $buf .= "路线:".$city1."--->".$city2."--->".$city3."<br>";
    $buf .= "列车号1:".$trainid1."</br>列车号2:".$trainid2."</br>";
    $buf .= "起点站1:".$startstationname1."  起点站2:".$startstationname2."</br>";
    $buf .= "终点站1:".$arrivestationname1."  终点站2:".$arrivestationname2."</br>";
    $buf .= "出发日期:".$startdate1."</br>";
    $buf .= "出发时间:".$starttime1."</br>";
    $buf .= "列车1座位类型:";
    switch($seattype1)
    {
      case 0 : $buf .= "硬座 <br>";$price1=$_REQUEST['yzprice1']; break;
      case 1 : $buf .= "软座 <br>";$price1=$_REQUEST['rzprice1']; break;
      case 2 : $buf .= "硬卧上<br>";$price1=$_REQUEST['ywsprice1']; break;
      case 3 : $buf .= "硬卧中<br>";$price1=$_REQUEST['ywzprice1']; break;
      case 4 : $buf .= "硬卧下<br>";$price1=$_REQUEST['ywxprice1']; break;
      case 5 : $buf .= "软卧上<br>";$price1=$_REQUEST['rwsprice1']; break;
      case 6 : $buf .= "软卧下<br>";$price1=$_REQUEST['rwxprice1']; break;            
    }
    $buf .= "列车2座位类型:";
    switch($seattype2)
    {
      case 0 : $buf .= "硬座 <br>";$price2=$_REQUEST['yzprice2']; break;
      case 1 : $buf .= "软座 <br>";$price2=$_REQUEST['rzprice2']; break;
      case 2 : $buf .= "硬卧上<br>";$price2=$_REQUEST['ywsprice2']; break;
      case 3 : $buf .= "硬卧中<br>";$price2=$_REQUEST['ywzrice2']; break;
      case 4 : $buf .= "硬卧下<br>";$price2=$_REQUEST['ywxrice2']; break;
      case 5 : $buf .= "软卧上<br>";$price2=$_REQUEST['rwsprice2']; break;
      case 6 : $buf .= "软卧下<br>";$price2=$_REQUEST['rwxprice2']; break;            
    }
    $totalprice1 = $price1 + 5;
    $totalprice2 = $price2 + 5;
    $buf .= "票价1:￥".$price1."  票价2:￥".$price2."</br>";
    $buf .= "订票费: ￥5/张 </br>";
    $totalprice = $totalprice1 + $totalprice2;
    $buf .= "总价：￥".$totalprice."</br>";
   
    $buf .= "<a href='req6-success.php"
      ."?ifchange=1"
      
      ."&orderid=".$OrderID

      ."&trainid1=".$trainid1
      ."&startdate1=".$startdate1
      ."&TrainStartDate1=".$TrainStartDate1
      ."&beginid1=".$beginid1
      ."&endid1=".$endid1
      ."&beginnum1=" .$beginnum1
      ."&endnum1=".$endnum1
      ."&seattype1=".$seattype1
      ."&totalprice1=".$totalprice1

      ."&trainid2=".$trainid2
      ."&startdate2=".$startdate2
      ."&TrainStartDate2=".$TrainStartDate2
      ."&beginid2=".$beginid2
      ."&endid2=".$endid2
      ."&beginnum2=" .$beginnum2
      ."&endnum2=".$endnum2
      ."&seattype2=".$seattype2
      ."&totalprice2=".$totalprice2

      ."'><button type='button'>确认生成订单</button></a>";
    $buf .= "<br>";
    $buf .= "<a href='homepage.php'><button type='button'>取消</button></a>";
    echo $buf;
  }
  else{
    echo "<script>alert('ifchange error in req6'); window.location.href='homepage.php';</script>";
  }
?>
    </div>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
