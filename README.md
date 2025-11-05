# Smart-Share Household Manager

A comprehensive household management dashboard for shared living spaces. Built with **HTML, CSS, and vanilla JavaScript** - no frameworks required.

## 🏠 Project Overview

Smart-Share is a complete UI mockup for managing shared households, featuring role-based dashboards, bill tracking, chore scheduling, maintenance requests, shopping lists, and more.

## ✨ Features

### 1. **Role-Based Views**
- **House Admin** - Full access to all features including user management
- **Roommate** - Access to bills, chores, shopping, and maintenance
- **Landlord** - View rent status, maintenance, and post announcements
- **Guest View** - Public page with house rules and Wi-Fi info

### 2. **Main Pages**

#### Dashboard (index.html)
- Overview cards showing bills due, chores today, and active maintenance
- Quick stats with visual indicators
- Recent activity feed
- At-a-glance household status

#### Finances (finances.html)
- Visual expense breakdown with bar charts
- Bill tracking with payment status
- Payment history timeline
- Roommate contribution tracking
- Split calculator

#### Chores (chores.html)
- Weekly calendar view
- Chore assignment system
- Mark complete functionality
- Auto-rotating schedule generator
- Filter by status (all, mine, pending, completed)

#### Maintenance (maintenance.html)
- Submit maintenance requests with photos
- Ticket tracking system
- Priority indicators (high, medium, low)
- Status timeline (received, in progress, completed)
- Progress bars for each ticket

#### Shopping List (shopping.html)
- Categorized lists (Groceries, Household, Personal Care)
- Claim items for purchase
- Quick-add interface
- Delete items with confirmation

#### User Management (users.html)
- Add/remove roommates
- View detailed user profiles
- Track payment status
- Assign roles (Admin/Roommate)
- View activity history

#### Announcements (announcements.html)
- Post house updates and events
- Important announcements highlighting
- Reaction system
- Read receipts
- Landlord/Admin posting

#### Guest View (guest.html)
- House rules display
- Wi-Fi credentials (with show/hide password)
- Emergency contacts
- Available amenities
- Parking information
- Trash & recycling schedule

## 🎨 Design Features

- **Modern, Clean Interface** with card-based layouts
- **Color-Coded Status Indicators**:
  - 🟢 Green - Completed/Paid
  - 🟡 Yellow - Pending
  - 🔴 Red - Overdue/Urgent
- **Fully Responsive** - Works on desktop, tablet, and mobile
- **Dark Mode Toggle** - Persistent theme preference
- **Smooth Animations** - Professional transitions and feedback
- **CSS Grid & Flexbox** - Modern layout techniques
- **Custom CSS Variables** - Easy theming and customization

## 🚀 JavaScript Functionality

### Core Features (app.js)
- Role switching with view restrictions
- Dark mode toggle with localStorage
- Modal management (open/close)
- Form validation
- Notification system
- Utility functions (copy to clipboard, format currency, etc.)

### Page-Specific Scripts
- **finances.js** - Bill filtering, payment marking
- **chores.js** - Chore completion, schedule rotation, week navigation
- **maintenance.js** - Ticket filtering, status updates
- **shopping.js** - Item claiming, quick add, delete with animation
- **users.js** - User management, edit/remove functionality
- **announcements.js** - Post announcements, reaction system
- **guest.js** - Password toggle, copy to clipboard

## 📊 Mock Data Included

- **4 roommates** (1 admin, 3 regular)
- **6 bills** with varying due dates and statuses
- **12 chores** distributed across the week
- **4 maintenance tickets** in different status stages
- **3 landlord announcements**
- **10 shopping list items** across 3 categories

## 🛠️ Technical Specifications

### File Structure
```
Smart Share/
├── index.html              # Dashboard
├── finances.html           # Finance tracking
├── chores.html            # Chore schedule
├── maintenance.html       # Maintenance requests
├── shopping.html          # Shopping list
├── users.html             # User management
├── announcements.html     # Announcements feed
├── guest.html             # Guest information
├── styles.css             # Main stylesheet
├── finances.css           # Finance page styles
├── chores.css             # Chores page styles
├── maintenance.css        # Maintenance page styles
├── shopping.css           # Shopping page styles
├── users.css              # Users page styles
├── announcements.css      # Announcements page styles
├── guest.css              # Guest page styles
├── app.js                 # Core JavaScript
├── finances.js            # Finance page logic
├── chores.js              # Chores page logic
├── maintenance.js         # Maintenance page logic
├── shopping.js            # Shopping page logic
├── users.js               # Users page logic
├── announcements.js       # Announcements page logic
└── guest.js               # Guest page logic
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

### Primary Colors
- Primary: #4F46E5 (Indigo)
- Success: #10B981 (Green)
- Warning: #F59E0B (Amber)
- Danger: #EF4444 (Red)
- Info: #3B82F6 (Blue)

### Neutral Colors
- Dark text: #111827
- Medium text: #6B7280
- Light text: #9CA3AF
- Background: #F9FAFB
- Cards: #FFFFFF

## 🚦 How to Use

1. **Open any HTML file** in a web browser
2. **Use the role selector** (top right) to switch between user views
3. **Click navigation items** to explore different pages
4. **Interact with buttons and forms** to see functionality
5. **Try dark mode toggle** in the sidebar footer
6. **Test on mobile** by resizing your browser

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

- **No data persistence** - All interactions are UI-only (localStorage used only for theme/role preferences)
- **Mock data** - All displayed data is hardcoded for demonstration
- **Forms don't submit** - Forms show visual feedback but don't send data anywhere
- **Fully functional UI** - All buttons and interactions provide visual feedback

## 👨‍💻 Development

No build process required! Simply:
1. Open HTML files directly in a browser
2. Edit CSS/JS files
3. Refresh to see changes

## 📄 License

This is a UI mockup/prototype project for educational purposes.

---

**Built with ❤️ using HTML, CSS, and JavaScript**

*Smart-Share - Making shared living easier, one feature at a time!*
