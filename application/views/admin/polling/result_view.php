<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>
<div id="content">
	<div class="container-fluid">
		<a href="<?php echo site_url('admin/polling/create');?>" class="btn btn-info btn-fill btn-wd" style='margin:10px 0px'>Add Poll</a>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
				<?php $this->load->view('public/themes/default/error_msg'); ?>
					<div class="content poll-result">
						<h3><?=$title?></h3>
						<div class="card-body wrapper">
							<?php foreach ($poll_answers as $poll_answer): ?>
							<div class="poll-question">
					            <p>
					            	<?=$poll_answer['choice']?> <span>(<?=$poll_answer['votes']?> Votes)</span>
					            </p>
								<?php if($poll_answer['votes'] > 0): ?>					        
						            <div class="result-bar" style="width:<?=@round(($poll_answer['votes']/$total_votes)*100)?>%">
						                <?=@round(($poll_answer['votes']/$total_votes)*100)?>%
						            </div>
							    <?php else: ?>
							    	<div class="result-bar" style="width:0%">0%</div>
							    <?php endif ?>
					    	</div>
					        <?php endforeach; ?>
							<br>
							<!-- submit votes -->
							<!-- <form action="<s?php //echo base_url('admin/polling/updateVotes/'.$poll_id) ?>" method="post">
								<s?php //for ($i = 0; $i < count($poll_answers); $i++): ?>
								<label>
									<input type="radio" name="id" value="<s?=//$poll_answers[$i]['id']?>"<s?=$i == 0 ? ' checked' : ''?>>
									<s?=//$poll_answers[$i]['choice']?>
								</label>
								<s?php //endfor; ?>
								<div>
									<input type="submit" value="Vote" class="btn btn-info btn-fill btn-sm">
									<a href="result.php?id=<s?= //$poll['id']?>">View Result</a>
								</div>								
							</form> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>