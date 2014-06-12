				<div id="breadcrumbs" class="fixed">
					<div class="toolbar" style="margin-left:20px;">
						<div class="btn-group">
							<button class="btn btn-small btn-purple" id="generate-node">
								<i class="icon-plus-sign-alt"></i> Generate Data
							</button>
						</div>
						<div class="btn-group">
							<button class="btn btn-small btn-success disabled" id="add-node">
								<i class="icon-plus-sign-alt"></i> Pasang Data
							</button>
							<button class="btn btn-small btn-primary disabled" id="exchange">
								<i class="icon-refresh"></i> Tukar Data
							</button>
							<button class="btn btn-small btn-danger disabled" id="delete-node">
								<i class="icon-minus-sign-alt"></i> Hapus Data
							</button>
						</div>
					</div>
				</div>

				<div id="page-content" class="clearfix">

					<div class="row-fluid">
						<!--PAGE CONTENT BEGINS HERE-->

						<div class="space-6"></div>

						<div class="row-fluid">
							<div class="span12" id="main-area">
								<div class="unscrollable">
									<table class="table minimized" id="head-table" style="margin-bottom:1px;">
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="scrollable">
									<table class="table minimized" id="main-table" style="margin-bottom: 70px">
										<tbody>
											<tr id="row10" data-row-id="10" data-order-id="1">
												<th class="yaxis">
													<span>
														<a rel="tooltip" href="#"><i class="icon-retweet" title="random data in this row"></i></a>
														05/06/2013
													</span>
												</th>
												<td data-column-id="1"><span rel="4" data-khatib="Rizki Abdurrahman">4</span></td>
												<td data-column-id="2"><span rel="5" data-khatib="M. Iqbal Fathurrahman">5</span></td>
												<td data-column-id="3"><span rel="6" data-khatib="Toni Haryanto">6</span></td>
												<td data-column-id="4"><span rel="7" data-khatib="Hilman Fikri">7</span></td>
												<td data-column-id="5"><span rel="8" data-khatib="Febtri Zainal Rahman">8</span></td>
												<td data-column-id="6"><span rel="9" data-khatib="Firman fauzi">9</span></td>
												<td data-column-id="7"><span rel="10" data-khatib="Kresna Galuh">10</span></td>
												<td data-column-id="8"><span rel="3" data-khatib="Hasan Nasrullah">3</span></td>
												<td data-column-id="9"><span rel="2" data-khatib="Iman Romansyah">2</span></td>
												<td data-column-id="10"><span rel="1" data-khatib="Hilman Rasyid">1</span></td>
											</tr>
										</tbody>
									</table>
									<script>
										$('#head-table tbody').load('app_query.php?f=loop_head_table', function(){
											$('#main-table tbody').load('app_query.php?f=loop_main_table', function(){
												$.ajax({
													url: 'app_query.php?f=get_matrix', 
													type: 'POST'
												}).done(function(msg){
													var data = jQuery.parseJSON(msg);
													if(data.status !== false){
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
													}
												});
												$('[rel=tooltip]').tooltip({placement:'bottom'});
											});
										});

									$('#exchange').hide();
									</script>

								</div> <!-- end .scrollable -->
							</div> <!-- end #main-content -->
						</div>

						<!--PAGE CONTENT ENDS HERE-->
					</div><!--/row-->
				</div><!--/#page-content-->