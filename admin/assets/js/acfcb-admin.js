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
		_.add_classes();
		_.bind();


		// Initial setup
		if(!_.elems.block.hasClass('-collapsed')){

			_.elems.handle
			.click();

		}

		_.hide_cb_box(function(){});


	}

	_.collect_elements = function(){

		_.elems.handle_input 	= _.elems.block.find('[data-name$="_block_handle"] .acf-input input[type="text"]');
		_.elems.width_input		= _.elems.block.find('[data-name$="_block_width"] .acf-input input[type="number"]');
		_.elems.proxy 			= jQuery('<input class="proxy-width" type="number" val="100">');
		_.elems.handle 			= _.elems.block.find('.acf-fc-layout-handle');
		_.elems.acf_fields 		= _.elems.block.find('.acf-fields');
		_.elems.overlay 		= _.elems.acf_fields.find('.acf-overlay')

	}



	_.hide_original_layout_elements = function(){

		_.elems.width_input.parents('.acf-field').eq(0).css('display', 'none');
		_.elems.handle_input.parents('.acf-field').eq(0).css('display', 'none');

	}


	_.setup_proxy_layout_elements = function(){

		_.elems.proxy
		.val(_.elems.width_input.val())
		.attr({
			'min': _.elems.width_input.attr('min'),
			'max': _.elems.width_input.attr('max'),
			'step': _.elems.width_input.attr('step'),
		})
		.change(function(){
			_.elems.width_input.val(_.elems.proxy.val()).change()
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


	_.add_classes = function(){
		_.elems.acf_fields
		.addClass('float')
	}


	_.bind = function(){


		_.elems.overlay
		.click(function(){
			
			_.hide_cb_box(function(){
				_.elems.handle.click();
			});

		});


		_.elems.handle
		.click(function(){

			if(!_.elems.block.hasClass('-collapsed'))
				return null;

			_.elems.overlay
			.animate({opacity: 1, width: '30%'}, 200);

			_.elems.acf_fields
			.animate({'right': '0%'}, 200)

		})



		_.elems.acf_fields
		.on("keypress", function(e) {
		          /* ENTER PRESSED*/
		          if(!_.elems.block.hasClass('-collapsed') && e.keyCode == 13) {
		          	hide_cb_box(function(){
		          		_.elems.handle.click()
		          	});
		              e.stopPropagation();
		              e.preventDefault()
		          }
		      });



		_.elems.width_input
		.change(function(){

			var val 	= parseInt(_.elems.width_input.val());
			var width 	= val * (100/12);
			
			var offset  = 10;
			
			if(val > 10)
				offset = 15;

			if(val < 8)
				offset = 12;

			_.elems.block.css('width', 'calc('+width+'% - '+offset+'px)')
		})
		.change()

	}



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