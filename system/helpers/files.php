<?php

/**
 * Class responsible for file operations such as writing to a file
 * or reading from a file. It also provides ability to get info
 * about file permissions, paths, etc.
 * 
 * @author khernik
 */
class Files {
	
	/**
	 * Lists all files from the given directory and its subdirectories
	 *
	 * @param string $directory
	 * @return array
	 */
	public static function list_files($directory)
	{
		// Folder structure goes here
		$data = [];
		
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $filename)
		{
	        $data[] = $filename;
		}
	
		return $data;
	}
	
	/**
	 * Creates the list of files/directories in the given location and
	 * creates an array out of it
	 * 
	 * @param string $directory
	 * @return array
	 */
	public static function directory_map($directory)
	{
		// Folder structure goes here
		$data = [];
		
		// Get all files
		$locations = new DirectoryIterator(realpath($directory));
		
		foreach($locations as $node)
		{
			if($node->isDir() && ! $node->isDot())
			{
				$data[$node] = Mooo::find_files($directory . DIRECTORY_SEPARATOR . $node);
			}
			elseif($node->isFile())
			{
				$data[] = $node->fileName();
			}
		}
		
		return $data;
	}
	
	/**
	 * Returns octal permissions of the given file
	 * 
	 * @param string $path
	 */
	public static function permissions($path)
	{
		return substr(sprintf('%o', fileperms($path)), -4);
	}
	
	/**
	 * Writes to file
	 * 
	 * @param string $path
	 * @param ambigious $data
	 * @param string $mode
	 */
	public static function write($path, $data, $mode = 'w')
	{
		if(function_exists('file_put_contents'))
		{
			return @file_put_contents($path, $data);	
		}
		
		// Opens file handler
		$fh = @fopen($path, $mode);
		
		// Writes to file
		fwrite($fh, $data);

		// Closes handler
		fclose($fh);
	}
	
	/**
	 * Reades from a file
	 * 
	 * @param string $path
	 * @param string $mode
	 */
	public static function read($path, $mode = 'r')
	{
		if(function_exists('file_get_contents'))
		{
			return file_get_contents($path);
		}
		
		// Opens file handler
		$fh = @fopen($path, $mode);
		
		// Reades from file
		fread($fh, filesize($path));
		
		// Closes handler
		fclose($fh);
	}
	
	/**
	 * Delete specified files
	 * 
	 * @param array $files
	 */
	public static function delete($files = [])
	{
		foreach($files as $file)
		{
			if(is_dir($file))
			{
				$sub_files = self::list_files($file);
				
				foreach($sub_files as $sub_file)
				{
					// Deletes file
					unlink($sub_file);
				}
			}
			else
			{
				// Deletes file
				unlink($file);
			}
		}
	}
	
	/**
	 * Returns array information about given file
	 * 
	 * @param string $path
	 * @param array $returned_values
	 * @return array
	 */
	public static function info($path, $returned_values = ['name', 'server_path', 'size', 'date'])
	{
		$path = realpath($path);
		
		if(! file_exists($path))
		{
			return FALSE;
		}
		
		foreach($returned_values as $key)
		{
			switch($key)
			{
				case 'name':
					$fileinfo['name'] = substr(strrchr($path, DIRECTORY_SEPARATOR), 1);
					break;
				case 'server_path':
					$fileinfo['server_path'] = $path;
					break;
				case 'size':
					$fileinfo['size'] = filesize($path);
					break;
				case 'date':
					$fileinfo['date'] = filemtime($path);
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($path);
					break;
				case 'writable':
					$fileinfo['writable'] = is_writable($path);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($path);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = self::permissions($path);
					break;
			}
		}
		
		return $fileinfo;
	}
	
} // End \Mooo\System\Helper\Files
