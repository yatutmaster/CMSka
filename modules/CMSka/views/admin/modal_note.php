<div id="modal_window_note" class="modal_window "  title="{%if fields.fid%}Изменить {%else%} Добавить{%endif%} запись">
   
   <script src="{{urlsite}}js/admin/select2/select2.min.js"></script>
   <script src="{{urlsite}}js/admin/select2/select2_locale_ru.js"></script>
   <script >
   function get_name_browser(){
    // получаем данные userAgent
    var ua = navigator.userAgent;   
    // с помощью регулярок проверяем наличие текста,
    // соответствующие тому или иному браузеру
    if (ua.search(/Chrome/) > 0) return 'Google Chrome';
    if (ua.search(/Firefox/) > 0) return 'Firefox';
    if (ua.search(/Opera/) > 0) return 'Opera';
    if (ua.search(/Safari/) > 0) return 'Safari';
    if (ua.search(/MSIE/) > 0) return 'Internet Explorer';
    // условий может быть и больше.
    // сейчас сделаны проверки только
    // для популярных браузеров
    return 'Не определен';
}
 


   var multi = {
				  
				           
							 From:[],
							 To:[],
				             serialize:function(){
							  
							   if(multi.From.length > 0)
							   {
							       for(var i =0; i<multi.From.length;i++)
							         $(multi.To[i]).val($(multi.From[i]).sortable('serialize',{ expression: /(.+)[\?](.+)/ } ));
							   
							   
							   }
							     
			   
							  
							  }
				  
				  }
  </script >
<!--
-->

     <div class="wrapp_window_note">

		<form enctype="multipart/form-data" onsubmit="input.onsub(event)" accept-charset="utf-8" action="{{urlsite}}admin/note" method="post"  target="hiddenframe"  >
		
		{% if fields.fsort %}
		   <input type="hidden" name='after_row_cms'  value="{{fields.fsort}}" >
		{%else%}
		   <input type="hidden" name='after_row_cms'  value="0" >
		{%endif%}
		  
{% for key, field in fields.fields%}

  {% set p = field.params%}
  
	{% if field.ftype == 'text' %}  
       	
		<div class="modal_note ">

			       <span class="header_note ui-widget-header ">{{field.name}}</span>
				    <div class="note_body ">
			       <input class="ui-widget-content ui-corner-all " type="text" name='{{field.fname}}' {%if p.max%} max={{p.max}} {%endif%}  {%if p.min%} min="{{p.min}}" {%endif%} {%if p.placeholder %} placeholder="{{p.placeholder}}" {%endif%}  value="{{field.val}}" {%if p.required%} required {%endif%} {%if p.pattern%} pattern="{{p.pattern}}"  onkeyup="valid.set(this,/{{p.pattern}}/)" rel='bad' {%endif%}  title="{{p.title}}" >       
				</div>
			  </div>
    {% elseif field.ftype == 'input' %}  
       	
		<div class="modal_note ">

			       <span class="header_note ui-widget-header ">{{field.name}}</span>
				    <div class="note_body ">
			   	
			
		        <input class="ui-widget-content ui-corner-all " type="{{p.type}}" {%if p.type == 'range'%} onchange="$('#output{{key}}').val($(this).val())" {%endif%} id="input{{key}}" {%if p.step%} step={{p.step}} {%endif%} {%if p.max%} max={{p.max}} {%endif%}  {%if p.min%} min="{{p.min}}" {%endif%} name='{{field.fname}}' {% if field.val %}  value="{{field.val}}" {%else%}  value="{{p.value}}" {%endif%}   title="{{p.title}}" >       
				{% if p.type == 'range'%}   
				<output id="output{{key}}">{% if field.val %}{{field.val}}{%else%}{{p.value}}{%endif%}</output>
				{%endif%}
				</div>
			  </div>
    {% elseif field.ftype == 'list' %}  
       	
		<div class="modal_note ">

			       <span title="{{p.title}}" class="header_note ui-widget-header ">{{field.name}}</span>
				    <div class="note_body ">
			   		
	                <div class="wrapp_t_cols">
					
					
					{% set values = field.val|split('|') %} 
			
					
	   	
                    <div   class="dataTables_wrapper" >
					
	                <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"></div>
		            <table class="display dataTable" cellpadding="0" cellspacing="0" border="0"  id="create_field{{key}}">
						
							<tbody style="text-align:center; color:#444444">
							<tr style="display:none"><td></td></tr>
							{% for k, val in values%}
							  {% if val%}
							<tr class="{%if k is odd%}odd gradeC{%else%}even gradeC{%endif%}" id="tr_{{loop.index}}{{key}}">
							<td>
							<input type="text" value="{{val}}"  title="{{p.title}}" required  name="{{field.fname}}[]" class="input_type ui-widget-content">
							</td>
							<td style="width: 40px;" class="create_but_wrapp">
							<button id="delete_{{loop.index}}{{key}}" class="button_delete btn-del-list"></button>
							</td>
							</tr>
							 {%endif%}	
						    {%endfor%}
							</tbody>
					</table>
	               <div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"></div>
	 
	               </div>
	               </div>  
  
                   <div class="modal_buttons" >
                   <button id="add_field_field{{key}}" data-rows="{{values|length}}" class="button">Добавить значение</button>
	               </div>
	
	 
	               <script>
				   
				    {% for k,item in values%}
					
					     {% if item%} 
					
                              $("#delete_{{loop.index}}{{key}}" ).click(function(){////////////////////инициализация удаления 
			    
				                                $("#tr_{{loop.index}}{{key}}").remove();
				                                return false;
			                  });
							  
		            
				   
				              $( "#delete_{{loop.index}}{{key}}" ).button({
                                           icons: {primary: "ui-icon-trash"}
                              });	
							  
							  
						 {%endif%}	  
				   
                    {%endfor%}	
					
					 $( "#create_field{{key}} tbody" ).sortable({ 
                             placeholder: " ui-state-highlight",
                             cursor: "move"});
					
				   
				 
				     //////////////////////////////////////////////добавление удаление полей			
                   $("#add_field_field{{key}}").bind('click',function(e){
 
                                       var style = 'even gradeC';
						 
						               var id = $(this).attr('data-rows');
 
                                       id = (id*1)+1;
						  
						               if(id%2== 0) style = 'odd gradeC';
						  
						               $(this).attr('data-rows',id);
						
                                $('<tr id="tr_'+id+'{{key}}" class="'+style+'">'+
									        '<td>'+
									        '<input class="input_type ui-widget-content" type="text" name="{{field.fname}}[]"  value="" required title="{{p.title}}">'+
									        '</td>'+
									        '<td style="width: 40px;" class="create_but_wrapp">'+
									        '<button class="button_delete" id="delete_'+id+'{{key}}"></button>'+
									        '</td></tr>').insertAfter('#create_field{{key}} tbody tr:last');
								
  
                                $("#delete_"+id+"{{key}}").click(function(){
			    
				                              $("#tr_"+id+"{{key}}").remove();
			 
			                    });
  
		    
	                            $("#delete_"+id+"{{key}}").button({
                                                  icons: {primary: "ui-icon-trash"}
                                });	
									  
									 e.preventDefault;
                                     return false;
  
                    }); 
 

  

				   
	               </script>


				</div>
			  </div>
     {%elseif field.ftype == 'date' %}
			 <div class="modal_note ">

			  <span class="header_note ui-widget-header ">{{field.name}}</span>
			  <div class="note_body  note_date">
			       <input id="datepicker{{key}}" class="ui-widget-content ui-corner-all note_date" name='{{field.fname}}'  value="{{field.val}}"  {%if p.max%} max={{p.max}} {%endif%}  {%if p.min%} min="{{p.min}}" {%endif%}  {%if p.placeholder %} placeholder="{{p.placeholder}}"{%else%} placeholder="Введите дату" {%endif%}  title="{{p.title}}"  >
				</div>

				<script>
				$.datepicker.regional['ru'] = {
                              closeText: 'Закрыть',
                              prevText: '&#x3c;Пред',
                              nextText: 'След&#x3e;',
                              currentText: 'Сегодня',
                              monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                              'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                              monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                              'Июл','Авг','Сен','Окт','Ноя','Дек'],
                              dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
                              dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
                              dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                              dateFormat: 'yy-mm-dd',
                              firstDay: 1,
                              isRTL: false
                    };


				 $( "#datepicker{{key}}" ).datepicker( $.datepicker.regional[ "ru" ]);

				</script>

			  </div>
 {%elseif field.ftype == 'textarea' %}


			  <div class="modal_note ">

			  <span class="header_note ui-widget-header ">{{field.name}}</span>
			    <div class="note_body note_textarea">

			       <textarea class="ui-widget-content ui-corner-all " title="{{p.title}}"   name='{{field.fname}}'   {%if p.placeholder %} placeholder="{{p.placeholder}}"{%endif%}    rows="4" {%if p.min%}  min="{{p.min}}" {%endif%} {%if p.max%} max="{{p.max}}" {%endif%}>{{field.val}}</textarea>
				</div>
			  </div>
 {%elseif field.ftype == 'select' %}
			  <div class="modal_note ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body ">

                  <select id="input_note_page{{key}}" {%if p.required%} required {%endif%} name='{{field.fname}}'  class="note_select" style="width:100%;"   title="{{p.title}}" >  
							{%for key , val in p.value%}
							<option  value="{{val}}" {%if field.val %}{%if field.val == val%}  selected {%endif%}{%else%}{%if p.selected[0] == loop.index%} selected {%endif%}{%endif%}class="ui-widget" >{%if p.name[key]%}{{p.name[key]}}{%else%}{{val}}{%endif%}</option>
							{%endfor%}
                    </select>

			  </div>
                     
			  <script>
			        $("#input_note_page{{key}} ").select2({
                                                      width:"copy"
                    });


			  </script>
			  </div>
{%elseif field.ftype == 'multipleselect' %}
			  <div class="modal_note ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body ">

                  <select id="input_note_page{{key}}" {%if p.required%} required {%endif%} name='{{field.fname}}[]' multiple  data-placeholder="Выбрать" class="note_select" style="width:100%;"   title="{{p.title}}" >  
							
							{%if field.val %}
							{% set mltpl = field.val|split('|') %} 
							{%endif%}
							
							
							{%for key , val in p.value%}
							<option  value="{{val}}" {%if field.val %}{%if val in mltpl %}  selected {%endif%}{%endif%} class="ui-widget" >{{val}}</option>
							{%endfor%}
							
                  </select>

			  </div>
                     
			  <script>
			        $("#input_note_page{{key}} ").select2({
                                                      width:"copy"
                    });


			  </script>
			  </div>
 {%elseif field.ftype == 'checkbox' %}
			  <div class="modal_note ">

			    <span class="header_note  ui-widget-header ">{{field.name}}</span>
				<div class="note_body note_checkbox">
				
			        <input type="checkbox" name='{{field.fname}}' class="button"{%if field.val %}  checked="checked"{%else%}{%if p.checked%} checked="checked" {%endif%} {%endif%}id="{{field.fname}}{{key}}" value="{{p.value}}"  title="{{p.title}}" ><label for="{{field.fname}}{{key}}">{%if p.name%}{{p.name}}{%else%}{{p.value}}{%endif%}</label>
				
				</div>
			  </div>
 {%elseif field.ftype == 'radio' %}
			  <div class="modal_note ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body">
			          <div class="note_radio_b">
             {%for k, val in p.value%}
                            <input type="radio" name='{{field.fname}}' id="{{field.fname}}{{k}}" value="{{val}}" {%if field.val %} {%if field.val == val%} checked="checked" {%endif%}{%else%}{%if p.checked%}   {%if p.checked[0] == loop.index%} checked="checked" {%endif%} {%elseif loop.index == 1%} checked="checked" {%endif%}{%endif%}><label for="{{field.fname}}{{k}}">{%if p.name[k]%}{{p.name[k]}}{%else%}{{val}}{%endif%}</label>
             {%endfor%}         
                      </div>
				</div>
			

			  </div>
 {%elseif field.ftype == 'img' %}
	         <div class="modal_note ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body file">
            <div class="wrapp_img_b">
				<label for="note_img{{key}}" class="button">Выбрать изображение
			        <input type="file"  onchange="input.img(event,{{key}})" name='{{field.fname}}' id="note_img{{key}}" style='display:none;'   >
					</label>
				   <button onclick = "input.del(event,this,{{key}})" id="img_delete{{key}}" class="img_delete button" {%if field.val %} {%else%}style = "display:none"{%endif%} >Отмена</button>
				   </div>
				<fieldset  id="img_fieldset{{key}}">
				   {%if field.val %}
				     <img  src="{{urlsite}}_album/prev{{field.val}}" /> <p>{{field.nval}}</p> 
				   {%endif%}
					</fieldset>
					{%if field.val %}
					<input type="hidden" name='{{field.fname}}_img_cms'  value="{{field.val}}" >       
		            {%endif%}
					</div>


			  </div>
{%elseif field.ftype == 'multipleimg' %}
	         <div class="modal_note  ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body file">
            <div class="wrapp_img_mult">
				<label for="note_img{{key}}" class="button">Выбрать изображения
			        <input type="file" class="multiple{{key}}" multiple onchange="input.multipleimg(this,event,{{key}})" name='{{field.fname}}[]' id="note_img{{key}}" style='display:none;'   >
					</label>
				   </div>
    
                 <div id="list{{key}}"></div>
				 <input type="hidden" name='{{field.fname}}_ser' id="hidden{{key}}" value="" >    
                                 {%if field.val %}
								       {% set mltpl = field.val|split(' ') %}
									       {%for item in mltpl %}
										     {%if item|length > 1%}
												<input type="hidden" name='{{field.fname}}_img_cms[]'  value="{{item}}" > 
											 {%endif%}
                                           {%endfor%}
                                   {%endif%}
				 
				 
                          	<div   class="multiple-list-wrapper" >
	   
	
		                             <ul id="sortable{{key}}" class="sortable-multiple">
		                           {%if field.val %}
								       {% set mltpl = field.val|split(' ') %}
									       {%for item in mltpl %}
											   {%if item|length > 1%}
									           <li class="ui-state-default" id="li?{{item}}" >
										                <span>
														<img class="img-multiple-tag" src="{{urlsite}}_album/prev{{item}}"  class="img-multiple-tag" > 
														</span> 
														<button onclick="" class="multiple-del button_delete" ></button>
									           </li>
											   {%endif%}
                                           {%endfor%}
                                   {%endif%}
                                    </ul>
	
	
	
	 
	                       </div>				   
				 
				  
				    <script>
					 $(function() {
                             $( "#sortable{{key}}" ).sortable();
							 
							 multi.From.push('#sortable{{key}}');
							 multi.To.push('#hidden{{key}}');
						
                       
					         $( ".button_delete" ).button({
                                           icons: {primary: "ui-icon-trash"}
                             });	
							   
                          
					         $(".multiple-del").click(function(){
			    
				                             $(this).parent().remove();
			 
			                  });
							   
                      });
					 
					  
                   
							   
							   
							   
					  
				    </script>
				
				
					</div>


			  </div>
 {%elseif field.ftype == 'file' %}
			  <div class="modal_note ">


			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				  <div class="note_body file_block file">
				  <label for="note_file{{key}}" class="button">Выбрать файл
			        <input type="file"  onchange="input.file(event,{{key}})"  style="display:none;"  name='{{field.fname}}'  id="note_file{{key}}" >
					</label>
					 <button onclick = "input.delf(event,this,{{key}})" id="file_delete{{key}}" class="file_delete button" {%if field.val %} {%else%}style = "display:none"{%endif%} >Отмена</button>
				 </div>
				 <fieldset class="file_fieldset"   id="file_fieldset{{key}}">
				   {%if field.val %}
				      <p>{{field.val}}</p> 
				   {%endif%}
					</fieldset>
					{%if field.val %}
					<input type="hidden" name='{{field.fname}}_file_cms'  value="{{field.val}}" >   
                    <a class="button" id="file_link{{key}}" target="_blank" href="/_files/{{field.val}}">Посмотреть</a>				
		            {%endif%}
			  </div>

 {%elseif field.ftype == 'html' %}

		   <div class="modal_note_html ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				<div class="note_body">
				
		         <textarea name="{{field.fname}}" id="editor{{key}}" cols="45" rows="5"> {%if field.val %}{{field.val|raw}} {%endif%}</textarea>
    
				</div>
				
			     <script>
                   CKEDITOR.replace( 'editor{{key}}');
                </script>
				
						
			</div>
 {%elseif field.ftype == 'color' %}
			  <div class="modal_note ">

			    <span class="header_note ui-widget-header ">{{field.name}}</span>
				 <div class="note_body">
			      <span class="note_colorpicker cp{{key}}" > <input class="ui-widget-content ui-corner-all " name='{{field.fname}}'  value="{{field.val}}" class="note_cp"  type="text" id="note_colorpicker{{key}}" ></span>
				</div>
   
   
                   <link rel="stylesheet" href='{{urlsite}}js/admin/colorpicker/colorPicker.css' type="text/css" >
	               <script src="{{urlsite}}js/admin/colorpicker/jquery.colorPicker.min.js"></script>
	 
	                <script>
						   $('#note_colorpicker{{key}}').colorPicker(); 
	                </script>
                </div>
 {%endif%}				
				
				
{%endfor%}

         <input type="hidden" name='tname_h_cms'  value="{{fields.tname}}" >       
		 <input type="hidden" name='pid_h_cms'  value="{{pid}}" >  		 
		 <input type="hidden" name='tkey_h_cms'  value="{{tkey}}" >  		 
		 {%if fields.fid%}
		 <input type="hidden" name='fid_h_cms'  value="{{fields.fid}}" > 
		 {%endif%}     

	    <div class="note_form_buttons">
			  <button id="close_page" class="button" >Отмена</button>
			  {%if fields.fid%}
			  <input class="button" type="submit" value="Сохранить изменения" name="submit_change_cms" >
			      {%if rank%}
			   <input class="button" type="submit" value="Дублировать запись " name="submit_add_cms" >
			      {%endif%} 
			  {%else%} 
			  <input class="button" type="submit" value="Добавить запись" name="submit_add_cms" >
			  {%endif%} 
		</div>

		  
		
		
	    </form>

		<iframe id="hiddenframe" name="hiddenframe" style="display:none"></iframe>
	
		
	 </div>

				<script>

               

				   var input = {
				   
                              del:function(e,obj,key){
						
						      $("#img_fieldset"+key).empty();
						
						      $("#note_img"+key).val('');
						      $("#"+key+"_img_cms").val('');
							  
							  $(obj).hide();
							  
						      e.preventDefault();
						      return false;
						    },
							delf:function(e,obj,key){
						
							  
							  $("#file_fieldset"+key).empty();
						
						      $("#note_file"+key).val('');
						      
							  $("#file_link"+key).hide();
							  
							  $(obj).hide();
							  
						      e.preventDefault();
						      return false;
						    },
				             img:function(ev,key){
							 
                                     if (window.File && window.FileReader && window.FileList && window.Blob) {
 
                                            var f = ev.target.files;
                                             f = f[0];
											 if (!f.type.match('image.*')) {
                                                            $("#note_img"+key).val('');
                                                }else {
											  	   var reader = new FileReader();
												   reader.onload =  function(e) {
												                          $("#img_fieldset"+key).html('<img  src="'+ e.target.result+'" /> <p>"'+escape(f.name)+'"</p>');
	                                                                      }
                                                   reader.readAsDataURL(f);
												   $("#img_delete"+key).show();
                                                }
                                            } else {
											     $("#note_img"+key).show();
                                           }
  
                              }, 
							  multipleimg:function(obj,ev,key){
							 
							           var browser = get_name_browser();
									   
                                     if (window.File && window.FileReader && window.FileList && window.Blob) {
 
                                           


										  var files = ev.target.files;
											
									      if(browser == 'Safari' || browser == 'Google Chrome' || browser == 'Internet Explorer')
										         $("#sortable"+key).empty();
										   
					                      if(browser == 'Firefox' || browser == 'Opera' )
											$(obj).clone().attr('id','').insertAfter('#list'+key);
													
										
										   
											for (var i = 0, f; f = files[i]; i++)
											{
											   
											  
											
                                               if (!f.type.match('image.*')) {
                                                                 continue;
                                                  } 
											  	  
											

												   var reader = new FileReader();
												   
												  reader.onload = (function(theFile) {
                                                                                  return function(e) {
                                                                                                // Render thumbnail.
                                                                                          var li = document.createElement('li');
																						  li.setAttribute("class", "ui-state-default");
																						  li.setAttribute("id", "li?"+theFile.name);
                                                                                          li.innerHTML = ['<span><img class="img-multiple-tag" src="', e.target.result,
                                                                                                                                 '"  class="img-multiple-tag" > </span> <button class="multiple-del button_delete" ></button>'].join('');
                                                                                           
																						   document.getElementById("sortable"+key).insertBefore(li, null); 
																						 
																						      $( ".button_delete" ).button({
                                                                                                    icons: {primary: "ui-icon-trash"}
                                                                                              });	
																							
																							  $(".multiple-del").click(function(){
			    
				                                                                                               $(this).parent().remove();
			                                                                                  });
																							  
																							
							 
                                                                                  };
                                                                        })(f);
																	   
                                                   reader.readAsDataURL(f);
											
											}
											
										    
					                      if(browser == 'Firefox' || browser == 'Opera' )
										          $(obj).attr('value','');
										   
										          $( "#sortable"+key).sortable();
							   
										
                                        
                                        } else {
										
										           
										
										
											     $("#note_img"+key).show();
											   
                                        }
										
										
  
                              }, 
							  file:function(ev,key){
							 
                                       if (window.File && window.FileReader && window.FileList && window.Blob) 
									   {
 
                                            var f = ev.target.files;
                                             f = f[0];
											
											  	   var reader = new FileReader();
												   reader.onload =  function(e) {
												                          $("#file_fieldset"+key).html('<p><span>'+escape(f.name)+' </span>'+Math.round(f.size/1024)+': кб</p>');
	                                                                      }
                                                   reader.readAsDataURL(f);
												   $("#file_delete"+key).show();
												   
												   if(f.size > 20500000)
												   {
												      alert('Слишком большой файл!');
													  
													  $("#file_delete"+key).trigger("click");
													  
												   }  
                                                
                                        } else {
											     $("#note_file"+key).show();
                                        }
  
                              },
							  onsub:function(event){
							  
							  
							         var flag = true;
			   
			  
                                         $('.cke_button__save').trigger('click');
			   
			                             $('.wrapp_window_note input').each(function(){	
										 
				                                if("bad" == $(this).attr('rel'))
					                               { 
					                                     flag = false;
					                                     $(this).addClass("ui-state-error");
														 event.preventDefault(); 
														 return false;
					                               }	
				                                 });
			   
			                               if(flag){
			                                  
											   multi.serialize();
											  
				                         
											     loading = modal.show('#body',5,10);
								
				                              
											   $('.modal_window').dialog( 'destroy' );
											  return true;
				     
		            	                     }
			   
							  
							  
							  
							  },
							 
							 change:function(data){
							  
							        var row = dTables[{{tkey}}].row($('.tr-{{tkey}}-{{fields.fid}}')).data();
							       
								    for(var i=0; i < (data.length); i++)
									{
										 row[(i+1)] = data[i];
									}
								   
								
								   
								    dTables[{{tkey}}].row($('.tr-{{tkey}}-{{fields.fid}}')).data(row);
								 
								   
							        loading.remove(); 
									  
							
				                             
							  
							  },
							  add:function(data){
							  
							      
							 
							        var row = [];
									var id = data[(data.length-1)];
									
								    row.push(0);
									
									for(var i=0; i < (data.length-1); i++)
									{
										
										 row.push(data[i]);
									}
									
								    
								 
								    row.push('<ul class="table_icons">'
														  +'<li class="ui-state-default f_change{{tkey}} ui-corner-all" data-tname="{{fields.tname}}" data-idpath="{{pid}}" onclick="table.note(this,\'change\')"  data-tkey="{{tkey}}" data-id="'+id+'" title="Изменить">'
														  +'<span class="ui-icon ui-icon-wrench"></span>'
														  +'</li>'
														  +'<li class="ui-state-default f_delete{{tkey}} ui-corner-all" onclick="delete_row({{tkey}},this)"   data-tname="{{fields.tname}}" data-idpath="{{pid}}" data-id="'+id+'" title="Удалить">'
														  +'<span class="ui-icon ui-icon-trash"></span>'
														  +'</li>'
														  +'<li class="ui-state-default hide_field{{tkey}}  ui-corner-all" onclick="hide_show_row({{tkey}},this,\'{{fields.tname}}\')" data-id="'+id+'"  title="Скрыть">'
														  +'<span class="ui-icon ui-icon-cancel"></span>'
														  +'</li>'
														  +'<li class="ui-state-default f_drag{{tkey}} ui-corner-all" onclick="sort_row({{tkey}},\'{{fields.tname}}\')"  title="Сортировать">'
														  +'<span class="ui-icon dragtree ui-icon-arrowthick-2-n-s"></span>'
														  +'</li>'
														  +'</ul>');
								   

								    dTables[{{tkey}}].row.add(row).draw();
									
									
									$('#recordsArray{{tkey}} tr').each(function(){
										
										   var attr = $(this).attr('id');

                                          
                                            if (typeof attr !== typeof undefined && attr !== false) {
                                                 
                                            }
											else
											{
												 $(this).attr("id", 'f_sort_'+id).attr("class", 'tr-{{tkey}}-'+id);
												 
												 if($(this).next().hasClass('even'))
												 {
													 $(this).addClass('odd');
													 
												 }
												 else
												 {
													  $(this).addClass('even');
													 
												 }	 
												 
												 
												 $(this).find('.table_icons li').attr('data-id',id);
												 
											
											}	
										    
									});
									
									dTables[{{tkey}}].rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                                                          var data = this.data();
														  data[0]++;
														  this.data(data);
  
                                    } );
									
									
									sort_row({{tkey}},'{{fields.tname}}');
									sort_row({{tkey}},'{{fields.tname}}');
								   
								   
							        loading.remove(); 
									
									
									
							
				                             
				                             
							  
							  }

                  }
	
 $("#close_page").bind('click',function(e){
				 
				$('.modal_window').dialog( 'destroy' );
				 
				 $(this).unbind('click');
				  e.preventDefault;
					return false;
				 });

$( ".button" ).button();
$( ".note_radio_b" ).buttonset();
</script>
 </div>

