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
       echo json_encode($nodata);    
  }
}
register_shutdown_function('shutDownFunction');
 
function adapterdb($command){
          $arr   = array( 
 
        'id_repair', 
        're_date_received',
        're_due_date',
        're_serial_number',
        're_type',
        're_model',
        're_breakdown',
        're_operation',
        're_agency',
        're_assessor',
        're_status',
        're_sendreport',
        're_sendreturn'
     );
 
  switch ($command) {
    case 'arr':
   
      break;
    case 'implode':
      $arr = implode(",",$arr);
      break;
      case 'table':
        $arr ="tb_repair";
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
        case 'serial':
          $request =  serial();
          echo json_encode($request);
    
            break;
              case 'select':
          $request =  select();
          $json = json_encode($request);
    
            break;
        case 'add':

        if(isset($_POST["token"])){
    if($_POST["token"]==session_id()){
      if($_POST["re_date_received"]==""){
        $nodata["bg"] = "bg-warning";
        $nodata["message"] = "!! วันที่ต้องไม่เว้นว่าง";
        echo json_encode($nodata);
        die();
      }
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
          if($_POST["re_date_received"]==""){
            $nodata["bg"] = "bg-warning";
            $nodata["message"] = "!! วันที่ต้องไม่เว้นว่าง";
            echo json_encode($nodata);
            die();
          }
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
              case 'del':
                del($_POST);

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

// sss
 
  $query = "SELECT ".adapterdb("implode");
  $query.="  ,if(re_due_date='0000-00-00','-',re_due_date) as re_due_date FROM tb_repair WHERE 1=1 ".$search;
  $query.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  
      LIMIT ".$requestData['start'].",".$requestData['length']." ";
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

function select($command=""){ 
  $mysqli = connectdb();
 
  
  $requestData= $_REQUEST;
   
  //ฟิลด์ที่จะเอามาแสดงและค้นหา
 
  $table = adapterdb("table");
 
// sss
$search = "`id_repair`=".$_GET["id_repair"];
  $query = "SELECT    * FROM tb_repair WHERE ".$search;
 
  $getRes2 = $mysqli->query($query);
   
  $data = array();
 $row = $getRes2->fetch_assoc();
 
 
  $json_data = array(

        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "columns" => $columns, // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data"            =>  $row   // total data array
        );

  $show["alert"]=    $query;
 
return    $json_data ;
}
function serial($command=""){ 
  $mysqli = connectdb();
 
  $requestData= $_REQUEST;
   
//   //ฟิลด์ที่จะเอามาแสดงและค้นหา
  $columns = array( 
    'img' ,
    //  'serial_id', 
    //  'id_hardware',
     'serial_agency',
     're_type',
     're_model',
   

  );
  
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
//   // getting total number records without any search
  $query = "SELECT count(*) AS num_rows ";
  $query.=" FROM v_serial WHERE 1=1 ".$search;
  
  $getRes = $mysqli->query($query);
  $row = $getRes->fetch_assoc();
  $totalData = $row['num_rows']; 
  $totalFiltered = $totalData;
 
 
  $query = "SELECT ".implode(",",$columns);
  $query.=" FROM v_serial WHERE 1=1 ".$search;
 
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
    $table = adapterdb("table");
    $VALUES = "";
   foreach($columns as $columnname)
   {
    if(isset($data[$columnname])){
      $row[$columnname]="'" . $data[$columnname] . "'";
     
       }else{
        $row[$columnname]="0";

       }
   }
   $row["id_repair"] = "NULL";
   $rows = implode(",",$row);
  $connectdb = connectdb();
  // INSERT INTO `tb_repair` (`id_repair`, `name_repair`, `number_repair`, `details_repair`) VALUES ('0', '0', '0', '0');
  $sql = "INSERT INTO `".$table."` ( ".adapterdb("implode").") VALUES ( $rows );";
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
  $table = adapterdb("table");
  $noteupdate="";
  $v = 0;
 foreach($columns as $columnname)
 {
   if($v!=0){
    $noteupdate.= ",";
    
   }
   $v=$v+1;
  if(isset($data[$columnname])){
    $row[$columnname]=$data[$columnname];
    $noteupdate.="`$columnname` = '".$row[$columnname]."'";
     }else{
      
      $row[$columnname]="0";
      $noteupdate.="`$columnname` = '".$row[$columnname]."'";
     }
 }
  $connectdb = connectdb();
  $sql = "UPDATE `".$table."` SET   $noteupdate WHERE `$table`.`id_repair` = ".$row['id_repair'].";";
 
  if ($connectdb->query($sql) === TRUE) {
    $nodata["bg"] = "bg-success";
    $nodata["message"] =  "เลขรับ : ".$row['id_repair'] . " ข้อมูลถูกแก้ไข "   ;
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

function del($data){
 
  $table = adapterdb("table");
  
  $v = 0;
 
  $connectdb = connectdb();
  // $sql = "UPDATE `".$table."` SET   $noteupdate WHERE `$table`.`id_repair` = ".$row['id_repair'].";";
   $sql = "DELETE FROM `".$table."` WHERE `tb_repair`.`id_repair` = '".$data['id']."';";
   $nodata["bg"] = "bg-warning";
     $nodata["message"] =  $sql  ;
       echo json_encode(  $sql);
       die();
  if ($connectdb->query($sql) === TRUE) {
    // $nodata["bg"] = "bg-success";
    // $nodata["message"] =  "เลขรับ : ".$row['id_repair'] . " ข้อมูลถูกแก้ไข "   ;
    echo json_encode($nodata);

 
    return  $connectdb->insert_id;
  }else{
    $nodata["bg"] = "bg-warning";
    $nodata["message"] = "ไม่สามารถแก้ไข ข้อมูลได้ " . $sql ;
    echo json_encode($nodata);
   
    return false;
  }
}