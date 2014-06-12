<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Scheduler</title>
		<meta name="description" content="This is page-header (.page-header &gt; h1)" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!--basic styles-->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="themes/font-awesome/css/font-awesome.min.css" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="themes/font-awesome/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="themes/css/w8.min.css" />
		<link rel="stylesheet" href="themes/css/w8-responsive.min.css" />
		<!--inline styles if any-->
	</head>
	<body class="navbar-fixed">
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a href="#" class="brand">
						<i class="icon-calendar"></i> Penjadwalan Khutbah
					</a>
					<!--/.brand-->
					<small id="filename">
						<?php if(isset($_SESSION['filename'])) echo $_SESSION['filename'].$_SESSION['file_ext']; ?>
						<a href="#" rel="tooltip" data-placement="left" id="closefile" style="display:none" title="Tutup File">
							<i class="icon-remove"></i>
						</a>

						<a href="#" rel="tooltip" data-placement="left" id="closeapp" style="color:white;font-size:18px;" title="Tutup Aplikasi" onclick="closeBrowser()">
							<i class="icon-remove-sign"></i>
						</a>
					</small>
					<div class="loading hide">
						<i class="icon-spinner icon-spin icon-large white"></i>
						<span class="white">memuat..</span>
					</div>
				</div>
				<!--/.container-fluid-->
			</div>
			<!--/.navbar-inner-->
		</div>
		<div class="container-fluid" id="main-container">
			<a id="menu-toggler" href="#"><span></span></a>
			<div id="sidebar" class="fixed">
				<div id="sidebar-shortcuts">
					<div id="sidebar-shortcuts-large">
						<button class="btn btn-small btn-success" rel="tooltip" data-placement="bottom" id="newfile" title="Buat file baru">
							<i class="icon-file-alt"></i>
						</button>
						<form id="myForm" action="app_file.php?f=catch_file" method="post" enctype="multipart/form-data" style="display:inline">
							<button class="btn btn-small btn-warning" rel="tooltip" data-placement="bottom" id="openfile" title="Buka file">
								<i class="icon-folder-open-alt"></i>
								<input type="file" name="myfile" id="myfile" style="position:absolute;opacity:0;width:42px;height:32px;top:0;left:0;" value="">
							</button>
						</form>
						<button class="btn btn-small btn-danger disabled" rel="tooltip" data-placement="bottom" id="downloadfile" title="Unduh data primari">
							<i class="icon-download-alt"></i>
						</button>
						<button class="btn btn-small btn-info disabled" rel="tooltip" data-placement="bottom" id="exportfile" title="Expor data ke Excel">
							<i class="icon-suitcase"></i>
						</button>
					</div>
					<div id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>
						<span class="btn btn-warning"></span>
						<span class="btn btn-danger"></span>
						<span class="btn btn-info"></span>
					</div>
				</div>
				<ul class="nav nav-list ajax" id="pagemenu" style="display:none" data-target="#main-content">
					<li class="active">
						<a href="table.php">
							<i class="icon-table"></i>
							<span>Tabel Jadwal</span>
						</a>
					</li>
					<li>
						<a href="columns.php">
							<i class="icon-columns"></i>
							<span>Lokasi Khutbah</span>
						</a>
					</li>
					<li>
						<a href="rows.php">
							<i class="icon-reorder"></i>
							<span>Tanggal Khutbah</span>
						</a>
					</li>
					<li>
						<a href="nodes.php">
							<i class="icon-book"></i>
							<span>Data Khatib</span>
						</a>
					</li>
				</ul>
				<!--.nav-list-->
				<div id="sidebar-collapse">
					<i class="icon-double-angle-left"></i>
				</div>
			</div>
			<div id="main-content" class="clearfix">
				<!-- PAGE WILL LOAD HERE -->
			</div>
			<!--/#main-content-->
		</div>
		<!--/.fluid-container#main-container-->
		
		<!-- Modal New File -->
		<div class="modal hide fade" id="modal-new">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>File Baru..</h3>
			</div>
			<div class="modal-body">
				<label for="filename">Nama File</label><input type="text" value="" id="filename"> .sch
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btn-create-file">Buat File Baru</a>
			</div>
		</div>
		<!-- Modal Confirm Delete -->
		<div class="modal hide fade" id="modal-confirm-delete">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Konfirmasi Penghapusan..</h3>
			</div>
			<div class="modal-body">
				Anda yakin akan menghapus data ini? Data  yang dihapus tidak dapat dikembalikan.
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btn-delete-data">Hapus</a>
				<a href="#" class="btn btn-primary" id="btn-cancel-delete">Batal</a>
			</div>
		</div>
		<!-- Modal Confirm generate data -->
		<div class="modal hide fade" id="modal-generate-node">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Konfirmasi Generate Node</h3>
			</div>
			<div class="modal-body">
				Anda yakin akan menggenerate semua node? Data yang sebelumnya tidak dapat dikembalikan.
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btn-confirm-generate">Generate</a>
				<a href="#" class="btn btn-primary" id="btn-cancel-generate">Batal</a>
			</div>
		</div>
		<!-- Modal Add Node -->
		<div class="modal hide fade" id="modal-add-node">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Pasang Data</h3>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btn-cancel-add-node">Batal</a>
			</div>
		</div>

		<!--basic scripts-->
		<script src="themes/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="themes/js/jquery.paginatetable.js"></script>
		<!--w8 scripts-->
		<script src="themes/js/w8.min.js"></script>
		<!--inline scripts related to this page-->
		<script src="themes/js/jquery.form.js"></script>
		<script src="themes/js/script.js"></script>

		<script>
		<?php if(isset($_SESSION['filename'])): ?>
		named = true;
		fileName = '<?php echo $_SESSION['filename']; ?>';
		mainpage = 'table.php';
		$('button#openfile, button#downloadfile, button#exportfile').removeClass('disabled');
		$('a#closefile').show();
		$('a#closeapp').hide();
		$('#sidebar .nav').show();
		<?php endif; ?>
		var hash = window.location.hash.replace('#','');
		if(hash){
		$('#main-content').load(hash);
		$('#sidebar ul').children('li').removeClass('active');
		$('#sidebar ul').children('li').children('a[href="'+hash+'"]').parent('li').addClass('active');
		} else
		$('#main-content').load(mainpage);

		</script>
	</body>
</html>