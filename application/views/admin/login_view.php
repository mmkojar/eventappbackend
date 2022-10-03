<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">    
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img class="img-responsive" src="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-32x32.png" />
            <a class="navbar-brand" href="<?php echo base_url() ?>">Event App</a>
        </div>
        <div class="collapse navbar-collapse">       
            
            <ul class="nav navbar-nav navbar-right">
                <li>
                   <?php echo anchor(base_url().'admin/user/forgot_password', 'Forgot Password ?');?>
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
                        <?php echo form_open('',array('class'=>'form-vertical form_validation','novalidate'=>'novalidate','name'=>'loginform','id'=>'loginform'));?>
                            
                        <!--   if you want to have the card without animation please remove the ".card-hidden" class   -->
                            <div class="card">
								<?php $this->load->view('public/themes/default/error_msg'); ?>
                                <div class="header text-center">Login</div>
                                <div class="content">
                                    <div class="form-group">
										<?php echo form_label('Email address');?>
										<?php echo form_error('identity');?>
										<?php echo form_input('identity','admin@eventapp.com','class="form-control" required = "true"');?>
                                    </div>
                                    <div class="form-group">
										<?php echo form_label('Password');?>
										<?php echo form_error('password');?>
										<?php echo form_password('password','123','class="form-control" required = "true"');?>
                                    </div> 
                                </div>
                                <div class="footer text-center">
                                    <?php echo form_submit('submit', 'Log in', 'class="btn btn-fill btn-info btn-wd"');?>
                                </div>
                            </div>
						<?php echo form_close();?>
                                
                    </div>                    
                </div>
            </div>
        </div>  