<?php defined('SYSPATH') or die('No direct script access.');

class Model_Admin_Tree extends Model_Admin_Read {



//////////////////////////////////////////////////////////////////////////////////создание дочернего узла /////////////////////////////////////////////////////////////////////////////////
public function  add_node ($pid = 1, $tname = null, $url = null, $name = null, $title = null, $desc = null, $keys = null, $change = 0,$create = 0,$delete = 0,$params = 0, $table = 'a_paths')
{
     $result = false;
	
	 $tpref = Kohana::$config->load('database');
		

     if($tname and $url and $name )
	 {
	    
	   DB::query(NULL, 'LOCK TABLE `'.$tpref['default']['table_prefix'].$table.'` WRITE')->execute();//Lock
	   
	   $rgtkey = DB::select('rgt','level','url')->from($table)
						  ->where('id','=',$pid)->execute()->cached()->as_array();

	   if(isset($rgtkey[0]))
	   {
	         $rgtkey = $rgtkey[0];
			 
			 $url =  ($pid==1)?$url:$rgtkey['url'].'/'.$url;

			 $check = DB::select('id')->from($table)
			                   ->where('url','=',$url)->execute()->cached()->as_array();
			 
			 if(isset($check[0]))
               			 throw new Kohana_Exception(' URL ('.$url.') уже существует в Model_Admin_Tree::add_node');//проверка. Есть ли уже такой урл или шаблон?
			 else{
			  DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `rgt` = `rgt` + 2 WHERE `rgt` >= '.$rgtkey['rgt'])->execute();
			  DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `lft` = `lft` + 2 WHERE `lft` > '.$rgtkey['rgt'])->execute();
			 
			 $result = DB::insert($table, array('lft', 'rgt','level','module','url','name','title','pdesc','pkeys','visible','changep','createp','deletep','params'))
			               ->values(array($rgtkey['rgt']*1, $rgtkey['rgt']+1, $rgtkey['level']+1, $tname,$url,$name,$title,$desc,$keys,1,$change,$create,$delete,$params))->execute();
				
						   
					 $result = $result[0];   
	         }
	   }else  throw new Kohana_Exception('Шаблон не найден в Model_Admin_Tree::add_node');
	 
	    DB::query(NULL, 'UNLOCK TABLES')->execute();//Unlock
	    
	 }
     else throw new Kohana_Exception('неопределено url ('.$url.') или шаблона ('.$tname.') или имя ('.$name.') модуля в Model_Admin_Tree::add_node');

	     if($result)
	     {
		     $parent = $this->get_parent($result);	 
			 $max_sort = DB::select(array(DB::expr('MAX(`sort`) '),'sort'))->from($table)->execute()->get('sort');
		     DB::update($table)->set(array('pid' => $parent['id'],'sort' => $max_sort+1))->where('id', '=', $result)->execute();
		 }
	  
	  
    return $result;
}

//////////////////////////////////////////////////////////////////////////удаление узла и всех его детишек/////////////////////////////////////////////////////////////////////////////////////
public function  delete_node ($id = null, $table = 'a_paths')
{
      $tpref = Kohana::$config->load('database');
		


     if($id)
	 {
	     DB::query(NULL, 'LOCK TABLE `'.$tpref['default']['table_prefix'].$table.'` WRITE')->execute();//Lock
	   
	     $data = DB::query(Database::SELECT,"SELECT `lft`, `rgt`, (`rgt` - `lft` + 1) as `width` FROM `".$table."` WHERE `id` =".$id)->execute()->cached()->as_array();

	   if(isset($data[0]))
	   {
	         $data = $data[0];
			 
			 DB::query(Database::DELETE,"DELETE FROM `".$table."` WHERE lft BETWEEN ".$data['lft']." AND ".$data['rgt'])->execute();
			 
			 DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `rgt` = `rgt` - '.$data['width'].' WHERE `rgt` > '.$data['rgt'])->execute();
			 DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `lft` = `lft` - '.$data['width'].' WHERE `lft` > '.$data['rgt'])->execute();
			
			$result = true;
			
	   }else  $result = false;
	 
	   DB::query(NULL, 'UNLOCK TABLES')->execute();//Unlock
	   
	 
	  return $result;
	 
	 }
     else throw new Kohana_Exception('не передан id в Model_Admin_Tree::delete_node');


}



//////////////////////////////////////////////////////////////////переименовывает url , имя узла////////////////////////////////////////////////////////////////////////////
public function change_node ($post,$table = 'a_paths')
{
        $tpref = Kohana::$config->load('database');
		

        $id = $post['parent_id'];
		$parent =  $this->get_parent($id);
	
	    if(!$parent) throw new Kohana_Exception('Шаблон не найден (неправильное имя шаблона) Model_Admin_Tree::change_node');
	   
	    $url = ($parent['url'] == '/')?$post['url']:$parent['url']."/".$post['url'];
	   
	    $sql = "SELECT `url` FROM `".$tpref['default']['table_prefix'].$table."`
            	  WHERE `url` = '".$url."'
				  AND `id` != '".$id."'";
	   
	    $data = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	   
	    $data = isset($data[0])?false:true;
	    
		if($data)
		{
		   $ch = isset($post['change'])?1:0;
           $cr = isset($post['create'])?1:0;
           $dl = isset($post['delete'])?1:0;
           $pr = isset($post['params'])?1:0;
		   
		   $module = isset($post['module'])?$post['module']:'';
		 
		  DB::update($table)->set(array('name' => trim($post['name']),
		                                             'title' => $post['title'],
		                                             'pdesc' => $post['desc'],
													 'pkeys' => $post['keys'],
		                                             'url' => $url,
													 'module' => $module,
													 'changep' => $ch,
													 'createp' => $cr,
													 'deletep' => $dl,
													 'params' => $pr))->where('id','=',$id)->execute();
													 
       					 
													 
		}											 
		else  throw new Kohana_Exception('URL exists in Model_Admin_Tree::change_node');
	   
	 
  
}
///////////////////////////////////////////////////////////////////////////////Добавляет узел  к родителю с уже существующим модулем////////////////////////////////////////////////////////////
public function  add_url ($id = null, $tname = null, $url = null, $name = null, $table = 'a_paths')
{
     $tpref = Kohana::$config->load('database');
		

     $result = false;

     if($id and $tname and ($tname!='root') and $url and $name)
	 {
	    
	   $parent = DB::select('url','lft','rgt','level')->from($table)->where('id','=',$id)->execute()->cached()->as_array();
	   
	   if(!$parent[0]) throw new Kohana_Exception($id.'- id родителя не найден в  Model_Admin_Tree::add_url');
	   $parent = $parent[0];
	   
	   $tname = DB::select('module')->from($table)->where('module','=',$tname)->execute()->cached()->as_array();
	   
	   if(!$tname[0]) throw new Kohana_Exception($tname.'- module не найден в  Model_Admin_Tree::add_url');
	   $tname = $tname[0]['module'];
	   
	   $url = ($parent['url'] == '/')?$url:$parent['url']."/".$url;
	   
	   $check = DB::select('url')->from($table)->where('url','=',$url)->execute()->cached()->as_array();
    
       DB::query(NULL, 'LOCK TABLE `'.$tpref['default']['table_prefix'].$table.'` WRITE')->execute();//Lock
		
	   if(!isset($check[0]))//если урл не существует то добавляем его 
	   {
			 DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `rgt` = `rgt` + 2 WHERE `rgt` >= '.$parent['rgt'])->execute();
			 DB::query(Database::UPDATE,'UPDATE `'.$tpref['default']['table_prefix'].$table.'` SET `lft` = `lft` + 2 WHERE `lft` > '.$parent['rgt'])->execute();
			 
			 $child = DB::insert($table, array('lft', 'rgt','level','module','visible','url','name'))
			               ->values(array($parent['rgt']*1, $parent['rgt']+1, $parent['level']+1, $tname,1,$url,$name))->execute();		
              			   
			$result = true;	   
	        
	   }
	 
	    DB::query(NULL, 'UNLOCK TABLES')->execute();//Unlock
	    
	 }
     else throw new Kohana_Exception('неопределено url ('.$url.') или шаблона ('.$tname.') или имя ('.$name.') модуля в Model_Admin_Tree::add_url');
	 
	    if($result)
	     {
		     $parent = $this->get_parent($child[0]);	 
		     DB::update($table)->set(array('pid' => $parent['id']))->where('id', '=', $child[0])->execute();
		 }

    return $result;
}
//////////////////////////////////////////////////////////////////////////////////////////изменяет имя шаблона на уже существующий////////////////////////////////////////////////////////////////////
public function change_templ ($id = null,$module = null,$table = 'a_paths')
{
     $tpref = Kohana::$config->load('database');
		

   if($id and ($id!=1) and $module)
   {
      $sql = 'SELECT `module` FROM `'.$tpref['default']['table_prefix'].$table."` WHERE `module` = '".$module."'";
   
      $tpl =  DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	 
	  if(isset($tpl[0]))
	  {
	      $tpl = $tpl[0]['module'];
	      
		  $sql = "UPDATE `".$tpref['default']['table_prefix'].$table."` SET `module` = '".$tpl."' WHERE `id` = ".$id;
		  
		 $data = DB::query(Database::UPDATE,$sql)->execute();
	  
	  }else throw new Kohana_Exception('Этот шаблон несуществует ('.$module.') в Model_Admin_Tree::add_templ');
	 
   }else throw new Kohana_Exception('Не передано имя шаблона ('.$module.') или id ('.$id.') в Model_Admin_Tree::add_templ');
  
   throw new Kohana_Exception(var_dump($data));

}


}
