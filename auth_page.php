<?php
 session_start();
//  header("Content-Type: application/json");
 error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
    //  header("Content-Type: application/json");
function shutDownFunction() {

 $error = error_get_last();
  // Fatal error, E_ERROR === 1
  define('ERRS','<h1 class="alert alert-danger">ERROR 404  โปรแกรมหยุดทำงานแบบไม่คาดคิด!!!  </h1>'); 
 if ($error['type'] === E_ERROR) {
//  echo '<h1 class="alert alert-danger">ERROR 404  โปรแกรมหยุดทำงานแบบไม่คาดคิด!!!  </h1>';
      define('ERRS','<h1 class="alert alert-danger">ERROR 404  โปรแกรมหยุดทำงานแบบไม่คาดคิด!!!  </h1>'); 
 }

}
register_shutdown_function('shutDownFunction');
 
define('ERRS',''); 
 
  
?>

<style>
@font-face {
    font-family: home;
    src: url("css/font/FC Home Thin ver 1.01.otf") format("opentype");
}

@font-face {
    font-family: homebold;
   
    src: url("css/font/FC Home Black ver 1.01.otf") format("opentype");
}

*{
    font-family: 'home';
    font-size: 22px;
}
h1,h2,h3,h4{
    font-family: 'homebold' !important;
    /* font-size: 30px !important; */
    
}
#myTables td {
    font-size: 30px;
}
#myTables thead td   {
    font-family: 'homebold' !important;
    font-size: 30px;
}
 </style>
<?php 

session_start();
// $_SESSION["level"] = 1;
if(isset($_COOKIE["PHPSESSID"])){
    define("TOKEN",$_COOKIE["PHPSESSID"]);
}
 
define('VER',"BETA 2.9.23 (4/3/65)");
//  บันทึก 
// 1.0.3 เปลี่ยนโครงสร้าง จาก php เป็นหลัก มาใช้ json เข้าร่วม 
// 2.8.5 เปลี่ยนโครงสร้าง contronใหม่,เพิ่มเมนู รายการซ่อม หม่วดหมู่,รายการซ่อมเพิ่ม CRU-D
// 2.9.15 มีการเพิ่ม VER แบบ define , แก้ไขข้อผิดพาด หน้ารายการซ่อมเพิ่ม ภาพไม่ updete ตอนแก้ไข
define('ERR4','<h1 class="alert alert-danger">! Error404  </h1>');
 
if(isset($_GET['page']) && !empty($_GET['page'])){
   
    define(strtoupper($_GET["page"]),"text-dark btn btn-warning   ");
  
}
if(isset($_SESSION["level"]) ){
define("LEVEL",$_SESSION["level"]);
switch (LEVEL) {
    case 1:
        require_once("temadmin.html");
        break;
    
    default:
    
    // header("Location: login.php");
    echo ERR4;
    echo "คุณไม่มีสิทธิ์เข้าใช้งานในหน้านี้ กรุณาติดต่อผู้ดูแลระบบ หรือกลับไปที่หน้า  >><a href='login.php'>LOGIN</a><<<";
        break;
}
 }else{
    define("LEVEL",0);
    require_once("temadmin.html");
    //  header("Locaction: logout.php");
     die();
 }