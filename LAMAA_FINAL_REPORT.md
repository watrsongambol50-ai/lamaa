# تقرير التسليم النهائي — تطبيق لماء LAMAA
> تاريخ الإصدار: 16 يوليو 2026 | Laravel 13 | PHP 8.4

---

## 🔐 بيانات الدخول للمشرف العام

> **⚠️ احتفظ بهذه البيانات في مكان آمن — لا تشاركها علناً**

| البيان | القيمة |
|--------|--------|
| رقم الجوال | `0500000000` |
| كلمة المرور | `Lamaa@20bb8fead6` |
| متغير البيئة | `ADMIN_PASSWORD=Lamaa@20bb8fead6` في `.env` |

لتغيير كلمة المرور: عدّل `ADMIN_PASSWORD` في `.env` ثم أعد تشغيل `php artisan db:seed`.

---

## 📦 ملخص ما تم إنجازه

### 1. الأمان (Security)
- **APP_DEBUG** → ضُبط على `false` في `.env` و`.env.example` (كان `true`)
- **كلمة مرور الأدمين** → مُولَّدة برمجياً قوية (`Lamaa@20bb8fead6`)، لا hardcoded
- **دور المستخدم (role)** → كل نقاط `User::create()` تُحدد الدور صراحةً؛ الكود الميت (Fortify) مُوثَّق وآمن
- **CSRF** → محمي بالكامل عبر middleware Laravel الافتراضي
- **قيود الإلغاء** → الإلغاء مسموح فقط بحالتَي `new` أو `picked_up`
- **عزل الأدوار** → admin/staff/customer مفصول تماماً في كل مسار وعرض

### 2. إصلاحات الأعمال (Business Logic)
- **ولاء العملاء (Loyalty)** → مُوحَّد: كل 5 طلبات، الطلب الخامس مجاني بالكامل
  - كان: 3 تعريفات متضاربة (total_orders%4 / total_orders%5 / total_pieces_washed%5)
  - أصبح: مصدر واحد في `User::getLoyaltyProgressAttribute()` يتبع `OrderController::isLoyaltyFreeOrder()` الفعلي
- **توصيل مجاني** → كل 3 طلبات (منفصل عن الولاء، صحيح)
- **POS للأدمين** → يُنشئ حساب عميل جديد بدور `customer` إذا لم يوجد

### 3. واجهة المستخدم (UX/UI)
- **Onboarding** → 3 شرائح قابلة للتمرير بتصميم تدرج لوني، نقاط متحركة، زر "ابدأ" وزر "تخطى"
- **الصفحة الرئيسية** → الخدمات والأسعار أصبحت أفقية قابلة للتمرير (مثل "كيف يعمل") مع نافذة منبثقة للتفاصيل
- **لوجو وضع الليل** → مُغلَّف بخلفية بيضاء ثابتة (لا يختفي في الداكن بعد الآن)
- **الإشعارات** → الجرس مُصنَّف: جرس العملاء ≠ جرس الأدمين ≠ الطاقم (staff لا يرى الاثنين)
- **أيقونات** → استُبدلت Emoji 🇸🇦 بـ SVG احترافي

### 4. الخريطة والتحديد الجغرافي
- **إنشاء الطلب** → JavaScript يستخدم Geolocation API لحفظ lat/lng في النموذج
- **لوحة الأدمين** → صفحة تفاصيل الطلب تعرض بطاقة OpenStreetMap iframe + رابط "فتح في Google Maps"
- **النطاق** → لا تتبع مباشر للسائق (خارج النطاق — لا بنية تحتية للسائقين)

### 5. PWA (تطبيق قابل للتثبيت)
- **manifest.json** → أيقونات PNG (192/512/maskable)، اتجاه RTL، اللغة العربية
- **Service Worker** → نمط cache-then-network، app-shell precache، offline fallback
- **صفحة offline.html** → صفحة احترافية عند انقطاع الاتصال
- **بانر التثبيت** → يظهر تلقائياً عبر `beforeinstallprompt` للمتصفح

### 6. الدفع الإلكتروني (Moyasar)
- **الحالة** → مُدمج كاملاً، ينتظر مفاتيح API فقط
- **كيفية التفعيل:**
  ```
  MOYASAR_PUBLISHABLE_KEY=pk_live_xxxxxxxx
  MOYASAR_SECRET_KEY=sk_live_xxxxxxxx
  ```
  بعد الإضافة إلى `.env` يظهر خيار "دفع إلكتروني" تلقائياً
- **CDN المستخدم** → `@moyasar/web-sdk@1.14.0` عبر unpkg (مُتحقق، HTTP 200)
- **متطلبات Go-Live:**
  1. حساب موثَّق على https://dashboard.moyasar.com
  2. إضافة مفاتيح الـ Live إلى `.env` على السيرفر
  3. ضبط Callback URL في لوحة Moyasar: `https://yourdomain.com/orders/{id}/payment-callback`

### 7. الرسائل النصية (Msegat)
- **الحالة** → مُدمج كاملاً، ينتظر بيانات API
- **كيفية التفعيل:**
  ```
  MSEGAT_USERNAME=your_username
  MSEGAT_API_KEY=your_api_key
  MSEGAT_SENDER_NAME=LAMAA
  ```
- **متطلبات Go-Live:**
  1. حساب على https://www.msegat.com مع هوية مفعَّلة
  2. رقم مرسِل (Sender Name) معتمد: `LAMAA`
  3. إضافة الرصيد (أقل من 10,000 رسالة = حزمة صغيرة)

### 8. إلغاء الطلب (Customer Cancellation)
- العميل يُلغي طلبه من صفحة الطلب — الحالات المسموحة: `new` أو `picked_up`
- يُسجَّل: `cancelled_at` (timestamp) + `cancel_reason` (نص)
- **اختُبر** ✅: Order#1 أُلغيَ بنجاح، الحالة تحوّلت لـ `cancelled`

### 9. التقارير المالية
- **المسار** → `/admin/reports`
- **المحتوى** → فلتر بالتاريخ، ملخص إيرادات، مخطط يومي بـ CSS، أكثر الخدمات طلباً، توزيع الحالات، الخصومات

### 10. إصلاح ازدواجية الـ Migration
- كانت: migration Laravel الافتراضي + migration مخصص كلاهما يُنشئان جدول `users` → الـ migrate يفشل
- أصبحت: migration الافتراضي أُزيل منه إنشاء `users` (يُبقي فقط password_reset_tokens/sessions)

---

## 🗄️ التغييرات على قاعدة البيانات

> **إشعار مهم** — هذه التغييرات تطلَّبت migrations جديدة

### Migration الجديد: `2026_07_15_000001_add_geo_payment_cancel_fields_to_orders_table`

أُضيفت الحقول التالية لجدول `orders`:

| الحقل | النوع | الوصف |
|-------|-------|--------|
| `lat` | decimal(10,7) nullable | خط العرض لعنوان العميل |
| `lng` | decimal(10,7) nullable | خط الطول لعنوان العميل |
| `cancelled_at` | timestamp nullable | وقت الإلغاء |
| `cancel_reason` | text nullable | سبب الإلغاء |
| `payment_status` | varchar(20) | حالة الدفع: unpaid/pending/paid/failed |
| `payment_reference` | varchar(100) nullable | مرجع عملية الدفع من Moyasar |

### قاعدة البيانات
- **بيئة التطوير** → SQLite (`database/database.sqlite`) — لأن MySQL غير متاح في بيئة Replit
- **الإنتاج** → أعد تفعيل MySQL في `.env` (الإعدادات موجودة ومُعلَّق عليها)
- **ترقية قاعدة بيانات موجودة في الإنتاج:**
  ```bash
  php artisan migrate --force
  ```

---

## 🚀 متطلبات الإطلاق (Go-Live Checklist)

```bash
# 1. نسخ .env.example وتعبئة القيم الحقيقية
cp .env.example .env

# 2. القيم المطلوبة في .env للإنتاج:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=lamaa_db
DB_USERNAME=lamaa_user
DB_PASSWORD=your_strong_password
ADMIN_PASSWORD=YourNewStrongAdminPassword
MOYASAR_PUBLISHABLE_KEY=pk_live_...
MOYASAR_SECRET_KEY=sk_live_...
MSEGAT_USERNAME=...
MSEGAT_API_KEY=...
MSEGAT_SENDER_NAME=LAMAA

# 3. تشغيل الـ migrations والـ seeder
php artisan migrate --force
php artisan db:seed --force

# 4. توليد مفتاح التطبيق (مرة واحدة فقط)
php artisan key:generate --force

# 5. تحسينات الأداء للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. صلاحيات storage
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ نتائج الاختبار الشامل

| الوظيفة | النتيجة |
|---------|---------|
| تسجيل عميل جديد | ✅ |
| تسجيل دخول الأدمين | ✅ |
| صفحة Onboarding (3 شرائح) | ✅ |
| الصفحة الرئيسية — خدمات أفقية | ✅ |
| إنشاء طلب مع إحداثيات | ✅ |
| إلغاء طلب (حالة new) | ✅ |
| لوحة إدارة — كل المسارات | ✅ |
| خريطة OpenStreetMap في تفاصيل الطلب | ✅ |
| التقارير المالية | ✅ |
| لوحة ولاء العملاء (متسقة مع OrderController) | ✅ |
| منع الدفع الإلكتروني بدون مفاتيح | ✅ |
| إشعارات منفصلة (admin ≠ customer ≠ staff) | ✅ |
| PWA manifest + service worker | ✅ |
| لوجو لا يختفي في الوضع الداكن | ✅ |
| APP_DEBUG=false | ✅ |

---

## 📁 الملفات الجديدة والمُعدَّلة الرئيسية

```
app/Http/Controllers/
  OrderController.php          ← إلغاء، جيولوكيشن، دفع، ولاء موحَّد
  Admin/AdminReportController.php  ← جديد: تقارير مالية
  DashboardController.php      ← ولاء موحَّد
  LoyaltyController.php        ← ولاء موحَّد
  Admin/AdminPosController.php ← role=customer عند إنشاء العميل

app/Models/User.php            ← loyalty_progress/next_free_order موحَّدان

app/Services/
  PaymentService.php           ← Moyasar integration
  SmsService.php               ← Msegat integration

resources/views/
  onboarding.blade.php         ← إعادة تصميم كاملة (3 شرائح)
  home.blade.php               ← خدمات أفقية + تقليص النص المكرر
  layouts/app.blade.php        ← إشعارات منفصلة + PWA banner
  layouts/auth/simple.blade.php ← لوجو لا يختفي في الداكن
  orders/pay.blade.php         ← Moyasar hosted payment
  orders/create.blade.php      ← جيولوكيشن + خريطة
  admin/orders/show.blade.php  ← خريطة OpenStreetMap + Google Maps link
  admin/reports/index.blade.php ← جديد: تقارير مالية
  admin/index.blade.php        ← القوائم بحسب الدور

database/migrations/
  0001_01_01_000000_*          ← إزالة تعارض users table
  2026_07_15_000001_*          ← حقول geo/payment/cancel جديدة

public/
  manifest.json                ← PWA manifest محسَّن
  sw.js                        ← Service Worker معاد كتابته
  offline.html                 ← صفحة انقطاع الاتصال
  icons/icon-192.png           ← أيقونة PWA
  icons/icon-512.png           ← أيقونة PWA كبيرة
  icons/icon-maskable-512.png  ← أيقونة maskable
```

---

*تقرير مُولَّد تلقائياً — LAMAA Laundry App v2.0*
