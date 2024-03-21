<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap core CSS     -->
    <link href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&amp;display=swap" rel="stylesheet"
        type="text/css">
</head>
<style>
.container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #ccc;
}

/* On mouse-over, add a grey background color */
.container:hover input~.checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked~.checkmark {
    background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.container input:checked~.checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>

<body style="background-image:url('<?php echo base_url(); ?>assets/admin/img/full-screen-image-1.jpg')">

    <div class="container">
        <div class="row" style="margin-top:20vh">
            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                <?php if(isset($_SESSION['event_sc'])): ?>
                    <div id="bs-alert" class="alert alert-success"><?php echo $_SESSION['event_sc']; ?></div>
                <?php endif ?>
                <?php if(isset($_SESSION['event_er'])): ?>
                    <div id="bs-alert1" class="alert alert-danger"><?php echo $_SESSION['event_er']; ?></div>
                <?php endif ?>
                
                <form action="<?php echo base_url('event_selection/add') ?>" method="POST">
                    <h1>Select Event</h1>
                    <br>
                    <?php foreach ($list as $key => $val): ?>
                        <div class="form-group">
                            <label class="container" for="<?php echo $key ?>"><?php echo $val ?>
                                <input type="checkbox" id="<?php echo $key ?>" name="<?php echo $key ?>">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    <?php endforeach ?>
                    <br>
                    <input type="hidden" name="user_id" value="<?php echo $uid ?>">
                    <input type="hidden" name="qr_id" value="<?php echo $qr_id ?>">
                    <input type="hidden" name="scan_by" value="<?php echo $scan_by ?>">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById("bs-alert").style.display='none';
            document.getElementById("bs-alert1").style.display='none';
        }, 3000);
    </script>
</body>
</html>