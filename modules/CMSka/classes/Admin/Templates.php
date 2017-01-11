<?php defined('SYSPATH') OR die('No direct script access.');


class Admin_Templates extends Controller {


private $_cont;
private $_view;




public static function create_module($post)
{
   $obj = new self;
   

             
   
   foreach ($post['tables'] as $value)
      ADB::insert('a_modules',array('mname','tname'),array($post['module'],$value));
      
   ADB::insert('a_about',array('name','uname','about','unit','visible'),array($post['name'],$post['module'],$post['about'],'module',$post['added']));
   
   $obj->add_cont($post['module']);
   
   if($post['view'] == 1)
       $obj->add_view($post['module']);
   
  //throw new Kohana_Exception(var_dump($post));


}


public function __construct ()
{

   $this->_cont = APPPATH.'classes/Controller';
   $this->_view = APPPATH.'views';

   $flag = is_dir($this->_cont)?true:false;
   $flag = (is_dir($this->_view) and $flag)?true:false;

   if(!$flag) throw new Kohana_Exception('нет директорий application/classes/Cotroller или application/views');
  
}

// ///////////////////////////////////////////////////////////изменяет существующий урл в init.php/////////////////////////////////////////////////////////////////////////

// public  function change_url ($id,$template,$name,$url,$params)
// {

  // $string = $this->templ_url($id,$template,$name,$url,$params);

  // $handle = fopen($this->_init, "r");
  // $contents = fread($handle, filesize($this->_init)); 
  // fclose($handle);
  
  // $contents =  preg_replace('#(\/\/'.$id.'\s).+(\/\/'.$id.'\s)#msi',$string, $contents);

  // $handle = fopen($this->_init, "w");
  // $contents = fwrite($handle, $contents); 
  // fclose($handle);

// }
// ////////////////////////////////////////////////////////////добавляет урл в init.php/////////////////////////////////////////////////////////////////////////
// public function add_url ($id,$template,$name,$url,$params)
// {
  // $string = $this->templ_url($id,$template,$name,$url,$params);

  // $handle = fopen($this->_init, "a");
  // fwrite($handle, $string); 
  // fclose($handle);

// }
// ///////////////////////////////////////////////////////////////удаляет урл/////////////////////////////////////////////////////////////////////////////////
// public function delete_url($id)
// {
  // $handle = fopen($this->_init, "r");
  // $contents = fread($handle, filesize($this->_init)); 
  // fclose($handle);
  
  // $contents =  preg_replace('#(\/\/'.$id.'\s).+(\/\/'.$id.'\s)#msi','', $contents);

  // $handle = fopen($this->_init, "w");
  // fwrite($handle, $contents); 
  // fclose($handle);

// }
//////////////////////////////////////////////////////////////удаляет файл в котроллере или в вейве///////////////////////////////////////////////////////////////////////////
public function delete ($dir,$name)
{
   if($dir == 'view') @unlink($this->_view.DIRECTORY_SEPARATOR.$name.'.php');
   if($dir == 'controller') @unlink($this->_cont.DIRECTORY_SEPARATOR.$name.'.php'); 
}
//////////////////////////////////////////////////////////////////создает фай котроллер////////////////////////////////////////////////////////////////////////////////////////////
public function add_cont ($name)
{
   $name = ucfirst(strtolower($name));
   
   $file = $this->_cont.DIRECTORY_SEPARATOR.$name.'.php';
   
  if(!is_file($file))
  {
      $handle = fopen($file, "x+");
	  $contents = $this->templ_cont($name);
      fwrite($handle, $contents); 
      fclose($handle);
	  chmod($file, 0664);
   
  }else throw new Kohana_Exception('Controler уже существует');


}
//////////////////////////////////////////////////////////////////создает файл вейв////////////////////////////////////////////////////////////////////////////////////////////////
public function add_view ($name)
{
  $name = strtolower($name);
   
   $file = $this->_view.DIRECTORY_SEPARATOR.$name.'.php';
   
  if(!is_file($file))
  {
      $handle = fopen($file, "x+");
	  $contents = $this->templ_view($name);
      fwrite($handle, $contents); 
      fclose($handle);
	  chmod($file, 0664);
   
  }else throw new Kohana_Exception('View уже существует');


}
//////////////////////////////////////////////////////////////////шаблоны вейва, котроллера, урл////////////////////////////////////////////////////////////////////////////////////////////

private function templ_cont ($name)
{


$string = "<?php defined('SYSPATH') or die('No direct script access.');

class Controller_".$name." extends CMS_Template {   //Template
// class Controller_".$name." extends CMS_Controller {   //Controller


	public function action_index()
	{
		
		\$urlsite = URL::site();
		
		\$this->template->pid = CMS::\$pid;
		\$this->template->title = CMS::\$ptitle;
		\$this->template->pkeys = CMS::\$pkeys;
		\$this->template->pdesc = CMS::\$pdesc;
		\$this->template->crumbs = CMS::crumbs();
		\$this->template->content = Twig::factory('".strtolower($name)."')
		                                        ->set('urlsite',\$urlsite)
												->set('title',CMS::\$pname);
	}	
		/////////////////////////////////////////////////////////////////////////////////////данные текущей страницы//////////////////////////////////////////////////////////////////////////////////////
		
		// CMS::\$pid;   //id страницы, idpath в таблице
		// CMS::\$parid;   //id родителя страницы
		// CMS::\$pname;   //Название раздела в меню CMS
		// CMS::\$ptitle;   //Заголовок страницы (title)
		// CMS::\$pdesc;   //Описание страницы (meta)
		// CMS::\$pkeys;   //Ключевые слова страницы (meta)
		// CMS::\$tnames;   //массив, имена таблиц с 1 до 3, связанные с модулем. имя первой таблицы \$tnames[0] и т.д
		// CMS::\$level;   //уровень вложености в дереве
		// CMS::\$uri;   //uri страницы без params path/to/page
		
		
		/////////////////////////////////////////////////////////////////////////////////////////работа с деревом сайта////////////////////////////////////////////////////////////////////////////////////

		// \$data = CMS::crumbs(\$id = null);  //крошки от главной до текущей страницы,  name, url, id,
		// \$data = CMS::depth(\$depth=1,\$id = 1,\$offset = 0,\$limit = 1000,\$only_visible = null,\$table = 'a_paths');  //возвращает глубину дерева дочерних элементов, по умолчанию от root-та
		// \$data = CMS::branch (\$id = 1, array \$select = null)  //Вывод всей ветки детишек узла без родителя,id-родитель,\$select-перечень имен полей 
		// \$data = CMS::final_node (\$id = 1)   //Нахождение всех конечных узлов родителя (самые крайние на ветке)
		// \$data = CMS::get_parent(\$id)   //возвращает родителя
		                                         
		
		//////////////////////////////////////////////////////////////////////////////////////////работа с данными///////////////////////////////////////////////////////////////////////////////////////////
		
		// \$data = CMS::instance()->get_data();  //возвращает данные связанные со страницей, === DB::select()->from(\$tnames[0])->where('idpath','=',CMS::\$pid)->where('visible','=',1)->order_by('sort')->execute()->as_array();
		
		// \$data = CMS::instance()->where('name','=','name')->get_data();   //возвращает данные только по условию
		// \$data = CMS::instance()->select('name','img','date')->get_data();  //возвращает данные  перечисленных имен
		// \$data = CMS::instance()->from('tname')->get_data();  //возвращает данные только по имени таблицы
		// \$data = CMS::instance()->from()->where->limit()->offset()->execute()->cached()->as_array();  // (->where): обьект Query Bilder-а для дополнительных условий
		// \$data = CMS::instance()->order_by('sort','desc');  //сортировка
		// \$data = CMS::values_field(\$fname = '');  // Возвращает значения всех полей radio или checkbox или multipleselect, или по имени поля \$fname  = название поля 
		// \$data = CMS::parse_tree(\$tree,\$pid = 1);  // строит массив в виде дерева 
		// \$data = CMS::instance()->insert(array('fname' => 'fdata','fname' => 'fdata'),\$tname = null);  //вставить данные в таблицу по умолчанию \$tnames[0]
		// \$data = CMS::instance()->update(\$tname = null)->set(array('username' => 'jane'))->where('username', '=', 'john')->execute();  //Обновляет данные в таблице по умолчанию \$tnames[0]
		
		///////////////////////////////////////////////////////////////////////////////////////Работа с пользователями////////////////////////////////////////////////////////////////////////////////////
		
		// в таблице должно быть два поля name и pass
		
		//  CMS::user_is_live(\$timestamp = null(deff 3600*24))////проверка на окончание сессии. если сессия не закончилась то продлеваем сессию на \$timestamp, при удаче возвращает данные пользователя, иначе вернет false
		//  CMS::user_in(['name' => '' , 'pass' => ''], \$table = null, \$idpath = null, \$active = true, \$timestamp = null(deff 3600*24))///вход пользователя. Если пользоветель ранее занесен в базе данных и не скрыт, то создается сессия и возрощает true, иначе false   
		//  CMS::user_exist(['name' => '' , 'pass' => ''], \$table = null, \$idpath = null)//////проверка на существование  пользователя в базе, при удаче возвращает данные пользователя, иначе false 
		//  CMS::user_name_exist(\$name = null, \$is_visible = null, \$table = null, \$idpath = null)////простая проверка, скуществует логин пользоваетля в базе или нет. возвращает true или false
		//  CMS::user_reg(['name' => '' , 'pass' => ''], \$table = null, \$idpath = null, \$active = true, \$timestamp = null(deff 3600*24))///////регистрация. Заносит в базу и в сессию. При удыче возвращает id только что созданной записи в таблице (id пользователя). 
		//  CMS::user_out()  //удаляет сессию  
		
		
		///////////////////////////////////////////////////////////////////////////////////////сохранение изображения////////////////////////////////////////////////////////////////////////////////////
		//сохраняет изображение в две копии одно изображение сохраняет в папке preview с разрешением заданным ранее в классе, а второе как есть, оба файла сохраняются в jpg 
        //\$mark = водяной знак png, \$mark_opa = прозрачность водю знака, \$crwidth = ширина обрезки, \$crheight = высота обр., \$cr_x = поз. x, \$cr_y = поз. y обрезки
		//\$data = имя сохраненного  изображеня /00/00000000/
		
		// \$data = CMS::setImage (\$image = \$_FILES['name'], \$width = null, \$height = null, \$mark = null, \$mark_opa = 100, \$crwidth = null, \$crheight = null, \$cr_x = null, \$cr_y = null);
		
		
	
		///////////////////////////////////////////////////////////////////////////////////////Хелперы////////////////////////////////////////////////////////////////////////////////////
		
		// \$data = CMS::group_by(\$key,\$array); ///груперует массив по ключу
		// \$data = CMS::div_array(\$divider,\$array)/////делит массив на заданное количество элементов
		// \$data = CMS::parse_date(\$newdate)////////////////изменяет формат даты с yyyymmdd на dd месяц yyyy
		// \$data = CMS::cyrillicToLatin(\$text, \$toLowerCase = true)////////////////переводит текст с кирилицы на латиницу
		// \$data = CMS::parse_tree(\$tree,\$pid = 1) ///связываем дитишек с каждой веткой 
		
		////////////////////////////////////////////////////////////404 страница
	    //throw HTTP_Exception::factory(404,'Page not visible');   
	
	
} " ;

return $string;
}

private function templ_view ($name)
{


$string = '<h2>{{title}}</h2>

<!--

<img src="{{urlsite}}_album{{img1}}" />   //путь к изображениям /_album/00/000000.jpg полный размер 
<img src="{{urlsite}}_album/prev{{img1}}" />  //путь к изображениям /_album/prev/00/000000.jpg превью
<img src="{{urlsite}}_files/{{file1}}" />  //путь к файлам /_files/file.doc

 если multiple 

 {% set imgs = img1|split(" ") %}

 {%for item in imgs %}
		 <img  src="{{urlsite}}_album/prev{{item}}"  /> 
 {%endfor%}
 
-->';

return $string;
}

private function templ_url($id,$template,$name,$url,$params)
{
    $params = ($params === 1)?'(/<param>)':'';
	 
$string = " 
//".$id." 
Route::set('".$id."', '".$url.$params."')
          ->defaults(array(
          'controller' => '".$template."',
	      'action'     => 'index'
        ));
//".$id." 
";

return $string;
}
}