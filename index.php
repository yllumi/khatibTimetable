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
					</a><!--/.brand-->

					<small id="filename">
						<?php if(isset($_SESSION['filename'])) echo $_SESSION['filename'].$_SESSION['file_ext']; ?>
						<a href="#" rel="tooltip" data-placement="left" id="closefile" style="display:none" title="Tutup File">
							<i class="icon-remove"></i>
						</a>
					</small>

					<div class="loading hide">
						<i class="icon-spinner icon-spin icon-large white"></i> 
						<span class="white">memuat..</span>
					</div>
				</div><!--/.container-fluid-->
			</div><!--/.navbar-inner-->
		</div>

		<div class="container-fluid" id="main-container">
			<a id="menu-toggler" href="#"><span></span></a>

			<div id="sidebar" class="fixed">
				<div id="sidebar-shortcuts">
					<div id="sidebar-shortcuts-large">
						<button class="btn btn-small btn-success" rel="tooltip" data-placement="bottom" id="newfile" title="File Baru">
							<i class="icon-file-alt"></i>
						</button>
						<button class="btn btn-small btn-warning" rel="tooltip" data-placement="bottom" id="openfile" title="Buka File">
							<i class="icon-folder-open-alt"></i>
						</button>
						<button class="btn btn-small btn-danger disabled" rel="tooltip" data-placement="bottom" id="savefile" title="Simpan File">
							<i class="icon-save"></i>
						</button>
						<button class="btn btn-small btn-info disabled" rel="tooltip" data-placement="bottom" id="exportfile" title="Ekspor">
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
							<i class="icon-table"></i> <span>Tabel</span>
						</a>
					</li>
					<li>
						<a href="columns.php">
							<i class="icon-columns"></i> <span>Kolom</span>
						</a>
					</li>
					<li>
						<a href="rows.php">
							<i class="icon-reorder"></i> <span>Baris</span>
						</a>
					</li>
					<li>
						<a href="data.php">
							<i class="icon-book"></i> <span>Data</span>
						</a>
					</li>
				</ul><!--/.nav-list-->

				<div id="sidebar-collapse">
					<i class="icon-double-angle-left"></i>
				</div>
			</div>

			<div id="main-content" class="clearfix">
				
				<!-- PAGE WILL LOAD HERE -->

			</div><!--/#main-content-->
		</div><!--/.fluid-container#main-container-->

		<!-- Modal New File -->
		<div class="modal hide fade" id="modal-new">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New File..</h3>
			</div>
			<div class="modal-body">
				<label for="filename">File Name</label><input type="text" value="" id="filename"> .sch
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btn-create-file">Create New File</a>
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

		<!--basic scripts-->

		<script src="themes/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="themes/js/jquery-ui-1.10.3.custom.min.js"></script>

		<!--w8 scripts-->
		<script src="themes/js/w8.min.js"></script>

		<!--inline scripts related to this page-->
		<script src="themes/js/script.js"></script>
		<script>
			<?php if(isset($_SESSION['filename'])): ?>
			named = true;
			fileName = '<?php echo $_SESSION['filename']; ?>';
			mainpage = 'table.php';
			$('button#savefile, button#exportfile').removeClass('disabled');
			$('a#closefile').show();
			<?php endif; ?>

			$('#main-content').load(mainpage);
		</script>

	</body>
</html>
