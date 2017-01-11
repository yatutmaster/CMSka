
  
<div id="modal_window_module" class="modal_window "  title="Изменение модуля">
 
 
<div id="modal_creat_module"  class="modal_create ">

 
 <form action="#" id="form_field" method="post"  >
 
     <label class="input_module_name modal_row" for="input_name_module">Имя модуля<span>
	       <input id="input_name_module" value="{{mod.name}}" name="name" class="ui-widget-content" type="text" max=15 min=3 required placeholder="имя" >
	 </span>
	 </label>
     <input name="name_h" value="{{mod.name}}" type="hidden" >
	   
	 
	 <label class="input_module_file modal_row"  for="input_url_module">Имя файла модуля<span>(application/classes/controller/{{mod.uname}}.php)</span> 
	 	 <input name="module_name_h" value="{{mod.uname}}" type="hidden" >
	     <input  name="type"  value="module"  type="hidden"  >
	 </label>
     
	 <label class="modal_row" for="check_hide_module">Разрешить добавлять модуль к странице
	 <div id="check_hide_module" class="radio_toggle">
        <input id="check_hide_module1" type="radio"  name="added" value="1" {%if mod.visible == 1%}checked="checked"{%endif%}><label for="check_hide_module1">Да</label>
        <input id="check_hide_module2" type="radio"  name="added" value="0" {%if mod.visible == 0%}checked="checked"{%endif%}><label for="check_hide_module2">Нет</label>
    </div>
    </label>
	   
	 <label class="modal_select"  for="input_module_table">Выбрать таблицу
	<div class="wrapp_select_table ui-widget">
	 {% if data%}
	 <select class="ui-widget-content ui-corner-all " id="input_module_table" style="width:300px" data-placeholder="Выбрать"  multiple  name="tables[]">
	 
        {% for  key in data%}
		<option class="ui-widget" value="{{key.uname}}" {%if key.sel == true%} selected="selected"{%endif%} >{{key.name|capitalize}}({{key.uname}})</option>
		
            {%endfor%}
		  </select> 
		  {%else%}
		  <strong style="color:red">Не найдено ни одной таблицы (Нужно создать таблицу)</strong>
		  {%endif%}
      
	  </div>
     <strong class="warning_s" style="color:red;display:none">Не выбрана таблица</strong>
	 
	  </label>
	
  
   
	 
	  <div class="modal_buttons" >
	      <button id="close_module" >Отмена</button>
	
	     <input id="check_submit_module"   name="submit" type="submit" value="Сохранить" >
	 
	  
	   <label class="textarea" for="info_module">
	      <textarea class="ui-widget-content ui-corner-all " rows=5 cols=50 id="info_module" name="about" maxlength=250 placeholder="Описание модуля ">{{mod.about}}</textarea>
	  </label>
	  
	   </div>
 </form>


      </div>
	  
  </div>

	   <script src="{{urlsite}}js/admin/select2/select2.min.js"></script>
	    <script src="{{urlsite}}js/admin/select2/select2_locale_ru.js"></script>
  <script>
  

  $("form").submit( function (event){
			   
			   var flag = true;
			   
			       if(!$("ul.select2-choices li").hasClass("select2-search-choice"))
			         { 
					   flag = false;
					   $(".warning_s").show();
					 
			          }
			   
			  if(flag){
			       var data= $('form').serialize();
			       set.post('{{urlsite}}admin/modalch',data,2);
				   $('.modal_window').dialog( 'destroy' );
			  }
			  
			  event.preventDefault();
			  return false;
			});
           
  
  
  

  
  
 $(function() {
 
           $("#input_module_table").select2({
                width:"copy",
		     maximumSelectionSize: 5
           });
  
         $("#input_module_table").on("change", function(e) {  $(".warning_s").hide(); })
  
 
 
		   
          $("#close_module").bind('click',function(e){s
				 $(this).unbind('click');
				  $("#modal_window_module").dialog( 'destroy' );
				  e.preventDefault;
				 return false;	
				 });
  
  

  
  $("#toggle_view_m,#check_hide_module" ).buttonset();
 
	 $("#close_module,#check_submit_module").button();
	 
	 });
  </script>