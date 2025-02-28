$(document).ready(function () {
    let $resetbutton = $('.resetbutton');
    let $resetform = $('.resetform-container');
    let $updateform = $('.update-container');
    let $updatephoto = $('.updatephoto')
    let $cancelbutton =$('.cancel')

    $resetform.hide();

    $resetbutton.click(function() {
        $resetbutton.hide();
        $updateform.hide();
        $updatephoto.hide();
        $resetform.show();
    });

    $cancelbutton.click(function() {
        $resetbutton.show();
        $updateform.show();
        $updatephoto.show();
        $resetform.hide();
    });
  });