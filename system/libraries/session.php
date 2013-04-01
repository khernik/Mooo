<?php

namespace Mooo\System\Session;
http://www.perfectgirls.net/227350/Priya_Rya_was_getting_nailed_on_a_yacht_while_she_was_having_fun_with_her_friends

// hashing session ids
/**
 * This class handles operations on sessions such as enabling them in the script,
 * or getting/setting information from its global variables
 * 
 * @author khernik
 */
class Session {
	
	/**
	 * @var integer $expiration
	 */
	public $expiration = 1800;
	
	/**
	 * @var integer $time_to_update
	 */
	public $time_to_update = 300;
	
	/**
	 * @var boolean $expire_on_close
	 */
	public $expire_on_close = FALSE;
	
	/**
	 * @var boolean $use_database
	 */
	public $use_database = FALSE;
	
	/**
	 * @var string $table_name
	 */
	public $table_name = '';
	
	/**
	 * @var boolean $match_ip
	 */
	public $match_ip = TRUE;
	
	/**
	 * @var boolean $match_useragent
	 */
	public $match_useragent = FALSE;
	
	/**
	 * @var string $cookie_name
	 */
	public $cookie_name = '';
	
	/**
	 * @var string $cookie_path
	 */
	public $cookie_path = '';
	
	/**
	 * @var string $cookie_prefix
	 */
	public $cookie_prefix = '';
	
	/**
	 * @var integer $gc_probability
	 */
	public $gc_probability = 5;
	
	/**
	 * @var integer $now
	 */
	public $now;
	
	/**
	 * @var array $userdata
	 */
	public $userdata = [];
	
	/**
	 * Something like session start. It opens new session, updates if one already exists,
	 * and do some initial stuff.
	 * 
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		// Set given options in class'es attributes
		foreach($params as $key => $value)
		{
			$this->$key = (property_exists($this, $key)) ? $valye : $this->$key;
		}
		
		// Set current time
		$this->now = time();
		
		// Set the session expiration time to 2 years if 0 integer given
		if ($this->expiration === 0)
		{
			$this->expiration = (60*60*24*365*2);
		}
		
		// Set cookie name
		$this->cookie_name = $this->cookie_prefix.$this->cookie_name;
		
		// Should we start new session, or update current one if exists?
		if(! $this->read())
		{
			$this->start();
		}
		else
		{
			$this->update();
		}
		
		// Garbage collection for database if neccessary 
		$this->database_gc();
	}
	
	/**
	 * Checks if session is already initialized, and if it is initialized
	 * properly. Also, if using database, we download session info from the
	 * database and set it here.
	 * 
	 * @return boolean
	 */
	private function read()
	{
		// Fetch the cookie
		if(isset($_COOKIE[$this->cookie_name]))
		{
			$session = $_COOKIE[$this->cookie_name];
		}
		
		// Session cookie was not found
		if (! isset($session))
		{
			return FALSE;
		}
		
		// Unserialize the session array
		$session = unserialize($session);
		
		// Is the session data with the correct format?
		if (!is_array($session) || !in_array(['id', 'ip_address', 'user_agent', 'last_activity', 'user_data'], $session))
		{
			$this->destroy();
			return FALSE;
		}
		
		// Did session expire?
		if (($session['last_activity'] + $this->expiration) < $this->now)
		{
			$this->destroy();
			return FALSE;
		}
		
		// Does the IP match?
		if ($this->match_ip && $session['ip_address'] !== \Mooo\System\Core\Request::$ip)
		{
			$this->destroy();
			return FALSE;
		}
		
		// Does the User Agent match?
		if ($this->match_useragent && trim($session['user_agent']) !== \Mooo\System\Core\Request::$user_agent)
		{
			$this->destroy();
			return FALSE;
		}
		
		// Is there a corresponding session in the DB?
		if ($this->use_database && $this->table_name != '')
		{
			$query = DB::select($this->table_name)->where('session_id', '=', $session['id']);
	
			if ($this->match_ip)
			{
				$query->and_where('ip_address', '=', $session['ip_address']);
			}
	
			if ($this->match_useragent)
			{
				$query->and_where('user_agent', '=', $session['user_agent']);
			}
			
			$query->execute();
			
			// If session doesn't exist in the database
			if ($query->num_rows() === 0)
			{
				$this->destroy();
				return FALSE;
			}
			
			// Is there custom data?
			$row = $query->row();
			
			if (isset($row->userdata) && $row->userdata != '')
			{
				$custom_data = unserialize($row->userdata);
				
				if(is_array($custom_data))
				{
					foreach ($custom_data as $key => $value)
					{
						$session[$key] = $value;
					}
				}
			}
		}
		
		// Save current session here
		$this->userdata = $session;
		
		return TRUE;
	}
	
	/**
	 * Writes new session variables
	 */
	private function write()
	{
		// Just set cookies if we are not using database
		if (! $this->use_database || $this->table_name != '')
		{
			$this->_set_cookie();
			return;
		}
		
		// Run the update query
		DB::update($this->table_name)->where('session_id', '=', $this->userdata['session_id'])
		->update([
			'last_activity' => $this->userdata['last_activity'],
			'user_data' 	=> $this->userdata['user_data']
		])->execute();
		
		// Write the cookie
		$this->_set_cookie();
	}
	
	/**
	 * Regenerate session
	 */
	private function update()
	{
		// Isn't it too early for an update?
		if (($this->userdata['last_activity'] + $this->time_to_update) >= $this->now)
		{
			return;
		}
	
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = $id = str_pad(rand(0, pow(10, 31)), 32, '0', STR_PAD_LEFT);
		
	
		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;
	
		// Update the session ID and last_activity field in the DB if needed
		if ($this->use_database && $this->table_name != '')
		{
			DB::update($this->table_name)
			->where('session_id', '=', $old_sessid)
			->values([
				'last_activity' => $this->now,
				'session_id'	=> $new_sessid,
			])->execute();
		}
		
		// Write the cookie
		$this->_set_cookie();
	}
	
	/**
	 * Start new session
	 */
	private function start()
	{
		// Generate session id
		$id = str_pad(rand(0, pow(10, 31)), 32, '0', STR_PAD_LEFT);
		
		// Set session info
		$this->userdata = [
			'session_id' 	=> $id,
			'ip_address' 	=> \Mooo\System\Core\Request::$ip,
			'user_agent' 	=> \Mooo\System\Core\Request::$user_agent,
			'last_activity' => $this->now,
			'user_data'		=> ''
		];
		
		// Save session info into database
		if($this->use_database === TRUE && $this->table_name != '')
		{
			DB::insert($this->table_name)->values($this->userdata)->execute();
		}
		
		// Set cookies
		$this->_set_cookie();
	}
	
	/**
	 * Set new session variable
	 * 
	 * @param mixed $data
	 * @param string $value
	 */
	public function set_userdata($data = [], $value = '')
	{
		if(is_string($data))
		{
			$this->userdata['user_data'][$data] = $value;
		}
		elseif(sizeof($data) > 0)
		{
			foreach($data as $key => $value)
			{
				$this->userdata['user_data'][$key] = $value;
			}
		}
		
		// Set cookies for a new session variables
		$this->write();
	}
	
	/**
	 * Unset session variable
	 * 
	 * @param mixed $data
	 */
	public function unset_userdata($data = [])
	{
		if(is_string($data))
		{
			$data = [$data];
		}
		
		foreach($data as $row)
		{
			unset($this->userdata['user_data'][$row]);
		}
		
		$this->write();
	}
	
	/**
	 * Returns single session variable
	 * 
	 * @return string
	 */
	public function get_userdata($data)
	{
		return $this->userdata['user_data'][$data];
	}
	
	/**
	 * Returns all session variables
	 * 
	 * @return array
	 */
	public function all_userdata()
	{
		return $this->userdata['user_data'];
	}
	
	/**
	 * Destroy session variables
	 */
	private function destroy()
	{
		// Kill the session DB row
		if ($this->use_database && $this->table_name != '')
		{
			DB::delete($this->table_name)->where('session_id', '=', $this->userdata['session_id']);
		}
		
		// Kill the cookie
		setcookie(
			$this->cookie_name,
			addslashes(serialize([])),
			($this->now - 31500000),
			$this->cookie_path,
			$this->cookie_domain,
			0
		);
		
		// Kill session data
		$this->userdata = [];
	}
	
	/**
	 * Sets cookie data out of the userdata attribute
	 */
	private function _set_cookie()
	{		
		// Serialize the userdata for the cookie
		$cookie_data = serialize($this->userdata['user_data']);
		
		// Expiration time
		$expire = ($this->expire_on_close) ? 0 : $this->sess_expiration + time();
		
		// Set the cookie
		setcookie(
			$this->cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
		);
	}
	
	/**
	 * Cleaning garbage collection for database sessions
	 */
	private function database_gc()
	{
		if (!$this->use_database || $this->table_name == '')
		{
			return;
		}
		
		srand(time());
		if ((rand() % 100) < $this->gc_probability)
		{
			$expire = $this->now - $this->expiration;
			
			DB::delete($this->table_name)
			->where('last_activity', '<', $expire)
			->execute();
		}
	}
	
} // End \Mooo\System\Session\Session
