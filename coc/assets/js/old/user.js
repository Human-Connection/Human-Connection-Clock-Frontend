jQuery(document).ready(function($) {
	
	var currentUser,
		currentUserID,
		imgSize = 180,
		firstUserID = $('.coc-user:first-child').attr('id'),
		lastUserID;
		
	var keyAllowed = {};
	
	var closeOverlay =  function() {
		
		$('.coc-user-clone').velocity({ top: '40%' });
		$('.coc-overlay').fadeOut(function(){ $(this).remove(); });
		$(document).off('keyup');
		$('html').css({ 'overflow': '' });
		$('body').removeClass('modal-open');
		
	}	
	
	var initUsers = function() {
	
		$('.coc-user').off('click').on('click', function() {
			
			$('html').css({ 'overflow': 'visible' });
			
			$('body').addClass('modal-open').append('<div class="coc-overlay"><span class="coc-ajax-spinner"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span><div class="coc-close"><i class="fa fa-times" aria-hidden="true"></i></div><div class="coc-prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></div><div class="coc-next"><i class="fa fa-chevron-right" aria-hidden="true"></i></div></div>');
			
			$('.coc-overlay').off('click').on('click', function(e) { 
				
				if (e.target !== this)
				return;
				
				closeOverlay();
				
			});
			
			$('.coc-close').off('click').on('click', closeOverlay);
			
			$('.coc-prev').off('click').on('click', prevUser);
			
			$('.coc-next').off('click').on('click', nextUser);
			
			$(window).off('keyup').on('keyup', function(e) {
				
				if(e.which == 27) {
					closeOverlay();
    			}
    			
    			if(e.which === 39) {
					nextUser();
    			}
    			
    			if(e.which == 37) {
					prevUser();
    			}
			});
			
			var $this = $(this),
				pos   = $this.offset(),
				width = $this.width(),
				windowOffset = $(window).scrollTop()
				centerX = window.innerWidth / 2,
				centerY = window.innerHeight / 2,
				viewportOffsetTop = pos.top - $(window).scrollTop(),
				viewportOffsetLeft = pos.left - $(window).scrollLeft();				
			
			var disanceX, distanceY;
				
			distanceX = ( viewportOffsetLeft >= centerX ) ? ( viewportOffsetLeft - centerX + width ) : ( centerX - viewportOffsetLeft );			
			
			distanceY = ( viewportOffsetTop >= centerY ) ? ( viewportOffsetTop - centerY + width ) : ( centerY - viewportOffsetTop )
			
			var distance = Math.sqrt( distanceX * distanceX + distanceY * distanceY );						
			
			var speed = distance + 200;			
				
			currentUser = $this;
			currentUserID = $this.attr('id');		
			
			$this.clone()
				.appendTo('.coc-overlay').removeClass('coc-user')
				.addClass('coc-user-clone')				
				.find('img')
				.css({
		  			'width': width + 'px',
					'height': width + 'px'
	  			}).velocity({
		  			'width': imgSize,
					'height': imgSize,	  			
	  			}, {
		  			duration: speed,
		  			queue: false,  			
	  			})
	  			.parent()
				.css({ 
					'position': 'fixed', 
					'top': (pos.top - windowOffset), 
					'left': pos.left,
					'width': width + 'px',
					//'height': width + 'px'										
				})
				.velocity({ 
					'top': '50%', 
					'left': '50%',	
					'width': imgSize,
					//'height': imgSize,			
					translateX: '-50%',
					translateY: '-90px',
				}, { 
					queue: false,
					duration: speed,
					complete: function() {
						
						$(this).velocity({ 			
							translateY: '-180px',
						});
					
						if (firstUserID == currentUserID) { $('.coc-overlay').addClass('no-prev'); }
					
						if (lastUserID == currentUserID) { $('.coc-overlay').addClass('no-next'); }
					
						$('.coc-overlay').addClass('show-card');		
					
	  				}
				});  			
			
		});
	
	}	
	
	initUsers();
	
	var nextUser = function() {
		
		if (lastUserID !== currentUserID) {
			
			var $this = currentUser.next('.coc-user');		
			
			if ( $this.length ) {	
			
				$('.coc-user-clone#' + currentUserID )
					.velocity({ 
						'left': '40%',
						'opacity': 0
					}, { 
						duration: 500, 
						complete: function() {
					
							$(this).remove();
							
						}					
					});
				
				var pos   = $this.offset(),
					width = $this.width()
					windowOffset = $(window).scrollTop();
					
						
				currentUser = $this;
				currentUserID = $this.attr('id');
				
				$this.clone()
						.appendTo('.coc-overlay').removeClass('coc-user')
						.addClass('coc-user-clone')
						.css({ 
							'opacity': 0, 
							'position': 'fixed', 
							'top': '50%', 
							'left': '60%'
						})
						.velocity({ 
							'top': '50%',
							'left': '50%',
							'opacity': 1
						
						}, { 
							queue: false,
							duration: 500, 
							complete: function() {
							
								if (firstUserID !== currentUserID) { $('.coc-overlay').removeClass('no-prev'); }					
								if (lastUserID == currentUserID) { $('.coc-overlay').addClass('no-next'); }	
							}				
						});
						
			} else {
				
				loadUsers(true);
				
			}

		}
			
	}
	
	var prevUser = function() {
		
		if (firstUserID !== currentUserID) {
		
			var $this = currentUser.prev('.coc-user');
			
			if ( $this.length ) {		
					
				$('.coc-user-clone#' + currentUserID )
					.velocity({ 
						'left': '60%',
						'opacity': 0
					}, { 							
						duration: 500, 
						complete: function() {
				
							$(this).remove();
							
						}		
					});
			
				var pos  = $this.offset(),
					width = $this.width()
					windowOffset = $(window).scrollTop();
					
				currentUser = $this;
				currentUserID = $this.attr('id');
			
				$this.clone()
					.appendTo('.coc-overlay').removeClass('coc-user')
					.addClass('coc-user-clone').addClass('show-card')
					.css({ 
						'opacity': 0, 
						'position': 'fixed', 
						'top': '50%', 
						'left': '40%'
					})
					.velocity({
						'top': '50%',
						'left': '50%',
						'opacity': 1 
					}, { 	
						queue: false,						
						duration: 500, 
						complete: function() {
						
							if (firstUserID == currentUserID) { $('.coc-overlay').addClass('no-prev'); }					
							if (lastUserID !== currentUserID) { $('.coc-overlay').removeClass('no-next'); }
							
						}						
					});
				
			}
		
		}
		
	}	
		
	var cocUserPage = 1;
	
	var $loadmoreButton = $('.coc-users-loadmore .button-default');
	
	var cocUserPageTotal = $('.coc-users-wall').data('pages');
	
	$loadmoreButton.on('click', function() {
		
		loadUsers();
		
		return false;
		
	});
	
	var loadUsers = function(next) {
		
		$('body').addClass('coc-loading');
		
		cocUserPage++;
		
		var data = {
			action: 'coc_get_users',
            post_var: cocUserPage
		};

	 	$.post(coc_ajax.ajaxurl, data, function(response) {
			//console.log( response );
			
			$(response).appendTo('.coc-users-wall');
			
			var $newUsers = $('.coc-new-users:last-child');
			
			$newUsers.hide().css('opacity', 0).imagesLoaded(function() {
				
				$newUsers.velocity({ 'height': 'show', 'opacity': 1 }, 400, function(){
					
					$newUsers.find('.coc-user').unwrap();
					
					initUsers();
					
					$('body').removeClass('coc-loading');
			
					if ( next === true ) {
				
						nextUser();
				
					}		
					
					if ( cocUserPageTotal === cocUserPage ) {
						
						$loadmoreButton.fadeOut();
						
						lastUserID = $('.coc-user:last-child').attr('id');
						
					}
					
				});
				
			});		
			
	 	});
	 			
	}	
	
});