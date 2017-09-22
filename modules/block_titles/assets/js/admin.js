window.block_title = function(block){

	var $ = jQuery;

	var _ = {
		title: '',
		is_setting: false,
		elems: {
			block: block
		}
	};



	_.init = function(){

	

		// Hide
		_.collect_elements();

		// Set title
		_.title = _.elems.handle.clone().children().remove().end().text().trim();

		// Setup overlay
		_.init_block();


	}



	_.collect_elements = function(){
		
		_.elems.title_field = _.elems.block.find('[data-name$="block_title"] .acf-input input[type="text"]');
		_.elems.handle 		= _.elems.block.find('.acf-fc-layout-handle');

	}


	_.init_block = function(){

		_.elems.title_field.on('keyup', _.set_block_title);
		_.elems.handle.on('DOMSubtreeModified', function(){
			
			if(!_.is_setting)
				_.set_block_title()

		});

		_.set_block_title()

	}


	_.set_block_title = function(){

		_.is_setting 	= true;

		var count_span 	= _.elems.handle.find('span');
		var title 		= _.elems.title_field.val().trim();

		if(!title)
			title = _.title;
		else
			title = title+' ('+_.title+')';

		_.elems.handle.empty().append(count_span).append(' '+title)

		_.is_setting = false;

	}


	_.init();


	return _;

}


acf.add_action('append', function($el){

	if(!$el.is('[data-layout^="acfcb_block_"]'))
		return null;

	new block_title($el);

});


jQuery(document).ready(function($) {
	
	var blocks = $('[data-layout^="acfcb_block_"]').not('.acf-clone');
	blocks.each(function(k, block){
		var block = $(block);
		new block_title(block);
	})


});