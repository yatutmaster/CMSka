<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_Admin_ADB extends Model {


  
   public static function insert ( $name, array $keys, array $values )
   {
       $result = DB::insert($name, $keys)->values($values)->execute();
					  
       return $result[0];
   }
   public static function check ($uname,$unit) 
   {
       $res = DB::select('uname')->from('a_about')->where('uname','=',$uname)->where('unit','=',$unit)->execute()->cached()->as_array();
		   
	   return   isset($res[0])?true:false;
   }
    public static function checklog ($uname) 
   {
       $res = DB::select('name')->from('a_users')->where('name','=',$uname)->execute()->cached()->as_array();
		   
	   return   isset($res[0])?true:false;
   }
   public static function create_table($name,array $cols)
   {
      $tpref = Kohana::$config->load('database');
		
   
      $sql = "CREATE TABLE IF NOT EXISTS `".$tpref['default']['table_prefix'].$name."` (";
	  $keys = '';
	  foreach($cols as $key => $value)
	  {
         $sql .=" `".$key."` ".$value.", ";
		 if($value == 'MEDIUMTEXT NOT NULL' or $value == 'TEXT NOT NULL')
		    continue;
	   	  
		 $keys .= " KEY `".$key."` (`".$key."`),";
		 
       }
	 $keys = substr($keys, 0, -1);
	 
     $sql= $sql.$keys.")ENGINE=innoDB DEFAULT CHARSET=utf8;";
   // throw new Kohana_Exception(var_dump($sql));
      DB::query(NULL,$sql)->execute();
   
   }
   public static function info_tables ()
   {
       $tpref = Kohana::$config->load('database');
		
      $sql = "select `name`,`uname` ,`about`
                from `".$tpref['default']['table_prefix']."a_about`
				where `unit` = 'table'
				ORDER BY `id` DESC";
   
   
    $res = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	
	if(isset($res[0]))
	{
	   foreach ($res as $table)
	   {
	       $mname =  DB::select('mname')->from('a_modules')->where('tname','=',$table['uname'])->execute()->cached()->as_array();
	   
	       $table['mname'] = $mname;
	       $box[]=$table;
	   }
	
	  $res = $box;
	
	}
	
	return $res;
   
   }
   public static function info_table ($name)
   {
       $tpref = Kohana::$config->load('database');
		
      
       $sql = "select `at`.`id`, `at`.`name` as fname,`at`.`fname` as rfname,
			   `at`.`ftype`,`at`.`fparams`,`at`.`visible`,`at`.`sort`
                from `".$tpref['default']['table_prefix']."a_tables` as at
				where `at`.`tname` = '".$name."'
				ORDER BY `sort` ";
   
   
       $res = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	   
	   $sql = "select `name`, `uname`,`about`,at.max
	   from `".$tpref['default']['table_prefix']."a_about`
	   inner join(select max(`id`) as max from `".$tpref['default']['table_prefix']."a_tables`where `tname` = '".$name."') as at
	   where `uname` = '".$name."' and `unit` = 'table'";
	   
       $info = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	   
	    
		$result['info'] = $info[0];
        $result['fields'] = $res;
       return $result;
   
   }
   public static function del_field($tname,$fname)
   {
       $tpref = Kohana::$config->load('database');
		
       
      DB::delete('a_tables')->where('fname', 'IN', array($fname))->where('tname', 'IN', array($tname))->execute();
	  
	  $sql  = "ALTER TABLE  `".$tpref['default']['table_prefix'].$tname."` DROP  `".$fname."` ;";
	  
      DB::query(NULL,$sql)->execute();
   }
   public static function add_field($tname,$fname,$type)
   {
        $tpref = Kohana::$config->load('database');
		
   
      $sql = "ALTER TABLE  `".$tpref['default']['table_prefix'].$tname."` ADD  `".$fname."` ".$type." ;";
	  
      DB::query(NULL,$sql)->execute();
   }
   public static function delete_table($tname)
   {
   
       $tpref = Kohana::$config->load('database');
		
   
      DB::delete('a_tables')->where('tname', 'IN', array($tname))->execute();
      DB::delete('a_modules')->where('tname', 'IN', array($tname))->execute();
      DB::delete('a_about')->where('unit', 'IN', array('table'))->where('uname', 'IN', array($tname))->execute();
   
      $sql =  "DROP TABLE ".$tpref['default']['table_prefix'].$tname;
   
      DB::query(NULL,$sql)->execute();
   } 
   public static function info_fields($name = '')
   {
   
   
     if($name) $name = " and `fname` = '".$name."'";
   
     $tpref = Kohana::$config->load('database');
		
      $sql = "select *
                from `".$tpref['default']['table_prefix']."a_tables`
				where (`ftype` = 'select' or `ftype` = 'radio' or `ftype` = 'checkbox' or `ftype` = 'multipleselect' )".$name."
				ORDER BY `id` ";
   
   
      $info = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	  
	    if(isset($info[0]))
	   {
              foreach($info as $k => $val)
              {
			       if(isset($val['fparams']))
				   {
				         $fields = explode(';',$val['fparams']);
					
						 if(isset($fields[0]))
						 {
						       foreach($fields as $k1 =>$val1)
						       {
							         $param = explode(':',$val1);
									 
									 if(isset($param[0]))
									 {
										    $param[0] = trim($param[0]);
									
										    if($param[0] == 'value')
										    {
											  $info[$k]['values']  = explode('|',$param[1]);
										    }
										    elseif($param[0] == 'table' or $param[0] == 'field' or $param[0] == 'idpath' ) 
										    {
											 
											  $info[$k]['table_data'][$param[0]] = trim($param[1]);
											 
										    }
									   
									   
									 }
							              
							   }
						 
						 }
				   
				   }
			  
			  }

	   }
	
	  return $info;
	
   }
   
   public static function info_field($id)
   {
     $tpref = Kohana::$config->load('database');
		
      $sql = "select `name`,`id` ,`fparams`
                from `".$tpref['default']['table_prefix']."a_tables`
				where `visible` = 1 and `id` = '".$id."'
				ORDER BY `id` ";
   
   
      $info = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	
	    if(isset($info[0]))
	   {
             
				         $fields = explode(';',$info[0]['fparams']);
					     // print_r($fields);exit;
						 if(isset($fields[0]))
						 {
						       foreach($fields as $k1 =>$val1)
						       {
							         $param = explode(':',$val1);
									 
									 if(isset($param[0]))
									  {
									        $param[0] = trim($param[0]);   
												  
										
										    if($param[0] == 'value' or $param[0] == 'name' )
										    {
											  $info[0][$param[0]]  = explode('|',$param[1]);
										    }
										    elseif($param[0] == 'table' or $param[0] == 'field' or $param[0] == 'idpath' ) 
										    {
											 
											  $info[0]['table_data'][$param[0]] = trim($param[1]);
											 
										    }
									  }
							              
							   }
						 
						 }
				   
				 
			  
			

	   }
	
	  return $info;
	
   }
   
    public static function info_modules ()
   {
        $tpref = Kohana::$config->load('database');
		
      $sql = "select `name`,`uname` ,`about`,`visible`
                from `".$tpref['default']['table_prefix']."a_about`
				where `unit` = 'module'
				ORDER BY `id` DESC";
   
   
    $res = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	
	if(isset($res[0]))
	{
	   foreach ($res as $module)
	   {
	       $paths =  DB::select('name','url')->from('a_paths')->where('module','=',$module['uname'])->execute()->cached()->as_array();
	   
	       $module['paths'] = $paths;
	       $box[]=$module;
	   }
	
	  $res = $box;
	
	}
	
	return $res;
   
   }
   public static function info_module ($name)
   {
       $tpref = Kohana::$config->load('database');
		
   
      $sql = "select `name`,`uname` ,`about`,`visible`
                from `".$tpref['default']['table_prefix']."a_about`
				where `uname` = '".$name."'
				and `unit` = 'module'";
   
      $res = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	   
	  $sql = "select `name`,`uname` 
                from `".$tpref['default']['table_prefix']."a_about`
				where `unit` = 'table'
				ORDER BY `id` DESC";
   
      $tables = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	   
   
   	  $sql = "select `tname`
                from `".$tpref['default']['table_prefix']."a_modules`
				where `mname` = '".$name."'";
   
      $selected = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	  $box='';
	  
	  foreach($tables as $table)
	  {
	      foreach($selected as $select)
		  {
		    if((isset($select['tname'])) and (isset($table['uname'])))
		    {
			    if ($table['uname'] == $select['tname'])
				  $table['sel'] = true;
					  
		     }
		  }
	  
	    $box[] = $table;
	  }
	   
	   
	   $total['info'] = $res[0];
	   $total['data'] = $box;
	   
	   return $total;
	   
   }
   
   public static function  change_module($post)
   {
   
      if($post['name'] !== $post['name_h'])
          DB::update('a_about')->set(array('name' => $post['name']))
						  ->where('uname', '=', $post['module_name_h'])
						  ->where('unit', '=', 'module')->execute();
   
      DB::delete('a_modules')->where('mname', 'IN', array($post['module_name_h']))->execute();
        
      
   
      foreach($post['tables'] as  $table)
        self::insert ( 'a_modules', array('mname','tname'), array($post['module_name_h'],$table) );
   
       $vis = $post['added'] == 1?1:0;
       
       DB::update('a_about')->set(array('about' => $post['about'],'visible' => $vis))
						  ->where('uname', '=', $post['module_name_h'])
						  ->where('unit', '=', 'module')->execute();
						
   
   }
   
   public static function delete_module($name)
   {
       DB::delete('a_modules')
	   ->where('mname', 'IN', array($name))->execute();
	 
	   DB::delete('a_about')
	   ->where('uname', 'IN', array($name))
	   ->where('unit', 'IN', array('module'))->execute();
	 
       $obj = new Admin_Templates;
       $obj->delete('view',$name);
       $obj->delete('controller',$name);
   
   }
   
   public static function delete_path($id)
   {
   

         $pid = DB::select('deletep')->from("a_paths")->where('id','=',$id)->execute()->as_array();
		 
		 if((Admin_Access::$data['rank'] < 2) or ($pid[0]['deletep'] == 1))
          {
		     $node = new Model_Admin_Tree;

		     $childs = $node->branch($id,array('id'));
		 
	         $boxid[]  =  $id;
		     if($childs)
		     {
		          foreach($childs as $child)
                  {

					  Admin_Tools::delTree(UPDIR.$child['id']);
					  Admin_Tools::delTree(UPDIR.'/prev/'.$child['id']);
					  $boxid[] = $child['id'];
				  }
				    
		     }
			 

             $node->delete_node($id);
			 
		     Admin_Tools::delTree(UPDIR.$id);
			 Admin_Tools::delTree(UPDIR.'/prev/'.$id);
			   
		     $tables = DB::select('uname')->from("a_about")->where('unit','=','table')->execute()->as_array();
		 
		     foreach($tables as $table)
		        DB::delete($table['uname'])->where('idpath', 'IN',$boxid)->execute();
   
          }
   }
   
   public static function delete_field($post)
   {
     
     $chekm = DB::select('module')->from('a_paths')
	              ->where('id','=',$post['pid'])->execute()->cached()->as_array();
	 $chek = DB::select('uname')->from('a_about')
	              ->where('uname','=',$chekm[0]['module'])
	              ->where('unit','=','module')
	              ->where('visible','=',1)->execute()->cached()->as_array();
				  
   if(isset($chek[0]) or (Admin_Access::$data['rank'] < 2))			  
   {
     $fimgs = DB::select('fname')->from('a_tables')
	              ->where('tname','=',$post['tname'])
				  ->where('ftype','=','img')->execute()->cached()->as_array();
   
     if(isset($fimgs[0]))
	 {
	    foreach($fimgs as $name)
	    {
		   $img = DB::select($name['fname'])->from($post['tname'])
		                       ->where('id','=',$post['fieldid'])
							   ->where('idpath','=',$post['pid'])->execute()->cached()->as_array();
							   
			if(isset($img[0]))
			{
			    $img = explode('/',$img[0][$name['fname']]);	
				
				if(isset($img[2]))
			    Admin_Image::instance(UPDIR)->delImage($img[2],$img[1]);
			 }
	    }
		
		
	 }
	 
	 $fimgs = DB::select('fname')->from('a_tables')
	              ->where('tname','=',$post['tname'])
				  ->where('ftype','=','multipleimg')->execute()->cached()->as_array();
   
     if(isset($fimgs[0]))
	 {
	    foreach($fimgs as $name)
	    {
		   $img = DB::select($name['fname'])->from($post['tname'])
		                       ->where('id','=',$post['fieldid'])
							   ->where('idpath','=',$post['pid'])->execute()->cached()->as_array();
							   
			if(isset($img[0]))
			{
				$imgs = explode(' ',$img[0][$name['fname']]);
				
				if(isset($imgs[0]))
				   foreach($imgs as $k => $v)
				        $img = explode('/',$v);
				
				if(isset($img[2]))
			    Admin_Image::instance(UPDIR)->delImage($img[2],$img[1]);
			 }
	    }
		
		
	 }
	 
	 $fimgs = DB::select('fname')->from('a_tables')
	              ->where('tname','=',$post['tname'])
				  ->where('ftype','=','file')->execute()->cached()->as_array();
   
     if(isset($fimgs[0]))
	 {
	    foreach($fimgs as $name)
	    {
		   $img = DB::select($name['fname'])->from($post['tname'])
		                       ->where('id','=',$post['fieldid'])
							   ->where('idpath','=',$post['pid'])->execute()->cached()->as_array();
							   
			if(isset($img[0]))
			{
			    @unlink(UPDIRF.$img[0][$name['fname']]);
			 }
	    }
		
		
	 }
	   DB::delete($post['tname'])
	                    ->where('id', 'IN',array($post['fieldid']))
	                    ->where('idpath','IN',array($post['pid']))->execute();
	 
    }
   }
   
   public static function checkp($url)
   {
     if($url === 'admin' or $url === '_album')
	   $result[0] = '';
	 else  
       $result =  DB::select('url')->from('a_paths')->where('url','=',$url)->execute()->cached()->as_array();
	   
	 return  isset($result[0])?true:null;
   
   }
  
   public static function bild_tables($pid,$module)
   {
      $tpref = Kohana::$config->load('database');
		
   
      $tables = DB::select('tname')->from('a_modules')->where('mname','=',$module)->order_by('id','desc')->execute()->cached()->as_array();
	  $select = '';
	  $res = null;
	   foreach ($tables as $key => $table) 
       {
	       $tfields = DB::select('name','fname','ftype','visible')->from('a_tables')->where('tname','=',$table['tname']);
	       $tname = DB::select('name')->from('a_about')->where('uname','=',$table['tname'])->where('unit','=','table')->execute()->cached()->as_array();
		     
			if(Admin_Access::$data['rank'] > 1) $tfields->where('visible','=',1);
			 
		   $tfields = $tfields->order_by('sort')->execute()->cached()->as_array();
  
	       foreach($tfields as $k =>  $fields)
		       $select.= "`".$fields['fname']."`,";
		       
	      $sql = "select ".$select."`visible`,`id`
                    from `".$tpref['default']['table_prefix'].$table['tname']."`
			    	where `idpath` = '".$pid."'
				    ORDER BY `sort` ";
					
          $res[$key]['tname'] = $table['tname'];
          $res[$key]['rtname'] = $tname[0];
   		  $res[$key]['thead'] = $tfields;
          $res[$key]['tbody']  = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
		  
		   
		   $select = '';
	   }
   return $res;

   }
  
   public static function delete_user ($post)
   {
     if($post['user'] > 0)
       DB::delete('a_users')->where('id', 'IN', array($post['user']))->execute();
	  
   }
   
   
}