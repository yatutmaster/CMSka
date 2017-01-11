
  <div class="head_module ui-widget-header" style="display:">
  
      <div class="header_result ui-widget">
	    <div class="mess_module ui-state-highlight ui-corner-all">
		    <div class="wrapp_add_button">
		
		    </div>
		       <p><span  class="ui-icon ui-icon-info"></span>
		        Это место для изменения полей </p>   
	      </div>
		  
      </div>
   

   {%for key,val in info%}
 
     <div class=" item_block">
	 <span class="nomber_item ui-state-highlight ui-corner-all">{{key+1}}</span>
	   <div class="ui-state-default ui-corner-all head_item">
	
	     <button rel="{{urlsite}}admin/modalch"  onclick="set.change(this,'field','{{val.id}}')" class="button1"></button>
	
	     <h3 class="ui-widget-header">{{val.name}}</h3>

		{%if val.table_data%}
		<p><strong class="ui-state-focus">Данные из таблицы: </strong> Таблица:{{val.table_data.table}}, Поле таблицы:{{val.table_data.field}}, {%if val.table_data.idpath%} idpath :{{val.table_data.idpath}}{%endif%} </p>
		{%else%}
		 <p><strong class="ui-state-focus">Значения: </strong>{%for k,item in val.values%}  {{item}} {%if val.values|length > loop.index %}, {%endif%}  {%endfor%}</p>
		{%endif%}
	     
		
	   </div>
	 </div>

     {%endfor%}

</div>



<script>

$(function() {
   
	 $( ".head_item .button1" ).button({
         icons: {
         primary: "ui-icon-wrench"
         },
		 text: false
     });

});
</script>