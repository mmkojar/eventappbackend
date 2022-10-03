<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
		<br>
		
		<a href="<?php echo site_url('admin/groups/create');?>" class="btn btn-info btn-fill btn-wd" style="margin:10px">Create group</a>
		
		<div class="row">
			  <div class="col-md-12">
				<div class="card">
				<?php $this->load->view('public/themes/default/error_msg'); ?>
					<div class="content">
						<div class="toolbar">
							<!--        Here you can write extra buttons/actions for the toolbar              -->
						</div>
			<div class="fresh-datatables">
			<?php
				if(!empty($groups))
				{
				echo '<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">';
				echo '<thead><tr>
					<th>ID</th>
					<th>Group name</th>
					<th>Group description</th>
					<th>Operations</th>
					</tr></thead>';
				$i = 1;
				foreach($groups as $group){
					if(!in_array($group->name, array('admin'))){
						
							echo '<tr>';
							echo '<td>'.$i.'</td>
							<td>'.anchor('admin/users/index/'.$group->id,$group->name).'</td>
							<td>'.$group->description.'</td>
							<td>'.anchor('admin/groups/edit/'.$group->id,'<i class="fa fa-edit"></i>','class="btn btn-simple btn-warning btn-icon edit"');
							if(!in_array($group->name, array('admin'))) echo ' '.anchor('admin/groups/delete/'.$group->id, '<i class="fa fa-times"></i>','class="btn btn-simple btn-danger btn-icon remove" ');
							echo '</td>';
							echo '</tr>';
							$i++;
						}
					}
				echo '</table>';
			}
			?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>
 <script type="text/javascript">
 
    $(document).ready(function() {
		$('#datatables').DataTable({
		    "pagingType": "full_numbers",
		    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		    responsive: true,
		    language: {
		    search: "_INPUT_",
		    searchPlaceholder: "Search records",
		    }

		});
	});
</script>