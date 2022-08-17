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
          $arr   = array( 'id_hardware',
            're_type',
            're_model',
            'img'   );
 
  switch ($command) {
    case 'arr':
   
      break;
    case 'implode':
      $arr = implode(",",$arr);
      break;
      case 'table':
        $arr ="tb_hardware";
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
              $_POST["token"]=session_id();
              $request = show("serial_id");
              $json= json_encode($request);
        
                break;
        case 'add':

        if(isset($_POST["token"])){
    if($_POST["token"]==session_id()){
      if($_POST["re_type"]==""){
        $nodata["bg"] = "bg-warning";
        $nodata["message"] = "!! ไม่ได้คีย์ -ประเภท";
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
          if($_POST["re_type"]==""){
            $nodata["bg"] = "bg-warning";
            $nodata["message"] = "!! ไม่ได้คีย์ -ประเภท";
          
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

// sss ,if(re_due_date='0000-00-00','-',re_due_date) as re_due_date 
if($command=="serial_id"){
  $serial_id =  $_GET["serial_id"] ;
   $query = "SELECT *  FROM `".$table."` WHERE serial_id='$serial_id' " ;

}else{
  $query = "SELECT ".adapterdb("implode");
  $query.="  FROM  $table WHERE 1=1 ".$search;
  $query.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start'].",".$requestData['length']." ";
 $getRes2 = $mysqli->query($query);
}
    // $nodata["bg"] = "bg-danger";
    // $nodata["message"] = $query;
    // echo json_encode($nodata);   
    //  die();
  $data = array();
  while($row = $getRes2->fetch_assoc())
  {
    $nestedData=array(); 
    foreach($columns as $columnname)
    {
      $tables[$i]["title"] =$columnname ;
      $nestedData[] = $row[$columnname];
      $i = $i+1;
    }	
    $data[] = $nestedData;
  }
 
  $json_data = array(

        "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
        "recordsTotal"    => intval( $totalData ),  // total number of records
        "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "columns" => $tables, // total number of records after searching, if there is no searching then totalFiltered = totalData
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
 
   $row["img"] = upload($data);
 
   
   $rows = implode(",",$row);
  $connectdb = connectdb();
  // INSERT INTO `tb_repair` (`id_repair`, `name_repair`, `number_repair`, `details_repair`) VALUES ('0', '0', '0', '0');
  $sql = "INSERT INTO `".$table."` ( ".adapterdb("implode").") VALUES ( $rows );";
 
        if ($connectdb->query($sql) === TRUE) {
          $nodata["bg"] = "bg-success";
          $nodata["message"] = "ข้อมูลถูกบันทึกเรียบร้อย เลขรับ : ". $connectdb->insert_id  ;
          echo json_encode($nodata);

          // $connectdb->exit();
          return  $connectdb->insert_id;
        }else{
         
         
          return false;
        }
}

function codeToMessage($code)
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;

        default:
            $message = "Unknown upload error";
            break;
    }
    return $message;
}
function upload($data){
 
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
      //uploading successfully done
      } else {
        if($_FILES['img']['error'] != UPLOAD_ERR_NO_FILE){
         $nodata["bg"] = "bg-warning";
          $nodata["message"] = codeToMessage($_FILES['img']['error']);
          echo json_encode($nodata);
     die();
    }
      return 0;
      }
  $row["img"] = 0;
  if(isset($_FILES['img'])){
    $errors= array();
    $file_name = $_FILES['img']['name'];
    $file_size =$_FILES['img']['size'];
    $file_tmp =$_FILES['img']['tmp_name'];
    $file_type=$_FILES['img']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['img']['name'])));
    
    $extensions= array("jpeg","jpg","png");
    
    if(in_array($file_ext,$extensions)=== false){
      //  $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      $nodata["bg"] = "bg-warning";
      $nodata["message"] = codeToMessage($_FILES['img']['error']);
      echo json_encode($nodata);
 die();
    }
    
    if($file_size > 2097152){
       $errors[]='File size must be excately 2 MB';
    }
    $re_model = trim($data["re_model"]);
    if(empty($errors)==true){
       move_uploaded_file($file_tmp,"../images/".$re_model.".jpg");
   
       $row["img"] = "'images/".$re_model.".jpg'";
    }else{
      $nodata["bg"] = "bg-warning";
      $nodata["message"] = "ไม่สามารถบันทึก ข้อมูลได้ ";
      echo json_encode($nodata);
    }
    return $row["img"];
 }else{
  return 0;
 }

}
function update($data){
  $columns = adapterdb("columns");
  $table = adapterdb("table");
  $noteupdate="";
  $v = 0;
   
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
      
      // $row[$columnname]="0";
      // $noteupdate.="`$columnname` = '".$row[$columnname]."'";
      // UPDATE `tb_hardware` SET `id_hardware` = '3',`re_type` = 'ปริ้นเตอร์',`re_model` = 'HP Laserjet p1102',`img` = ''images/IMG20160204113538.jpg'' WHERE `tb_hardware`.`id_hardware` = '3'
     }
 }
 if($_FILES["img"]["error"] != 4) {
  $row["img"] = upload($data);
  $noteupdate.=",`img` = ".$row["img"]."";
  }else{
  
  }
  $connectdb = connectdb();
  // $sql = "UPDATE `tb_hardware` SET `re_type` = 'ปริ้นเตอร์1', `re_model` = 'HP LaserJet Pro M402-M4031' WHERE `tb_hardware`.`id_hardware` = 1;";
  $sql = "UPDATE `".$table."` SET   $noteupdate WHERE `$table`.`id_hardware` = '".$row['id_hardware']."' ";
  //  $nodata["bg"] = "bg-warning";
  // $nodata["message"] =   $sql ;
  // echo json_encode($nodata);
  // die();
  if ($connectdb->query($sql) === TRUE) {
    $nodata["bg"] = "bg-success";
    $nodata["message"] =  "เลขรับ : ".$row['id_hardware'] . " ข้อมูลถูกแก้ไข "   ;
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
