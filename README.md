# Bistro Briss REST API

**REST API** لمشروع مطعم **Bistro Briss** باستخدام **Laravel 12** و **Sanctum** للمصادقة.

## 📦 المميزات
- CRUD للحجوزات
- نظام إشعارات للمستخدمين والأدمن
- إدارة الأقسام Menu Categories
- إدارة عناصر المنيو Menu Items
- نظام صلاحيات: مستخدم / أدمن
- واجهة API فقط، بدون Blade

## 🔧 التقنيات المستخدمة
- PHP 8+
- Laravel 12
- MySQL
- Sanctum Authentication
- Composer & NPM
- Git & GitHub

## 🚀 البدء بسرعة
1. استنساخ الريبو:
```bash
git clone https://github.com/AymanEbaid/resturant-api.git
تثبيت التبعيات:

composer install
npm install


إعداد ملف البيئة:

cp .env.example .env
php artisan key:generate


تشغيل السيرفر:

php artisan serve

📄 التوثيق

جميع Endpoints موجودة في ملف Postman / أو في Docs (إذا حابب تعمل Postman collection)

مثال على طلب تسجيل مستخدم:

POST /api/register
{
  "name": "Ayman",
  "email": "example@mail.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}

👨‍💻 المطور

Ayman Mohamed

Email: ayman.mohamed.ebaid@gmail.com

GitHub: https://github.com/AymanEbaid
