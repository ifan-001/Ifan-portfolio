# Paid hosting (cPanel) এ live করার গাইড

কেন paid hosting: InfinityFree এর bot-check live chat, admin notification আর AI assistant এর background request আটকে দেয়। Paid cPanel hosting এ এই সমস্যা নেই, সব ঠিকঠাক চলবে।

> কেনার (payment) কাজটা আপনাকেই করতে হবে। বাংলাদেশের বেশিরভাগ host bKash / Nagad / card এ টাকা নেয়।

---

## ধাপ ১: কী কিনবেন (এই তালিকা মিলিয়ে নিন)

Hosting বেছে নেওয়ার আগে sales এ জিজ্ঞেস করুন বা plan এর পাতায় দেখুন:

| দরকার | মান |
|---|---|
| Control panel | **cPanel** |
| PHP | **8.2 বা তার উপরে** (8.3 হলে ভালো), Select PHP Version / MultiPHP থাকতে হবে |
| PHP extensions | `mbstring`, `pdo_mysql`, `zip`, `fileinfo`, `openssl`, `curl`, `xml`, `gd`, `intl` |
| Database | MySQL / MariaDB, phpMyAdmin |
| SSL | Free SSL (AutoSSL / Let's Encrypt) |
| Space | অন্তত 2 GB SSD |
| Domain | `.com` (সাধারণত বছরে কয়েক হাজার টাকার মধ্যে, hosting এর সাথেই কেনা যায়) |
| `.htaccess` | চালু থাকতে হবে (Apache / LiteSpeed, mod_rewrite) |

SSH না থাকলেও চলবে। আমাদের package আগে থেকেই সব library সহ তৈরি।

**Domain নাম:** আপনার চাওয়া অনুযায়ী `ifan...` দিয়ে শুরু হয় এমন কিছু নিন, যেমন `ifan.com` বা `ifanportfolio.com` (পাওয়া গেলে)।

## ধাপ ২: cPanel এ গিয়ে (hosting কেনার পরে)

1. **Select PHP Version** (বা MultiPHP Manager) → আপনার domain এ **PHP 8.2+** বাছুন, উপরের extension গুলো টিক দিন।
2. **MySQL® Databases**:
   - নতুন database বানান, নাম `portfolio` (cPanel শুরুতে নিজে prefix বসায়, যেমন `cpuser_portfolio`)।
   - নতুন database user বানান, শক্ত password দিন।
   - **Add User To Database** এ user কে database এ যোগ করুন, **ALL PRIVILEGES** দিন।
3. **phpMyAdmin** → আপনার database বাছুন → **Import** → `database.sql` (ধাপ ৩ এর পরে) → **Go**।

## ধাপ ৩: আপনার PC তে zip বানানো

```powershell
cd D:\portfolio
powershell -ExecutionPolicy Bypass -File deploy\pack-for-cpanel.ps1
```
এটা জিজ্ঞেস করবে:
- site এর ঠিকানা (যেমন `https://ifan.com`)
- database name, user, password (password hidden থাকে, শুধু zip এর ভেতরের `.env` এ বসে)

ফলাফল: `storage\backups\shared-hosting\cpanel\` এ `site.zip` আর `database.sql`।

> **আপনার InfinityFree site এ যদি নতুন কিছু বদলে থাকেন** (post, ছবি, text), আগে সেগুলো আনুন:
> - phpMyAdmin → **Export** → `database.sql` নামিয়ে ওই ফোল্ডারে বসান।
> - FTP/File Manager দিয়ে `htdocs/public/images`, `public/uploads`, `public/resume` নামিয়ে নিন।

## ধাপ ৪: Upload ও extract

1. cPanel → **File Manager** → `public_html` খুলুন (domain এর root)।
2. আগের default ফাইল (`index.html`, `cgi-bin` বাদে) মুছে ফেলুন।
3. **Upload** → `site.zip` দিন (প্রায় 25 MB, সাধারণত লাগে 1-2 মিনিট)।
4. `site.zip` এ right click → **Extract** → `public_html` এ।
5. extract হলে `site.zip` মুছে দিন।
6. Hidden ফাইল দেখতে File Manager এর **Settings → Show Hidden Files** টিক দিন, `.env` আর `.htaccess` আছে কিনা দেখুন।

## ধাপ ৫: Permission ও SSL

- `storage`, `bootstrap/cache`, `public/images`, `public/uploads`, `public/resume` ফোল্ডারের permission **755** (লেখা না গেলে **775**)।
- cPanel → **SSL/TLS Status** → **Run AutoSSL**। HTTPS চালু হলে site `https://` এ খুলবে।
  - SSL চালু হওয়ার আগে `pack-for-cpanel.ps1` চালালে ঠিকানা `http://...` দিন, তাহলে script নিজে https redirect বাদ দেয়।

## ধাপ ৬: যাচাই

- [ ] `https://আপনার-domain/` খুলছে
- [ ] `https://আপনার-domain/portal/login` এ admin এর email/password দিয়ে ঢোকা যাচ্ছে
- [ ] Live chat এ message পাঠালে admin **Inbox** এ আসছে (এটাই InfinityFree এ কাজ করেনি)
- [ ] Admin থেকে ছবি upload হচ্ছে, CV download হচ্ছে
- [ ] ফোনে খুলে **Install** করা যাচ্ছে

## পরে code বদলালে

বদলানো ফাইলগুলো File Manager দিয়ে `public_html` এ বসান, বা আমাকে বলুন আমি নতুন zip বানিয়ে দেব।

## Backup

cPanel এর **Backup** বা **JetBackup** থাকলে চালু রাখুন। না থাকলে মাসে একবার phpMyAdmin **Export** আর `public/images`, `public/uploads`, `public/resume` নামিয়ে রাখুন।

## সমস্যা হলে

| লক্ষণ | সমাধান |
|---|---|
| 500 error | `.env` এর database তথ্য ঠিক আছে কিনা, PHP 8.2+ বাছা আছে কিনা দেখুন। দরকারে সাময়িক `APP_DEBUG=true` করে কারণ দেখুন, তারপর আবার `false` |
| শুধু প্রথম page খোলে, বাকি 404 | `.htaccess` আপলোড হয়নি (Show Hidden Files) |
| Login এ 419 | `APP_URL` আর http/https মিলছে কিনা, HTTPS না থাকলে `SESSION_SECURE_COOKIE=false` |
| Upload ব্যর্থ | উপরের ফোল্ডারগুলোর permission 775 |
| `Class ZipArchive`/extension error | Select PHP Version এ সংশ্লিষ্ট extension টিক দিন |
