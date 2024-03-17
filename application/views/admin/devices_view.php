<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
	<a href="<?php echo site_url('admin/users/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px 0px'>Create user</a>
		
	<div class="row">
		<div class="col-md-12">
			<div class="card">
			<?php $this->load->view('public/themes/default/error_msg'); ?>
				<div class="content">
					<div class="toolbar">
					</div>
						<div class="fresh-datatables">
							<table id="jqdatatable" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <td style="width:5%">ID</td>
                                    <td style="width:15%">Emp.Code</td>
                                    <td style="width:20%">Name</td>
                                    <td style="width:15%">D.Type</td>
                                    <td style="width:35%">D.ID</td>
                                    <td style="width:10%">Delete</td>
                                </thead>
                                <tbody>
                                    <?php foreach($devices as $row): ?>
                                        <tr>
                                            <td><?php echo $row->device_id ?></td>
                                            <td><?php echo $row->emp_code ?></td>
                                            <td><?php echo $row->user_name ?></td>
                                            <td><?php echo $row->devicetype ?></td>
                                            <td><?php echo $row->device_notification_id ?></td>
                                            <td><?php echo anchor('admin/users/delete_device/'.$row->device_id,'<i class="fa fa-remove"></i>','class="btn btn-simple btn-danger btn-icon delete" onclick="return confirm(\'Are You Sure ?\')" ') ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
							</table>
                            
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>