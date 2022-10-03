<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div class="content">
	<h1  class="text-danger">
		404 Error Page
	</h1>
	<div class="error-page">

		<div class="error-content">
			<h3 class="text-danger"><i class="fa fa-warning text-yellow"></i> &nbsp;&nbsp;&nbsp;&nbsp; Oops! Page not found.</h3>

			<p>
			We could not find the page you were looking for.
			Meanwhile, you may <a href="<?php echo base_url(); ?>admin">return to dashboard</a>.
			</p>
		</div>
		<!-- /.error-content -->
	</div>
	<!-- /.error-page -->
</div>

<script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script>