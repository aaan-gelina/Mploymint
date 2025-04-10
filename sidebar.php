<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="./css/sidebar.css">
<aside class="sidebar" id="sidebar">
  <ul class="menu">
    <?php if ($_SESSION['type'] === 'company'): ?>
      <li><a href="./joblist.php?type=company" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
      <li><a href="./applicant_list.php" class="menu-item"><i class="fas fa-users"></i> Applicant List</a></li>
    <?php else: ?>
      <li><a href="./joblist.php" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
      <li><a href="./my_joblist.php" class="menu-item"><i class="fas fa-list"></i> My Jobs List</a></li>
      <li><a href="./discussion.php" class="menu-item"><i class="fas fa-comments"></i> Discussion</a></li>
      <li><a href="./profile.php" class="menu-item <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a></li>
    <?php endif; ?>
  </ul>

  <div class="settings">
    <h4>SETTINGS</h4>
    <a href="./settings.php" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
  </div>

  <div class="user-profile">
    <div class="avatar-initials">
      <?php echo strtoupper($user_name[0]); ?>
    </div>
    <div>
      <h5><?php echo $user_name; ?></h5>
      <p><?php echo $user_email; ?></p>
    </div>
  </div>
</aside>
