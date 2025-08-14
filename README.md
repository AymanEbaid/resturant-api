# 🍽 Bistro Briss - Restaurant Reservation API

هذا المشروع عبارة عن **RESTful API** تم تطويره باستخدام **Laravel 12** لإدارة نظام حجز الطاولات لمطعم **Bistro Briss**.  
يتيح النظام للمستخدمين حجز طاولة، وإدارة الحجوزات، ويحتوي على نظام إشعارات للتأكيد أو الإلغاء.

---

## 🚀 المميزات

- تسجيل مستخدمين جدد وتسجيل الدخول باستخدام **JWT Authentication**.
- حجز الطاولات مع تحديد:
  - التاريخ
  - الوقت
  - عدد الضيوف
- نظام إشعارات:
  - إشعار للمشرف عند حجز جديد.
  - إشعار للمستخدم عند تأكيد أو إلغاء الحجز.
- صلاحيات المستخدمين (User / Admin).
- API منظم باستخدام **Resource Controllers**.

---

## 🛠 المتطلبات

- PHP >= 8.1
- Composer
- MySQL
- Laravel 12
- Node.js & NPM (اختياري لتشغيل الـ Frontend)

---

| الطريقة | المسار                       | الوصف                |
| ------- | ---------------------------- | -------------------- |
| POST    | `/api/register`              | تسجيل مستخدم جديد    |
| POST    | `/api/login`                 | تسجيل الدخول         |
| GET     | `/api/bookings`              | عرض الحجوزات (Admin) |
| POST    | `/api/bookings`              | إنشاء حجز جديد       |
| PUT     | `/api/bookings/{id}/confirm` | تأكيد الحجز (Admin)  |
| PUT     | `/api/bookings/{id}/cancel`  | إلغاء الحجز (Admin)  |
👨‍💻 المطور

Ayman Mohamed
📧 Email: ayman.mohamed.ebaid@gmail.com
🔗 LinkedIn
💻 GitHub
