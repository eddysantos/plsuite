<?php namespace Homemade;

class  queryBuilder {
    private $db = "";


    public function __construct($mysqli)
    {
      $this->db = $mysqli;
      if (is_a($this->db, 'mysqli')) {
        throw new Exception("The parameter passed to the Query Builder must be mysqli.");
      }

    }
}

?>
