$(document).ready(function () {
    let $navbutton = $('.nav .button');
    let $editbutton = $('.filters #edit');
    let $searchbutton = $('.filters #search');
    let $searchbar = $('.filters #searchbar');
    let $submitbutton = $('.filters #submit');
    let $submitsearch = $('.filters #submitsearch');
    let $log = $('.log');
    let $users = $('.users');
    let $jobs = $('.jobs');
    let $applications = $('.applications');
    let $discussions = $('.discussions');

    $editbutton.hide();
    $submitbutton.hide();
    $searchbar.hide();
    $submitsearch.hide();
    $users.hide();
    $jobs.hide();
    $applications.hide();
    $discussions.hide();

    $navbutton.click(function() {
        $navbutton.removeClass("selected");
        $(this).addClass("selected");
        $searchbutton.show();
        $editbutton.show();
        $submitbutton.hide();
        $searchbar.hide();
        $submitsearch.hide();

        if ($(this).html().indexOf("Users") >= 0) {
            $editbutton.show();
            $log.hide();
            $jobs.hide();
            $applications.hide();
            $discussions.hide();
            $users.show();
        }
        else if ($(this).html().indexOf("Jobs") >= 0) {
            $editbutton.show();
            $log.hide();
            $jobs.show();
            $applications.hide();
            $discussions.hide();
            $users.hide();
        }
        else if ($(this).html().indexOf("Applications") >= 0) {
            $editbutton.show();
            $log.hide();
            $jobs.hide();
            $applications.show();
            $discussions.hide();
            $users.hide();
        }
        else if ($(this).html().indexOf("Discussions") >= 0) {
            $editbutton.show();
            $log.hide();
            $jobs.hide();
            $applications.hide();
            $discussions.show();
            $users.hide();
        }
        else {
            $editbutton.hide();
            $users.hide();
            $jobs.hide();
            $applications.hide();
            $discussions.hide();
            $log.show();
        }
    });

    $editbutton.click(function() {
        $editbutton.hide();
        $submitbutton.show();
    });

    $submitbutton.click(function() {
        $editbutton.show();
        $submitbutton.hide();
    });

    $searchbutton.click(function() {
        $searchbutton.hide();
        $searchbar.show();
        $submitsearch.show();
    });
  });