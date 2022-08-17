<?php
 
  header("Content-Type: application/json");
require_once("config.php");
if(isset($_GET["key"])){
switch ($_GET['key']) {
    case 'msgdata':
        $connectdb = connectdb();
        $sql = "SELECT * FROM tb_message";
        $result = $connectdb->query($sql);
        $request =$result->fetch_all();
       
       echo json_encode($request);
        break;
      case 'countdata':
                    $connectdb = connectdb();
                    $sql = "SELECT count(*) FROM tb_message";
                    $result = $connectdb->query($sql);
                    $request =$result->fetch_all();
                   
                   echo json_encode($request);
                    break;
                    default:
                
                     break;
}
}else{
   
}