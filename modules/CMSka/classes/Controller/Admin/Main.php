<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Main extends Controller {

  public function action_index()
  {
    $this->response->headers(array('Content-Type' => 'text/html', 'Cache-Control' => 'no-cache', 'Cache-Control' => 'no-store','must-revalidate'));
	$deni = Session::instance()->get('_admin');
	

	
	if(isset($deni['denied']) and $deni['denied']) //доступ закрыт
	{
	      // if($deni < time() )
		  // {
		       Admin_Access::auth_exit();
			   $this->redirect('admin', 303);
			   
		  // }else{
		       // $view = Twig::factory('Errors/404');
               // $this->response->status(404)->body($view);
		  
		  // }
		
	}elseif(Admin_Access::is_allowed()){	//доступ к админке
	
		$params = $this->request->param('param');
		 
		  
        if(Request::initial()->is_ajax())
        {		
		    switch($params)
		    {
		
		       case 'tableres':$this->table_result();break; //генерация таблиц данных на страницах
		       case 'headmod':$this->head_module();break;//страница списка модулей
		       case 'headsett':$this->head_sett();break;//страница настройки cms
		       case 'headdb':$this->head_dbase();break;//страница списка таблиц и создание таблиц в базе
		       case 'modalp':$this->modal_page();break;//модальное окно создание страницы
		       case 'modalm':$this->modal_module();break;//модальное окно создания модулей
		       case 'modalt':$this->modal_table();break;//модальное окно создания таблиц
		       case 'modalch':$this->modal_change();break;//модальные окна изменения таблиц, модулей, страниц, полей
		       case 'check':$this->check_data();break;//проверка на наличие таблицы, модуля или страницы
		       case 'delete':$this->delete_unit();break;//удаление таблицы, модуля или страницы
		       case 'pchilds':$this->path_childs();break;//генерация вложений в навигации страниц
		       case 'addpath':$this->add_path();break;//добавляет страницу
		       case 'sortel':$this->sort_elements();break;//сортировка страниц или записей в таблице
		       case 'setvis':$this->set_visible();break;//скрытие  страницы или записи в таблице
		       case 'note':$this->set_note();break;//модальное окно добавления или измененя записей в таблице
		       case 'users':$this->modal_users();break;//модальное окно пользователя
		       case 'logout':$this->log_out();break;//выход
		       case 'userscr':$this->add_user();break;//создание пользователя
		       case 'headm':$this->head_menu();break;//меню
		       case 'setth':$this->set_theme();break;//Установка темы
		
		   
		
		    }
	    }elseif($this->request->post()){ //сохранение и добавление записей в таблицу
	
	             $post =  $this->request->post();
				 
	
		
		         if(isset($post['submit_add_cms']))
				 {
				     $resp = Admin_Tables::add_note($post);
				   
					 $this->response->body('<script type="text/javascript"> window.parent.input.add('.$resp.') </script>');
				
				 }
				 elseif(isset($post['submit_change_cms']))
				 {
				 
				     $resp = Admin_Tables::change_note($post);
						
				  // print_r($resp);exit;
				     $this->response->body('<script type="text/javascript"> window.parent.input.change('.$resp.') </script>');
						
				 }
	
	    }else{	
		
	   
	            $model = new Model_Admin_Tree;
		        $file =   new Admin_Templates;
		 
		        $return= 'Add';
                $url = URL::site();
	 
	            $array =  $model->depth (1,1);

	            $data = array();
	     
		        if($array)
	           {
	               foreach($array as $key => $val)
	               {
	                    if(($val['lft']+1) != ($val['rgt']))
				               $val['nest'] = 1;
	
	                       $data[] = $val;
	
	               }
	
	            }
	             
											
		        $nav = Twig::factory('admin/nav_paths')
	                                        ->set('urlsite' , $url)
											->set('rank', Admin_Access::$data['rank'])
						                    ->set('data' , $data)
					                        ->set('parent' , 0)->render();									
								
                $content = Twig::factory('admin/welcome');
                
	
			    $twig = Twig::factory('admin/index')
						                    ->set('urlsite' , $url)
											->set('rank', Admin_Access::$data['rank'])
						                    ->set('theme' , Admin_Access::$data['theme'])
						                    ->set('nav' , $nav)
						                    ->set('content' , $content);
						                		
	            $this->response->body($twig);
	    }	  
   }elseif($this->request->post() and !Request::initial()->is_ajax()){//проверка формы входа
         
		$post = $this->request->post();

		if(Admin_Access::check($post))
		     $this->redirect('admin', 302);
		else{
		
		  $url = URL::site();
          $form = Twig::factory('admin/auth')
	                    ->set('urlsite', $url)
	                    ->set('error', '1');
      
          $this->response->body($form);
		
		}
   
   }else{
   
      if(Request::initial()->is_ajax())
	  {
	  
	       $this->response->body('access-denied');
	  
	  }
	  else
	  {
	  
	    $url = URL::site();
        $form = Twig::factory('admin/auth')
	                    ->set('urlsite', $url);
      
        $this->response->body($form);
   
	  
	  }
   
   
    
   }
       
  }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function set_theme()
	{
	    if($this->request->post())
	    {
		    $post =  $this->request->post();
		   
		    DB::update('a_users')
			      ->set(array('theme' => $post['theme']))
			      ->where('id', '=',Admin_Access::$data['id'])
                  ->execute();
		
		}
	
	}
	
	public function head_menu()
	{
	   $box =  Admin_Tables::get_users();
	   $dirs = Admin_Tools::getThemes();
	   $url = URL::site();
	   $hmenu = Twig::factory('admin/head_menu')
                                  	 ->set('urlsite', $url)
                                  	 ->set('themes', $dirs)
                                  	 ->set('rank', Admin_Access::$data['rank'])
                                  	 ->set('rtheme', Admin_Access::$data['theme'])
                                  	 ->set('admins', $box['admins'])
                                  	 ->set('users', $box['users']);
	   $this->response->body($hmenu);
	}
	
	public function add_user()
	{
	  
	  if(Admin_Access::$data['rank'] < 2)
	  { 
	   if($this->request->post() )
	    {
		   $post =  $this->request->post();
		   
		   Admin_Tables::set_user($post,true);
		    // 
		
		}
		else
		{
	        $url = URL::site();
            $form = Twig::factory('admin/modal_users')
	                    ->set('urlsite', $url)
	                    ->set('create', '1')
	                    ->set('uid', Admin_Access::$data['id']);
      
           $this->response->body($form);
		}
      }
	}
	
	public function modal_users()
	{
	     $url = URL::site();
         $form = Twig::factory('admin/modal_users')
	                    ->set('urlsite', $url)
	                    ->set('uid', Admin_Access::$data['id']);
      
        $this->response->body($form);
   
	}
	
	
	public function log_out()
	{ 
	      Admin_Access::auth_exit();
		  $this->redirect('admin', 302);
	}
	
	public function set_note()
	{
	    if($this->request->post())
	    {
		   $url = URL::site();
		   $post = $this->request->post();
		  
		   if($post['type_h_cms'] === 'add')
		   {
			      $fields =  Admin_Tables::params_note($post);  
		   }
		   elseif($post['type_h_cms'] === 'change')
		   {
			      $fields =  Admin_Tables::params_note($post,true);
		   }
			    
				
		
		   $content = Twig::factory('admin/modal_note')
				                       ->set('urlsite',$url)
				                       ->set('rank',Admin_Access::$data['rank'] < 2?true:false)
				                       ->set('pid',$post['pid'])
				                       ->set('tkey',$post['tkey'])
				                       ->set('fields',$fields);
		   $this->response->body($content);
			
		}
	
	
	}
	
	public function set_visible()
	{
	     if($this->request->post())
		 {
		     $post =  $this->request->post();
			 
			 if($post['type'] === 'path')
		        DB::update('a_paths')->set(array('visible' => $post['val']))->where('id','=',$post['id'])->execute();
			 elseif($post['type'] === 'field')
			     DB::update($post['tname'])->set(array('visible' => $post['val']))->where('id','=',$post['id'])->where('idpath','=',$post['pid'])->execute();
		     elseif(($post['type'] === 'user') and (Admin_Access::$data['rank'] < 2))
			        DB::update('a_users')->set(array('active' => $post['val']))->where('id','=',$post['id'])->execute();
			
		 }
	
	
	}
	
	public function sort_elements()
	{
	   
	   if($this->request->post())
	   {
	     $post =  $this->request->post();
	   
	     if($post['name'] === 'paths')
		{
		   
		   $i = 1;
		   foreach($post['wrapp_p'] as $id)
		   {
			     DB::update('a_paths')->set(array('sort' => $i))->where('id','=',$id)->execute();
				 $i++;
		   }
		   
	    }elseif($post['name'] === 'fields')
	      {
				
				 $i = 1;
		     foreach($post['f_sort'] as $id)
		     {
			     DB::update($post['tname'])->set(array('sort' => $i))->where('id','=',$id)->where('idpath','=',$post['pageid'])->execute();
				 $i++;
		     }
				
				
		  }
	   
	   }
	
	
	
	}
	
	public function add_path()
	{
	  if($this->request->post())
	  {
	     $post = $this->request->post();
		
	     $node = new Model_Admin_Tree;
	
		 
		 $change = isset($post['change'])?1:0;
		 $create =  isset($post['create'])?1:0;
		 $delete =  isset($post['delete'])?1:0;
		 $params =  isset($post['params'])?1:0;

		 $crp = DB::select('createp')->from('a_paths')->where('id','=',$post['parent_id'])->execute()->as_array();
		 	
		 if((Admin_Access::$data['rank'] < 2) or ($crp[0]['createp'] == 1))
		 {
	                 $newid = $node->add_node ($post['parent_id'], $post['module'], $post['url'], trim($post['name']),$post['title'],$post['desc'],$post['keys'],$change,$create,$delete,$params);  
		            
		 }
		
		
	  }
	  
	  
	}
	
	public function path_childs()
	{
	   
	   $url = URL::site();
	   
	   $data = $this->request->post();
	  if(isset($data['id']))
	  {
	       $model = new Model_Admin_Tree;
	  
	       $array =  $model->depth ($data['id'],1);
	
		   $paths = array();
	        if($array)
	        {
	                foreach($array as $key => $val)
	                {
	                    if(($val['lft']+1) != ($val['rgt']))
				           $val['nest'] = 1;
	
	                         $paths[] = $val;
	
	                }
	
	        }
		
	      $nav = Twig::factory('admin/nav_paths')
	                                      ->set('urlsite' , $url)
	                                      ->set('rank' , Admin_Access::$data['rank'])
						                  ->set('data' , $paths)
					                      ->set('parent' , $data['id']);				
	  
	  
	      $this->response->body($nav);
	  }
	
	}
	
	public function delete_unit()
	{
	   $data = $this->request->post();
	   
	   if(isset($data['table']) and (Admin_Access::$data['rank'] < 2))
	     ADB::delete_table($data['table']);
	    elseif(isset($data['module'])
           		and (strtolower($data['module']) !==  'main')
           		and (Admin_Access::$data['rank'] < 2)
			  )
	     ADB::delete_module($data['module']);
	
	    elseif(isset($data['path']))
		{
		
		       $chek = CMS::depth(1,$data['path']);
			   
	           if(isset($chek[0]))
			            $this->response->body('has-childs');
               else
			             ADB::delete_path($data['path']);
		}
		elseif(isset($data['fieldid']))
		 ADB::delete_field($data);
		 elseif(isset($data['user']) and (Admin_Access::$data['rank'] < 2))
		  ADB::delete_user($data);
		 
	}
	
	public function modal_change()
	{
	   $url = URL::site();
	
	   $data = $this->request->post();
	   If(($data['type'] == 'table') and (Admin_Access::$data['rank'] < 2))
	   {
	       if(isset($data['table_name_h']))
		   {
		
				Admin_Tables::change_table($data);
		   
		   }else{
	           $data =  ADB::info_table($data['name']);
			  
			
			
	           $result = Twig::factory('admin/change_table')
			              ->set('data', $data)
		                  ->set('urlsite' , $url);
		
	           $this->response->body($result );
		 }
	   }
	   elseIf($data['type'] == 'field')
	   {
	       // print_r($data);exit;
	       if(isset($data['field_name_h']) and Valid::digit(trim($data['field_name_h'])))
		   {
		    //  throw new Kohana_Exception(print_r($data));
			 Admin_Tables::change_field($data,$this);
		   
		   }
		   elseif(Valid::digit(trim($data['name'])))
		   {
	          $data = ADB::info_field(trim($data['name']));
			  

	           $result = Twig::factory('admin/change_field')
			              ->set('data', $data)
		                  ->set('urlsite' , $url);
		             
	           $this->response->body($result );
		   }
	   }
	   elseif(($data['type'] == 'module') and (Admin_Access::$data['rank'] < 2))
	   { 
	   
	       if(isset($data['module_name_h']))
		   {
		      ADB::change_module($data);
		 //  throw new Kohana_Exception(print_r($data));
		   }else{
	       $resp =  ADB::info_module($data['name']);
	        
			// throw new Kohana_Exception(print_r($resp['info']));
			 $result = Twig::factory('admin/change_module')
			              ->set('mod', $resp['info'])
			              ->set('data', $resp['data'])
		                  ->set('urlsite' , $url);
		
	           $this->response->body($result );
	       }
	   }
	   elseif($data['type'] == 'page')
	    {
			      if(isset($data['url_h']))
				        $id = $data['parent_id'];
				  else
                        $id = $data['name'];

                  $pch =  DB::select('changep')->from('a_paths')->where('id','=',$id)->execute()->as_array();
						
	        if((Admin_Access::$data['rank'] < 2) or ($pch[0]['changep'] == 1))   
			{	  
	            if(isset($data['url_h']))
	            {
				    $tree = new Model_Admin_Tree;
					$tree->change_node($data);
				
				}else{
		 
		          $modules =  DB::select('name','uname','visible')->from('a_about')->where('unit','=','module')->execute()->as_array();
		          $pvalue =  DB::select('name','title','pdesc','pkeys','url','module','createp','changep','deletep','params')->from('a_paths')->where('id','=',$data['name'])->execute()->as_array();
		
		           $tree = new Model_Admin_Tree;
		  
		           $parent = $tree->get_parent($data['name']);
		           $purl = ($parent['url'] == '/')?'':$parent['url'];
		
		           $pvalue[0]['furl'] = substr(strrchr($pvalue[0]['url'], "/"), 1);
		      
		           if(empty($pvalue[0]['furl']))$pvalue[0]['furl'] = $pvalue[0]['url'];
		
	               $result = Twig::factory('admin/modal_page')
				                ->set('rank' , Admin_Access::$data['rank'])
	                            ->set('urlsite',$url)
	                            ->set('purl',$purl)
	                            ->set('val',$pvalue[0])
	                            ->set('pid',$data['name'])
	                            ->set('modules',$modules);
	               $this->response->body($result );
	            }
				
			 }	
	     }elseif($data['type'] == 'user')
			       Admin_Tables::set_user($data);
			
			
			
			
			
			
	}

	public function check_data()
	{
	    if($this->request->post())
		{
		  $data = $this->request->post();
		  
		  if($data['unit'] == 'table')
		  {
		      if(ADB::check($data['val'],'table'))
			        $res='bad';
				else $res='good';
				// throw new Kohana_Exception(var_dump($res));
		  
		  }
		  elseif($data['unit'] == 'module')
		  {
		      if((ADB::check($data['val'],'module')) 
			       or ( strtolower($data['val']) === 'main')
			       or ( strtolower($data['val']) === 'ajax')
				)
			       $res='bad';
				else $res='good';
		   }
		   elseif($data['unit'] == 'page')
		   {
		     if(ADB::checkp($data['val']))
			       $res='bad';
				else $res='good';
		   }
		   elseif($data['unit'] == 'user')
		   {
		      if(Admin_Access::check($data))
			  {
		            $res='good';
				    $sess = Session::instance()->get('_admin');
					
					if(isset($sess['try'])) $sess['try'] = null;
					
				    Session::instance()->set('_admin',$sess);
					
			  }else $res='bad';
		    // throw new Kohana_Exception(print_r($res));
		   }
		   elseif($data['unit'] == 'login')
		   {
		        if(ADB::checklog($data['val']))
			       $res='bad';
				else $res='good';
		       
		   }
		   
		   
		    $this->response->body($res);
		}
	   
	}
	
	
	public function table_result()
	{
	   $url = URL::site();
	
	
	   $item_id = $this->request->post('data');
		
       if($item_id > 1)		
	   {
		    $page = DB::select()->from('a_paths')->where('id','=',$item_id)->execute()->as_array();
			
	        $chekm = DB::select('visible')->from('a_about')
			          ->where('uname','=',$page[0]['module'])
			          ->where('unit','=','module')
			          ->where('visible','=',1)
					  ->execute()->as_array();
					  
	        if(isset($chekm[0]) or (Admin_Access::$data['rank'] < 2))			  
			{	
					$tree = new Model_Admin_Tree;
					$parent = $tree->get_parent($item_id);
			
					$parents = $tree->crumbs($item_id);
			  
					$res = ADB::bild_tables($item_id,$page[0]['module']);
	
					$result = Twig::factory('admin/table_result')
		                  ->set('urlsite' , $url)
		                  ->set('rank' , Admin_Access::$data['rank'])
		                  ->set('parent' , $parent['id'])
		                  ->set('parents' , $parents)
		                  ->set('tables' , $res)
		                  ->set('page' , $page[0]);
		
					$this->response->body($result);
			}
			else $this->response->body('<h2 class="hide-table">Раздел скрыт</h2>');
		   
		   
	   }	
	   else	
	   {
		   
		    $result = Twig::factory('admin/welcome');
		
			$this->response->body($result);
		   
	   }
	  
	
	
	  
	
	    
	}
	
	public function head_module()
	{
	    
	   
	   $url = URL::site();
	   
	   $info = ADB::info_modules();
	
	   $result = Twig::factory('admin/head_module')
	                      ->set('info',$info)
						  ->set('rank' , Admin_Access::$data['rank'])
		                  ->set('urlsite' , $url);
		
	   $this->response->body($result);
	  
	}
	
	public function head_sett()
	{
	    
	   
	   $url = URL::site();
	   
	   $info = ADB::info_fields();
	   
	   
	   $result = Twig::factory('admin/head_sett')
	                      ->set('info',$info)
		                  ->set('urlsite' , $url);
		
	   $this->response->body($result);
	  
	}
	
	public function head_dbase()
	{
	   
		
		if($this->request->post())
		{
		     $params =  $this->request->post();
		     Admin_Tables::create_table($params);
		  
		}else{
              $url = URL::site();
			  $info = ADB::info_tables();
	//throw new Kohana_Exception(var_dump($info));
	          $result = Twig::factory('admin/head_dbase')
			                 ->set('rank' , Admin_Access::$data['rank'])
			                 ->set('info',$info)
		                     ->set('urlsite' , $url);
		
		      $this->response->body($result);
	    }

	
	    
	}
	
	public function modal_module()
	{
	    if($this->request->post())
		{
		     $params =  $this->request->post();
		     Admin_Templates::create_module($params);
		  
		}else{
		
		   $url = URL::site();
		
		  $data=DB::select('uname','name')->from('a_about')
		         ->where('unit','=', 'table')
				 ->order_by('id','desc')
				 ->execute()->as_array();

		
	       $result = Twig::factory('admin/modal_module')
										->set('data',$data)
		                                ->set('urlsite' , $url);
		
		         $this->response->body($result);
	    }                    
	}
	
	public function modal_page()
	{
	       $url = URL::site();
		   
	  if($this->request->post())
      {	
	       $post = $this->request->post();
	     
		   $modules =  DB::select('name','uname','visible')->from('a_about')->where('unit','=','module')->execute()->as_array();
		   $purl =  DB::select('url','createp')->from('a_paths')->where('id','=',$post['name'])->execute()->as_array();
		 
		  if($purl[0]['createp'] ==1 or  Admin_Access::$data['rank'] < 2)
		  {
		 
		        if($post['name'] == 1)$purl[0]['url'] = '';
		  
	            $result = Twig::factory('admin/modal_page')
				                ->set('rank' , Admin_Access::$data['rank'])
	                            ->set('urlsite',$url)
	                            ->set('purl',$purl[0]['url'])
	                            ->set('pid',$post['name'])
	                            ->set('modules',$modules);
								
		        $this->response->body($result);
			}
	   }
	 
	
	    
	}
	
	public function modal_table()
	{
	    $url = URL::site();
	
	   $result = Twig::factory('admin/modal_table')
		                  ->set('urlsite' , $url);
		
	  $this->response->body($result );
	
	    
	}
	
	
	
	
	
} 
