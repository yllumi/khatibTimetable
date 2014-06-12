<div id="breadcrumbs" class="fixed">
	<div class="toolbar" style="margin-left:20px;">
		<div class="btn-group">
			<a href="nodes.php" class="btn btn-small ajax" id="add-node">
				<i class="icon-double-angle-left"></i> Kembali ke daftar
			</a>
		</div>
	</div>
</div>

<div id="page-content" class="clearfix">
	<div class="page-header position-relative">
		<h1>Data Node
			<small><i class="icon-double-angle-right"></i> Daftar Khotib</small>
		</h1>
	</div><!--/.page-header-->

	<div class="row-fluid">
		<!--PAGE CONTENT BEGINS HERE-->
		<form action="#" method="POST">
			<label for="code">Kode Khotib</label>
			<input type="text" name="code" id="code" placeholder="mis. 1, 2, K1, K2">
			<label for="code">Nama Khotib</label>
			<input type="text" name="title" id="title" placeholder="mis. Iman Romansyah">
			<label for="code">Deskripsi Khotib</label>
			<textarea name="description" id="description" placeholder="mis. no.telp, alamat"></textarea>
			<br>
			<input type="hidden" name="table" value="node" />
			<a href="#" class="btn btn-small btn-primary insert-data">Simpan</a>
		</form>

		<!--PAGE CONTENT ENDS HERE-->
	</div><!--/row-->
</div>