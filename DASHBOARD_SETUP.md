# Dashboard & Profile Setup & Testing Guide

## Quick Start

### 1. Ensure Database is Set Up

```bash
cd SmartKitchen
php artisan migrate
```

### 2. Create Storage Link (Required for Profile Pictures)

```bash
php artisan storage:link
```

This creates a symlink from `public/storage` to `storage/app/public`, allowing profile pictures to be accessible via HTTP.

### 3. Start Development Server

```bash
php artisan serve
```

Application runs on `http://localhost:8000`

---

## Features Implemented

### Dashboard (`/dashboard`)
- **Welcome Message**: Personalized greeting with username
- **Statistics Cards**: 
  - My Recipes (count)
  - Total Likes (sum of likes on all recipes)
  - Followers (count)
  - Following (count)
  - Total Ratings (sum of ratings)
  - Average Rating (average of all recipe ratings)
- **Quick Actions**:
  - Add Recipe (placeholder)
  - View Profile (links to `/profile/show`)
  - Edit Profile (links to `/profile`)
- **Recent Activity Placeholder**: For future implementation
- **Recommended Recipes Placeholder**: For future implementation
- **Responsive Design**: Mobile, tablet, and desktop optimized

### Profile Show (`/profile/show`)
- **Profile Header**:
  - Profile picture (or default avatar with first letter)
  - Name
  - Email
  - Bio (or "No bio yet")
  - Date joined (Month Year format)
  - Edit Profile button
- **Profile Statistics**:
  - Recipe count
  - Followers count
  - Following count
- **About Section**: Display user bio
- **Recent Recipes Section**: Placeholder for user's recipes
- **Achievements Section**: Placeholder for future achievements
- **Responsive Design**: Mobile, tablet, and desktop optimized

### Profile Edit (`/profile`)
- **Profile Picture Upload**:
  - Current picture preview (or default avatar)
  - File input with validation
  - Success message on upload
  - Automatic old picture deletion
- **Edit Fields**:
  - Name (required)
  - Email (required, unique)
  - Bio (optional, textarea)
- **Password Management**: Link to change password page
- **Account Deletion**: Delete account with password confirmation

---

## File Structure

```
SmartKitchen/
├── app/Http/Controllers/
│   └── ProfileController.php ..................... Updated with new methods
├── routes/
│   ├── web.php .................................. Updated with new routes
│   └── auth.php .................................. Password reset routes
├── resources/views/
│   ├── components/
│   │   ├── stat-card.blade.php .................. Reusable stats component
│   │   ├── quick-action-card.blade.php ......... Reusable action card
│   │   └── profile-header.blade.php ............ Reusable profile header
│   ├── dashboard/
│   │   └── index.blade.php ..................... Dashboard view
│   ├── profile/
│   │   ├── show.blade.php ...................... Profile display view
│   │   ├── edit.blade.php ...................... Profile edit view
│   │   └── change-password.blade.php ........... Password change view
│   └── layouts/
│       └── app.blade.php ........................ Main layout
└── storage/
    └── app/public/profile_pictures/ ............ Profile picture storage
```

---

## Routes

### Public Routes
```
GET  /                                    → Home
GET  /login                               → Login page
POST /login                               → Login action
GET  /register                            → Register page
POST /register                            → Register action
POST /logout                              → Logout action
```

### Protected Routes (auth required)
```
GET  /dashboard                           → Redirect to profile.dashboard
GET  /profile/dashboard                   → Dashboard view
GET  /profile/show                        → Profile display
GET  /profile                             → Profile edit form
PATCH /profile                            → Update profile (name, email, bio)
POST /profile/upload-picture              → Upload profile picture
GET  /profile/change-password             → Change password form
PUT  /password                            → Update password
DELETE /profile                           → Delete account
```

---

## Database Schema

### Users Table (Existing)
```sql
id                      BIGINT PRIMARY KEY
name                    VARCHAR(255) REQUIRED
email                   VARCHAR(255) REQUIRED UNIQUE
password                VARCHAR(255) REQUIRED (hashed)
role                    VARCHAR(50) DEFAULT 'registered'
profile_picture         VARCHAR(255) NULLABLE
bio                     TEXT NULLABLE
email_verified_at       TIMESTAMP NULLABLE
created_at              TIMESTAMP
updated_at              TIMESTAMP
```

---

## Validation Rules

### Profile Update
- **name**: required, string, max:255
- **email**: required, email, unique:users (except own), max:255
- **bio**: nullable, string, max:500

### Profile Picture Upload
- **profile_picture**: required, image, mimes:jpeg,png,jpg,gif, max:2048 (2MB)

### Password Change
- **current_password**: required, must match authenticated user's password
- **password**: required, min:8, confirmed (must include uppercase, lowercase, number, special char per Password::defaults())

---

## Component Usage Examples

### Stat Card Component
```blade
<x-stat-card 
    label="My Recipes" 
    :value="$stats['total_recipes']" 
    icon="📚"
    bgColor="bg-orange-100"
    textColor="text-orange-600"
    subtext="Recipes you've shared"
/>
```

### Quick Action Card Component
```blade
<x-quick-action-card 
    href="{{ route('profile.show') }}" 
    icon="👤" 
    label="View Profile" 
    description="Check your public profile"
/>
```

### Profile Header Component
```blade
<x-profile-header :user="$user" :showEditButton="true" />
```

---

## Testing Quick Reference

### Test Guest Access Prevention
```bash
# Try accessing protected routes without login
curl http://localhost:8000/dashboard
# Expected: Redirect to /login
```

### Test User Dashboard
```bash
# Login first, then visit
http://localhost:8000/dashboard
```

### Test Profile Picture Upload
1. Go to `/profile`
2. Choose an image file (PNG, JPG, GIF, max 2MB)
3. Click "Upload Picture"
4. Check `/storage/app/public/profile_pictures/` for saved file

### Test Profile Stats
- Create test data in database or manipulate existing data
- Verify stats on dashboard match database

---

## Security Considerations

✓ Authentication required for all profile/dashboard routes
✓ Profile pictures stored outside web root (storage/app/public)
✓ File type validation (images only)
✓ File size limit (2MB)
✓ CSRF protection on all forms
✓ Password hashing (bcrypt)
✓ Old profile pictures deleted when replaced
✓ Email verification flag reset when email changed
✓ Password confirmation required for account deletion

---

## Responsive Breakpoints

- **Mobile**: < 640px (1 column layout)
- **Tablet**: 640px - 1024px (2 column layout)
- **Desktop**: > 1024px (3+ column layout)

All components use Tailwind CSS for responsive design.

---

## Known Limitations (By Design)

As per requirements, the following are placeholders for future implementation:
- Recipes functionality (Add Recipe, My Recipes)
- Followers/Following relationships (showing counts only)
- Achievements system
- Recent Activity logging
- Recommended recipes algorithm
- Social interactions

---

## Troubleshooting

### Profile Pictures Not Displaying
```bash
# Ensure storage link is created
php artisan storage:link

# Check storage permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Dashboard Stats Showing Zero
- Verify database migration ran: `php artisan migrate`
- Check user has recipes/followers in database

### Upload File Too Large Error
- Maximum file size is 2MB
- Reduce image size before uploading

### Email Change Requires Verification
- After changing email, user must verify new email
- Check email verification flow in auth.php

---

## Next Steps (Future Implementation)

1. Implement actual recipe management
2. Implement social features (followers, following, likes)
3. Implement achievements system
4. Implement activity logging
5. Implement recommendation engine
6. Add admin dashboard
7. Add notifications system
