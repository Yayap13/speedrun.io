$( document ).ready(function() {

	$('#menu').click(function() {
		$(this).toggleClass('active');
		$('body').toggleClass('with-sidebar');
	});

	$('#site-cache').click(function() {
		$('body').removeClass('with-sidebar');
		$('#menu').toggleClass('active');
	});


	function showTable() {
		var hash = $(location).attr('hash').replace(/^#/, "");
		if (hash.length > 0) {
			// The table with the hash is displayed.
			$('.table').removeAttr('id');
			$('.table').hide();
			$('.tableTitle').hide();
			$('.tableTitle').next().hide();
			$('.table-' + hash).attr('id', 'table');
			$('.table-' + hash).show();
			$('.tableTitle-' + hash).css('display', 'inline-block');
			$('.tableTitle-' + hash).next().show();
			$('.tableLink-'+ hash).addClass('tableLink-selected');
		} else {
			// The first table is displayed.
			$('.table').hide();
			$('.tableTitle').hide();
			$('.tableTitle').next().hide();
			$('.table:first').attr('id', 'table');
			$('.table:first').show();
			$('.tableTitle:first').css('display', 'inline-block');
			$('.tableTitle:first').next().show();
			$('.tableLink:first').addClass('tableLink-selected');
		}

		//hide table is tbody is empty!
		$('tbody').each(function(){
			$(this).html($.trim($(this).html()))
		});
		$('tbody:empty').parents('table').hide();

		$(function(){
			$('#table').tablesorter(); 
		});
	}

	showTable();
	$('.tableLink').click(function() {
		$('.tableLink').removeClass('tableLink-selected');
		$( this ).addClass('tableLink-selected');
		$('.showMoreContent').hide();
		//Strange bug here but I need to wait the hash to update..
		setTimeout(function(){
  			showTable();
		}, 200);
	});


	$('.showMoreBtn').click(function() {
		$(this).next().slideToggle();
	});

	//List games live filter!
	$('#filterBox').keyup(function(){
   		var valThis = $(this).val().toLowerCase();
    	$('.navList>li>a:first-child').each(function(){
    		var text = $(this).text().toLowerCase();
        	(text.indexOf(valThis) == 0) ? $(this).parent().show() : $(this).parent().hide();            
		});
	});

	//flashbag notification auto hide
	$('#flashbag').delay(4000).slideUp();

	$('.follow').click(function( event ) {
		event.preventDefault();
		$(this).html('Working. . .');
		var thisEl = $(this);
		$.ajax({
			url: Routing.generate('_followingManager', { user: ""+thisEl.attr('user')+"" }),
			success: function(data) {
				if (data.validate == 'following') {
					thisEl.html('Unfollow');
				} else if (data.validate == 'unfollowed') {
					thisEl.html('Follow');
				}
			},
			error: function(data) {
				thisEl.html('Error');
			}
		})
	});

	$('.timeVerification').click(function( event ) {
		event.preventDefault();
		$(this).removeClass('fa-warning');
		$(this).addClass('fa-spinner fa-spin');
		var thisEl = $(this);
		$.ajax({
			url: Routing.generate('_verifyTime', { slug: ""+$(this).attr('game')+"", time: ""+$(this).attr('time')+"" }),
			success: function(data) {
				if (data.validate == true) {
					thisEl.removeClass('fa-spinner fa-spin');
					thisEl.addClass('fa-check');
					thisEl.css('color', 'green');
				} else if (data.validate == false) {
					thisEl.removeClass('fa-spinner fa-spin');
					thisEl.addClass('fa-warning');
				}
			},
			error: function(data) {
				thisEl.removeClass('fa-spinner fa-spin');
				thisEl.addClass('fa-warning');
			}
		})
	});

});