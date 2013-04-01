<?php

namespace Mooo\Modules\Database\Classes;

/**
 * The query builder for SQL statements. The patterns below:
 * 
 * 		DB::Select()
 * 		->from('users')
 * 		->where('id = ' . $_POST['id'])
 * 		->execute();
 * 
 * @author khernik
 */
class DB {
	
	/**
	 * @var string Database query
	 */
	private $query = '';
	
	/**
	 * @var array Array of variables in a query
	 */
	public $variables = array();
	
	/**
	 * @var sting Query type (SELECT/INSERT/DELETE/UPDATE)
	 */
	public static $type = '';
	
	/**
	 * Set the first part of a query
	 * 
	 * @param string $query
	 */
	private function __construct($query)
	{
		$this->query = $query;
	}
	
	/**
	 * "Select" part of a query being created
	 * 
	 * "SELECT * FROM table WHERE row = condition"
	 * 
	 * @return object
	 */
	public static function select()
	{
		DB::$type = 'SELECT';

		// The beginning of the statement
		$statement = 'SELECT ';
		
		if(func_num_args())
		{
			for($i = 0; $i < sizeof(func_num_args()); $i++)
			{
				// Create list of columns - don't add a coma to the last one
				$statement .= ( ($i == sizeof(func_num_args())) ? func_get_args()[$i].',' : func_get_args()[$i] ) . ' ';
			}
		}
		else
		{
			$statement .= '* ';
		}
		
		return new DB($statement);
	}
	
	/**
	 * "... FROM table ..."
	 * 
	 * @param string $table
	 * @return Mooo_DB
	 */
	public function from($table)
	{
		$this->query .= 'FROM ' . $table . ' ';
		
		return $this;
	}
	
	/**
	 * "... WHERE id = 5 ..."
	 * 
	 * @param string $firstCond
	 * @param string $sign
	 * @param unknown $secondCond
	 * @return Mooo_DB
	 */
	public function where($firstCond, $sign, $secondCond)
	{
		$this->query .= 'WHERE ' . $firstCond . ' ' . $sign . ' (?) ';
	
		$this->variables[] = $secondCond;
		
		return $this;
	}
	
	/**
	 * "... AND WHERE id = 5 ..."
	 *
	 * @param string $firstCond
	 * @param string $sign
	 * @param unknown $secondCond
	 * @return Mooo_DB
	 */
	public function and_where($firstCond, $sign, $secondCond)
	{
		$this->query .= 'AND ' . $firstCond . ' ' . $sign . ' ? ';
		
		$this->variables[] = $secondCond;
		
		return $this;
	}
	
	/**
	 * "... OR WHERE id = 5 ..."
	 *
	 * @param string $firstCond
	 * @param string $sign
	 * @param unknown $secondCond
	 * @return Mooo_DB
	 */
	public function or_where($firstCond, $sign, $secondCond)
	{
		$this->query .= 'OR `' . $firstCond . '` ' . $sign . ' ? ';
		
		$this->variables[] = $secondCond;
		
		return $this;
	}
	
	/**
	 * "Update" part of a query being created
	 * 
	 * "UPDATE table SET row = value"
	 * 
	 * @return Object
	 */
	public static function update($table)
	{
		DB::$type = 'UPDATE';
		
		// The beginning of the statement
		$statement = 'UPDATE ' . $table . ' ';
		
		return new DB($statement);
	}
	
	/**
	 * "... SET row = value ..."
	 * 
	 * @param array $rows
	 * @return Mooo_DB
	 */
	public function set($rows)
	{
		$statement = 'SET ';
		
		foreach($rows as $row => $value)
		{
			$statement .= $row . ' = ' . $value . ' ';	
		}		
		$this->query .= $statement;
		
		return $this;
	}
	
	/**
	 * "Insert" part of a query being created
	 * 
	 * "INSERT INTO table VALUES(value1, value2, value3)"
	 *
	 * @return Object
	 */
	public static function insert($table)
	{
		DB::$type = 'INSERT';
	
		// The beginning of the statement
		$statement = 'INSERT INTO ' . $table . ' ';
		
		return new DB($statement);
	}
	
	/**
	 * "... VALUES(value1, value2, value3) ..."
	 * 
	 * @param array $rows
	 * @return Mooo_DB
	 */
	public function values($rows)
	{
		$statement = 'VALUES(';
		
		$i = 0;
		
		// Every table should have an ID column
		$statement .= 'id, ';		
		foreach($rows as $row => $value)
		{
			$i = $i + 1;
			$statement .= (sizeof($rows) == $i) ? '\''.$value . '\')' : '\''.$value.'\'' . ', ';
		}		
		$this->query .= $statement;
		
		return $this;
	}
	
	/**
	 * "Delete" part of a query being created
	 * 
	 * "DELETE FROM table"
	 *
	 * @return Object
	 */
	public static function delete($table)
	{
		DB::$type = 'DELETE';
	
		// The beginning of the statement
		$statement = 'DELETE FROM ' . $table . ' ';
	
		return new DB($statement);
	}
	
	/**
	 * Execute query created above
	 * 
	 * @return multitype:
	 */
	public function execute($fetch = NULL)
	{
		if($fetch === NULL)
		{
			$outcome = Database::execute($this->query, $this->variables);
		} else {
			$outcome = Database::execute($this->query, $this->variables)->fetch();
		}
		
		return $outcome;
	}
	
} // End \Mooo\Modules\Datase\Classes\DB
