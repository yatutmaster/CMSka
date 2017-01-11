

(function ($) {
    $.fn.modalLoading = function (height, flag ,top,url) {
    	if (flag == 0) flag = 'Загрузка...';
        else flag = '<img src="'+url+'js/admin/modalLoading/'+flag+'.GIF">';
		
		
		modalLoading = $('<div style="position: absolute; border-radius:5px; display: block; z-index: 999990; text-align: center; font-size: 20px;"  class="ui-widget-overlay">'+
							'<div style="display: inline-block; position:relative; top:'+top+'%;">'+flag+'</div>'+
						'</div>');

    	modalLoading.attr('id', 'modalLoading');

		modalLoading.css('width', this.innerWidth());

		if (height && height > 0) height = height+'%';
		else height = this.innerHeight();
		modalLoading.css('height', height);

		offset = this.parent().offset();
		modalLoading.css('top', offset.top+'px');
		modalLoading.css('left', offset.left+'px');

    	this.append(modalLoading);

		return $(modalLoading);
    }
})(jQuery);