$(document).ready(function () {
    let $resetbutton = $('.resetbutton');
    let $resetform = $('.resetform-container');
    let $updateform = $('.update-container');
    let $updatephoto = $('.updatephoto');
    let $cancelbutton =$('.cancel');
    let $editbutton = $('#edit');
    let $submit = $('#submit-update');
    let $userdata = $('.userdata');

    $resetform.hide();
    $updateform.hide();
    $updatephoto.hide();

    $resetbutton.click(function() {
        $resetbutton.hide();
        $updateform.hide();
        $updatephoto.hide();
        $editbutton.hide();
        $userdata.hide();
        $resetform.show();
    });

    $cancelbutton.click(function() {
        $resetbutton.show();
        $editbutton.show();
        $userdata.show();
        $updateform.hide();
        $updatephoto.hide();
        $resetform.hide();
    });

    $editbutton.click(function() {
        $updatephoto.show();
        $resetbutton.show();
        $updateform.show();
        $userdata.hide();
        $resetform.hide();
        $editbutton.hide();
    });

    $submit.click(function() {
        $updatephoto.hide();
        $resetbutton.show();
        $updateform.hide();
        $userdata.show();
        $resetform.hide();
        $editbutton.show();
    });
});