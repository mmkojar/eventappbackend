<head>
<style>
.title_main{
	font-size: 18pt;
    color: rgb(0,32,96);
}
span{
	color: rgb(31,73,125);
	font-family: arial,sans-serif;
	font-size: 100%;
}
.title_sub{
    font-size: 100%;
	color: rgb(0,32,96);
	font-style: italic;
	font-weight: bold;
}
.sub_text{
	font-size: 12.8px;	
}
.number span{
	font-weight: bold;
	font-family: "Eras Light ITC";
    color: navy;
}
.address span{
	font-weight: bold;
	font-family: Cambria,serif;
    color: rgb(23,54,93);
}
</style>
</head>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<center><img src="<?php echo base_url(); ?>assets/admin/img/logo.png"></center>
			<br>
			<p align="center" style="text-align:center">
				<span class = "title_main" style=""><a href="https://info2ideas.com/" target="_blank">Info2ideas</a></span><br>
				<span class = "title_sub">Event App</span>
			</p>
			<br>	
			<p>
				<span>Hello <?php echo $requested_to_name; ?>,</span>
				<br>
				<br>
				<span>You got a Request from <?php echo $requested_by_name; ?> of <?php echo $requested_by_company; ?> and email address is <?php echo $requested_by_email; ?>.</span>
			</p>
			<p>
				<span> Event Team </span><br>
				<span><b>Thanks & Regards,</b></span><br>
				<span>Event Department.</span><br>
			</p>
			<p>	
			<img src='<?php echo base_url(); ?>assets/admin/img/logo.png'>
			</p>
			
			<!-- <p class="address">
				<span> 211-212, 2ND Floor, MIDAS’, Sahar Plaza,</span><br>
				<span> Kondivita, Mathuradas Vassanji Road,</span><br>
				<span> Andheri (East), Mumbai – 400 069</span><br>
			</p>
			<p class="number">
				<span> Tel:  + 91- 22-65754321/</span><br>
				<span> Fax: + 91-22-67259555</span><br>
				<span> Email: <a href="mailto:events@mrai.org.in" target="_blank">events@mrai.org.in</a></span><br>
				<span> Website:<a href="http://www.mrai.org.in/" target="_blank"> http://www.mrai.org.in/ </a></span><br>
			</p> -->
			<p>	
			<!-- <img src='<s?php //echo base_url(); ?>assets/admin/img/banner.png' style="max-width:624px;"> -->
			</p>
			
		</div>
	</div>
</div>