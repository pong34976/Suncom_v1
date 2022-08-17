<div class="b"></div>
<?php
echo '<meta http-equiv="Cache-Control" content="no-store"/>';
 function ckid( ){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT idcard  FROM tb_user  ";
    if ($result = $connectdb->query($sql)) {

        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
   
 }
 function ckphones( ){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT phones  FROM tb_user  ";
    if ($result = $connectdb->query($sql)) {

        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
   
 }
 function ckemail( ){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT email  FROM tb_user  ";
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
    $sql = "SELECT *  FROM tb_user";
    $result = $connectdb->query($sql);
       define("NOMROW",($result->num_rows/10)+1);
       define("NOMROWS",$result->num_rows );
    
 
    $limit  = " LIMIT $limit ";
    $max = ",$max";
    
 

   $limitsql = "$limit $max";

    $sql = "SELECT *,((YEAR(NOW())+ 543)-YEAR(birthdate))  as age FROM `tb_user` $limitsql";
    // echo  $sql ;
    $result = $connectdb->query($sql);
    if ($result->num_rows > 0) {
      
        // $numrows = $result->fetch_assoc();
      // output data of each row 
    //    <td>'.ckdate($row["birthdate"],true).'</td>
      while($row = $result->fetch_assoc()) {
          $tables  = '<tr>
   
      <td>'.$row["prefix"].' '.$row["rname"].' '.$row["lname"].'</td>
      <td>'.$row["affiliation"].' </td>
      <td>'.$row["sex"].'</td>
      <td>'.$row["age"].'</td>
      <td><img class="img-fluid" style="width:80px;" src="images/'.$row["img"].'" alt=""></td>
      <td>  <div class="btn-group">
      <a  href="?view=view&id='.$row["id_user"].'&page=user" class=" btn btn-primary col-10 m-1 m-sm-0 col-sm-4 ">ดู</a>
    <a  href="?view=edit&vals='.$row["id_user"].'&page=user" class=" btn btn-warning col-10 m-1 m-sm-0 col-sm-4 ">แก้ไข</a>
      <button name="id" value="'.$row["id_user"].'"  data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="$(\'#myid\').val(\''.$row["id_user"].'\'); "  class="btn  btn-danger col-10  m-1 m-sm-0 col-sm-4 ">ลบ</button>
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
      require_once("list_user.html");
     }else{
        echo ERR4;
     }
            break;
        
           
            case 'add':
              
            //   print_r($_POST);
            //     die();
                $idcard=ckstring("idcard" ,'รหัสบัตรประชาชน ไม่ควรเว้นว่าง' );
             
                $prefix=ckstring("prefix",'คำนำหน้า ไม่ควรเว้นว่าง' );
                $rname=ckstring("rname",'ชื่อจริง ไม่ควรเว้นว่าง' );
                $lname=ckstring("lname",'นามสกุล ไม่ควรเว้นว่าง' );
                $affiliation=ckstring("affiliation",'สังกัด ไม่ควรเว้นว่าง' );
                $email=ckstring("email",'อีเมล์ ไม่ควรเว้นว่าง' );
                $phones=ckstring("phones",'เบอร์โทร ไม่ควรเว้นว่าง' );
                $sex=ckstring("sex",'โปรดเลือก เพศ ' );
                // $userImage=ckstring("userImage",'รูปภาพไม่สมบูรณ์ กรุณา เลือกภาพอีกครัง' );
                if($sex=="ชาย"){
                    $sexs["m"]="checked";
                    $sexs["key"]="m";
                }else{
                    $sexs["s"]="checked";
                    $sexs["key"]="s";
                }
                $address=ckstring("address",'ที่อยู่ ไม่ควรเว้นว่าง' );
                $birthdate=ckstring("birthdate",'วันเกิด ไม่ควรเว้นว่าง' );
                    //   $log_level=ckstring("log_level",'ระดับสมาชิก ไม่ควรเว้นว่าง' );
             if(  $idcard ==false || $prefix==false || $rname==false || $lname==false  || $affiliation==false || $email==false || $phones==false || $address==false || $birthdate==false){
                $err['idcard']= @IDCARD;
                $err['sex']= @SEX;
                $err['prefix']= @PREFIX;
                $err['rname']= @RNAME;
                $err['lname']= @LNAME;
                $err['log_level']= @LOG_LEVEL;
                 $err['affiliation']= @AFFILIATION;
                 $err['email']= @EMAIL;
                 $err['phones']= @PHONES;
                 $err['address']= @ADDRESS;
                //  $err['userImage']= @USERIMAGE;
                require_once("add_user.html");
                die();
             }   
             $idcards = "non". $sexs["key"];
            //  print_r($_FILES["userImage"]["name"]);
            //  die();
            // //  
             if(!empty($_FILES['userImage']) && @$_FILES["userImage"]["name"]!="") {
                $idcards =  $idcard;
                if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
                $sourcePath = $_FILES['userImage']['tmp_name'];
                $targetPath = "images/".$idcards . ".jpg";
                if(move_uploaded_file($sourcePath,$targetPath)) {
                ?>
                <img src="<?php echo $targetPath; ?>" width="100px" height="100px" />
                <?php
                }
                }
                } 
        
             
                // $birthdate  =date("Y-m-d",strtotime($birthdate));
                // $birthdate = ckdate($birthdate);
                    $connectdb = connectdb();
                    $sql = "INSERT INTO `tb_user` (`id_user`,`idcard`, `prefix`, `rname`, `lname`,`sex`, `affiliation`, `address`, `email`, `phones`, `birthdate`, `img`) VALUES (NULL,'$idcard',  '$prefix', '$rname', '$lname','$sex','$affiliation','$address','$email','$phones', '$birthdate','$idcards.jpg');";
                    //  echo $sql;
                    //  die();
        if ($connectdb->query($sql) === TRUE) {
    
                        echo "<h3 class='alert alert-success'>เพิ่มข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("add_user.html"); 
                    
                        // require_once("list_user.html");
                    } else {
                        
                        //  $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         echo  "<h5 class='alert alert-danger'>ไม่สามารถเพิ่มข้อมูลได้</h5>";
                         require_once("add_user.html");
    }
         
         
            break;
            case 'edit':
                $idcard=ckstring("idcard" ,'รหัสบัตรประชาชน ไม่ควรเว้นว่าง' );
                $id_user=ckstring("id_user" ,'id_user ไม่ควรเว้นว่าง' );
                $prefix=ckstring("prefix",'คำนำหน้า ไม่ควรเว้นว่าง' );
                $rname=ckstring("rname",'ชื่อจริง ไม่ควรเว้นว่าง' );
                $lname=ckstring("lname",'นามสกุล ไม่ควรเว้นว่าง' );
                $affiliation=ckstring("affiliation",'สังกัด ไม่ควรเว้นว่าง' );
                $email=ckstring("email",'อีเมล์ ไม่ควรเว้นว่าง' );
                $phones=ckstring("phones",'เบอร์โทร ไม่ควรเว้นว่าง' );
                $sex=ckstring("sex",'โปรดเลือก เพศ ' );
                // $userImage=ckstring("userImage",'รูปภาพไม่สมบูรณ์ กรุณา เลือกภาพอีกครัง' );
                if($sex=="ชาย"){
                    $sexs["m"]="checked";
                    $sexs["key"]="m";
                }else{
                    $sexs["s"]="checked";
                    $sexs["key"]="s";
                }
                $address=ckstring("address",'ที่อยู่ ไม่ควรเว้นว่าง' );
                $birthdate=ckstring("birthdate",'วันเกิด ไม่ควรเว้นว่าง' );
                    //   $log_level=ckstring("log_level",'ระดับสมาชิก ไม่ควรเว้นว่าง' );
             if(  $idcard ==false || $prefix==false || $rname==false || $lname==false  || $affiliation==false || $email==false || $phones==false || $address==false || $birthdate==false){
                $err['idcard']= @IDCARD;
            
                $err['prefix']= @PREFIX;
                $err['rname']= @RNAME;
                $err['lname']= @LNAME;
                $err['log_level']= @LOG_LEVEL;
                 $err['affiliation']= @AFFILIATION;
                 $err['email']= @EMAIL;
                 $err['phones']= @PHONES;
                 $err['address']= @ADDRESS;
                //  $err['userImage']= @USERIMAGE;
                require_once("edit_user.html");
                die();
             }   
             $idcards = "non". $sexs["key"];
             //  print_r($_FILES["userImage"]["name"]);
             //  die();
             // //  
             $imgs ="";
              if(!empty($_FILES['userImage']) && @$_FILES["userImage"]["name"]!="") {
             
                 $idcards =  $idcard;
                 $imgs =", `img` = '$idcards.jpg'";
                 if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
                 $sourcePath = $_FILES['userImage']['tmp_name'];
                 $targetPath = "images/".$idcards . ".jpg";
                 if(move_uploaded_file($sourcePath,$targetPath)) {
                 ?>
                 <img src="<?php echo $targetPath; ?>" width="100px" height="100px" />
                 <?php
                 }
                 }
                 } 
                 
                    $connectdb = connectdb();
  $sql = "UPDATE `tb_user` SET `idcard` = '$idcard', `prefix` = '$prefix', `rname` = '$rname', `lname` = '$lname', `sex` = '$sex', `affiliation` = '$affiliation', `birthdate` = '$birthdate', `address` = '$address', `email` = '$email', `phones` = '$phones' $imgs WHERE `tb_user`.`id_user` = $id_user;";
                     
        if ($connectdb->query($sql) === TRUE) {
           
                        echo "<h3 class='alert alert-success'>แก้ข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("edit_user.html"); 
                    
                    } else {
                       echo  $sql;
                        die();
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("edit_user.html");
    }  
                require_once("edit_user.html");
            break;
                 case 'del':
                
            
                $id=ckstring("id" ,'รหัส ไม่ควรเว้นว่าง' );
                 
             
             
               
                    $connectdb = connectdb();
                    $sql = "DELETE FROM tb_user where id_user='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
            // $users ="";
            // $showname="";
                        echo "<h3 class='alert alert-success'>ลบ สิทธิ์ เรียบร้อย  </h3>";
                        $pins[1]="active";
        $tables  = tables();
       
     
        
        
        require_once("list_user.html");
                        die();
                        // require_once("list_user.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("lise_user.html");
    }  
                require_once("list_user.html");
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
        
        require_once("list_user.html");
    break;
    case 'view':
       
            if(isset($_GET["id"]) && !empty($_GET["id"])){
                $vals=$_GET["id"];
                $connectdb = connectdb();
             $sql = "SELECT * FROM tb_user where id_user = $vals";
             $result = $connectdb->query($sql);
             if ($result->num_rows > 0) {
                 $row = $result->fetch_assoc();
                 $id_user = $row["id_user"];
                
                 $idcard = $row["idcard"];  
                 $prefix = $row["prefix"];
                 $rname = $row["rname"];
                 $lname = $row["lname"];
                 $sex = $row["sex"];
                 $birthdate = $row["birthdate"];
                 $affiliation = $row["affiliation"];
                 $address = $row["address"];
                 $email = $row["email"];
                 $phones = $row["phones"];
                 $img = $row["img"];
                  
 
              
                 if($sex=="ชาย"){
                     $sexs["m"]="checked";
                     $sexs["key"]="m";
                 }else{
                     $sexs["s"]="checked";
                     $sexs["key"]="s";
                 }
             }
            require_once("view_user.html");
        }else{
          echo  ERR4;
        }
       
        break;
    case 'add':
  
    require_once("add_user.html");
    break;
    case 'edit':
        if(isset($_GET["vals"]) && !empty($_GET["vals"])){
               $vals=$_GET["vals"];
               $connectdb = connectdb();
            $sql = "SELECT * FROM tb_user where id_user = $vals";
            $result = $connectdb->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id_user = $row["id_user"];
               
                $idcard = $row["idcard"];  
                $prefix = $row["prefix"];
                $rname = $row["rname"];
                $lname = $row["lname"];
                $sex = $row["sex"];
                $birthdate = $row["birthdate"];
                $affiliation = $row["affiliation"];
                $address = $row["address"];
                $email = $row["email"];
                $phones = $row["phones"];
                $img = $row["img"];
                 

             
                if($sex=="ชาย"){
                    $sexs["m"]="checked";
                    $sexs["key"]="m";
                }else{
                    $sexs["s"]="checked";
                    $sexs["key"]="s";
                }
            }
            require_once("edit_user.html");
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