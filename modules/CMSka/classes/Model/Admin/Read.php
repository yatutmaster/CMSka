<?php defined('SYSPATH') or die('No direct script access.');

class Model_Admin_Read extends Model {

public function path_info ($path)
{

    $pinfo = DB::select('id','name','title','module','pdesc','pkeys','visible','pid','level')
	                            ->from('a_paths')
								->where('url','=',$path)->execute()->cached()->as_array();
    if(isset($pinfo[0]))
	{
       $result['tables']  = DB::select('tname')
	                           ->from('a_modules')
							   ->where('mname','=',$pinfo[0]['module'])->order_by('id','desc')->execute()->cached()->as_array();
							   
	   $result['pdata'] = $pinfo[0];
	   
	}else $result = false;
	
   return $result;


}

/////////////////////////////////////////////////////////////////////////Вывод всей ветки детишек узла без родителя /////////////////////////////////////////////////////////////////////////////////////
public function branch ($id = 1, array $select = null,$table = 'a_paths')
{
     $tpref = Kohana::$config->load('database');
		

   if($id)
   {
     $order = '';
   
     if(($table!='a_paths')and($select === null))
	 {
	    $select = '`node`.*';
	 }
     elseif(($select === null)and ($table=='a_paths')) 
	 {
	           $select = '`node`.`module`,`node`.`name`,`node`.`id`,`node`.`title`,`node`.`pdesc`,`node`.`pkeys`,`node`.`pid`,`node`.`url`,`node`.`sort`,`node`.`level`,`node`.`module`,`node`.`visible`,`node`.`createp`,`node`.`changep`,`node`.`deletep`';
			   $order =  'ORDER BY `node`.`level`';
	 }
     else{
	   $dump = ''; 
	   $int = 1;
	   foreach($select as $value)
	   {
	      if($int===1)$dump.='`node`.`'.$value.'`';
	      $dump.=',`node`.`'.$value.'`';
		  $int = 0;
	   } 
		 $select = $dump;
	 }
	 
	
	 
     $sql = "SELECT ".$select."
               FROM `".$tpref['default']['table_prefix'].$table."` AS node,
               `".$tpref['default']['table_prefix'].$table."` AS parent
               WHERE `node`.`lft` BETWEEN `parent`.`lft` AND `parent`.`rgt`
               AND `parent`.`id` = '".$id."'
			   AND `node`.`id` != '".$id."' ".$order;
	 
	 
     $data = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	 
    }else throw new Kohana_Exception('неопределено имя шаблона в Model_Admin_Tree::branch');
	 
	 
	return isset($data[0])?$data:null;

}


////////////////////////////////////////////////////////////Нахождение всех конечных узлов родителя (самые крайние на ветке)///////////////////////////////////////////////////////////////////////////////
public function final_node ($id = 1,$table = 'a_paths')  
{

   $tpref = Kohana::$config->load('database');
		
   $sql = "SELECT `node`.`module`,`node`.`name`,`node`.`id`,`node`.`title`,`node`.`pdesc`,`node`.`pkeys`,`node`.`pid`,`node`.`url`,`node`.`sort`,`node`.`level`,`node`.`module`,`node`.`visible`,`node`.`createp`,`node`.`changep`,`node`.`deletep`
               FROM `".$tpref['default']['table_prefix'].$table."` AS node,
               `".$tpref['default']['table_prefix'].$table."` AS parent
               WHERE `node`.`lft` > `parent`.`lft` AND `node`.`rgt` < `parent`.`rgt` 
			   AND `node`.`rgt` = `node`.`lft` + 1
               AND `parent`.`id` = ".$id." 
               ORDER BY `node`.`sort` ";
	 
	 
    $data = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	 
	 
	return isset($data[0])?$data:null;


}


////////////////////////////////////////////////////////////Получение  пути от ребенка до root-а (хлебные крошки)///////////////////////////////////////////////////////
public function crumbs ($id = 1,$table = 'a_paths')
{
     $tpref = Kohana::$config->load('database');
		
  
  
    $sql = "SELECT `parent`.`module`,`parent`.`name`,`parent`.`title`,`parent`.`pdesc`,`parent`.`pkeys`,`parent`.`url`,`parent`.`id`,`parent`.`level`
               FROM `".$tpref['default']['table_prefix'].$table."` as parent
               inner join (select `lft` from `".$tpref['default']['table_prefix'].$table."` where `id` = '".$id."') as node
               on `node`.`lft` BETWEEN `parent`.`lft` AND `parent`.`rgt` AND `parent`.`visible`<> 0
               ORDER BY `parent`.`id` ";
	 
	 
     $data = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	 
   
	
	return isset($data[0])?$data:null;
   

}



///////////////////////////////////////////////////////////////Вывод Глубины поддерева ветки без родителя//////////////////////////////////////////////////////////////
public function depth ($id = 1,$depth = 1,$offset = 0,$limit = 1000,$only_visible = null,$table = 'a_paths')
{

     $tpref = Kohana::$config->load('database');
		

     $sql = "SELECT `child`.`module`,`child`.`createp`,`child`.`changep`,`child`.`deletep`,`child`.`name`,`child`.`title`,`child`.`pdesc`,`child`.`pkeys`,`child`.`id`,`child`.`pid`,`child`.`lft`,`child`.`rgt`,`child`.`url`,`child`.`sort`,`child`.`visible`, `child`.`level` as level
               FROM `".$tpref['default']['table_prefix'].$table."` as child
			   inner join (select `lft`,`rgt`,`level`,`module` from `".$tpref['default']['table_prefix'].$table."` where `id` = '".$id."') as node
               on `child`.`lft` BETWEEN `node`.`lft` AND `node`.`rgt`
			   and `child`.`level` <= `node`.`level`+".$depth ."
			   and `child`.`level` != `node`.`level`";
			  
	 if($only_visible)	$sql .= ' and `child`.`visible` = 1 ';
			  
			  
      $sql .=  "ORDER BY `child`.`sort` LIMIT ".$limit." OFFSET ".$offset;
	
      $data = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
	 
  return isset($data[0])?$data:null;
	 
//	



}


//////////////////////////////////////////////////////////////////////изменение видимости родителя и его потомков, (виден или не виден)///////////////////////////////////////////////////////////////
public function visible ($id = 1,$visible = true,$table = 'a_paths' )
{

     $tpref = Kohana::$config->load('database');
		

     $visible = $visible?1:0;
	 
     $sql = "UPDATE `".$table."` as parent,
	           `".$tpref['default']['table_prefix'].$table."` as node
               SET `parent`.`visible` = ".$visible."
               WHERE `parent`.`lft` BETWEEN `node`.`lft` AND `node`.`rgt`
			   AND `node`.`id` = '".$id."'";
			   	 
     $data = DB::query(Database::UPDATE,$sql)->execute();
	  
	 throw new Kohana_Exception(var_dump($data));


}


/////////////////////////////////////////////////////////////////////возвращает родителя////////////////////////////////////////////////////////////////////////////////////////
public function get_parent($id, $table = 'a_paths')
{
        $tpref = Kohana::$config->load('database');
		
        $sql = "SELECT `parent`.`url`,`parent`.`id`,`parent`.`pid`,`parent`.`module`,`parent`.`title`,`parent`.`pdesc`,`parent`.`pkeys`,`parent`.`level`,`parent`.`name`,`parent`.`lft`,`parent`.`rgt`
	              FROM `".$tpref['default']['table_prefix'].$table."` as parent 
				  INNER JOIN (SELECT `lft`,`rgt`,`level` FROM `".$tpref['default']['table_prefix'].$table."` WHERE `id` = '".$id."') as child
				  ON `parent`.`level` = `child`.`level`-1
				  AND `parent`.`lft`< `child`.`lft` AND `child`.`rgt`<`parent`.`rgt`";
				 		 
	    $purl = DB::query(Database::SELECT,$sql)->execute()->cached()->as_array();
		
		  return isset($purl[0])?$purl[0]:false;
}
}