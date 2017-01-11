<?php defined('SYSPATH') OR die('No direct script access.');

abstract class CMS_Template extends Controller_Main {

 

   

 public function before()
 {
    parent::before();
	
	 CMS::instance();
    
	  
 
 }
 
 
 
}