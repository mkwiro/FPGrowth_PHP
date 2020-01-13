<?php
/**
 *
 */
class Database
{
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $db_name = DB_NAME;

  private $dbh;
  private $stmt;

  public function __construct()
  {
    //data source name
    //disi koneksi ke PDO
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
  //cek koneksi dengan try tray catch
  //option Database

  $option=[
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ];

  try {
    $this->dbh = new PDO($dsn, $this->user, $this->pass, );
  } catch (PDOException $e) { //catch kalau gagal kirim error ke e
    echo "Connection Failed:";
    die($e->getMessage()); //kirim message error
  }
  }

//func query SQL
public function query($query)
{
  $this->stmt =$this->dbh->prepare($query);
}
//fungsi bind tipe data otomatis
public function bind($param, $value, $type =null)
{
  if (is_null($type)) {
    switch (true) {
      case is_int($value):
        $type = PDO::PARAM_INT;
        break;
      case is_bool($value):
        $type = PDO::PARAM_BOOL;
        break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
      default:
        $type = PDO::PARAM_STR;
        break;
    }
  }
//bind untuk menghindari sql injection
  $this->stmt->bindValue($param, $value, $type);
}

//eksekusi query
public function execute( )
{
  $this->stmt->execute();
}

//fetchall banyak data
public function resultSet()
{
  $this->execute();
  return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
}
//
public function resultSetFP()
{
  $this->execute();
  $bigarray=array();
  $res = $this->stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

  foreach ($res as $key => $value) {
    foreach ($value as $i => $v) {
        foreach ($v as $b) {
            $res[$key][$b] = $b;
        }
        unset($res[$key][$i]);
    }
}
 return $res;
}

//function remove duplicate
function unique_multidim_array($array, $key) {
  $temp_array = array();
  $i = 0;
  $key_array = array();
 
  foreach($array as $val) {
      if (!in_array($val[$key], $key_array)) {
          $key_array[$i] = $val[$key];
          $temp_array[$i] = $val;
      }
      $i++;
  }
  return $temp_array;
}

//fecth 1 data
public function single()
{
  $this->execute();
  return $this->stmt->fetch(PDO::FETCH_ASSOC);
}


public function rowCount()
{
  return $this->stmt->rowCount();
}
}


 ?>
