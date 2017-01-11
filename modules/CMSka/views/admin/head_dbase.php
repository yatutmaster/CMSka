<div class="head_dbase ui-widget-header" >
  
  
      <div class="header_result  ui-widget">
	    <div class="mess_module ui-state-highlight ui-corner-all">
		<div class="wrapp_add_button"><button id="add_table" rel="{{urlsite}}admin/modalt" onclick="set.modal(this,'table')" class="add_button ">{%if info%}Добавить{%else%}Создать{%endif%} таблицу</button></div>
		       <p><span  class="ui-icon ui-icon-info"></span>
		       Здесь место для создания таблиц в базе данных.</p>   
	      </div>
		  
      </div>
   

   {%for nomb, key in info%}
     <div class=" item_block">
	
	 <span class="nomber_item ui-state-highlight ui-corner-all">{{nomb+1}}</span>
	   <div class="ui-state-default ui-corner-all head_item">
	   <button rel="{{urlsite}}admin/modalch"  onclick="set.change(this,'table','{{key.uname}}')" class="button1"></button>
		 <button rel="{{key.uname}}" class="button2"></button>
	     <h3 class="ui-widget-header">{{key.name}}</h3>
		 <p><strong class="ui-state-focus">Имя в базе:</strong> {{ key.uname}}</p>
		 <p><strong class="ui-state-focus">Используется в:</strong> {%for mod in key.mname%} ({{mod.mname}}) {%endfor%}</p>
		 {%if key.about%}<p><strong class="ui-state-focus">Описание:</strong> {{key.about}} </p>{%endif%}
	     
		
	   </div>
	 </div>
	 {%endfor%}

	<div id="dialog-confirm" style="display:none" title="Удалить таблицу?"></div>

</div>
<script>

$('.button2').bind('click',function(){

  var tname = $(this).attr('rel');
  
   $( "#dialog-confirm" ).dialog({
                   resizable: false,
                   height:140,
				   width:280,
                   modal: true,
				   draggable:false,
                   buttons: {
                  "Да удалить": function() {
				     
					 var data = 'table='+tname;
					 
						 set.post('{{urlsite}}admin/delete',data,3);
                            $( this ).dialog( "destroy" );
                   },
                   "Нет": function() {
                            $( this ).dialog( "destroy" );
                   }
                  }
           });

});

$(".add_button").button();

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
</script>