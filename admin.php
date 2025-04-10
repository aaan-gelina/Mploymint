<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

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
            <hr>
          </div>
          <div class="filters">
            <button id="submit" class="button">Submit</button>
            <button id="edit" class="button">Edit</button>
            <button id="filter" class="button">Filter</button>
            <button id="search" class="button">Search</button>
            <input id="searchbar" title="searchbar" type="text" placeholder="Search"/>
            <button id="submitsearch" class="button">Submit</button>
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
      </main>
    </div>
    <div class="footer">
      <br>
    </div>
    <script src="/js/admin.js"></script>
  </body>
</html>     <td>1</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>3</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>5</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>6</td>
            <td><a herf="">[Title]</a></td>
            <td>[example@email.com]</td>
            <td>[tag1, tag2, ... tagn]</td>
            <td><button class="button">...</button></td>
          </tr>
        </table>
      </div>
    </main>
    <div class="footer">
      <br>
    </div>
    <script src="./js/admin.js"></script>
  </body>
</html>
