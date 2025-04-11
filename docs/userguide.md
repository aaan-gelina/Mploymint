# Mploymint User Walkthrough & Guide

## Introduction

Mploymint is a web-based job portal designed to connect jobseekers with employers. It provides a seamless experience for individuals seeking employment and for companies looking to post job opportunities and manage applicants.

---

## Getting Started

### System Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection
- XAMPP (for local deployment)

### Installation Steps
1. Clone or download the Mploymint repository.
2. Place the project folder inside your XAMPP `htdocs` directory.
3. Import the provided SQL file into your database using phpMyAdmin or the terminal.
4. Start Apache and MySQL services in XAMPP.

---

## User Types

Mploymint supports three user roles:
- **Jobseekers**: Can search and apply to job postings, manage their profile and resume, and participate in the discussion forum.
- **Companies**: Can post jobs, manage applicants, and update company details.
- **Admin**: Can manage all aspects of the site and view site logs.

---

## Landing Page

When users visit the homepage (`landing.php`), they see a list of active job postings. Each posting displays the job title, contract type, location, and salary. Users can scroll to browse more listings or search using the job title search bar. The top navigation bar includes a **Login** button, which directs to the login page.

---

## Account Management

### Login
Users enter their email and password to log in. If no account exists, they can click the **Sign-Up** link. The system checks the user type and redirects accordingly:
- **Jobseekers** are redirected to the job listings page.
- **Companies** are redirected to their job management dashboard.
- **Admins** are redirected to the admin panel.

### Sign Up
Users fill in their name, email, password, select their account type (Jobseeker or Company), and can optionally upload a profile picture. Validation ensures unique emails and appropriate input. On success, users are redirected to the login page.

### Logout
Clicking the **Logout** button in the top navigation ends the session and returns users to the landing page.

---

## Navigation

### Top Navigation Bar
Includes the Mploymint logo, dynamic buttons (Login, Logout, Post a Job), and links for quick access.

### Sidebar
Visible after login, located on the left side of the screen. It displays navigation links based on user type:
- **Jobseekers**: Jobs, My Jobs List, Discussion, Profile
- **Companies**: Jobs, Applicant List, Settings
- **Admins**: All of the above, plus Admin Dashboard

If a profile picture was uploaded, it is displayed in the sidebar. Otherwise, the user’s initials are shown.

---

## Jobseeker Experience

### Job Listings
Jobseekers are directed to a scrollable list of active job postings upon login. Each job card shows title, company, location, contract type, salary, a **Detail** button, and an **Apply** button. Applied jobs display a green “Applied” checkmark. Users can search postings by job title.

### Job Details
Clicking the **Detail** button opens the full job description, requirements, and application link or instructions. Applications can be submitted from this page.

### My Applied Jobs
Accessed via **My Jobs List** in the sidebar. Displays all jobs the user has applied to, including title, company name, and application status. Users can view details or cancel applications.

### Profile Management
Accessible via the **Profile** link in the sidebar. Allows editing of phone number, location, bio, and skills. Name and email fields are read-only. Changes are saved with AJAX and return success or error messages.

### Resume Management
Resumes can be uploaded through the **Profile** page. Only PDF format is supported. Users can view or replace their uploaded resume.

### Discussion Forum
Accessed via **Discussion** in the sidebar. Jobseekers can post messages, view all posts, search posts by content, and delete only their own messages. All forum activity is logged.

---

## Company Experience

### Job Management
Companies are redirected to their **Jobs** page after login. It shows all their job postings in card format. Each card displays job title, location, contract type, salary, and includes **View** and **Delete** (archive) buttons. The page includes a search bar to filter postings. The **Post a Job** button opens the job creation form.

### Creating Job Postings
Companies fill out job title, category, type, location, salary, description, requirements, application method, and deadline. Requirements are stored as delimited arrays. The form is validated client-side and submitted using prepared statements. On success, the user is redirected back to their job list.

### Applicant Management
Accessible via the **Applicant List** link in the sidebar. Displays all applicants for each job, including name, email, application date, and resume download link. Applicant profiles are view-only. Data is securely filtered based on job ownership and session.

### Company Profile
Accessible via the **Profile** link. Editable fields include phone, location, and company description. Company name and email are read-only. Profile image and description can be updated.

---

## Admin Panel

Admins access the admin panel via the sidebar. Users can navigate between views using the navigation bar at the top of the page.

The **Landing View** shows an audit log of all database changes (user activity).  
Default views display only non-archived records.

The **Edit View** allows inline editing and bulk form submission. Users can navigate between pages using **Edit** and **Submit** buttons.

**Delete** buttons in edit mode archive the selected data. A confirmation is required before any changes or deletions are applied.

Search and filter buttons are present in the interface but not fully functional.

---

## Settings (All Users)

Accessible via the **Settings** link in the sidebar. Allows users to:
- Edit name, email, password, and description
- Upload or change their profile photo
- Reset their password using the reset password link
- Update their image using the edit image link

---

## Troubleshooting

### Common Issues
- **404 Error**: Check file paths and routes
- **500 Error**: Inspect server logs and PHP syntax
- **Resume Upload Errors**: Only PDF format is supported
- **Database Errors**: Ensure the schema is correctly imported and connection credentials are accurate

### Error Messages
The system provides specific and descriptive error messages across all forms. Resume upload errors return detailed messages for invalid file format, size, or permission issues.




