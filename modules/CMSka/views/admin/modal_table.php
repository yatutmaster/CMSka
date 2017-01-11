  
<div id="modal_window_table" class="modal_window "   title="Создание таблицы">
 
   
 
<div  class="modal_create ">

 	
	
 <form action="#" method="post"  >
 
     <label class="input_table_name modal_row" for="input_name_table">Имя тыблицы<span>
	 <input id="input_name_table"   name="table_name" class=" ui-widget-content" type="text" max=15 min=2  placeholder="имя" required title="Имя таблицы от 3 до 15 символов" >
	 </span>
	 </label>
    
	 

	 <label class="input_db_name modal_row"  for="input_db_name_t">Имя таблицы в базе<span>
	 <input id="input_db_name_t" rel="bad" pattern="[a-zA-Z0-9]{2,15}" onblur="set.check(this,'table')" onkeyup="valid.set(this,/^[a-z0-9]{2,15}$/)" title="Имя может содержать латинские буквы и цыфры от 2 до 15 символов" max=15 min=2 name="real_table_name" class=" ui-widget-content" type="text"  rel="bad"  required placeholder="имя таблицы" >
	 </span> 
	 <strong class="warning_t" style="color:red; display:none">Имя таблицы уже существует!</strong>
	 </label>
   
   
	<strong>Создать поля:</strong>
	<div class="wrapp_t_cols">
	   		
	<div   class="dataTables_wrapper" >
	<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"></div>
	
	
		  <table  class="display dataTable" cellpadding="0" cellspacing="0" border="0" id="create_table">
							<thead>
								<tr>
									<th class="ui-state-default" >Название</th>
									<th class="ui-state-default" >Имя поля в таблице</th>
									<th class="ui-state-default" >Тип поля</th>
									<th class="ui-state-default"  >Параметры</th>
									<th class="ui-state-default"  >Установки</th>
								</tr>
							</thead>
							
							<tbody style="text-align:center; color:#444444">
							<tr style="display:none"><td></td><td></td><td></td><td></td><td></td></tr>
							<tr class="even gradeC" id="tr_1">
							<td>
							<input type="text"  title="название столбца у полльзователя" required="" max=15 min=2 placeholder="имя" name="name_1" class="input_type ui-widget-content">
							</td><td>
							<input type="text" required="" placeholder="имя поля" onblur="table.f_match(this)" rel="bad" max=15 min=2 pattern="[a-zA-Z0-9]{2,15}" onkeyup="valid.set(this,/^[a-zA-Z0-9]{2,15}$/)" name="name_f_1" title="Имя может содержать латинские буквы и цыфры от 2 до 15 символов" class="input_type field_name ui-widget-content">
							</td><td>
							<select class="ui-widget-content ui-corner-all "  name="type_1" id="input_module_table">
							<option value="text" selected="selected">text</option>
							<option value="date">date</option>
							<option value="autodatetime">auto datetime</option>
							<option value="input">input</option>
							<option value="textarea">textarea</option>
							<option value="select">select</option>
							<option value="multipleselect">multiple select</option>
							<option value="checkbox">checkbox</option>
							<option value="radio">radio</option>
							<option value="img">img</option>
							<option value="multipleimg">multiple img</option>
							<option value="file">file</option>
							<option value="html">html</option>
							<option value="list">list</option>
							<option value="color">color</option>
							</select> 
							</td><td>
							<input style="min-width: 329px;" type="text" title="если тип select,checkbox или radio то значение value:value1|value2; и т.д. если img то size:200|300;  данные с другой таблицы при (select и т.д.) table:table_name; field:field_name; idpath:id или parent или self  (не обязательно);" name="params_1" class="ui-widget-content ui-corner-all input_type" >
							</td><td style="width: 114px;" class="create_but_wrapp">
							<button id="sort_1" class="button_sort" ></button>
							<label for="hide_1" class="cancel_field" ></label>
							<input type="checkbox" id="hide_1" class="button_cancel" value="0" name="cancel_1">
							<button id="delete_1" class="button_delete"></button>
							</td>
							</tr>
							</tbody>
						</table>
	
	
	    <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"></div>
	  </div>
	
	</div>  
  
    
	  <div class="modal_buttons" >
      <input id="hidden" type="hidden" name="sort">
	  <button id="add_field_table" rel="1" class="button">Добавить поле</button>
	  
	  
	
	  
      
		 <button class="button"  id="close_table" >Отмена</button>
	    <input class="button" id="submit_table"  name="submit" type="submit" value="Сохранить" >
		
		
		  <label class="textarea" for="info_table">
	      <textarea class="ui-widget-content ui-corner-all" rows=5 cols=50 id="info_table" name="about" maxlength=250 placeholder="Описание таблицы "></textarea>
	  </label>
	   </div>
	
	  
 </form>


      </div>
 
 
  <script >
 
  var table={
  
            
			f_match:function (obj){
			    
				
			   if($(obj).attr('rel') == 'good')
			   {
			     var val = $(obj).val();
				 var name = $(obj).attr('name');
			   
			     $('.field_name').each(function(){
			      	
				    if(name != $(this).attr('name'))
					{ 
					   
					     if(val == $(this).val()){
					     
						    alert('Одинаковых полей не должно быть!');
					       $(obj).addClass("ui-state-error");
			               $(obj).attr({'rel':'bad'});
					   }
					
					}
					
					
				 
				 });
			   
			   
			   }
			
			
			}
  }

            $('form').submit(function (event){
			   
			   var flag = true;
			   
			   $('.field_name').each(function(){	
				    if("bad" == $(this).attr('rel'))
					{ 
					   flag = false;
					}	
				 });
			   
			  if(flag){
			       $('#hidden').val($( "#create_table tbody" ).sortable('serialize'));
			       var data= $('form').serialize();
			       set.post('{{urlsite}}admin/headdb',data,3);
				       $('.modal_window').dialog( 'destroy' );
			  }
			  
			  event.preventDefault();
			  return false;
			});

   
   				  
            $(".button_sort").bind('click', function(e){
                 
      
                  if(!$(".button_sort").hasClass("ui-state-error"))
	              {         $(".button_sort").addClass("ui-state-error");
                            $( "#create_table tbody").sortable( "enable" );
							$("#add_field_table").button('disable');
							$('tr').css('cursor','n-resize');
	              }else{
				            $(".button_sort").removeClass("ui-state-error");
	                        $(".button_sort").removeClass("ui-state-focus");
                            $( "#create_table tbody").sortable( "disable" );  
							$("#add_field_table").button('enable');
							$('tr').css('cursor','auto');
	              }
	                   e.preventDefault;
	                   return false;
            });
  
		        $( ".button_cancel" ).button({
                              icons: {primary: "ui-icon-cancel"}
                 });
	            $( ".button_sort" ).button({
                              icons: {primary: "ui-icon-arrowthick-2-n-s"}
                 });
	 
	            $( ".button_delete" ).button({
                              icons: {primary: "ui-icon-trash"}
                });	
		  <!--sortable-->
		  
		   
                $( "#create_table tbody" ).sortable({ 
                             placeholder: " ui-state-highlight",
                             cursor: "move",
                             cancel: ".button_cancel,.button_delete,input" });

                $( "#create_table tbody").sortable( "disable" );
  
   <!--tooltip-->
	                      	
 
	<!--dialog-->
			
   $("#close_table").bind('click',function(e){
				 $(this).unbind('click');
				 $('.modal_window').dialog( 'destroy' );
				 e.preventDefault;
                 return false;				 
				 });
     
  
  $( ".button" ).button();
		
        $("#delete_1" ).click(function(){
			    
				$("#tr_1").remove();
				return false;
			 
			 });
		
	

			
			
 $("#add_field_table").bind('click',function(e){
 
                         var style = 'even gradeC';
						 
						 var id = $(this).attr('rel');
 
                          id = (id*1)+1;
						  
						  if(id%2== 0) style = 'odd gradeC';
						  
						$(this).attr('rel',id);
						
                              $(   '<tr id="tr_'+id+'" class="'+style+'">'+
									'<td>'+
									'<input class="input_type ui-widget-content" type="text" name="name_'+id+'" placeholder="имя" required title="название столбца у полльзователя">'+
									'</td>'+
									'<td>'+
									'<input class="input_type field_name ui-widget-content" rel="bad"  onblur="table.f_match(this)" type="text" title="Имя может содержать латинские буквы и цыфры от 2 до 15 символов" max=15 min=2 name="name_f_'+id+'" pattern="[a-zA-Z0-9]{2,15}" onkeyup="valid.set(this,/^[a-zA-Z0-9]{2,15}$/)" placeholder="имя поля" title="имя поля в базе данных" required >'+
									'</td>'+
									'<td>'+
									'<select class="ui-widget-content ui-corner-all " id="input_module_table" name="type_'+id+'">'+
                                            '<option selected="selected" value="text">text</option>'+
                                            '<option value="date">date</option>'+
                                            '<option value="autodatetime">auto datetime</option>'+
                                            '<option value="input">input</option>'+
										    '<option value="textarea">textarea</option>'+
                                            '<option value="select">select</option>'+
                                            '<option value="multipleselect">multiple select</option>'+
                                            '<option value="checkbox">checkbox</option>'+
                                            '<option value="radio">radio</option>'+
                                            '<option value="img">img</option>'+
                                            '<option value="multipleimg">multiple img</option>'+
                                            '<option value="file">file</option>'+
                                            '<option value="html">html</option>'+
                                            '<option value="list">list</option> '+        
                                            '<option value="color">color</option> '+        
                                   '</select> '+
								   '</td>'+
									'<td>'+
									'<input style="min-width: 329px;" class="ui-widget-content ui-corner-all input_type"  type="text" name="params_'+id+'"  title="если тип select,checkbox или radio то значение value:value1|value2; и т.д. если img то size:200|300;  данные с другой таблицы при (select и т.д.) table:table_name; field:field_name; idpath:id или parent или self  (не обязательно);" >'+
									'</td>'+
									'<td style="width: 114px;" class="create_but_wrapp">'+
									'<button class="button_sort" id="sort_'+id+'"></button>'+
									'<label class="cancel_field" for="hide_'+id+'"></label>'+
									'<input type="checkbox" name="cancel_'+id+'"  value="0" class="button_cancel" id="hide_'+id+'">'+
									'<button class="button_delete" id="delete_'+id+'"></button>'+
									'</td></tr>').insertAfter('table tbody tr:last');
									
                  $("#sort_"+id).bind('click', function(e){
                 
      
                  if(!$(".button_sort").hasClass("ui-state-error"))
	              {         $(".button_sort").addClass("ui-state-error");
                            $( "#create_table tbody").sortable( "enable" );
							$("#add_field_table").button('disable');
					 
	              }else{
				            $(".button_sort").removeClass("ui-state-error");
	                        $(".button_sort").removeClass("ui-state-focus");
                            $( "#create_table tbody").sortable( "disable" );  
							$("#add_field_table").button('enable');
						 
	              }
	                   e.preventDefault;
	                   return false;
            });
  
  
             $("#delete_"+id ).click(function(){
			    
				$("#tr_"+id).remove();
			 
			 });
  
		        $( '#hide_'+id ).button({
                              icons: {primary: "ui-icon-cancel"}
                 });
	            $( "#sort_"+id ).button({
                              icons: {primary: "ui-icon-arrowthick-2-n-s"}
                 });
	 
	            $("#delete_"+id ).button({
                              icons: {primary: "ui-icon-trash"}
                });	
									  
									 e.preventDefault;
                                     return false;
  
                  }); 
 	
	                      
	</script>	
 </div>	