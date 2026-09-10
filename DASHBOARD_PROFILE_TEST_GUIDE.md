# Dashboard & Profile Testing Guide

## Overview
This guide tests the implementation of the User Dashboard and Profile pages, including profile picture upload functionality.

## Database Setup
Before testing, ensure the database is set up:

```bash
cd SmartKitchen
php artisan migrate
```

## Test Cases

### Test 1: Guest Cannot Access Dashboard
**Flow:** Unauthenticated user attempts to access /dashboard

**Steps:**
1. Open browser and go to `http://localhost:8000/dashboard`
2. Expected: Redirected to `/login` page

**Expected Result:** ✓ Access denied, redirected to login

---

### Test 2: Guest Cannot Access Profile Show Page
**Flow:** Unauthenticated user attempts to access /profile/show

**Steps:**
1. Open browser and go to `http://localhost:8000/profile/show`
2. Expected: Redirected to `/login` page

**Expected Result:** ✓ Access denied, redirected to login

---

### Test 3: User Can Access Dashboard
**Flow:** Authenticated user accesses dashboard

**Steps:**
1. Register a new account or login with existing account
2. Navigate to `http://localhost:8000/dashboard` OR click "Dashboard" in navbar
3. Expected to see:
   - Welcome message with username
   - Stats cards showing: My Recipes, Total Likes, Followers, Following, Total Ratings, Average Rating
   - Quick Actions section with links to: Add Recipe, View Profile, Edit Profile
   - Recent Activity placeholder
   - Recommended recipes placeholder

**Expected Result:** ✓ Dashboard loads with all sections visible

**Responsive Design Check:**
- Desktop (1920px+): 3 columns for stats
- Tablet (768px): 2 columns for stats
- Mobile (375px): 1 column for stats
- All buttons and links clickable and properly sized

---

### Test 4: User Can Access Profile Show Page
**Flow:** Authenticated user accesses their public profile

**Steps:**
1. Login to account
2. Click "View Profile" in Dashboard OR navigate to `http://localhost:8000/profile/show`
3. Expected to see:
   - Profile header with: Profile picture, Name, Email, Bio (or "No bio yet")
   - Date joined in format "Month Year"
   - Edit Profile button
   - Profile stats: Recipes count, Followers count, Following count
   - About section with user bio
   - Recent Recipes section (placeholder if no recipes)
   - Achievements placeholder section

**Expected Result:** ✓ Profile page displays correctly

---

### Test 5: User Profile Picture Upload
**Flow:** User uploads a new profile picture

**Steps:**
1. Login to account
2. Click "Edit Profile" or navigate to `http://localhost:8000/profile`
3. Scroll to "Profile Picture" section
4. Current profile picture displays (or initial letter if none)
5. Click "Choose File" and select an image (PNG, JPG, or GIF, max 2MB)
6. Click "Upload Picture" button
7. Expected: Success message "Your profile picture has been updated successfully."
8. Navigate back to `/profile/show` or refresh edit page
9. Expected: New profile picture displays

**File Testing:**
- Valid: JPG, PNG, GIF (under 2MB)
- Invalid: BMP, WEBP (unsupported)
- Invalid: JPG over 2MB (too large)
- Invalid: Empty file

**Expected Result:** ✓ Picture uploads, stores in storage/app/public/profile_pictures/, displays on profile

---

### Test 6: User Can Edit Profile (Name, Email, Bio)
**Flow:** User edits profile information

**Steps:**
1. Login to account
2. Navigate to `http://localhost:8000/profile`
3. Update fields:
   - Name: Change to a new name
   - Email: Change to a new email (note: will require re-verification)
   - Bio: Add or update bio text
4. Click "Save Changes"
5. Expected: Success message "Your profile has been updated successfully."
6. Verify changes persist by navigating to `/profile/show`

**Validation Testing:**
- Name: Required, alphanumeric
- Email: Required, valid email format, unique
- Bio: Optional, max 500 characters

**Expected Result:** ✓ Changes saved and persist

---

### Test 7: User Can Change Password
**Flow:** User changes their password

**Steps:**
1. Login to account
2. Navigate to `http://localhost:8000/profile`
3. Click "Change Password" button
4. Enter current password, new password, and confirmation
5. Click "Update Password"
6. Expected: Success message
7. Logout and login with new password
8. Expected: Login successful with new password

**Expected Result:** ✓ Password changed successfully

---

### Test 8: Profile Picture Default Display
**Flow:** User without profile picture shows default

**Steps:**
1. Create a new account (no picture uploaded)
2. Go to `/profile/show`
3. Expected: Circular avatar with first letter of name (e.g., "J" for "John")
4. Upload a picture following Test 5
5. Expected: Picture replaces the default avatar

**Expected Result:** ✓ Default avatar shows, replaced when picture uploaded

---

### Test 9: Dashboard Stats Are Accurate
**Flow:** Verify dashboard statistics match actual data

**Prerequisites:** 
- Account with multiple recipes, followers, etc. (can manually edit database if needed for testing)

**Steps:**
1. Login to account
2. Navigate to `/dashboard`
3. Note the stats displayed
4. Compare with database:
   ```bash
   sqlite> SELECT COUNT(*) FROM recipes WHERE user_id = 1;
   sqlite> SELECT COUNT(*) FROM follows WHERE following_id = 1;
   ```
5. Expected: Stats match database values

**Expected Result:** ✓ Stats are accurate

---

### Test 10: Mobile Responsiveness
**Flow:** Verify responsive design on mobile devices

**Steps:**
1. Open browser DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Test on multiple sizes:
   - iPhone 12 (390px)
   - iPad (768px)
   - Desktop (1920px)

**Dashboard Check:**
- Menu collapses to hamburger on mobile
- Stats cards stack vertically
- Quick action cards stack properly
- All text is readable without horizontal scrolling

**Profile Check:**
- Profile header stacks vertically on mobile (picture top, info below)
- Stats cards stack vertically
- About section is readable
- Buttons are touch-friendly (min 44px height)

**Expected Result:** ✓ All pages responsive and functional

---

### Test 11: Navbar Navigation
**Flow:** Verify navbar links work correctly

**Steps:**
1. Login to account
2. Look at navbar
3. Click "Dashboard" → Should navigate to /dashboard
4. Click "Profile" → Should navigate to /profile
5. Click SmartKitchen logo → Should navigate to home
6. Click "Logout" → Should logout and redirect to home

**Expected Result:** ✓ All navbar links work correctly

---

### Test 12: Delete Account Flow
**Flow:** User can delete their account

**Steps:**
1. Login to account with a test email
2. Navigate to `/profile`
3. Scroll to "Danger Zone"
4. Click "Delete Account"
5. Confirm in popup dialog
6. Expected: Account deleted, redirect to home
7. Try logging in with deleted account email
8. Expected: Login fails

**Expected Result:** ✓ Account successfully deleted

---

## Browser Compatibility

Test on:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Performance Checks

- Dashboard loads in < 2 seconds
- Profile page loads in < 2 seconds
- Profile picture upload completes in < 5 seconds for 2MB file

## Security Checks

- Profile picture stored in non-public directory (storage/app/public)
- Profile picture accessible only via Storage::url()
- Validation prevents non-image files from uploading
- File size limited to 2MB
- CSRF protection on all forms
- Authentication required for dashboard and profile
- Old profile picture deleted when new one uploaded

---

## Test Results Summary

| Test | Status | Notes |
|------|--------|-------|
| 1. Guest Dashboard Access | | |
| 2. Guest Profile Access | | |
| 3. User Dashboard Access | | |
| 4. User Profile Show | | |
| 5. Profile Picture Upload | | |
| 6. Profile Edit (Name/Email/Bio) | | |
| 7. Change Password | | |
| 8. Default Profile Picture | | |
| 9. Dashboard Stats Accuracy | | |
| 10. Mobile Responsiveness | | |
| 11. Navbar Navigation | | |
| 12. Delete Account | | |

---

## Running the Application

```bash
cd SmartKitchen

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate

# Start development server
php artisan serve

# In another terminal, start storage link server (for profile pictures)
php artisan storage:link
```

Application will be available at `http://localhost:8000`
