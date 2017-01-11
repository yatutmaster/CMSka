
  <div class="head_module ui-widget-header" style="display:">
  
      <div class="header_result ui-widget">
	    <div class="mess_module ui-state-highlight ui-corner-all">
		    <div class="wrapp_add_button">
		   {%if rank < 2%}  <button id="add_module" rel="{{urlsite}}admin/modalm" onclick="set.modal(this,'module')"  class="add_button ">{%if info%}Добавить{%else%}Создать{%endif%} модуль</button>{%endif%}
		    </div>
		       <p><span  class="ui-icon ui-icon-info"></span>
		        Это место для создания файлов модулей</p>   
	      </div>
		  
      </div>
   

   {%for key,val in info%}
 
     <div class=" item_block">
	 <span class="nomber_item ui-state-highlight ui-corner-all">{{key+1}}</span>
	   <div {%if val.visible == 0%}style="opacity:0.5"{%endif%} class="ui-state-default ui-corner-all head_item">
	   {%if rank < 2%}
	   <button rel="{{urlsite}}admin/modalch"  onclick="set.change(this,'module','{{val.uname}}')" class="button1"></button>
		 <button rel="{{val.uname}}" class="button2"></button>
		 {%endif%}
	     <h3 class="ui-widget-header">{{val.name}}</h3>
		 {%if rank < 2%} <p><strong class="ui-state-focus">Файл:  </strong>{{val.uname}}.php</p> {%endif%}
		 <p><strong class="ui-state-focus">Используется в:  </strong>{%for key, path in val.paths%}</br>{{path.name}}&#x20({{path.url}}){%endfor%}</p>
	    {%if val.about%}<p><strong class="ui-state-focus">Описание:  </strong>{{val.about}}</p>{%endif%}
	     
		
	   </div>
	 </div>

     {%endfor%}
<div id="dialog-confirm" style="display:none" title="Удалить модуль?"></div>
</div>

<script>

$(function() {

$('.button2').bind('click',function(){

  var name = $(this).attr('rel');
  
   $( "#dialog-confirm" ).dialog({
                   resizable: false,
                   height:140,
				   width:280,
                   modal: true,
				   draggable:false,
                   buttons: {
                  "Да удалить": function() {
				     
					 var data = 'module='+name;
					
					 set.post('{{urlsite}}admin/delete',data,2);
                            $( this ).dialog( "close" );
                   },
                   "Нет": function() {
                            $( this ).dialog( "destroy" );
                   }
                  }
           });

});

      
	 $( ".add_button" ).button();
	 
	 $( ".head_item .button1" ).button({
         icons: {
         primary: "ui-icon-wrench"
         },
		 text: false
     });
	 $( ".head_item .button2" ).button({
         icons: {
         primary: "ui-icon-trash"
         },
		 text: false
     });
});
</script>