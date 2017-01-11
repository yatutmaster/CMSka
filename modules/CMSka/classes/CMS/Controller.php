<?php defined('SYSPATH') OR die('No direct script access.');

abstract class CMS_Controller extends Controller {

 

   

 public function before()
 {
    parent::before();
	
	  
      CMS::instance();
	  
 
 }
 
 
 
}