<div class="alert_msg">
<?php if($this->session->flashdata('success')){ ?>
	<div class="" style="padding-top:10px;">
		<div class="alert alert-success alert-dismissible">
			<button class="close" data-dismiss="alert"  aria-label="Close">×</button>
			<strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
		</div>	
	</div>

<?php }else if($this->session->flashdata('message1')){ ?>
 <div class="" style="padding-top:10px;">
    <div class="alert alert-error alert-dismissible">
	<button class="close" data-dismiss="alert" aria-label="Close">×</button>
		<strong>Request!</strong> <?php echo $this->session->flashdata('message1'); ?>
	</div>		
  </div>

<?php }else if($this->session->flashdata('error')){ ?>
 <div class="" style="padding-top:10px;">
    <div class="alert alert-danger alert-dismissible">
	<button class="close" data-dismiss="alert" aria-label="Close">×</button>
		<strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
	</div>		
  </div>
  
<?php }else if($this->session->flashdata('message')){ ?>
 <div class="" style="padding-top:10px;">
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
		aria-hidden="true">&times;</span></button>
      <?php echo $this->session->flashdata('message'); ?>
    </div>
  </div>

<?php }
?>
</div>