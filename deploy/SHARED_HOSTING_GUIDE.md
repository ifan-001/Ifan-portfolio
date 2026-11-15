# Card ছাড়া free hosting এ live করার গাইড (InfinityFree)

কোনো card লাগে না। একই package যেকোনো cPanel/shared hosting এও (যেমন বাংলাদেশের paid hosting) কাজ করবে।

> মেনুর নাম বা জায়গা hosting প্যানেলে একটু আলাদা হতে পারে। মূল ধাপ একই।

## আপনার কাছে যা তৈরি আছে

`D:\portfolio\storage\backups\shared-hosting\` ফোল্ডারে:

| ফাইল | কী |
|---|---|
| `site\` | আপলোডের জন্য পুরো site (vendor সহ), ভেতরে `.env` আর `.htaccess` ও আছে |
| `site.zip` | একই জিনিস zip আকারে (প্রায় 25 MB) |
| `database.sql` | আপনার সব content সহ database (visitor এর test chat বাদ) |

> এই ফোল্ডারে আপনার admin এর password hash আছে। কাউকে পাঠাবেন না, GitHub এও দেবেন না (ফোল্ডারটা git এ যায় না)।

---

## ধাপ ১: Account খোলা

1. https://www.infinityfree.com এ **Sign Up** করুন (email দিয়ে, card লাগবে না)।
2. **Create Account** → **Free Subdomain** বাছুন, যেমন `ifan` + `.infinityfreeapp.com` (বা অন্য যে extension দেয়)।
3. Account চালু হতে কয়েক মিনিট লাগে। Active হলে **Control Panel** এ ঢুকুন।

## ধাপ ২: PHP version ঠিক করা

Control panel এ **Select PHP Version** (বা PHP Config) খুঁজে **PHP 8.2 বা তার উপরের** version বাছুন। এটা না করলে site চলবে না (Laravel 12 এ PHP 8.2+ লাগে)।

## ধাপ ৩: Database বানানো ও ভরা

1. Control panel → **MySQL Databases** → নতুন database বানান, নাম দিন `portfolio`।
2. যে তথ্য দেখাবে লিখে রাখুন (প্রতিটার নামের শুরুতে নিজে নিজে prefix বসে যায়):
   - **MySQL Host Name** (যেমন `sql123.infinityfree.com`), `localhost` নয়
   - **Database Name** (যেমন `if0_12345678_portfolio`)
   - **User Name** (যেমন `if0_12345678`) আর **Password**
3. **phpMyAdmin** খুলুন → বাঁদিক থেকে আপনার database বাছুন → **Import** → `database.sql` বাছুন → **Go**।
4. 29 টা table তৈরি হলে ঠিক আছে।

## ধাপ ৪: `.env` ফাইল ঠিক করা

`site\.env` ফাইলটা Notepad দিয়ে খুলে এই ঘরগুলো বদলান:

```
APP_URL=https://ifan.infinityfreeapp.com        ← আপনার site এর ঠিকানা
DB_HOST=sql123.infinityfree.com                  ← ধাপ ৩ এর host
DB_DATABASE=if0_12345678_portfolio
DB_USERNAME=if0_12345678
DB_PASSWORD=আপনার-database-password
```
বাকি সব যেমন আছে তেমন থাকবে (`APP_KEY` আগে থেকে বসানো)। Save করুন।

> SSL certificate চালু না হওয়া পর্যন্ত (ধাপ ৭) `APP_URL` এ `http://` আর `SESSION_SECURE_COOKIE=false` দিয়ে রাখুন, নইলে admin login ধরে রাখবে না।

## ধাপ ৫: ফাইল আপলোড

1. **FileZilla** (https://filezilla-project.org) নামান।
2. Control panel এর **FTP Details** থেকে host, username, password নিন (Port 21)।
3. FileZilla এ menu **Server → Force showing hidden files** টিক দিন। না দিলে `.htaccess` আর `.env` দেখা যাবে না, আর সেটাই সবচেয়ে বড় ভুল।
4. দূরের দিকে `htdocs` ফোল্ডারে ঢুকুন, ভেতরের ডিফল্ট `index.html` মুছুন।
5. বাঁদিকে `site` ফোল্ডারের **ভেতরের সব কিছু** (`app`, `bootstrap`, `config`, `public`, `vendor`, `.env`, `.htaccess` ...) টেনে `htdocs` এ ফেলুন।
   - প্রায় 6,500 টা ফাইল, তাই 20 থেকে 40 মিনিট লাগতে পারে। মাঝপথে থামলে আবার দিলে বাকিটা যোগ হবে।
   - Online File Manager এ zip আপলোড করে "Extract" এর অপশন থাকলে `site.zip` দিয়েও করতে পারেন (জায়গা থাকলে এটা অনেক দ্রুত)।
6. আপলোড শেষে `htdocs` এর ভেতরে `.htaccess`, `.env`, `app`, `public`, `vendor` সব আছে কিনা মিলিয়ে নিন।

## ধাপ ৬: প্রথমবার খুলে দেখা

Browser এ আপনার site এর ঠিকানা খুলুন।
- Site আসলে ঠিক আছে।
- **500 বা সাদা page** এলে `.env` এ সাময়িক `APP_DEBUG=true` করে আবার খুলুন, ত্রুটির কারণ দেখাবে। ঠিক হলে আবার `false` করবেন (live এ true রাখা বিপজ্জনক)।

## ধাপ ৭: HTTPS (free SSL)

InfinityFree এর free subdomain (`...gt.tc`, `...infinityfreeapp.com` ইত্যাদি) এ **HTTPS নিজে নিজেই চালু থাকে**, আলাদা certificate নিতে হয় না (নিজের domain হলে Control panel → **SSL Certificates** থেকে নিতে হয়)।

HTTPS চালু হলে:
1. `.env` এ `APP_URL=https://...` আর `SESSION_SECURE_COOKIE=true` করুন।
2. `htdocs/.htaccess` এ `RewriteEngine On` এর ঠিক পরে এই তিন লাইন যোগ করুন, তাহলে `http://` খুললে নিজে `https://` এ চলে যাবে:
   ```
   # Always use HTTPS
   RewriteCond %{HTTPS} off
   RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

HTTPS ছাড়া ফোনের PWA ("Install on Phone") আর secure login কাজ করে না।

## ধাপ ৮: Live হলো কিনা যাচাই

- [ ] `https://আপনার-site/` খুলছে, profile photo আর project এর ছবি আসছে
- [ ] `https://আপনার-site/portal/login` এ আপনার **আসল admin email ও password** দিয়ে ঢোকা যাচ্ছে
- [ ] Admin এ ছোট একটা পরিবর্তন save করে public site এ দেখা গেল
- [ ] Admin থেকে ছোট একটা ছবি upload করা গেল
- [ ] Site থেকে live chat এ message পাঠালে admin inbox এ আসছে
- [ ] CV download হচ্ছে

---

## মনে রাখার বিষয় (Free hosting এর সীমা)

- **Terminal (SSH) নেই:** `php artisan` চালানো যায় না। তাই database বদলাতে হলে SQL ফাইল phpMyAdmin এ import করতে হয়।
- **Code update:** বদলানো ফাইলগুলো FTP দিয়ে আবার আপলোড করতে হবে। বড় পরিবর্তন হলে আমাকে বলুন, আমি নতুন package বানিয়ে দেব।
- **Daily সীমা:** free hosting এ দিনে হিসাবের একটা request সীমা থাকে। Live chat বারবার request পাঠায়, তাই অনেক visitor আসলে সীমা ছুঁতে পারে।
- **Account বন্ধ:** অনেকদিন login না করলে free account বন্ধ হয়ে যেতে পারে। মাসে একবার ঢুকে দেখবেন।
- **Backup নিজেকে নিতে হবে:** মাসে অন্তত একবার phpMyAdmin → **Export** দিয়ে database নামান, আর FTP দিয়ে `htdocs/public/images`, `public/uploads`, `public/resume` নামিয়ে রাখুন।
- বেশি traffic বা স্থায়ী ব্যবহারের জন্য পরে বাংলাদেশি paid hosting (bKash/Nagad দিয়ে কেনা যায়) এ একই package দিয়ে সরানো যাবে।

## সমস্যা হলে

| লক্ষণ | সমাধান |
|---|---|
| শুধু প্রথম page খোলে, বাকি সব 404 | `.htaccess` আপলোড হয়নি। FileZilla তে hidden files দেখান (ধাপ ৫) আর দুটো `.htaccess` (htdocs এ এবং `public` এ) আছে কিনা দেখুন |
| 500 error | `.env` ঠিক আছে কিনা, PHP 8.2+ বাছা আছে কিনা (ধাপ ২), `vendor` পুরোটা আপলোড হয়েছে কিনা দেখুন |
| "Access denied for user" / DB ত্রুটি | `DB_HOST` কে `localhost` রাখবেন না, প্যানেলের দেওয়া host বসান। User আর password আবার মিলিয়ে নিন |
| Login এ **419 Page Expired** | `APP_URL` আর http/https মিলছে কিনা দেখুন। HTTPS না থাকলে `SESSION_SECURE_COOKIE=false` |
| Upload ব্যর্থ | FTP তে `storage`, `bootstrap/cache`, `public/images`, `public/uploads`, `public/resume` এর permission 775 করুন |
| `.env` বদলালাম কিন্তু কাজ হচ্ছে না | `bootstrap/cache` এ কোনো `config.php` থাকলে মুছুন |
