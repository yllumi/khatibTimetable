<?php require('app/bootstrapper.php'); ?>

<div id="page-content" class="clearfix">

	<div class="row-fluid">
		<!--PAGE CONTENT BEGINS HERE-->

		<div class="dashboard-menu">
			<table>
				<tr>
					<th><button class="btn btn-success" id="newfile"><i class="icon-file"></i></button></th>
					<td>
						<h3>Buat File Jadwal Baru</h3>
						<p>Buat jadwal khutbah baru, mulai dari mendefenisikan baris, kolom, dan data.</p>
					</td>
				</tr>
				<tr>
					<th>
						<form id="myForm" action="app_file.php?f=catch_file" method="post" enctype="multipart/form-data">
							<button class="btn btn-warning">
								<i class="icon-folder-open"></i>
								<input type="file" size="60" name="myfile" id="myfile" style="position: absolute;top: 0;left: 0;width: 90px;height: 90px;opacity: 0;background: rgb(238, 238, 238);">
							</button>
						</form>
					</th>
					<td>
						<h3>Buka File Jadwal</h3>
						<p>Buka jadwal khutbah dari file yang sudah pernah dibuat. <br>
							<?php
								$files = get_filenames('app/assets/saved/');
								$details = array();
								foreach ($files as $value) {
									$details[filemtime('app/assets/saved/'.$value)] = $value;
								}
								krsort($details);
								foreach($details as $key=>$file):
							?>
								<a href="<?php echo substr($file, 0, -4); ?>" class="open-this-file">
									<i class="icon-file-alt"></i>&nbsp;&nbsp;<?php echo $file; ?></a>
									<?php echo 'on '.date ("d F Y H:i", $key); ?>
								<br />
							<?php endforeach; ?>
						</p>
					</td>
				</tr>
				<tr>
					<th><a href="help/index.html" target="_blank" class="btn btn-info"><i class="icon-question-sign"></i></a></th>
					<td>
						<h3>Lihat Panduan Pemakaian</h3>
						<p>Lihat buku panduan cara menggunakan aplikasi.</p>
					</td>
				</tr>
			</table>
		</div>

		<!--PAGE CONTENT ENDS HERE-->
	</div><!--/row-->
</div><!--/#page-content-->