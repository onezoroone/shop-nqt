# NQTDev Shop & Portfolio

NQTDev Shop & Portfolio là website bán sản phẩm số kết hợp portfolio cá nhân. Hệ thống gồm cửa hàng source code/template/plugin, trang dự án, giỏ hàng, đơn hàng, thanh toán USDT thủ công và khu vực quản trị nội dung bằng Backpack.

## Công nghệ

- PHP 8.2+
- Laravel 12
- Backpack CRUD 7 và Backpack Theme Tabler 2
- Tailwind CSS 4
- Vite 7
- Laravel File Manager
- MySQL/MariaDB hoặc SQLite
- PHPUnit 11

## Chức năng chính

- Trang chủ, cửa hàng sản phẩm số và portfolio dự án.
- Tìm kiếm, lọc danh mục, sắp xếp và phân trang sản phẩm.
- Sản phẩm thường hoặc có nhiều biến thể, giá sale và gallery ảnh.
- Đăng ký, đăng nhập, giỏ hàng, checkout và lịch sử đơn hàng.
- Thanh toán USDT thủ công, xác nhận qua Telegram.
- Cấp link tải source code sau khi đơn hàng được duyệt.
- Quản trị sản phẩm, biến thể, dự án, danh mục, kỹ năng, đơn hàng, liên hệ, người dùng và thiết lập website.
- SEO metadata, Open Graph, Twitter Card và sitemap XML.

## 1. Yêu cầu hệ thống

Máy cài đặt cần có:

- Git.
- PHP `8.2` trở lên.
- Composer 2.
- Node.js `20.19+` hoặc `22.12+`.
- npm 10+.
- MySQL 8+/MariaDB hoặc SQLite.
- Các PHP extension: `dom`, `exif`, `fileinfo`, `filter`, `gd`, `iconv`, `json`, `mbstring`, `openssl`, `pdo`, `session` và `tokenizer`.
- Extension database tương ứng: `pdo_mysql` hoặc `pdo_sqlite`.

Kiểm tra nhanh môi trường:

```bash
php -v
composer --version
node -v
npm -v
```

## 2. Lấy mã nguồn

```bash
git clone git@github.com:onezoroone/shop-nqt.git
cd shop-nqt
```

Nếu tài khoản GitHub chưa cấu hình SSH, có thể clone bằng HTTPS:

```bash
git clone https://github.com/onezoroone/shop-nqt.git
cd shop-nqt
```

## 3. Cài dependency

```bash
composer install
npm ci
```

Project có `composer.lock` và `package-lock.json`; nên dùng `composer install` và `npm ci` để cài đúng phiên bản đã khóa.

## 4. Tạo file môi trường

macOS/Linux:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Tạo application key:

```bash
php artisan key:generate
```

Cập nhật tối thiểu các biến sau trong `.env`:

```dotenv
APP_NAME="NQT Dev"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=vi
APP_FALLBACK_LOCALE=vi

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

Không commit `.env` hoặc khóa bí mật lên Git.

## 5. Cấu hình database

Chọn một trong hai phương án dưới đây.

### Phương án A: SQLite

SQLite phù hợp để chạy nhanh trên máy local.

macOS/Linux:

```bash
touch database/database.sqlite
```

Windows PowerShell:

```powershell
New-Item database/database.sqlite -ItemType File
```

Giữ cấu hình sau trong `.env`:

```dotenv
DB_CONNECTION=sqlite
```

Không cần khai báo `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` và `DB_PASSWORD` khi dùng file SQLite mặc định này.

### Phương án B: MySQL/MariaDB

Tạo database UTF-8:

```sql
CREATE DATABASE shop_nqt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Cập nhật `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_nqt
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Tạo bảng và dữ liệu ban đầu

### Cài kèm dữ liệu mẫu

Lựa chọn này tạo danh mục, kỹ năng, thiết lập website, dự án và sản phẩm mẫu:

```bash
php artisan migrate --seed
```

Dữ liệu seed chỉ dùng để khởi tạo/demo. Sau khi cài, cần thay các giá trị Telegram, ví USDT, nội dung giới thiệu và SEO trong trang quản trị.

### Cài dữ liệu trống

Nếu không muốn tạo sản phẩm và dự án mẫu:

```bash
php artisan migrate
php artisan db:seed --class=SettingSeeder
```

Lệnh thứ hai chỉ tạo các thiết lập nền tảng cần chỉnh trong admin.

Không chạy `php artisan migrate:fresh --seed` trên hệ thống đã có dữ liệu vì lệnh này xóa toàn bộ bảng trước khi tạo lại.

## 7. Public storage và frontend assets

Tạo symbolic link để ảnh upload có thể truy cập từ trình duyệt:

```bash
php artisan storage:link
```

Build CSS và JavaScript:

```bash
npm run build
```

Sau bước này, Vite sẽ tạo manifest và assets trong `public/build`.

## 8. Tạo tài khoản quản trị

Project không có tài khoản admin mặc định và không tự động cấp admin cho user ID 1.

Trước tiên, tạo một user bằng lệnh tương tác:

```bash
php artisan backpack:user
```

Hoặc chạy website và đăng ký user tại `/register`.

Sau đó cấp quyền admin cho đúng email vừa tạo:

```bash
php artisan app:make-admin admin@example.com
```

Thay `admin@example.com` bằng email thực tế. Nên tạo user trước rồi mới chạy lệnh cấp quyền để bảo đảm tài khoản được lưu đầy đủ và có `is_admin = 1`.

Đăng nhập quản trị tại:

```text
http://127.0.0.1:8000/admin/login
```

Trang quản trị chỉ cho phép tài khoản có `is_admin = 1` truy cập.

## 9. Chạy project local

### Cách đầy đủ

```bash
composer run dev
```

Lệnh này chạy đồng thời:

- Laravel development server.
- Vite development server.
- Database queue listener.
- Laravel Pail để xem log.

Mở website tại [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Cách chạy từng tiến trình

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Terminal 3, khi cần xử lý queue:

```bash
php artisan queue:work --tries=3
```

Muốn đổi cổng Laravel:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

Khi đó cần đổi `APP_URL=http://127.0.0.1:8001` trong `.env`, sau đó chạy:

```bash
php artisan config:clear
```

## 10. Thiết lập bắt buộc sau khi cài

Vào `/admin` và kiểm tra các nhóm sau:

1. `Settings`: tên website, nội dung hero, giới thiệu và footer.
2. `telegram_url`: URL Telegram nhận xác nhận thanh toán/liên hệ.
3. `usdt_wallet_address`: địa chỉ ví USDT thực tế.
4. Các khóa `seo_*`: title, description, keyword và ảnh chia sẻ mặc định.
5. `Products`: giá, biến thể, link demo và link tải source code.
6. `Projects`: thumbnail, công nghệ, link demo/source và nội dung SEO.
7. `Orders`: duyệt đơn sau khi đã xác minh thanh toán.

Link tải source chỉ xuất hiện với khách khi đơn ở trạng thái `paid` hoặc `completed` và sản phẩm/biến thể có `source_url`.

## 11. Email

Môi trường local mặc định dùng `MAIL_MAILER=log`; email được ghi vào `storage/logs/laravel.log` thay vì gửi thật.

Để gửi email qua SMTP trong production, cấu hình ví dụ:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_SCHEME=smtp
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Sau khi thay `.env`:

```bash
php artisan config:clear
```

## 12. Kiểm tra code

Chạy test:

```bash
composer test
```

Hoặc:

```bash
php artisan test --compact
```

Format PHP:

```bash
vendor/bin/pint --format agent
```

Kiểm tra build frontend:

```bash
npm run build
```

## 13. Cài nhanh bằng Composer script

Project có script `composer run setup`. Cần chuẩn bị database trước khi chạy.

Với SQLite:

```bash
cp .env.example .env
touch database/database.sqlite
composer run setup
php artisan storage:link
php artisan db:seed
php artisan backpack:user
php artisan app:make-admin admin@example.com
```

Với MySQL, hãy tạo database và sửa `.env` trước `composer run setup`.

Script setup sẽ cài Composer dependency, tạo key, migrate database, cài npm dependency và build frontend. Script không tự tạo file SQLite, không seed dữ liệu và không tạo admin.

## 14. Deploy production

### Cấu hình máy chủ

Máy chủ cần PHP-FPM, Composer, Node.js và MySQL/MariaDB. Document root của Nginx/Apache phải trỏ vào thư mục `public`, không trỏ vào thư mục gốc project.

Ví dụ Nginx tối thiểu:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/shop-nqt/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Điều chỉnh `server_name`, đường dẫn project và PHP-FPM socket theo máy chủ thực tế. Bật HTTPS trước khi đưa website vào sử dụng.

### Cài bản production

```bash
cd /var/www/shop-nqt
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Tạo `.env` production và cấu hình tối thiểu:

```dotenv
APP_NAME="NQT Dev"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_nqt
DB_USERNAME=shop_nqt
DB_PASSWORD=strong-database-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

BACKPACK_REGISTRATION_OPEN=false
```

Chỉ tạo key ở lần deploy đầu tiên:

```bash
php artisan key:generate
```

Không chạy lại `key:generate` trên website đang hoạt động vì session và dữ liệu đã mã hóa có thể không còn đọc được.

Hoàn tất cài đặt:

```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan backpack:user
php artisan app:make-admin admin@example.com
```

Cấp quyền ghi cho web server:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
```

### Queue worker bằng Supervisor

Vì project dùng `QUEUE_CONNECTION=database`, production nên chạy worker nền. Ví dụ `/etc/supervisor/conf.d/shop-nqt-worker.conf`:

```ini
[program:shop-nqt-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/shop-nqt/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/shop-nqt/storage/logs/worker.log
stopwaitsecs=3600
```

Áp dụng cấu hình:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start shop-nqt-worker:*
```

Project hiện không có scheduled task riêng nên chưa bắt buộc cấu hình cron cho `schedule:run`.

### Quy trình cập nhật production

```bash
cd /var/www/shop-nqt
git pull
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
php artisan queue:restart
```

Nên backup database, `.env` và `storage/app/public` trước mỗi lần cập nhật lớn.

## 15. Lỗi thường gặp

### `No application encryption key has been specified`

```bash
php artisan key:generate
php artisan config:clear
```

### `Database file ... does not exist`

```bash
touch database/database.sqlite
php artisan migrate
```

Trên Windows, dùng `New-Item database/database.sqlite -ItemType File`.

### `Unable to locate file in Vite manifest`

```bash
npm ci
npm run build
```

### Ảnh upload bị lỗi 404

```bash
php artisan storage:link
php artisan config:clear
```

Kiểm tra `APP_URL`, quyền ghi của `storage/app/public` và document root của web server.

### Không upload hoặc tạo thumbnail được

Đảm bảo PHP đã bật `fileinfo`, `gd` và `mbstring`, sau đó khởi động lại PHP-FPM/web server.

### Báo thiếu bảng `sessions`, `cache` hoặc `jobs`

```bash
php artisan migrate
```

Các bảng này đã có sẵn trong migration của project.

### Thay `.env` nhưng website vẫn dùng cấu hình cũ

```bash
php artisan optimize:clear
```

Trong production, cache lại sau khi kiểm tra:

```bash
php artisan optimize
```

### Port 8000 đang được sử dụng

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

## Cấu trúc thư mục quan trọng

```text
app/Http/Controllers/         Controller frontend
app/Http/Controllers/Admin/   Backpack CRUD controller
app/Models/                   Eloquent model
database/migrations/          Cấu trúc database
database/seeders/             Dữ liệu khởi tạo/mẫu
resources/css/app.css         Toàn bộ styling frontend
resources/js/app.js           Interaction và animation frontend
resources/views/              Blade views và components
routes/web.php                Route frontend
routes/backpack/custom.php    Route quản trị
storage/app/public/           File upload công khai
tests/                        PHPUnit feature tests
```

## Bản quyền

Dự án được phát triển bởi NQTDev. Không thương mại hóa hoặc phân phối lại mã nguồn khi chưa có sự đồng ý của chủ sở hữu.
