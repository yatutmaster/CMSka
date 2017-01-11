  
<div id="modal_window_field" class="modal_window "  title="Изменение поля: ( {{data[0].name}} )">
 
   
 
<div id="modal_creat_field"  class="modal_create ">

 
 <form action="#"  >
 
     <label class="input_field_name modal_row" for="input_name_field">Имя поля<span>
	 <input id="input_name_field" value="{{data[0].name}}"  name="field_name" class="ui-widget-content" type="text"   placeholder="имя" required title="Имя поля" >
	 </span>
	 </label>
	 <input  name="field_name_h" value="{{data[0].id}}"  type="hidden" >

	 <input  name="type"  value="field"  type="hidden"  >


   
   
	<strong>Изменить значения:</strong>
	<div class="wrapp_t_cols">
	   	
      <div   class="dataTables_wrapper" >
	    <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"></div>
			
	  {%if data[0].table_data%}
		  <table class="display dataTable" cellpadding="0" cellspacing="0" border="0"  id="create_field">
							<thead>
								<tr>
									<th class="ui-state-default" >Имя таблицы</th>
									<th class="ui-state-default" >Поле таблицы</th>
									<th class="ui-state-default" >id узла (idpath)</th>
								</tr>
							</thead>
							
							<tbody style="text-align:center; color:#444444">
					
							<tr class="" >
							<td>
							<input type="text" value="{{data[0].table_data.table}}"  title="Имя таблицы, из которой брать данные" required  name="table" class="input_type ui-widget-content">
							</td>
							<td>
							<input type="text" value="{{data[0].table_data.field}}"  title="Имя поля в таблице" required   name="field" class="input_type ui-widget-content">
							</td>
							<td>
							<input type="text" value="{{data[0].table_data.idpath}}"  title="idpath-id раздела"   name="idpath" class="input_type ui-widget-content">
							</td>
							
							</tr>
						
							</tbody>
						</table>
	
	{%else%}
		  <table class="display dataTable" cellpadding="0" cellspacing="0" border="0"  id="create_field">
							<thead>
								<tr>
									<th class="ui-state-default" >Значения поля</th>
									<th class="ui-state-default" >Установки</th>
								</tr>
							</thead>
							
							<tbody style="text-align:center; color:#444444">
							<tr style="display:none"><td></td></tr>
							{% for key, field in data[0].value%}
							<tr class="{%if key is odd%}odd gradeC{%else%}even gradeC{%endif%}" id="tr_{{loop.index}}">
							<td>
							<input type="text" value="{{field}}"  title="Значение поля ввода" required  placeholder="Значение" name="values[]" class="input_type ui-widget-content">
							</td>
							<td style="width: 114px;" class="create_but_wrapp">
							<button id="sort_{{loop.index}}" class="button_sort" ></button>
							<button id="delete_{{loop.index}}" class="button_delete"></button>
							</td>
							</tr>
						   {%endfor%}
							</tbody>
						</table>
	
	{%endif%}
	 <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"></div>
	 
	</div>
	</div>  
  
      <div class="modal_buttons" >
	  {%if data[0].table_data%}
	  <input id="hidden" type="hidden" name="table_data"  value=1>
	  {%else%}
      <input id="hidden" type="hidden" name="sort" >
      <button id="add_field_field" rel="{{data[0].value|length }}" class="button">Добавить значение</button>
      <button class="button"  id="close_field" >Отмена</button>
	  {%endif%}
	  
	  <input class="button" id="submit_field"  name="submit" type="submit" value="Сохранить" >
	  
	  </div>
	
	 
	  
 </form>


      </div>
  </div>
  
  
  
  
  
  <script>
 
      $( ".button" ).button();
 
 
	  {%if data[0].table_data%}
	  
	  
	     $('form').submit(function (event){
			  			     
			       var data= $('form').serialize();
			       set.post('{{urlsite}}admin/modalch',data,4);
				    $('.modal_window').dialog( 'destroy' );

			  
			  event.preventDefault;
			  return false;
		});

	  
	  
	  
	  {%else%}
   $('form').submit(function (event){
			   
			
			       $('#hidden').val($( "#create_field tbody" ).sortable('serialize'));
			       var data= $('form').serialize();
			       set.post('{{urlsite}}admin/modalch',data,4);
				    $('.modal_window').dialog( 'destroy' );

			  
			  event.preventDefault;
			  return false;
			});

   
   				  
  $(".button_sort").bind('click', function(e){
                 
      
                  if(!$(".button_sort").hasClass("ui-state-error"))
	              {         $(".button_sort").addClass("ui-state-error");
                            $( "#create_field tbody").sortable( "enable" );
							$("#add_field_field").button('disable');
							$('tr').css('cursor','n-resize');
	              }else{
				            $(".button_sort").removeClass("ui-state-error");
	                        $(".button_sort").removeClass("ui-state-focus");
                            $( "#create_field tbody").sortable( "disable" );  
							$("#add_field_field").button('enable');
							$('tr').css('cursor','auto');
	              }
	                   e.preventDefault;
	                   return false;
            });
  
		    
	            $( ".button_sort" ).button({
                              icons: {primary: "ui-icon-arrowthick-2-n-s"}
                 });
	 
	            $( ".button_delete" ).button({
                              icons: {primary: "ui-icon-trash"}
                });	
		  <!--sortable-->
		  
		   
                $( "#create_field tbody" ).sortable({ 
                             placeholder: " ui-state-highlight",
                             cursor: "move",
                             cancel: ".button_cancel,.button_delete,input" });

                $( "#create_field tbody").sortable( "disable" );
  
   <!--tooltip-->
	                      	
 
	<!--dialog-->
			
   $("#close_field").bind('click',function(e){
				 $(this).unbind('click');
				 $('.modal_window').dialog( 'destroy' );
				 e.preventDefault;	
				 return false;
				 });
     
  

	
{% for k,item in data[0].value%}
        $("#delete_{{loop.index}}" ).click(function(){
			    
				$("#tr_{{loop.index}}").remove();
				return false;
			 
			 });
		
{%endfor%}	

		 	
			
 $("#add_field_field").bind('click',function(e){
 
                         var style = 'even gradeC';
						 
						 var id = $(this).attr('rel');
 
                          id = (id*1)+1;
						  
						  if(id%2== 0) style = 'odd gradeC';
						  
						$(this).attr('rel',id);
						
                              $(   '<tr id="tr_'+id+'" class="'+style+'">'+
									'<td>'+
									'<input class="input_type ui-widget-content" type="text" name="values[]" placeholder="Значение" value="" required title="Значение поля ввода">'+
									'</td>'+
									'<td style="width: 114px;" class="create_but_wrapp">'+
									'<button class="button_sort" id="sort_'+id+'"></button>'+
									'<button class="button_delete" id="delete_'+id+'"></button>'+
									'</td></tr>').insertAfter('table tbody tr:last');
									
              $("#sort_"+id).bind('click', function(e){
                 
      
                  if(!$(".button_sort").hasClass("ui-state-error"))
	              {         $(".button_sort").addClass("ui-state-error");
                            $( "#create_field tbody").sortable( "enable" );
							$("#add_field_field").button('disable');
					 
	              }else{
				            $(".button_sort").removeClass("ui-state-error");
	                        $(".button_sort").removeClass("ui-state-focus");
                            $( "#create_field tbody").sortable( "disable" );  
							$("#add_field_field").button('enable');
						 
	              }
	                   e.preventDefault;
	                   return false;
            });
  
  
             $("#delete_"+id ).click(function(){
			    
				$("#tr_"+id).remove();
			 
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
 

  

	  {%endif%}
	                      
	</script>		