<div id="breadcrumbs" class="fixed">
	<div class="toolbar" style="margin-left:20px;">
		<div class="btn-group">
			<a href="columns.php" class="btn btn-small ajax" id="add-column">
				<i class="icon-double-angle-left"></i> Kembali ke daftar
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
		<form action="#">
			<label for="code">Kode Masjid</label>
			<input type="text" name="code" id="code" placeholder="mis. A, B, A1, Col1">
			<label for="code">Nama Masjid</label>
			<input type="text" name="title" id="title" placeholder="mis. Masjid al-Muhajirin">
			<label for="code">Deskripsi Masjid</label>
			<textarea name="description" id="description" placeholder="mis. telp:(021)5634p5o alamat: Jl. Blabla 19 Bandung"></textarea>
			<br>
			<input type="hidden" name="table" value="column" />
			<a href="#" class="btn btn-small btn-primary insert-data">Simpan</a>
		</form>

		<!--PAGE CONTENT ENDS HERE-->
	</div><!--/row-->
</div>