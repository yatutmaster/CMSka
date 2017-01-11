<div id="modal_window_users" class="modal_window "  title="{%if create%} Создать {%else%} Изменить свою{%endif%} учетную запись ">
 
     
 
<div id="modal_create_users"  class="modal_create ui-widget">

 
	 
 
 <form  action="#" method="post"  >
 
     <label class="input_page_name modal_row" for="input_name1">Логин (текущий логин)<span>
	 <input id="input_name1"  class="ui-widget-content " rel="bad"  onkeyup="valid.set(this,/^[\w]{2,30}$/)" pattern="[\w]{2,30}"   name="name" title="Логин входа" type="text" max=15 min=2 required placeholder="логин" >
	 </span></label>
    
	 <input   name="user_id" value="{{uid}}" type="hidden" >
	 
	  
	 <label class="input_page_url modal_row"  for="input_pass3">Пароль (текущий пароль)<span>
	 <input id="input_pass3" class="ui-widget-content" rel="bad"  onkeyup="valid.set(this,/^[\w]{6,30}$/)" pattern="[\w]{6,30}"  name="pass" type="password" title="пароль латинские буквы или цыфры (от 6 до 20 символов)" max=15 min=6 required placeholder="пароль" ></span> 
	 </label>
	 
      <strong class="warning_n" style="color:red;display:none">Не верено введен логин или пароль</strong>
	
	  
    
	{%if create%}
	<strong>Создание пользователя</strong> 
	<fieldset class="set_user_gr" for="create_rank">Добавить в группу 
	 <div id="create_rank">
         <input type="radio" id="create_radio2" name="group" value="2" checked="checked"><label for="create_radio2">Пользователи</label>
         <input type="radio" id="create_radio3" name="group" value="1" ><label for="create_radio3">Администраторы</label>
     </div>
	 </fieldset>
	{%else%}
	<strong>Введите свой новый логин или пароль</strong> 
	{%endif%}
	 <div id="change_pass">
	 <label class="input_page_name modal_row" for="input_name2">Новый логин<span>  
	 <input id="input_name2" class="ui-widget-content " onblur="set.check(this,'login')" {%if create%} required rel="bad"{%endif%} onkeyup="valid.set(this,/^[\w]{2,30}$/)" pattern="[\w]{2,30}"   name="new-name" title="Логин входа" type="text" max=15 min=2  placeholder="Новый логин" >
	 </span>
	 <strong class="warning_t" style="color:red;display:none">Этот логин уже существует</strong>
	 </label>
    
	 
	 <label class="input_page_url modal_row"  for="input_pass1">Новый пароль<span>
	 <input id="input_pass1"  class="ui-widget-content" {%if create%} required rel="bad"{%endif%}  onkeyup="valid.set(this,/^[\w]{6,30}$/)" pattern="[\w]{6,30}"  name="new-pass" type="password" title="пароль латинские буквы или цыфры (от 6 до 20 символов)" max=15 min=6  placeholder="Новый пароль"  ></span> 
	 </label>
	 
    <label class="input_page_url modal_row"  for="input_pass2">Повторить новый пароль<span>
	 <input id="input_pass2"  class="ui-widget-content" {%if create%} required rel="bad"{%endif%}  onkeyup="valid.set(this,/^[\w]{6,30}$/)" pattern="[\w]{6,30}"  name="new-pass2" type="password" title="пароль латинские буквы или цыфры (от 6 до 20 символов)" max=15 min=6 placeholder="Повтор нового пароля"   ></span>  
	 </label>
	 
	 <strong class="warning_pass" style="color:red;display:none">Пароли не совпадают</strong>
	 </div>
	 
	
	  <button id="close_page" >Отмена</button>
	
	 <input id="check_submit_user"  name="submit" type="submit" value="Сохранить" >
	  
 </form>


      </div>
  </div>
      
<script>



  
  
  
  $("#input_pass1,#input_pass2").on("change", function(e) {  $(".warning_pass").hide(); })
  $("#input_name1,#input_pass3").on("change", function(e) {  $(".warning_n").hide(); })
		   
          $("#close_page").bind('click',function(e){
				 
				$('.modal_window').dialog( 'destroy' );
				 
				 $(this).unbind('click');
				  e.preventDefault;
					return false;
				 });

 $("form").submit(function (event){
			   
			   var flag = true;
			   
			    loading = modal.show('#body',5,10);
			   
			   $("#modal_create_users form input").each(function(){
			   
			         if("bad" == $(this).attr('rel'))
			          {
					       flag = false;
					       $(this).addClass("ui-state-error");
					  }
			   
			   });
			   
			    if(flag){
			   
			     if($("#input_pass1").val() !== $("#input_pass2").val())
			       {
				        flag = false;
						$(".warning_pass").show();
						$("#input_pass1").addClass("ui-state-error");
						$("#input_pass2").addClass("ui-state-error");
						
			       }
			   }
			   
			  if(flag){
			       
				 var name = $("#input_name1").val(),
				        pass = $('#input_pass3').val();
			  
			      var data = 'unit=user&name='+name+'&pass='+pass;
			  
			        $.post("{{urlsite}}admin/check",data,
						            function(data){   
										if(data === 'good'){
										
										      var data= $('form').serialize();
				   
				                               data = 'type=user&'+data;
				   
				                               $.post('{{urlsite}}admin/{%if create%}userscr{%else%}modalch{%endif%}',data,function(data){
				   
				                                                $('.modal_window').dialog( 'destroy' );
				                                                $('#toggle_menu').trigger("click");
					                                            $('#toggle_menu').trigger("click");
			       
				                                      });  
										
										
										    $('.warning_n').hide();
										}else if(data === 'bad'){
										
										
										
										    $('.warning_n').show();
											$("#input_name1").addClass("ui-state-error");
											$("#input_pass3").addClass("ui-state-error");
			                                $('#input_pass3').attr({'rel':'bad'});
										
											
											
										}	
						           }); 
			       
			  
			  }
			   
			 
			   
			
			  
			  loading.remove(); 
			  event.preventDefault();
			  return false;
			});


     $("#create_rank").buttonset();
	 
	  $( ".button,#close_page,#check_submit_user" ).button();
	 

             


</script>