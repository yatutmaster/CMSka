<?php
$urlsite = '/';
$host = '';

      function getex($filename) {
             return end(explode(".", $filename));
      }
	  
	  
      if($_FILES['upload'])
       {
                if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) )
                {
                                   $message = "Вы не выбрали файл";
                 }
                 else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 9050000)
                {
                                   $message = "Размер файла не соответствует нормам";
                 }
                 else if (($_FILES['upload']["type"] != "image/jpg")
				     AND ($_FILES['upload']["type"] != "image/jpeg")
				     AND ($_FILES['upload']["type"] != "image/png")  
				     AND ($_FILES['upload']["type"] != "image/gif"))
                {
                                    $message = "Допускается загрузка только картинок JPG, JPEG и PNG.";
                }
                 else if (!is_uploaded_file($_FILES['upload']["tmp_name"]))
                {
                                    $message = "Что-то пошло не так. Попытайтесь загрузить файл ещё раз.";
                }
                else{
                                    $name =rand(1, 1000).'-'.md5($_FILES['upload']['name']).'.'.getex($_FILES['upload']['name']);
									
                                     move_uploaded_file($_FILES['upload']['tmp_name'], "_album/".$name);
									 
                                    $full_path = $host.$urlsite.'_album/'.$name;
									
                                    $message = "Файл ".$_FILES['upload']['name']." загружен";
									
                                    $size=@getimagesize('_album/'.$name);
									
                                     if($size[0]<50 OR $size[1]<50){
                                                         unlink('_album/'.$name);
														 
                                                         $message = "Файл не является допустимым изображением";
														 
                                                         $full_path="";
                                    }
                }
				
                $callback = $_REQUEST['CKEditorFuncNum'];
                echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$full_path.'", "'.$message.'" );</script>';
           }
		   
		   
?>