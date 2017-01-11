<?php defined('SYSPATH') or die('No direct script access.');

class Admin_Tools  {


  public static function delTree($dir) 
 {
   $files = @array_diff(@scandir($dir), array('.','..'));
   
   if($files)
    {
	  foreach ($files as $file) 
         (is_dir($dir.'/'.$file)) ? self::delTree($dir.'/'.$file) : @unlink($dir.'/'.$file);
    
       return @rmdir($dir);
	}
  } 
  
  public static function getThemes()
  {
          $root = DOCROOT.'css'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR;
          $dirs = @array_diff(@scandir($root), array('.','..'));
   if($dirs)
	{	  $result = array();
           if(is_array($dirs))
		   {
		      foreach($dirs as $dir)
		       {
			     if(is_dir($root.$dir))
			      $result[] = $dir;
			   
			   }
		   
		   }else return false;
		   
		   return  $result;
    }
  }  
  
 }