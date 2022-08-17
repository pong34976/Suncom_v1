<?php
//  define("DB_TYPE", "MySQL"); // MySQL & SQLite
 define("DB_HOST", "localhost");
 define("DB_USERNAME", "root");
 define("DB_PASSWORD", "1234");
 define("DB_NAME", "db_housing");
 
  define("DB_DNS_MYSQL", "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME. ";charset=utf8mb4");
//  define("DB_DNS_SQLITE", "sqlite:db/sqlite_file");
//  define("DB_PREFIX", "yourdatabase_");
class Model {
  public  $table = "";
  public $fuse = "";
  public $pdo;
  function __construct() {
    

  }

  public function connectdb( ){

    // $this->fuse = $fuse;
    // $servername = "localhost";
    // $username = "root";
    // $password = "1234";
    // $dbname = "db_housing";
    
    // $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
$options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
];
try {
  $pdo = new PDO(DB_DNS_MYSQL, DB_USERNAME, DB_PASSWORD, $options);
} catch (Exception $e) {
  error_log($e->getMessage());
  exit('Something weird happened'); //something a user can understand
}
 
 return $pdo;
 }
  public function show( ) {
    $columns =    array( 'id_hardware',
    're_type',
    're_model',
    'img'   );;
    $table = "tb_hardware";
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
    $pdo= $this->connectdb();
    $query = "SELECT count(*) AS num_rows ";
    $query.=" FROM `".$table."` WHERE 1=1 ".$search;
       // $sth = $pdo->prepare($sql);
    // $sth->execute(  Array(':user' => $_POST['user'])     );
    //  $row = $sth->fetch(PDO::FETCH_BOTH);
    $sth = $pdo->prepare($query);
 
    $sth->execute();
    $totalData = $row['num_rows']; 
    $totalFiltered = $totalData;
    $query = "SELECT ".adapterdb("implode");
    $query.="  FROM  $table WHERE 1=1 ".$search;
    $query.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start'].",".$requestData['length']." ";
    $sth = $pdo->prepare($query);
    $sth->execute();
    
    //  ล่าสุด
         $row = $sth->fetch(PDO::FETCH_ASSOC);
     print_r($row["num_rows"]);

  }


 


}




$strawberry = new Model();
$strawberry->show( );
 ?>
 


 
 