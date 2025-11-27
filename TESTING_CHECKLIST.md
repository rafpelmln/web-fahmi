# 🧪 TESTING CHECKLIST - PORTFOLIO SYSTEM

## ✅ Pre-Launch Verification

Pastikan semua item di checklist ini completed sebelum production deploy.

---

## 🔑 Test Login Credentials

```
👨‍💼 Admin Account:
   Email:    admin@example.com
   Password: password
   Role:     admin

👨‍🏫 Teacher Account:
   Email:    guru@example.com
   Password: password
   Role:     teacher

👨‍🎓 Student Account:
   Email:    siswa@example.com
   Password: password
   Role:     student
```

---

## 📋 Test Scenarios

### A. Authentication Tests

- [ ] **Login as Admin**
  - [ ] Open http://127.0.0.1:8000/login
  - [ ] Enter admin@example.com
  - [ ] Enter password
  - [ ] Click Login
  - [ ] Verify redirected to /portfolios
  - [ ] Verify navbar shows "Admin User"

- [ ] **Login as Teacher**
  - [ ] Login dengan guru@example.com
  - [ ] Verify role badge shows "Teacher"
  - [ ] Verify can see portfolios list

- [ ] **Login as Student**
  - [ ] Login dengan siswa@example.com
  - [ ] Verify role badge shows "Student"
  - [ ] Verify can only see own portfolios

- [ ] **Logout**
  - [ ] Click user dropdown
  - [ ] Click Logout
  - [ ] Verify redirected to login page
  - [ ] Verify session cleared

- [ ] **Register New User**
  - [ ] Click "Don't have account?" link
  - [ ] Fill registration form
  - [ ] Verify new user created
  - [ ] Verify default role is "student"

---

### B. Portfolio List Tests

- [ ] **View Portfolio List**
  - [ ] Login as student
  - [ ] Click "Portfolios" in navbar
  - [ ] Verify list shows portfolios
  - [ ] Verify pagination works
  - [ ] Verify status badges display correctly

- [ ] **Search Functionality**
  - [ ] Enter search term in search box
  - [ ] Verify results filtered
  - [ ] Test with different keywords
  - [ ] Verify XSS protection (try `<script>alert()</script>`)

- [ ] **Filter by Status**
  - [ ] Select "Pending" filter
  - [ ] Verify only pending portfolios shown
  - [ ] Select "Approved" filter
  - [ ] Verify only approved portfolios shown
  - [ ] Select "Rejected" filter
  - [ ] Verify only rejected portfolios shown

- [ ] **Filter by Type**
  - [ ] Select "Prestasi" filter
  - [ ] Verify only prestasi portfolios shown
  - [ ] Test other type filters

- [ ] **Pagination**
  - [ ] Verify page numbers display
  - [ ] Click next page
  - [ ] Verify content changes
  - [ ] Click previous page
  - [ ] Verify content changes back

---

### C. Create Portfolio Tests

- [ ] **Access Create Form**
  - [ ] Login as student
  - [ ] Click "Add Portfolio" button
  - [ ] Verify form loads
  - [ ] Verify all fields present

- [ ] **Valid Form Submission**
  - [ ] Fill title (min 3 chars)
  - [ ] Fill description (min 10 chars)
  - [ ] Select type from dropdown
  - [ ] Upload file (PDF/JPG/PNG)
  - [ ] Click "Create"
  - [ ] Verify success message
  - [ ] Verify redirected to show page

- [ ] **Title Validation**
  - [ ] Try submit with empty title
  - [ ] Verify error message shows
  - [ ] Try submit with 2 chars
  - [ ] Verify error "minimum 3 characters"

- [ ] **Description Validation**
  - [ ] Try submit with empty description
  - [ ] Verify error shows
  - [ ] Try submit with 5 chars
  - [ ] Verify error "minimum 10 characters"

- [ ] **File Validation**
  - [ ] Try submit without file
  - [ ] Verify error "file required"
  - [ ] Upload .txt file
  - [ ] Verify error "only PDF/JPG/PNG"
  - [ ] Upload 10MB+ file
  - [ ] Verify error "max 5MB"

- [ ] **File Upload Success**
  - [ ] Upload valid PDF
  - [ ] Verify file saved to storage
  - [ ] Verify filename format: `timestamp_slug.extension`

---

### D. View Portfolio Details

- [ ] **View Portfolio as Owner**
  - [ ] Login as student
  - [ ] Click portfolio title
  - [ ] Verify all details display
  - [ ] Verify file download link works
  - [ ] Verify status badge shows

- [ ] **View Portfolio as Teacher**
  - [ ] Login as teacher
  - [ ] View student's portfolio
  - [ ] Verify can see all details
  - [ ] Verify can see verify form
  - [ ] Verify "Approve" button present
  - [ ] Verify "Reject" button present

- [ ] **View Timeline**
  - [ ] Check portfolio with approval
  - [ ] Verify timeline shows
  - [ ] Verify verified_by shows username
  - [ ] Verify verified_at shows timestamp

---

### E. Edit Portfolio Tests

- [ ] **Edit Pending Portfolio**
  - [ ] Create new portfolio
  - [ ] Click "Edit" button
  - [ ] Modify title
  - [ ] Modify description
  - [ ] Upload new file
  - [ ] Click "Update"
  - [ ] Verify changes saved

- [ ] **Cannot Edit Approved Portfolio**
  - [ ] Find approved portfolio
  - [ ] Verify "Edit" button NOT visible
  - [ ] Try accessing edit URL directly
  - [ ] Verify 403 error or redirect

- [ ] **Cannot Edit Rejected Portfolio**
  - [ ] Find rejected portfolio
  - [ ] Verify "Edit" button NOT visible
  - [ ] Verify cannot modify

---

### F. Delete Portfolio Tests

- [ ] **Delete Pending Portfolio**
  - [ ] Create test portfolio
  - [ ] Click delete button
  - [ ] Verify confirmation modal shows
  - [ ] Click confirm
  - [ ] Verify portfolio deleted
  - [ ] Verify success message

- [ ] **Cannot Delete Approved Portfolio**
  - [ ] Find approved portfolio
  - [ ] Verify "Delete" button NOT visible
  - [ ] Try accessing delete via URL
  - [ ] Verify 403 error

---

### G. Verification Tests (Teacher/Admin)

- [ ] **Approve Portfolio**
  - [ ] Login as teacher
  - [ ] View pending portfolio
  - [ ] Click "Approve"
  - [ ] Verify status changes to "Approved"
  - [ ] Verify verified_by shows teacher name
  - [ ] Verify verified_at shows timestamp

- [ ] **Reject Portfolio**
  - [ ] Login as teacher
  - [ ] View pending portfolio
  - [ ] Click "Reject"
  - [ ] Verify status changes to "Rejected"
  - [ ] Verify verified_by shows teacher name

- [ ] **Cannot Verify as Student**
  - [ ] Login as student
  - [ ] View own portfolio
  - [ ] Verify verify form NOT visible

---

### H. Authorization Tests

- [ ] **Student Authorization**
  - [ ] Login as student siswa@example.com
  - [ ] Try access admin routes
  - [ ] Verify cannot access other students' portfolios
  - [ ] Try accessing /portfolios - OK ✅
  - [ ] Try accessing other student's detail - 403 ❌

- [ ] **Teacher Authorization**
  - [ ] Login as teacher guru@example.com
  - [ ] Verify can see all portfolios
  - [ ] Try create portfolio - can create ✅
  - [ ] Try delete approved portfolio - 403 ❌

- [ ] **Admin Authorization**
  - [ ] Login as admin admin@example.com
  - [ ] Verify can access all features
  - [ ] Verify can create/edit/delete any portfolio
  - [ ] Verify can verify any portfolio

---

### I. File Storage Tests

- [ ] **File Save Location**
  - [ ] Create portfolio with file
  - [ ] Check file exists in storage/app/public/portfolios/
  - [ ] Verify filename format correct

- [ ] **File Access via URL**
  - [ ] View portfolio detail
  - [ ] Click download file link
  - [ ] Verify file downloads correctly
  - [ ] Verify file content intact

- [ ] **Storage Link**
  - [ ] Verify `/storage` symlink exists
  - [ ] Verify can access via http://127.0.0.1:8000/storage/portfolios/...

---

### J. UI/UX Tests

- [ ] **Responsive Design**
  - [ ] Test on desktop (1920x1080)
  - [ ] Test on tablet (768x1024)
  - [ ] Test on mobile (375x667)
  - [ ] Verify navbar responsive
  - [ ] Verify forms responsive
  - [ ] Verify tables responsive

- [ ] **Flash Messages**
  - [ ] Create portfolio
  - [ ] Verify success message shows
  - [ ] Verify auto-disappears after 5s
  - [ ] Test error message display

- [ ] **Navigation**
  - [ ] Verify navbar links work
  - [ ] Verify active link highlighted
  - [ ] Verify mobile menu works
  - [ ] Verify breadcrumbs (if any)

- [ ] **Icons & Colors**
  - [ ] Verify status badges colored correctly
  - [ ] Green for Approved ✅
  - [ ] Yellow for Pending ⏳
  - [ ] Red for Rejected ❌

---

### K. Database Tests

- [ ] **Data Persistence**
  - [ ] Create portfolio
  - [ ] Refresh page
  - [ ] Verify data still there
  - [ ] Restart server
  - [ ] Verify data persists

- [ ] **Foreign Keys**
  - [ ] Delete student
  - [ ] Verify portfolios deleted (cascade)
  - [ ] Verify no orphaned records

- [ ] **Timestamps**
  - [ ] Create portfolio
  - [ ] Verify created_at set
  - [ ] Verify updated_at set
  - [ ] Edit portfolio
  - [ ] Verify updated_at changed

---

### L. Error Handling Tests

- [ ] **404 Errors**
  - [ ] Try access non-existent portfolio
  - [ ] Verify 404 page shows
  - [ ] Try access non-existent route
  - [ ] Verify 404 page shows

- [ ] **403 Errors**
  - [ ] Try access other student's portfolio
  - [ ] Verify 403 error shows
  - [ ] Try delete approved portfolio
  - [ ] Verify 403 error shows

- [ ] **Validation Errors**
  - [ ] Submit empty form
  - [ ] Verify validation messages show
  - [ ] Verify form preserved with values

---

### M. Performance Tests

- [ ] **Page Load Time**
  - [ ] Portfolio list: < 500ms
  - [ ] Portfolio detail: < 500ms
  - [ ] Create form: < 300ms

- [ ] **Search Performance**
  - [ ] Search with 8 items: instant
  - [ ] Verify no timeout

- [ ] **File Upload Performance**
  - [ ] Upload 5MB file: < 3s
  - [ ] Upload 500KB file: < 1s

---

### N. Security Tests

- [ ] **CSRF Protection**
  - [ ] Verify CSRF token in forms
  - [ ] Try POST without token
  - [ ] Verify 419 error

- [ ] **XSS Protection**
  - [ ] Try search: `<script>alert('xss')</script>`
  - [ ] Verify script not executed
  - [ ] Check source - script escaped

- [ ] **SQL Injection**
  - [ ] Try search: `' OR '1'='1`
  - [ ] Verify safe query execution
  - [ ] Verify no database error

- [ ] **Access Control**
  - [ ] Try guess portfolio IDs
  - [ ] Verify cannot access unauthorized
  - [ ] Try modify URL parameters
  - [ ] Verify authorization checked

---

## ✨ Final Checklist

Before deployment:

- [ ] All tests passed ✅
- [ ] No console errors
- [ ] No database errors
- [ ] All features working
- [ ] UI responsive on all devices
- [ ] Files uploading correctly
- [ ] Search working
- [ ] Pagination working
- [ ] Authorization enforced
- [ ] CSRF protection active
- [ ] XSS protection active
- [ ] Database seeded
- [ ] Storage link created
- [ ] Cache cleared

---

## 📞 Support Notes

**Common Issues & Solutions:**

1. **File not uploading**
   - Check storage link: `php artisan storage:link`
   - Check permissions: `chmod -R 755 storage/`

2. **Page shows $slot error**
   - Layout mismatch - Fixed in current version

3. **Login not working**
   - Clear cache: `php artisan cache:clear`
   - Check .env DATABASE settings

4. **Authorization error (403)**
   - Check PortfolioPolicy rules
   - Verify AuthServiceProvider configured

---

**Test Status**: Ready for Production ✅

Generate tanggal: 27 November 2025
