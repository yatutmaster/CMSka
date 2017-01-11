<?php defined('SYSPATH') OR die('No direct script access.');



class Admin_AccessCMS extends Controller {


   public static function user_is_live($timestamp)//////////////////////////////////пользователь активирован или нет?
   {
  
	 $sess = Session::instance()->get('CMS');
	
	 if($sess)
	 {
		 
		 $visible =  DB::select('visible')->from($sess['_table'])
		                          ->where('name','=',$sess['name'])
		                          ->where('idpath','=',$sess['idpath'])
		                          ->where('pass','=',$sess['pass'])->execute()->cached()->get('visible');

		 
		 if($visible == 1 and $sess['_time'] > time())
	     {
		 
		     $sess['_time'] = (time() + $timestamp);
		 
		     Session::instance()->set('CMS',$sess);
		     return $sess;
		 
	     }
		 else return false;
		 
		 
	 }
	 else return false;
	
  
   }
   

   public static function user_exist($data, $idpath, $table)//////////////////////проверка, скуществует пользователь в базе или нет
   {
				 
		$hash = md5($data['name'].$data['pass']);

		$check =  DB::select()->from($table)
		                          ->where('name','=',$data['name'])
		                          ->where('idpath','=',$idpath)
		                          ->where('pass','=',$hash)->execute()->cached()->as_array();
		
		if(isset($check[0]) and $check[0]['visible'] == 1)
			return $check[0];
	    else 
		    return false;
		 
   }
   
   
   public static function user_in($data, $timestamp, $idpath, $table, $visible)///////////////////////////вход пользователя
   {
		$user =	self::user_exist($data, $idpath, $table);
		
		if($user)
        {			
		     $pass = md5($data['name'].$data['pass']);
	
	         $user['pass'] = $pass;
	         $user['_table'] = $table;
	         $user['_time'] = (time() + $timestamp);
		    

			 Session::instance()->set('CMS',$user);
			 
			 return true;
	    }
		else 
			return false;
	   
		
   }
   
   

   public static function user_name_exist($data,  $is_visible, $idpath, $table)//////////////////////простая проверка, скуществует логин пользоваетля в базе или нет
   {
		 
		$db =  DB::select('name')->from($table)
		                          ->where('name','=',$data['name']);
			
        if($is_visible) $db->where('visible','=', 1);

			
	    $check =   $db->where('idpath','=',$idpath)->execute()->cached()->get('name');
		
		if($check)
		    return true;
		else
			return false;
		 
   }
   
   
   public static function user_reg($data, $table, $idpath, $timestamp, $visible)/////////////регистрация пользователя 
   {
							
        if(!self::user_exist($data, $idpath, $table))
		{
		           
		            $pass = md5($data['name'].$data['pass']);
		   
		            $sess = ['name' => $data['name'],
					         'pass' => $pass,
					         '_table' => $table,
					         'idpath' => $idpath,
					         '_time' => (time() + $timestamp),
							 'visible' => $visible];
		   
				    Session::instance()->set('CMS',$sess);
					
				    $res = DB::insert($table,['name','pass','idpath','visible'])->values([$data['name'],$pass,$idpath,$visible])->execute();
					
			        return isset($res[0])?$res[0]:false;
			  
		} else throw new Kohana_Exception("Error. User is exists in database. auth_reg");//нет необходимых ключей в массиве $data
 
   }
  
   
   public static function clear_data($data)
   {
	   	return  Arr::map('strip_tags',Arr::map('trim',$data));
   }


   public static function user_out()
   {
         
		  Session::instance()->delete('CMS');
		  return true;
		  
   }
  



  

}