<?php defined('SYSPATH') OR die('No direct script access.');


//Директории для сохранения изображений и файлов
define('UPDIR' , realpath(DOCROOT.'_album').DIRECTORY_SEPARATOR);
define('UPDIRF' , realpath(DOCROOT.'_files').DIRECTORY_SEPARATOR);

if (!Route::cache()) {
    Route::cache(TRUE);
}


Route::set('/admin', 'admin(/<param>)')
            ->defaults(array(
             'directory'  => 'admin',
             'controller' => 'main',
	         'action'     => 'index'
            ));
			
Route::set('/ajax', '_ajax(/<param>)')
            ->defaults(array(
             'controller' => 'ajax',
	         'action'     => 'index'
            ));			

////////////////////////////////////редиректы
		
	    // $redir = DB::select('id','from')->from('redirects')->where('visible','=',1)->execute()->as_array();
		
		// if(isset($redir[0]) and (!empty($redir[0]['from'])))
		// {
		      // foreach($redir as $k => $v)
		      // {    
                // Route::set('redir-'.$v['id'],$v['from'])
	                 // ->defaults(array(
		          // 'controller' => 'main',
		          // 'action'     => 'index'
	             // ));
		  
		      // }
		// }
		
//////////////////////////////////редиректы

/////////////////////////////////роуты

       $route = DB::select('id','url','module','params')
	                  ->from('a_paths')
					  ->where('visible','=',1)
					  ->where('url','<>','/')->execute()->as_array();


	   if(isset($route[0]))
		{
		    foreach($route as $key => $val)
		    {    
               $regex = null;     
			   $params = '';
				 
				 
			   if($val['params'] == 1)
               {
				   $params = '(/<param>)';
				   $regex = array('param' => '[a-z0-9\-]+');
			   }				   
				 
			  
			   
			   Route::set($val['id'],$val['url'].$params,$regex)
	                 ->defaults(array(
		          'controller' => $val['module'],
		          'action'     => 'index'
	             ));
		  
		    }
		}

/////////////////////////////////роуты



