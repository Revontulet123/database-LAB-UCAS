<?php
    session_start();
    header('Content-type:text/html;charset=utf-8');
    if(isset($_SESSION['loginname'])){
            session_unset();//free all session variable
            session_destroy();//销毁一个会话中的全部数据
            //setcookie(session_name(),'',time()-3600);
            echo "<script>alert('成功退出'); window.location.href='homepage.php';</script>";
        }else{
            echo "<script>alert('error?没登录怎么退?');history.go(-1);</script>";
        }
?>