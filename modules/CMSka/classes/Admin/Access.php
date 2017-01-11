<?php defined('SYSPATH') OR die('No direct script access.');



class Admin_Access extends Controller {

  public static $data = array();
 
  private static $_timeout = 3600; ////////////////////сессия на 1 час 
 
  public static function is_allowed()
  {
  
      $sess = Session::instance()->get('_admin');
	  

	  
	  if(isset($sess['hash']) and isset($sess['last_active']) and $sess['last_active'] >  time())//без активности, сессия на 1 час
	  {
	      $result = DB::select('id','rank','name','theme')->from('a_users')
		                        ->where('password','=',$sess['hash'])
		                        ->where('active','=',1)->execute()->cached()->as_array();
	  
	      if(isset($result[0]))
		  {
		        self::$data['id'] = $result[0]['id'];
		        self::$data['rank'] = $result[0]['rank'];
		        self::$data['theme'] = $result[0]['theme'];
		        self::$data['name'] = $result[0]['name'];
				
		        $sess['try'] = null;
		        $sess['last_active'] = time() + self::$_timeout;
				
				Session::instance()->set('_admin',$sess);
				
	            return true;
				
		  }else{
		       self::auth_exit();
		       return  false; 
          }		  
  
      }elseif(isset($sess['hash'])) 
               self::auth_exit();
	   
	   return false;
	  
  }

  public static function check ($post)
  {
  
	 
	 if(isset($post['pass']))
	 {
   
 		
		if(self::auth_valid($post))
		{   

		  $hash = md5($post['name'].$post['pass']);
	
		  $check =  DB::select('name')->from('a_users')->where('password','=',$hash)->where('active','=',1)->execute()->cached()->as_array();
		   
		   
		  if(isset($check[0]))
		  { 
	         $sess = [
			       'hash' => $hash,
				   'last_active' => time() + self::$_timeout //продлеваем жизнь
			 ];
		     Session::instance()->set('_admin', $sess);
			 return true;
		  }
		  else{
		    $try = Session::instance()->get('_admin');
			
			if(!isset($try['try']) or $try['try'] == null) 
			     $try['try'] = 1;
			elseif(isset($try['try']) and $try['try']< 5)
			     $try['try'] =  $try['try']+1;
			else
			     $try['denied'] = time() + 3600 ;//запретить доступ на 1 час, после 5-ти неудачных попыток
				
		    Session::instance()->set('_admin',$try);
			 
		    return false;
		  }
		
		
		
		}

       
	  }

   }
   
   
   private static function auth_valid($post)
   {
	   
	   $post= Arr::map('strip_tags',Arr::map('trim',$post));
	   
	   
	    $valid = Validation::factory($post)
		->rule(true, 'not_empty')
	    -> rule('name', 'min_length', array(':value',2))
	    -> rule('name', 'max_length', array(':value',20))
	    -> rule('pass', 'min_length', array(':value',6))
	    -> rule('pass', 'max_length', array(':value',20));
 		
		
		return $valid->check();
   }
  

   public static function auth_check($data, $time, $table)
   {
         
		 
		  if(self::auth_valid($data))
		  {
			  		  
			 $hash = md5($data['name'].$data['pass']);
	
		     $check =  DB::select('name')->from($table)->where('password','=',$hash)->execute()->cached()->as_array();
		
		    if(isset($check[0]))
		    { 
		        $sess = [
				          'hash'=> $hash,
						  'last_active'=> time() + $time
				        ];
		
		
		        Session::instance()->set('_admin',$sess);
			 
			   
			    return true;
		    }
			  
		  }
		  elseif(empty($data))
		  {
			  
			  
			  
			$sess = Session::instance()->get('_admin');
			
		    if($sess)
			{

  		       $check =  DB::select('name')->from($table)->where('password','=',$sess['hash'])->execute()->cached()->as_array();
			 
			   if(isset($sess['last_active']) and $sess['last_active'] <  time() )
			   {
				 self::auth_exit();
			     return false;
			   }
			   elseif(!isset($sess['last_active']))
			   {

			     return false;
				 
			   }
			   elseif($sess['last_active'] > 1 and $sess['last_active'] > time() and isset($check[0])) 
			   {
				     $sess['last_active'] = (time() + $time);
					 
				     Session::instance()->set('_admin',$sess);
				 	 return true;
			   }
				 
		    }
		  }
		  

		  return false;
		  
		 
   }
  

   public static function auth_exit()
   {
         
		  Session::instance()->delete('_admin');
		                  
		  return true;
		  
		 
   }
  



  

}