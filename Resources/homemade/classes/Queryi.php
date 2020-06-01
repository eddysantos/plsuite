<?php

/*
 This class handles files uploads and downloads or metadata editing.
 */
class Queryi extends mysqli
{
  public $orderby = NULL;
  public $groupby = NULL;

  //DML Properties
  private $fields;
  private $table;
  private $left_joins = [];
  private $wheres = []; //To store the conditions of the query.
  private $wheres_values = [];

  //MySqli objects will be stored here.
  private $stmt;
  private $rslt;
  public $aff_rows;

  public $rows_returned;
  public $dataset = [];

  public $last_error = "";
  public $last_id;
  //$db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;");

  function __construct(){
    $db_configuration = parse_ini_file('db_config.ini', true);
    parent::__construct($db_configuration['global']['host'],$db_configuration['global']['user'],$db_configuration['global']['password'],$db_configuration['global']['database'],$db_configuration['global']['port']);

    if ($this->errno) {
      error_log($this->last_error = "MySql Connection Error ($this->errno): $this->error");
    }
  }

  function insert(string $table, array $data){

    // error_log("1 $table");

    $num_params = count($data);
    $s = str_repeat("s", $num_params);

    $bind_params = [$s];
    foreach ($data as $field => $value) {
      $bind_params[] =& $data[$field];
      // array_push($bind_params, $value);
    }
    // error_log("2 $table");

    $fields = implode(",", array_keys($data));

    $value_tokens = str_repeat("?,", $num_params);
    $value_tokens = rtrim($value_tokens, ",");

    $query = "INSERT INTO $table ($fields) VALUES ($value_tokens)";

    // error_log("3 $table");
    $this->stmt = $this->prepare($query);

    if (!$this->stmt) {
      $this->last_error = "MySql error ($this->errno): $this->error";

      return false;
    }

    // error_log("4 $table");
    call_user_func_array(array($this->stmt, 'bind_param'), $bind_params);
    if (!$this->stmt) {
      $this->last_error = "MySql error ($this->errno): $this->error";
      return false;
    }

    // error_log("5 $table");
    if ($this->stmt->execute()) {
      $this->aff_rows = $this->stmt->affected_rows;
      $this->last_id = $this->stmt->insert_id == 0 ? "true" :  $this->stmt->insert_id;
      return $this->last_id;
    } else {

      $this->last_error = "MySql error ({$this->stmt->errno}): {$this->stmt->error}";
      return false;
    }
  }

  function update(string $table, array $id, array $data){
    $num_params = count($data);
    $s = str_repeat("s", $num_params + 1);
    $field_value_tokens = "";
    $id_token = "$id[0] = ?";

    $bind_params = [$s];
    foreach ($data as $field => $value) {
      $field_value_tokens = "$field = ?,";
      // $bind_params[] =& $value;
      array_push($bind_params, $value);
    }
    array_push($bind_params, $id[1]); //Hay que ingresar el valor del ID a modificar

    $field_value_tokens = rtrim($field_value_tokens, ",");

    $query = "UPDATE $table SET $field_value_tokens WHERE $id_token";
    $this->stmt = $this->prepare($query);

    if (!$this->stmt) {
      $this->last_error = "MySql error ($this->errno): $this->error";

      return false;
    }

    // error_log("4 $table");
    call_user_func_array(array($this->stmt, 'bind_param'), $bind_params);
    if (!$this->stmt) {
      $this->last_error = "MySql error ($this->errno): $this->error";
      return false;
    }

    // error_log("5 $table");
    if ($this->stmt->execute()) {
      $this->aff_rows = $this->stmt->affected_rows;
      $this->last_id = $this->stmt->insert_id;
      return true;
    } else {
      $this->last_error = "MySql error ($this->errno): $this->error";
      return false;
    }
  }

  function select(string $query = NULL){
    $where = "";
    $left_joins = "";
    $bind_params = [];

    if (count($this->left_joins) > 0) {
      foreach ($this->left_joins as $left_join) {
        $left_joins .= " $left_join";
      }
    }

    if (count($this->wheres) > 0) {
      $where = "WHERE ";
      foreach ($this->wheres as $i => $a_where) {
        if ($i != 0) {
          $where .= " AND ";
        }
        $where .= $a_where;
      }
      $num_params = substr_count($where, "?");

      $s = str_repeat("s", $num_params);
      $bind_params = [$s];
      foreach ($this->wheres_values as $value) {
        array_push($bind_params, $value);
      }
    }




    $query = "SELECT $this->fields FROM $this->table $left_joins $where";

    $stmt = $this->prepare($query);
    if (!$stmt) {
      $this->last_error = "MySql error ($this->errno): $this->error";
      return false;
    }

    if ($bind_params != []) {
      call_user_func_array(array($stmt, 'bind_param'), $bind_params);
      if (!$stmt) {
        $this->last_error = "MySql error ($this->errno): $this->error";
        return false;
      }
    }

    if ($stmt->execute()) {
      $this->rows_returned = $stmt->affected_rows;

      if ($this->rows_returned > 0) {
        $rslt = $stmt->get_result();
        while ($row = $rslt->fetch_assoc()) {
          $this->dataset[] = $row;
        }
      }
      return $this->dataset;
    } else {
      $this->last_error = "MySql error ($this->errno): $this->error";
      return false;
    }



  }

  function setTable($table){
    $this->table = $table;
  }

  function setFields(array $fields){
    $this->fields =
    implode(",", $fields);
  }

  function addEqualTo($field, $value){
    $this->wheres[] = "$field = ?";
    $this->wheres_values[] = $value;
  }

  function addGreaterThan($field, $value){
    $this->wheres[] = "$field > ?";
    $this->wheres_values[] = $value;
  }

  function addLessThan($field, $value){
    $this->wheres[] = "$field < ?";
    $this->wheres_values[] = $value;
  }

  function addBetween($field, $value1, $value2){
    $this->wheres[] = [
      "$field BETWEEN ? AND ?"
    ];
    $this->wheres_values[] = $value1;
    $this->wheres_values[] = $value2;
  }

  function leftJoin($table, ...$joinConditions){
    $fullJoinStatement = "LEFT JOIN $table ON ";
    foreach ($joinConditions as $i => $joinCondition) {
      if ($i !== 0) {
        $fullJoinStatement .= " AND ";
      }
      $fullJoinStatement .= $joinCondition;
    }

    $this->left_joins[] = $fullJoinStatement;
  }
}


 ?>
