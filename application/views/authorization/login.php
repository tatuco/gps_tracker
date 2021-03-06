<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo favicon_path;?>?v=2">
    <meta name="viewport" content="width=device-width, user-scalable = no">
    <meta charset="utf-8">
    <script src="<?php echo base_url('web/js/libs/jquery-1.11.1.min.js');?>"></script>
    <script src="<?php echo base_url('web/js/libs/jsencrypt.min.js');?>"></script>
    <title>GPS Tracker</title>
<style>
#login_start_page{
    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -230px;
    margin-left: -180px;
    color: rgb(0,0,205);
    font-weight: bold;
    padding: 10px 30px 40px 50px;
    border: 2px rgb(100,100,255) solid;
    background-color: rgb(255,255,255);
}
#login_start_page input[type="checkbox"]{
    -ms-transform: scale(1.5); /* IE */
    -moz-transform: scale(1.5); /* FF */
    -webkit-transform: scale(1.5); /* Safari and Chrome */
    -o-transform: scale(1.5); /* Opera */
    padding: 10px;
    background-color: black !important;
}
#login_start_page a{
    margin-left: 40px;
}
#msie_no_support{
    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -180px;
    margin-left: -380px;
    display: none;
    font-size: 20px;
    color: red;
}
#main_pic{
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: -1;
    border: 2px solid rgb(50,50,150);
}
.login_footer{
    background: rgb(240,245,245);
    opacity: 0.7;
    filter: alpha(opacity = 70);
    border-bottom: 2px rgb(100,100,155) solid;
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 30px;
    padding-top:5px;
}
.login_footer a{
    position: absolute;
    left: 50%;
    margin-left: -80px;
    font-weight: bold;
    color: rgb(0,25,125);
}
</style>
</head>
<body>
    <img id="main_pic" src="<?php echo base_url('web/pics/world_map.jpg'); ?>" />
    <div>
<div id="login_start_page">
    <h2 style="margin-left: -20px;">Welcome to GPS Tracker</h2>
<h3>Login  <a href="<?php echo base_url('signup') ?>">Register</a></h3>

<?php echo form_open(); ?>

<input name="pubkey" id="pubkey" value="<?php if (!empty($pubkey)) echo $pubkey; ?>" style="display: none;" />

Email address:<p><?php echo form_input('email', $this->input->post('email')); ?></p>

Password:<p> <?php echo form_password(array('name' => 'password',
                                             'id' => 'password',
                                             'value' => $this->input->post('email'))); ?></p>
<br/>
<label style="display: inline;" for="phone">
    Logging in on phone?
</label>
<?php echo form_checkbox('phone', '1', false, 'id="phone"'); ?></p>
<br/>
<?php echo form_submit('submit', 'Login'); ?>

<a href="<?php echo base_url('password_reset') ?>">Forgot password?</a>

<?php echo form_close(); ?>

<?php echo validation_errors(); ?>
<?php if (isset($message)) echo $message; ?>

</div>
    </div>
<div class="login_footer">
    <a href="https://github.com/akosbrachna/gps_tracker/" target="_blank">About GPS Tracker</a>
</div>
    
<div id="msie_no_support">
    We are aware of the security issues on Internet Explorer, thus we do not support it. <br /><br />
    Please use some other browsers such as Firefox, Google Chrome, Safari etc. <br /><br />
    Thank you.
</div>
    
<script>
// only on http - no need for this on https
//$("form").submit(function(e)
//    {
//        e.stopPropagation();
//        var encrypt = new JSEncrypt();
//        encrypt.setPublicKey(document.getElementById('pubkey').value);
//        var encrypted = encrypt.encrypt(document.getElementById('password').value);
//        document.getElementById('password').value = "superpassword";
//        document.getElementById('pubkey').value = encrypted;
//    });
</script>
<script>
    var ua = window.navigator.userAgent
    var msie = ua.indexOf ( "MSIE " )
    if ( msie > 0 )
    {
       var login = document.getElementById('login_start_page');
       login.parentNode.removeChild(login);
       document.getElementById("msie_no_support").style.display = "block";
    }
</script>
</body>
</html>