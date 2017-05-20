<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
#main
{
    position: relative !important;
    width: 100%;
    border: 2px solid rgb(200,200,255);
}
</style>

<?php
echo draw_table($this->data['records']);
?>
<script>
    form_attr.form_ref     = 'admin/user/get_user/';
    form_attr.dialog_title = 'Contact Form';
    form_attr.table_row = table_row;
</script>

<script>
function get_user(id)
{
    $('#spinning_wheel').fadeIn(200);
    $.get('admin/user/get_user/'+ id , function(data) 
    {
        $('#spinning_wheel').fadeOut(200);
        $('#main_form').html(data);
        $('#main_form').dialog({autoOpen: true,
                                draggable: true,
                                position: { my: "top center",
                                            at: "top center",
                                            of: "body"},
                                resizable: true,
                                modal: true,
                                width: '700px',
                                title: 'User Form'
                               });
        submit_form('#main_form');
    });
}
    table_row();
</script>
