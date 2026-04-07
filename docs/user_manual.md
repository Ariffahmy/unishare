# UniShare – User Manual

**System Name:** UniShare  
**Platform:** Web-based (Laravel)  
**Prepared For:** Final Year Project Report  
**Version:** 1.0  

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
   - 2.1 [Registering an Account](#21-registering-an-account)
   - 2.2 [Logging In](#22-logging-in)
   - 2.3 [Logging Out](#23-logging-out)
3. [Student User Guide](#3-student-user-guide)
   - 3.1 [Navigating the Dashboard](#31-navigating-the-dashboard)
   - 3.2 [Browsing & Searching Items](#32-browsing--searching-items)
   - 3.3 [Viewing an Item](#33-viewing-an-item)
   - 3.4 [Posting a New Item](#34-posting-a-new-item)
   - 3.5 [Managing Your Items](#35-managing-your-items)
   - 3.6 [Sending a Borrow Request](#36-sending-a-borrow-request)
   - 3.7 [Managing Borrow Requests (as Lender)](#37-managing-borrow-requests-as-lender)
   - 3.8 [Reporting Damage or Missing Item](#38-reporting-damage-or-missing-item)
   - 3.9 [Rating & Reviewing a Transaction](#39-rating--reviewing-a-transaction)
   - 3.10 [Messaging](#310-messaging)
   - 3.11 [Viewing Your Penalties](#311-viewing-your-penalties)
   - 3.12 [Viewing & Editing Your Profile](#312-viewing--editing-your-profile)
4. [Administrator User Guide](#4-administrator-user-guide)
   - 4.1 [Accessing the Admin Panel](#41-accessing-the-admin-panel)
   - 4.2 [Admin Dashboard Overview](#42-admin-dashboard-overview)
   - 4.3 [Managing Users](#43-managing-users)
   - 4.4 [Managing Items](#44-managing-items)
   - 4.5 [Monitoring Borrow Requests](#45-monitoring-borrow-requests)
   - 4.6 [Managing Penalties](#46-managing-penalties)
   - 4.7 [Viewing Reports & Analytics](#47-viewing-reports--analytics)
5. [Points System Overview](#5-points-system-overview)
6. [Penalty System Overview](#6-penalty-system-overview)

---

## 1. Introduction

UniShare is a web-based item-sharing platform designed for university students. It allows students to lend and borrow items from peers using a points-based system. Administrators oversee the platform by managing users, items, borrow requests, and penalties to ensure fair usage.

There are **two user roles** in UniShare:

| Role | Description |
|------|-------------|
| **Student** | Any registered university student who can lend or borrow items |
| **Administrator** | A privileged user who manages and monitors the entire platform |

---

## 2. Getting Started

### 2.1 Registering an Account

New users must create an account before using UniShare.

**Steps:**
1. Open your web browser and navigate to the UniShare URL.
2. On the landing page, click the **Register** link.
3. Fill in the registration form with the following details:
   - **Full Name** – Your full name
   - **Email Address** – A valid university email (must be unique)
   - **Phone Number** *(optional)* – Your phone number
   - **Password** – At least 8 characters
   - **Confirm Password** – Re-enter your password
4. Click the **Register** button to submit the form.
5. Upon successful registration, you will be automatically logged in and redirected to the Dashboard.

> **Note:** New accounts are granted **100 starting points** to begin using the platform.

---

### 2.2 Logging In

**Steps:**
1. Navigate to the UniShare login page (`/login`).
2. Enter your **Email Address** or **Phone Number** in the credential field.
3. Enter your **Password**.
4. *(Optional)* Tick the **Remember Me** checkbox to stay logged in.
5. Click **Sign In**.
6. You will be redirected to the Dashboard upon successful login.

> **Note:** If your credentials are incorrect, an error message will appear. Double-check your email/phone and password.

---

### 2.3 Logging Out

**Steps:**
1. Click your **name** in the top-right navigation bar to open the dropdown menu.
2. Click **Sign Out**.
3. You will be logged out and redirected to the landing page.

---

## 3. Student User Guide

### 3.1 Navigating the Dashboard

After logging in, you are taken to the **Dashboard**, which is the main page of UniShare.

The **navigation bar** at the top provides links to all main sections:

| Navigation Link | Description |
|-----------------|-------------|
| **Dashboard** | View all available items for borrowing |
| **Post Item** | List a new item for others to borrow |
| **My Items** | View and manage your own listed items |
| **My Requests** | View all your sent and received borrow requests |
| **Messages** | Access your conversations with other users |
| **Penalties** | View any penalties applied to your account |
| **[Your Name] ▾** | Dropdown menu for Profile, Admin Panel (if admin), and Sign Out |

Your current **points balance** is displayed in the top-right corner of the navigation bar (e.g., `100 pts`).

---

### 3.2 Browsing & Searching Items

The Dashboard displays all active items available for borrowing.

**Steps:**
1. Go to the **Dashboard** by clicking "Dashboard" in the navigation bar.
2. To **search** for a specific item:
   - Type a keyword into the **search bar** and press Enter or click the search icon.
3. To **filter by category**:
   - Click on a category filter (e.g., Books, Electronics, Sports) to show only items in that category.
4. Items are displayed as cards showing the item name, photo, points per day, and owner.
5. Click on any item card to view its full details.

---

### 3.3 Viewing an Item

**Steps:**
1. From the Dashboard, click on any item card.
2. The **Item Details** page shows:
   - **Photo gallery** – Click thumbnails to view additional photos
   - **Title & Description** – Full details about the item
   - **Points per day** – Cost to borrow per day
   - **Condition** – Physical condition of the item
   - **Max Duration** – Maximum number of days it can be borrowed
   - **Status** – Whether the item is Available or Unavailable
   - **Owner** – Name and rating of the item owner
   - **Reviews** – Past borrower reviews and star ratings
3. On the right panel, you can:
   - **Request to Borrow** the item (see Section 3.6)
   - **Message the Owner** to ask questions

---

### 3.4 Posting a New Item

Students can list their own items for others to borrow.

**Steps:**
1. Click **Post Item** in the navigation bar.
2. Fill in the item listing form:
   - **Title** *(required)* – Name of the item
   - **Category** – Select the most fitting category
   - **Description** – Describe the item in detail
   - **Condition** – State the current condition (e.g., Good, Fair)
   - **Points Per Day** *(required)* – How many points you charge per day (minimum 0)
   - **Max Borrow Duration** *(required)* – Maximum number of days (1–365)
   - **Photos** *(optional)* – Upload up to 5 photos (JPEG/PNG/JPG/GIF, max 5MB each)
3. Click **Post Item** to publish the listing.
4. You will be redirected to the Dashboard with a success message.

> **Tip:** Adding clear photos and a detailed description increases the likelihood of getting borrow requests.

---

### 3.5 Managing Your Items

**Steps:**
1. Click **My Items** in the navigation bar.
2. A list of all items you have posted will appear.
3. From this page you can:

   **Edit an Item:**
   - Click the **Edit** button on an item card.
   - Update any fields (title, description, condition, points, duration, or photos).
   - Click **Save Changes** to apply updates.

   **Delete a Photo:**
   - On the Edit Item page, find the photo you want to remove.
   - Click the **Delete** (trash) icon on that photo.

   **Activate / Deactivate an Item:**
   - Click the **Toggle Status** button (Active/Inactive) on an item.
   - An inactive item will not appear in the Dashboard for others to borrow.

   **Delete an Item:**
   - Click the **Delete** button on an item card.
   - Confirm the deletion when prompted.

> **Note:** You can only edit or delete items that you own.

---

### 3.6 Sending a Borrow Request

**Steps:**
1. Navigate to the item you wish to borrow (see Section 3.3).
2. On the **Item Details** page, locate the **Request to Borrow** panel on the right.
3. Select a **Start Date** – the date you want to start borrowing.
4. Select an **End Date** – the date you will return the item.
5. The system will automatically calculate and display:
   - **Duration** (number of days)
   - **Estimated Total Points** (duration × points per day)
6. Ensure you have enough points in your balance.
7. Click **Send Request** to submit the borrow request.
8. A success message will confirm the request was sent.

> **Note:** You cannot borrow your own items. The system will block duplicate bookings for the same dates.

---

### 3.7 Managing Borrow Requests (as Lender)

When another student sends you a borrow request for your item, you receive it under **My Requests**.

**Steps:**
1. Click **My Requests** in the navigation bar.
2. The page shows two tabs:
   - **Received Requests** – Requests sent to you (as the item owner/lender)
   - **Sent Requests** – Requests you have sent to others (as a borrower)

**Approving a Request:**
1. Under **Received Requests**, find the pending request.
2. Click **Approve**.
3. The system will automatically deduct points from the borrower and add them to your account.
4. The request status changes to **Approved**.

**Rejecting a Request:**
1. Under **Received Requests**, find the pending request.
2. Click **Reject**.
3. The request status changes to **Rejected**. No points are transferred.

**Marking as Borrowed (Item Picked Up):**
1. Once the borrower collects the item, click **Mark as Borrowed**.
2. The status changes to **Borrowed**.

**Marking as Returned:**
1. When the item is returned to you, click **Mark as Returned**.
2. The status changes to **Returned**.
3. If the item was returned **after the agreed end date**, a **late return penalty** is automatically applied to the borrower.

**Cancelling a Request:**
1. An approved request can be cancelled by either party by clicking **Cancel**.
2. Points are automatically refunded to the borrower.

---

### 3.8 Reporting Damage or Missing Item

As the item lender (owner), you can report if a returned item is damaged or if an item has gone missing.

**Reporting Damage:**
1. Go to **My Requests** and find the relevant borrow transaction.
2. Click **Report Damage**.
3. Fill in the form:
   - **Damage Description** *(required)* – Describe what was damaged
   - **Evidence Photo** *(required)* – Upload a photo of the damage (JPEG/PNG/JPG/WEBP, max 5MB)
4. Click **Submit Report**.
5. The report is submitted with **Pending** status. An administrator will review and approve or reject it.

**Reporting a Missing Item:**
1. Go to **My Requests** and find the relevant borrow transaction.
2. Click **Mark as Missing**.
3. Upload an **Evidence Photo** to support your report.
4. Click **Submit**.
5. The borrow request status changes to **Missing** and a pending penalty is created for admin review.

> **Important:** Damage and missing item penalties are **not applied immediately**. They require administrator approval before any points are deducted.

---

### 3.9 Rating & Reviewing a Transaction

After a borrow transaction is completed (marked as Returned), either party can leave a rating.

**Steps:**
1. Go to **My Requests** and find the completed transaction.
2. Click the **Rate** button next to the transaction.
3. On the Rating page:
   - Select a **Star Rating** (1–5 stars)
   - Write an optional **Comment/Review**
4. Click **Submit Rating**.
5. The review will appear on the item's detail page under the Reviews section.

---

### 3.10 Messaging

UniShare has a built-in messaging system to communicate with other users.

**Starting a Conversation from an Item Page:**
1. Navigate to any item detail page.
2. Click **Message Owner**.
3. A pre-filled message is sent and a conversation is started automatically.

**Viewing All Messages:**
1. Click **Messages** in the navigation bar.
2. All your conversations are listed.
3. A **red badge** on the Messages link indicates unread messages.

**Reading & Replying to a Message:**
1. Click on a conversation from the Messages list.
2. The full message thread is displayed.
3. Type your reply in the text box at the bottom.
4. Click **Send** to send your message.

---

### 3.11 Viewing Your Penalties

Penalties are deductions applied to your points balance for rule violations.

**Steps:**
1. Click **Penalties** in the navigation bar.
2. The Penalties page shows:
   - **Summary cards** – Total penalties, total points lost, late returns count, damaged/missing count
   - **Penalty list** – Each penalty with its type, reason, date, status, and points deducted

**Penalty Statuses:**

| Status | Meaning |
|--------|---------|
| **Pending Review** | Submitted by the lender; awaiting admin decision |
| **Active** | Approved by admin; points have been deducted |
| **Rejected** | Admin rejected the report; no points deducted |

**Penalty Types:**

| Type | Trigger | Points Deducted |
|------|---------|-----------------|
| Late Return | Auto-applied when item is returned late | Based on overdue days |
| Damaged | Lender reports damage (admin must approve) | Fixed penalty |
| Missing | Lender reports item missing (admin must approve) | Based on total borrow points |

> **Note:** A red badge on the Penalties navigation link indicates you have active penalties.

---

### 3.12 Viewing & Editing Your Profile

**Steps:**
1. Click your **name** in the top-right navigation bar.
2. Select **Profile** from the dropdown.
3. Your profile page shows your name, email, points balance, items listed, and reviews received.

**Editing Your Profile:**
1. On the Profile page, click **Edit Profile**.
2. Update your details (name, email, phone number, password).
3. Click **Save** to apply changes.

---

## 4. Administrator User Guide

> **Prerequisites:** You must have an account with `Administrator` privileges (`is_admin = true`). Contact a system owner to grant admin access.

---

### 4.1 Accessing the Admin Panel

**Steps:**
1. Log in with your administrator account.
2. Click your **name** in the top-right navigation bar.
3. Select **Admin Panel** (shown in purple with a gear icon) from the dropdown.
4. You will be redirected to the **Admin Dashboard** at `/admin/dashboard`.

> **Note:** Regular students will receive a **403 Unauthorized** error if they attempt to access any `/admin/*` URL directly.

---

### 4.2 Admin Dashboard Overview

The Admin Dashboard provides a real-time overview of the entire platform.

**Statistics Cards:**

| Statistic | Description |
|-----------|-------------|
| Total Users | Total number of registered accounts |
| Total Items | Total items listed on the platform |
| Active Items | Items currently available for borrowing |
| Total Requests | All borrow requests ever made |
| Pending Requests | Requests awaiting lender approval |
| Completed Transactions | Successfully returned borrow requests |
| Overdue Items | Items not returned past the agreed end date |
| Missing Items | Items reported as missing |
| Active Penalties | Penalties currently applied to users |
| Total Ratings | Total reviews submitted |
| Total Messages | Total messages sent on the platform |

**Recent Activity Feed:**
- Lists the **5 most recent** user registrations, item listings, and borrow requests.

**User Registration Chart:**
- A bar chart showing daily new user registrations for the past **7 days**.

---

### 4.3 Managing Users

**Steps to view the user list:**
1. From the Admin Panel, click **Users** in the admin navigation.
2. The Users page displays all registered accounts with their name, email, number of items, borrow requests, and reviews.

**Searching & Filtering:**
- Use the **search bar** to find a user by name or email.
- Use the **status filter** to show: All, Admin, Suspended, or Active users.

---

**Viewing a User's Profile:**
1. Click the **View** button next to a user.
2. The detailed profile shows:
   - Account info (name, email, phone, join date, points balance)
   - Item listings count
   - Borrow/lend history (last 10 transactions)
   - Ratings count

---

**Granting or Revoking Admin Privileges:**
1. On the User Details page, click **Toggle Admin**.
2. The user's role is updated immediately.
3. A success message confirms the change.

> **Restriction:** Admins cannot modify their own admin status.

---

**Suspending a User:**
1. On the User Details page, click **Suspend User**.
2. Enter an optional **Reason** for the suspension.
3. Click **Confirm Suspend**.
4. The user's account is suspended immediately and they cannot log in.

> **Restriction:** You cannot suspend another administrator. Remove their admin status first.

---

**Unsuspending a User:**
1. On a suspended user's profile, click **Unsuspend**.
2. The account is restored and the user can log in again.

---

**Manually Adjusting User Points:**
1. On the User Details page, find the **Adjust Points** form.
2. Enter a **point amount**:
   - Positive number to **add** points (e.g., `50`)
   - Negative number to **deduct** points (e.g., `-30`)
3. Enter an optional **Reason** for the adjustment.
4. Click **Adjust Points**.
5. The user's balance is updated immediately.

---

### 4.4 Managing Items

**Steps to view the item list:**
1. From the Admin Panel, click **Items** in the admin navigation.
2. All items on the platform are listed with their title, owner, category, and status.

**Searching & Filtering:**
- Use the **search bar** to find items by title or description.
- Use the **status filter** to show: All, Active, or Inactive items.
- Use the **category filter** to view items in a specific category.

---

**Toggling Item Status:**
1. Find the item in the list.
2. Click **Toggle Status** (Activate/Deactivate).
3. The item's visibility is updated immediately.
   - **Active** – Item appears on the Dashboard.
   - **Inactive** – Item is hidden from other users.

---

**Deleting an Item:**
1. Find the item in the list.
2. Click the **Delete** button.
3. Confirm the deletion.
4. The item is permanently removed from the platform.

> **Warning:** Deleting an item is irreversible. Ensure no active borrow requests are linked before deleting.

---

### 4.5 Monitoring Borrow Requests

**Steps:**
1. From the Admin Panel, click **Borrow Requests** in the admin navigation.
2. All borrow requests across the platform are listed.
3. Use the **status filter tabs** to narrow results:

| Status Filter | Description |
|---------------|-------------|
| All | Every borrow request |
| Pending | Awaiting lender approval |
| Approved | Lender approved, not yet picked up |
| Borrowed | Item currently with the borrower |
| Returned | Item successfully returned |
| Overdue | Past the end date and not returned |
| Missing | Reported as missing by the lender |
| Rejected | Lender rejected the request |
| Cancelled | Request cancelled after approval |

> **Note:** Admins have view-only access to borrow requests. Status changes are performed by the lender and borrower through their own accounts.

---

### 4.6 Managing Penalties

**Steps to view the penalty list:**
1. From the Admin Panel, click **Penalties** in the admin navigation.
2. All penalties are listed with their type, borrower, item, points, and status.
3. Use the **type filter** to view: All, Late Return, Damaged, or Missing.
4. Use the **status filter** to view: All, Pending, Active, or Rejected.

The top of the page shows:
- **Pending Approvals** – Number of penalties awaiting review
- **Total Active Penalty Points** – Sum of all active penalty deductions

---

**Approving a Penalty (Damage or Missing):**

> Late return penalties are automatic and do not require admin action.

1. Find a penalty with **Pending Review** status in the list.
2. Review the details:
   - Penalty type (Damaged or Missing)
   - Borrower name
   - Item name
   - Penalty points to be deducted
   - Reason and evidence photo submitted by the lender
3. Click **Approve**.
4. The system will:
   - **Deduct** the penalty points from the borrower's balance
   - **Compensate** the lender with the same amount of points (for Damaged and Missing only)
   - Mark the penalty as **Active**
5. A success message confirms the action.

---

**Rejecting a Penalty:**
1. Find a penalty with **Pending Review** status.
2. Click **Reject**.
3. The system will:
   - Mark the penalty as **Rejected**
   - **No points** are deducted from the borrower
   - If the penalty was for a Missing item, the borrow request status is reverted back to **Borrowed** or **Overdue**
4. A success message confirms the action.

---

### 4.7 Viewing Reports & Analytics

**Steps:**
1. From the Admin Panel, click **Reports** in the admin navigation.
2. The Reports page shows the following analytics:

**Top Lenders:**
- A ranked list of the top 10 users who have listed the most items.

**Top Borrowers:**
- A ranked list of the top 10 users with the most borrow requests.

**Most Popular Items:**
- A ranked list of the top 10 items that have received the most borrow requests.

**Category Distribution:**
- A breakdown of all items by category showing item count per category.

**Monthly Transactions:**
- A chart showing the number of borrow requests created each month for the **past 6 months**.

> **Use Case:** This section helps administrators identify platform trends, top contributors, and categories of most demand.

---

## 5. Points System Overview

UniShare uses a **points-based economy** to facilitate item sharing.

| Event | Points Effect |
|-------|---------------|
| New user registration | +100 points (welcome bonus) |
| Borrow request approved | Borrower is charged; Lender earns points |
| Borrow request cancelled | Full refund to borrower; Lender's points reversed |
| Late return | Automatic deduction from borrower |
| Damage penalty (admin approved) | Deducted from borrower; Lender compensated |
| Missing item penalty (admin approved) | Deducted from borrower; Lender compensated |
| Admin manual adjustment | Points added or deducted by admin |

---

## 6. Penalty System Overview

| Penalty Type | Trigger | Requires Admin Approval |
|---|---|:---:|
| **Late Return** | Item returned after end date | ❌ Automatic |
| **Damage** | Lender submits damage report with evidence | ✅ Yes |
| **Missing Item** | Lender marks item as missing with evidence | ✅ Yes |

**Penalty Workflow for Damage / Missing:**

```
Lender submits report (with evidence photo)
        ↓
Penalty created with "Pending Review" status
        ↓
Admin reviews the report & evidence
        ↓
    [Approved]              [Rejected]
        ↓                       ↓
Points deducted          No action taken
  from borrower       Penalty marked "Rejected"
Lender compensated
Penalty marked "Active"
```

---

*End of UniShare User Manual*
