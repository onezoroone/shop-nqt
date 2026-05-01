# NQTDev Shop & Portfolio - Laravel 12

Chào mừng bạn đến với mã nguồn **NQTDev Shop & Portfolio**. Đây là một hệ thống web hiện đại, được xây dựng trên nền tảng Laravel 12 kết hợp với Backpack for Laravel, chuyên dùng để bán các sản phẩm số (Source Code, Template, Plugin) và trưng bày các dự án cá nhân (Portfolio).

Giao diện người dùng (Frontend) được thiết kế theo phong cách Glassmorphism sang trọng, mượt mà với Tailwind CSS. Trang quản trị (Backend) sử dụng Backpack for Laravel để quản lý nội dung dễ dàng, linh hoạt.

## 🌟 Tính năng nổi bật

### Dành cho Khách hàng (Frontend)
- **Giao diện hiện đại:** Thiết kế Dark Mode tinh tế, hiệu ứng Glassmorphism (thẻ kính trong suốt), thân thiện với mọi thiết bị (Responsive).
- **Cửa hàng Sản phẩm số:** Hiển thị danh sách sản phẩm, lọc theo danh mục, sắp xếp (Mới nhất, Phổ biến, Giá cả), tìm kiếm.
- **Trưng bày Dự án (Portfolio):** Giới thiệu các dự án đã thực hiện kèm hình ảnh, công nghệ sử dụng và link demo.
- **Quản lý Tài khoản (Dashboard):** Đăng nhập/Đăng ký nhanh chóng. Theo dõi lịch sử đơn hàng, trạng thái thanh toán.
- **Thanh toán linh hoạt:** Hỗ trợ thanh toán thủ công qua USDT (TRC20) hoặc chuyển khoản. Hướng dẫn xác nhận qua Telegram.
- **Tự động cấp quyền tải:** Sau khi Admin duyệt đơn, nút "Tải Source Code" sẽ tự động xuất hiện trong chi tiết đơn hàng của khách.

### Dành cho Quản trị viên (Backend - Backpack)
- **Quản lý Sản phẩm:** Thêm, sửa, xoá sản phẩm. Hỗ trợ WYSIWYG editor (CKEditor), tải lên nhiều ảnh (Gallery), quản lý giá bán, và tự động ẩn/hiện link Source Code.
- **Quản lý Đơn hàng:** Duyệt đơn hàng nhanh bằng 1 click chuột (Quick Action), tự động cập nhật trạng thái đơn thành "Đã thanh toán".
- **Laravel File Manager (LFM):** Tích hợp trình quản lý file siêu mạnh mẽ cho toàn bộ hệ thống Admin.
- **Quản lý Danh mục & Phân quyền:** Phân chia danh mục sản phẩm, bảo mật tuyệt đối với Middleware kiểm tra quyền Admin (`is_admin`).

---

## 🚀 Hướng dẫn cài đặt (Installation)

Yêu cầu hệ thống:
- **PHP** >= 8.2
- **Composer** v2+
- **Node.js** & **NPM** (để biên dịch Tailwind CSS)
- **MySQL** hoặc **PostgreSQL**

### Bước 1: Lấy mã nguồn & Cài đặt thư viện PHP
Mở Terminal/Command Prompt và chạy các lệnh sau:
```bash
git clone <link-repo-cua-ban>
cd shop-nqt
composer install
```

### Bước 2: Cấu hình môi trường (.env)
Copy file `.env.example` thành `.env`:
```bash
cp .env.example .env
```
Mở file `.env` và điền thông tin kết nối Database của bạn (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
Sau đó tạo key bảo mật:
```bash
php artisan key:generate
```

### Bước 3: Liên kết thư mục Storage & Chạy Migration
Hệ thống cần thư mục public/storage để lưu trữ ảnh sản phẩm:
```bash
php artisan storage:link
```
Chạy lệnh tạo các bảng trong Database:
```bash
php artisan migrate
```
*(Lưu ý: Migration `2026_05_01_052811_add_is_admin_to_users_table.php` đã được cấu hình để tự động cấp quyền Admin cho tài khoản có ID = 1).*

### Bước 4: Cài đặt thư viện Frontend & Biên dịch CSS
Cài đặt thư viện Node.js:
```bash
npm install
```
Biên dịch Tailwind CSS và các file JS:
```bash
npm run build
# Hoặc dùng lệnh sau nếu bạn đang code và muốn tự cập nhật khi sửa file:
# npm run dev
```

### Bước 5: Tạo tài khoản Admin
Bạn có thể tự tạo tài khoản Admin theo 2 cách:
1. Đăng ký 1 tài khoản bình thường ở trang ngoài (`/register`), đây sẽ là tài khoản đầu tiên (ID = 1). Hệ thống sẽ tự động biến tài khoản ID 1 thành Admin.
2. Hoặc chạy lệnh Artisan dựng sẵn để cấp quyền bằng Email (hoặc tạo mới Admin):
```bash
php artisan app:make-admin your@email.com
```

---

## 🖥️ Cách sử dụng

- **Trang chủ người dùng:** Truy cập `http://localhost:8000`
- **Trang quản trị (Admin):** Truy cập `http://localhost:8000/admin`
  - Đăng nhập bằng tài khoản đã được cấp quyền `is_admin = 1`.

### Một số thiết lập tùy chỉnh (Settings)
Bạn có thể thêm các cấu hình tuỳ chỉnh trong bảng `settings` (thông qua giao diện Admin -> Cài đặt), ví dụ:
- `usdt_wallet_address`: Địa chỉ ví USDT (TRC20) hiển thị cho khách chuyển khoản.
- `telegram_url`: Link Telegram để khách hàng liên hệ xác nhận (Ví dụ: `https://t.me/nqtdev`).

---

## 🛠️ Công nghệ sử dụng (Tech Stack)
- **Framework:** Laravel v12.x
- **Admin Panel:** Backpack for Laravel v6.x
- **CSS Framework:** Tailwind CSS v3.x
- **File Manager:** UniSharp Laravel File Manager (LFM)
- **Bundler:** Vite

## 📝 Bản quyền & Giấy phép
Dự án được xây dựng và phát triển bởi **NQTDev**. 
Vui lòng không thương mại hoá mã nguồn nếu chưa có sự đồng ý.
