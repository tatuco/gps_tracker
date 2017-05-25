<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo base_url('web/favicon.ico');?>">
    <meta name="viewport" content="width=device-width, user-scalable = no">
    <meta charset="utf-8">
    <title>Reset password</title>
   <style>
    #main_pic{
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    #save_password{
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -180px;
        margin-left: -200px;
        color: rgb(0,0,205);
        font-weight: bold;
        padding: 10px 30px 40px 30px;
        border: 2px rgb(200,200,255) solid;
        background-color: rgb(255,255,255);
    }
    #save_password table, td, tr{
        border: none !important;
    }
    #save_password table td:first-child{
        width: 130px;
    }
    .login_footer{
    background: rgb(245,245,245);
    border: 2px rgb(200,200,255) solid;
    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 30px;
}
.login_footer a{
    position: absolute;
    left: 50%;
    margin-left: -80px;
    font-weight: bold;
}
    </style>
</head>
<body>
<img id="main_pic" src="<?php echo base_url('web/pics/world_map.jpg'); ?>" />
<div id="save_password">
<h3>Save new password</h3>
<?php echo form_open(); ?>
<table>
    <tbody>
        <tr>
            <td>New password:</td>
            <td><?php echo form_password('password', $this->input->post('password')); ?></td>
        </tr>
        <tr>
            <td>Confirm password:</td>
            <td><?php echo form_password('confirm_password', $this->input->post('confirm_password')); ?></td>
        </tr>
    </tbody>
</table>
<p><?php echo form_submit('submit', 'Save password'); ?></p>
<?php echo form_close(); ?>
<?php echo validation_errors(); ?>
<?php if (isset($message)) echo $message; ?>
</div>

<div class="login_footer">
    <a href="https://github.com/akosbrachna/gps_tracker/" target="_blank">About GPS Tracker</a>
</div>

</body>
</html>