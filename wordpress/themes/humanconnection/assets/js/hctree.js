jQuery(document).ready(($) => {
	$.getJSON('/wp-content/themes/humanconnection/assets/js/hctree-text-de.json', (json) => {
		let jsonDoc;
		let description = $(".description");
		let textContainer = new Array();
		let titleContainer = new Array();
		let headerColors = new Array();
			headerColors['basics'] = 'brown';
			headerColors['soziales-netzwerk'] = 'green';
			headerColors['wissens-netzwerk'] = 'blue';
			headerColors['aktions-netzwerk'] = 'orange';
		jsonDoc = json;
		$.each(jsonDoc, (item) => {
	  		textContainer[item] = jsonDoc[item].text;
	  		titleContainer[item] = jsonDoc[item].headline;
		});
		$('.enabled').popover({
	  		container: '.hctree-wrapper',
	  		html: true,
	  		placement: 'auto',
	  		trigger: 'manual',
	  		title: function(){
	    		return titleContainer[$(this).data('text')] +
	           	'<button class="close" title="schließen">&times</button>';
	  		},
	  		content: function(){
	    		return textContainer[$(this).data('text')];
	  		}
		}).on('shown.bs.popover', function(e){
			let popover = $(e.target);
			let colorClass = popover.parent().parent().attr('id');

			$(document).find('.popover').addClass(headerColors[colorClass]);
			popover.addClass('active');
			
			$(document).find('.popover button.close').click(function() {
				$('.enabled').not(this).popover('hide');
				popover.removeClass('active');
	  		});
		}).on('hidden.bs.popover', function(){
	  		$(this).removeClass('active');
		});
		$('.enabled').click(function(){
			$('.enabled').not(this).popover('hide');
			$(this).popover('toggle');
		});
	});
});
