<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['uid'])) {
    die("You must log in to view this page");
}

$uid = $_SESSION['uid'];

$uid_query = $db->prepare("SELECT type FROM user WHERE uid = ?");
    $uid_query->bind_param("i", $uid);
    $uid_query->execute();
    $uid_query->bind_result($type);
    $uid_query->fetch();
    $uid_query->close();

if (!(htmlspecialchars($type) == "admin")) {
  die("You must be an administrator to view this page");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mploymint</title>
    <link rel="stylesheet" href="./css/admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  <body>
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="cont">
      <?php include "sidebar.php"; ?>
      <main class="layout">
          <div class="nav">
            <button class="button selected">Activity Log</button>
            <button class="button">Users (<span>12</span>)</button>
            <button class="button">Jobs (<span>35</span>)</button>
            <button class="button">Applications (<span>42</span>)</button>
            <button class="button">Discussions (<span>5</span>)</button>
            <button class="button">Messages (<span>2</span>)</button>
            <button class="button">Resumes (<span>1</span>)</button>
            <hr>
          </div>
          <div class="filters">
            <button id="submit-user" class="submit">Submit</button>
            <button id="submit-job" class="submit">Submit</button>
            <button id="submit-application" class="submit">Submit</button>
            <button id="submit-discussion" class="submit">Submit</button>
            <button id="submit-message" class="submit">Submit</button>
            <button id="submit-resume" class="submit">Submit</button>
            <button id="edit-user" class="button">Edit</button>
            <button id="edit-job" class="button">Edit</button>
            <button id="edit-application" class="button">Edit</button>
            <button id="edit-discussion" class="button">Edit</button>
            <button id="edit-message" class="button">Edit</button>
            <button id="edit-resume" class="button">Edit</button>
            <button id="filter-log" class="button">Filter</button>
            <button id="filter-user" class="button">Filter</button>
            <button id="filter-job" class="button">Filter</button>
            <button id="filter-application" class="button">Filter</button>
            <button id="filter-discussion" class="button">Filter</button>
            <button id="filter-message" class="button">Filter</button>
            <button id="filter-resume" class="button">Filter</button>
            <button id="search-log-button" class="button">Search</button>
            <button id="search-user-button" class="button">Search</button>
            <button id="search-job-button" class="button">Search</button>
            <button id="search-application-button" class="button">Search</button>
            <button id="search-discussion-button" class="button">Search</button>
            <button id="search-message-button" class="button">Search</button>
            <button id="search-resume-button" class="button">Search</button>
            <input id="searchbar" title="searchbar" type="text" placeholder="Search"/>
            <button id="search-log" class="submit">Submit</button>
            <button id="search-user" class="submit">Submit</button>
            <button id="search-job" class="submit">Submit</button>
            <button id="search-application" class="submit">Submit</button>
            <button id="search-discussion" class="submit">Submit</button>
            <button id="search-message" class="submit">Submit</button>
            <button id="search-resume" class="submit">Submit</button>
          </div>
          <div class="log">
          <table>
              <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Action</th>
                <th>Description</th>
                <th>Database Table</th>
                <th>Operation</th>
                <th>Previous Value</th>
                <th>New Value</th>
                <th>Timestamp</th>
              </tr>
              <?php
                $audit_query = $db->prepare("SELECT uid, email, action, description, db_table, operation, prev_value, new_value, timestamp FROM audit_log ORDER BY timestamp DESC");
                $audit_query->execute();
                $audit_query->bind_result($uid, $email, $action, $description, $db_table, $operation, $prev_value, $new_value, $timestamp);
                $hasRows = false;

                while ($audit_query->fetch()):
                  $hasRows = true;
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($uid); ?></td>
                    <td><?php echo htmlspecialchars($email); ?></td>
                    <td><?php echo htmlspecialchars($action); ?></td>
                    <td><?php echo htmlspecialchars($description); ?></td>
                    <td><?php echo htmlspecialchars($db_table); ?></td>
                    <td><?php echo htmlspecialchars($operation); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($prev_value)); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($new_value)); ?></td>
                    <td><?php echo date("m-d-Y H:i:s", strtotime($timestamp)); ?></td>
                  </tr>
              <?php endwhile; ?>
              <?php if (!$hasRows): ?>
                <tr>
                  <td colspan="10">No audit log entries found.</td>
                </tr>
              <?php endif; ?>
            </table>
            <?php $audit_query->close(); ?>
          </div>
          <div class="users">
            <table>
            <tr>
              <th>Email</th>
              <th>Name</th>
              <th>Type</th>
              <th>Password</th>
              <th>Profile Image</th>
              <th>Description</th>
            </tr>
            <?php
            $user_query = $db->prepare("SELECT uid, email, name, type, password, profileimg, description FROM user ORDER BY name ASC");
            $user_query->execute();
            $user_query->bind_result($uid, $email, $name, $type, $password, $profileimg, $description);
            $hasUsers = false;

            while ($user_query->fetch()):
              $hasUsers = true;
            ?>
              <tr>
                <td><a href="profile.php?uid=<?php echo urlencode($uid); ?>"><?php echo htmlspecialchars($email); ?></a></td>
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($type); ?></td>
                <td><?php echo htmlspecialchars($password); ?></td>
                <td><?php echo htmlspecialchars($profileimg); ?></td>
                <td><?php echo nl2br(htmlspecialchars($description)); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasUsers): ?>
              <tr>
                <td colspan="6">No users found.</td>
              </tr>
            <?php endif; ?>
          </table>
          <?php $user_query->close(); ?>
        </div>
        <div class="edit-users">
          <form class="user-form">
            <table>
              <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Type</th>
                <th>Password</th>
                <th>Profile Image</th>
                <th>Description</th>
              </tr>
              <?php
              $user_query = $db->prepare("SELECT uid, email, name, type, password, profileimg, description FROM user ORDER BY name ASC");
              $user_query->execute();
              $user_query->bind_result($uid, $email, $name, $type, $password, $profileimg, $description);
              $hasUsers = false;

              while ($user_query->fetch()):
                $hasUsers = true;
              ?>
                <tr>
                  <td><input type="email" name="users[<?php echo $uid; ?>][email]" value="<?php echo htmlspecialchars($email); ?>" /></td>
                  <td><input type="text" name="users[<?php echo $uid; ?>][name]" value="<?php echo htmlspecialchars($name); ?>" /></td>
                  <td><input type="text" name="users[<?php echo $uid; ?>][type]" value="<?php echo htmlspecialchars($type); ?>" /></td>
                  <td><input type="text" name="users[<?php echo $uid; ?>][password]" value="<?php echo htmlspecialchars($password); ?>" /></td>
                  <td><input type="text" name="users[<?php echo $uid; ?>][profileimg]" value="<?php echo htmlspecialchars($profileimg); ?>" /></td>
                  <td><textarea name="users[<?php echo $uid; ?>][description]"><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasUsers): ?>
                <tr>
                  <td colspan="6">No users found.</td>
                </tr>
              <?php endif; ?>
            </table>
            <?php $user_query->close(); ?>
          </form>
        </div>
        <div class="jobs">
          <table>
            <tr>
              <th>Title</th>
              <th>Company ID</th>
              <th>Company URL</th>
              <th>Category</th>
              <th>Type</th>
              <th>Location</th>
              <th>Salary</th>
              <th>Experience</th>
              <th>Application Deadline</th>
              <th>Application URL</th>
              <th>Description</th>
              <th>Requirements</th>
              <th>Responsibilities</th>
              <th>Status</th>
            </tr>
            <?php
            $job_query = $db->prepare("SELECT jid, cid, curl, title, category, type, location, salary, experience, appdeadline, appurl, description, requs, resps, status FROM job ORDER BY title ASC");
            $job_query->execute();
            $job_query->bind_result($jid, $cid, $curl, $title, $category, $type, $location, $salary, $experience, $appdeadline, $appurl, $description, $requs, $resps, $status);
            $hasJobs = false;

            while ($job_query->fetch()):
              $hasJobs = true;
            ?>
              <tr>
                <td><a href="job.php?jid=<?php echo urlencode($jid); ?>"><?php echo htmlspecialchars($title); ?></a></td>
                <td><?php echo htmlspecialchars($cid); ?></td>
                <td><?php echo htmlspecialchars($curl); ?></td>
                <td><?php echo htmlspecialchars($category); ?></td>
                <td><?php echo htmlspecialchars($type); ?></td>
                <td><?php echo htmlspecialchars($location); ?></td>
                <td><?php echo htmlspecialchars(number_format($salary)); ?></td>
                <td><?php echo nl2br(htmlspecialchars($experience)); ?></td>
                <td><?php echo $appdeadline ? date("m-d-Y H:i", strtotime($appdeadline)) : "N/A"; ?></td>
                <td><?php echo htmlspecialchars($appurl); ?></td>
                <td><?php echo nl2br(htmlspecialchars($description)); ?></td>
                <td><?php echo nl2br(htmlspecialchars($requs)); ?></td>
                <td><?php echo nl2br(htmlspecialchars($resps)); ?></td>
                <td><?php echo htmlspecialchars($status); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasJobs): ?>
              <tr>
                <td colspan="14">No jobs found.</td>
              </tr>
            <?php endif; ?>
          </table>
          <?php $job_query->close(); ?>
        </div>
        <div class="edit-jobs">
          <form class="job-form">
            <table>
              <tr>
                <th>Title</th>
                <th>Company ID</th>
                <th>Company URL</th>
                <th>Category</th>
                <th>Type</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Experience</th>
                <th>Application Deadline</th>
                <th>Application URL</th>
                <th>Description</th>
                <th>Requirements</th>
                <th>Responsibilities</th>
                <th>Status</th>
              </tr>
              <?php
              $job_query = $db->prepare("SELECT jid, cid, curl, title, category, type, location, salary, experience, appdeadline, appurl, description, requs, resps, status FROM job ORDER BY title ASC");
              $job_query->execute();
              $job_query->bind_result($jid, $cid, $curl, $title, $category, $type, $location, $salary, $experience, $appdeadline, $appurl, $description, $requs, $resps, $status);
              $hasJobs = false;

              while ($job_query->fetch()):
                $hasJobs = true;
              ?>
                <tr>
                  <td><input type="text" name="jobs[<?php echo $jid; ?>][title]" value="<?php echo htmlspecialchars($title); ?>" /></td>
                  <td><input type="number" name="jobs[<?php echo $jid; ?>][cid]" value="<?php echo htmlspecialchars($cid); ?>" /></td>
                  <td><input type="url" name="jobs[<?php echo $jid; ?>][curl]" value="<?php echo htmlspecialchars($curl); ?>" /></td>
                  <td><input type="text" name="jobs[<?php echo $jid; ?>][category]" value="<?php echo htmlspecialchars($category); ?>" /></td>
                  <td><input type="text" name="jobs[<?php echo $jid; ?>][type]" value="<?php echo htmlspecialchars($type); ?>" /></td>
                  <td><input type="text" name="jobs[<?php echo $jid; ?>][location]" value="<?php echo htmlspecialchars($location); ?>" /></td>
                  <td><input type="number" name="jobs[<?php echo $jid; ?>][salary]" value="<?php echo htmlspecialchars($salary); ?>" /></td>
                  <td><textarea name="jobs[<?php echo $jid; ?>][experience]"><?php echo htmlspecialchars($experience); ?></textarea></td>
                  <td><input type="datetime-local" name="jobs[<?php echo $jid; ?>][appdeadline]" value="<?php echo $appdeadline ? date('Y-m-d\TH:i', strtotime($appdeadline)) : ''; ?>" /></td>
                  <td><input type="url" name="jobs[<?php echo $jid; ?>][appurl]" value="<?php echo htmlspecialchars($appurl); ?>" /></td>
                  <td><textarea name="jobs[<?php echo $jid; ?>][description]"><?php echo htmlspecialchars($description); ?></textarea></td>
                  <td><textarea name="jobs[<?php echo $jid; ?>][requs]"><?php echo htmlspecialchars($requs); ?></textarea></td>
                  <td><textarea name="jobs[<?php echo $jid; ?>][resps]"><?php echo htmlspecialchars($resps); ?></textarea></td>
                  <td><input type="text" name="jobs[<?php echo $jid; ?>][status]" value="<?php echo htmlspecialchars($status); ?>" /></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasJobs): ?>
                <tr>
                  <td colspan="14">No jobs found.</td>
                </tr>
              <?php endif; ?>
            </table>
            <?php $job_query->close(); ?>
          </form>
        </div>
        <div class="applications">
          <table>
            <tr>
              <th>Application ID</th>
              <th>Job ID</th>
              <th>User ID</th>
              <th>Company ID</th>
              <th>Status</th>
            </tr>

            <?php
            $application_query = $db->prepare("SELECT aid, jid, uid, cid, status FROM application ORDER BY aid ASC");
            $application_query->execute();
            $application_query->bind_result($aid, $jid, $uid, $cid, $status);
            $hasApplications = false;

            while ($application_query->fetch()):
              $hasApplications = true;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($aid); ?></td>
                <td><?php echo htmlspecialchars($jid); ?></td>
                <td><?php echo htmlspecialchars($uid); ?></td>
                <td><?php echo htmlspecialchars($cid); ?></td>
                <td><?php echo htmlspecialchars($status); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasApplications): ?>
              <tr>
                <td colspan="5">No applications found.</td>
              </tr>
            <?php endif; ?>
          </table>
          <?php $application_query->close(); ?>
        </div>
        <div class="edit-applications">
          <form class="application-form">
            <table>
              <tr>
                <th>Application ID</th>
                <th>Job ID</th>
                <th>User ID</th>
                <th>Company ID</th>
                <th>Status</th>
              </tr>

              <?php
              $application_query = $db->prepare("SELECT aid, jid, uid, cid, status FROM application ORDER BY aid ASC");
              $application_query->execute();
              $application_query->bind_result($aid, $jid, $uid, $cid, $status);
              $hasApplications = false;

              while ($application_query->fetch()):
                $hasApplications = true;
              ?>
                <tr>
                  <td><input type="number" name="applications[<?php echo $aid; ?>][aid]" value="<?php echo htmlspecialchars($aid); ?>" readonly /></td>
                  <td><input type="number" name="applications[<?php echo $aid; ?>][jid]" value="<?php echo htmlspecialchars($jid); ?>" /></td>
                  <td><input type="number" name="applications[<?php echo $aid; ?>][uid]" value="<?php echo htmlspecialchars($uid); ?>" /></td>
                  <td><input type="number" name="applications[<?php echo $aid; ?>][cid]" value="<?php echo htmlspecialchars($cid); ?>" /></td>
                  <td><input type="text" name="applications[<?php echo $aid; ?>][status]" value="<?php echo htmlspecialchars($status); ?>" /></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasApplications): ?>
                <tr>
                  <td colspan="5">No applications found.</td>
                </tr>
              <?php endif; ?>
            </table>
            <?php $application_query->close(); ?>
          </form>
        </div>
        <div class="discussions">
          <table>
            <tr>
              <th>Title</th>
              <th>Creator ID</th>
              <th>Members</th>
              <th>Tag List</th>
              <th>Description</th>
            </tr>
            <?php
            $discussion_query = $db->prepare("SELECT did, title, creatorid, members, taglist, description FROM discussion ORDER BY title ASC");
            $discussion_query->execute();
            $discussion_query->bind_result($did, $title, $creatorid, $members, $taglist, $description);
            $hasDiscussions = false;

            while ($discussion_query->fetch()):
              $hasDiscussions = true;
            ?>
              <tr>
                <td><a href="discussion.php?did=<?php echo urlencode($did); ?>"><?php echo htmlspecialchars($title); ?></a></td>
                <td><?php echo htmlspecialchars($creatorid); ?></td>
                <td><?php echo nl2br(htmlspecialchars($members)); ?></td>
                <td><?php echo nl2br(htmlspecialchars($taglist)); ?></td>
                <td><?php echo nl2br(htmlspecialchars($description)); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasDiscussions): ?>
              <tr>
                <td colspan="5">No discussions found.</td>
              </tr>
            <?php endif; ?>
          </table>
          <?php $discussion_query->close(); ?>
        </div>
        <div class="edit-discussions">
          <form class="discussion-form">
            <table>
              <tr>
                <th>Title</th>
                <th>Creator ID</th>
                <th>Members</th>
                <th>Tag List</th>
                <th>Description</th>
              </tr>
              <?php
              $discussion_query = $db->prepare("SELECT did, title, creatorid, members, taglist, description FROM discussion ORDER BY title ASC");
              $discussion_query->execute();
              $discussion_query->bind_result($did, $title, $creatorid, $members, $taglist, $description);
              $hasDiscussions = false;

              while ($discussion_query->fetch()):
                $hasDiscussions = true;
              ?>
                <tr>
                  <td><input type="text" name="discussions[<?php echo $did; ?>][title]" value="<?php echo htmlspecialchars($title); ?>" /></td>
                  <td><input type="number" name="discussions[<?php echo $did; ?>][creatorid]" value="<?php echo htmlspecialchars($creatorid); ?>" /></td>
                  <td><textarea name="discussions[<?php echo $did; ?>][members]"><?php echo htmlspecialchars($members); ?></textarea></td>
                  <td><textarea name="discussions[<?php echo $did; ?>][taglist]"><?php echo htmlspecialchars($taglist); ?></textarea></td>
                  <td><textarea name="discussions[<?php echo $did; ?>][description]"><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasDiscussions): ?>
                <tr>
                  <td colspan="5">No discussions found.</td>
                </tr>
              <?php endif; ?>
            </table>
            <?php $discussion_query->close(); ?>
          </form>
        </div>
        <div class="messages">
          <table>
            <tr>
              <th>Discussion ID</th>
              <th>Sender ID</th>
              <th>Text</th>
              <th>Time Sent</th>
            </tr>
            <?php
            $message_query = $db->prepare("SELECT mid, did, senderid, text, timesent FROM message ORDER BY timesent DESC");
            $message_query->execute();
            $message_query->bind_result($mid, $did, $senderid, $text, $timesent);
            $hasMessages = false;

            while ($message_query->fetch()):
              $hasMessages = true;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($did); ?></td>
                <td><?php echo htmlspecialchars($senderid); ?></td>
                <td><?php echo nl2br(htmlspecialchars($text)); ?></td>
                <td><?php echo htmlspecialchars($timesent); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasMessages): ?>
              <tr><td colspan="4">No messages found.</td></tr>
            <?php endif; ?>
            <?php $message_query->close(); ?>
          </table>
        </div>
        <div class="edit-messages">
          <form class="message-form">
            <table>
              <tr>
                <th>Discussion ID</th>
                <th>Sender ID</th>
                <th>Text</th>
                <th>Time Sent</th>
              </tr>
              <?php
              $message_query = $db->prepare("SELECT mid, did, senderid, text, timesent FROM message ORDER BY timesent DESC");
              $message_query->execute();
              $message_query->bind_result($mid, $did, $senderid, $text, $timesent);
              $hasMessages = false;

              while ($message_query->fetch()):
                $hasMessages = true;
              ?>
                <tr>
                  <input type="hidden" name="messages[<?php echo $mid; ?>][mid]" value="<?php echo htmlspecialchars($mid); ?>" />
                  <td><input type="number" name="messages[<?php echo $mid; ?>][did]" value="<?php echo htmlspecialchars($did); ?>" /></td>
                  <td><input type="number" name="messages[<?php echo $mid; ?>][senderid]" value="<?php echo htmlspecialchars($senderid); ?>" /></td>
                  <td><textarea name="messages[<?php echo $mid; ?>][text]"><?php echo htmlspecialchars($text); ?></textarea></td>
                  <td><input type="datetime-local" name="messages[<?php echo $mid; ?>][timesent]" value="<?php echo date('Y-m-d\TH:i', strtotime($timesent)); ?>" /></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasMessages): ?>
                <tr><td colspan="4">No messages found.</td></tr>
              <?php endif; ?>
              <?php $message_query->close(); ?>
            </table>
          </form>
        </div>
        <div class="resumes">
          <table>
            <tr>
              <th>User ID</th>
              <th>Filename</th>
            </tr>
            <?php
            $resume_query = $db->prepare("SELECT rid, uid, filename FROM resume ORDER BY rid ASC");
            $resume_query->execute();
            $resume_query->bind_result($rid, $uid, $filename);
            $hasResumes = false;

            while ($resume_query->fetch()):
              $hasResumes = true;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($uid); ?></td>
                <td><?php echo htmlspecialchars($filename); ?></td>
              </tr>
            <?php endwhile; ?>
            <?php if (!$hasResumes): ?>
              <tr><td colspan="2">No resumes found.</td></tr>
            <?php endif; ?>
            <?php $resume_query->close(); ?>
          </table>
        </div>
        <div class="edit-resumes">
          <form class="resume-form">
            <table>
              <tr>
                <th>User ID</th>
                <th>Filename</th>
              </tr>
              <?php
              $resume_query = $db->prepare("SELECT rid, uid, filename FROM resume ORDER BY rid ASC");
              $resume_query->execute();
              $resume_query->bind_result($rid, $uid, $filename);
              $hasResumes = false;

              while ($resume_query->fetch()):
                $hasResumes = true;
              ?>
                <tr>
                  <input type="hidden" name="resumes[<?php echo $rid; ?>][rid]" value="<?php echo htmlspecialchars($rid); ?>" />
                  <td><input type="number" name="resumes[<?php echo $rid; ?>][uid]" value="<?php echo htmlspecialchars($uid); ?>" /></td>
                  <td><input type="text" name="resumes[<?php echo $rid; ?>][filename]" value="<?php echo htmlspecialchars($filename); ?>" /></td>
                </tr>
              <?php endwhile; ?>
              <?php if (!$hasResumes): ?>
                <tr><td colspan="2">No resumes found.</td></tr>
              <?php endif; ?>
              <?php $resume_query->close(); ?>
            </table>
          </form>
        </div>
    <div class="footer">
      <br>
    </div>
    <script src="js/admin.js"></script>
  </body>
</html>     