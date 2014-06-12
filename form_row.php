<div id="breadcrumbs" class="fixed">
	<div class="toolbar" style="margin-left:20px;">
		<div class="btn-group">
			<a href="rows.php" class="btn btn-small ajax" id="add-column">
				<i class="icon-double-angle-left"></i> Kembali ke daftar
			</a>
		</div>
	</div>
</div>

<div id="page-content" class="clearfix">
	<div class="page-header position-relative">
		<h1>Data Baris
			<small><i class="icon-double-angle-right"></i> Tanggal Khutbah</small>
		</h1>
	</div><!--/.page-header-->

	<div class="row-fluid">
		<!--PAGE CONTENT BEGINS HERE-->
		<form action="#">
			<label for="code">Kode Baris</label>
			<input type="text" name="code" id="code" placeholder="mis. 2/10/2013">
			<label for="code">Judul Baris</label>
			<input type="text" name="title" id="title" placeholder="mis. 2 Oktober 2013">
			<label for="code">Deskripsi Baris</label>
			<textarea name="description" id="description" placeholder="mis. Minggu pertama Oktober"></textarea>
			<br>
			<input type="hidden" name="table" value="row" />
			<a href="#" class="btn btn-small btn-primary insert-data">Simpan</a>
		</form>

		<!--PAGE CONTENT ENDS HERE-->
	</div><!--/row-->
</div>