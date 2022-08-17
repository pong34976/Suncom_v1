<?php
 function ckusername(){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT username  FROM tb_login  ";
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
    $sql = "SELECT *  FROM tb_login";
    $result = $connectdb->query($sql);
       define("NOMROW",($result->num_rows/10)+1);
       define("NOMROWS",$result->num_rows );
    
 
    $limit  = " LIMIT $limit ";
    $max = ",$max";
    
 

   $limitsql = "$limit $max";

    $sql = "SELECT * FROM `tb_login` $limitsql";
    // echo  $sql ;
    $result = $connectdb->query($sql);
    if ($result->num_rows > 0) {
        $level[1]="ผู้ดูแลระบบ";
        $level[2]="หัวหน้างาน";
        $level[3]="พนักงาน";
        // $numrows = $result->fetch_assoc();
      // output data of each row 
    //    <td>'.ckdate($row["birthdate"],true).'</td>
      while($row = $result->fetch_assoc()) {
          $tables  = '<tr>
      <td>'.$row["username"].'</td>
      <td>'.$row["prefix"].' '.$row["rname"].' '.$row["lname"].'</td>
      <td>'.$level[$row["log_level"]].'</td>
      <td><div class="btn-group">
    <a  href="?view=edit&vals='.$row["log_id"].'&page=login" class=" btn btn-warning col-10 m-1 m-sm-0 col-sm-4 ">แก้ไข</a>
      <button name="id" value="'.$row["log_id"].'"  data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="$(\'#myid\').val(\''.$row["log_id"].'\'); "  class="btn  btn-danger col-10  m-1 m-sm-0 col-sm-4 ">ลบ</button>
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
      require_once("list_login.html");
     }else{
        echo ERR4;
     }
            break;
            case 'add':
                $username=ckstring("username" ,'username ไม่ควรเว้นว่าง' );
                $password=ckstring("password",'password ไม่ควรเว้นว่าง' );
                $prefix=ckstring("prefix",'คำนำหน้า ไม่ควรเว้นว่าง' );
                $rname=ckstring("rname",'ชื่อจริง ไม่ควรเว้นว่าง' );
                $lname=ckstring("lname",'นามสกุล ไม่ควรเว้นว่าง' );
                $birthdate=ckstring("birthdate",'วันเกิด ไม่ควรเว้นว่าง' );
                      $log_level=ckstring("log_level",'ระดับสมาชิก ไม่ควรเว้นว่าง' );
             if($username ==false || $password==false || $prefix==false || $rname==false || $lname==false){
                $err['username']= @USERNAME;
                $err['password']= @PASSWORD;
                $err['prefix']= @PREFIX;
                $err['rname']= @RNAME;
                $err['lname']= @LNAME;
                 $err['birthdate']= @BRTHDATE;
                 $err['log_level']= @LOG_LEVEL;
                require_once("add_login.html");
                die();
             }
                 
                // $birthdate  =date("Y-m-d",strtotime($birthdate));
                // $birthdate = ckdate($birthdate);
                    $connectdb = connectdb();
                    $sql = "INSERT INTO `tb_login` (`log_id`, `log_level`, `username`, `password`, `prefix`, `rname`, `lname`, `birthdate`) VALUES (NULL, '$log_level', '$username', '$password', '$prefix', '$rname', '$lname', '$birthdate');";
                     
        if ($connectdb->query($sql) === TRUE) {
            $logins ="";
            $showname="";
                        echo "<h3 class='alert alert-success'>เพิ่มข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("add_login.html"); 
                        // require_once("list_login.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("add_login.html");
    }
         
         
            break;
            case 'edit':
                $log_id=ckstring("log_id" ,'log_id ไม่ควรเว้นว่าง' );
                $username=ckstring("username" ,'username ไม่ควรเว้นว่าง' );
                $password=ckstring("password",'password ไม่ควรเว้นว่าง' );
                $prefix=ckstring("prefix",'คำนำหน้า ไม่ควรเว้นว่าง' );
                $rname=ckstring("rname",'ชื่อจริง ไม่ควรเว้นว่าง' );
                $lname=ckstring("lname",'นามสกุล ไม่ควรเว้นว่าง' );
                $birthdate=ckstring("birthdate",'วันเกิด ไม่ควรเว้นว่าง' );
                $log_level=ckstring("log_level",'วันเกิด ไม่ควรเว้นว่าง' );
             if($username ==false || $password==false || $prefix==false || $rname==false || $lname==false){
                $err['username']= @USERNAME;
                $err['password']= @PASSWORD;
                $err['prefix']= @PREFIX;
                $err['rname']= @RNAME;
                $err['lname']= @LNAME;
                 $err['birthdate']= @BRTHDATE;
                require_once("edit_login.html");
                die();
             }
               
                    $connectdb = connectdb();
                    $sql = "UPDATE `tb_login` SET `log_level` = '$log_level', `username` = '$username', `password` = '$password', `prefix` = '$prefix', `rname` = '$rname', `lname` = '$lname', `birthdate` = '$birthdate' WHERE `tb_login`.`log_id` = $log_id; ";
                     
        if ($connectdb->query($sql) === TRUE) {
           
                        echo "<h3 class='alert alert-success'>แก้ข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("edit_login.html"); 
                    
                    } else {
                       echo  $sql;
                        die();
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("edit_login.html");
    }  
                require_once("edit_login.html");
            break;
                 case 'del':
                
            
                $id=ckstring("id" ,'รหัส ไม่ควรเว้นว่าง' );
                 
             
             
               
                    $connectdb = connectdb();
                    $sql = "DELETE FROM tb_login where log_id='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
            // $logins ="";
            // $showname="";
                        echo "<h3 class='alert alert-success'>ลบ สิทธิ์ เรียบร้อย  </h3>";
                        $pins[1]="active";
        $tables  = tables();
       
     
        
        
        require_once("list_login.html");
                        die();
                        // require_once("list_login.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("lise_login.html");
    }  
                require_once("list_login.html");
            break;
            default:
            echo  ERR4; 
                break;
        }
    }else{
           // ----------------------------เมื่อมีเรียกหน้าเว็บ แต่ไม่มีค่าPOST vvvvvv -------------------------------
switch ($_GET['view']) {
    case 'list':
        $pins[1]="active";
        $tables  = tables();
        
        require_once("list_login.html");
    break;
    case 'add':
    require_once("add_login.html");
    break;
    case 'edit':
        if(isset($_GET["vals"]) && !empty($_GET["vals"])){
               $vals=$_GET["vals"];
               $connectdb = connectdb();
            $sql = "SELECT * FROM tb_login where log_id = $vals";
            $result = $connectdb->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $log_id = $row["log_id"];
                $log_level[$row["log_level"]] = "selected";
              
                $username = $row["username"];
                $password = $row["password"];
                $prefix = $row["prefix"];
                $rname = $row["rname"];
                $lname = $row["lname"];
                $birthdate = $row["birthdate"];
            }
            require_once("edit_login.html");
        }else{
     
         echo ERR4;
        }
 
    break;
    default:
    echo  ERR4; 
        break;
}
}
 
?>