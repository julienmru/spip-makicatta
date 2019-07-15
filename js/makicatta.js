$(function () {
//	makicattaSidebarHover()
	$('*[title]').tooltip({
		delay: 1500
	});
	makicattaSelect2();
});

/*$.fn.barre_outils = function(nom, settings) {
	var $textareas = $(this).not(':hidden'), source = {}, needs_conversion;
	$textareas.each(function() {
		if ($(this).val() && ($(this).val().indexOf('<p') == -1) && ($(this).val().indexOf('<br') == -1)) {
			source[$(this).attr('id')] = $(this).val();
			needs_conversion = true;
		}
	});
	if (needs_conversion) {
		$.post( "../spip.php?action=spip2html", source, function(result) {
		  $.each(result, function(id, html) {
		  	var $me = $('#'+id);
		  	$me.removeAttr('disabled').val(html);
		  	//$me.summernote({
			//	height: $me.height()
			//});
		  });
		});
	}
	$textareas.each(function() {
		if (typeof source[$(this).attr('id')] == 'string') {
			$(this).attr('disabled', 'disabled');
		} else {
			//$(this).summernote({
			//	height: $(this).height()
			//});
		}
	});

	return $textareas;

};*/

function makicattaSelect2() {
	$('.selecteur_parent, .editer select').select2({
		minimumResultsForSearch: 10
	});
}

function makicattaSidebarHover() {

	$('.nav-sidebar .has-treeview').on('mouseenter', function () {
		$(this).find('.nav-treeview').slideDown(300, function () {
			$(this).parent('.has-treeview').addClass('open menu-open');
		});
	}).on('mouseleave', function () {
		$(this).find('.nav-treeview').slideUp(300, function () {
			$(this).parent('.has-treeview').removeClass('open menu-open');
		});
	});
}