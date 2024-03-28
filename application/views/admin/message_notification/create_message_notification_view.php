<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">Add Message Notification</div>
                    <div class="content">
                        <?php $this->load->view('public/themes/default/error_msg'); ?>
                        <div class="row">
                            <?php echo form_open('',array('id'=>'form_validation','class'=>'form_validation'));?>                            
                            <div class="form-group">
                                <?php 
										$attributes = array(
										'class' => 'control-label'
										);
										echo form_label('Title','title',$attributes); 
								?>
                                <div class="controls">
                                    <?php
										echo form_error('title');
										echo form_input('title',set_value('title'),'class="form-control" required ="true"');
									?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
									echo form_label('Message','message',$attributes);
									?>
								<div class="controls">
									<?php
										echo form_error('message');
										echo form_input('message',set_value('message'),'class="form-control" required ="true"');
									?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
									echo form_label('Type','type',$attributes);
								?>
                                <div class="controls">
                                    
                                    <?php
                                        $options = array(
                                                ''  => 'Select Type',
                                                '1' => 'sendall',
                                                '0' => 'polling',
                                        );
										echo form_error('type');
                                        echo  form_dropdown('type',$options,'','class="form-control" required ="true"');
									?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php
									echo form_label('Status','status',$attributes);
								?>
                                <div class="controls">
									<?php
									$options = array(
											''         => 'Select the Status',
											'1'         => 'Active',
											'0'           => 'Inactive',
									);
									echo form_error('status');
									
									echo  form_dropdown('status',$options,'','class="form-control" required ="true"');
									?>
                                </div>
                            </div>
                            <br>
                            <?php echo form_submit('submit', 'Add Message Notification', 'class="btn btn-info btn-fill btn-wd"');?>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>