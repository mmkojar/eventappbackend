<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">    
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
           <a class="navbar-brand" href="<?php echo base_url(); ?>">Event App</a>
        </div>
        <div class="collapse navbar-collapse">       
            
            <ul class="nav navbar-nav navbar-right">
                <li>
				   <?php echo anchor('admin/user/login','Login');?>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="wrapper wrapper-full-page">
    <div class="full-page login-page" data-color="azure" data-image="<?php echo base_url(); ?>assets/admin/img/full-screen-image-1.jpg">   
        
    <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
            <div class="container">
                <div class="row">                   
                    <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                        <?php echo form_open('',array('class'=>'form-vertical','novalidate'=>'novalidate','name'=>'forgotform','id'=>'forgotform'));?>
                            
                        <!--   if you want to have the card without animation please remove the ".card-hidden" class   -->
                            <div class="card card-hidden">
								<?php $this->load->view('public/themes/default/error_msg'); ?>
                                <div class="header text-center">Forgot Password</div>
                                <div class="content">
                                    <div class="form-group">
										<?php echo form_label('Email address');?>
										<?php echo form_error('identity');?>
										<?php echo form_input('identity','','class="form-control" required = "true"');?>
                                    </div>
                                </div>
                                <div class="footer text-center">
                                    <?php echo form_submit('submit', 'Reset', 'class="btn btn-fill btn-info btn-wd"');?>
                                </div>
                            </div>
						<?php echo form_close();?>
                                
                    </div>                    
                </div>
            </div>
        </div>
    <!--   Core JS Files and PerfectScrollbar library inside jquery.ui   -->
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui.min.js" type="text/javascript"></script> 
	<script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>
	
	    
    <script type="text/javascript">
        $().ready(function(){
			$('#forgotform').validate();
			
            lbd.checkFullPageBackgroundImage();
            
            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 200)
        });
    </script>