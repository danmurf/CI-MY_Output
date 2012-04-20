<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *     Copyright (C) 2012  Dan Murfitt
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Extended Output Class
 *
 * Adds additional functionality to the Output library.
 *
 * @package		CodeIgniter
 * @subpackage          Libraries
 * @category            Output
 * @author		Dan Murfitt
 * @link		http://twitter.com/danmurf
 * @license             http://opensource.org/licenses/gpl-license.php GNU Public License (GPLv3)
 */
class MY_Output extends CI_Output {
    
    /**
     * Clears the cache for the specified path
     * @param string $uri The URI path
     * @return boolean TRUE if successful, FALSE if not
     */
    public function clear_path_cache($uri)
    {
        $CI =& get_instance();
	$path = $CI->config->item('cache_path');
        
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
        
        $uri =	$CI->config->item('base_url').
		$CI->config->item('index_page').
		$uri;

	$cache_path .= md5($uri);
        
        return @unlink($cache_path);
    }
    
    /**
     * Clears all cache from the cache directory
     */
    public function clear_all_cache()
    {
        $CI =& get_instance();
	$path = $CI->config->item('cache_path');
        
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
        
        $handle = opendir($cache_path);

        while (($file = readdir($handle))!== FALSE) 
        {
            //Leave the directory protection alone
            if ($file != '.htaccess' && $file != 'index.html')
            {
               @unlink($cache_path.'/'.$file);
            }
        }

        closedir($handle);
    }
    
    /**
     * Checks to see if a cache file exists for the specified path
     * @param string $uri The URI path to check
     * @return boolean TRUE if it is, FALSE if not
     */
    public function path_cached($uri)
    {
        $CI =& get_instance();
	$path = $CI->config->item('cache_path');
        
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
        
        $uri =	$CI->config->item('base_url').
		$CI->config->item('index_page').
		$uri;

	$cache_path .= md5($uri);
        
        return file_exists($cache_path);
    }
    
    /**
     * Returns the cache expiration timestamp for the specified path
     * @param string $uri The URI patch to check
     * @return int|boolean The expiration Unix timestamp or FALSE if there is no cache
     */
    public function get_path_cache_expiration($uri)
    {
        $CI =& get_instance();
	$path = $CI->config->item('cache_path');
        
        $cache_path = ($path == '') ? APPPATH.'cache/' : $path;
        
        $uri =	$CI->config->item('base_url').
		$CI->config->item('index_page').
		$uri;

	$cache_path .= md5($uri);
        
        if (!$fp = @fopen($cache_path, FOPEN_READ))
        {
            return FALSE;
        }
        
        flock($fp, LOCK_SH);

        $cache = '';
        if (filesize($cache_path) > 0)
        {
            $cache = fread($fp, filesize($cache_path));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        //Strip out the embedded timestamp
        if (!preg_match("/(\d+TS--->)/", $cache, $match))
        {
            return FALSE;
        }
        
        //Return the timestamp
        return (int)trim(str_replace('TS--->', '', $match['1']));
    }
}

/* End of file MY_Output.php */
/* Location: ./application/core/MY_Output.php */