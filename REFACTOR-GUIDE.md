# Smart-Share Household Manager - Refactored Version
## Pakistani Localized Multi-User Authentication System

---

## 🎯 System Overview

The Smart-Share Household Manager has been completely refactored with:
1. **Pakistani Localization** - Currency (PKR), local names, items, and cultural context
2. **Separate Login System** - Multi-page authentication with 5 distinct user roles
3. **Role-Based Dashboards** - Each user type has a customized dashboard
4. **Session Management** - Secure sessionStorage-based authentication
5. **Pakistani Theme** - Green and white color scheme (Pakistan flag colors)

---

## 🔐 Login System

### Access the System
- **Entry Point:** `login.html`
- **Theme Colors:** 
  - Pakistan Green: `#01411C` (dark green)
  - Pakistan Green Light: `#0A6738` (light green)
  - Accent Gold: `#FFB800` (for highlights)

### Demo Credentials
The login page displays 5 credential cards for easy testing:

| Username | Password | Role | Full Name | Dashboard |
|----------|----------|------|-----------|-----------|
| `ahmed` | `admin123` | Admin | Ahmed Khan | admin-dashboard.html |
| `hassan` | `room123` | Roommate | Hassan Ali | roommate-dashboard.html |
| `fatima` | `room123` | Roommate | Fatima Noor | roommate-dashboard.html |
| `malik` | `land123` | Landlord | Malik Tariq | landlord-dashboard.html |
| `usman` | `maint123` | Maintenance | Usman Electrician | maintenance-dashboard.html |

### Guest Access
- Guests can access limited information without logging in
- Click "Continue as Guest" on login page
- Redirects to: `guest-view.html`

---

## 👥 User Roles & Access Levels

### 1. 🔑 House Admin (Ahmed Khan)
**Full Access Dashboard**
- **Navigation Access:**
  - ✅ Dashboard
  - ✅ Finances (full access)
  - ✅ Chores (manage all)
  - ✅ Maintenance (full control)
  - ✅ Shopping (manage list)
  - ✅ Users (manage members)
  - ✅ Announcements (create/edit)

- **Key Features:**
  - View all household finances
  - Assign and manage chores for everyone
  - Handle maintenance issues
  - Manage shopping list
  - Add/remove users
  - Post announcements
  - Full administrative control

- **Dashboard Highlights:**
  - Monthly expenses overview (PKR 53,100)
  - Recent finances with PKR amounts
  - Chores status for all members
  - Active maintenance tickets
  - Shopping list with Pakistani items (Chawal, Atta, Daal)
  - Announcements feed

---

### 2. 👤 Roommate (Hassan Ali / Fatima Noor)
**Limited Access Dashboard**
- **Navigation Access:**
  - ✅ Dashboard
  - ✅ Finances (view only - personal payments)
  - ✅ Chores (personal tasks only)
  - ✅ Shopping (can add items)
  - ✅ Announcements (read only)
  - ❌ Maintenance (cannot access)
  - ❌ Users (cannot access)

- **Key Features:**
  - View personal payment history
  - See personal share breakdown (PKR 13,275/month)
  - Manage assigned chores
  - Add items to shopping list
  - Read household announcements
  - View other household members

- **Dashboard Highlights:**
  - Personal monthly share (PKR 13,275)
  - Payment status (Rent, Electricity, Internet, Gas, Water shares)
  - Personal assigned chores with Urdu names (Bartan Dhona, Jhadu Lagana)
  - Shopping list with Pakistani items
  - Important announcements
  - Household members list

---

### 3. 🏠 Landlord (Malik Tariq)
**Property Management Portal**
- **Navigation Access:**
  - ✅ Dashboard
  - ✅ Maintenance (property issues only)
  - ❌ All other pages

- **Key Features:**
  - View rent payment history
  - Monitor payment reliability
  - Track maintenance issues requiring landlord approval
  - View property details
  - Access tenant contact information
  - Review lease information

- **Dashboard Highlights:**
  - Monthly rent tracking (PKR 45,000)
  - Payment history (last 6 months)
  - Property information (House #12, F-10/3, Islamabad)
  - Maintenance issues requiring approval
  - Tenant contact details
  - Lease start/end dates
  - Security deposit status (PKR 90,000)

---

### 4. 🔧 Maintenance Staff (Usman Electrician)
**Work Order Management**
- **Navigation Access:**
  - ✅ Dashboard
  - ✅ Maintenance (ticket management)
  - ❌ All other pages

- **Key Features:**
  - View assigned work orders
  - Update ticket status
  - Add work notes
  - Track completed tasks
  - View work schedule
  - Monitor tools & inventory
  - Access emergency contacts

- **Dashboard Highlights:**
  - Active work orders with priority levels
  - Pakistani-context issues (UPS battery, Gas leakage, Load shedding)
  - Recently completed tasks with costs
  - Daily schedule
  - Tools and inventory status
  - Important contacts (Ahmed, Malik, SSGC, WAPDA)

---

### 5. 👁️ Guest (Public Access)
**Information-Only View**
- **Navigation Access:**
  - ✅ Guest View only
  - ❌ All other pages

- **Key Features:**
  - View Wi-Fi credentials
  - Read house rules
  - Access emergency contacts
  - See available amenities
  - Parking information
  - Nearby places and tips

- **Page Highlights:**
  - Bilingual content (English/Urdu)
  - Pakistani emergency numbers (15-Police, 1122-Ambulance, 16-Fire)
  - Local context (PIMS Hospital, F-10 Markaz, Faisal Mosque)
  - Pakistani amenities (UPS backup, Gas Geyser, Prayer mats)
  - Cultural tips (Shoes off, Chai time, Azaan)
  - Load shedding and water conservation notes

---

## 💰 Pakistani Localization

### Currency Conversion
All amounts now in **Pakistani Rupees (PKR)**:
- Rent: **PKR 45,000**/month
- Electricity: **PKR 3,500**/month
- Internet: **PKR 2,000**/month
- Gas: **PKR 1,800**/month
- Water: **PKR 800**/month
- **Total Monthly: PKR 53,100**

### Roommate Share Calculation:
- 4 people sharing
- Per person: PKR 53,100 ÷ 4 = **PKR 13,275**/month

### Names
- **Ahmed Khan** - House Admin
- **Hassan Ali** - Roommate 1
- **Fatima Noor** - Roommate 2
- **Malik Tariq** - Landlord
- **Usman Electrician** - Maintenance Staff

### Shopping Items (Pakistani Context)
- 🍚 Chawal (Rice) - PKR 600
- 🥔 Atta (Flour) - PKR 850
- 🫘 Daal (Lentils) - PKR 420
- 🧴 Dish Soap - PKR 150
- 🧻 Tissue Roll - PKR 300

### Chores (Urdu/English)
- Bartan Dhona (Wash Dishes)
- Jhadu Lagana (Sweep Floor)
- Safai Karna (Clean Kitchen)
- Kamray Ki Safai (Room Cleaning)

### Maintenance Issues (Pakistani Context)
- Load Shedding UPS Battery Issue
- Gas Leakage Check (SSGC)
- AC Filter Cleaning
- Water Tank Maintenance

### Location
- **Address:** House #12, Street 5, F-10/3, Islamabad, Pakistan
- **Nearby:** F-10 Markaz, PIMS Hospital, Faisal Mosque

---

## 🗂️ File Structure

### New/Updated Files:
```
📁 Smart Share/
├── 🔐 login.html (NEW) - Entry point with 5 user credentials
├── 🎨 login.css (NEW) - Pakistani theme styling
├── ⚙️ login.js (NEW) - Authentication & session management
│
├── 📊 admin-dashboard.html (NEW) - Ahmed Khan's full access
├── 👤 roommate-dashboard.html (NEW) - Hassan/Fatima limited access
├── 🏠 landlord-dashboard.html (NEW) - Malik Tariq property portal
├── 🔧 maintenance-dashboard.html (NEW) - Usman's work orders
├── 👁️ guest-view.html (NEW) - Public information page
│
├── 💰 finances.html (UPDATE NEEDED) - Pakistani localization
├── 🧹 chores.html (UPDATE NEEDED) - Urdu chore names
├── 🔧 maintenance.html (UPDATE NEEDED) - Pakistani issues
├── 🛒 shopping.html (UPDATE NEEDED) - Pakistani items
├── 👥 users.html (UPDATE NEEDED) - Pakistani names
├── 📢 announcements.html (UPDATE NEEDED) - Local context
│
├── 🎨 styles.css (UPDATE NEEDED) - Pakistani color theme
├── ⚙️ app.js (UPDATE NEEDED) - Session authentication checks
│
└── 📄 README.md (ORIGINAL) - Project documentation
```

---

## 🔄 Authentication Flow

### Login Process:
1. User opens `login.html` (entry point)
2. User can either:
   - **Click a credential card** to auto-fill login form
   - **Manually enter** username, password, and select role
   - **Continue as Guest** to view public information
3. On submit, `login.js` validates credentials
4. If valid, creates session in `sessionStorage`:
   ```javascript
   {
     username: 'ahmed',
     fullName: 'Ahmed Khan',
     role: 'admin',
     dashboard: 'admin-dashboard.html',
     loginTime: '2025-12-05T10:30:00Z'
   }
   ```
5. Redirects to role-specific dashboard

### Session Management:
- Stored in `sessionStorage` (cleared on browser close)
- Each dashboard checks for valid session
- If no session or wrong role → redirects to `login.html`

### Logout Process:
1. User clicks "Logout" button in header
2. Confirmation dialog appears
3. On confirm:
   - Clears `sessionStorage`
   - Redirects to `login.html`

---

## 🎨 Design Theme

### Color Scheme (Pakistani Flag Colors):
```css
/* Primary Colors */
--pakistan-green: #01411C;        /* Dark green */
--pakistan-green-light: #0A6738;  /* Light green */
--pakistan-white: #FFFFFF;         /* White */
--accent-gold: #FFB800;            /* Gold accent */

/* Semantic Colors */
--success: #0A6738;   /* Green for positive actions */
--error: #DC2626;     /* Red for errors */
--warning: #F59E0B;   /* Orange for warnings */
--info: #3B82F6;      /* Blue for information */
```

### Typography:
- **Font Family:** Inter (Google Fonts)
- **Weights:** 300, 400, 500, 600, 700

### Role Badges:
- **Admin:** Green badge
- **Roommate:** Blue badge
- **Landlord:** Purple badge
- **Maintenance:** Orange badge
- **Guest:** Gray badge

---

## 🚀 Testing Guide

### Test Each Role:

#### 1. Test Admin (Ahmed Khan):
```
1. Go to login.html
2. Click "Ahmed Khan" credential card
3. Click "Login"
4. Should see: admin-dashboard.html
5. Navigate to all 7 pages (should have access)
6. Click "Logout"
```

#### 2. Test Roommate (Hassan Ali):
```
1. Go to login.html
2. Click "Hassan Ali" credential card
3. Click "Login"
4. Should see: roommate-dashboard.html
5. Try navigating (only 5 links visible)
6. Click "Logout"
```

#### 3. Test Roommate (Fatima Noor):
```
1. Go to login.html
2. Click "Fatima Noor" credential card
3. Click "Login"
4. Should see: roommate-dashboard.html (same as Hassan)
5. Greeting should say "Fatima"
6. Click "Logout"
```

#### 4. Test Landlord (Malik Tariq):
```
1. Go to login.html
2. Click "Malik Tariq" credential card
3. Click "Login"
4. Should see: landlord-dashboard.html
5. Try navigating (only 2 links: Dashboard, Maintenance)
6. Click "Logout"
```

#### 5. Test Maintenance (Usman):
```
1. Go to login.html
2. Click "Usman Electrician" credential card
3. Click "Login"
4. Should see: maintenance-dashboard.html
5. Try navigating (only 2 links: Dashboard, All Tickets)
6. Click "Logout"
```

#### 6. Test Guest Access:
```
1. Go to login.html
2. Click "Continue as Guest"
3. Should see: guest-view.html
4. No login required
5. Click "Back to Login" to return
```

---

## ⚠️ Remaining Tasks

### High Priority:
1. ✅ Create login system with authentication
2. ✅ Create 5 role-specific dashboards
3. ✅ Create guest-view page
4. ⏳ **Update existing pages with Pakistani localization:**
   - finances.html → PKR currency, Pakistani names
   - chores.html → Urdu chore names
   - maintenance.html → Pakistani issues (load shedding, gas)
   - shopping.html → Pakistani items (Chawal, Atta, Daal)
   - users.html → Pakistani user names
   - announcements.html → Local context

5. ⏳ **Update styles.css** with Pakistani color theme
6. ⏳ **Update navigation in existing pages** to respect user roles
7. ⏳ **Add session checks** to all existing pages

### Medium Priority:
- Add more Pakistani context to existing pages
- Update all JavaScript files with session validation
- Add role-based feature restrictions
- Improve mobile responsiveness for new pages

### Low Priority:
- Add more Urdu translations
- Create password reset functionality
- Add profile picture support
- Implement "Remember Me" feature

---

## 📱 How to Use

### For Developers:
1. Start with `login.html` as the entry point
2. All authentication happens in `login.js`
3. Each dashboard checks for valid session
4. Use provided credentials for testing
5. Session clears on browser close (sessionStorage)

### For Users:
1. Open `login.html`
2. Click your credential card OR enter manually
3. Select correct role from dropdown
4. Click "Login"
5. Access your personalized dashboard
6. Use navigation based on your permissions
7. Click "Logout" when done

---

## 🇵🇰 Pakistani Cultural Elements

### Greetings:
- "Assalam-o-Alaikum" (Peace be upon you)
- "Khush Amdeed" (Welcome)
- "Mehman" (Guest)

### Language:
- **Bilingual:** English & Urdu
- **Urdu Text:** Right-to-left where appropriate
- **Common Terms:** Bartan (dishes), Safai (cleaning), Mehman (guest)

### Local Context:
- **Currency:** Pakistani Rupee (PKR)
- **Location:** Islamabad, F-10 sector
- **Emergency:** 15 (Police), 1122 (Ambulance), 16 (Fire)
- **Utilities:** SSGC (gas), WAPDA/LESCO (electricity), PTCL (internet)
- **Culture:** Shoes off, prayer space, chai time, Azaan times

### Daily Life:
- **Load Shedding:** Power cuts (UPS backup mentioned)
- **Gas Pressure:** Low in winter mornings
- **Water Conservation:** Occasional shortages
- **Transportation:** Careem, InDriver, Bykea (ride-hailing apps)

---

## 🛠️ Technical Details

### Session Storage Schema:
```javascript
sessionStorage.currentUser = {
  username: string,      // 'ahmed', 'hassan', etc.
  fullName: string,      // 'Ahmed Khan', 'Hassan Ali', etc.
  role: string,          // 'admin', 'roommate', 'landlord', 'maintenance'
  dashboard: string,     // URL to redirect on login
  roommateName: string,  // 'hassan' or 'fatima' (roommates only)
  loginTime: string      // ISO timestamp
}
```

### Authentication Check (Add to all pages):
```javascript
const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
if (!currentUser.username || currentUser.role !== 'expected_role') {
    window.location.href = 'login.html';
}
```

### Logout Function (Already in dashboards):
```javascript
function handleLogout() {
    if (confirm('Are you sure you want to logout?')) {
        sessionStorage.removeItem('currentUser');
        window.location.href = 'login.html';
    }
}
```

---

## 📊 Access Matrix

| Page | Admin | Roommate | Landlord | Maintenance | Guest |
|------|-------|----------|----------|-------------|-------|
| Dashboard | ✅ admin-dashboard | ✅ roommate-dashboard | ✅ landlord-dashboard | ✅ maintenance-dashboard | ✅ guest-view |
| Finances | ✅ Full | ✅ Personal only | ❌ | ❌ | ❌ |
| Chores | ✅ Manage all | ✅ Personal only | ❌ | ❌ | ❌ |
| Maintenance | ✅ Full | ❌ | ✅ Property only | ✅ Work orders | ❌ |
| Shopping | ✅ Manage | ✅ Add items | ❌ | ❌ | ❌ |
| Users | ✅ Manage | ❌ | ❌ | ❌ | ❌ |
| Announcements | ✅ Create/Edit | ✅ Read only | ❌ | ❌ | ❌ |

---

## 🎓 Project Context

**Course:** University Project (Smart Share)  
**Location:** Islamabad, Pakistan  
**Theme:** Household Management for Shared Living  
**Technology:** HTML5, CSS3, Vanilla JavaScript (No frameworks)  
**Design:** Responsive, Mobile-friendly, Pakistani-localized  

---

## 📞 Support & Contact

### For Issues:
- Check session in browser DevTools (Application → Session Storage)
- Clear session and try logging in again
- Verify credentials match the table above
- Check browser console for errors

### Demo Credentials Quick Reference:
- **Admin:** ahmed / admin123
- **Roommate 1:** hassan / room123
- **Roommate 2:** fatima / room123
- **Landlord:** malik / land123
- **Maintenance:** usman / maint123
- **Guest:** No credentials needed (click "Continue as Guest")

---

## 🎉 Features Implemented

✅ Complete login system with authentication  
✅ 5 distinct role-based dashboards  
✅ Session management with sessionStorage  
✅ Pakistani localization (currency, names, items)  
✅ Pakistani color theme (green & white)  
✅ Role-based navigation restrictions  
✅ Logout functionality  
✅ Guest public access  
✅ Credential card quick-fill  
✅ Password visibility toggle  
✅ Loading animations  
✅ Error notifications  
✅ Responsive design  
✅ Bilingual content (English/Urdu)  
✅ Cultural context (Load shedding, Azaan, Chai)  

---

**Version:** 2.0 - Refactored with Multi-User Authentication  
**Last Updated:** December 5, 2025  
**Made with ❤️ in Pakistan 🇵🇰**
