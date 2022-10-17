<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div id="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="header">Theme Options</div>
					<div class="content">
						<div class="card-body">
							<?php echo form_open('admin/settings/store',array('id'=>'userFormValidation','class'=>'form_validation','enctype'=>'multipart/form-data'));?>
								<div class="row">
									<?php $this->load->view('public/themes/default/error_msg');?>
									<h3>Color</h3>
									<div class="col-md-3">
										<div class="form-group">
											<label for="color">Color</label>
											<input type="color" name="color" class="form-control"  value="<?php echo set_value('about',isset($res['color']) ? $res['color'] : '')?>">
										</div>
									</div>
								</div>
								<div class="row">
									<h3>Icons</h3>
									<div class="col-md-3">
										<div class="form-group">
											<label for="about">About Event</label>
											<input type="text" name="about" class="form-control" value="<?php echo set_value('about',isset($res['about']) ? $res['about'] : '')?>">
											<br>
											<input type="file" name="about_file" class="form-control" value="<?php echo set_value('about_file',isset($res['about_file']) ? $res['about_file'] : '')?>">
											<input type="hidden" name="about_file" value="<?php echo set_value('about_file',isset($res['about_file']) ? $res['about_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="agenda">Agenda</label>
											<input type="text" name="agenda" class="form-control" value="<?php echo set_value('agenda',isset($res['agenda']) ? $res['agenda'] : '')?>">
											<br>
											<input type="file" name="agenda_file" class="form-control" value="<?php echo set_value('agenda_file',isset($res['agenda_file']) ? $res['agenda_file'] : '')?>">
											<input type="hidden" name="agenda_file" value="<?php echo set_value('agenda_file',isset($res['agenda_file']) ? $res['agenda_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="delg">Delegates</label>
											<input type="text" name="delg" class="form-control" value="<?php echo set_value('delg',isset($res['delg']) ? $res['delg'] : '')?>">
											<br>
											<input type="file" name="delg_file" class="form-control" value="<?php echo set_value('delg_file',isset($res['delg_file']) ? $res['delg_file'] : '')?>">
											<input type="hidden" name="delg_file" value="<?php echo set_value('delg_file',isset($res['delg_file']) ? $res['delg_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="chat">Chat</label>
											<input type="text" name="chat" class="form-control" value="<?php echo set_value('chat',isset($res['chat']) ? $res['chat'] : '')?>">
											<br>
											<input type="file" name="chat_file" class="form-control" value="<?php echo set_value('chat_file',isset($res['chat_file']) ? $res['chat_file'] : '')?>">
											<input type="hidden" name="chat_file" value="<?php echo set_value('chat_file',isset($res['chat_file']) ? $res['chat_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="notify">Notification</label>
											<input type="text" name="notify" class="form-control" value="<?php echo set_value('notify',isset($res['notify']) ? $res['notify'] : '')?>">
											<br>
											<input type="file" name="notify_file" class="form-control" value="<?php echo set_value('notify_file',isset($res['notify_file']) ? $res['notify_file'] : '')?>">
											<input type="hidden" name="notify_file" value="<?php echo set_value('notify_file',isset($res['notify_file']) ? $res['notify_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="polls">Polling</label>
											<input type="text" name="polls" class="form-control" value="<?php echo set_value('polls',isset($res['polls']) ? $res['polls'] : '')?>">
											<br>
											<input type="file" name="polls_file" class="form-control" value="<?php echo set_value('polls_file',isset($res['polls_file']) ? $res['polls_file'] : '')?>">
											<input type="hidden" name="polls_file" value="<?php echo set_value('polls_file',isset($res['polls_file']) ? $res['polls_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="qr">QRScan</label>
											<input type="text" name="qr" class="form-control" value="<?php echo set_value('qr',isset($res['qr']) ? $res['qr'] : '')?>">
											<br>
											<input type="file" name="qr_file" class="form-control" value="<?php echo set_value('qr_file',isset($res['qr_file']) ? $res['qr_file'] : '')?>">
											<input type="hidden" name="qr_file" value="<?php echo set_value('qr_file',isset($res['qr_file']) ? $res['qr_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="speaker">Speakers</label>
											<input type="text" name="speaker" class="form-control" value="<?php echo set_value('speaker',isset($res['speaker']) ? $res['speaker'] : '')?>">
											<br>
											<input type="file" name="speaker_file" class="form-control" value="<?php echo set_value('speaker_file',isset($res['speaker_file']) ? $res['speaker_file'] : '')?>">
											<input type="hidden" name="speaker_file" value="<?php echo set_value('speaker_file',isset($res['speaker_file']) ? $res['speaker_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="sponsors">Sponsors</label>
											<input type="text" name="sponsors" class="form-control" value="<?php echo set_value('sponsors',isset($res['sponsors']) ? $res['sponsors'] : '')?>">
											<br>
											<input type="file" name="sponsors_file" class="form-control" value="<?php echo set_value('sponsors_file',isset($res['sponsors_file']) ? $res['sponsors_file'] : '')?>">
											<input type="hidden" name="sponsors_file" value="<?php echo set_value('sponsors_file',isset($res['sponsors_file']) ? $res['sponsors_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="exhi">Exhibitors</label>
											<input type="text" name="exhi" class="form-control" value="<?php echo set_value('exhi',isset($res['exhi']) ? $res['exhi'] : '')?>">
											<br>
											<input type="file" name="exhi_file" class="form-control" value="<?php echo set_value('exhi_file',isset($res['exhi_file']) ? $res['exhi_file'] : '')?>">
											<input type="hidden" name="exhi_file" value="<?php echo set_value('exhi_file',isset($res['exhi_file']) ? $res['exhi_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="faq">FAQ</label>
											<input type="text" name="faq" class="form-control" value="<?php echo set_value('faq',isset($res['faq']) ? $res['faq'] : '')?>">
											<br>
											<input type="file" name="faq_file" class="form-control" value="<?php echo set_value('faq_file',isset($res['faq_file']) ? $res['faq_file'] : '')?>">
											<input type="hidden" name="faq_file" value="<?php echo set_value('faq_file',isset($res['faq_file']) ? $res['faq_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="support">Support</label>
											<input type="text" name="support" class="form-control" value="<?php echo set_value('support',isset($res['support']) ? $res['support'] : '')?>">
											<br>
											<input type="file" name="support_file" class="form-control" value="<?php echo set_value('support_file',isset($res['support_file']) ? $res['support_file'] : '')?>">
											<input type="hidden" name="support_file" value="<?php echo set_value('support_file',isset($res['support_file']) ? $res['support_file'] : '')?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<?php echo form_submit('submit', 'Save', 'class="btn btn-info btn-fill btn-wd"');?>								
										</div>
									</div>
								</div>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>