

function clear_fields(form)
{
	
	form.find('input[type="text"], textarea').each(function(){
		
		$(this).val('');
		$(this).removeClass("success");
		
	});
	
	
}


function valid_field (obj,pattern)
{
             
			 if($(obj).hasClass('disabled') === false)
			 {
				  var val = $(obj).val(),
				      patt = new RegExp(pattern);
		     
			      if(patt.test(val))
				  {
				     $(obj).removeClass("error");
					 $(obj).addClass("success");
					 $(obj).attr({'rel':'good'});
					 return true;
				  
				  }else{
				     $(obj).removeClass("success");
				     $(obj).addClass("error");
			         $(obj).attr({'rel':'bad'});
					 
				
				     return false;
				  }
				 
			 }

}



function check_fields (form,callBack_begin)/////////////////////// перед отправкой формы
{
	var valid = true;
	
	
	if(callBack_begin) 
	{
		valid = callBack_begin($(form));
	}
	
	
	
	$(form).find('input, textarea').each(function(){
									  									  
        if($(this).trigger('blur') && $(this).attr('rel') == 'bad' && $(this).hasClass('disabled') === false)
		{
										 
										 
										  $(this).removeClass("success");
				                          $(this).addClass("error");
										  
										  
										  if($(this).hasClass('scroll-it') && valid)
												scroll_to($(this));
					 
					                      valid = false; 
										
		}
									  
	});  

	

	return valid;
	
}

function match_pass(obj)
{

   

	            var wrapp = $(obj).parent().parent().parent();
	            var ver_pass = wrapp.find('.match-pass').eq(0).val(),
	                ver_pass1 = wrapp.find('.match-pass').eq(1).val();

	
					
		        if(ver_pass1 != ver_pass)
				{
					 wrapp.find('.error-pass').show(); 
					 $(obj).removeClass("success");
				     $(obj).addClass("error");
			         $(obj).attr({'rel':'bad'});

				}
				else
				{
					   
					 wrapp.find('.error-pass').hide();
					 wrapp.find('.match-pass').removeClass("error");
					 wrapp.find('.match-pass').addClass("success");
					 wrapp.find('.match-pass').attr({'rel':'good'});

				}	 

}




function init_valid_fields()
{
	
	
	
  $('input, textarea').each(function(){
	
	var pattern = $(this).data('pattern');
	
	
	if(pattern)
	{
		
	   $(this).attr('rel','bad');
	   
	
	   
	   $(this).blur(function(){
		   
		 	if(valid_field(this,pattern) && $(this).hasClass('match-pass'))
				   match_pass(this);
	   });
	   
	   $(this).bind('paste',function(){
		   
			if(valid_field(this,pattern) && $(this).hasClass('match-pass'))
				   match_pass(this);
	   });
	   
	   $(this).keyup(function(){
		   
		   	if(valid_field(this,pattern) && $(this).hasClass('match-pass'))
				   match_pass(this);
	   });
		
	}
	
	
  });

	
}



function generatePass(nb) {
		        var chars = 'azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN_-#@';
		        var pass = '';
		        for(i=0;i<nb;i++){
		            var wpos = Math.round(Math.random()*chars.length);
		            pass += chars.substring(wpos,wpos+1);
		        }
		        return pass;
}


var is_busy = true;


////////////////////Функция отправки формы с капчей
///////////////////form: $(form) 
///////////////////ajax: boolian false or true
///////////////////callBack: callBack function



function send_form(form,ajax,callBack_begin,callBack_end)
{
	
	
	/////////////////////////////создаем прелоадер если нет в форме
	if(!form.find('.qap-preloader').length) 
	{
	   	$('<div>',{class:'qap-preloader',style:'display:none'}).appendTo(form);
	}

	
	
	form.submit(function(event){
		
	
		 var ev = event;
		 var form = this;
		
		
		 
		 ///////////////////////////событие на проверку (челове или нет)
	     // if (ev.originalEvent !== undefined) {
					
				// is_busy = false;
					
		 // } 

		 if(is_busy) alert('необходимо расскоментировать событие на проверку (челове или нет) для is_busy! originalEvent' );
		 
		 
		 
		 
		 
		 
		 ev.preventDefault();
		 
		 if(!is_busy && check_fields(form,callBack_begin))
		 {
			 is_busy = true;
			 
			 var name_qap = generatePass(32),
			     val_qap = generatePass(7);
				 
				 
			 $(form).find('.qap-preloader').show();	 
				 
			 //////////////////////////////////////
				if($(form).find('.qap-field').length) 
				{
					 $(form).find('.qap-field').attr({
						 name:name_qap,
						 value:val_qap
					 });
				}
				else
				{
					$('<input>',{name:name_qap,value:val_qap,type:'hidden',class:'qap-field'}).appendTo($(form));
					
				}				
			////////////////////////////////////////////	

			
				 // Отправка ключа в php файл и сохраняем в сессии
				$.post('/_jqap',{
								action : 'qaptcha',
								qaptcha_key : name_qap
				},
				function(data) {///////////////////////////когда все хорошо отправляем форму
					if(!data.error)
					{
						
						 if(ajax)
						 {
							   var form_data = $(form).serialize(),
						           action = $(form).attr('action');
						  
						       $.post(action,form_data).always(function(){/////////////////////////когда форма успешго отправлена
								   
								   
								   
								    $(form).find('.qap-preloader').hide();	 
								   
								    clear_fields($(form));
								   
									
									if(callBack_end) callBack_end();
								    
							   });
									
							 
							 
						 }	 
						 else
						 {
							 form.submit();
							 
						 }	 
						
						
					}
					else
				    {
						alert('Произошла ошибка, перезагрузите страницу и попробуйте снова.');
						
					}		
				},'json');
				 
			 
		 }
		
	});
	
	
	
	
}


///////////////////////////////////////инициализация
$(function(){
	
	init_valid_fields();
	
	
	////////////////////////////кнопка (отправить)
	  $('.subm-form').click(function(event){
		
		         ///////////////////////////событие на проверку (челове или нет)
			    if (event.originalEvent !== undefined) {
					
					is_busy = false;
				    $(this).parent().find('input[type="submit"]').trigger('click');	  
					
				} 

		
	  });
				 
	
	
	
	
});


