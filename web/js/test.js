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

});