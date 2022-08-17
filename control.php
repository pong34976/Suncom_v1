
<?php
// define('ERR4','<h1 class="alert alert-danger">Error404</h1>');
echo ERRS;
 ob_start(); 
require_once("config.php");

 $ver = ""; 
if(isset($_GET['page']) && !empty($_GET['page'])  && isset($_GET['view']) && !empty($_GET['view'])){
    function ckdate($strDate,$th=false){
          

         
        $strYear = date("Y",strtotime($strDate));
       $strMonth= date("m",strtotime($strDate));
         $strDay= date("d",strtotime($strDate));
       
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
      
        if($th==true){
            
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
            // $strYear = date("Y",strtotime($strDate));
              $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear ";    
    }else{
        //  echo "$strYear-$strMonth-$strDay";
        
        return "$strYear-$strMonth-$strDay";  
    }
    //    return $texts;
    }
 
 
switch ($_GET["page"]) {
    case 'pmis':
        require_once("pmis_col.php");
    break;
    case 'repair':
        // require_once("repair_col.php");
        require_once("list_repair.html");
    break;
    case 'hardware':
        // require_once("repair_col.php");
        require_once("hardware.html");
    break;
       case 'serial':
        // require_once("repair_col.php");
        require_once("serial.html");
    break;
    case 'user':
        require_once("user_col.php");
    break;
       case 'housing':
       require_once("housing_col.php");
    break;
    case 'equip':
        require_once("equip.html");
     break;
    case 'phones':
        require_once("phones_col.php");
     break;
    default:
   echo ERR4;
        break;
}
}else{
    // echo ERR4; 
    
    header("Refresh: 1; url=auth_page.php?view=list&page=phones");
}