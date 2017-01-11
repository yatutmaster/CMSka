<?php defined('SYSPATH') or die('No direct script access.');





class Admin_Image extends Controller {

 private $dir = null;
 private $x = 400;
 private $y = null;
 private $quality = 80;
 private $watermark = null;
 

 
 public function __construct ($dir)
 {
    $this->dir = $dir;
 }
 public static function instance ($dir = null)
 {
 
    if(($dir === null) or (!is_dir($dir)))
	  throw new Exception("Не указана директория, для сохранения изображений или нехватает прав  - Image::instance on line ");
	  
    return new self($dir);
 }
 
 

///сохраняет изображение в две копии одно изображение сохраняет в папке preview с разрешение заданны ранее в классе, а второе как есть, оба файла сохраняются в jpg 
 public function setImage ($image,$dirname,$width = null,$height = null,$mark = null,$mark_opa = 100, $crwidth = null, $crheight = null, $cr_x = null, $cr_y = null)
 {
     
	  $dir = $this->dir.trim($dirname).'/';
      $dirprev =  $this->dir.'prev/'.trim($dirname).'/';
      

     if (!is_dir($dirprev))
	    {
	       if (!mkdir($dirprev,0755,true))
		     throw new Exception("Неудается создать директории для пользователя - Image::seImage on line ");   	
	    }
	   
     if (!is_dir($dir))
	    {
	       if (!mkdir($dir))
		     throw new Exception("Неудается создать директории для пользователя - Image::seImage on line ");   	
	    }
	  
     if (
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg','jpeg','png','gif')))
        {         
			// $err['status']  = false;
			// $err['message'] = "Возникла непредвиденная ощибка, перед загрузкой изображения,
                      			  // удостовертесь что файл имеет расширение jpg, jpeg, jpg или gif, и не превышет 5-ти мегабайт";
			// throw new Exception("Неудается найти директорию пользователя - Image::delImage on line ");   	
			return false;	
        }
        
	if ($file = Upload::save($image, NULL, $dir))	
    {
     	
	   $prev = Image::factory($file);
	   
	    $mime =  explode('/',$prev->mime);
	   
	      $result = uniqid();
     	  $imgname = $result.'.'.$mime[1];
     	  $result = $imgname;
		  
	  
	   if($mark) $prev->watermark($mark, TRUE, TRUE,$mark_opa);
	   if($crwidth or $crheight) $prev->crop($crwidth, $crheight,$cr_x,$cr_y);
	 
	   if(($width === null) and ($height === null)) 
		   $prev ->resize($this->x, $this->y)->save($dirprev.$imgname);
        else
		   $prev->resize($width, $height)->save($dirprev.$imgname);
		 
		
        $img =  Image::factory($file);
		  
		  if($mark) $img->watermark($mark, TRUE, TRUE,$mark_opa);
	      
		$img->save($dir.$imgname,$this->quality);
        
		  unlink($file);
		
		 
     }else $result = false;
	 
	 return  $result;
	
 }

 	   			   
 
 ///возвращает все прев файлы, нужно передать id(то есть директорию где лежат файлы)
 public function getAllPrev($dirname)
 {
   $dir = $this->dir.'/prev/'.trim($dirname);
   
   if (!is_dir($dir))
        throw new Exception("Неудается найти директорию изображений - Image::getAll on line "); 
    
    $dir = new DirectoryIterator($dir);
	
    foreach ($dir as $file) 
    {
            if ($file->isFile())
                 $files[] = trim($dirname).strstr($file->getFilename(), '.', true);        
    }
   
    return isset($files)?$files:false;
   
 }
 
 ///удаляет файл, надо передать имя файла и id(то есть директорию где лежит файл)
 public function delImage($image,$dirname)
 {
     
	  $dir = $this->dir.trim($dirname).DIRECTORY_SEPARATOR;
      $dirprev = $this->dir.'prev/'.trim($dirname).DIRECTORY_SEPARATOR;
 
      // if (!is_dir($dirprev))
	     // throw new Exception("Неудается найти директорию пользователя - Image::delImage on line ");   	
	 
    
      @unlink($dir.$image);
      @unlink($dirprev.$image);
     
      return $this;	 
			 
			 			 
 }
 
 
 







}