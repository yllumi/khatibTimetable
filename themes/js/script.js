// first load main page
var mainpage = 'dashboard.php';

var currentColumn, place, khatib, fileName, fileExt, named;
var $activeRow, $activeColumn, $activeNode, $parentNode;

// set default filename
fileName = 'untitled-schedule';
fileExt = '.sch';
named = false;

// when mouse over table
$(document).on('mouseover', '.scrollable td span', function(){
	$(this).attr('data-original-title', '');
	currentColumn = $(this).parent().index();
	$head = $('.unscrollable td:nth-child('+(currentColumn+1)+')').children('span');
	$head.css('background', '#aaa');
	$(this).parent('td').siblings('th').children('span').css('background', '#aaa');
	place = $head.data('original-title');
	khatib = $(this).data('khatib');
	$(this).tooltip({ title: khatib+"<br>("+place+")", html: true, placement:"bottom", delay: { show: 5000, hide: 10 } }).tooltip('show');
});
// when mouse out of table
$(document).on('mouseout', '.scrollable td span', function(){
	$(this).tooltip('hide');
	$(this).parent('td').siblings('th').children('span').css('background', '#ddd');
	$('.unscrollable td:nth-child('+(currentColumn+1)+')').children('span').css('background', '#ddd');
});

$('[rel=tooltip]').tooltip();

// when table node clicked
$(document).on('click', 'td span', function(){
	$activeNode = $(this);
	$activeColumn = $activeNode.parent('td');
	$activeRow = $activeColumn.parent('tr');

	resetToolbar();

	if($activeNode.hasClass('selected')){
		removeAdditionalClass();
	} else { 
		if($activeNode.attr('rel') == 0){
			$('td span').removeClass('active selected');
			$activeNode.addClass('selected');
			$('button#add-node').removeClass('disabled');
			$('td span.exchangable').removeClass('exchangable');
		} else {
			$('button#delete-node, button#exchange, button#shift').removeClass('disabled');
			removeAdditionalClass();
			$('td span[rel='+$activeNode.attr('rel')+']').addClass('active');
			$activeNode.addClass('selected');

			// add .exchangable for same row siblings
			$activeColumn.siblings('td').children('span').addClass('exchangable');
		}

	}

	// if delete-node button clicked
	$(document).on('click', 'button#delete-node', function(){
		if(!$(this).hasClass('disabled')){
			$('#modal-confirm-delete').modal({backdrop:"static"}).modal('show');
			$(document).on('click', '#btn-delete-data', function(){
				$('td span[rel='+$activeNode.attr('rel')+']').removeClass('active');
				delete_node($activeNode);
				resetToolbar();
				$('td span').removeClass('active selected');
				$('#modal-confirm-delete').modal('hide');
			});
			$(document).on('click', '#btn-cancel-delete', function(){
				$('#modal-confirm-delete').modal('hide');
			});
		}
	});

	// if empty node add
	$(document).on('click', 'button#add-node', function(){
		if(!$(this).hasClass('disabled')){			
			$parentNode = $activeNode.parent();
			var i = add_node($activeNode);
			$activeNode = $parentNode.children('span');
			$activeNode.addClass('selected');
			$('td span[rel='+i+']').addClass('active');
			resetToolbar();
			$('button#delete-node, button#exchange, button#shift').removeClass('disabled');
		}
	});

	// if exchange button clicked
	$(document).on('click', 'button#exchange', function(){
		if(!$(this).hasClass('disabled')){

		}
	});

});

var removeAdditionalClass = function(){
	$('td span.active').removeClass('active');
	$('td span.selected').removeClass('selected');
	$('td span.exchangable').removeClass('exchangable');
}

// when page menu clicked
$(document).on('click', '.ajax a, a.ajax', function(e){
	e.preventDefault();
	$this = $(this);
	
	checkSession();

	$('.loading').removeClass('hide').css('opacity', 1).children('span').text('Loading..');
	$container = $('#main-content');
	$container.empty().load($this.attr('href'), function() {
		$this.parent('li').parent('ul').children('li').removeClass('active');
		$this.parent('li').addClass('active');
		$('.loading').addClass('hide');
	});
});


// MAIN BUTTON FUNCTION (NEW, OPEN, SAVE, EXPORT)
// when save button clicked
$(document).on('click', 'button#newfile', function(){
	$('#modal-new').modal({backdrop:"static"}).modal('show');
	$('#modal-new input#filename').attr('placeholder', fileName);
	$('#btn-create-file').click(function(e){
		e.preventDefault();
		$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Creating new file..');
		$('#modal-new').modal('hide');
		if(jQuery.trim($('input#filename').val()) !== '')
			fileName = jQuery.trim($('input#filename').val());
		$.saveToFile(fileName, fileExt, function(){
			$('.loading').children('span').text('File created.');
			$('.loading').children('i').addClass('hide');
			$('.loading').animate({opacity:0}, 2000, function(){
				$('.loading').addClass('hide');
				$('.loading').children('i').removeClass('hide');
			});
			$('button#savefile, button#exportfile').removeClass('disabled');
			window.location = 'index.php';
		});
	});
});
$('button#openfile').click(function(){
	
});
$('button#savefile').click(function(){
	if(!$(this).hasClass('disabled')){	
		$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Saving..');
		$.saveToFile(fileName, fileExt, function(){
			$('.loading').children('span').text('File saved.');
			$('.loading').children('i').addClass('hide');
			$('.loading').animate({opacity:0}, 2000, function(){
				$('.loading').addClass('hide');
				$('.loading').children('i').removeClass('hide');
				console.log('saved');
			});
		});
	}
});
$('button#exportfile').click(function(){
	
});

$(document).on('click', '#closefile', function(){
	if(confirm('Anda yakin akan menutup file ini?')){
		if(confirm('Simpan data terakhir ke dalam file?')){
			$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Saving..');
			$.saveToFile(fileName, fileExt, function(){
				$('.loading').children('span').text('File saved.');
				$('.loading').children('i').addClass('hide');
				$('.loading').animate({opacity:0}, 2000, function(){
					$('.loading').addClass('hide');
					$('.loading').children('i').removeClass('hide');
					console.log('saved');
				});
			});
		};

		window.location = 'app_session.php?f=sess_destroy';
	}
});

// THE FUNCTIONS

$.extend({
	saveToFile : function(filename, ext, callbackFn){
		$.ajax({
			type: 'POST',
			url:'app_save.php', 
			data: { filename: filename, ext: ext }
		}).done(function(msg){
			named = true;
			fileName = filename;
			if(typeof callbackFn == 'function'){
				callbackFn.call(this);
			};
		});
	}
});

function resetToolbar(){
	$('.toolbar button').addClass('disabled');
}

function exchange(){

}
function shift(){

}
function add_node($node){
	var rel = 1;
	var khatib = 'Jajang Jamaludin';
	$node.parent().empty().html('<span rel="'+rel+'" data-khatib="'+khatib+'">'+rel+'</span>');
	return rel;
}
function delete_node($node){ // element node
	$node.parent().empty().html('<span rel="0" data-khatib="empty" data-original-title="empty">&nbsp;</span>');
}