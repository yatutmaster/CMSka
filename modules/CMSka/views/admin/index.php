<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CMS</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--  Place favicon.ico and apple-touch-icon.png in the root directory 
       <link rel="stylesheet" href='{{urlsite}}css/normalize.css' >-->

			
        <link rel="stylesheet" href='{{urlsite}}js/admin/dataTables/demo_table_jui.css' >
        <link rel="stylesheet" href="{{urlsite}}css/admin/{{theme}}/jquery-ui.min.css">
		<link rel="stylesheet" href='{{urlsite}}js/admin/select2/select2.css' >

		
		
		
		<link rel="stylesheet" href='{{urlsite}}css/admin/admin.css' >

    
        <script src="{{urlsite}}js/admin/jquery-1.9.0.min.js"></script>
		<script>window.jQuery || document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"><\/script>')</script>
        <script src="{{urlsite}}js/admin/jquery-ui-1.10.2.custom.min.js"></script>
        <script src="{{urlsite}}js/admin/jquery-migrate-1.2.1.min.js"></script>
		<script src="{{urlsite}}js/admin/modalLoading/modalLoading.js"></script>


        <script src="{{urlsite}}js/admin/ckeditor/ckeditor.js" ></script>

       <script type="text/javascript">
$.widget( "ui.dialog", $.ui.dialog, {
 /*! jQuery UI - v1.10.2 - 2013-12-12
  *  http://bugs.jqueryui.com/ticket/9087#comment:27 - bugfix
  *  http://bugs.jqueryui.com/ticket/4727#comment:23 - bugfix
  *  allowInteraction fix to accommodate windowed editors
  */
  _allowInteraction: function( event ) {
    if ( this._super( event ) ) {
      return true;
    }

    // address interaction issues with general iframes with the dialog
    if ( event.target.ownerDocument != this.document[ 0 ] ) {
      return true;
    }

    // address interaction issues with dialog window
    if ( $( event.target ).closest( ".cke_dialog" ).length ) {
      return true;
    }

    // address interaction issues with iframe based drop downs in IE
    if ( $( event.target ).closest( ".cke" ).length ) {
      return true;
    }
  },
 /*! jQuery UI - v1.10.2 - 2013-10-28
  *  http://dev.ckeditor.com/ticket/10269 - bugfix
  *  moveToTop fix to accommodate windowed editors
  */
  _moveToTop: function ( event, silent ) {
    if ( !event || !this.options.modal ) {
      this._super( event, silent );
    }
  }
});

    var loading ;

    </script>
    

    </head>
    <body  oncontextmenu="return false;" >
      

	
     <header class="ui-widget ui-widget-content" >
	   <div id="header">
	    <div class="header_menu  ">
				<button id="toggle_menu" class="button">Меню</button>
				<div class="wr_head_menu">
				     {{head_menu|raw}}
				</div>	 
				</div>
		
   
	          <div class="wrapp_header_r">
			     <button onclick="log.out()" class="button" >Выход</button>
				 
			  </div>
	   </div>
	 </header>
	 
	 
	
<div id="body" class="ui-widget ui-widget-content">	
<div id="left_block" style="z-index: 1000; position:relative">
 
	<div class="wrapptree ui-widget ui-widget-content">
	<div class="tree_head">
     <ul class="toggle_menu ">
			<li class="ui-state-default  toggle_menu1 ui-corner-all " >
			<span class="ui-icon ui-icon-arrow-4"></span></li>
			<li class="ui-state-default toggle_menu2 ui-corner-all " >
			<span class="ui-icon ui-icon-extlink"></span></li>
		</ul>
	</div>
	
	<div class="tree_root">
     <ul class="root_menu">
			<li {%if rank < 2%} rel="{{urlsite}}admin/modalp"  onclick="set.change(this,'page',1)"  class="ui-state-default root_menu1 ui-corner-all " {%else%}  class="ui-state-default root_menu1 ui-state-disabled ui-corner-all "  {%endif%}title="Добавить страницу">
			<span class="ui-icon ui-icon-plusthick"></span></li>
			
		</ul>
	</div>
	
	{{nav|raw}}
		<div class="path_dialog " style="display:none;" title="Удалить страницу?"><div class="ui-state-content" style="color:red">Вы действительно хотите удалить страницу?</div></div> 
     </div>
</div>	
	

<div id="right_block" >

<div id="content_head">

 <div id="content_radio">
        <input type="radio"  id="content_radio1" name="radio" checked="checked"><label for="content_radio1">Главная</label>
        <input type="radio" rel="{{urlsite}}admin/headsett" onclick="set.ajax(this)" id="content_radio4" name="radio" ><label for="content_radio4">Настройки</label>
        <input type="radio" rel="{{urlsite}}admin/headmod" onclick="set.ajax(this)" id="content_radio2" name="radio" ><label for="content_radio2">Модули</label>
      {%if rank < 2%}  <input type="radio" rel="{{urlsite}}admin/headdb" onclick="set.ajax(this)" id="content_radio3" name="radio"><label for="content_radio3">Таблицы</label>{%endif%}
 </div>

</div>
<div id="content_page_block" style="display:none">{{content|raw }} </div>
<div id="content_block" class="content_wrapp">
    {{content|raw }} 
 </div>   


<div id="modal_box" style="display:none"></div>
 
  </div>
 
	   
	

        <script>
			
			
			
			
	var modal = {
		              show: function(element,unit,top){
					     var mod = $(element).modalLoading(0, unit,top,"{{urlsite}}"); 
					     return mod;
					  }
    } 
	
	var log = {
		   
		           out:function () {
				   
				      $.post('{{urlsite}}admin/logout',function(){
					  
					    window.location = 'admin';
					  
					  
					  });
				   
				   
				   }
		   
    }
	          
    var sort = {				
						 
					hover: function(znak) {       	        
   					                  
									  
								if(znak == 1){
						                  $( ".icons li,.icons_toggle li" ).unbind("mouseover");
						                  $( ".icons li,.icons_toggle li" ).unbind("mouseout");
						        } 
								if(znak == 0){
						                      $( ".icons li,.icons_toggle li" ).bind({
						                            mouseover: function (){
						                      $(this).addClass("ui-state-hover")},
						                            mouseout: function (){
						                      $(this).removeClass("ui-state-hover")}
											  });
						       }
					
					}	  
					
	}		

    var set = {
 
                 ajax:function (obj) {
				 
				           var  href = $(obj).attr('href');
				 
				            if(!href) href = $(obj).attr('rel');
			             
				            
				            
				            $.ajax(href, { 						
							beforeSend: function(){ 
							 $("#content_page_block").hide();
							 $("#content_block").show(); 
							},        
							success: function(data){
							
							if(data == 'access-denied')
							   window.location.href = location.protocol + "//" + location.hostname+location.pathname;
							   
							$(".content_wrapp").html(data);  
						
							}, 
							complete:function(){
							
								
							},
							error: function(jqXHR, textStatus){ 
							
							alert('<span>' + textStatus +            
							'</span><br />Код ответа сервера: ' + jqXHR.status); 
							
							}     
							});    
				 
				 
				 },
				 modal:function(obj,name){
				 
				            href = $(obj).attr('rel');
				 
				            $.ajax(href, { 						
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
							 
							  $( "#modal_window_"+name ).dialog({
                                                 resizable: false,
												 minWidth:1000,
                                                 maxWidth:1800,
                                                 modal: true,
                                                 draggable: false,
												 beforeClose: function( event, ui ) {
												   $('.modal_window').dialog( 'destroy' );
												 }
                               });
								
							},
							error: function(jqXHR, textStatus){ 
							
							alert('<span>' + textStatus +            
							'</span><br />Код ответа сервера: ' + jqXHR.status); 
							
							}     
							});    
				 
				 
				 },
				 post:function (url,data,id){
				
					 
                           $.ajax(url, {  
						    type:"POST",
							data:data,    
							beforeSend: function(){ 
							 loading = modal.show('#body',5,10);
							
							},        
							success: function(data){
							  
							if(data == 'access-denied')
							   window.location.href = location.protocol + "//" + location.hostname+location.pathname;
							
							  loading.remove(); 
				               
						                            
							}, 
							complete:function(){
							
							 
							        $("#content_radio"+id).trigger('click');
								
							},
							error: function(jqXHR, textStatus){ 
							
							alert('<span>' + textStatus +            
							'</span><br />Код ответа сервера: ' + jqXHR.status); 
							
							}     
							});    
				 
				 },
				 change:function(obj,name,uname){
				 
				            href = $(obj).attr('rel');
							
				 
				            $.ajax(href, {
                            type:"post",
                            data:	"name="+uname+"&type="+name,					   
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
							 
							  $( "#modal_window_"+name ).dialog({
                                                 resizable: false,
												 minWidth:1000,
                                                 maxWidth:1800,
                                                 modal: true,
                                                 draggable: false,
												 beforeClose: function( event, ui ) {
												   $('.modal_window').dialog( 'destroy' );
												 }
                               });
								
							},
							error: function(jqXHR, textStatus){ 
							
							alert('<span>' + textStatus +            
							'</span><br />Код ответа сервера: ' + jqXHR.status); 
							
							}     
							});    
				 
				 
				 },
				 check:function (obj,name,purl){
			
			         
			
			          if($(obj).attr('rel') == 'good')
			          {
			             var val =  $(obj).val();
						 
						 if(name === 'page') val = purl+val;
						 
			              $.post("{{urlsite}}admin/check", 
						           { 
								      val: val,
									  unit:name
								   },
						            function(data){   
										if(data == 'good'){
										    $('.warning_t').hide();
										}else if(data == 'bad'){
										    $('.warning_t').show();
											$(obj).addClass("ui-state-error");
			                                $(obj).attr({'rel':'bad'});
										}	
						           }); 
			          }
			}
				
 
 
 
 
 }
 
 
    var valid = {
 
               set:function (obj,pattern){
			   
			      var val = $(obj).val();
			      
			      if(pattern.test(val))
				  {
				     $(obj).removeClass("ui-state-error");
				     $(obj).addClass("ui-state-highlight");
					 $(obj).attr({'rel':'good'});
				  
				  }else{
				  
				     $(obj).addClass("ui-state-error");
			         $(obj).attr({'rel':'bad'});
				  
				  }
			   
			   }
 
 
    }
 
 
    var path = {
 
            result:function(obj,event){
			
			               // alert(event.button);
			            var id = $(obj).data('val');
								
					
								
							 ///////////////////////////////////////переключатель кнопочек раздела
					        
							     $('.wrapp-icons').hide();
							     $('#icons_'+id).show();
		
			                ////////////////////////////////////////////	
								
			  
			                loading = modal.show('#body',5,10);
			  
			  
							if(!$('#label_path_'+id).hasClass('ui-state-active'))
							{
								$('label.label_path').removeClass('ui-state-active');
								$('#label_path_'+id).addClass('ui-state-active');
					
							}		
			
			                var  href = $(obj).attr('href');
				 
				            if(!href) href = $(obj).attr('rel');
			           
				        
				            
				            $.ajax(href, { 
							type:"POST",
							data:"data="+id,     
							beforeSend: function(){ 
						
							
							},        
							success: function(data){
							
						    if(data == 'access-denied')
							   window.location.href = location.protocol + "//" + location.hostname+location.pathname;
							 
							$("#content_page_block").html(data);  
						      
							}, 
							complete:function(){
							 
							 $("#content_radio1").trigger('click');
							  loading.remove();
					
							},
							error: function(jqXHR, textStatus){ 
							
							alert('<span>' + textStatus +            
							'</span><br />Код ответа сервера: ' + jqXHR.status); 
							
							}     
							});    
			  
			 
		
				
						
						 
		
			
			},
			get_childs:function(obj,url){///////////////////обработчик раскрытия дерева в меню
			
			                   var id =  $(obj).attr('rel');
			                    
							   var tid = $('#table_result').attr('rel');
								
			                   if($('#wrapp_p_'+id).hasClass('ui-widget-header') && (!$(obj).hasClass("ui-widget-shadow")))
			                   {
										$('#treeblock_'+id).load(url,{id:id},function(data){
												
												if(data == 'access-denied')
															window.location.href = location.protocol + "//" + location.hostname+location.pathname;
												
												$('#wrapp_p_'+id).removeClass('ui-widget-header').addClass('ui-widget-content');
												$(obj).addClass("ui-state-error");
												$(obj).find('span').removeClass('ui-icon-plus').addClass("ui-icon-minus");
												if(tid) 
												{
												     $('#label_path_'+tid).addClass('ui-state-active');
												
												}
												$('#treeblock_'+id).show();
												
													
							 ///////////////////////////////////////переключатель кнопочек раздела
					        
							     $('.wrapp-icons').hide();
							     $('#icons_'+tid).show();
		
			                ////////////////////////////////////////////	
								
								  
										});
							      
								  
								  
							   
			                   }else{
							   
										$('#wrapp_p_'+id).addClass('ui-widget-header').removeClass('ui-widget-content');
							 
										$('#treeblock_'+id).hide();
								  
										$(obj).removeClass("ui-state-error");
										$(obj).find('span').removeClass("ui-icon-minus").addClass('ui-icon-plus');
							   
							   }
			
			},
			visible:function (obj,type,url){
			
			  var id = $(obj).attr('rel'),data;
			  
			  if($(obj).hasClass('ui-state-error'))
			  {
			     $('.visible_p_'+id).removeClass('ui-state-error');
				 $('#nodename_'+id).css('opacity','1.0');
			     data = 'id='+id+'&type='+type+'&val=1';
			     $.post(url+'admin/setvis',data);
			  
			  }else{
			  
			       $('.visible_p_'+id).addClass('ui-state-error');
				   $('#nodename_'+id).css('opacity','0.45');
				   
				  data = 'id='+id+'&type='+type+'&val=0';
			      $.post(url+'admin/setvis',data);
			  
			  }
			},
			  del: function(obj,pid,url){
                          
				var id = $(obj).attr('rel');
							
               $( ".path_dialog" ).dialog({
                        resizable: false,
				        width:280,
                        modal: true,
				        draggable:false,
                        buttons: {
                          "Да, удалить": function() {
				     
					        var data = 'path='+id;	
					
					       $.post(url+'admin/delete',data,function(data){
						   
						        
						   
							           if(data == 'access-denied')
							                    window.location.href = location.protocol + "//" + location.hostname+location.pathname;
												
                                       if(data == 'has-childs')
									          alert("Нельзя удалить раздел с подразделами, сначала удалите дочерние разделы!");
									   else {	  

									      $('#wrapp_p_'+id).remove();
						                  $(".wrapp_table_"+id).remove();
							
							 
							              if(!$("#sortable_"+pid+" li").hasClass('tree'))
							              {
					              
					                             $("#wrapp_p_"+pid).removeClass("ui-widget-content");
					                             $("#wrapp_p_"+pid).addClass("ui-widget-header");
					                             $("#icons_toggle_"+pid).html('<span class="ui-icon toggle_tree ui-icon-plus"></span>');
					                             $("#icons_toggle_"+pid).removeClass("ui-state-error").hide();
								                 $("#wrapp_sortable_"+pid).remove();
					                             $("#treeblock_"+pid).hide();
												 $("#nodename_"+pid).trigger('click');
							              }   
                                     
						              }
						     });
						   
						   
						   
						   
					         $( this ).dialog( "close" );
						   
						   
                            },
                           "Нет": function() {
                                 $( this ).dialog( "destroy" );
                            }
                         }
                 });

            }
 
 
    }
		
		
		
   $(document).ready(function(){
			
			 $('#toggle_menu').toggle(function(){
			 
			       $('.wr_head_menu').load('{{urlsite}}admin/headm',function(data){
				   
				     if(data == 'access-denied')
							   window.location.href = location.protocol + "//" + location.hostname+location.pathname;
				   
				     $( "#head_menu" ).toggle('blind', 200 ); 
				   
				   });
			    	
			       $('#toggle_menu').data('toggle',1);
			
			},function(){
			 
			       $( "#head_menu" ).toggle('blind', 200 );  
			       $('#toggle_menu').removeData('toggle');
		
			});
			
			$('#body,.wrapp_header_r,#left_block,.header_menu' ).bind('click',function(){
			 
			 if($('#toggle_menu').data('toggle') === 1)
			    {
				     $('#toggle_menu').trigger("click");
					  $('#toggle_menu').removeData('toggle');
					  
				}
			});
			
			
			$('#content_radio1').bind('click',function(){
			
			  $("#content_block").hide();
			  $("#content_page_block").show();
			  
			});
			
			
			
			
			
			$(".button").button();
			
			 
			
			
			
			$( "#left_block" ).draggable({
                         start: function( event, ui ) {
						     
							 $('#right_block').css({'width':'100%'});
							 $(this).css({'position':'absolute'});
					
							
						
						 },
						 zIndex: 1000
						
                    });
		   $( "#left_block" ).draggable( {cancel: ".tree_root,#sortable_0,.toggle_menu2"});
				
           $(".toggle_menu2").bind('click',function(){
		      
             if($('.tree_head').hasClass('open'))
			 {
			     $('.tree_root').show();
			     $('#sortable_0').show();
		         $('.ui-state-default.toggle_menu1.ui-corner-all').css( {'border-radius':'4px 0 0 0'});
		         $('.ui-state-default.toggle_menu2.ui-corner-all').css({'border-radius':'0 4px 0 0' });
				 $('.tree_head').removeClass('open');

			 
			 
			 }else{  
		         $('.tree_root').hide();
			     $('#sortable_0').hide();
			     $('.ui-state-default.toggle_menu1.ui-corner-all').css({'border-radius':'4px 0 0 4px'});
			     $('.ui-state-default.toggle_menu2.ui-corner-all').css({'border-radius':'0 4px 4px 0' });
				 $('.tree_head').addClass('open');
			
				  
			  }
		   });          
						
						
						
          $( "#content_radio" ).buttonset();
				  		
          $( "#button_close" ).button({
                           icons: {
                                  primary: "ui-icon-closethick"
                           }
             });
	    
						
						 
						 
						 
					 
             $( ".icons li,.icons_toggle li,.tree_head li,.tree_root li" ).bind({
						       mouseover: function (){
						         $(this).addClass("ui-state-hover")},
						       mouseout: function (){
						         $(this).removeClass("ui-state-hover")}
								 });
				
				
				
    });
			
			
			
			
			
			
			
			
	
 
 
 
  
 
 
 

 

		</script>

		 
     </div>		
    </body>
</html>
