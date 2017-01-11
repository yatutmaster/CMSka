<?php defined('SYSPATH') OR die('No direct script access.');




class Admin_Tables extends Controller {

    
   public static function change_field($post,$obj)
   {
       
	   
	    $newval = '' ;
	   
        if(isset($post['table_data']))
        {
			$newval = 'table:'.$obj->request->post('table').'; field:'.$obj->request->post('field').'; idpath:'.$obj->request->post('idpath').';';
			
		}
		else
		{
			$params = DB::select('fparams')->from('a_tables')->where('id','=',$post['field_name_h'])->execute()->as_array();
		 
		    $str = '';
		 
		    if(isset($params[0]))
		    {
		
		              $values = explode(';',$params[0]['fparams']);
		
		              if(isset($values[0]))
		              {
		                   foreach($values as $k => $v)
			               {
			                     $unit = explode(':',$v);
					
					             if(isset($unit[0]) and trim($unit[0]) != 'value' and trim($unit[0]) != '')
					                 $str.= $values[$k].'; ';
			  
			               }
		   
		              }
		
		    }
		
		
		    $newval = 'value:';
		
		    if(isset($post['values'][0]))
		    {
		      foreach($post['values'] as $k1 => $v1)
			  {
			    $newval .= $v1;
				
				if(($k1+1) < count($post['values']))
				    $newval .= '|';
			  }
		
		    }
		
		    $newval .= ';'.$str;
		 

			
		}
	   
	   
	   
        // print_r($newval.$str );exit;
		
		if(isset($post['field_name']))
		{
		
		  $update['name'] = $post['field_name'];
		  $update['fparams'] = $newval;
		  
		
		  DB::update('a_tables')->set($update)->where('id', '=', $post['field_name_h'])->execute();
		
		}
		    
		  
   
   }

   public static function create_table ($post){
   

	 $sort =  preg_split('/[\D]/i', $post['sort'], -1, PREG_SPLIT_NO_EMPTY);
	 $t_name = $post['table_name'];
	 $r_t_name = $post['real_table_name'];
	 $about = $post['about'];
	 
	
	 $id = ADB::insert('a_about',array('name','uname','about','unit'),array($t_name,$r_t_name,$about,'table'));
	 
	  $arr = array(
	         'id' => 'int not null auto_increment  primary key',
	         'idpath' => 'int(10) not null',
	         'visible' => 'tinyint(1) not null',
	         'sort' => 'int(10) not null' );
	 $time = true;
	//  throw new Kohana_Exception(var_dump($post));
      for ($i=1;$i<=count($sort); $i++)
	  {
	       $int = $sort[$i-1];
	       ADB::insert('a_tables',
		                  array('name','tname','fname','ftype','fparams','visible','sort'),
						  array($post['name_'.$int],$r_t_name,$post['name_f_'.$int],
						          $post['type_'.$int],$post['params_'.$int],
                          isset($post['cancel_'.$int])?$post['cancel_'.$int]:1,$i));
			
			$type = $post['type_'.$int];
			if($type == 'text' or $type == 'date' or $type == 'select' or $type == 'input'
			     or $type == 'checkbox' or $type == 'radio' 
				    or $type == 'img' or $type == 'file' or $type == 'color')
					$arr[$post['name_f_'.$int]] = 'VARCHAR(255) NOT NULL';
			   elseif($type == 'textarea' or $type == 'multipleimg' or $type == 'multipleselect'  or $type == 'list' )
			        $arr[$post['name_f_'.$int]] = 'TEXT NOT NULL';
				  elseif($type == 'html')
			          $arr[$post['name_f_'.$int]] = 'MEDIUMTEXT NOT NULL';
					  elseif(($type == 'autodatetime' ) and $time)
					  {
			              $arr[$post['name_f_'.$int]] = 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP';
						  $time =  false;
		              }
		
	  }
	  
      ADB::create_table($r_t_name,$arr);
   
   
   }
   
   public static function change_table($post)
   {
      $tname = $post['real_table_name_h'];
	    
     if($post['table_name'] !== $post['table_name_h'])//зменяем имя в a_about name
	 {
	      DB::update('a_about')->set(array('name' => $post['table_name']))->where('uname', '=', $tname)->execute();
	 }
   
      if($post['about'] !== $post['about_h']) //изменяем значение в a_about about
      {
	      DB::update('a_about')->set(array('about' => $post['about']))->where('uname', '=', $tname)->execute();
	  }
	  
	  
	  
	  $info = DB::select('id','fname','ftype')->from('a_tables')->where('tname','=',$tname)->execute()->as_array();
   
      foreach($info as $index)//удаляем поля
	  {
	   
	    if(!isset($post['name_f_'.$index['id'].'_h']))
		{
			 if($index['ftype'] === 'img')
			 {
			   $imgs =  DB::select($index['fname'])->from($tname)->execute()->as_array();
			  // throw new Kohana_Exception(print_r($imgs));
			   foreach($imgs as $key => $val)
			   {
			       if(!empty($val[$index['fname']]))
				   {
			         $img = explode('/',$val[$index['fname']]);
			         Admin_Image::instance(UPDIR)->delImage($img[2],$img[1]);
					}
			   }
			 
			 }
			 
			  ADB::del_field($tname,$index['fname']);
		 }
	  
	  }
	  
	 $sort =  preg_split('/[\D]/i', $post['sort'], -1, PREG_SPLIT_NO_EMPTY);
	  
	 $time = true;
	 for($i=1;$i<=count($sort);$i++)
	 {
	     $id = $sort[$i-1];
		 if(isset($post['type_'.$id.'_h']))
		  {   $type = $post['type_'.$id.'_h'];
		 
		      if($type == 'autodatetime' )
		          $time = false;
		 }  
	 }
	 
	
      for($i=1;$i<=count($sort);$i++)
	  {
	     $id = $sort[$i-1];
	  
	     $vis = isset($post['cancel_'.$id])?0:1;
		   
	      if(isset($post['name_'.$id.'_h']))  //обновление полей
		  {
		    if($post['name_'.$id] !== $post['name_'.$id.'_h'])
			      DB::update('a_tables')->set(array('name' => $post['name_'.$id]))->where('name', '=', $post['name_'.$id.'_h'])->where('id', '=', $id)->execute();
	        
			if($post['params_'.$id] !== $post['params_'.$id.'_h'])
			      DB::update('a_tables')->set(array('fparams' => $post['params_'.$id]))->where('fparams', '=', $post['params_'.$id.'_h'])->where('id', '=', $id)->execute();
	        
			      DB::update('a_tables')->set(array('visible' => $vis,'sort' => $i))
				  ->where('tname', '=', $tname)
				  ->where('fname', '=', $post['name_f_'.$id.'_h'])
				  ->execute();
	        
		  
		  }else{ //добавление полей
		  
		    $type = $post['type_'.$id];
		   
			if($type == 'text' or $type == 'date' or $type == 'input'
			     or $type == 'checkbox' or $type == 'radio' 
				    or $type == 'img' or $type == 'file' or $type == 'color')
					$ftype = 'VARCHAR(255) NOT NULL';
			   elseif($type == 'textarea' or $type == 'select' or  $type == 'multipleimg' or $type == 'multipleselect' or $type == 'list')
			        $ftype = 'TEXT NOT NULL';
				  elseif($type == 'html')
			          $ftype = 'MEDIUMTEXT NOT NULL';
					  elseif(($type == 'autodatetime' ) and $time)
					  {  
			              $ftype = 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP';
						  $time =  false;
		              }else  $ftype = '';
		 
		  if(!empty($ftype))
		  { 
		     ADB::insert('a_tables',
		                  array('name','tname','fname','ftype','fparams','visible','sort'),
						  array($post['name_'.$id],$tname,$post['name_f_'.$id],$type,$post['params_'.$id],$vis,$i));
	 
		     ADB::add_field($tname,$post['name_f_'.$id],$ftype);
		  
		  
		  }
		  
		  }
		  
		  
		  
	  }
	  
   
   }

   public static function params_note ($post, $change = null)
   {
   
      $tinfo = DB::select('name','fname','ftype','fparams')
	                    ->from('a_tables');
						
	  if(Admin_Access::$data['rank'] > 1)	$tinfo->where("visible",'=','1');
					
	  $tinfo = $tinfo->where("tname",'=',$post['tname'])->order_by("sort")->execute()->as_array();
						
	  $rows = true;			
	
      $result['tname'] = $post['tname'];
	 
      foreach($tinfo as $field)
	  {
	            if($rows)//Добавить после
	             {
				      
			       $result['rows']= DB::select($field['fname'])
	                                       ->from($post['tname'])
						                   ->where("idpath",'=',$post['pid'])->order_by("sort")->execute()->as_array();
						
				   $rows = false;
				 }
				 
				$catch_table = true; 
				 
	            $vals = explode(';',$field['fparams']);
	   //   throw new Kohana_Exception(print_r($vals));
	             foreach($vals as $val)
				 {
				    $str = strpos($val,':');
					
				    if($str)
                    {				    
					  $params = explode(':',$val);
					   
				      $params = Arr::map('trim', $params);
					  
					
					  if(($field['ftype'] === 'select') or ($field['ftype'] === 'multipleselect') or ($field['ftype'] === 'radio'))
					  {
						    if($params[0] == 'table' or $params[0] == 'field' or $params[0] == 'idpath')///////////////////////если данные из таблицы
					        {

						  
						       if($catch_table)
							   {
								    $t = null;
									$f = null;
									$i = null;
								   
								   
								    foreach($vals as $v)
									{
										 $p = explode(':',$v);
										 
										 $p = Arr::map('trim', $p);
										 
										 if($p[0] == 'table') $t = $p[1];
										 if($p[0] == 'field') $f = $p[1];
										 if($p[0] == 'idpath') $i = $p[1];

									}
								    
									if($t and $f)
								    {
										try
										{
											
												$db = DB::select($f)->from($t)->where('visible','=',1);
										
										        if($i)
												{
												
												    if($i == 'parent')
													{
													
													    $parent =  (new Model_Admin_Read)->get_parent($post['pid']);
													
													    $db->where('idpath','=',$parent['id']);
													
													}
												    elseif($i == 'self')
													{
													  $db->where('idpath','=',$post['pid']);
													}
												    else
													{
													    $db->where('idpath','=',$i);
													}
												
												
												}
										
										        $res = $db->order_by('sort')->execute()->as_array();
										
											    $params[0] = 'value';
									
									            foreach($res as $k => $v)
									                  $res[$k] = $v[$f];
											 
										        $params[1] = $res;
												
												
										}
									    catch (Exception $e)  
                                        {  
                                             
                                        }  
										
									
									}
										
										
									$catch_table = false;

								
								   
							   }	   
						  
						  
					        }	  
					        else
							{
								 $params[1] = explode('|',$params[1]);
								
							}
							
					  }	  
					     
			
					  $field['params'][$params[0]] = $params[1];
					  
					  
					  
					}      
				 }
				 
			$box[] = $field;	 
	  }
	  
	  if($change)
	  {
	       
         $fdata =  DB::select()->from($post['tname'])
		                       ->where('idpath','=',$post['pid'])
							   ->where('id','=', $post['id'])->execute()->as_array();
  
		  $result['fid'] = $post['id'];
		  $result['fsort'] = $fdata[0]['sort'];
		    
	      foreach($box as  $key => $field)
	      {
		      
					
		      if(isset($field['fname']))
			  {
			       
			       $field['val'] = $fdata[0][$field['fname']];
			  }
			   	   
				   
			  if(isset($field['fname']) and $field['ftype'] === 'img' and (!empty($field['val'])))	
                 {          
				       $imarr = explode('/',$field['val']);
					   
					   if(isset($imarr[2]))
					      $field['nval']  = $imarr[2];
					   elseif(isset($imarr[1]))
						     $field['nval']  = $imarr[1];
					   else
						     $field['nval']  = $imarr[0];
							 
							 
                 }				 
				  
			  $box[$key] = $field;
		  }
	  
	  }
	   // throw new Kohana_Exception(print_r($box));
	  $result['fields'] = $box;
   
       return $result;
   }
   
 
   public static function add_note($post)
   {
    $tinfo =  DB::select('fname','ftype','fparams')->from('a_tables')
	                          ->where('tname','=',$post["tname_h_cms"])->execute()->as_array();
	                      
              $fields[] = 'idpath';
		      $values[] = $post['pid_h_cms'];
			  
			  $fields[] = 'sort';
		      $values[] = $post['after_row_cms'] ;
			  
			  $fields[] = 'visible';
		      $values[] = 1;
	 
     foreach($tinfo as $key =>$val)
	 {
	       if(($val['ftype'] === 'img') and (!empty($_FILES[$val['fname']]['name'])))
		   {
		     $imgname = self::parse_params($val,$post,$_FILES[$val['fname']]);
				  
		     if(!$imgname) continue;
		      
		       $fields[] = $val['fname'];
		       $values[] = '/'.$post['pid_h_cms'].'/'.$imgname;
		      
		      continue;
		   }elseif($val['ftype'] === 'img' and (empty($_FILES[$val['fname']]['name'])) and (isset($post[$val['fname'].'_img_cms']))) {
		     
			 $fields[] = $val['fname'];
		     $values[] =$post[$val['fname'].'_img_cms'];
			 continue;
		   }
		   ////////////////////////////////////////////
		  
		    if(($val['ftype'] === 'multipleimg') and !isset($post[$val['fname'].'_img_cms']) )//если новая запись
		   {
		        $vstring = '';
				
				parse_str($post[$val['fname'].'_ser'],$sorting);
				   
			 
			   if(isset($sorting['li']))
			   {
			   
			    foreach($sorting['li'] as $k =>$nimg)
			    {
			       $fflag = true;
			         foreach($_FILES[$val['fname']]['name'] as $k1 =>$nimg1)
			         {
					      if($nimg == $nimg1 and $fflag)
						  {
						  
						      $fimg = array();
				              $fimg['name'] = $_FILES[$val['fname']]['name'][$k1];
			                  $fimg['type'] =  $_FILES[$val['fname']]['type'][$k1];
			                  $fimg['tmp_name'] = $_FILES[$val['fname']]['tmp_name'][$k1];
			                  $fimg['error'] = $_FILES[$val['fname']]['error'][$k1];
			                  $fimg['size'] = $_FILES[$val['fname']]['size'][$k1];
						  
						      $imgname = self::parse_params($val,$post,$fimg);
							  if(!$imgname) continue;
							  
							   
							   $vstring.= '/'.$post['pid_h_cms'].'/'.$imgname.' ';
							   $_FILES[$val['fname']]['name'][$k1] = "";
							   
							   $fflag = false;
							  
						  }
					 
					 
					 }
			  
			  
			   }
           
		       }
		      
		   
		       $fields[] = $val['fname'];
		       $values[] =  substr($vstring,0,(strlen($vstring)-1));
		      
		      continue;
		   }elseif($val['ftype'] === 'multipleimg' and  (isset($post[$val['fname'].'_img_cms']))) {
		             
			          $vstring = '';
				
				      parse_str($post[$val['fname'].'_ser'],$sorting);
				
				      $hashide = false;
				
				   if(isset($sorting['li']))
				   {
				      foreach($sorting['li'] as $sk => $sval)
					  {
					      
                            foreach($post[$val['fname'].'_img_cms'] as $hk => $hval)
				            {
						          if($sval == $hval)
						           { 
									   $hashide = true;
									   unset($post[$val['fname'].'_img_cms'][$hk]);
						           }
					       
					         }
				
				            if($hashide)
							{
							     $vstring .= $sval.' ';
								 $hashide = false;
							}	 
				
				             
							$fflag = true;
							 
			                foreach($_FILES[$val['fname']]['name'] as $k1 =>$nimg1)
			                {
					            if($sval == $nimg1 and $fflag)
						        {
						  
						            $fimg = array();
				                    $fimg['name'] = $_FILES[$val['fname']]['name'][$k1];
			                        $fimg['type'] =  $_FILES[$val['fname']]['type'][$k1];
			                        $fimg['tmp_name'] = $_FILES[$val['fname']]['tmp_name'][$k1];
			                        $fimg['error'] = $_FILES[$val['fname']]['error'][$k1];
			                        $fimg['size'] = $_FILES[$val['fname']]['size'][$k1];
						  
						            $imgname = self::parse_params($val,$post,$fimg);
							         if(!$imgname) continue;
							  
							   
							        $vstring.= '/'.$post['pid_h_cms'].'/'.$imgname.' ';
							        $_FILES[$val['fname']]['name'][$k1] = "";
							   
							        $fflag = false;
							  
						        }
					 
					 
					         }
				
				
				
				      }
				     
				   } 
					 
					 
					 foreach($post[$val['fname'].'_img_cms'] as $oldk => $oldval)//удаленные остатки
					 {
				                          $oldname = explode('/',$oldval);
										  if(isset($oldname[2]))
									       Admin_Image::instance(UPDIR)->delImage($oldname[2],$oldname[1]);
					 }					   
				   
			
			
		      
		   
		       $fields[] = $val['fname'];
		       $values[] =  substr($vstring,0,(strlen($vstring)-1));
		      
		      continue;
		   }
		   
		   
		   
		   if(($val['ftype'] === 'file') and (!empty($_FILES[$val['fname']]['name'])))
		   {
		     $filename = self::safe_file($val);
				  
		     if(!$filename) continue;
		      
		       $fields[] = $val['fname'];
		       $values[] = $filename;
		      
		      continue;
		   }elseif($val['ftype'] === 'file' and (empty($_FILES[$val['fname']]['name'])) and (isset($post[$val['fname'].'_file_cms']))) {
		     
			 $fields[] = $val['fname'];
		     $values[] =$post[$val['fname'].'_file_cms'];
			 continue;
		   }
				  
			 
		    if($val['ftype'] === 'multipleselect' and isset($post[$val['fname']]))
			{
				   $str = '';  
				   
		           if(is_array($post[$val['fname']]))
					{
							   
							  
							     foreach($post[$val['fname']] as $k => $v)
								 {
								    
									      $str .= $v .'|';
								    
								 }
								 
						   
					}
						      
					$post[$val['fname']] = $str ;	
 				
			}	  
				  
				 
		    if($val['ftype'] === 'list' and isset($post[$val['fname']]))
			{
				   $str = '';  
				   
		           if(is_array($post[$val['fname']]))
					{
							   
							  
							     foreach($post[$val['fname']] as $k => $v)
								 {
								     $str .= $v .'|';
								    
								 }
								 
						   
					}
						      
					$post[$val['fname']] = $str ;	
 				
			}	  
				  
				 
		   if(!empty($post[$val['fname']]))
		   {
		      $fields[] = $val['fname'];
		      $values[] = $post[$val['fname']];
		   }
	 
	 
	 }
	
	     $values = Arr::map('trim',$values);
	
       $id = ADB::insert($post["tname_h_cms"],$fields,$values);

	 $result = [];
	   
	 if(Valid::digit($id))
	 {
		 
		  $result = self::get_note_to_table($post['tname_h_cms'],$id);	
		  $result[] = $id;
	 }
	   	
	 
	 
	return  json_encode($result);
   
   }
   
   
   public static function change_note($post)
   {
       $result = array();
   
       $fields =  DB::select('fname','ftype','fparams')->from('a_tables')
					                     ->where('tname','=',$post['tname_h_cms'])->execute()->as_array();
                       
						foreach($fields as $field)
                         {
						     if($field['ftype'] === 'img' and (!empty($_FILES[$field['fname']]['name'])))
						     {
								      $imgname = self::parse_params($field,$post,$_FILES[$field['fname']]);
		                              
									  if(!$imgname) continue;
									  
									  if(isset($post[$field['fname'].'_img_cms']))
									    {
										   $oldname = explode('/',$post[$field['fname'].'_img_cms']);
									       Admin_Image::instance(UPDIR)->delImage($oldname[2],$oldname[1]);
									    }
										
							          $result[$field['fname']] = '/'.$post['pid_h_cms'].'/'.$imgname;
									  continue;
							 }
							 
							 if($field['ftype'] === 'multipleimg'  and  (isset($post[$field['fname'].'_img_cms']) or isset($_FILES[$field['fname']]['name'][1])))
						     {
								        $vstring = '';
				
			                          	parse_str($post[$field['fname'].'_ser'],$sorting);
				
				                        $hashide = false;
				                      if(isset($sorting['li']))
									 {
				                        foreach($sorting['li'] as $sk => $sval)
					                    {
					      
						                        if(isset($post[$field['fname'].'_img_cms']))
												{
                                                   foreach($post[$field['fname'].'_img_cms'] as $hk => $hval)
				                                   {
						                                    if($sval == $hval)
						                                    { 
									                                 $hashide = true;
									                                 unset($post[$field['fname'].'_img_cms'][$hk]);
						                                     }
					       
					                                }
				                                }
				                                   if($hashide)
							                       {
							                           $vstring .= $sval.' ';
								                       $hashide = false;
							                       }	 
				
				             
							                       $fflag = true;
							 
			                                       foreach($_FILES[$field['fname']]['name'] as $k1 =>$nimg1)
			                                       {
					                                      if($sval == $nimg1 and $fflag)
						                                  {
						  
						                                           $fimg = array();
				                                                   $fimg['name'] = $_FILES[$field['fname']]['name'][$k1];
			                                                       $fimg['type'] =  $_FILES[$field['fname']]['type'][$k1];
			                                                       $fimg['tmp_name'] = $_FILES[$field['fname']]['tmp_name'][$k1];
			                                                       $fimg['error'] = $_FILES[$field['fname']]['error'][$k1];
			                                                       $fimg['size'] = $_FILES[$field['fname']]['size'][$k1];
						  
						                                           $imgname = self::parse_params($field,$post,$fimg);
							                                       if(!$imgname) continue;
							  
							   
							                                       $vstring.= '/'.$post['pid_h_cms'].'/'.$imgname.' ';
							                                       $_FILES[$field['fname']]['name'][$k1] = "";
							   
							                                       $fflag = false;
							  
						                                }
					 
					 
					                                 }
				
				
				
				                        }
				     
					                 }
									 
									 if(isset($post[$field['fname'].'_img_cms']))
									 {
					                    foreach($post[$field['fname'].'_img_cms'] as $oldk => $oldval)//удаление остаток
					                    {
				                               $oldname = explode('/',$oldval);
										       if(isset($oldname[2]))
									           Admin_Image::instance(UPDIR)->delImage($oldname[2],$oldname[1]);
					                    }					   
				   
			                         }
			
		      
		   
		                                
		                                 $result[$field['fname']] =  substr($vstring,0,(strlen($vstring)-1));
		      
		                                 continue;
							 }
							 
						    if($field['ftype'] === 'file' and (!empty($_FILES[$field['fname']]['name'])))
						     {
								      $filename = self::safe_file($field);
		                              
									  if(!$filename) continue;
									  
									  if(isset($post[$field['fname'].'_file_cms']))
									    {
										   $oldname = $post[$field['fname'].'_file_cms'];
									       @unlink(UPDIRF.$oldname);
									    }
										
							          $result[$field['fname']] = $filename;
									  continue;
							 }
							 
						   if($field['ftype'] === 'img' or $field['ftype'] === 'multipleimg' or $field['ftype'] === 'file' or $field['ftype'] === 'autodatetime' ) continue;
						 
						 
						   if($field['ftype'] === 'multipleselect')
						   {
						      $str = '';
								
						      if(isset($post[$field['fname']]) and is_array($post[$field['fname']]))
							  {
							     
							     foreach($post[$field['fname']] as $k => $v)
								 {
								     $str .= $v .'|';
								 }
							  
							  }
						      
 							  $post[$field['fname']] = $str ;
						 
						   }
						 
						   if($field['ftype'] === 'list')
						   {
						      $str = '';
								
						      if(isset($post[$field['fname']]) and is_array($post[$field['fname']]))
							  {
							     
							     foreach($post[$field['fname']] as $k => $v)
								 {
								      $str .= $v .'|';
								 }
							  
							  }
						      
 							  $post[$field['fname']] = $str ;
						 
						   }
						 
						 
						   if(isset($post[$field['fname']]))
						     $result[$field['fname']] = $post[$field['fname']];
						   
					       
						 
						   
						   
						 }
						 
						 if(!empty($result ))	
						 {
						 
						       $result = Arr::map('trim',$result);
						 
				               // throw new Kohana_Exception(print_r($result));
				                DB::update($post['tname_h_cms'])->set($result)
					                                  ->where('id', '=', $post['fid_h_cms'])
					                                  ->where('idpath', '=', $post['pid_h_cms'])
													  ->execute();
													  
													  
							   $result = self::get_note_to_table($post['tname_h_cms'],$post['fid_h_cms']);				  
				 
                         }
						 
						 
						 
		return json_encode($result);			 
   
   }
   
   
   public static function get_note_to_table($table,$id)
   {
    
       $response = array();
   
       $fields =  DB::select('fname','ftype','fparams')->from('a_tables')
					                     ->where('tname','=',$table)->order_by('sort')->offset(0)->limit(6)->execute()->as_array();
       $table_data =  DB::select()->from($table)
					                     ->where('id','=',$id)->execute()->as_array();
                    
					
		if(isset($table_data[0]))
		{
				foreach($fields as $field)
                {
					if(isset($table_data[0][$field['fname']]))
					{
						
						
						
						
						
						     if($field['ftype'] === 'img')
						     {
								   
									  $response[] =  '<img class="inner_timg" src="/_album/prev'.$table_data[0][$field['fname']].'" >';
									
									  continue;
							 }
							
							 
							 if($field['ftype'] === 'multipleimg')
						     {
								      
										
									$imgs = explode(' ',$table_data[0][$field['fname']]);
									$filename = '';
											 
									foreach($imgs as $k => $val)
									{
										if(!empty($imgs[$k]))
											$filename .='<img class="multiple-td" src="/_album/prev'.$val.'"> ';
									}
											 

									$response[] = $filename;

		                            continue;
							 }
							 
						    if($field['ftype'] === 'file')
						     {
								    
							        $response[] = '<a href="/_files/'.$table_data[0][$field['fname']].'" target="_blank"> Открыть файл </a>';	
										
							         
							        
									continue;
							 }
							 

						 //////////////////////////////////////
						   if($field['ftype'] === 'multipleselect')
						   {
						         $str = '';
						    
								 $items = explode('|',$table_data[0][$field['fname']]);
						        
							     
							     foreach($items as $k => $v)
								 {
									if(!empty($v))
								     $str .= $v .',';
								    
								 }
							  
							     $response[] = htmlentities($str);	
						      
 							
							     continue;
						 
						   }
						 //////////////////////////////////////
						   if($field['ftype'] === 'list')
						   {
						         $str = '';
						    
								 $items = explode('|',$table_data[0][$field['fname']]);
						        
							     
							     foreach($items as $k => $v)
								 {
									 if(!empty($v))
								       $str .= $v .',';
								    
									
								 }
							  
							     $response[] = htmlentities($str);	
						      
 							
							     continue;
						 
						   }
						 ////////////////////////////////////////////////
						   if($field['ftype'] === 'html' )
						   {
							   
							    $response[] = 'HTML';	
							   
							   
							    continue;
						   }
						   ////////////////////////////////////////////
						   if($field['ftype'] === 'text' 
						      or $field['ftype'] === 'textarea'
						      or $field['ftype'] === 'input'
						      or $field['ftype'] === 'select'
						      or $field['ftype'] === 'checkbox'
						      or $field['ftype'] === 'radio'
							  or $field['ftype'] === 'date' )
						   {
							  
								$t = mb_substr($table_data[0][$field['fname']],0,46);
								   
								if(mb_strlen($table_data[0][$field['fname']]) > 47)
								         $t .= '...';
									 
								$response[] = htmlentities($t);
									
							  
							    continue;
						   }

						  
						 
						 
						   if($field['ftype'] === 'color' )
						   {
							   
								$response[] = '<span class="inner_tclr"  style="background:'.$table_data[0][$field['fname']].'">';
								   
							    continue;
						   }	   
						 
						 
							$response[] = $table_data[0][$field['fname']];
							    
						  
						    
						   
					       
						 
						   
						   
					}
						 
						
	
				}
		}			
					
					
					
		return $response;			
						 
   
   }
   
   public static function get_users()
   {
       $box['admins'] = DB::select('name','id','active')
	                         ->from('a_users')
							 ->where('rank','=',1)->execute()->as_array();
	   $box['users'] = DB::select('name','id','active')
	                         ->from('a_users')
							 ->where('rank','=',2)->execute()->as_array();
   
       return $box;
   }
   
 public static function set_user($post,$add = null)
 {
       	 $check['name'] = $post['name'];
       	 $check['pass'] = $post['pass'];
		 
	   if(Admin_Access::check($check) )
	   {
		   if($add and (Admin_Access::$data['rank'] < 2))
		   {
		      $valid = Validation::factory($post)
		              ->rule(true, 'not_empty')
	                 -> rule('new-name', 'min_length', array(':value',2))
	                 -> rule('new-name', 'max_length', array(':value',20))
	                 -> rule('new-pass', 'min_length', array(':value',6))
	                 -> rule('new-pass', 'max_length', array(':value',20));
 		
	         	if($valid->check())
				{
				   $pass = md5($post['new-name'].$post['new-pass']);
				   $rank = ($post['group'] > 0)?$post['group']:2;
			     	ADB::insert('a_users',array('name','active','password','theme','rank'),
					                             array($post['new-name'],1,$pass,'hot-sneaks',$rank));
				
				}
		      
		   }
		   elseif((Admin_Access::$data['id'] == $post['user_id']))
		   {
		        $box = array();
				
		        $oldpass = md5($post['name'].$post['pass']);
		   
	                if(!empty($post['new-name']))
		                  $box['name'] = $post['new-name'];
			        else $box['name'] = $post['name'];
		   
		        if(!empty($post['new-pass']))
		                 $box['password']  = md5($box['name'].$post['new-pass']);
		        else $box['password']  = md5($box['name'].$post['pass']);
		  
	
	
		         DB::update('a_users')->set($box)
					       ->where('id', '=', $post['user_id'])
					       ->where('password', '=', $oldpass)
				           ->execute();
				     
	        }
	   }
 
 }
   
 private static function safe_file ($val)
 {
 
       $array =  explode('.',$_FILES[$val['fname']]['name']);
 
       $type = $array[(count($array) -1)];
 
       $fname = uniqid().'.'.$type;
     
       $uploadfile = UPDIRF.$fname;
	   
	   
      
       if (move_uploaded_file($_FILES[$val['fname']]['tmp_name'], $uploadfile)) {
           $result = $fname;
       } 
	   else
	   {
           $result = false;
       }
	   
	   return $result;
	   
	   
	   
 }
   
   
 private static function parse_params ($finfo,$post,$file)
 {

   
		         $size_x = null;
				 $size_y = null;				  
		         $mark = null;
			     $mark_opa= null;
				 $crwidth = null;
		         $crheight = null;
				 $cr_x = null;
				 $cr_y = null;
				 
		            $params =  explode(';',$finfo['fparams']);
		            
					if(isset($params[0]))
					{
					   foreach($params as $param)
					   {
					        $pname =  explode(':',$param);
					    
					       if($pname[0] === 'size') 
						     {
						          $size = explode("|",$pname[1]);
								  
								  $size_x = $size[0]>0?$size[0]:null;
								  $size_y = $size[1]>0?$size[1]:null;
								  
							 }
                            
							if($pname[0] === 'watermark') 
							{
							   $pmark =  explode("|",$pname[1]);
							   
							   $mark = isset($pmark[0])?$pmark[0]:null;
							   $mark_opa = isset($pmark[1])?$pmark[1]:null;
							
							}
							 
							 if($pname[0] === 'crop') 
							 {
							      $cr = explode("|",$pname[1]);
								 
								  $crwidth = isset($cr[0])?$cr[0]:null;
							      $crheight = isset($cr[1])?$cr[1]:null;
						       	  $cr_x = isset($cr[2])?$cr[2]:null;
							      $cr_y = isset($cr[3])?$cr[3]:null;
							 
							 }
					
					   }      
					}  
		   
		   
		    $imgname = Admin_Image::instance(UPDIR)
			      ->setImage($file,$post['pid_h_cms'],$size_x,$size_y,$mark,$mark_opa, $crwidth, $crheight,$cr_x,$cr_y);
				  

     return $imgname;

 } 
   
   
   
}









