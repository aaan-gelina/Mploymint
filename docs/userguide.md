# Mploymint User Walkthrough & Guide

## Introduction
Mploymint is a web-based job portal designed to connect jobseekers with employers. It provides a seamless experience for individuals seeking employment and for companies looking to post job opportunities and manage applicants.

---

## Getting Started

### System Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection
- XAMPP (for local deployment)

### Installation
1. Clone or download the Mploymint repository.
2. Place the project folder inside your XAMPP `htdocs` directory.
3. Import the provided SQL file into your database using phpMyAdmin or the terminal.
4. Start Apache and MySQL services in XAMPP.

---

## User Types
Mploymint supports two user roles:
- **Jobseekers**: Can search and apply to job postings, manage their profile and resume, and participate in the discussion forum.
- **Companies**: Can post jobs, manage applicants, and update company details.

---

## Landing Page
- When users visit the homepage (`landing.php`), they see a list of active job postings.
- Each posting displays the job title, contract type, location, and salary.
- Users can scroll to browse more listings or search using the job title search bar.
- The top navigation bar includes a **Login** button, which directs to the login page.

---

## Account Management

### Login
- Enter email and password to log in.
- If no account exists, click the **Sign Up** link.
- The system checks the user type and redirects accordingly:
  - **Jobseekers**: Redirected to the job listings page.
  - **Companies**: Redirected to their job management dashboard.
- Example test credentials: `test@gmail.com / p123`

### Sign Up
- Fill in name, email, password, account type (Jobseeker or Company), and optionally upload a profile picture.
- Validation ensures unique emails and appropriate input.
- On success, users are redirected to the login page.

### Logout
- Clicking the **Logout** button in the top navigation ends the session and returns users to the landing page.

---

## Navigation

### Top Navigation Bar
- Includes the Mploymint logo, dynamic buttons (e.g., Login, Logout, Post a Job), and links for quick access.

### Sidebar
- Visible after login, located on the left side of the screen.
- Displays navigation links based on user type:
  - **Jobseekers**: Jobs, My Jobs List, Discussion, Profile
  - **Companies**: Jobs, Applicant List, Settings
  - **Admins**: All of the above, plus Admin Dashboard
- Profile picture or initials are shown at the bottom of the sidebar.

---

## Jobseeker Experience

### Job Listings
- Jobseekers are directed to a scrollable list of active job postings upon login.
- Each job card shows:
  - Title, company, location, contract type, salary
  - **Detail** button for full description
  - **Apply** button to submit an application
- Applied jobs display a green “Applied” checkmark.
- Jobs can be searched by title.

### Job Details
- Clicking **Detail** opens the job detail page, showing:
  - Full description, requirements, application link or process
- Applications can be submitted from this page.

### My Applied Jobs
- Accessed via **My Jobs List** in the sidebar.
- Displays all jobs the user has applied to with:
  - Job title, company name, application status
  - **Detail** and **Cancel** buttons for managing applications

### Profile Management
- Accessible via **Profile** in the sidebar.
- Allows editing of:
  - Phone number, location, bio, and skills
- Name and email fields are read-only.
- Changes are saved via AJAX with success/error feedback.

### Resume Management
- Resume upload handled on the Profile page.
- Only PDF format is accepted.
- Uploaded resumes can be viewed or replaced.

### Discussion Forum
- Accessed via **Discussion** in the sidebar.
- Users can:
  - Post messages or questions
  - View and search all posts
  - Delete only their own messages
- All posts are displayed chronologically.

---

## Company Experience

### Job Management
- Upon login, companies are redirected to their **Jobs** page.
- Displays all their current job postings in card format.
- Each card shows:
  - Job title, location, contract type, salary
  - **View** and **Delete** (archive) buttons
- Search bar enables filtering by job attributes.

### Creating Job Postings
- Accessible via **Post a Job** in the top navigation.
- Form collects:
  - Job title, category, type, location, salary
  - Requirements, responsibilities (stored as delimited arrays)
  - Application method (deadline, link)
- Input is validated client-side and processed server-side using prepared statements.

### Applicant Management
- Accessible via **Applicant List** in the sidebar.
- For each job posting, view a list of applicants:
  - Name, email, resume link, application date, and status
- Applicant details are view-only.
- Data is filtered based on job ownership and user session.

### Company Profile
- Accessible via **Profile** in the sidebar.
- Fields for phone, location, and company description are editable.
- Company name and email are read-only.
- Profile image and description can be updated.

---

## Admin Panel

### Access
- Only visible in the sidebar for users with admin privileges.

### Functionality
- Full database control via `admin.php` with CRUD support for:
  - Users, Jobs, Applications, Discussions, Messages, Resumes
- Features:
  - Tabbed view with persistent state
  - Editable table rows
  - Archive buttons that trigger `archive<Entity>.php`
  - All edits submitted via AJAX to `edit<Entity>.php`
  - Full audit logging (JSON snapshot of old/new values)
- A log of all database changes is available.
- Search and filter buttons are present but filtering was not fully implemented.

---

## Settings (All Users)

- Accessible via **Settings** in the sidebar.
- Features:
  - Edit name, email, password, description
  - Upload/change profile image
- JavaScript handles form toggling and visibility
- Submissions handled via AJAX and logged to `audit_log`

---

## Troubleshooting

### Common Issues
- **404 Error**: Check file paths and routing
- **500 Error**: Inspect server logs and PHP syntax
- **Resume Upload Error**: Only PDF format supported
- **Database Errors**: Ensure connection settings are correct and schema is imported

### Error Messages
- User-friendly errors provided throughout the platform
- Resume upload issues return specific error codes for invalid format or permission problems



