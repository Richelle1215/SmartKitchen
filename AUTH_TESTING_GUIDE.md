# SmartKitchen Authentication System - Complete Testing Guide

**Status**: ✅ Ready for Testing  
**Date**: September 10, 2026  
**Server**: http://localhost:8000

---

## 📋 AUTHENTICATION SYSTEM OVERVIEW

### Components Implemented

✅ **Register** - New user account creation with validation  
✅ **Login** - Email/password authentication with remember me  
✅ **Logout** - Session destruction with security  
✅ **Forgot Password** - Password reset link via email  
✅ **Reset Password** - Update password with token validation  
✅ **User Profile** - Edit name, email, bio  
✅ **Change Password** - Secure password update with current password verification  
✅ **Email Verification** - Optional email verification after registration  

### Access Control

- **Guest Users**: Can access home, recipes, categories, shorts, AI
- **Authenticated Users**: Full access to dashboard, pantry, meal planner, profile
- **Protected Routes**: /dashboard, /profile, /pantry, /meal-planner redirect to login
- **After Login**: Redirects to intended page if applicable, otherwise dashboard

---

## 🧪 TEST 1: USER REGISTRATION

### Objective
Verify that new users can create accounts with validation

### Step-by-Step

**1. Navigate to Register Page**
```
URL: http://localhost:8000/register
Expected: Registration form displays
```

**2. Fill Form with Valid Data**
```
Name: John Doe
Email: john@example.com
Password: SecurePass123!
Confirm Password: SecurePass123!
```

**3. Submit Form**
```
Expected: 
- Form submits successfully
- User created in database
- Redirects to /dashboard
- User logged in automatically
- "Registered" role set
```

**4. Test Validation**

Try submitting with:
- **Empty fields** → Error message for each field
- **Short password** → "Password must be at least 8 characters"
- **Non-matching passwords** → "Passwords must match"
- **Invalid email** → "Invalid email format"
- **Duplicate email** → "Email already registered"

**Expected Result**: ✅ All validations work, error messages display

---

## 🧪 TEST 2: USER LOGIN

### Objective
Verify authentication with email and password

### Step-by-Step

**1. Register Test User First** (from Test 1)
```
Email: testuser@example.com
Password: TestPass123!
```

**2. Logout** (click logout in navbar dropdown)

**3. Navigate to Login**
```
URL: http://localhost:8000/login
Expected: Login form displays
```

**4. Enter Correct Credentials**
```
Email: testuser@example.com
Password: TestPass123!
```

**5. Submit Login**
```
Expected:
- Logs in successfully
- Redirects to /dashboard
- User session created
- "Remember me" checkbox available
```

**6. Test Remember Me**
```
- Check "Remember me"
- Close browser
- Clear cookies
- Reopen browser
- Visit http://localhost:8000
- Expected: User still logged in
```

**7. Test Invalid Credentials**
```
- Try wrong password
- Expected: Error message "Invalid credentials"
- Try non-existent email
- Expected: Error message "These credentials do not match our records"
```

**Expected Result**: ✅ Login works with proper validation and remember me functionality

---

## 🧪 TEST 3: LOGIN REDIRECT TO INTENDED PAGE

### Objective
Verify guest is redirected to originally requested page after login

### Step-by-Step

**1. Logout** (if logged in)

**2. Try to Access Protected Page**
```
URL: http://localhost:8000/dashboard
Expected: Redirected to /login with info message
```

**3. Login with Valid Credentials**
```
Email: testuser@example.com
Password: TestPass123!
```

**4. Check Redirect**
```
Expected: 
- After login, redirects to /dashboard (the page originally requested)
- NOT to home or default dashboard
```

**5. Test with Different Protected Pages**
```
Try accessing: /profile
Try accessing: /pantry
Try accessing: /meal-planner
Expected: Each one redirects to login, then back to the page after authentication
```

**Expected Result**: ✅ Return URL functionality works correctly

---

## 🧪 TEST 4: LOGOUT

### Objective
Verify session is destroyed and user is logged out

### Step-by-Step

**1. Login with Test Account**
```
Email: testuser@example.com
Password: TestPass123!
```

**2. Verify Logged In**
```
- Check navbar shows user avatar/name
- Should see user dropdown
```

**3. Click User Avatar → Logout**
```
Expected: User dropdown opens and shows logout option
```

**4. Click Logout**
```
Expected:
- Session destroyed
- Redirects to home (/)
- Navbar shows "Login" and "Register" buttons
- Cannot access /dashboard anymore (redirects to /login)
```

**Expected Result**: ✅ Logout works, session cleaned up

---

## 🧪 TEST 5: FORGOT PASSWORD - EMAIL RESET

### Objective
Verify password reset workflow with email

### Step-by-Step

**1. Navigate to Login**
```
URL: http://localhost:8000/login
```

**2. Click "Forgot Password?" Link**
```
Expected: Redirects to /forgot-password
```

**3. Enter Email Address**
```
Email: testuser@example.com
Expected: Form submits successfully
```

**4. Check Mail**
```
Note: If using mail system, check:
- storage/logs/laravel.log
- Or configured mail provider (Mailtrap, etc.)
- Look for "Reset Password Notification"
```

**5. Get Reset Link**
```
Extract token from email link, e.g.:
http://localhost:8000/reset-password/abc123token?email=testuser@example.com
```

**6. Follow Reset Link**
```
Expected: Redirects to /reset-password/{token}
Shows reset password form with email pre-filled
```

**7. Enter New Password**
```
New Password: NewPass456!
Confirm: NewPass456!
```

**8. Submit Form**
```
Expected:
- Password updated successfully
- Message: "Password reset successfully"
- Redirects to /login
```

**9. Login with New Password**
```
Email: testuser@example.com
Password: NewPass456!
Expected: Logs in successfully with new password
```

**Expected Result**: ✅ Password reset workflow complete

---

## 🧪 TEST 6: USER PROFILE EDITING

### Objective
Verify users can update profile information

### Step-by-Step

**1. Login**
```
Email: testuser@example.com
Password: (your current password)
```

**2. Navigate to Profile Edit**
```
URL: http://localhost:8000/profile
Or: Click "Edit Profile" in user dropdown
Expected: Profile edit form displays with current info
```

**3. Update Name**
```
Current: John Doe
New: Jane Smith
Click: Save Changes
Expected: Name updated, success message shown
```

**4. Update Email**
```
Current: john@example.com
New: jane@example.com
Click: Save Changes
Expected: 
- Email updated
- Message: "If email changed, re-verification required"
```

**5. Update Bio**
```
Enter: "I love cooking and sharing recipes!"
Click: Save Changes
Expected: Bio saved successfully
```

**6. Test Validation**
```
- Try saving with empty name
- Try saving with invalid email
- Expected: Validation errors display
```

**Expected Result**: ✅ Profile updates work with validation

---

## 🧪 TEST 7: CHANGE PASSWORD

### Objective
Verify secure password change with current password verification

### Step-by-Step

**1. Login**
```
Email: testuser@example.com
Password: NewPass456!
```

**2. Navigate to Profile**
```
URL: http://localhost:8000/profile
```

**3. Click "Change Password" Button**
```
URL: http://localhost:8000/profile/change-password
Expected: Password change form displays
```

**4. Enter Current Password (Incorrect)**
```
Current Password: WrongPass123!
New Password: AnotherPass789!
Confirm: AnotherPass789!
Click: Update Password
Expected: Error "Current password is incorrect"
```

**5. Enter Correct Current Password**
```
Current Password: NewPass456!
New Password: FinalPass000!
Confirm: FinalPass000!
Click: Update Password
Expected: Success message "Password updated successfully"
```

**6. Logout and Test New Password**
```
Click: Logout
Try: Login with old password (NewPass456!)
Expected: "Invalid credentials"
```

**7. Login with New Password**
```
Email: testuser@example.com
Password: FinalPass000!
Expected: Logs in successfully
```

**Expected Result**: ✅ Password change works securely

---

## 🧪 TEST 8: GUEST ACCESS RESTRICTIONS

### Objective
Verify guests cannot access protected routes

### Step-by-Step

**1. Make Sure You're Logged Out**
```
Logout if necessary
Check navbar shows "Login" and "Register"
```

**2. Try Accessing Protected Routes**
```
Try: http://localhost:8000/dashboard
Expected: Redirected to /login with message
         "Please login or create an account to use this feature."

Try: http://localhost:8000/profile
Expected: Same redirect behavior

Try: http://localhost:8000/pantry
Expected: Same redirect behavior

Try: http://localhost:8000/meal-planner
Expected: Same redirect behavior
```

**3. Verify Guest Can Access Public Routes**
```
Try: http://localhost:8000/ (home)
Expected: Accessible, shows home page

Try: http://localhost:8000/recipes
Expected: Accessible, shows recipes

Try: http://localhost:8000/categories
Expected: Accessible, shows categories

Try: http://localhost:8000/shorts
Expected: Accessible, shows shorts

Try: http://localhost:8000/ai-assistant
Expected: Accessible, shows limited AI features
```

**Expected Result**: ✅ Guest restrictions enforced correctly

---

## 🧪 TEST 9: EMAIL VERIFICATION (Optional)

### Objective
Verify email verification flow after registration

### Step-by-Step

**1. Register New Account**
```
From TEST 1, but check for email verification prompt
```

**2. After Registration**
```
Expected: Redirected to /verify-email
Shows: "Please verify your email address"
```

**3. Check Email**
```
Look in logs or mail provider for verification email
Click verification link in email
```

**4. Verification Complete**
```
Expected: Redirects to /dashboard
Message: "Email verified successfully"
Can now access all features
```

**Expected Result**: ✅ Email verification works (if enabled)

---

## 🧪 TEST 10: MOBILE RESPONSIVENESS

### Objective
Verify auth pages work on mobile devices

### Step-by-Step

**1. Open Auth Pages on Mobile**
```
Press F12 → Toggle Device Toolbar
Select: iPhone 12 or similar
```

**2. Test Each Auth Page**
```
Register (/register)
- Form fields stack vertically
- Button full width
- Text readable

Login (/login)
- Form fields stack vertically
- Button full width
- Forgot password link visible
- Remember me checkbox works

Forgot Password (/forgot-password)
- Fully responsive

Reset Password (/reset-password/{token})
- Fully responsive

Profile (/profile)
- All sections accessible
- Forms work

Change Password (/profile/change-password)
- Form fields readable
- Button clickable
```

**Expected Result**: ✅ All pages responsive on mobile

---

## 📊 COMPLETE TEST MATRIX

| Test # | Feature | Status | Notes |
|--------|---------|--------|-------|
| 1 | Register | ⬜ | Test all validations |
| 2 | Login | ⬜ | Test remember me |
| 3 | Return URL | ⬜ | Test redirect after login |
| 4 | Logout | ⬜ | Verify session cleanup |
| 5 | Forgot Password | ⬜ | Test email reset |
| 6 | Profile Edit | ⬜ | Test all fields |
| 7 | Change Password | ⬜ | Test current password check |
| 8 | Guest Restrictions | ⬜ | Test protected routes |
| 9 | Email Verification | ⬜ | Optional |
| 10 | Mobile | ⬜ | Test responsiveness |

---

## 🔐 SECURITY CHECKLIST

✅ Password hashing with bcrypt  
✅ CSRF protection on all forms  
✅ Session security (regenerate token on login/logout)  
✅ Password confirmation required on sensitive operations  
✅ Rate limiting on login attempts (optional)  
✅ Secure password reset tokens  
✅ Email verification (optional)  
✅ Account deletion requires password confirmation  
✅ Password requirements enforced (8 chars, numbers, symbols)  
✅ Protected routes use middleware  

---

## 🚀 COMMANDS FOR TESTING

### Reset Database for Testing
```powershell
cd "c:\Users\rm\Documents\HCI-FINAL_PROJECT\SmartKitchen"

# Fresh migrations
php artisan migrate:fresh

# Seed with test data
php artisan db:seed
```

### Create Test User Manually
```powershell
php artisan tinker

User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('TestPass123!'),
    'role' => 'registered',
    'email_verified_at' => now()
])
```

### View Server Logs
```powershell
Get-Content "c:\Users\rm\Documents\HCI-FINAL_PROJECT\SmartKitchen\storage\logs\laravel.log" -Tail 50
```

### Clear Caches
```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📋 TEST CREDENTIALS

**Test Account 1**:
```
Email: test@example.com
Password: TestPass123!
Role: registered
```

**Test Account 2**:
```
Email: john@example.com
Password: JohnPass456!
Role: registered
```

---

## ✅ SIGN-OFF

When all tests pass:

- [ ] TEST 1: Register ✅
- [ ] TEST 2: Login ✅
- [ ] TEST 3: Return URL ✅
- [ ] TEST 4: Logout ✅
- [ ] TEST 5: Forgot Password ✅
- [ ] TEST 6: Profile Edit ✅
- [ ] TEST 7: Change Password ✅
- [ ] TEST 8: Guest Restrictions ✅
- [ ] TEST 9: Email Verification ✅
- [ ] TEST 10: Mobile ✅

**Status**: Ready for Production ✅

---

**Last Updated**: September 10, 2026  
**By**: SmartKitchen Development Team

