<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">Edit Message Notification</div>
                    <div class="content">
                        <?php $this->load->view('public/themes/default/error_msg'); ?>
                        <div class="row">
							<?php echo form_open('',array('id'=>'editMembership','class'=>'form_validation'));?>
							<div class="form-group">
								<?php 
									$attributes = array(
									'class' => 'control-label'
									);
								echo form_label('Title','title',$attributes); ?>
								<div class="controls">
									<?php
									echo form_error('title');
									echo form_input('title',set_value('title',$message_notification["title"]),'class="form-control" required ="true"');
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
									echo form_input('message',set_value('message',$message_notification["message"]),'class="form-control" required ="true"');
									?>
								</div>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Type','type',$attributes);
								?>
								<div class="controls">
									<?php
										echo form_error('type');
										echo form_input('type',set_value('type',$message_notification["type"]),'class="form-control" required ="true"');
									?>
								</div>
							</div>
							<div class="form-group">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="1" <?php echo ($message_notification['status'] == '1') ? 'selected' : '' ?>>Active</option>
									<option value="0" <?php echo ($message_notification['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
								</select>
							</div>
							<br>
							<?php echo form_hidden('msg_id',$message_notification["msg_id"]);?>
							<?php echo form_submit('submit', 'Edit Message Notification', 'class="btn btn-info btn-fill btn-wd"');?>
							<?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>