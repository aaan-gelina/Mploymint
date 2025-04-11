# Project Feature Summary

---

## Landing Page

**Files**: `landing.php`, `landing-load-function.php`  
- Public homepage with a job search bar and job preview cards  
- Linked to `landing.css` and `landing.js` for improved UX  
- Loads all active, non-archived jobs from the database using `landing-load-function.php`

---

## Login System

**Files**: `login.php`, `login-function.php`  
- Renders the login form and displays error messages  
- Error query strings are cleared via JavaScript  
- Validates user credentials using prepared statements  
- Sets session variables (`loggedin`, `uid`, `name`, `type`) if valid  
- Redirects back to login page with error on failure  
- Limitation: Passwords are not hashed

---

## Signup System

**Files**: `signup.php`, `signup-function.php`  
- Signup form for name, email, password, and account type  
- Validates input and checks if email already exists  
- Inserts new user with default profile image if valid  
- Uses query strings to display error messages  
- Limitation: Passwords are stored in plain text

---

## Logout System

**File**: `logout-function.php`  
- Ends the session using `session_start()`, `session_unset()`, and `session_destroy()`  
- Redirects the user to `landing.php`

---

## Discussion Forum System

**Files**: `discussion.php`, `discussion-function.php`  
- Displays a forum interface for logged-in users  
- Allows post creation and deletion  
- Loads posts via `discussion-function.php`  
- All actions logged in the `audit_log` table  
- Limitation: No comment functionality

---

## Job List

**Files**: `joblist.php`, `joblist-function.php`  
- Jobseekers: search and apply for jobs  
- Companies: view their own job postings  
- Linked to JS and CSS for dynamic filtering by title  
- All applications and interactions are logged  
- Limitation: No advanced filters beyond job title search

---

## My Applied Job List

**Files**: `my_joblist.php`, `my_joblist-function.php`  
- Jobseekers: view applied jobs  
- Companies: view and archive their own postings  
- Includes a search bar and links to common styling/scripts  
- Logs all actions in `audit_log`  
- Limitation: Filtering not supported beyond basic search

---

## Sidebar

**File**: `sidebar.php`  
- Displays navigation options based on user type  
- Jobseekers: job listings, applications, forum, profile  
- Companies: job postings and applicant list  
- Displays logged-in user's name, email, and avatar initial  
- Refactored for reusability

---

## Topbar

**File**: `top-navbar.php`  
- Detects user type and displays appropriate buttons  
- Example: login, logout, post a job

---

## Create Job Page

- Accessible only to logged-in company users  
- Collects job title, category, type, location, salary, description, and requirements  
- Validates and sanitizes inputs  
- Stores job details using prepared statements  
- Requirements and responsibilities stored as delimited arrays  
- Redirects to job listings with confirmation  
- Logs action in `audit_log`

---

## Applicant List Page

- Accessible only to logged-in company users  
- Displays applicant name, email, resume (download/view), and application date  
- Fetches applicants from the database using job-company relation  
- Provides detailed profile view  
- Uses prepared statements and session validation  

---

## Profile Page

- Personalized dashboard to manage user info  
- Jobseekers: update professional details, upload resumes  
- Companies: view-only mode for applicant profiles  
- Features:
  - Conditional interface by user type  
  - Profile completeness indicator  
  - Resume upload and management  
  - Read-only applicant profile view  
  - Structured display of skills and experience  
- Integrated with `user` and `resume` tables  
- Session-based access and conditional rendering

---

## Admin Page

**Files**:  
- `admin.php`, `admin.css`, `admin.js`  
- `archiveuser.php`, `archivejob.php`, `archiveapplication.php`, etc.  
- `edituser.php`, `editjob.php`, `editapplication.php`, etc.

**Functionality**:  
- Manage Users, Jobs, Applications, Discussions, Messages, Resumes  
- View and edit tabs with persistent selection on refresh  
- AJAX-based form editing and confirmation popups  
- Edit view shows all records, submit logs changes to `audit_log`  
- Delete triggers archiving (`archive = 1`) and logs full record state  
- All forms are serialized and submitted with AJAX  
- Limitation: Search/filter buttons exist but logic not implemented

---

## Job Page

**Files**: `job.php`, `job.css`  
- Lists all job postings for logged-in users  
- Displays title, company, location, and description  
- Includes "Apply" button (disabled if already applied)  
- Submits application to `apply.php`, logs action in `audit_log`  
- JSON responses used for success/error messages

---

## Settings Page

**Files**: `settings.php`, `settings.js`, `settings.css`  
**Endpoints**: `updateuser.php`, `resetpassword.php`, `updateimage.php`

**Functionality**:  
- Edit user information (email, name, type, description)  
- Upload/change profile image  
- Reset password via separate form section  
- JS toggles form visibility  
- AJAX submission for updates  
- All changes logged in `audit_log`

---

## Database Design

- Built in PostgreSQL via pgAdmin  
- Database: `mploymintdb`  
- Core tables: `user`, `job`, `application`, `discussion`, `message`, `resume`, `audit_log`  
- Features:
  - Primary and foreign keys  
  - `archive` boolean flag for soft deletion  
  - Timestamp defaults (e.g., `NOW()`)  
  - `audit_log`: stores user ID/email, action type, old and new values in JSON  
- Designed for CRUD operations and full audit tracking  
- Integrated with all major application components

---

## GitHub Repository

- Structured and maintained project repository  
- Managed GitHub Project board for task tracking

---

## UBCO Server Deployment

- Configured environment using PuTTY and UBCO VPN  
- Uploaded all files and database schema (`mploymintdb.sql`)  
- Updated paths and environment configs for production  
- Debugged live errors and tested database connectivity  
- Verified:
  - AJAX functionality  
  - Public routing  
  - Feature completeness and error handling  
- Final deployment included clean version of all files

---

## Testing

- Implemented server-side error handling with try-catch and conditionals  
- Conducted client-side testing with varied input scenarios  
- Tested across:
  - Local Apache server  
  - UBCO production server  
- Database tested via:
  - Form submissions  
  - pgAdmin queries  
  - psql command line  
- Verified:
  - End-to-end feature integration  
  - Input validation  
  - Reliable data persistence

