<div id="modal_window_page" class="modal_window "  title="{%if val%}Изменение{%else%}Создание{%endif%} страницы">
 
     
 
<div id="modal_create_page"  class="modal_create">

 
	 
 
 <form action="#" method="post"  >
 
     <label class="input_page_name modal_row" for="input_name_page">Название раздела сайта (или страницы)<span>
	 <a  class="button chpu-btn">ЧПУ</a>
	 <input id="input_name_page" {%if val%}value="{{val.name}}"{%endif%} class="ui-widget-content page-input-field"  name="name" title="Название раздела" type="text" max=20 min=3 required placeholder="Название" >
	 </span></label>
	 
    
    
	 <input   name="parent_id" value="{{pid}}" type="hidden" >
    
	{%if val%} 
	<input   name="type" value="page" type="hidden" >
	<input    id="url_h"  name="url_h" value="{{val.furl}}" type="hidden" >
	{%endif%}
	 
	 <label class="input_page_url modal_row"  for="input_url_page">Адресс страницы<span>URL: {{urlsite}}{%if purl%}{{purl}}/{%endif%}
	 <input id="input_url_page" class="ui-widget-content page-input-field"  {%if val%}value="{{val.furl}}"  {%else%}rel="bad" onblur="set.check(this,'page','{%if purl%}{{purl}}/{%endif%}')" {%endif%} onkeyup="valid.set(this,/^[a-z0-9\-]+$/)" pattern="[a-z0-9\-]+"  name="url" type="text" title="адрес страницы example.ru/ваш_адрес " required placeholder="адрес" ></span> 
	 <strong class="warning_t" style="color:red;display:none">Этот адрес уже существует</strong>
	 
	 </label>
	 
	 <label class="input_page_name modal_row" for="input_title_page">Заголовок страницы (title-мета данные страницы)<span>
	 <textarea id="input_title_page" class="ui-widget-content "  name="title" title="имя страницы"  cols=41 rows=1  placeholder="Заголовок" >{%if val%}{{val.title}}{%endif%}</textarea>
	 </span></label>
   
	 <label class="input_page_desc modal_row" for="input_name_desc">Описание страницы (description-мета данные страницы)<span>
	 <textarea id="input_name_desc"  class="ui-widget-content "  name="desc" title="Описание страницы"  cols=41 rows=1 placeholder="Описание"  >{%if val%}{{val.pdesc}}{%endif%}</textarea>
	 </span></label>
	 <label class="input_page_keys modal_row" for="input_name_keys">Ключевые слова страницы (keys-мета данные страницы)<span>
	 <textarea id="input_name_keys" class="ui-widget-content "  name="keys" title="Ключевые слова страницы"  cols=41 rows=1 placeholder="Ключевые слова"  >{%if val%}{{val.pkeys}}{%endif%}</textarea>
	 </span></label>
	 
     
   
        
	 <label class="modal_select"  for="input_module_page">Выбрать модуль
	
	{%if modules%}	
	 <select id="input_module_page" class="ui-widget" style="width:300px"   name="module">
     <option />
	 {%for module in modules%}
	      
      
		   <option class="ui-widget" {%if module.uname == val.module%} selected {%endif%}value="{{module.uname}}">{{module.name}}&#x20({{module.uname}}){%if module.visible == 0%}&#x20!{%endif%}</option>
	
    {%endfor%}   
      </select> 
	  {%else%}
   <strong style="color:red">Не найдено ни одного модуля (Нужно создать модуль)</strong>
   {%endif%}
 <strong class="warning_s" style="color:red;display:none">Не выбран модуль</strong>
	  </label>
	

 <div class="set_page_params">
 <strong>Включить параметры в URL: {{urlsite}}{%if purl%}{{purl}}/{%endif%}{%if val%}{{val.furl}}{%else%}адрес{%endif%}/(params) <span>(Включать если требуется модулю)</span></strong>
	 
	  <label  for="check_params_page">Включить</label>
	  <input id="check_params_page" {%if val.params == 1%} checked {%endif%}  name="params"  type="checkbox" value="1"  title="Разрешить параметры">
     
 </div>
 
 {%if rank < 2%}
 
      <div class="rule_page">	  
	 <strong>Задать разрешения страницы на:</strong>
	 
	  <label for="check_delete_page">Удаление</label>
	  <input id="check_delete_page" {%if val.deletep == 1%} checked {%endif%}  name="delete"  type="checkbox" value="1"  title="Задать разрешение на удаление">
     
	 <label for="check_change_page">Изменение</label>
	  <input id="check_change_page" {%if val.changep == 1%} checked {%endif%}   name="change" type="checkbox" value="1"  title="Задать разрешение на изменение">
     
	 <label for="check_create_page">Создание</label>
	  <input id="check_create_page" {%if val.createp == 1%} checked {%endif%}  name="create" type="checkbox" value="1"  title="Задать разрешение на создание">
    
	 </div>
 
 {%elseif  val and rank > 1%}
  
	 {%if val.deletep == 1%}  <input checked  name="delete"  type="hidden" value="1"  />{%endif%} 
     

	{%if val.changep == 1%}  <input  checked   name="change" type="hidden" value="1" />{%endif%}
     

	 {%if val.createp == 1%}   <input checked   name="create" type="hidden" value="1"  />{%endif%} 
	  
  {%else%}	  
      <input  checked  name="delete"  type="hidden" value="1"  />
     

	  <input  checked  name="change" type="hidden" value="1" />
     

	  <input  checked  name="create" type="hidden" value="1"  />
 {%endif%}
 
 
	 
	 
	  
	  <button id="close_page" >Отмена</button>
	
	 <input id="check_submit_page"  name="submit" type="submit" value="Сохранить" >
	  
 </form>


      </div>
  </div>
       <script src="{{urlsite}}js/admin/select2/select2.min.js"></script>
	  <script src="{{urlsite}}js/admin/select2/select2_locale_ru.js"></script>
	  <script src="{{urlsite}}js/admin/string2url.js"></script>
<script>


  


   $('.chpu-btn').on("click",function(){
	   
	   $('#input_url_page').val(string2url($("#input_name_page").val()));
	   
	   
       valid.set($('#input_url_page'),/^[a-z0-9\-]+$/);
	   
	   set.check($('#input_url_page'),'page','{%if purl%}{{purl}}/{%endif%}');
   });




  $("#input_module_page ").select2({
        width:"copy",
		placeholder: "Выбрать",
	
		
  });
  
  
  
  $("#input_module_page").on("change", function(e) { 


       $(".warning_s").hide(); 
	   
  
  })
		   
  $("#close_page").bind('click',function(e){
				 
				$('.modal_window').dialog( 'destroy' );
				 
				 $(this).unbind('click');
				  e.preventDefault;
					return false;
	});
				 
{%if val%}

 $("form").submit(function (event){
			   
			   var flag = true;
			   
			       if("bad" === $('#input_url_page').attr('rel')) 
                       {			
					         $(".warning_s").hide();
				             flag = false;
							 $('#input_url_page').addClass("ui-state-error");
							
			                
					  }
		            
			   
			  if(flag){
			      var loading = modal.show('#body',5,10);
				  
				  
			       var data= $('form').serialize();
			       $.post('{{urlsite}}admin/modalch',data,function(data){
				     
					 loading.remove(); 
					   
				   var val = $('#input_name_page').val();
				   var url = $('#input_url_page').val();
				   
				   val = $.trim(val);
				   
				   var tileval = val;
				   
				   if(val.length > 22)
				   {
				      tileval=  val.substring(0,22);
					  tileval = tileval+'...';
				   }
				   
				   $("span#nodename_{{pid}}").text(tileval);
				   $("span#nodename_{{pid}}").attr('title','{{urlsite}}{%if purl%}{{purl}}/{%endif%}'+url);
				    
					$("#h3_name_{{pid}}").text(val);
					if($(".crumbs_{{pid}}").hasClass("ui-widget-header"))
					$(".crumbs_{{pid}}").text(val+" >>");
					else $(".crumbs_{{pid}}").text(val);
					$("#ancor_page_{{pid}}").text('{{urlsite}}{%if purl%}{{purl}}/{%endif%}'+url).attr('href','{{urlsite}}{%if purl%}{{purl}}/{%endif%}'+url);
					
				   
				    $('.modal_window').dialog( 'destroy' );
					
					 $('#nodename_{{pid}}').trigger('click');
			    
				   });
				    
			  }
			  
			  event.preventDefault();
			  return false;
			});

{%else%}
 $("form").submit(function (event){
			   
			   var flag = true;
			   
			       if ("bad" == $('#input_url_page').attr('rel')) 
                       {			
					         $(".warning_s").hide();
				             flag = false;
							 $('#input_url_page').addClass("ui-state-error");
							
			                
					   }
		             else if($('#input_module_page').val() == '')
			         { 
							flag = false;
							$(".warning_s").show();
				
			          }
			   
			  if(flag){
			       var  loading = modal.show('#body',5,10);
			       var data= $('form').serialize();
			       $.post('{{urlsite}}admin/addpath',data,function(data){
				   
				   if($('#icons_toggle_{{pid}}').is(':visible'))
				   {
		
				        if($('#icons_toggle_{{pid}}').hasClass('ui-state-error'))
				        {
						    $('#icons_toggle_{{pid}}').trigger('click');
						
						}
					  
				   
				   }
				   else
				   {
				     
				          $('#icons_toggle_{{pid}}').show();
				   
				   
                   }
				   
				    $('.modal_window').dialog( 'destroy' );
				   
				
				   
				    $('#icons_toggle_{{pid}}').trigger('click');
					
				    $('#nodename_{{pid}}').trigger('click');
					
					
			        loading.remove(); 
					if( {{pid}} == 1 )
					{
					     window.location = 'admin';
					
					
					}
					
				   });
				    
			  }
			  
			  event.preventDefault();
			  return false;
			});
{%endif%}

   $( "#check_hide_page" ).button({
         icons: {
         primary: "ui-icon-cancel"
         }
     });
	  
	  $( "#input_check_url_page" ).button({
         icons: {
         primary: "ui-icon-link"
         }
     });
   
	 
	
	  
	  $( "#check_create_page" ).button({
         icons: {
         primary: "ui-icon-plusthick"
         }
     });
	  
	  
	  $( "#check_delete_page" ).button({
         icons: {
         primary: "ui-icon-trash"
         }
     });
	 
	  $( "#check_change_page" ).button({
         icons: {
         primary: "ui-icon-wrench"
         }
     });
	 
	  $( ".button,#close_page,#check_submit_page,#check_params_page" ).button();
	 
{%if val%}
$("#input_url_page").blur(function(){

   if(!($('#url_h').val() == $('#input_url_page').val()))
             set.check(this,'page','{%if purl%}{{purl}}/{%endif%}');
   else $('.warning_t').hide();		 
});

{%endif%}

</script>