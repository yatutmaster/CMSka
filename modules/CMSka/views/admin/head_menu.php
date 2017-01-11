	  		  <ul id="head_menu" style="display:none">
                            <li><a class="prevent_def" href="#">Темы</a>
		 					 {%if themes[0]%}
							      <ul>
								  {%for theme in themes%}
                                       <li class=" ui-corner-all {%if theme == rtheme %} ui-state-active {%endif%} "  ><a  data-theme="{{theme}}" class="user_theme prevent_def" href="#">{{theme}}</a></li>
								  {%endfor%}
                                  </ul>
							 {%endif%}
							</li>
                            <li><a rel="{{urlsite}}admin/users" onclick="set.modal(this,'users')" class="prevent_def" href="#">Изменение учетки</a></li>
                       {% if rank < 2 %}
						   <li><a class="prevent_def" href="#">Пользователи</a>
                                 <ul>
                                       <li><a rel="{{urlsite}}admin/userscr" onclick="set.modal(this,'users')" class="prevent_def" href="#">Создать</a></li>
                                       <li><a class="prevent_def" href="#">Пользователи</a>
									    {%if users[0] %}
									              <ul>
												  {% for user in users %}
                                                   <li id="unit_{{user.id}}"  class="{%if user.active == 0 %} cms-shadow {%endif%}" ><a class=" prevent_def" href="#">{{user.name}}</a>
												                 <ul>
                                                                          <li id="unita_{{user.id}}"  class=" ui-corner-all {%if user.active == 0%} ui-state-active {%endif%}" ><a data-id="{{user.id}}"  class=" user_hide prevent_def" href="#">Скрыть</a></li>
                                                                          <li><a data-id="{{user.id}}" class=" user_delete prevent_def" href="#">Удалить</a></li>
                                      
                                                                </ul>
												   </li>
												   {%endfor%}
                                                  </ul>
										{%endif%}	  
							</li>
                             <li><a class="prevent_def" href="#">Администраторы</a>
									        {%if admins[0] %}
									              <ul>
												  {% for admin in admins %}
                                                   <li id="unit_{{admin.id}}" class="{%if admin.active == 0%} cms-shadow {%endif%}" ><a class="  prevent_def" href="#">{{admin.name}}</a>
												                <ul>
                                                                          <li  id="unita_{{admin.id}}"  class=" ui-corner-all {%if admin.active == 0%} ui-state-active {%endif%}" ><a data-id="{{admin.id}}" class=" user_hide prevent_def" href="#">Скрыть</a></li>
                                                                          <li><a data-id="{{admin.id}}" class=" user_delete prevent_def" href="#">Удалить</a></li>
                                      
                                                                </ul>
												   </li>
												   {%endfor%}
                                                  </ul>
										  {%endif%}	  
							</li>
                                  </ul>
                             </li>
                           {%endif%}  
                  </ul>
				
				 <div id="user-confirm" style="display:none" title="Удалить учетную запись?"></div>
				 <script>
				 
				 $(".user_theme").bind('click',function(){
				 
				     var th =  $(this).data('theme');
				    
					 var data = "theme="+th;
				 
				     $.post('{{urlsite}}admin/setth',data,function(){
					 
					      window.location = 'admin';
					 
					 });
				 
				 });
				 
				 
		 $(".user_delete").bind("click",function(){
				 
				var id = $(this).data('id');
				 var data = 'user='+id;
				  $( "#user-confirm" ).dialog({
                   resizable: false,
                   height:140,
				   width:280,
                   modal: true,
				   draggable:false,
                   buttons: {
                  "Да удалить": function() {
				   
				    loading = modal.show('#body',5,10);
					
				    $.post('{{urlsite}}admin/delete',data,function(){
					 
				        $('#toggle_menu').trigger("click");
					    $('#toggle_menu').trigger("click");
					 
					  loading.remove(); 

					 
					
					});
				   
					 $( this ).dialog( "close" );
					
                   },
                   "Нет": function() {
                            $( this ).dialog( "destroy" );
                   }
                  }
               });
				 
		 });
				 
				 

				 
				 
				 $(".user_hide").bind('click',function(){
				 
				      var id = $(this).data('id');
					  var data = "type=user&id="+id;
				 
				      if($("#unita_"+id).hasClass('ui-state-active'))
					  {
					      data = data+'&val=1';
					      $.post('{{urlsite}}admin/setvis',data,function(){
						       $("#unita_"+id).removeClass('ui-state-active');
						       $("#unit_"+id).removeClass('cms-shadow');
						       
						  });
					  
					  }else{
					  
					        data = data+'&val=0';
					      $.post('{{urlsite}}admin/setvis',data,function(){
						        
						       $("#unita_"+id).addClass('ui-state-active');
						       $("#unit_"+id).addClass('cms-shadow');
						  });
					  
					  
					  }
				 
				 
				 });
				 
				 $(".prevent_def").click(function(e){
				 
				    e.preventDefault();
					return false;
				 
				 });
				 
				$( "#head_menu" ).menu();
				 
				
				 </script>