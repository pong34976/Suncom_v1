<div class="b"></div>
<?php
  function  status($id="0"){
    $status[0] = "-";
    $status[1] = "<div class=' badge  bg-success'>ว่าง</div>";
    $status[2] = "<div class='  badge  bg-primary'>มีผู้พักอาศัย</div>";
    $status[3] = "<div class=' badge  bg-warning'>ซ่อมบำรุง</div>";
    $status[4] = "<div class=' badge  bg-danger'>ปิด</div>";
    return $status[$id];
}
 
echo '<meta http-equiv="Cache-Control" content="no-store"/>';
 function ckid( ){
    require_once("config.php");
    $connectdb = connectdb();
 
    $sql = "SELECT idcard  FROM tb_housing  ";
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
 
    $sql = "SELECT phones  FROM tb_housing  ";
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
 
    $sql = "SELECT email  FROM tb_housing  ";
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
    $sql = "SELECT *  FROM tb_housing";
    $result = $connectdb->query($sql);
       define("NOMROW",($result->num_rows/10)+1);
       define("NOMROWS",$result->num_rows );
    
 
    $limit  = " LIMIT $limit ";
    $max = ",$max";
    
 

   $limitsql = "$limit $max";
 
//    $status[1][1]="ss";
    $sql = "SELECT *  FROM `tb_housing` $limitsql";
    // echo  $sql ;
    $result = $connectdb->query($sql);
    if ($result->num_rows > 0) {
    
        // $numrows = $result->fetch_assoc();
      // output data of each row 
    //    <td>'.ckdate($row["birthdate"],true).'</td>
      while($row = $result->fetch_assoc()) {
          $tables  = '<tr>
   
      <td>'.$row["noroom"]. '</td>
      <td>'.$row["housingrow"].' </td>
      <td class=" h5 w-25"> '.status($row["status"]).'</td>
   
      
      <td>  <div class="btn-group">
      <a  href="?view=view&id='.$row["id"].'&page=housing" class=" btn btn-primary col-10 m-1 m-sm-0 col-sm-4 ">ดู</a>
    <a  href="?view=edit&vals='.$row["id"].'&page=housing" class=" btn btn-warning col-10 m-1 m-sm-0 col-sm-4 ">แก้ไข</a>
      <button name="id" value="'.$row["id"].'"  data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="$(\'#myid\').val(\''.$row["id"].'\'); "  class="btn  btn-danger col-10  m-1 m-sm-0 col-sm-4 ">ลบ</button>
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
      require_once("list_housing.html");
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
                // $housingImage=ckstring("housingImage",'รูปภาพไม่สมบูรณ์ กรุณา เลือกภาพอีกครัง' );
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
                //  $err['housingImage']= @housingIMAGE;
                require_once("add_housing.html");
                die();
             }   
             $idcards = "non". $sexs["key"];
            //  print_r($_FILES["housingImage"]["name"]);
            //  die();
            // //  
             if(!empty($_FILES['housingImage']) && @$_FILES["housingImage"]["name"]!="") {
                $idcards =  $idcard;
                if(is_uploaded_file($_FILES['housingImage']['tmp_name'])) {
                $sourcePath = $_FILES['housingImage']['tmp_name'];
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
                    $sql = "INSERT INTO `tb_housing` (`id`,`idcard`, `prefix`, `rname`, `lname`,`sex`, `affiliation`, `address`, `email`, `phones`, `birthdate`, `img`) VALUES (NULL,'$idcard',  '$prefix', '$rname', '$lname','$sex','$affiliation','$address','$email','$phones', '$birthdate','$idcards.jpg');";
                    //  echo $sql;
                    //  die();
        if ($connectdb->query($sql) === TRUE) {
    
                        echo "<h3 class='alert alert-success'>เพิ่มข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("add_housing.html"); 
                    
                        // require_once("list_housing.html");
                    } else {
                        
                        //  $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         echo  "<h5 class='alert alert-danger'>ไม่สามารถเพิ่มข้อมูลได้</h5>";
                         require_once("add_housing.html");
    }
         
         
            break;
            case 'edit':
                $idcard=ckstring("idcard" ,'รหัสบัตรประชาชน ไม่ควรเว้นว่าง' );
                $id=ckstring("id" ,'id ไม่ควรเว้นว่าง' );
                $prefix=ckstring("prefix",'คำนำหน้า ไม่ควรเว้นว่าง' );
                $rname=ckstring("rname",'ชื่อจริง ไม่ควรเว้นว่าง' );
                $lname=ckstring("lname",'นามสกุล ไม่ควรเว้นว่าง' );
                $affiliation=ckstring("affiliation",'สังกัด ไม่ควรเว้นว่าง' );
                $email=ckstring("email",'อีเมล์ ไม่ควรเว้นว่าง' );
                $phones=ckstring("phones",'เบอร์โทร ไม่ควรเว้นว่าง' );
                $sex=ckstring("sex",'โปรดเลือก เพศ ' );
                // $housingImage=ckstring("housingImage",'รูปภาพไม่สมบูรณ์ กรุณา เลือกภาพอีกครัง' );
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
                //  $err['housingImage']= @housingIMAGE;
                require_once("edit_housing.html");
                die();
             }   
             $idcards = "non". $sexs["key"];
             //  print_r($_FILES["housingImage"]["name"]);
             //  die();
             // //  
             $imgs ="";
              if(!empty($_FILES['housingImage']) && @$_FILES["housingImage"]["name"]!="") {
             
                 $idcards =  $idcard;
                 $imgs =", `img` = '$idcards.jpg'";
                 if(is_uploaded_file($_FILES['housingImage']['tmp_name'])) {
                 $sourcePath = $_FILES['housingImage']['tmp_name'];
                 $targetPath = "images/".$idcards . ".jpg";
                 if(move_uploaded_file($sourcePath,$targetPath)) {
                 ?>
                 <img src="<?php echo $targetPath; ?>" width="100px" height="100px" />
                 <?php
                 }
                 }
                 } 
                 
                    $connectdb = connectdb();
  $sql = "UPDATE `tb_housing` SET `idcard` = '$idcard', `prefix` = '$prefix', `rname` = '$rname', `lname` = '$lname', `sex` = '$sex', `affiliation` = '$affiliation', `birthdate` = '$birthdate', `address` = '$address', `email` = '$email', `phones` = '$phones' $imgs WHERE `tb_housing`.`id` = $id;";
                     
        if ($connectdb->query($sql) === TRUE) {
           
                        echo "<h3 class='alert alert-success'>แก้ข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("edit_housing.html"); 
                    
                    } else {
                       echo  $sql;
                        die();
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("edit_housing.html");
    }  
                require_once("edit_housing.html");
            break;
                 case 'del':
                
            
                $id=ckstring("id" ,'รหัส ไม่ควรเว้นว่าง' );
                 
             
             
               
                    $connectdb = connectdb();
                    $sql = "DELETE FROM tb_housing where id='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
            // $housings ="";
            // $showname="";
                        echo "<h3 class='alert alert-success'>ลบ สิทธิ์ เรียบร้อย  </h3>";
                        $pins[1]="active";
        $tables  = tables();
       
     
        
        
        require_once("list_housing.html");
                        die();
                        // require_once("list_housing.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("lise_housing.html");
    }  
                require_once("list_housing.html");
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
        
        require_once("list_housing.html");
    break;
    case 'view':
       
            if(isset($_GET["id"]) && !empty($_GET["id"])){
                $vals=$_GET["id"];
                $connectdb = connectdb();
             $sql = "SELECT * FROM tb_housing where id = $vals";
             $result = $connectdb->query($sql);
             if ($result->num_rows > 0) {
                 $row = $result->fetch_assoc();
                 $id = $row["id"];
                
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
            require_once("view_housing.html");
        }else{
          echo  ERR4;
        }
       
        break;
    case 'add':
  
    require_once("add_housing.html");
    break;
    case 'edit':
        if(isset($_GET["vals"]) && !empty($_GET["vals"])){
               $vals=$_GET["vals"];
               $connectdb = connectdb();
            $sql = "SELECT * FROM tb_housing where id = $vals";
            $result = $connectdb->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row["id"];
               
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
            require_once("edit_housing.html");
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