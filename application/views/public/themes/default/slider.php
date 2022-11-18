<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!--sidebar-menu-->
<div class="wrapper">
 <div class="sidebar" data-color="gray" data-image="<?php echo base_url(); ?>assets/admin/img/full-screen-image-3.jpg">
	<!--

		Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
		Tip 2: you can also add an image using data-image tag

	-->
	<div class="logo">
	    <img class="img-responsive" src="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-32x32.png" style="display:initial"/>
		<a href="" class="logo-text" style="display:initial">
			Event App
		</a>
	</div>
	<div class="logo logo-mini">
		<img class="img-responsive" src="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-32x32.png" style="display:initial"/>
	</div>

	<div class="sidebar-wrapper">
		<div class="user">
			<div class="photo">
				<img src="<?php echo ($this->ion_auth->user()->row()->user_image !== null) ? base_url($this->ion_auth->user()->row()->user_image) : base_url('assets/admin/img/default-avatar.png'); ?>" />
			</div>
			<div class="info">
				<a data-toggle="collapse" href="#collapseExample" class="collapsed">
					<?php print_r($this->ion_auth->user()->row()->username);?>
					<b class="caret"></b>
				</a>
				<div class="collapse" id="collapseExample">
					<ul class="nav">
						<li><a href="<?php echo site_url('admin/user/changepassword');?>">Change Password</a></li>
						<li><a href="<?php echo site_url('admin/user/profile');?>">My Profile</a></li>
					</ul>
				</div>
			</div>
		</div>

		<ul class="nav">
			<?php
				if($this->ion_auth->is_admin()){?>
					<li class = "<?php echo ($current_tab == 'settings' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/settings'); ?>"><i class="fa fa-cog" aria-hidden="true"></i><p>App Theme Settings</p></a></li>
					<li class = "<?php echo ($current_tab == 'dashboard' ? "active" :  "") ?>"><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>
					<li class = "<?php echo ($current_tab == 'users' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/users'); ?>"><i class="fa fa-users" aria-hidden="true"></i><p>Users</p></a></li>
					<li class = "<?php echo ($current_tab == 'qr_code' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/QR'); ?>"><i class="fa fa-qrcode" aria-hidden="true"></i><p>QR Codes</p></a></li>
					<li class = "<?php echo ($current_tab == 'message' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/message_notification'); ?>"><i class="fa fa-bell" aria-hidden="true"></i><p>Message Notification</p></a></li>
					<li class = "<?php echo ($current_tab == 'Polling' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/polling'); ?>"><i class="fa fa-tasks" aria-hidden="true"></i><p>Polling</p></a></li>
					<li class = "<?php echo ($current_tab == 'about_event' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/about_event'); ?>"><i class="fa fa-info" aria-hidden="true"></i><p>About Event</p></a></li>
					<!-- <li class = "<?php echo ($current_tab == 'gallery' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/gallery'); ?>"><i class="fa fa-cog" aria-hidden="true"></i><p>Gallery</p></a></li> -->
					<li class = "<?php echo ($current_tab == 'faqs' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/FAQ'); ?>"><i class="fa fa-question" aria-hidden="true"></i><p>FAQ's</p></a></li>
					<li class = "<?php echo ($current_tab == 'agenda' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/agenda'); ?>"><i class="fa fa-calendar" aria-hidden="true"></i><p>Event Agenda</p></a></li>
					
					<li class = "<?php echo ($current_tab == 'exhibitors' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/exhibitors'); ?>"><i class="fa fa-cog" aria-hidden="true"></i><p>Exhibitors</p></a></li>
					<li class = "<?php echo ($current_tab == 'sponsor' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/sponsor'); ?>"><i class="fa fa-address-card-o" aria-hidden="true"></i><p>Sponsors</p></a></li>
					<li class = "<?php echo ($current_tab == 'speaker' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/speaker'); ?>"><i class="fa fa-male" aria-hidden="true"></i><p>Speakers</p></a></li>
					
					<!-- <li class = "<?php echo ($current_tab == 'requested' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/requested'); ?>"><i class="pe-7s-mail"></i><p>Request</p></a></li>
					<li class = "<?php echo ($current_tab == 'attendee' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/attendee'); ?>"><i class="pe-7s-users"></i><p>Attendee</p></a></li>
					<li class = "<?php echo ($current_tab == 'membership' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/membership'); ?>"><i class="pe-7s-users"></i><p>Membership</p></a></li>
					<li class = "<?php echo ($current_tab == 'users' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/users'); ?>"><i class="pe-7s-add-user"></i><p>Users</p></a></li>
					
					<li class = "<?php echo ($current_tab == 'groups' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/groups'); ?>"><i class="pe-7s-graph"></i><p>Groups</p></a></li> -->
					
				<?php } //else if($this->ion_auth->in_group("sub admin")){?>
				
					<!--<li class = "<?php echo ($current_tab == 'users' ? "active" :  "") ?>"><a href="<?php echo site_url('admin/users'); ?>"><i class="pe-7s-users"></i><p>Users</p></a></li>-->
					
				<?php //} ?>
		</ul>
	</div>
</div>
<div class="main-panel">
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-minimize">
			<button id="minimizeSidebar" class="btn btn-info btn-fill btn-round btn-icon">
				<i class="fa fa-ellipsis-v visible-on-sidebar-regular"></i>
				<i class="fa fa-navicon visible-on-sidebar-mini"></i>
			</button>
		</div>
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo base_url(); ?>">Event App</a>
		</div>
		<div class="collapse navbar-collapse">

			<ul class="nav navbar-nav navbar-right">
				<!--<li>
					<a href="">
						<i class="fa fa-line-chart"></i>
						<p>Stats</p>
					</a>
				</li>-->
				<li class="dropdown dropdown-with-icons">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-list"></i>
						<p class="hidden-md hidden-lg">
							More
							<b class="caret"></b>
						</p>
					</a>
					<ul class="dropdown-menu dropdown-with-icons">
						<li>
							<a href="<?php echo site_url('admin/user/profile');?>">
								<i class="pe-7s-look"></i> My Profile
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="<?php echo site_url('admin/user/logout');?>" class="text-danger">
								<i class="pe-7s-close-circle"></i>
								Log out
							</a>
						</li>
					</ul>
				</li>

			</ul>
		</div>
	</div>
</nav>