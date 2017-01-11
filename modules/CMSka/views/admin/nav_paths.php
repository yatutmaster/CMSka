{%if data%}
<div id="wrapp_sortable_{{parent}}">
		<ul id="sortable_{{parent}}"  class="ui-sortable sortable ">
{%for tree in data%}		
				<li id="wrapp_p_{{tree.id}}"  class="ui-widget-header s_{{parent}} tree ui-corner-all">
			
						<ul id="icons_{{tree.id}}" style="display:none"  class="wrapp-icons icons">
								<li rel="{{tree.id}}" {%if (rank == 1) or ( tree.deletep == 1)%} onclick="path.del(this,{{parent}},'{{urlsite}}')" class="ui-state-default delete_path ui-corner-all " {%else%} class="ui-state-default ui-state-disabled delete_path ui-corner-all " {%endif%}title="Удалить сраницу">
										<span class="ui-icon ui-icon-trash"></span>
								</li>
								<li rel="{{urlsite}}admin/modalch" {%if (rank == 1) or ( tree.changep == 1) %}   onclick="set.change(this,'page',{{tree.id}})"  class="ui-state-default ui-corner-all" {%else%} class="ui-state-default ui-state-disabled ui-corner-all" {%endif%}title="Изменить страницу">
										<span class="ui-icon ui-icon-wrench"></span>
								</li>
								<li rel="{{urlsite}}admin/modalp" {%if (rank == 1) or ( tree.createp == 1) %}  onclick="set.change(this,'page',{{tree.id}})" class="ui-state-default ui-corner-all" {%else%} class="ui-state-default ui-state-disabled ui-corner-all" {%endif%}title="Добавить страницу">
										<span class="ui-icon ui-icon-plusthick"></span>
								</li>
								<li rel="{{tree.id}}" onclick="path.visible(this,'path','{{urlsite}}')"  class="ui-state-default {%if tree.visible == 0%}ui-state-error{%endif%} ui-corner-all visible_p_{{tree.id}}" title="Скрыть страницу">
										<span class="ui-icon ui-icon-cancel"></span>
								</li>
								<li  class="ui-state-default drag_{{parent}} ui-corner-all" title="Переместить страницу">
										<span class="ui-icon dragtree ui-icon-arrowthick-2-n-s"></span>
								</li>
						</ul>
			
						<ul class="icons_toggle" style="padding-left: 0px; float: right; margin: 0px 1px;">
							
								<li id="icons_toggle_{{tree.id}}" 	{%if tree.nest != 1%}  style="display:none"	{%endif%} onclick="path.get_childs(this,'{{urlsite}}admin/pchilds')" rel="{{tree.id}}" class="ui-state-default icons_toggle1 ui-corner-all" >
										<span class="ui-icon toggle_tree ui-icon-plus"></span>
								</li>
							
								
						</ul>
						<label id="label_path_{{tree.id}}" class="label_path" for="nodename_{{tree.id}}">
								<span id="nodename_{{tree.id}}"  rel="{{urlsite}}admin/tableres" data-val="{{tree.id}}"  onclick="path.result(this,event)" {%if tree.visible == 0%}style="opacity:0.45"{%endif%}  class="nodename " title="  {{tree.name}} - url: {{urlsite}}{{tree.url}} {%if rank < 2%} - id:({{tree.id}}) {%endif%} ">{{tree.name[0:42]}}{%if tree.name|length > 42%}...{%endif%}</span>
						</label>
			            <div id="treeblock_{{tree.id}}" style="display:none" class="treeblock "></div>
				</li>
			
  {%endfor%}          
   </ul>

<script>

                      


                         $( "#sortable_{{parent}}" ).sortable({ placeholder: " ui-state-highlight",cursor: "move" });
                         $( "#sortable_{{parent}}" ).disableSelection();
                         $( "#sortable_{{parent}}" ).sortable('disable');
						 
						 $('.drag_{{parent}}').bind('click',function(){
						 
								if(!$('.drag_{{parent}}').hasClass("ui-state-error"))
								{        
									   $('.drag_{{parent}}').addClass("ui-state-error");
                                       $("#sortable_{{parent}}" ).sortable( "enable" );
									   $('.s_{{parent}}').css({'cursor':'move'});
									   $('.nodename').removeAttr('onclick');
									   $('.nodename').css({'cursor':'move'});
							           
								}else{
				                      $('.drag_{{parent}}').removeClass("ui-state-error");
                                      $( "#sortable_{{parent}}").sortable( "disable" );  
									  $('.s_{{parent}}').css({'cursor':'pointer'});
									  var sort = $(  "#sortable_{{parent}}" ).sortable('serialize');
									        sort = sort+"&name=paths";
									   
									  $('.nodename').attr('onclick','path.result(this)');
									  $('.nodename').css({'cursor':'pointer'});
									   
									  $.post('{{urlsite}}admin/sortel',sort);
									
									 
								}
						 
						 });
						 
						  $( ".icons li,.icons_toggle li,.tree_head li,.tree_root li" ).bind({
						       mouseover: function (){
						         $(this).addClass("ui-state-hover")},
						       mouseout: function (){
						         $(this).removeClass("ui-state-hover")}
								 });
				
				
				
		      

</script>
 </div>
{%endif%}