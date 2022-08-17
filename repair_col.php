<?php
 function ckusername(){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT username  FROM tb_phones  ";
    if ($result = $connectdb->query($sql)) {

        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
   
 }
        
 function tables($limit = 0,$max=10){
  require_once("config.php");
  $connectdb = connectdb();
  if ($connectdb->connect_error) {
      die("Connection failed: " . $connectdb->connect_error);
    }
    $tables = "";
    $sql = "SELECT *  FROM tb_phones";
    $result = $connectdb->query($sql);
       define("NOMROW",($result->num_rows/10)+1);
       define("NOMROWS",$result->num_rows );
    
 
//     $limit  = " LIMIT $limit ";
//     $max = ",$max";
    
 

//    $limitsql = "$limit $max";

//     $sql = "SELECT * FROM `tb_phones` $limitsql";
    // echo  $sql ;
    // $result = $connectdb->query($sql);
    if ($result->num_rows > 0) {
        
        // $numrows = $result->fetch_assoc();
      // output data of each row 
    //    <td>'.ckdate($row["birthdate"],true).'</td>
      while($row = $result->fetch_assoc()) {
          $tables  = '<tr>
      <td>'.$row["number"].'</td>
      <td>'.$row["room"]. '</td>
      <td>'.$row["roomgroup"]. '</td>
      <td><div class="btn-group">
    <a  href="?view=edit&vals='.$row["id"].'&page=phones" class=" btn btn-warning col-10 m-1 m-sm-0 col-sm-4 ">แก้ไข</a>
      '.($_SESSION["level"]==1 ? $_SESSION["level"].'<button name="id" value="'.$row["id"].'"  data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="$(\'#myid\').val(\''.$row["id"].'\'); "  class="btn  btn-danger col-10  m-1 m-sm-0 col-sm-4 ">ลบ</button>' : '').'
 </div>
      </td>
    </tr>'.$tables;
      }
    } else {
      $tables  ="";
     
    }
    return $tables;
}
    // ----------------------------เมื่อมีการรับค่าPOST vvvvvv -------------------------------
    if($_SERVER['REQUEST_METHOD']=="POST"){
      
        function ckstring($text,$alerts ){
            if(isset($_POST[$text]) && !empty($_POST[$text]) && trim($_POST[$text])){
                      
                $texts=htmlentities($_POST[$text]);
            }else{
                echo  "<h5 class='alert alert-danger'>".$alerts."</h5>";
               
                define(strtoupper($text),"alert-danger");
                  $err =false;
                // die();
                return $err;
            }
           return $texts;
        }

        switch ($_GET['view']) {
             
            case 'list':
     if(isset($_POST["pni"]) && !empty($_POST["pni"])){
        $pni = ($_POST["pni"]-1)*10;
       
      $tables  = tables($pni);
     
       $pins[$_POST["pni"]]="active";
      require_once("list_phones.html");
     }else{
        echo ERR4;
     }
            break;
            case 'add':
              
                
                $number=ckstring("number" ,'number ไม่ควรเว้นว่าง' );
                $room=ckstring("room",'room ไม่ควรเว้นว่าง' );
      
             if(  $number ==false || $room==false ){
           
                $err['number']= @NUMBER;
                $err['room']= @ROOM;
               
                require_once("add_phones.html");
                die();
             }
                 
                // $birthdate  =date("Y-m-d",strtotime($birthdate));
                // $birthdate = ckdate($birthdate);
                    $connectdb = connectdb();
                    $sql = "INSERT INTO `tb_phones` (`id`, `number`, `room` ) VALUES (NULL, '$number', '$room');";
                     
        if ($connectdb->query($sql) === TRUE) {
            $phoness ="";
            $showname="";
                        echo "<h3 class='alert alert-success'> เพิ่มข้อมูล เรียบร้อย  </h3>";
                        require_once("add_phones.html"); 
                        // require_once("list_phones.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("add_phones.html");
    }
         
         
            break;
            case 'addmsg':
              
                $data ="";
                $message=ckstring("message" ,'message ไม่ควรเว้นว่าง' );
                $userid=ckstring("userid" ,'userid ไม่ควรเว้นว่าง' );
      
             if(  $message ==false  ){
           
                // $err['message']= @NUMBER;
                // $err['date_msg']= @ROOM;
                // $data = @MESSAGE;
             
                // require_once("add_phones.html");
                die();
             }
                 
                // $birthdate  =date("Y-m-d",strtotime($birthdate));
                // $birthdate = ckdate($birthdate);
                    $connectdb = connectdb();
                    $sql = "INSERT INTO `tb_message` (`id`, `message` ,`userid` ) VALUES (NULL, '$message' ,$userid);";
                     
        if ($connectdb->query($sql) === TRUE) {
        
            $data = "Success";
                         
                        // require_once("list_phones.html");
                    } else {
                        
                        // $data = ERR4;
                       
                       
    }
    header("Content-Type: application/json");
    // echo json_encode($data);
         
            break;
         
            case 'edit':
                $id=ckstring("id" ,'id ไม่ควรเว้นว่าง' );
                     
                $number=ckstring("number" ,'number ไม่ควรเว้นว่าง' );
                $room=ckstring("room",'room ไม่ควรเว้นว่าง' );
      
             if( $id ==false || $number ==false || $room==false ){
                $err['id']= @ID;
                $err['number']= @NUMBER;
                $err['room']= @ROOM;
               
                require_once("edit_phones.html");
                die();
             }
               
                    $connectdb = connectdb();
                    $sql = "UPDATE `tb_phones` SET `number` = '$number', `room` = '$room'  WHERE `tb_phones`.`id` = $id; ";
                     
        if ($connectdb->query($sql) === TRUE) {
           
                        echo "<h3 class='alert alert-success'>แก้ข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("edit_phones.html"); 
                    
                    } else {
                       echo  $sql;
                        die();
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("edit_phones.html");
    }  
                require_once("edit_phones.html");
            break;
                 case 'del':
                
            
                $id=ckstring("id" ,'รหัส ไม่ควรเว้นว่าง' );
                 
             
             
               
                    $connectdb = connectdb();
                    $sql = "DELETE FROM tb_phones where id='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
            // $phoness ="";
            // $showname="";
                        echo "<h3 class='alert alert-success'>ลบ สิทธิ์ เรียบร้อย  </h3>";
                        $pins[1]="active";
        $tables  = tables();
       
     
        
        
        require_once("list_phones.html");
                        die();
                        // require_once("list_phones.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("lise_phones.html");
    }  
                require_once("list_phones.html");
            break;
            default:
            echo  ERR4; 
                break;
        }
    }else{
           // ----------------------------เมื่อมีเรียกหน้าเว็บ แต่ไม่มีค่าPOST vvvvvv -------------------------------
switch ($_GET['view']) {
 
    case 'list':
 
        
        require_once("list_repair.html");
    break;
    case 'add':
   
    break;
    case 'edit':
     
 
    break;
    default:
    echo  ERR4; 
        break;
}
}
 
?>