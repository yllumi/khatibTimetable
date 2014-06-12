// first load main page
var mainpage = 'dashboard.php';

var currentColumn, place, khatib, fileName, fileExt, named;
var $activeRow, $activeColumn, $activeNode, $parentNode;

var $activeNode;
var $activeColumn;
var $activeRow;

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

	// alert($activeNode.html());
});

// if delete-node button clicked
	$(document).on('click', 'button#delete-node', function(){
		if(!$(this).hasClass('disabled')){
			$('#modal-confirm-delete').modal({backdrop:"static"}).modal('show');
			$(document).on('click', '#btn-delete-data', function(){
				$('td span[rel='+$activeNode.attr('rel')+']').removeClass('active');
				delete_node($activeNode);
				var matrix = get_matrix_table();
				$.ajax({
					type: 'POST',
					url: 'app_query.php?f=update_matrix',
					data: {matrix:matrix}
				}).done(function(msg){
					resetToolbar();
					$('td span').removeClass('active selected');
					$('#modal-confirm-delete').modal('hide');
					$(document).off('click', '#btn-delete-data');
				});
				return false;
			});
			$(document).on('click', '#btn-cancel-delete', function(){
				$('#modal-confirm-delete').modal('hide');
				$(document).off('click', '#btn-delete-data');
			});
		}
		return false;
	});

	// if empty node add
	$(document).on('click', 'button#add-node', function(){
		if(!$(this).hasClass('disabled')){
			$parentNode = $activeNode.parent('td');
			var activerow = $parentNode.parent('tr').attr('data-order-id');
			$('#modal-add-node').modal({backdrop:"static"}).modal('show');
			$('#modal-add-node .modal-body').empty().load('app_query.php?f=get_available_nodes', {activerow : activerow }, function(){
				$(document).on('click', '#btn-cancel-add-node', function(e){
					$('#modal-add-node').modal('hide');
				});
				$(document).on('click', '.modal-body button', function(e){
					if(!$(this).hasClass('disabled')){
						add_node($activeNode, $(this).attr('data-id'), $(this).attr('data-code'), $(this).attr('data-title'));
						var matrix = get_matrix_table();
						$.ajax({
							type: 'POST',
							url: 'app_query.php?f=update_matrix',
							data: {matrix:matrix}
						}).done(function(msg){
							$activeNode = $parentNode.children('span');
							$activeNode.addClass('selected');
							$('td span[rel='+$(this).attr('data-id')+']').addClass('active');
							resetToolbar();
							$('button#delete-node, button#exchange, button#shift').removeClass('disabled');	
							$('#modal-add-node').modal('hide');
						});
					}
					return false;
				});
				return false;
			});

		}
		$(document).off('click', '.modal-body button');
	});

	// if exchange button clicked
	$(document).on('click', 'button#exchange', function(){
		if(!$(this).hasClass('disabled')){

		}
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
	
	$('.loading').removeClass('hide').css('opacity', 1).children('span').text('Loading..');
	$container = $('#main-content');
	$container.empty().load($this.attr('href'), function() {
		$this.parent('li').parent('ul').children('li').removeClass('active');
		$this.parent('li').addClass('active');
		$('.loading').addClass('hide');
	});
});


// MAIN BUTTON FUNCTION (NEW, OPEN, SAVE, EXPORT)
// when generate-node button clicked
$(document).on('click', 'button#generate-node', function(){
	$('#modal-generate-node').modal({backdrop:"static"}).modal('show');
	$(document).on('click', '#btn-confirm-generate', function(e){
		$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Generating timetable..');
		$.ajax({
			type: 'POST',
			url: 'app_query.php?f=generate_node'
		}).done(function(msg){
			var data = jQuery.parseJSON(msg);
			// iterate per row
			for(var i = 0; i < data.length; i++){
				// iterate per column
				$row = $('#main-table').children('tbody').children('tr[id=row'+(i+1)+']');
				for(var j = 0; j < data[i].length; j++){
					$row.children('td[data-column-id='+(j+1)+']')
					.html('<span rel="'+data[i][j].rowid+'" data-khatib="'+data[i][j].title+'">'+data[i][j].code+'</span>');
					// console.log(data[i][j].code + ' ' + data[i][j].title);
					$('.loading').addClass('hide');
				}
			}
			$('#modal-generate-node').modal('hide');
		})
	});
	$(document).on('click', '#btn-cancel-generate', function(e){
		$('#modal-generate-node').modal('hide');
	});
});
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
		$.newFile(fileName, fileExt, function(){
			$('.loading').children('span').text('File created.');
			$('.loading').children('i').addClass('hide');
			$('.loading').animate({opacity:0}, 2000, function(){
				$('.loading').addClass('hide');
				$('.loading').children('i').removeClass('hide');
			});
			$('button#compressfile, button#exportfile').removeClass('disabled');
			window.location = 'index.php';
		});
	});
});

$('button#compressfile').click(function(){
	if(!$(this).hasClass('disabled')){	
		$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Cleaning..');
		$.ajax({
			type: 'POST',
			url: 'app_query.php?f=compress_file'
		}).done(function(msg){
			$('.loading').children('span').text(msg + ' row cleaned.');
			$('.loading').children('i').addClass('hide');
			$('.loading').animate({opacity:0}, 2000, function(){
				$('.loading').addClass('hide');
				$('.loading').children('i').removeClass('hide');
				console.log('cleaned');
			});
		});
	}
});
$('button#downloadfile').click(function(){
	if(!$(this).hasClass('disabled'))
		window.location = 'app_file.php?f=download_file';
});
$('button#exportfile').click(function(){
	if(!$(this).hasClass('disabled'))
		window.location = 'app/export-data.php?filename='+fileName;
});
$(document).on('click', '#closefile', function(){
	if(confirm('Anda yakin akan menutup file ini?')){
		window.location = 'app_session.php?f=sess_destroy';
	}
});
$(document).on('click', '.open-this-file', function(e){
	e.preventDefault();
	var file = $(this).attr('href');
	$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Opening file..');
	$.openFile(file, fileExt, function(){
		window.location = 'index.php';
	});
});

// DATA FUNCTION
// if delete-data link clicked
$(document).on('click', '.delete-data', function(e){
	e.preventDefault();
	var id = $(this).attr('href');
	var table = $(this).attr('data-table');
	$('#modal-confirm-delete').modal({backdrop:"static"}).modal('show');
	$(document).on('click', '#btn-delete-data', function(){
		$.ajax({
			type: 'POST',
			url: 'app_query.php?f=delete_data&j='+id,
			data: {table: table}
		}).done(function(msg){
			$('tr[rel='+id+']').fadeOut();
			$('#modal-confirm-delete').modal('hide');
		});
	});
	$(document).on('click', '#btn-cancel-delete', function(){
		$('#modal-confirm-delete').modal('hide');
	});
});
// if edit-data link clicked
$(document).on('click', '.edit-data', function(e){
	e.preventDefault();
	var id = $(this).attr('href');
	$row = $(this).parent('td').parent('tr');
	var code = $row.children('td.code').text();
	var title = $row.children('td.title').text();
	var desc = $row.children('td.desc').text();

	$row.children('td.code').html('<input type="text" name="code" id="code" class="span12" value="'+code+'" />');
	$row.children('td.title').html('<input type="text" name="title" id="title" class="span12" value="'+title+'" />');
	$row.children('td.desc').html('<input type="text" name="desc" id="desc" class="span12" value="'+desc+'" />');

	$row.children('td').children('.edit-data, .delete-data').hide();
	$row.children('td').children('.cancel-update').show().click(function(){
		$row.children('td').children('.edit-data, .delete-data').show();
		$row.children('td').children('.update-data, .cancel-update').hide();
		$row.children('td.code').html(code);
		$row.children('td.title').html(title);
		$row.children('td.desc').html(desc);
	});
	$row.children('td').children('.update-data').show().click(function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'app_query.php?f=update_data&j='+id,
			data: { code: $row.children('td.code').children('input').val(), 
					title: $row.children('td.title').children('input').val(), 
					desc: $row.children('td.desc').children('input').val(),
					table: $(this).attr('data-table') }
		}).done(function(msg){
				var res = jQuery.parseJSON(msg);
				// console.log(msg);
				if(res.status = 'success'){
					$row.children('td.code').html(res.code);
					$row.children('td.title').html(res.title);
					$row.children('td.desc').html(res.desc);
					$row.children('td').children('.edit-data, .delete-data').show();
					$row.children('td').children('.update-data, .cancel-update').hide();
				} else {
					$row.children('td').children('.edit-data, .delete-data').show();
					$row.children('td').children('.update-data, .cancel-update').hide();
					$row.children('td.code').html(code);
					$row.children('td.title').html(title);
					$row.children('td.desc').html(desc);
					alert('Data gagal disimpan');
				}
		});
	});
});
// if insert-data button clicked
$(document).on('click', '.insert-data', function(e){
	e.preventDefault();
	$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Inserting..');
	$this = $(this);
	var code = $this.siblings('input[name=code]').val();
	var title = $this.siblings('input[name=title]').val();
	var desc = $this.siblings('textarea#description').val();
	var table = $this.siblings('input[name=table]').val();

	$.ajax({
		type: 'POST',
		url: 'app_query.php?f=insert_data',
		data: { code: code, title: title, desc: desc, table: table }
	}).done(function(msg){
		$('.loading').addClass('hide');
		$('#main-content').load(table+'s.php');
	});
});

// prevent context menu on right click
$('body').bind("contextmenu", function () {
	// alert("Right click not allowed");
	return false;
});

// upload file handler
var uploadoptions = {
	complete: function(response){
		resp = jQuery.parseJSON(response.responseText);
		if(resp.error){
			$('.loading').children('span').text(resp.error);
			$('.loading').children('i').addClass('hide');
			$('.loading').animate({opacity:0}, 2000, function(){
				$('.loading').addClass('hide');
				$('.loading').children('i').removeClass('hide');
			});
		} else
			window.location = 'index.php';
	},
	error: function(){
		$('.brand').text('Terjadi kesalahan saat membuka file.');
	}
};
$(document).on('change', '#myfile', function(){
	$('.loading').css('opacity', 1).removeClass('hide').children('span').text('Opening..');
	$("#myForm").ajaxForm(uploadoptions).submit();
});

// THE FUNCTIONS

$.extend({
	newFile : function(filename, ext, callbackFn){
		$.ajax({
			type: 'POST',
			url:'app_file.php?f=new_file', 
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
$.extend({
	cloneFile : function(filename, ext, callbackFn){
		$.ajax({
			type: 'POST',
			url:'app_file.php?f=clone_file', 
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
$.extend({
	openFile : function(filename, ext, callbackFn){
		$.ajax({
			type: 'POST',
			url:'app_file.php?f=open_file',
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

function closeBrowser(){
	var win = window.open('', '_self');
	window.close();
	win.close();
	return false;
}

function resetToolbar(){
	$('.toolbar button').addClass('disabled');
	$('#generate-node').removeClass('disabled');
}

function exchange(){

}
function shift(){

}
function add_node($node, id, code, title){
	$node.parent().empty().html('<span rel="'+id+'" data-khatib="'+title+'">'+code+'</span>');
}
function delete_node($node){ // element node
	$node.parent().empty().html('<span rel="0" data-khatib="empty" data-original-title="empty">&nbsp;</span>');
}

function get_matrix_table(){
	var matrix = '[';
	$('#main-table tr').each(function(i){
		matrix += '[';
		$(this).children('td').each(function(j){
			matrix += '{"rowid":'+ $(this).children('span').attr('rel') +',';
			matrix += '"code":"'+ $(this).children('span').html() +'",';
			matrix += '"title":"'+ $(this).children('span').attr('data-khatib') +'"},';
		});
		matrix += '],';
	});
	matrix += ']';
	
	return matrix;
}