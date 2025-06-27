# 🎉 Event Registration Website

A simple event registration system built using **HTML**, **CSS**, **PHP**, and **MySQL**.

---

## 📌 Project Overview

This is a dummy event registration website designed to allow users to view event details, register for the event, and see the list of attendees. It also includes basic user authentication (login and signup) for admin access.

---

## 🗂️ Folder Structure

```
/event_registration_website/
├── /css/                    # Stylesheets
├── /images/                 # Images for the website
├── /config/                 # Database configuration
├── /pages/                  # Main website pages
├── index.php                # Homepage
└── README.md                # Project instructions
```

---

## 🌟 Features

- ✅ Homepage with event overview and CTA
- ✅ Detailed event information page
- ✅ Event registration form with PHP validation
- ✅ Attendees list display
- ✅ Registration confirmation page
- ✅ User signup and login pages (for admin access)
- ✅ Basic MySQL database integration

---

## 🛠️ Technologies Used

- **HTML5**
- **CSS3**
- **JavaScript**
- **PHP (Plain PHP, no frameworks)**
- **MySQL**

---

## 🗃️ Database Setup

1. Create a database called:  
```
event_registration
```

2. Import the provided SQL script(schema.sql) to create and populate tables


## ✅ How to Run Locally

1. Place the project folder in your **htdocs** (if using XAMPP) or your local web server directory.

2. Start **Apache** and **MySQL** from XAMPP.

3. Import the database into **phpMyAdmin**.

4. Open the website in your browser:  
```
http://localhost/event_registration_website/
```

---

## ✅ Pages Included

| Page | Description |
|---|---|
| `index.php` | Homepage with event overview |
| `/pages/event_details.php` | Full event details |
| `/pages/registration.php` | Event registration form |
| `/pages/attendees.php` | List of registered attendees |
| `/pages/confirmation.php` | Registration confirmation |
| `/auth/login.php` | Admin login page |
| `/auth/signup.php` | Admin signup page |

---

## ✨ Customization Ideas

- Add an admin dashboard for managing attendees.
- Send confirmation emails.
- Add ticket payment simulation.
- Style the website using frameworks like Bootstrap or Tailwind CSS.

---


## ✅ License
For educational/demo purposes only.
