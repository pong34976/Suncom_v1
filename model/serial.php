<?php
 session_start();
  header("Content-Type: application/json");
  error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

function shutDownFunction() {
  $error = error_get_last();
   // Fatal error, E_ERROR === 1
  if ($error['type'] === E_ERROR) {
       $nodata["bg"] = "bg-danger";
       $nodata["message"] = "ERROR 404  โปรแกรมหยุดทำงานแบบไม่คาดคิด!!!";
       $nodata["errcode"] = $error;
       echo json_encode($nodata);    
  }
}
register_shutdown_function('shutDownFunction');
 
function adapterdb($command){
          $arr   = array( 
 
        'serial_id',
        'serial_agency',
        'phones',
        'room', 
        'id_hardware',
        're_type',
        're_model'
     );
 
  switch ($command) {
    case 'arr':
   
      break;
    case 'implode':
      $arr = implode(",",$arr);
      break;
      case 'table':
        $arr ="v_serial";
        break;
    default:
      # code...
      break;
  }


return $arr;
}
 
require_once("../config.php");
 
if(isset($_GET["keyword"])){
switch ($_GET['keyword']) {
  case 'session':
    
    $request["session"]["id"] = session_id();
  $request["session"]["name"] =  session_name() ;
  $request["session"]["module"] =  session_module_name()  ;
    $request["session"]["path"] =  session_save_path()  ;
    $request["session"]["limiter"] =  session_cache_limiter() ;
    $request["session"]["expire"] =  session_cache_expire() ;

      $request["session"]["encode"] =  session_encode() ;
   $request["session"]["decode"] =  session_decode($request["session"]["encode"]) ;

   $request["session"]["list"] =  $_SESSION ;
  //  setcookie ( "username", "bamboo", time() + 3600 );
   $request["cookie"]["list"] = $_COOKIE;

    echo json_encode($request);
    break;  

    case 'view':
 
      $request = show();
      echo json_encode($request);

        break;
        case 'select':
          $_POST["token"]=session_id();
          $request = show("serial_id");
          $json= json_encode($request);
    
            break;
        case 'hardware':
          $request =  hardware();
          echo json_encode($request);
    
            break;
        case 'add':

        if(isset($_POST["token"])){
    if($_POST["token"]==session_id()){
      // if($_POST["re_date_received"]==""){
      //   $nodata["bg"] = "bg-warning";
      //   $nodata["message"] = "!! วันที่ต้องไม่เว้นว่าง";
      //   echo json_encode($nodata);
      //   die();
      // }
      add($_POST);
    }else{
      $nodata["bg"] = "bg-warning";
      $nodata["message"] = "token ไม่ถูกต้อง";
            echo json_encode($nodata);    
    }
     

           }else{
            $nodata["bg"] = "bg-danger";
            $nodata["message"] = "token ไม่ถูกต้อง";
            echo json_encode($nodata);      
           }
    
          break; 
          case 'edit':

            if(isset($_POST["token"])){
        if($_POST["token"]==session_id()){
           
          update($_POST);
        }else{
          $nodata["bg"] = "bg-warning";
          $nodata["message"] = "token ไม่ถูกต้อง";
                echo json_encode($nodata);    
        }
         
    
               }else{
                $nodata["bg"] = "bg-danger";
                $nodata["message"] = "token ไม่ถูกต้อง";
                echo json_encode($nodata);      
               }
        
              break; 
                    default:
                    $nodata["bg"] = "bg-danger";
  $nodata["message"] = "keyword ไม่ถูกต้อง";
    echo json_encode($nodata);           
                     break;
}
}else{
  $nodata["bg"] = "bg-danger";
  $nodata["message"] = "ไม่มีการส่งค่า keyword";
  echo json_encode($nodata);
}
function ckstring($text,$alerts ){
  if(isset($_POST[$text]) && !empty($_POST[$text]) && trim($_POST[$text])){
            
      $texts=htmlentities($_POST[$text]);
  }else{
      // echo  "<h5 class='alert alert-danger'>".$alerts."</h5>";
     
 
      return $alerts;
  }
 return $texts;
}

function show($command=""){ 
  $mysqli = connectdb();
 
  
  $requestData= $_REQUEST;

  //ฟิลด์ที่จะเอามาแสดงและค้นหา
  $columns = adapterdb("columns");
  $table = adapterdb("table");
  $search = "";



  if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $search.=" AND ( ";
    $i = 0;
    foreach($columns as $columnname)
    {
      $search.= $i==0 ? "" : " OR ";
      $search.= $columnname." LIKE '%".$requestData['search']['value']."%' ";
      $i++;
    }
    $search.=")";
  }
  // getting total number records without any search
  $query = "SELECT count(*) AS num_rows ";
  $query.=" FROM `".$table."` WHERE 1=1 ".$search;
  
  $getRes = $mysqli->query($query);
  $row = $getRes->fetch_assoc();
  $totalData = $row['num_rows']; 
  $totalFiltered = $totalData;
  if($command=="serial_id"){
    $serial_id =  $_GET["serial_id"] ;
     $query = "SELECT *  FROM `".$table."` WHERE serial_id='$serial_id' " ;
 
 
  
    }else{
      $query = "SELECT ".adapterdb("implode");
      $query.="    FROM $table WHERE 1=1 ".$search;
      $query.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start'].",".$requestData['length']." ";
     
    }
 // $nodata["bg"] = "bg-warning";
  // $nodata["message"] =  $query;
  //       echo json_encode($nodata); 
  // die();
  
  $getRes2 = $mysqli->query($query);
   

  $data = array();
  while($row = $getRes2->fetch_assoc())
  {
    $nestedData=array(); 
    foreach($columns as $columnname)
    {
      $nestedData[] = $row[$columnname];
    }	
    $data[] = $nestedData;
  }
 
  $json_data = array(

        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "columns" => $columns, // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data"            => $data   // total data array
        );

  $show["alert"]=    $query;
 
return    $json_data ;
}
 
  function add($data){
    $columns = adapterdb("columns");
    $table = "tb_serial";
    $VALUES = "";
 
    $columns =  array_diff($columns, ["re_type", "re_model"]);
 
   foreach($columns as $columnname)
   {
    if(isset($data[$columnname])){
      $row[$columnname]="'" . $data[$columnname] . "'";
      if( $data[$columnname]==""){
        $row[$columnname]="NULL";
        
  
         }
       }else{
        $row[$columnname]="NULL";

       }
 
   }
   
  //  $row["id_serial"] = "NULL";
  $columnss = implode(",",$columns);
   $rows = implode(",",$row);
  $connectdb = connectdb();
  // INSERT INTO `tb_serial` (`id_serial`, `name_serial`, `number_serial`, `details_serial`) VALUES ('0', '0', '0', '0');
  $sql = "INSERT INTO `".$table."` ( ". $columnss.") VALUES ( $rows );";
  // $nodata["bg"] = "bg-success";
  // $nodata["message"] =  $sql  ;
  // echo json_encode($nodata);
  // die();
        if ($connectdb->query($sql) === TRUE) {
          $nodata["bg"] = "bg-success";
          $nodata["message"] = "ข้อมูลถูกบันทึกเรียบร้อย เลขรับ : ". $connectdb->insert_id  ;
          echo json_encode($nodata);

          // $connectdb->exit();
          return  $connectdb->insert_id;
        }else{
          $nodata["bg"] = "bg-warning";
          $nodata["message"] = "ไม่สามารถบันทึก ข้อมูลได้ ";
          echo json_encode($nodata);
         
          return false;
        }
}

function update($data){
  $columns = adapterdb("columns");
  $table = "tb_serial";
  $noteupdate="";
  $v = 0;
  $columns =  array_diff($columns, ["re_type", "re_model"]);
 foreach($columns as $columnname)
 {
 
  if(isset($data[$columnname])){
    if($v!=0){
      $noteupdate.= ",";
      
     }
     $v=$v+1;
 
    $row[$columnname]=$data[$columnname];
    $noteupdate.="`$columnname` = '".$row[$columnname]."'"; 
 
     }else{
      
      // $row[$columnname]="null";
      // $noteupdate.="`$columnname` = '".$row[$columnname]."'";
     }
 }
  $connectdb = connectdb();
  $sql = "UPDATE `".$table."` SET   $noteupdate WHERE `$table`.`serial_id` = ".$row['serial_id'].";";
 
  if ($connectdb->query($sql) === TRUE) {
    $nodata["bg"] = "bg-success";
    $nodata["message"] =  "เลขรับ : ".$row['serial_id'] . " ข้อมูลถูกแก้ไข "   ;
    echo json_encode($nodata);

    // $connectdb->exit();
    return  $connectdb->insert_id;
  }else{
    $nodata["bg"] = "bg-warning";
    $nodata["message"] = "ไม่สามารถแก้ไข ข้อมูลได้ " . $sql ;
    echo json_encode($nodata);
   
    return false;
  }
}