<div id="breadcrumbs" class="fixed">
	<div class="toolbar" style="margin-left:20px;">
		<div class="btn-group ajax">
			<a href="form_column.php" class="btn btn-small btn-success" id="add-column">
				<i class="icon-plus-sign-alt"></i> Tambah Kolom
			</a>
		</div>
	</div>
</div>

<div id="page-content" class="clearfix">
	<div class="page-header position-relative">
		<h1>Data Kolom
			<small><i class="icon-double-angle-right"></i> Lokasi Khutbah</small>
		</h1>
	</div><!--/.page-header-->

	<div class="row-fluid">
		<!--PAGE CONTENT BEGINS HERE-->
		
		<table class="table table-striped table-condensed" id="datatable">
			<thead>
				<tr><th width="5%">No.</th>
					<th width="10%">Kode</th>
					<th width="20%">Judul</th>
					<th width="65%" colspan="2">Deskripsi</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<ul class='pager'>
			<li class="previous"><a href='#' alt='First' class='firstPage'><i class="icon-chevron-sign-left"></i> First</a></li>
			<li class="previous"><a href='#' alt='Previous' class='prevPage'><i class="icon-circle-arrow-left"></i> Prev</a></li>
			<li><span><span class='currentPage'></span> of <span class='totalPages'></span></span></li>
			<li class="next"><a href='#' alt='Last' class='lastPage'>Last <i class="icon-chevron-sign-right"></i></a></li>
			<li class="next"><a href='#' alt='Next' class='nextPage'>Next <i class="icon-circle-arrow-right"></i></a></li>
		</ul>

		<script>
			// var data = 
			$('#datatable tbody').load('app_query.php?f=get_dataTable', {table:'column'}, function(){
				$('[rel=tooltip]').tooltip({placement:'bottom'});
				$('#datatable').paginateTable({ rowsPerPage: 10 });
			});
		</script>

		<!--PAGE CONTENT ENDS HERE-->
	</div><!--/row-->
</div>