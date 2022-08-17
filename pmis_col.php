<?php
 function tables($limit = 0,$max=10){
  require_once("config.php");
  $connectdb = connectdb();
  if ($connectdb->connect_error) {
      die("Connection failed: " . $connectdb->connect_error);
    }
    $tables = "";
    $sql = "SELECT *  FROM tb_permission";
    $result = $connectdb->query($sql);
    define("NOMROW",($result->num_rows/10)+1);
       define("NOMROWS",$result->num_rows);
    
 
    $limit  = " LIMIT $limit ";
    $max = ",$max";
    
 

   $limitsql = "$limit $max";

    $sql = "SELECT * FROM `tb_permission` $limitsql";
    // echo  $sql ;
    $result = $connectdb->query($sql);
    if ($result->num_rows > 0) {
        // $numrows = $result->fetch_assoc();
      // output data of each row
      while($row = $result->fetch_assoc()) {
          $tables  = '<tr>
      <td>'.$row["pmiss"].'</td>
      <td>'.$row["showname"].'</td>
      <td>
    <a  href="?view=edit&vals='.$row["id"].'&page=pmis" class=" btn btn-warning col-10 m-1 m-sm-0 col-sm-4 ">แก้ไข</a>
      <button name="id" value="'.$row["id"].'"  data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="$(\'#myid\').val(\''.$row["id"].'\');$(\'#myname\').val(\''.$row["pmiss"].'\');$(\'#showname\').val(\''.$row["showname"].'\')"  class="btn  btn-danger col-10  m-1 m-sm-0 col-sm-4 ">ลบ</button>
     <form class="row align-content-center d-block"  action="?view=del&page=pmis" method="post">
      </form>
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
      require_once("list_pmis.html");
     }else{
        echo ERR4;
     }
            break;
            case 'add':
                $pmiss=ckstring("pmiss" ,'ชื่อสิทธิ ไม่ควรเว้นว่าง' );
                $showname=ckstring("showname",'หน้าที่ ไม่ควรเว้นว่าง' );
             
             if($pmiss ==false || $showname==false){
                $err['pmiss']= @PMISS;
                $err['showname']= @SHOWNAME;
                require_once("add_pmis.html");
                die();
             }
               
                    $connectdb = connectdb();
                    $sql = "INSERT INTO tb_permission (pmiss,showname) VALUES ('$pmiss','$showname')";
                     
        if ($connectdb->query($sql) === TRUE) {
            $pmiss ="";
            $showname="";
                        echo "<h3 class='alert alert-success'>เพิ่มข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("add_pmis.html"); 
                        // require_once("list_pmis.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("add_pmis.html");
    }
         
         
            break;
            case 'edit':
                $id=ckstring("id" ,'ชื่อสิทธิ ไม่ควรเว้นว่าง' );
                $pmiss=ckstring("pmiss" ,'ชื่อสิทธิ ไม่ควรเว้นว่าง' );
                $showname=ckstring("showname",'หน้าที่ ไม่ควรเว้นว่าง' );
             
             if($pmiss ==false || $showname==false){
                $err['pmiss']= @PMISS;
                $err['showname']= @SHOWNAME;
                require_once("add_pmis.html");
                die();
             }
               
                    $connectdb = connectdb();
                    $sql = "UPDATE tb_permission SET pmiss='$pmiss',showname='$showname' where id='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
           
                        echo "<h3 class='alert alert-success'>แก้ข้อมูลสิทธิ์ เรียบร้อย คลิ๊ก!! รายการสิทธิ์ เพื่อเรียกดู</h3>";
                        require_once("edit_pmis.html"); 
                    
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("edit_pmis.html");
    }  
                require_once("edit_pmis.html");
            break;
                 case 'del':
                
            
                $id=ckstring("id" ,'รหัส ไม่ควรเว้นว่าง' );
                 
             
             
               
                    $connectdb = connectdb();
                    $sql = "DELETE FROM tb_permission where id='$id' ";
                     
        if ($connectdb->query($sql) === TRUE) {
            // $pmiss ="";
            // $showname="";
                        echo "<h3 class='alert alert-success'>ลบ สิทธิ์ เรียบร้อย  </h3>";
                        $pins[1]="active";
        $tables  = tables();
       
     
        
        
        require_once("list_pmis.html");
                        die();
                        // require_once("list_pmis.html");
                    } else {
                        
                         $mession=  "<br> <h5>Error: " . $sql . "<br>" . $conn->error . '</h5>';
                         echo  ERR4;
                         require_once("lise_pmis.html");
    }  
                require_once("list_pmis.html");
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
        
        require_once("list_pmis.html");
    break;
    case 'add':
    require_once("add_pmis.html");
    break;
    case 'edit':
        if(isset($_GET["vals"]) && !empty($_GET["vals"])){
               $vals=$_GET["vals"];
               $connectdb = connectdb();
            $sql = "SELECT * FROM tb_permission where id = $vals";
            $result = $connectdb->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row["id"];
                $pmiss = $row["pmiss"];
                $showname = $row["showname"];
            }
            require_once("edit_pmis.html");
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