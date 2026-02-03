This project is a **Laravel Passport-based REST API** developed during my internship.  
It focuses on secure authentication, scalable architecture, and clean coding practices.

### 🔐 Authentication & Authorization
- User registration, login, logout
- Access token & refresh token with expiry handling
- Profile view & update
- Email verification
- Password reset via email notification
- Token validation & expiration handling on frontend
- Resend reset link functionality
- Role & permission management using **Spatie Permissions**

### User Management
- User status system (Temporary → Permanent after 24 hours)
- Automated status update using **Laravel Command & Scheduler**
- Email notification sent after permanent status approval

### Product & Category Management
- Products CRUD
- Categories CRUD
- Product ↔ User relationship (**HasMany**)
- Product ↔ Category relationship (**Many-to-Many**)
- Soft delete implementation for products

### Image Management
- Polymorphic relationships (**MorphMany**) for:
  - Product images
  - Category images

### Architecture & Code Quality
- Service & Repository pattern
- Separate Form Request classes for validation
- Centralized failed validation base class
- Controllers return responses only
- Business logic handled in services
- API Resources & Collections for responses
- Database transactions with try-catch handling
- Clean and scalable folder structure

### Frontend Pages
- Password reset page
- Token expired page
- Resend reset link page  
(Frontend focused only on authentication recovery)

###  Tech Stack
- Laravel
- Laravel Passport
- MySQL
- Spatie Laravel Permission
- Notifications & Mail
- RESTful API architecture
