# Mploymint User Guide

## Introduction
Mploymint is a web-based job portal designed to connect jobseekers with employers. The platform provides a seamless experience for both jobseekers looking for employment opportunities and companies looking to hire talented individuals.

## Getting Started

### System Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- Internet connection
- XAMPP for local deployment

### Installation
1. Clone or download the Mploymint repository
2. Place the project folder in your XAMPP's htdocs directory
3. Import the database using the provided SQL file
4. Start Apache and MySQL services in XAMPP


## User Types
Mploymint supports two types of users:
- **Jobseekers**: Individuals looking for job opportunities
- **Companies**: Organizations posting job openings

## Landing Page
- Access the homepage to view a list of active job postings
- Job listings display titles, locations, contract types, and salaries
- Scroll through listings or use the search bar to find specific jobs by title
- Click the login button in the top navigation bar to access the login page

## Account Management

### Login
- Enter your email and password at the login page
- If you don't have an account, click the sign-up link
- The system automatically detects your account type based on your email:
  - Jobseekers are redirected to the job listings page
  - Companies are redirected to their job posting management page
- Example test account: test@gmail.com / p123

### Sign Up
- Fill in your name, email address, and password
- Select your account type (Jobseeker or Company)
- Optionally upload a profile picture
- Error messages will guide you if there are issues (e.g., email already exists)
- Upon successful registration, you'll be redirected to the login page

### Logout
- Click the logout button in the top navigation bar
- This ends your session and returns you to the landing page

## Navigation

### Top Navigation Bar
- Contains the Mploymint logo, navigation links, and logout button
- Consistent across all pages for easy navigation

### Sidebar
- Located on the left side after logging in
- Provides quick access to different sections of the platform
- Navigation options vary based on user type:
  - Jobseekers: Jobs, My Jobs List, Discussion, Profile
  - Companies: Jobs, Applicant List, Settings
- Displays user information and profile picture at the bottom
- If no profile picture is uploaded, displays user initials

## Jobseeker Features

### Profile Management
- Access your profile by clicking "Profile" in the sidebar
- View and edit personal information:
  - Name and email (read-only)
  - Phone number, location, bio, and skills (editable)
- Changes are saved immediately when you click "Save Changes"
- Success or error messages are displayed after submission

### Resume Management
- Upload your resume through the Profile page
- Supported format: PDF
- View your current resume or upload a new one
- Error messages guide you if there are issues with the upload

### Job Listings
- View all active job postings in a card format
- Each card displays:
  - Job title, company name, location, contract type, and salary
  - "Detail" button to view more information
  - "Apply" button for quick application
- Applied jobs show a green checkmark labeled "Applied"
- Use the search bar to find specific jobs by title

### Job Details
- Click the "Detail" button on a job card to view comprehensive information
- Includes full job description, requirements, and application details
- Apply to the job directly from the details page

### My Applied Jobs
- Access your applied jobs by clicking "My Jobs List" in the sidebar
- View all jobs you've applied to, including:
  - Job title, company name, and application status
  - "Detail" button to view job information
  - "Cancel" button to withdraw your application

### Discussion Forum
- Access by clicking "Discussion" in the sidebar
- Post messages, questions, or comments
- View all posts, including your own
- Search posts by content
- Delete only your own posts using the delete button

## Company Features

### Profile Management
- Access your company profile by clicking "Profile" in the sidebar
- View and edit company information:
  - Company name and email (read-only)
  - Phone number, location, and company description (editable)
- Changes are saved immediately when you click "Save Changes"

### Job Management
- View all your job postings by clicking "Jobs" in the sidebar
- Each job card displays:
  - Job title, location, contract type, and salary
  - "View" button to see detailed information
  - "Delete" button to archive the posting
- Use the search bar to filter job postings
- Click "Post a Job" button to create new job listings

### Creating Job Postings
- Click "Post a Job" button in the jobs page
- Fill in all required information:
  - Company details (name, website)
  - Job details (title, category, type, location, salary)
  - Requirements (experience, qualifications)
  - Application information (deadline, link)
  - Comprehensive job description
- Click "Post Job" to publish the listing
- Form validation ensures all information is correct

### Managing Applicants
- Access applicant lists by clicking "Applicant List" in the sidebar
- View all applicants for a specific job posting
- Information displayed includes:
  - Applicant name and email
  - Resume link for download and review
  - Application date
  - Current status
- Use the search bar to filter applicants

## Troubleshooting

### Common Issues
- **404 Error**: Check your URL path and ensure all files exist
- **500 Error**: Check error logs for PHP or server issues
- **Upload Errors**: Ensure file formats are supported and file sizes are reasonable
- **Database Errors**: Check your database connection and schema

### Error Messages
- The system provides specific error messages to help diagnose issues
- For resume uploads, error codes indicate the exact problem (invalid file type, permission issues, etc.)

