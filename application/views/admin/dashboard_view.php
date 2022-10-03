<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('public/themes/default/slider');?>

<div class="content dashboard">
    <div class="container-fluid">
        <div class="row">
            <a href="<?php echo base_url('admin/users') ?>">
                <div class="col-md-3">
                    <div class="panel panel-default bcg-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Total Users</h3>
                        </div>
                        <div class="panel-body">
                        <h3><?php echo $total_users ?></h3>
                        </div>
                    </div>
                </div>
            </a>
            <!-- <a href="<s?php //echo base_url('admin/users') ?>"> -->
            <div class="col-md-3">
                <div class="panel panel-default bcg-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Active Users</h3>
                    </div>
                    <div class="panel-body">
                       <h3><?php echo $active_users ?></h3>
                    </div>
                </div>
            </div>
            <!-- </a> -->
            <a href="<?php echo base_url('admin/QR') ?>">
            <div class="col-md-3">
                <div class="panel panel-default bcg-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total QR</h3>
                    </div>
                    <div class="panel-body">
                       <h3><?php echo $total_qr ?></h3>
                    </div>
                </div>
            </div>
            </a>
            <a href="<?php echo base_url('admin/polling') ?>">
                <div class="col-md-3">
                    <div class="panel panel-default bcg-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">Total Polls</h3>
                        </div>
                        <div class="panel-body">
                        <h3><?php echo $total_polls ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>