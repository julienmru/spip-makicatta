$(function () {
//	makicattaSidebarHover()
	$('*[title]').tooltip({
		delay: 1500
	});
	makicattaSelect2(); onAjaxLoad(makicattaSelect2);
	//makicattaTokenfield();
	makicattaToggleFullscreen(); //onAjaxLoad(makicattaToggleFullscreen);
	if (typeof $.contentbuilder == 'function' && $('#text_area').length) {
		makicattaContentBuilder(); // onAjaxLoad(makicattaContentBuilder);
	}
});

function makicattaContentBuilder() {
	$textarea = $('#text_area').hide().addClass('no_barre'), initContentBuilder = function () {
	    MCB = $.contentbuilder({
            container: '.contentbuildercontainer',
            snippetOpen: true,
            scriptPath: '../lib/ContentBuilder/contentbuilder/',
            snippetData: '../lib/ContentBuilder/assets/minimalist-blocks/snippetlist.html',
            snippetPathReplace: ['assets/minimalist-blocks/', '../lib/ContentBuilder/assets/minimalist-blocks/'],
            modulePath: '../lib/ContentBuilder/assets/modules/',
            htmlSyntaxHighlighting: true,
            row: 'row',
            cols: ['col-md-1', 'col-md-2', 'col-md-3', 'col-md-4', 'col-md-5', 'col-md-6', 'col-md-7', 'col-md-8', 'col-md-9', 'col-md-10', 'col-md-11', 'col-md-12']            
        })

        $('#btnViewSnippets').on('click', function () {
            obj.viewSnippets();
        });

        $('#btnViewHtml').on('click', function () {
            obj.viewHtml();
        });

        $('#btnConfig').on('click', function () {
            obj.viewConfig();
        });
    }, prepareHTML = function (html) {
    	html = html.replace('<HTML>', '', html);
    	html = html.replace('</HTML>', '', html);
    	html = html.replace(/src="([^:"]*)"/ig, 'src="../$1"', html);
    	return html;
	};
	if ($textarea.val() && ($textarea.val().substring(0,6) != '<HTML>')) {
		$.post( "../spip.php?action=spip2html", {data: $textarea.val()}, function(result) {
			$('.editer_texte').append('<div class="contentbuildercontainer">'+prepareHTML('<div class="row"><div class="col-md-12">'+result+'</div></div>')+'</div>');
			initContentBuilder();
		});
	} else {
		$('.editer_texte').append('<div class="contentbuildercontainer">'+prepareHTML($('#text_area').val())+'</div>');
		initContentBuilder();
	}
	$('.formulaire_editer').on('submit', function () {
		var html = MCB.html().replace('src="../', '"src="');
		if (html) $('#text_area').val('<HTML>'+html+'</HTML>');
	});
}

function makicattaToggleFullscreen() {
	$('body').on('click', 'button[data-toggle-fullscreen]', function () {
		var $div = $(this).closest('div');
		if ($div.toggleClass('edit-fullscreen').hasClass('edit-fullscreen')) {
			$div.parents().css('z-index', 1000);
			$div.css('z-index', 1001);
			$div.css('overflow', 'scroll');
		} else {
			$div.parents().css('z-index', 'unset');
			$div.css('z-index', 'unset');
			$div.css('overflow', 'inherit');
		}
		$(this).find('i').toggleClass('fa-expand-arrows-alt').toggleClass('fa-compress-arrows-alt');
		return false;
	});
	$('.editer_texte').find('label').append('<button class="btn btn-default btn-sm float-right" data-toggle-fullscreen><i class="fas fa-expand-arrows-alt"></i></button>');
}

function makicattaSelect2() {
	$('.card-liens select').select2({
		minimumResultsForSearch: 10,
		closeOnSelect: false,
		selectOnClose: true
	});
	$('.navigation .editer select').select2({
		minimumResultsForSearch: 10
	});
	$('.selecteur_parent, .contenu .editer select').select2();
}

function makicattaTokenfield() {
	var engine = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.whitespace,
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  // url points to a json file that contains an array of country names, see
	  // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
	  prefetch: '../spip.php?exec=makicatta_mots'
	});

	engine.initialize();

	$('.form-control-liens').tokenfield({
	  typeahead: [null, { source: engine.ttAdapter() }]
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