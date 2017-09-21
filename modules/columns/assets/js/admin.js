window.column_block = function(block){

	var $ = jQuery;


	var _ = {
		mode: null,
		data: {},
		elems: {
			block: block
		}
	};



	_.init = function(){

		_.set_mode('l');

		_.elems.block.addClass('column-block');

		// Hide
		_.collect_elements();

		
		// Setup overlay
		_.init_block();


	}



	_.collect_elements = function(){
		
		_.elems.data_field 		= _.elems.block.find('.block-cols-data .acf-input input[type="text"]');
		_.elems.parent 			= $('#acfcb-content-blocks');
		_.elems.handle 			= _.elems.block.find('.acf-fc-layout-handle');
		_.elems.cols_inputs 	= _.elems.block.find('.acf-field[data-name$="_cols"]');
		_.elems.offset_inputs 	= _.elems.block.find('.acf-field[data-name$="_offset"]');
		_.elems.acf_fields 		= _.elems.block.find('.acf-fields');
		_.elems.overlay 		= $('<div class="acf-cols-overlay"></div>').appendTo(_.elems.block);
		_.elems.block_extra		= $('<div class="block-extra"></div>').appendTo(_.elems.block)
		_.elems.sortable 		= _.elems.acf_fields.find('.values')
		_.elems.resizer			= $('<a class="resizer acf-icon -arrow-combo small"></a>').appendTo(_.elems.block_extra);
		_.elems.mode_select		= $('#block-cols-mode');

	}


	_.init_block = function(){

		// Read data from block
		_.fetch_block_data()


		// Click overlay closes edit screen
		_.elems.overlay.click(_.toggle)

		// Inital hiding of any open edit screens
		if(_.is_closed()) 
			_.toggle();


		_.init_resizeable_block();

		

		// Bind to changes for columns
		_.bind_cols_inputs();


		// Listen to mode switcher
		_.elems.mode_select.change(function(){
			_.set_mode(_.elems.mode_select.val());
		})
		.change();



		// Offsetting functionality
		_.setup_offsetting();

	}


	_.bind_cols_inputs = function(){

		_.elems.cols_inputs.each(function(k, el){

			var self 	= $(el);
			var mode 	= self.find('.acf-label label').text();
			var input 	= self.find('.acf-input input');

			input.change(function(){

				if(_.mode != mode)
					return;

				var cols		= _.data[mode].cols;
				var min_cols	= _.data[mode].min;
				var max_cols	= _.data[mode].max;

				var col_width	= 100/cols;
				var col_pad		= 5;

				var val 		= parseInt(input.val());
				var width 		= val * col_width;
				var calc_pad	= col_pad - 2;

				_.elems.block.css('width', 'calc('+width+'% - '+calc_pad+'px)');

			}).change();

		})

		
		_.elems.block.on('mode_switch', function(){
			_.elems.cols_inputs.find('.acf-input input').change();
			_.elems.offset_inputs.find('.acf-input input').change();
		})


	}


	_.setup_offsetting = function(){
		
		_.bind_offset_inputs();

	}


	_.bind_offset_inputs = function(){

		_.elems.offset_inputs.each(function(k, el){

			var self 	= $(el);
			var mode 	= self.find('.acf-label label').text();
			var input 	= self.find('.acf-input input');

			input.change(function(){

				if(_.mode != mode)
					return;

				var position 	= 'right';
				if(self.hasClass('left'))
					position = 'left';

				var cols		= _.data[mode].cols;
				var min_cols	= _.data[mode].min;
				var max_cols	= _.data[mode].max;

				var col_width	= 100/cols;
				var col_pad		= 5;

				var val 		= parseInt(input.val());

				var width 		= val * col_width;
				var calc_pad	= col_pad - 2;


				if(val <= 0){
					var attr = 0;
				}
				else{
					var attr = 'calc('+width+'% - '+calc_pad+'px)';
				}

				_.elems.block.css('margin-'+position, attr, 'important');
				_.elems.resizer.trigger('drag')

			}).change();

		})


		_.elems.block.on('mode_switch', function(){
			_.elems.cols_inputs.find('.acf-input input').change();
			_.elems.offset_inputs.find('.acf-input input').change();
		})


	}



	_.fetch_block_data = function(){

		_.data = JSON.parse(_.elems.data_field.val());

	}


	_.toggle = function(){
		return _.elems.handle.click();
	}


	_.is_closed = function(){
		return !_.elems.block.hasClass('-collapsed');
	}


	_.set_mode = function(mode){

		_.mode = mode;
		_.elems.block.trigger('mode_switch')

	}


	_.init_resizeable_block = function(){

		var container 	= _.elems.parent.eq(0);

		_.elems.resizer.draggable({
			containment: container,
			axis: 'x',
			start: function(e, ui){
				_.elems.sortable.sortable('disable');
			},
			drag: function(e, ui){

				var cols		= _.data[_.mode].cols;
				var min_cols	= _.data[_.mode].min;
				var max_cols	= _.data[_.mode].max;

				var part_size 		= container.width()/cols;
				var left 			= ui.position.left;
				var set_cols 		= Math.ceil(left/part_size);

				if(set_cols < min_cols)
					set_cols = min_cols;
				else if (set_cols > max_cols)
					set_cols = max_cols;

				_.set_cols(set_cols, _.mode)

			},
			stop: function(e, ui){
				_.elems.sortable.sortable('enable')
			}
		});

	}


	_.set_placeholder_width = function(){

		_.elems.parent.find('.ui-sortable-placeholder').width(_.elems.block.width());

	}


	_.set_cols = function(cols, mode){

		var input = _.elems.cols_inputs.filter('[data-name$="_block_'+mode+'_cols"]').find('.acf-input input[type="number"]')
		input.val(cols).change()

	}


	_.init();


	return _;


}


acf.add_action('append', function($el){

	if(!$el.is('[data-layout^="acfcb_block_"]'))
		return null;

	new column_block($el);

});


jQuery(document).ready(function($) {
	
	var blocks = $('[data-layout^="acfcb_block_"]').not('.acf-clone');
	blocks.each(function(k, block){
		var block = $(block);
		new column_block(block);
	})


});