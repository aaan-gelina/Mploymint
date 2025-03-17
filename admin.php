<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mploymint</title>
    <link rel="stylesheet" href="./css/admin.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  <body>
    <main class="layout">
        <div class="head-container">
          <h1 class="main-heading">Mploymint</h1>
        </div>
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
                <th>Operation</th>
                <th>Timestamp</th>
                <th>Event Initiated By</th>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
              <tr>
                <td>[Operation Name]</td>
                <td>[mm-dd-yyyy 00:00:00]</td>
                <td>[Initiated By...]</td>
              </tr>
            </table>
        </div>
        <div class="users">
          <table>
            <tr>
              <th>UID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Type</th>
              <th></th>
              <th></th>
            </tr>
            <tr>
              <td>1</td>
              <td><a herf="">[Firstname Lastname]</a></td>
              <td>[example@email.com]</td>
              <td>[Job Seeker]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td><a herf="">[Company Name]</a></td>
              <td>[company@email.com]</td>
              <td>[Company]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
            <tr>
              <td>3</td>
              <td><a herf="">[Firstname Lastname]</a></td>
              <td>[example@email.com]</td>
              <td>[Job Seeker]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
            <tr>
              <td>4</td>
              <td><a herf="">[Firstname Lastname]</a></td>
              <td>[admin@email.com]</td>
              <td>[Administrator]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
            <tr>
              <td>5</td>
              <td><a herf="">[Firstname Lastname]</a></td>
              <td>[example@email.com]</td>
              <td>[Job Seeker]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
            <tr>
              <td>6</td>
              <td><a herf="">[Firstname Lastname]</a></td>
              <td>[example@email.com]</td>
              <td>[Job Seeker]</td>
              <td id="password"><button class="button">Update Password...</button></td>
              <td><button class="button">...</button></td>
            </tr>
          </table>
      </div>
      <div class="jobs">
        <table>
          <tr>
            <th>JID</th>
            <th>Title</th>
            <th>Company Email</th>
            <th>Salary</th>
            <th>Description</th>
            <th></th>
          </tr>
          <tr>
            <td>1</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>3</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>5</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>6</td>
            <td><a herf="">[Job Title]</a></td>
            <td>[Company Email]</td>
            <td>[$0.00/hr]</td>
            <td>[Job Description]</td>
            <td><button class="button">...</button></td>
          </tr>
        </table>
      </div>
      <div class="applications">
        <table>
          <tr>
            <th>AID</th>
            <th>Job Title</th>
            <th>Applicant Email</th>
            <th>Company Email</th>
            <th>Status</th>
            <th></th>
          </tr>
          <tr>
            <td>1</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>2</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>3</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>5</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
          <tr>
            <td>6</td>
            <td>[Job Title]</td>
            <td>[example@email.com]</td>
            <td>[company@email.com]</td>
            <td>[Approved/Pending/Denied]</td>
            <td><button class="button">...</button></td>
          </tr>
        </table>
      </div>
      <div class="discussions">
        <table>
          <tr>
            <th>DID</th>
            <th>Title</th>
            <th>Creator Email</th>
            <th>Tags</th>
            <th></th>
          </tr>
          <tr>
            <td>1</td>
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
