window.block_layout_handler = function(block){

	var _ = {};

	var $ = jQuery;

	_.elems = {
		block: block
	};


	_.init = function(){


		// Initial collection
		_.collect_elements();

		// Setup functions
		_.hide_original_layout_elements();
		_.setup_proxy_layout_elements();
		_.create_overlay();

		//_.create_offsets();

		// _.add_classes();
		// _.make_block_resizable();
		_.bind();


		// // Initial setup
		// if(!_.elems.block.hasClass('-collapsed')){

		// 	_.elems.handle
		// 	.click();

		// }

		// _.hide_cb_box(function(){});

		// _.elems.block.addClass('acfcb_arrange_init');


	}

	_.collect_elements = function(){

		_.elems.handle_input 	= _.elems.block.find('[data-name$="_block_handle"] .acf-input input[type="text"]');
		_.elems.width_inputs	= _.elems.block.find('[data-name$="_block_*_width"] .acf-input input[type="number"]');
		_.elems.proxy 			= $('<input class="proxy-width" type="number" val="100">');
		_.elems.handle 			= _.elems.block.find('.acf-fc-layout-handle');
		_.elems.acf_fields 		= _.elems.block.find('.acf-fields');
		_.elems.sortable 		= _.elems.acf_fields.find('.values')
		_.elems.overlay 		= _.elems.acf_fields.find('.acf-overlay')
		_.elems.block_extra		= $('<div class="block-extra"></div>').appendTo(_.elems.block)

	}



	_.hide_original_layout_elements = function(){

		_.elems.width_inputs.parents('.acf-field').eq(0).css('display', 'none');
		_.elems.handle_input.parents('.acf-field').eq(0).css('display', 'none');

	}


	_.setup_proxy_layout_elements = function(){

		_.elems.current_size = $('chosen_size')

		_.elems.proxy
		.val(_.elems.width_input.val())
		.attr({
			'min': _.elems.width_input.attr('min'),
			'max': _.elems.width_input.attr('max'),
			'step': _.elems.width_input.attr('step'),
		})
		.change(function(){
			_.elems.width_inputs.val(_.elems.proxy.val()).change()
		})


		_.elems.proxy_input = jQuery('<div class="proxy-wrapper acf-input"><div class="acf-input-append">columns</div><div class="acf-input-wrap"></div></div>')
	
		
		_.elems.proxy_input
		.find('.acf-input-wrap')
		.prepend(_.elems.proxy)

		_.elems.proxy_input
		.prependTo(_.elems.block);

	}


	_.create_overlay = function(){
		
		if(_.elems.overlay.length >= 1)
			return null;

		_.elems.overlay 	= jQuery('<div class="acf-overlay"></div>');
		
		_.elems.overlay
		.appendTo(_.elems.acf_fields)

	}


	_.create_offsets = function(){
		
		_.elems.offset_left = $('<div class="offset offset-left"></div>');
		_.elems.offset_right = $('<div class="offset offset-right"></div>');

		_.elems.block.prepend(_.elems.offset_left).append(_.elems.offset_right)

	}


	_.add_classes = function(){
		_.elems.acf_fields
		.addClass('float')
	}


	_.change_columns = function(cols){
		_.elems.width_input.val(cols).change()
	}


	_.make_block_resizable = function(){

		_.elems.resizer = $('<a class="resizer acf-icon -arrow-combo small"></a>')
		_.elems.block_extra.append(_.elems.resizer);

		var container 	= _.elems.block.parents('#acfcb-content-blocks').eq(0);
		var max_cols	= 12;

		_.elems.resizer.draggable({
			containment: _.elems.acf_fields.find('.values'),
			axis: 'x',
			start: function(e, ui){
				_.elems.sortable.sortable('disable')
			},
			drag: function(e, ui){

				var part_size 		= container.width()/max_cols;
				var left 			= ui.position.left;
				var cols 			= Math.ceil(left/part_size);

				if(cols < 1)
					cols = 1;
				else if (cols > max_cols)
					cols = max_cols;

				_.change_columns(cols)

			},
			stop: function(e, ui){
				_.elems.sortable.sortable('enable')
			}
		});

	}

	_.on_drag = function(elem, drag_callback, stop_callback){

		if(typeof drag_callback != 'function')
			drag_callback = function(){};

		if(typeof stop_callback != 'function')
			stop_callback = function(){};


		var dragged = false;
		var grabbed = false;

		elem
		.mousedown(function() {
		    
		    grabbed = true;

			$(window).mousemove(function() {

			    if(grabbed){
				    dragged = true;

				    drag_callback();

					$(window).one('mouseup', function(){
					
					    if(dragged) {
					        stop_callback();
					        grabbed = false
						    dragged = false;
					    }

					});

				}

			 })

		})

	}


s



	_.hide_cb_box = function(callback){

		if(typeof callback != 'function')
			callback = function(){};

		_.elems.overlay
		.animate({opacity: 0, width: '100%'}, 200, function(){
				//overlay.removeAttr('style')
		});

		_.elems.acf_fields
		.animate({'right': '-100%'}, 200, function(){
			//acf_fields.removeAttr('style');
			callback()
		});
	}


	_.init();


	return _;


};



// Init
acf.add_action('append', function($el){
	new block_layout_handler($el);
});

jQuery(document).ready(function($) {
	var blocks = $('.acf-field .layout');
	blocks.each(function(k, block){
		var block = $(block);
		new block_layout_handler(block);
	})
});