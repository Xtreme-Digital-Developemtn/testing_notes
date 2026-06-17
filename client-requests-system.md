# Client Request Management System — Laravel + Blade

## نظرة عامة على المشروع

نظام لإدارة ومتابعة طلبات العملاء، يتيح للعميل رفع مشاكله ومتابعة حالتها، ويتيح للأدمن مراجعتها وتغيير حالتها وإرسال رسائل للعميل.

---

## التقنيات المستخدمة

- **Backend:** Laravel (PHP)
- **Frontend:** Blade Templates
- **Database:** MySQL
- **Storage:** Laravel Storage (local disk)
- **Auth:** Laravel Breeze أو Fortify (Guard منفصل للأدمن والعميل)

---

## المستخدمون (Guards)

| Guard | الدور |
|-------|-------|
| `web` (client) | العميل — يرفع طلبات ويتابعها |
| `admin` | الأدمن — يراجع الطلبات ويديرها |

---

## قاعدة البيانات — الجداول

### 1. `users` — العملاء
```
id, name, email, password, timestamps
```

### 2. `admins` — المسؤولون
```
id, name, email, password, timestamps
```

### 3. `requests` — الطلبات
```
id
user_id          (FK → users)
title            (string)
description      (text)
priority         (enum: low, medium, high)
status           (enum: not_started, in_progress, solved)
image_path       (string, nullable)
video_path       (string, nullable)
created_at
updated_at
```

### 4. `notifications` — الإشعارات
```
id
user_id          (FK → users)
request_id       (FK → requests)
type             (enum: in_progress, solved)
message          (string)
is_read          (boolean, default: false)
created_at
updated_at
```

### 5. `messages` — الرسائل من الأدمن للعميل
```
id
user_id          (FK → users)
admin_id         (FK → admins)
request_id       (FK → requests, nullable)
body             (text)
is_read          (boolean, default: false)
created_at
updated_at
```

### 6. `media_files` — Storage للملفات المرفوعة
```
id
user_id          (FK → users)
request_id       (FK → requests)
type             (enum: image, video)
path             (string)
original_name    (string)
size             (bigInteger) — bytes
created_at
updated_at
```

---

## وظائف العميل (Client Panel)

### 1. رفع طلب جديد
- حقل: `title` (نص إلزامي)
- حقل: `description` (نص إلزامي)
- حقل: `priority` (اختيار: low / medium / high — إلزامي)
- حقل: `image` (صورة — اختياري، مسموح: jpg, png, jpeg, max 5MB)
- حقل: `video` (فيديو — اختياري، مسموح: mp4, mov, max 50MB)
- `created_at` يُحدد تلقائياً عند الرفع
- بعد الرفع: حالة الطلب تبدأ `not_started` تلقائياً
- الملفات تُحفظ في `storage/app/private/requests/{user_id}/` وتُسجَّل في جدول `media_files`

### 2. قائمة الطلبات (مع فلاتر)
عرض جميع طلبات العميل المسجل دخوله مع الفلاتر التالية:
- **البحث بكلمة:** تبحث في `title` و `description`
- **الفلترة بالتاريخ:** من تاريخ / إلى تاريخ
- **الفلترة بالسنة + الشهر:** يختار السنة ثم الشهر
- **الفلترة بالأولوية:** low / medium / high
- **الفلترة بالحالة:** not_started / in_progress / solved

جميع الفلاتر تعمل معاً (يمكن تطبيق أكثر من فلتر في نفس الوقت) عن طريق GET query parameters.

### 3. تفاصيل الطلب
- عرض كامل بيانات الطلب
- عرض الصورة والفيديو المرفقَين إن وجدا
- عرض الحالة الحالية مع badge ملون:
  - `not_started` → رمادي
  - `in_progress` → أصفر/برتقالي
  - `solved` → أخضر

### 4. لوحة التحكم (Dashboard)
إحصائيات خاصة بالعميل:
- إجمالي الطلبات
- عدد الطلبات `not_started`
- عدد الطلبات `in_progress`
- عدد الطلبات `solved`
- آخر 5 طلبات

### 5. الإشعارات
- صفحة مخصصة لعرض إشعارات العميل
- يظهر عدد الإشعارات غير المقروءة في الـ navbar
- عند فتح صفحة الإشعارات تُعلَّم كـ مقروءة تلقائياً
- الإشعارات نوعان:
  - عند تغيير الحالة إلى `in_progress`: "بدأ العمل على طلبك: {title}"
  - عند تغيير الحالة إلى `solved`: "تم حل طلبك: {title}"
- فلاتر الإشعارات: الكل / مقروء / غير مقروء / النوع (in_progress / solved)

### 6. الرسائل
- صفحة لعرض الرسائل الواردة من الأدمن
- كل رسالة تعرض: نص الرسالة + اسم الأدمن + التاريخ + الطلب المرتبط (اختياري)
- يظهر عدد الرسائل غير المقروءة في الـ navbar
- عند فتح الرسالة تُعلَّم كمقروءة

### 7. Storage (مكتبة الملفات)
- صفحة تعرض جميع الصور والفيديوهات المرفوعة من العميل على مدار طلباته
- فلاتر:
  - النوع: صور / فيديوهات / الكل
  - الطلب المرتبط
  - التاريخ (سنة / شهر)
- عرض الصور كـ grid thumbnails
- رابط تحميل / عرض لكل ملف

---

## وظائف الأدمن (Admin Panel)

### 1. قائمة جميع الطلبات
- عرض جميع طلبات جميع العملاء
- نفس فلاتر العميل + فلتر إضافي بـ `user_id` (اختيار عميل محدد)
- ترتيب: الأحدث أولاً

### 2. تفاصيل الطلب
- عرض كامل البيانات
- عرض الصورة والفيديو
- **زر تغيير الحالة:**
  - `not_started` → `in_progress`
  - `in_progress` → `solved`
  - الأدمن يستطيع كذلك تغيير إلى `not_started` (رفض / إعادة)
- عند كل تغيير للحالة → يُنشأ إشعار في جدول `notifications` للعميل صاحب الطلب

### 3. إرسال رسالة للعميل
- من صفحة تفاصيل الطلب أو صفحة مستقلة
- حقول: اختيار العميل (إن لم يكن محدداً) + نص الرسالة + الطلب المرتبط (اختياري)
- الرسالة تُحفظ في جدول `messages`
- **ليست Real-time** (لا Pusher ولا WebSocket)

### 4. إدارة العملاء
- قائمة العملاء المسجلين
- عرض إحصائيات كل عميل

### 5. Dashboard الأدمن
- إجمالي الطلبات في النظام
- توزيع الطلبات حسب الحالة
- توزيع الطلبات حسب الأولوية
- عدد العملاء

---

## منطق الإشعارات

```
عند تغيير status من الأدمن:
  إذا status = in_progress:
    أنشئ notification للعميل (type: in_progress)
    message: "بدأ العمل على طلبك: {request.title}"

  إذا status = solved:
    أنشئ notification للعميل (type: solved)
    message: "تم حل طلبك: {request.title}"

  إذا status = not_started:
    لا يُنشأ إشعار
```

الإشعارات **ليست Real-time** — تظهر عند تحديث الصفحة فقط.

---

## Routes — هيكل المسارات

### Client Routes (middleware: auth)
```
GET    /dashboard                          → client.dashboard
GET    /requests                           → client.requests.index
GET    /requests/create                    → client.requests.create
POST   /requests                           → client.requests.store
GET    /requests/{id}                      → client.requests.show
GET    /notifications                      → client.notifications.index
GET    /messages                           → client.messages.index
GET    /storage-files                      → client.storage.index
```

### Admin Routes (middleware: auth:admin, prefix: /admin)
```
GET    /admin/dashboard                    → admin.dashboard
GET    /admin/requests                     → admin.requests.index
GET    /admin/requests/{id}                → admin.requests.show
PATCH  /admin/requests/{id}/status        → admin.requests.updateStatus
GET    /admin/messages/create              → admin.messages.create
POST   /admin/messages                     → admin.messages.store
GET    /admin/clients                      → admin.clients.index
```

---

## Controllers

| Controller | Guard | المسؤولية |
|-----------|-------|-----------|
| `ClientRequestController` | client | رفع وعرض الطلبات |
| `ClientDashboardController` | client | الإحصائيات |
| `ClientNotificationController` | client | الإشعارات |
| `ClientMessageController` | client | الرسائل الواردة |
| `ClientStorageController` | client | مكتبة الملفات |
| `AdminRequestController` | admin | مراجعة الطلبات وتغيير الحالة |
| `AdminMessageController` | admin | إرسال رسائل للعمالء |
| `AdminDashboardController` | admin | إحصائيات الأدمن |
| `AdminClientController` | admin | إدارة العملاء |

---

## File Storage

```
storage/app/private/
└── requests/
    └── {user_id}/
        └── {request_id}/
            ├── image.jpg
            └── video.mp4
```

- استخدم `Storage::disk('local')` للحفظ
- استخدم `Storage::url()` أو signed URL للعرض
- تحقق من الصلاحية: العميل يرى ملفاته فقط

---

## Validation Rules

### رفع الطلب
```php
'title'       => 'required|string|max:255',
'description' => 'required|string',
'priority'    => 'required|in:low,medium,high',
'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
'video'       => 'nullable|mimes:mp4,mov|max:51200',
```

### تغيير الحالة (أدمن)
```php
'status' => 'required|in:not_started,in_progress,solved',
```

### إرسال رسالة (أدمن)
```php
'user_id'    => 'required|exists:users,id',
'body'       => 'required|string|max:2000',
'request_id' => 'nullable|exists:requests,id',
```

---

## Blade Views — هيكل الملفات

```
resources/views/
├── layouts/
│   ├── client.blade.php       (navbar + sidebar للعميل)
│   └── admin.blade.php        (navbar + sidebar للأدمن)
├── client/
│   ├── dashboard.blade.php
│   ├── requests/
│   │   ├── index.blade.php    (قائمة + فلاتر)
│   │   ├── create.blade.php   (فورم رفع الطلب)
│   │   └── show.blade.php     (تفاصيل الطلب)
│   ├── notifications/
│   │   └── index.blade.php
│   ├── messages/
│   │   └── index.blade.php
│   └── storage/
│       └── index.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── requests/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── messages/
    │   └── create.blade.php
    └── clients/
        └── index.blade.php
```

---

## ملاحظات التنفيذ

1. **Authentication:** استخدم Guard منفصل للأدمن (`config/auth.php`) مع جدول `admins` مستقل
2. **Notifications:** ليست Laravel Notifications الرسمية — جدول `notifications` يدوي مع query بسيطة
3. **Real-time:** لا يوجد — كل شيء يتحدث عند تحديث الصفحة (polling غير مطلوب)
4. **Filters:** جميع الفلاتر عبر GET parameters ومعالجتها في الـ Controller بـ `when()` على الـ Query Builder
5. **Storage access:** استخدم `route('storage.serve', $file->id)` مع middleware للتحقق من الملكية
6. **Seeder:** أنشئ `AdminSeeder` لإضافة أدمن افتراضي
7. **Status badge:** استخدم Blade component أو helper لعرض الـ badge بالألوان الصحيحة

---

## ترتيب التنفيذ المقترح للـ Agent

1. إنشاء المشروع وضبط `.env`
2. إنشاء Migrations بالترتيب: `users` → `admins` → `requests` → `notifications` → `messages` → `media_files`
3. إنشاء Models مع العلاقات
4. ضبط Auth Guards في `config/auth.php`
5. إنشاء Controllers للعميل
6. إنشاء Controllers للأدمن
7. تعريف Routes في `web.php`
8. إنشاء Blade layouts ثم Views
9. تنفيذ منطق الإشعارات عند تغيير الحالة
10. إنشاء Seeder للأدمن الافتراضي
11. اختبار جميع الفلاتر والصلاحيات
