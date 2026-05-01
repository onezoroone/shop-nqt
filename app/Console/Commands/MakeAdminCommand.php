<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {email? : Địa chỉ email của người dùng}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cấp quyền Admin cho một người dùng bằng Email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Nhập địa chỉ email của người dùng cần cấp quyền Admin');
        }

        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            $this->error("Không tìm thấy người dùng với email: {$email}");
            
            if ($this->confirm('Bạn có muốn tạo tài khoản Admin mới với email này không?')) {
                $name = $this->ask('Nhập họ tên');
                $password = $this->secret('Nhập mật khẩu');
                
                $user = \App\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'is_admin' => true,
                ]);
                
                $this->info("Đã tạo tài khoản Admin mới thành công!");
                return self::SUCCESS;
            }
            
            return self::FAILURE;
        }

        if ($user->is_admin) {
            $this->warn("Người dùng {$email} đã là Admin từ trước.");
            return self::SUCCESS;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("Đã cấp quyền Admin cho người dùng {$email} thành công!");
        return self::SUCCESS;
    }
}
