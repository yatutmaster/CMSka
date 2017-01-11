<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Admin_CMS extends Controller {

 static  $pid;
 static  $parid;
 static  $tnames;
 static  $pname;
 static  $ptitle;
 static  $pkeys;
 static  $pdesc;
 static  $uri;
 static  $level;
 static private  $_obj;
 private $_select = null;
 private $_from;
 public $where;
 
 public function __construct()
 {
      
     $cururl = Request::current()->uri();
     $param = Request::current()->param();
		
	 if(isset($param['param']))
		   $cururl = substr($cururl, 0, strrpos($cururl, "/"));
		   
		
     $rmodel =  new Model_Admin_Read;
	 $result = $rmodel->path_info($cururl);
	// print_r($result);exit;
	 if($result and $result['pdata']['visible'] == 1)
	 {	
	   
	        if(isset($result['tables'][0]))
	        {
	               foreach($result['tables'] as $key)
		                   self::$tnames[] = $key['tname'];
		       
	        }
	        else throw new Kohana_Exception("Table is not exist. CMS::__construct");//нет таблицы модуля
	    
		    self::$pid = (int)$result['pdata']['id'];
		    self::$parid = (int)$result['pdata']['pid'];
			self::$pname = $result['pdata']['name'];
			self::$ptitle = $result['pdata']['title'];
			self::$pkeys = $result['pdata']['pkeys'];
			self::$pdesc = $result['pdata']['pdesc'];
			self::$level = $result['pdata']['level'];
			self::$uri = $cururl;
			
	 }
	 else throw HTTP_Exception::factory(404,"Page not visible");//страница скрыта
	 
	 
	 
	 // throw new Kohana_Exception(print_r($result));
 }
 
 public static function instance ()
 {
       if(! is_object(self::$_obj))
               self::$_obj = new CMS;
			   
		self::$_obj->reset_db();	
			  
  return  self::$_obj;
    
 }
 
 
 private function reset_db ()
 {
	 
    self::$_obj->where = '';
	self::$_obj->_from = '';
    self::$_obj->_select = null;
	 
 } 
 
 
 
 
 
///////////////////////////////get_data
  
 public function select ($columns = null)
 {
    $this->reset_db();	
	
    $this->_select = new Database_Query_Builder_Select(func_get_args());
   
    return $this;
     
 }
 public function update ($table = null)
 {
    
	if($table === null) $table = CMS::$tnames[0];
	
    return new Database_Query_Builder_Update($table);
 
       
 }
 
 public function from($tname = null)
 {
    if(!is_object($this->_select))
	        $this->_select = new Database_Query_Builder_Select(array());
 
    if($tname === null)
        $this->_from = $this->_select->from(CMS::$tnames[0]);
	else 	
  	   $this->_from = $this->_select->from($tname);
	   
	   if(!is_object($this->where))
	      $this->where = $this->_from->where('idpath','=',CMS::$pid);

    return $this;
 }
 
 public function where($column = null, $op = null, $value = null)
 {
    if(!is_object($this->_from))
                 $this->from();
				 
	if(($column !== null) and ($op !== null) and ($value !== null))
	          $this->where->where($column, $op, $value);
  

     return $this;		
 
 }
 

 
 public function get_data()
 {
      if(!is_object($this->_from))
                 $this->from();
			 
      $result = $this->where->where('visible',"=",1)->order_by('sort')->execute()->cached()->as_array();
 
	  $this->reset_db();
 
      return $result;
 
 
 }
 
 public function order_by($order = null, $ask= null)
 {
      if(!is_object($this->_from))
                 $this->from();
				 
      $order = $order?$order:'sort';
	 
 
      return $this->where->where('visible',"=",1)->order_by($order,$ask)->execute()->cached()->as_array();
 
 
 }
 
 public function insert($data, $tname = null)
 {
 
    if(!$tname) $tname = CMS::$tnames[0];

	$keys[] = 'idpath';
    $values[] = CMS::$pid;
	
	foreach($data as $key => $val)
	{
	 
	    $keys[] = $key;
	    $values[] = $val;
	    
	}
	
	
	 $id = DB::insert($tname, $keys)->values($values)->execute();
 
 
     return isset($id[0])?$id[0]:$id;
 }
 
 public static function crumbs($id = null)
 {
   
   $id = $id?$id:CMS::$pid;
 
   $rmod = new Model_Admin_Read;
 
   return $rmod->crumbs($id);
 }
 
 public static function depth ($depth=1,$id = 1,$offset = 0,$limit = 1000,$only_visible = null)
 {
   
   $rmod = new Model_Admin_Read;
 
   return $rmod->depth($id,$depth,$offset,$limit,$only_visible);
 }
 
 public static function  branch ($id = 1, array $select = null)
 {
   
   $rmod = new Model_Admin_Read;
 
   return $rmod->branch ($id,$select);
 }
 
 public static function final_node ($id = 1) 
 {
   
   $rmod = new Model_Admin_Read;
 
   return $rmod->final_node ($id);
 }
 
 public static function get_parent($id)
 {
   
   $rmod = new Model_Admin_Read;
 
   return $rmod->get_parent($id);
 }
 
 public static function setImage ($image,$width = null,$height = null,$mark = null,$mark_opa = 100, $crwidth = null, $crheight = null, $cr_x = null, $cr_y = null)
 {
   

 
   $imgname = Admin_Image::instance(UPDIR)->setImage($image,CMS::$pid,$width,$height,$mark,$mark_opa, $crwidth, $crheight, $cr_x, $cr_y);
   
     
   return '/'.CMS::$pid.'/'.$imgname;
   
 }
 


 
 //////////////////////////////////////////////////////////Authentification
 
 
 ////////////////////////////////////////проверка на существование пользователя в сессии и активен ли он
 public static function user_is_live($timestamp = null)
 {

     if($timestamp === null) $timestamp = 3600*24; 
 
     return  Admin_AccessCMS::user_is_live($timestamp);

 }
 

 ////////////////////////////////////////вход пользователя. заносит пользователя в сессию
 public static function user_in($data = [], $table = null, $idpath = null, $active = true, $timestamp = null)
 {
	 
	 
	  if($timestamp === null) $timestamp = 3600*24;
	  if($idpath === null) $idpath = CMS::$pid;
	  if($table === null) $table = CMS::$tnames[0];
	  
	  $active = $active?1:0;
	  
	  if(isset($data['name']) and isset($data['pass']))
	  {
		    $data = Admin_AccessCMS::clear_data($data); 
		  
		    return  Admin_AccessCMS::user_in($data, $timestamp, $idpath, $table, $active);
	  }	   
	  else throw new Kohana_Exception("in CMS::user_in. There should be data keys, name and pass ");//нет необходимых ключей в массиве $data
	
	 
 }
 
  //////////////////////простая проверка, скуществует логин пользоваетля в базе или нет
 public static function user_name_exist($name = null, $is_visible = null, $table = null, $idpath = null)
 {
	 
	  if($idpath === null) $idpath = CMS::$pid;
	  if($table === null) $table = CMS::$tnames[0];
	  
	  
	  if($name)
	  {
		    $data = Admin_AccessCMS::clear_data(['name' => $name]); 
			
		    return  Admin_AccessCMS::user_name_exist($data, $is_visible, $idpath, $table);
		  
	  }	   
	  else throw new Kohana_Exception("in CMS::user_name_exist. There should be data keys, name  ");//нет необходимых ключей в массиве $data
	
	 
 }
 
 ////////////////////////////////////////проверка на существование  пользователя в базе
 public static function user_exist($data = [], $table = null, $idpath = null)
 {
	 
	    if($idpath === null) $idpath = CMS::$pid;
	    if($table === null) $table = CMS::$tnames[0];
	 
	    if(isset($data['name']) and isset($data['pass']))
	    {
	       $data = Admin_AccessCMS::clear_data($data); 
	 
		   return Admin_AccessCMS::user_exist($data, $idpath, $table);
		}
		else throw new Kohana_Exception("in CMS::user_exist. There should be data keys, name and pass ");//нет необходимых ключей в массиве $data
 }
 
 //////////////////////////////////регистрация 
 public static function user_reg($data = [], $table = null, $idpath = null, $active = true, $timestamp = null)
 {
	   if($timestamp === null) $timestamp = 3600*24;
	   if($idpath === null) $idpath = CMS::$pid;
	   if($table === null) $table = CMS::$tnames[0];
	   
	   $active = $active?1:0;

	   if(isset($data['name']) and isset($data['pass']))
	   {
		    $data = Admin_AccessCMS::clear_data($data); 
		   
		    return  Admin_AccessCMS::user_reg($data, $table, $idpath, $timestamp, $active);  
	   }
	   else throw new Kohana_Exception("in CMS::user_reg. There should be data keys, name and pass ");//нет необходимых ключей в массиве $data

 }
 
 ////////////////////////////////////////////////////////////////////Выход пользователя
 public static function user_out()
 {
	   return  Admin_AccessCMS::user_out();
 }
 
 
 
//////////////////////////////////////////////////////////////////////////////////////////Authentification


//////////////////////////////////////////////Возвращает значения всех полей radio или checkbox или multipleselect///////////
public static function values_field($name = '')
{

    $rubr = ADB::info_fields($name);
	
	if($name != '')
	    $rubr =  isset($rubr[0]['values'][0])?$rubr[0]['values']:null;

    return $rubr;
}


//////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////Хелперы
//////////////////////////////////////////////////////////////////////
 
 	
public static function parse_tree($tree,$pid = 1) ///связываем дитишек с каждой веткой 
{
		
		$buff = [];
		
		foreach($tree as $k => $v)
		{
	
	            if($v['pid'] == $pid)
	            {
					
					$v['childs'] = self::parse_tree($tree,$v['id']);
					
					$buff[] = $v;
				}
	

		}
		
		return $buff;
		
}	
		
 
 

public static function group_by($key = null,$array)////////////////////груперует по ключу
{
		
	if($key)
	{	
		$box = [];
		$buff = $array;
		
		foreach($array as $k => $v)////////////////груперуем 
		{
			
			
			   foreach($buff as $k1 => $v1)
		       {
			
			     if($v[$key] == $v1[$key])
				 {
					 $box[$v[$key]][] = $buff[$k1];
					 
					 unset($buff[$k1]);
				 }
			        

		       }
		  
			
		}
		
		return $box;
	}	
}


public static function div_array ($divider = null,$array)////////////////////делит массив на заданное количество элементов
{
	
	
	if($divider)
	{	   
            $box = [];
		    $i = 0;
		    $k1 = 0;
		
		    foreach($array as $k => $v)
		    {

				if($i%$divider == 0) $k1++;
			   
				$box[$k1][$k] = $array[$k];
				 								  
				$i++;
			
		    }
    
	
	
	        return $box;
	}
	
}

public static function cyrillicToLatin($text, $toLowerCase = true)
{
        $matrix=array(
            "й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
            "г"=>"g","ш"=>"sh","щ"=>"shch","з"=>"z","х"=>"h","ъ"=>"",
            "ф"=>"f","ы"=>"y","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
            "о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"e","ё"=>"e",
            "я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
            "ь"=>"","б"=>"b","ю"=>"yu",
            "Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
            "Г"=>"G","Ш"=>"SH","Щ"=>"SHCH","З"=>"Z","Х"=>"X","Ъ"=>"",
            "Ф"=>"F","Ы"=>"Y","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
            "О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"E","Ё"=>"E",
            "Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
            "Ь"=>"","Б"=>"B","Ю"=>"YU",
            "«"=>"","»"=>""," "=>"-",

            "\""=>"", "\."=>"", "–"=>"-", "\,"=>"", "\("=>"", "\)"=>"",
            "\?"=>"", "\!"=>"", "\:"=>"",

            '#' => '', '№' => '',' - '=>'-', '/'=>'-', '  '=>'-',
        );

        // Enforce the maximum component length
        $maxlength = 150;
        $text = implode(array_slice(explode('<br>',wordwrap(trim(strip_tags(html_entity_decode($text))),$maxlength,'<br>',false)),0,1));

        foreach($matrix as $from=>$to)
            $text=mb_eregi_replace($from,$to,$text);

        // Optionally convert to lower case.
        if ($toLowerCase)
        {
            $text = strtolower($text);
        }

        return $text;
}
    
  

public static function parse_date($newdate = null)////////////////изменяет формат даты с yyyymmdd на dd mm yyyy
{
	
	if(Valid::date($newdate))
	{	                
                                $months = array('01' => 'Января',
						                        '02' => 'Феврыля',
												'03' => 'Марта',
												'04' => 'Апреля',
												'05' => 'Мая',
												'06' => 'Июня',
												'07' => 'Июля',
												'08' => 'Августа',
												'09' => 'Сентября',
												'10' => 'Октября',
												'11' => 'Ноября',
												'12' => 'Декабря');
	
		                 ///////////////////////////парсим дату  с y-m-d на d month y
						   $date  = explode('-',$newdate);
					   
					       $newdate  = $date[2].' '.($months[$date[1]]).' '.$date[0];
						//////////////////////////   
	}
	
	
	     return $newdate;
	
}


 
}