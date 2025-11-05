# Smart-Share Household Manager 🇵🇰

A comprehensive household management dashboard for shared living spaces in Pakistan. Built with **HTML, CSS, and vanilla JavaScript** - no frameworks required.

## 🏠 Project Overview

Smart-Share is a complete multi-user authentication system for managing shared households, featuring role-based dashboards, bill tracking, chore scheduling, maintenance requests, shopping lists, and more. **Version 2.0** includes Pakistani localization with PKR currency, local context, and a secure login system with 5 distinct user roles.

## 🆕 What's New in Version 2.0

### Major Updates:
- ✅ **Separate Login System** - Multi-page authentication with 5 user roles
- ✅ **Pakistani Localization** - PKR currency, Pakistani names, local items
- ✅ **Role-Based Dashboards** - Each user has a customized dashboard
- ✅ **Session Management** - Secure sessionStorage authentication
- ✅ **Pakistani Theme** - Green & white color scheme (Pakistan flag colors)
- ✅ **Cultural Context** - Load shedding, Azaan, Chai time, Urdu terms

### Entry Point:
**Start Here:** `login.html` (replaces index.html as entry point)

## 🔐 Demo Credentials

Click any credential card on the login page to auto-fill, or enter manually:

| Username | Password | Role | Full Name | Dashboard |
|----------|----------|------|-----------|-----------|
| `ahmed` | `admin123` | Admin | Ahmed Khan | Full Access |
| `hassan` | `room123` | Roommate | Hassan Ali | Limited Access |
| `fatima` | `room123` | Roommate | Fatima Noor | Limited Access |
| `malik` | `land123` | Landlord | Malik Tariq | Property Portal |
| `usman` | `maint123` | Maintenance | Usman Electrician | Work Orders |

**Guest Access:** Click "Continue as Guest" - no login required

---

## ✨ Features

### 1. **Role-Based Authentication System** 🆕
- **Secure Login** - Username, password, and role verification
- **Session Management** - Persistent sessions using sessionStorage
- **Auto-redirect** - Already logged in? Direct to your dashboard
- **Quick Fill** - Click credential cards to auto-fill login form
- **Logout** - Secure logout with confirmation

### 2. **Five Distinct User Roles** 🆕

#### 🔑 House Admin (Ahmed Khan)
**Full Access Dashboard** - `admin-dashboard.html`
- ✅ All 7 navigation pages
- ✅ Manage finances, chores, maintenance, shopping
- ✅ User management and announcements
- ✅ Complete household oversight
- **Dashboard Shows:** Monthly expenses (PKR 53,100), all members' chores, maintenance tickets, shopping list

#### 👤 Roommate (Hassan Ali / Fatima Noor)
**Limited Access Dashboard** - `roommate-dashboard.html`
- ✅ Personal payment history (PKR 13,275/month share)
- ✅ Personal assigned chores only
- ✅ Can add to shopping list
- ✅ Read announcements
- ❌ No maintenance or user management access
- **Dashboard Shows:** Personal payments, assigned chores (Bartan Dhona, Jhadu Lagana), shopping contributions

#### 🏠 Landlord (Malik Tariq)
**Property Management Portal** - `landlord-dashboard.html`
- ✅ Rent payment history (PKR 45,000/month)
- ✅ Maintenance requiring approval
- ❌ No access to daily household operations
- **Dashboard Shows:** Payment reliability, property details (House #12, F-10/3, Islamabad), tenant contacts

#### 🔧 Maintenance Staff (Usman Electrician)
**Work Order Dashboard** - `maintenance-dashboard.html`
- ✅ View assigned work orders
- ✅ Update ticket status
- ✅ Add work notes
- ❌ No access to other household pages
- **Dashboard Shows:** Active tickets (UPS battery, Gas leakage), schedule, tools inventory

#### 👁️ Guest (Public Access)
**Information Page** - `guest-view.html`
- ✅ View Wi-Fi credentials
- ✅ House rules
- ✅ Emergency contacts
- ✅ No login required
- **Page Shows:** Pakistani emergency numbers (15, 1122, 16), local amenities, cultural tips

### 3. **Main Pages**

#### 🔐 Login (login.html) 🆕
- **Entry Point** for the entire system
- Display 5 user credential cards for easy testing
- Username, password, and role selection
- Guest access option
- Loading animation
- Error notifications with shake animation

#### 📊 Dashboards (NEW - Role-Specific)
- **admin-dashboard.html** - Ahmed Khan's full overview
- **roommate-dashboard.html** - Hassan/Fatima's personal view
- **landlord-dashboard.html** - Malik Tariq's property portal
- **maintenance-dashboard.html** - Usman's work orders
- **guest-view.html** - Public information page

#### 💰 Finances (finances.html) [TO UPDATE]
- Visual expense breakdown with bar charts
- **Pakistani Currency:** All amounts in PKR
- Bill tracking: Rent (PKR 45,000), Electricity (PKR 3,500), Internet (PKR 2,000), Gas (PKR 1,800), Water (PKR 800)
- **Roommate Shares:** PKR 13,275 per person
- Payment history timeline
- Split calculator

#### 🧹 Chores (chores.html) [TO UPDATE]
- Weekly calendar view
- **Pakistani Context:** Bartan Dhona (Wash Dishes), Jhadu Lagana (Sweep Floor), Safai Karna (Clean Kitchen)
- Chore assignment system
- Mark complete functionality
- Auto-rotating schedule generator
- Filter by status (all, mine, pending, completed)

#### 🔧 Maintenance (maintenance.html) [TO UPDATE]
- Submit maintenance requests with photos
- **Pakistani Issues:** Load Shedding UPS, Gas Leakage (SSGC), AC Filter Cleaning
- Ticket tracking system
- Priority indicators (high, medium, low)
- Status timeline (received, in progress, completed)
- Progress bars for each ticket

#### 🛒 Shopping List (shopping.html) [TO UPDATE]
- **Pakistani Items:** Chawal (Rice) PKR 600, Atta (Flour) PKR 850, Daal (Lentils) PKR 420
- Categorized lists (Groceries, Household, Personal Care)
- Claim items for purchase
- Quick-add interface
- Delete items with confirmation

#### 👥 User Management (users.html) [TO UPDATE]
- **Pakistani Names:** Ahmed Khan, Hassan Ali, Fatima Noor, Malik Tariq
- Add/remove roommates
- View detailed user profiles
- Track payment status
- Assign roles (Admin/Roommate/Landlord/Maintenance)
- View activity history

#### 📢 Announcements (announcements.html) [TO UPDATE]
- Post house updates and events
- **Local Context:** Electricity bills, house meetings, load shedding schedules
- Important announcements highlighting
- Reaction system
- Read receipts
- Landlord/Admin posting

#### 👁️ Guest View (guest-view.html) 🆕
- **Bilingual:** English & Urdu content
- House rules display (Shoes off, Quiet hours, No smoking)
- Wi-Fi credentials (SmartShare_Islamabad / Pakistan2025!)
- **Pakistani Emergency:** 15 (Police), 1122 (Ambulance), 16 (Fire), 1199 (SSGC Gas)
- Available amenities (UPS Backup, Gas Geyser, Prayer Mats)
- **Nearby Places:** F-10 Markaz, PIMS Hospital, Faisal Mosque
- **Cultural Tips:** Load shedding, Chai time, Azaan

## 🎨 Design Features

### Pakistani Theme 🆕
- **Pakistan Flag Colors:**
  - `#01411C` - Pakistan Green (dark)
  - `#0A6738` - Pakistan Green (light)
  - `#FFFFFF` - White
  - `#FFB800` - Accent Gold
- **Typography:** Inter font family (Google Fonts)
- **Role Badges:** Color-coded for each user type

### UI/UX
- **Modern, Clean Interface** with card-based layouts
- **Color-Coded Status Indicators**:
  - 🟢 Green - Completed/Paid
  - 🟡 Yellow - Pending
  - 🔴 Red - Overdue/Urgent
  - 🔵 Blue - In Progress
- **Fully Responsive** - Works on desktop, tablet, and mobile
- **Smooth Animations** - Professional transitions and feedback
- **CSS Grid & Flexbox** - Modern layout techniques
- **Custom CSS Variables** - Easy theming and customization

## 🚀 JavaScript Functionality

### Authentication System (login.js) 🆕
- **User Validation** - Check credentials against mock database
- **Session Management** - Store user data in sessionStorage
- **Role-Based Redirects** - Send users to appropriate dashboards
- **Auto-fill Credentials** - Click card to populate login form
- **Password Toggle** - Show/hide password functionality
- **Loading Overlay** - Visual feedback during login
- **Error Handling** - Shake animation and notifications
- **Already Logged In** - Auto-redirect to dashboard

### Mock User Database (login.js)
```javascript
users = {
    admin: { username: 'ahmed', password: 'admin123', role: 'admin', ... },
    hassan: { username: 'hassan', password: 'room123', role: 'roommate', ... },
    fatima: { username: 'fatima', password: 'room123', role: 'roommate', ... },
    landlord: { username: 'malik', password: 'land123', role: 'landlord', ... },
    maintenance: { username: 'usman', password: 'maint123', role: 'maintenance', ... }
}
```

### Session Storage Schema
```javascript
sessionStorage.currentUser = {
    username: 'ahmed',
    fullName: 'Ahmed Khan',
    role: 'admin',
    dashboard: 'admin-dashboard.html',
    roommateName: 'ahmed', // for roommates only
    loginTime: '2025-12-05T10:30:00Z'
}
```

### Core Features (app.js)
- Role switching with view restrictions
- Modal management (open/close)
- Form validation
- Notification system
- Utility functions (copy to clipboard, format currency PKR, etc.)

### Dashboard-Specific Scripts
- **admin-dashboard.html** - Full stats, logout handler
- **roommate-dashboard.html** - Personal stats, roommate name logic
- **landlord-dashboard.html** - Rent history, contact functions
- **maintenance-dashboard.html** - Work order management, status updates
- **guest-view.html** - Password toggle, copy to clipboard

### Page-Specific Scripts [TO UPDATE]
- **finances.js** - Bill filtering, payment marking (update for PKR)
- **chores.js** - Chore completion, schedule rotation (update for Urdu names)
- **maintenance.js** - Ticket filtering, status updates (update for Pakistani issues)
- **shopping.js** - Item claiming, quick add (update for Pakistani items)
- **users.js** - User management, edit/remove functionality
- **announcements.js** - Post announcements, reaction system
- **guest.js** - Password toggle, copy to clipboard

## 📊 Mock Data Included

### Users 🆕
- **5 users** with distinct roles:
  - Ahmed Khan (Admin)
  - Hassan Ali (Roommate)
  - Fatima Noor (Roommate)
  - Malik Tariq (Landlord)
  - Usman Electrician (Maintenance)

### Finances 🆕
- **Monthly Rent:** PKR 45,000
- **Utilities:** PKR 8,100 (Electricity PKR 3,500, Internet PKR 2,000, Gas PKR 1,800, Water PKR 800)
- **Per Person Share:** PKR 13,275 (for 4 residents)
- **6 bills** with varying due dates and statuses

### Chores 🆕
- **Pakistani Context:** Bartan Dhona, Jhadu Lagana, Safai Karna, Kamray Ki Safai
- **12 chores** distributed across the week with Urdu names
- Assigned to Ahmed, Hassan, and Fatima

### Maintenance 🆕
- **4 tickets:** Load Shedding UPS Issue, Gas Leakage Check, AC Filter Cleaning, Bathroom Tap Repair
- **Pakistani vendors:** SSGC (gas), WAPDA (electricity)
- Different status stages (Open, In Progress, Completed)

### Shopping 🆕
- **Pakistani Items:** Chawal (Rice) PKR 600, Atta (Flour) PKR 850, Daal (Lentils) PKR 420
- **10 items** across 3 categories with PKR prices
- Household items in Pakistani context

### Announcements
- **3 announcements** with local context (Electricity bills, House meetings, Load shedding schedules)

### Guest Information 🆕
- **Location:** House #12, F-10/3, Islamabad
- **Nearby:** F-10 Markaz, PIMS Hospital, Faisal Mosque
- **Emergency:** Pakistan emergency numbers (15, 1122, 16, 1199)

## 🛠️ Technical Specifications

### File Structure 🆕
```
Smart Share/
├── 🔐 AUTHENTICATION (NEW)
│   ├── login.html                    # ⭐ ENTRY POINT - Login page
│   ├── login.css                     # Login page styles (Pakistani theme)
│   └── login.js                      # Authentication & session management
│
├── 📊 DASHBOARDS (NEW - Role-Specific)
│   ├── admin-dashboard.html          # Ahmed Khan - Full access
│   ├── roommate-dashboard.html       # Hassan/Fatima - Limited access
│   ├── landlord-dashboard.html       # Malik Tariq - Property portal
│   ├── maintenance-dashboard.html    # Usman - Work orders
│   └── guest-view.html               # Public information page
│
├── 📄 FEATURE PAGES (Original - Need Update)
│   ├── finances.html                 # Finance tracking [UPDATE FOR PKR]
│   ├── chores.html                   # Chore schedule [UPDATE FOR URDU]
│   ├── maintenance.html              # Maintenance requests [UPDATE]
│   ├── shopping.html                 # Shopping list [UPDATE FOR PAKISTANI ITEMS]
│   ├── users.html                    # User management [UPDATE]
│   └── announcements.html            # Announcements feed [UPDATE]
│
├── 🎨 STYLESHEETS
│   ├── styles.css                    # Main stylesheet [UPDATE FOR PAKISTANI THEME]
│   ├── login.css                     # ✅ Login page styles (NEW)
│   ├── finances.css                  # Finance page styles
│   ├── chores.css                    # Chores page styles
│   ├── maintenance.css               # Maintenance page styles
│   ├── shopping.css                  # Shopping page styles
│   ├── users.css                     # Users page styles
│   ├── announcements.css             # Announcements page styles
│   └── guest.css                     # Guest page styles
│
├── ⚙️ JAVASCRIPT
│   ├── login.js                      # ✅ Authentication (NEW)
│   ├── app.js                        # Core JavaScript [UPDATE FOR SESSION]
│   ├── finances.js                   # Finance page logic
│   ├── chores.js                     # Chores page logic
│   ├── maintenance.js                # Maintenance page logic
│   ├── shopping.js                   # Shopping page logic
│   ├── users.js                      # Users page logic
│   ├── announcements.js              # Announcements page logic
│   └── guest.js                      # Guest page logic
│
├── 📚 DOCUMENTATION
│   ├── README.md                     # ⭐ Main documentation (updated)
│   └── REFACTOR-GUIDE.md             # ✅ Detailed refactor guide (NEW)
│
└── 📦 DEPRECATED
    ├── index.html                    # Old dashboard (replaced by role dashboards)
    └── guest.html                    # Old guest page (replaced by guest-view.html)
```

### Technologies Used
- **HTML5** - Semantic markup
- **CSS3** - Custom properties, Grid, Flexbox
- **Vanilla JavaScript** - ES6+ features
- **Google Fonts** - Inter font family
- **No external libraries** - Pure web standards

## 🎯 Key Interactions

### UI Features
- ✅ Tab/page switching between sections
- ✅ Show/hide modals for forms
- ✅ Dynamic list rendering
- ✅ Form validation with visual feedback
- ✅ Mark items complete with status updates
- ✅ Filter/sort functionality
- ✅ Role-based view switching
- ✅ Bill split calculations
- ✅ Copy to clipboard
- ✅ Password show/hide
- ✅ Delete with confirmation
- ✅ Notification system

### Visual Feedback
- Hover effects on all interactive elements
- Smooth transitions and animations
- Loading states and progress indicators
- Color-coded status badges
- Toast notifications for actions
- Form validation highlighting

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📱 Responsive Breakpoints

- **Desktop**: 1024px and above
- **Tablet**: 768px - 1023px
- **Mobile**: Below 768px

## 🎨 Color Palette

### Pakistani Theme Colors 🆕
- **Pakistan Green (Dark):** #01411C - Primary color, headers, buttons
- **Pakistan Green (Light):** #0A6738 - Success states, accents
- **Pakistan White:** #FFFFFF - Backgrounds, cards
- **Accent Gold:** #FFB800 - Highlights, important badges

### Semantic Colors
- **Success:** #0A6738 (Pakistan green - paid, completed)
- **Warning:** #F59E0B (Amber - pending, due soon)
- **Danger:** #EF4444 (Red - overdue, high priority)
- **Info:** #3B82F6 (Blue - in progress, informational)

### Role Badge Colors
- **Admin:** Green (#0A6738)
- **Roommate:** Blue (#3B82F6)
- **Landlord:** Purple (#8B5CF6)
- **Maintenance:** Orange (#F59E0B)
- **Guest:** Gray (#6B7280)

### Neutral Colors
- Dark text: #111827
- Medium text: #6B7280
- Light text: #9CA3AF
- Background: #F9FAFB
- Cards: #FFFFFF

## 🚦 How to Use

### Quick Start 🆕
1. **Open `login.html`** in a web browser (⭐ START HERE!)
2. **Click any credential card** to auto-fill the login form OR enter manually:
   - Username: `ahmed` | Password: `admin123` | Role: `Admin`
3. **Click "Login"** button
4. **You'll be redirected** to the appropriate dashboard based on your role
5. **Explore the dashboard** - navigation shows only pages you can access
6. **Click "Logout"** in the header when done

### Testing Different Roles
- **Admin:** Login as `ahmed` / `admin123` → See all 7 pages
- **Roommate:** Login as `hassan` / `room123` → See 5 pages (no maintenance/users)
- **Landlord:** Login as `malik` / `land123` → See only 2 pages (dashboard/maintenance)
- **Maintenance:** Login as `usman` / `maint123` → See 2 pages (dashboard/tickets)
- **Guest:** Click "Continue as Guest" → No login required

### Session Behavior
- **Session persists** within the same browser session
- **Already logged in?** Automatically redirected to your dashboard
- **Logout** clears the session and returns to login page
- **Session clears** when you close the browser

### Old System (Deprecated)
- ~~**Use the role selector** (top right) to switch between user views~~
- ~~**Try dark mode toggle** in the sidebar footer~~
- The old `index.html` dashboard has been replaced by role-specific dashboards

## 🔮 Future Enhancements (Backend Required)

- User authentication and sessions
- Real-time data synchronization
- Database integration
- Email notifications
- File upload handling
- Payment processing integration
- Calendar sync
- Mobile app versions

## 📝 Notes

### Version 2.0 Changes 🆕
- **Session persistence** - Uses sessionStorage for login state
- **Mock authentication** - Credentials stored in login.js (not secure - demo only)
- **No backend** - All authentication is client-side for demonstration
- **Session clears** - Closing browser tab/window ends the session

### General Notes
- **No data persistence** - All interactions are UI-only
- **Mock data** - All displayed data is hardcoded for demonstration
- **Forms don't submit** - Forms show visual feedback but don't send data anywhere
- **Fully functional UI** - All buttons and interactions provide visual feedback
- **Pakistani context** - Localized for Pakistan with PKR currency and local names

## ⚠️ Remaining Work

### High Priority:
- [ ] Update `finances.html` with PKR currency throughout
- [ ] Update `chores.html` with Urdu chore names
- [ ] Update `maintenance.html` with Pakistani issues
- [ ] Update `shopping.html` with Pakistani items
- [ ] Update `users.html` with Pakistani names
- [ ] Update `announcements.html` with local context
- [ ] Update `styles.css` with Pakistani color theme
- [ ] Add session checks to all existing pages

### Completed:
- [x] Create login system with authentication
- [x] Create 5 role-specific dashboards
- [x] Create guest-view page with Pakistani context
- [x] Implement session management
- [x] Apply Pakistani theme colors
- [x] Add logout functionality

## 👨‍💻 Development

No build process required! Simply:
1. **Start with `login.html`** (entry point)
2. Edit CSS/JS files as needed
3. Refresh browser to see changes
4. Test with different user roles
5. Check sessionStorage in browser DevTools

### For Authentication Testing:
- Open browser DevTools (F12)
- Go to Application → Session Storage
- Check `currentUser` key to see logged-in user data
- Clear session to test logout/login flow

## 📚 Additional Resources

- **REFACTOR-GUIDE.md** - Detailed documentation of the refactoring process, authentication flow, and role-based access
- **README.md** - This file - Overview and quick start guide

## 📞 Support

For issues or questions:
1. Check `REFACTOR-GUIDE.md` for detailed explanations
2. Verify you're starting from `login.html`
3. Check browser console for JavaScript errors
4. Clear sessionStorage and try again

## 📄 License

This is a UI mockup/prototype project for educational purposes.

---

**Built with ❤️ in Pakistan 🇵🇰 using HTML, CSS, and JavaScript**

*Smart-Share - Making shared living easier, one feature at a time!*

**Version 2.0** - Multi-User Authentication with Pakistani Localization  
**Last Updated:** December 5, 2025
