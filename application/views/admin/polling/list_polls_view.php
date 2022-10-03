<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
	<a href="<?php echo site_url('admin/polling/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px 0px'>Add Poll</a>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<?php $this->load->view('public/themes/default/error_msg'); ?>
				<div class="content">
					<div class="toolbar">
						      <!--  Here you can write extra buttons/actions for the toolbar  -->            
					</div>
						<div class="fresh-datatables">
							<table id="jqdatatable" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
								<thead>
									<th>ID</th>
									<th>Title</th>
									<th>Answers</th>
									<th>Status</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php foreach($polls as $data): ?>
										<?php 
										echo '
											<tr>
											<td>'.$data['id'].'</td>
											<td>'.$data['title'].'</td>
											<td>'.$data['choice'].'</td>
											<td>'.($data['status'] == '1' ? anchor('admin/Polling/updateStatus/'.$data['id'].'/0','Active','class="badge badge-success text-white"') : anchor('admin/Polling/updateStatus/'.$data['id'].'/1','Inactive','class="badge badge-danger text-white"')).'</td>
											<td>'.anchor('admin/Polling/view/'.$data['id'],'<i class="fa fa-eye"></i>','class="btn btn-simple btn-primary btn-icon edit"').' '.anchor('admin/Polling/delete/'.$data['id'],'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon remove" onclick="return confirm(\'Are You Sure ?\')"').'</td>
											</tr>
										'
										?>
										
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>