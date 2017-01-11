


<div id="table_result" class="wrapp_table_{{page.id}}" rel="{{page.id}}" >
   
<div class="ui-widget-header page_mess ">
<h3 id="h3_name_{{page.id}}" >{{page.name}}</h3> <p><strong>Перейти по ссылке : </strong><a id="ancor_page_{{page.id}}" class="ancor_page  " href="{{urlsite}}{{page.url}}">{{urlsite}}{{page.url}}</a></p>
</div>
   <div class="branch_page_p">
            <button rel="{{urlsite}}admin/tableres" data-val="1" title="Главная Админки"class="branch_p crumbs_1   ui-widget-header " onclick="path.result(this,event)">{{parents[0].name}} >></button>
			{%for p in parents%}
			  {%if not loop.first %} <button rel="{{urlsite}}admin/tableres" data-val="{{p.id}}" title="{{urlsite}}{{p.url}}"class="branch_p crumbs_{{p.id}} {%if p.id == page.id%} crumbs_{{page.id}}  ui-state-active{%else%}ui-widget-header{%endif%} " onclick="path.result(this,event)">{{p.name}} {%if p.id != page.id%}>>{%endif%}</button>{%endif%}
		    {%endfor%}
			
			</div>
  <div class="page_buttons">


  <div class="page_left_buttons">
 
<script>
 
    var dTables = [];
	   
	   
	   
	   
    $("#table_result").hide();////////////////////перед тем как инициализируется таблица, мы её скроем
	
	
 /////////////////////////////////////////////////инициализация кнопки (удалить запись в таблице)	
			 function delete_row (key,obj){

					var trid =  $(obj).parent().parent().parent(),
						id = $(obj).data('id'),
						tname = $(obj).data('tname'),
						idpath = $(obj).data('idpath');
		   
					var data = 'fieldid='+id+'&tname='+tname+'&pid='+idpath;
  
					$( "#dialog-confirm"+key ).dialog({
						resizable: false,
						height:140,
						width:280,
						modal: true,
						draggable:false,
						buttons: {
							"Да, удалить": function() {
				   
								loading = modal.show('#body',5,10);
								$.post('{{urlsite}}admin/delete',data,function(){
					 
										
					                    dTables[key].row(trid).remove().draw( false );
										
										
										loading.remove(); 
					
								});
				   
								$( this ).dialog( "close" );
					
							},
							"Нет": function() {
										$( this ).dialog( "destroy" );
							}
						}
					});

			}


 
 
 /////////////////////////////////////////////////инициализация кнопки (скрыть / показать запись в таблице)	
			function hide_show_row(key,obj,tname){
			  
			
			 
						if(!$(".f_drag"+key).hasClass("ui-state-error"))
						{
								var data =  "type=field&pid={{page.id}}&tname="+tname ,
									id = $(obj).data("id");
				   
									data = data+"&id="+id;
			  
									if($(obj).hasClass("ui-state-error"))
									{
												$(obj).removeClass("ui-state-error").attr("title","Скрыть");
				
												data = data+"&val=1";
												$.post('{{urlsite}}admin/setvis',data);
									}else{
			 
												$(obj).addClass("ui-state-error").attr("title","Показать");
												data = data+"&val=0";
												$.post('{{urlsite}}admin/setvis',data);
			 
									}
						} 
			  
			  
			  
			}

  
 /////////////////////////////////////////////////инициализация кнопки (сортировка записей в таблице в таблице)	
 
 
			  
			function sort_row(key,tname){
			  
						if($('.f_drag'+key).hasClass("ui-state-error"))
						{
		
								$('.f_drag'+key).removeClass("ui-state-error");
								$( "#recordsArray"+key ).sortable('disable');
								$('#recordsArray'+key+' tr td').css({'cursor':''});
					  
								var sort = $( "#recordsArray"+key ).sortable('serialize');
								sort = sort+"&name=fields&pageid={{page.id}}&tname="+tname;
										
								$.post('{{urlsite}}admin/sortel',sort);
			          
						}else{
			   
								$('.f_drag'+key).addClass("ui-state-error");
								$( "#recordsArray"+key ).sortable('enable');
								$('#recordsArray'+key+' tr td').css({'cursor':'move'});
			   
						}
			  
			}

  
    var  table = {
			 
			        note:function(obj,type){
						 
						var tname =  $(obj).data('tname'),/////////////////название таблицы
						    pid = $(obj).data('idpath'),///////////////id раздела (меню с лева)
						    tkey = $(obj).data('tkey'),///////////////индекс таблицы в dTables 
						    id = $(obj).data('id');////////id запси в таблице, если type = change
							
							
						// var er = dTables[tkey].row($('#f_sort_'+id)).data();	
				
								
						var data = "tname="+tname+"&type_h_cms="+type+"&pid="+pid+"&tkey="+tkey+"&id="+id;
						  
						$.ajax("{{urlsite}}admin/note", {
								type:"post",
								data:data,					      
								beforeSend: function(){ 
									loading = modal.show('#body',5,10);
							
								},        
								success: function(data){
							
									if(data == 'access-denied')
										window.location.href = location.protocol + "//" + location.hostname+location.pathname;
							 
									$("#modal_box").html(data);
						                            
								}, 
								complete:function(){
							
									loading.remove(); 
							 
									$( "#modal_window_note").dialog({
                                                 resizable: true,
												 minWidth:1100,
                                                 maxWidth:1800,
												 position: ['center',20],
                                                 modal: true,
                                                 draggable: true,
												 beforeClose: function( event, ui ) {
													$('.modal_window').dialog( 'destroy' );
												 }
									});
								
								},
								error: function(jqXHR, textStatus){ 
							
									alert('<span>' + textStatus + '</span><br />Код ответа сервера: ' + jqXHR.status); 
							
								}     
						});    
						   
						
					}
			 
			 
			 
			 
			 }
 





</script>
   </div>	
   

			
			
</div>
    <script src="{{urlsite}}js/admin/dataTables/jquery.dataTables.min.js"></script>
	
{% for key, table in tables%}
<div class="wrapp_table">
 	
	<div class="page_right_buttons">
            <h2>{{table.rtname.name}}</h2>
            <button data-tname="{{table.tname}}" title="Добавить новую запись в таблицу ( {{table.rtname.name}} )" data-idpath="{{page.id}}"  data-tkey="{{key}}" onclick="table.note(this,'add')" class="button4">Добавить запись</button>
           
	  
    </div>			
	
		  <table   width="100%" cellpadding="5" cellspacing="0" border="0" class="ui-widget table_result" id="example{{key}}">
							<thead >
								
								<tr>
									<th>№</th>
									{%for  th in table.thead%}
									           {%if 6 >=  loop.index %}
									
									              <th title="{{th.fname}}">{{th.name}}</th>
										  
										      {%endif%}
									{%endfor%}
									<th title="{{table.tname}}"  >Дополнительно</th>
								</tr>
								
							</thead>
							
							<tbody id="recordsArray{{key}}" style="color:#444444" >
							{%for tr in table.tbody%}	
							   
								<tr id="f_sort_{{tr.id}}" class="tr-{{key}}-{{tr.id}}" >
								<td style="">{{loop.index}}</td>
								{%for tkey, td in tr%}	
									
									  {%if 6 >=  loop.index %}
									  
									  
									  {%if table.thead[loop.index0].ftype == "autodatetime" %}<td >{{td}}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "input" %}<td  >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "date" %}<td  >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "select" %}<td  >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "multipleselect" %}
									  {% set items = td|split('|') %}
									  <td >
									   {%for item in items %}
									       {%if item|length%}
									          {{item}},<br>
										   {%endif%}
									   {%endfor%}
									  </td>
									  {%endif%}
									  {%if table.thead[loop.index0].ftype == "file" %}<td  > {%if td|length > 0%} <a href="/_files/{{td}}" target="_blank"> Открыть файл </a> {%endif%} </td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "radio" %}<td  >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "img" %}<td class="" ><img src="{{urlsite}}_album/prev{{td}}" class="inner_timg" ></td>{%endif%}
									  
									  {%if table.thead[loop.index0].ftype == "multipleimg" %}
									  <td class="" >
									  
									  {% set mltpl = td|split(' ') %}
									  {%for item in mltpl %}
									        {%if item|length > 1%}
									        <img src="{{urlsite}}_album/prev{{item}}" class="multiple-td" >
											{%endif%}
									  {%endfor%}
									  </td>
									  {%endif%}
									 
									  {%if table.thead[loop.index0].ftype == "html" %}<td  >HTML</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "text" %}<td class="" >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "textarea" %}<td class="" >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  {%if table.thead[loop.index0].ftype == "checkbox" %}<td >{{td[0:46]}}{%if td|length > 47%}...{%endif%}</td>{%endif%}
									  
									  {%if table.thead[loop.index0].ftype == "list" %}
									  {% set items = td|split('|') %}
									  <td >
									   {%for item in items %}
									       {%if item|length%}
									          {{item}},<br>
										   {%endif%}
									   {%endfor%}
									  </td>
									  {%endif%}
									  
									  
									  {%if table.thead[loop.index0].ftype == "color" %}<td  ><span class="inner_tclr"  style="background:{{td}}"></span></td>{%endif%}
									 	
									{%endif%}
									
								 {%endfor%}
									<td class="" >
                                             <ul class="table_icons">
														  <li class="ui-state-default f_change{{key}} ui-corner-all" data-tname="{{table.tname}}" data-idpath="{{page.id}}" onclick="table.note(this,'change')"  data-tkey="{{key}}" data-id="{{tr.id}}" title="Изменить">
														  <span class="ui-icon ui-icon-wrench"></span>
														  </li>
														   <li class="ui-state-default f_delete{{key}} ui-corner-all" onclick="delete_row({{key}},this)"   data-tname="{{table.tname}}" data-idpath="{{page.id}}" data-id="{{tr.id}}" title="Удалить">
														  <span class="ui-icon ui-icon-trash"></span>
														  </li>
														  <li class="ui-state-default hide_field{{key}} {%if tr.visible == 0%}ui-state-error{%endif%} ui-corner-all" onclick="hide_show_row({{key}},this,'{{table.tname}}')" data-id="{{tr.id}}" {%if tr.visible == 0%} title="Показать"{%else%} title="Скрыть"{%endif%}>
														  <span class="ui-icon ui-icon-cancel"></span>
														  </li>
														  <li class="ui-state-default f_drag{{key}} ui-corner-all" onclick="sort_row({{key}},'{{table.tname}}')"  title="Сортировать">
														  <span class="ui-icon dragtree ui-icon-arrowthick-2-n-s"></span>
														  </li>
														  </ul>
								   </td>
								
								</tr>
								
							
								
                          {%endfor%}
							</tbody>
						</table>
		  
</div>	
<div id="dialog-confirm{{key}}" style="display:none" title="Удалить запись?"></div>
<script>


 /////////////////////////////////////////////////инициализация таблицы
		        dTables[{{key}}] = $('#example{{key}}').DataTable( {
						"bJQueryUI": true,
						"sPaginationType": "full_numbers",
						"bAutoWidth": false,
						"iDisplayLength": 10,
						"aoColumnDefs": [
								{ "sWidth": "152px", "aTargets": [ -1] }
						] ,
						"aLengthMenu": [[10,20, 30, 50, -1], [10,20, 30, 50, "Все"]],
						"oLanguage": { "sUrl": "{{urlsite}}js/admin/dataTables/ru_dataTables.txt"},
						"fnPreDrawCallback":function(){
                       
                 
        
						},
						"fnDrawCallback":function(){
                         
                  
						},
						"fnInitComplete":function(){
				          
								$("#table_result").show();
		                  
                          
						}
				} );
  
	

 
			$( "#recordsArray{{key}}" ).sortable({ placeholder: " ui-state-highlight",cursor: "move" });
                        
						 
			$( "#recordsArray{{key}}" ).sortable('disable');
			  
			  
			  



</script>

{%endfor%}

<script>
       
 /////////////////////////////////////////////////установка стиля  кнопки Jquery
            $( ".wrapp_table .button4" ).button({
                       icons: {
                                primary: "ui-icon-plus"
                       }
            });
			  
			 
			  
			$( ".ancor_page ,.branch_p , .table_icons li" ).bind({
						   mouseover: function (){
						         $(this).addClass("ui-state-hover")},
						   mouseout: function (){
						         $(this).removeClass("ui-state-hover")}
			});
   
</script>
	 

	 
 </div>
	


