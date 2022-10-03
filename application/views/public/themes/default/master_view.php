<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/themes/default/header');  
 ?>

<?php echo $the_view_content; ?>
	
<?php $this->load->view('public/themes/default/footer');?>