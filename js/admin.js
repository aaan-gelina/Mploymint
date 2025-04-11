$(document).ready(function () {
    let $navbutton = $('.nav .button');

    let $edituser = $('.filters #edit-user');
    let $editjob = $('.filters #edit-job');
    let $editapplication = $('.filters #edit-application');
    let $editdiscussion = $('.filters #edit-discussion');
    let $editmessage = $('.filters #edit-message');
    let $editresume = $('.filters #edit-resume');

    let $deleteuser = $('.delete-user');
    let $deletejob = $('.delete-job');
    let $deleteapplication = $('.delete-application');
    let $deletediscussion = $('.delete-discussion');
    let $deletemessage = $('.delete-message');
    let $deleteresume = $('.delete-resume');

    let $submituser = $('.filters #submit-user');
    let $submitjob = $('.filters #submit-job');
    let $submitapplication = $('.filters #submit-application');
    let $submitdiscussion = $('.filters #submit-discussion');
    let $submitmessage = $('.filters #submit-message');
    let $submitresume = $('.filters #submit-resume');

    let $filterlog = $('.filters #filter-log');
    let $filteruser = $('.filters #filter-user');
    let $filterjob = $('.filters #filter-job');
    let $filterapplication = $('.filters #filter-application');
    let $filterdiscussion = $('.filters #filter-discussion');
    let $filtermessage = $('.filters #filter-message');
    let $filterresume = $('.filters #filter-resume');

    let $searchlogbutton = $('.filters #search-log-button');
    let $searchuserbutton = $('.filters #search-user-button');
    let $searchjobbutton = $('.filters #search-job-button');
    let $searchapplicationbutton = $('.filters #search-application-button');
    let $searchdiscussionbutton = $('.filters #search-discussion-button');
    let $searchmessagebutton = $('.filters #search-message-button');
    let $searchresumebutton = $('.filters #search-resume-button');

    let $searchbar = $('.filters #searchbar');
    let $searchlog = $('.filters #search-log');
    let $searchuser = $('.filters #search-user');
    let $searchjob = $('.filters #search-job');
    let $searchapplication = $('.filters #search-application');
    let $searchdiscussion = $('.filters #search-discussion');
    let $searchmessage = $('.filters #search-message');
    let $searchresume = $('.filters #search-resume');

    let $log = $('.log');

    let $users = $('.users');
    let $editusers = $('.edit-users');

    let $jobs = $('.jobs');
    let $editjobs = $('.edit-jobs');

    let $applications = $('.applications');
    let $editapplications = $('.edit-applications');

    let $discussions = $('.discussions');
    let $editdiscussions = $('.edit-discussions');

    let $messages = $('.messages');
    let $editmessages = $('.edit-messages');

    let $resumes = $('.resumes');
    let $editresumes = $('.edit-resumes');

    let savedTab = localStorage.getItem("selectedTab");

    if (savedTab === "Users") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Users") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayUsers();
        localStorage.removeItem("selectedTab");
    }
    else if (savedTab === "Jobs") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Jobs") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayJobs();
        localStorage.removeItem("selectedTab");
    }
    else if (savedTab === "Applications") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Applications") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayApplications();
        localStorage.removeItem("selectedTab");
    }
    else if (savedTab === "Discussions") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Discussions") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayDiscussions();
        localStorage.removeItem("selectedTab");
    }
    else if (savedTab === "Messages") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Messages") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayMessages();
        localStorage.removeItem("selectedTab");
    }
    else if (savedTab === "Resumes") {
        $navbutton.removeClass("selected");
        $navbutton.each(function () {
            if ($(this).html().indexOf("Resumes") >= 0) {
                $(this).addClass("selected");
            }
        });

        displayResumes();
        localStorage.removeItem("selectedTab");
    }
    else {
        displayLog();
    }

    $navbutton.click(function() {
        $navbutton.removeClass("selected");
        $(this).addClass("selected");

        if ($(this).html().indexOf("Users") >= 0) {
            displayUsers();
        }
        else if ($(this).html().indexOf("Jobs") >= 0) {
            displayJobs();
        }
        else if ($(this).html().indexOf("Applications") >= 0) {
            displayApplications();
        }
        else if ($(this).html().indexOf("Discussions") >= 0) {
            displayDiscussions();
        }
        else if ($(this).html().indexOf("Messages") >= 0) {
            displayMessages();
        }
        else if ($(this).html().indexOf("Resumes") >= 0) {
            displayResumes();
        }
        else {
            displayLog();
        }
    });

    $edituser.click(function() {
        $edituser.hide();
        $submituser.show();
        $users.hide();
        $editusers.show();
    });

    $editjob.click(function() {
        $editjob.hide();
        $submitjob.show();
        $jobs.hide();
        $editjobs.show();
    });

    $editapplication.click(function() {
        $editapplication.hide();
        $submitapplication.show();
        $applications.hide();
        $editapplications.show();
    });

    $editdiscussion.click(function() {
        $editdiscussion.hide();
        $submitdiscussion.show();
        $discussions.hide();
        $editdiscussions.show();
    });

    $editmessage.click(function() {
        $editmessage.hide();
        $submitmessage.show();
        $messages.hide();
        $editmessages.show();
    });

    $editresume.click(function() {
        $editresume.hide();
        $submitresume.show();
        $resumes.hide();
        $editresumes.show();
    });

    $submituser.click(function () {
        if (!confirm("Are you sure you want to update all user data in the database?")) {
            return;
        }

        let form = $(".user-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/edituser.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch user update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch user update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Users");
                location.reload();
            }
        });
    });    
    
    $submitjob.click(function () {
        if (!confirm("Are you sure you want to update all job data in the database?")) {
            return;
        }
    
        let form = $(".job-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/editjob.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch job update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch job update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Jobs");
                location.reload();
            }
        });
    });    

    $submitapplication.click(function () {
        if (!confirm("Are you sure you want to update all application records in the database?")) {
            return;
        }
    
        let form = $(".application-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/editapplication.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch application update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch application update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Applications");
                location.reload();
            }
        });
    });

    $submitdiscussion.click(function () {
        if (!confirm("Are you sure you want to update all discussion records in the database?")) {
            return;
        }
    
        let form = $(".discussion-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/editdiscussion.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch discussion update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch discussion update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Discussions");
                location.reload();
            }
        });
    });    

    $submitmessage.click(function () {
        if (!confirm("Are you sure you want to update all messages in the database?")) {
            return;
        }
    
        let form = $(".message-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/editmessage.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch message update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch message update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Messages");
                location.reload();
            }
        });
    });    

    $submitresume.click(function () {
        if (!confirm("Are you sure you want to update all resumes in the database?")) {
            return;
        }
    
        let form = $(".resume-form");
        console.log(form.serialize());
    
        $.ajax({
            type: "POST",
            url: "php/editresume.php",
            data: form.serialize(),
            success: function (response) {
                console.log("Batch resume update success:", response);
            },
            error: function (xhr, status, error) {
                console.error("Batch resume update error:", error);
            },
            complete: function () {
                localStorage.setItem("selectedTab", "Resumes");
                location.reload();
            }
        });
    });    

    $searchlogbutton.click(function() {
        $searchlogbutton.hide();
        $searchbar.show();
        $searchlog.show();
    });

    $searchuserbutton.click(function() {
        $searchuserbutton.hide();
        $searchbar.show();
        $searchuser.show();
    });

    $searchjobbutton.click(function() {
        $searchjobbutton.hide();
        $searchbar.show();
        $searchjob.show();
    });

    $searchapplicationbutton.click(function() {
        $searchapplicationbutton.hide();
        $searchbar.show();
        $searchapplication.show();
    });

    $searchdiscussionbutton.click(function() {
        $searchdiscussionbutton.hide();
        $searchbar.show();
        $searchdiscussion.show();
    });

    $searchmessagebutton.click(function() {
        $searchmessagebutton.hide();
        $searchbar.show();
        $searchmessage.show();
    });

    $searchresumebutton.click(function() {
        $searchresumebutton.hide();
        $searchbar.show();
        $searchresume.show();
    });

    $deleteuser.click(function () {
        const uid = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this user?")) return;
    
        $.post("php/archiveuser.php", { uid: uid }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Users");
            location.reload();
        });
    });
    
    $deletejob.click(function () {
        const jid = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this job?")) return;
    
        $.post("php/archivejob.php", { jid: jid }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Jobs");
            location.reload();
        });
    });
    
    $deleteapplication.click(function () {
        const aid = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this application?")) return;
    
        $.post("php/archiveapplication.php", { aid: aid }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Applications");
            location.reload();
        });
    });
    
    $deletediscussion.click(function () {
        const did = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this discussion?")) return;
    
        $.post("php/archivediscussion.php", { did: did }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Discussions");
            location.reload();
        });
    });
    
    $deletemessage.click(function () {
        const mid = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this message?")) return;
    
        $.post("php/archivemessage.php", { mid: mid }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Messages");
            location.reload();
        });
    });
    
    $deleteresume.click(function () {
        const rid = $(this).data("id");
    
        if (!confirm("Are you sure you want to delete this resume?")) return;
    
        $.post("php/archiveresume.php", { rid: rid }, function (response) {
            console.log(response);
            localStorage.setItem("selectedTab", "Resumes");
            location.reload();
        });
    });       

    $searchlog.click(function() {
        
    });

    $searchuser.click(function() {
        
    });

    $searchjob.click(function() {
        
    });

    $searchapplication.click(function() {
        
    });

    $searchdiscussion.click(function() {
        
    });

    $searchmessage.click(function() {
        
    });

    $searchresume.click(function() {
        
    });

    $filterlog.click(function() {
        
    });

    $filteruser.click(function() {
        
    });

    $filterjob.click(function() {
        
    });

    $filterapplication.click(function() {
        
    });

    $filterdiscussion.click(function() {
        
    });

    $filtermessage.click(function() {
        
    });

    $filterresume.click(function() {
        
    });

    function displayLog() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.show();

        $users.hide();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.hide();
        $editjob.hide();
        $editapplication.hide();
        $editdiscussion.hide();
        $editmessage.hide();
        $editresume.hide();

        $filterlog.show();
        $filteruser.hide();
        $filterjob.hide();
        $filterapplication.hide();
        $filterdiscussion.hide();
        $filtermessage.hide();
        $filterresume.hide();

        $searchlogbutton.show();
        $searchuserbutton.hide();
        $searchjobbutton.hide();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.hide();
        $searchresumebutton.hide();
    }

    function displayUsers() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.show();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.show();
        $editjob.hide();
        $editapplication.hide();
        $editdiscussion.hide();
        $editmessage.hide();
        $editresume.hide();

        $filterlog.hide();
        $filteruser.show();
        $filterjob.hide();
        $filterapplication.hide();
        $filterdiscussion.hide();
        $filtermessage.hide();
        $filterresume.hide();

        $searchlogbutton.hide();
        $searchuserbutton.show();
        $searchjobbutton.hide();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.hide();
        $searchresumebutton.hide();
    }

    function displayJobs() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.hide();
        $editusers.hide();

        $jobs.show();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.hide();
        $editjob.show();
        $editapplication.hide();
        $editdiscussion.hide();
        $editmessage.hide();
        $editresume.hide();

        $filterlog.hide();
        $filteruser.hide();
        $filterjob.show();
        $filterapplication.hide();
        $filterdiscussion.hide();
        $filtermessage.hide();
        $filterresume.hide();

        $searchlogbutton.hide();
        $searchuserbutton.hide();
        $searchjobbutton.show();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.hide();
        $searchresumebutton.hide();
    }

    function displayApplications() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.hide();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.show();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.hide();
        $editjob.hide();
        $editapplication.show();
        $editdiscussion.hide();
        $editmessage.hide();
        $editresume.hide();

        $filterlog.hide();
        $filteruser.hide();
        $filterjob.hide();
        $filterapplication.show();
        $filterdiscussion.hide();
        $filtermessage.hide();
        $filterresume.hide();

        $searchlogbutton.hide();
        $searchuserbutton.hide();
        $searchjobbutton.hide();
        $searchapplicationbutton.show();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.hide();
        $searchresumebutton.hide();
    }

    function displayDiscussions() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.hide();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.show();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.hide();
        $editjob.hide();
        $editapplication.hide();
        $editdiscussion.show();
        $editmessage.hide();
        $editresume.hide();

        $filterlog.hide();
        $filteruser.hide();
        $filterjob.hide();
        $filterapplication.hide();
        $filterdiscussion.show();
        $filtermessage.hide();
        $filterresume.hide();

        $searchlogbutton.hide();
        $searchuserbutton.hide();
        $searchjobbutton.hide();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.show();
        $searchmessagebutton.hide();
        $searchresumebutton.hide();
    }

    function displayMessages() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.hide();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.show();
        $editmessages.hide();

        $resumes.hide();
        $editresumes.hide();

        $edituser.hide();
        $editjob.hide();
        $editapplication.hide();
        $editdiscussion.hide();
        $editmessage.show();
        $editresume.hide();

        $filterlog.hide();
        $filteruser.hide();
        $filterjob.hide();
        $filterapplication.hide();
        $filterdiscussion.hide();
        $filtermessage.show();
        $filterresume.hide();

        $searchlogbutton.hide();
        $searchuserbutton.hide();
        $searchjobbutton.hide();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.show();
        $searchresumebutton.hide();
    }

    function displayResumes() {
        $submituser.hide();
        $submitjob.hide();
        $submitapplication.hide();
        $submitdiscussion.hide();
        $submitmessage.hide();
        $submitresume.hide();

        $searchbar.hide();
        $searchlog.hide();
        $searchuser.hide();
        $searchjob.hide();
        $searchapplication.hide();
        $searchdiscussion.hide();
        $searchmessage.hide();
        $searchresume.hide();

        $log.hide();

        $users.hide();
        $editusers.hide();

        $jobs.hide();
        $editjobs.hide();

        $applications.hide();
        $editapplications.hide();
        
        $discussions.hide();
        $editdiscussions.hide();

        $messages.hide();
        $editmessages.hide();

        $resumes.show();
        $editresumes.hide();

        $edituser.hide();
        $editjob.hide();
        $editapplication.hide();
        $editdiscussion.hide();
        $editmessage.hide();
        $editresume.show();

        $filterlog.hide();
        $filteruser.hide();
        $filterjob.hide();
        $filterapplication.hide();
        $filterdiscussion.hide();
        $filtermessage.hide();
        $filterresume.show();

        $searchlogbutton.hide();
        $searchuserbutton.hide();
        $searchjobbutton.hide();
        $searchapplicationbutton.hide();
        $searchdiscussionbutton.hide();
        $searchmessagebutton.hide();
        $searchresumebutton.show();
    }
});