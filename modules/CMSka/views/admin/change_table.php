  
<div id="modal_window_table" class="modal_window "  title="Изменение таблицы: ( {{data.info.uname}} )">
 
   
 
<div id="modal_creat_table"  class="modal_create ">

 
 <form action="#"  >
 
     <label class="input_table_name modal_row" for="input_name_table">Имя тыблицы<span>
	 <input id="input_name_table" value="{{data.info.name}}"  name="table_name" class="ui-widget-content" type="text" max=15 min=2  placeholder="имя" required title="Имя таблицы от 3 до 15 символов" >
	 </span>
	 </label>
	 <input  name="table_name_h" value="{{data.info.name}}"  type="hidden" >

    
	 <input  name="real_table_name_h"  value="{{data.info.uname}}"  type="hidden"  >
	 <input  name="type"  value="table"  type="hidden"  >


   
   
	<strong>Изменить поля:</strong>
	<div class="wrapp_t_cols">
	   	
      <div   class="dataTables_wrapper" >
	    <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"></div>
			
	
		  <table class="display dataTable" cellpadding="0" cellspacing="0" border="0"  id="create_table">
							<thead>
								<tr>
									<th class="ui-state-default" >Название</th>
									<th class="ui-state-default" >Имя поля в таблице</th>
									<th class="ui-state-default" >Тип поля</th>
									<th class="ui-state-default" >Параметры</th>
									<th class="ui-state-default" >Установки</th>
								</tr>
							</thead>
							
							<tbody style="text-align:center; color:#444444">
							<tr style="display:none"><td></td><td></td><td></td><td></td><td></td></tr>
							{% for key, field in data.fields%}
							<tr class="{%if key is odd%}odd gradeC{%else%}even gradeC{%endif%}" id="tr_{{field.id}}">
							<td>
							<input type="text" value="{{field.fname}}"  title="название столбца у полльзователя" required="" max=15 min=2 placeholder="имя" name="name_{{field.id}}" class="input_type ui-widget-content">
							<input type="hidden" value="{{field.fname}}"  name="name_{{field.id}}_h" >
							</td><td>
							<strong class="strong_fname">{{field.rfname}}</strong>
							<input type="hidden" value="{{field.rfname}}" class="field_name " name="name_f_{{field.id}}_h"  >
							</td><td>
							
							{% if field.ftype == 'text' %}<strong class="strong_type">text</strong>{%endif%}
							{% if field.ftype == 'date' %}<strong class="strong_type">date</strong>{%endif%}
							{% if field.ftype == 'autodatetime' %}<strong class="strong_type">auto datetime</strong>{%endif%}
							{% if field.ftype == 'input' %}<strong class="strong_type">input</strong>{%endif%}
							{% if field.ftype == 'textarea' %}<strong class="strong_type">textarea</strong>{%endif%}
							{% if field.ftype == 'select' %}<strong class="strong_type">select</strong>{%endif%}
							{% if field.ftype == 'multipleselect' %}<strong class="strong_type">multiple select</strong>{%endif%}
							{% if field.ftype == 'checkbox' %}<strong class="strong_type">checkbox</strong>{%endif%}
							{% if field.ftype == 'radio' %}<strong class="strong_type">radio</strong>{%endif%}
							{% if field.ftype == 'img' %}<strong class="strong_type">img</strong>{%endif%}
							{% if field.ftype == 'multipleimg' %}<strong class="strong_type">multiple img</strong>{%endif%}
							{% if field.ftype == 'file' %}<strong class="strong_type">file</strong>{%endif%}
							{% if field.ftype == 'html' %}<strong class="strong_type">html</strong>{%endif%}
							{% if field.ftype == 'list' %}<strong class="strong_type">list</strong>{%endif%}
							{% if field.ftype == 'color' %}<strong class="strong_type">color</strong>{%endif%}
							
							<input type="hidden" value="{{field.ftype}}" name="type_{{field.id}}_h" >
							</td><td>
							<input style="min-width: 329px;" type="text" value="{{field.fparams}}" title="если тип select,checkbox или radio то значение value:value1|value2; и т.д. если img то size:200|300;  данные с другой таблицы при (select и т.д.) table:table_name; field:field_name; idpath:id или parent или self  (не обязательно);" name="params_{{field.id}}" class="ui-widget-content ui-corner-all input_type" >
							<input type="hidden" value="{{field.fparams}}" name="params_{{field.id}}_h" >
							</td><td style="width: 114px;" class="create_but_wrapp">
							<button id="sort_{{field.id}}" class="button_sort" ></button>
							<label for="hide_{{field.id}}" class="cancel_field" ></label>
							<input type="checkbox" id="hide_{{field.id}}"{%if field.visible == 0%} checked {%endif%}class="button_cancel" value="0" name="cancel_{{field.id}}">
							<input type="hidden" value="{{field.visible}}" name="cancel_{{field.id}}_h">
							<button id="delete_{{field.id}}" class="button_delete"></button>
							</td>
							</tr>
						   {%endfor%}
							</tbody>
						</table>
	
	
	 <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"></div>
	 
	</div>
	</div>  
  
     <div class="modal_buttons" >
      <input id="hidden" type="hidden" name="sort" >
      <button id="add_field_table" rel="{{data.info.max}}" class="button">Добавить поле</button>
           
	 
		   
	 
      <button class="button"  id="close_table" >Отмена</button>
	  <input class="button" id="submit_table"  name="submit" type="submit" value="Сохранить" >
	  
	   <label class="textarea" for="info_table">
	      <textarea class="ui-widget-content ui-corner-all "  rows=5  cols=50 id="info_table" name="about" maxlength=250 placeholder="Описание таблицы ">{{data.info.about}}</textarea>
		  <input type="hidden" value="{{data.info.about}}" name="about_h">
	  </label>
	  </div>
	
	 
	  
 </form>


      </div>
  </div>
  
  
  
  
  
  <script>
 
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
			       set.post('{{urlsite}}admin/modalch',data,3);
				    $('.modal_window').dialog( 'destroy' );
			  }
			  
			  event.preventDefault;
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
	
{% for field in data.fields%}
        $("#delete_{{field.id}}" ).click(function(){
			    
				$("#tr_{{field.id}}").remove();
				return false;
			 
			 });
		
{%endfor%}	

		 	
			
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
									'<input class="input_type field_name ui-widget-content" onblur="table.f_match(this)" rel="bad" type="text" title="Имя может содержать латинские буквы и цыфры от 2 до 15 символов" max=15 min=2 name="name_f_'+id+'" pattern="[a-zA-Z0-9]{2,15}" onkeyup="valid.set(this,/^[a-zA-Z0-9]{2,15}$/)" placeholder="имя поля" title="имя поля в базе данных" required >'+
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
									'<input style="min-width: 329px;" class="ui-widget-content ui-corner-all input_type" type="text" name="params_'+id+'"  title="если тип select,checkbox или radio то значение value:value1|value2; и т.д. если img то size:200|300;  данные с другой таблицы при (select и т.д.) table:table_name; field:field_name; idpath:id  или parent или self   (не обязательно);" >'+
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