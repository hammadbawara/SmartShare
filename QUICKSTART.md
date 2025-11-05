# 🚀 QUICK START GUIDE
## Smart-Share Household Manager v2.0

---

## ⚡ 3-Step Quickstart

### Step 1: Open the Login Page
📂 Open `login.html` in your web browser

### Step 2: Choose a User
Click any of these credential cards on the login page:

| 👤 User | 🔑 Role | ✅ What You'll See |
|---------|---------|-------------------|
| **Ahmed Khan** | Admin | Full access - all 7 pages |
| **Hassan Ali** | Roommate | Limited - 5 pages (no maintenance/users) |
| **Fatima Noor** | Roommate | Same as Hassan |
| **Malik Tariq** | Landlord | Property only - 2 pages |
| **Usman Electrician** | Maintenance | Work orders - 2 pages |

**OR** click "Continue as Guest" for public info (no login needed)

### Step 3: Explore!
- Click around the dashboard
- Try the navigation links
- Click "Logout" when done

---

## 🎯 What to Test

### As Admin (Ahmed Khan):
✅ View all finances (PKR 53,100/month)  
✅ See everyone's chores  
✅ Manage maintenance tickets  
✅ Access shopping list  
✅ Manage users  
✅ Post announcements  
✅ Full control  

### As Roommate (Hassan/Fatima):
✅ View personal payments (PKR 13,275 share)  
✅ See my assigned chores  
✅ Add to shopping list  
✅ Read announcements  
❌ Cannot access maintenance or users  

### As Landlord (Malik Tariq):
✅ View rent payments (PKR 45,000/month)  
✅ Track maintenance needing approval  
❌ Cannot access daily household operations  

### As Maintenance (Usman):
✅ View work orders (UPS battery, Gas leak)  
✅ Update ticket status  
✅ Add work notes  
❌ Cannot access other pages  

### As Guest:
✅ View Wi-Fi info  
✅ See house rules  
✅ Emergency contacts (15, 1122, 16)  
✅ No login needed  

---

## 🔐 Manual Login

If you want to type credentials instead of clicking cards:

### Admin Access:
```
Username: ahmed
Password: admin123
Role: Admin
```

### Roommate Access:
```
Username: hassan  OR  fatima
Password: room123
Role: Roommate
```

### Landlord Access:
```
Username: malik
Password: land123
Role: Landlord
```

### Maintenance Access:
```
Username: usman
Password: maint123
Role: Maintenance Staff
```

---

## 🇵🇰 Pakistani Context

### Currency (PKR):
- Monthly Rent: **PKR 45,000**
- Electricity: **PKR 3,500**
- Internet: **PKR 2,000**
- Gas: **PKR 1,800**
- Water: **PKR 800**
- **Per Person: PKR 13,275** (for 4 people)

### Chores (Urdu):
- Bartan Dhona (Wash Dishes)
- Jhadu Lagana (Sweep Floor)
- Safai Karna (Clean Kitchen)
- Kamray Ki Safai (Room Cleaning)

### Shopping (Pakistani Items):
- Chawal (Rice) - PKR 600
- Atta (Flour) - PKR 850
- Daal (Lentils) - PKR 420

### Maintenance (Local Issues):
- Load Shedding UPS Battery
- Gas Leakage (SSGC)
- AC Filter Cleaning

### Location:
**House #12, Street 5, F-10/3, Islamabad**

### Emergency Numbers:
- 🚨 Police: **15**
- 🚑 Ambulance: **1122**
- 🔥 Fire: **16**
- 💨 Gas (SSGC): **1199**

---

## ⚠️ Common Issues

### Problem: Login doesn't work
**Solution:** Make sure you select the correct role from dropdown!

### Problem: Redirected back to login
**Solution:** You might not have permission for that page. Check your role's access level.

### Problem: Session lost
**Solution:** Sessions clear when you close the browser. Just login again!

### Problem: Can't access certain pages
**Solution:** This is normal! Each role has different permissions. Check the access matrix in README.md.

---

## 🎨 Theme Colors

**Pakistan Flag Colors:**
- 🟢 Dark Green: `#01411C`
- 🟢 Light Green: `#0A6738`
- ⚪ White: `#FFFFFF`
- 🟡 Gold: `#FFB800`

---

## 📱 Mobile Testing

Resize your browser window to test responsive design:
- **Desktop:** 1024px+
- **Tablet:** 768px - 1023px
- **Mobile:** < 768px

---

## 🔍 Debugging

Open Browser DevTools (Press **F12**):

1. **Console Tab** - Check for JavaScript errors
2. **Application Tab** → Session Storage
   - Look for `currentUser` key
   - See your login data
3. **Clear Session:**
   - Right-click `currentUser` → Delete
   - Or click Logout button

---

## 📚 Need More Info?

- **README.md** - Complete overview and features
- **REFACTOR-GUIDE.md** - Detailed technical documentation
- **Browser Console** - Check for error messages

---

## 🎉 Have Fun!

Explore the different user experiences and see how the system adapts to each role!

**Made with ❤️ in Pakistan 🇵🇰**
