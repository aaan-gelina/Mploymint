<aside class="sidebar" id="sidebar">
  <ul class="menu">
    <li><a href="#" class="menu-item"><i class="fas fa-briefcase"></i> Jobs</a></li>
    <li><a href="#" class="menu-item"><i class="fas fa-list"></i> My Jobs List</a></li>
    <li><a href="discussion.php" class="menu-item active"><i class="fas fa-comments"></i> Discussion</a></li>
  </ul>

  <div class="settings">
    <h4>SETTINGS</h4>
    <a href="settings.php" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
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
