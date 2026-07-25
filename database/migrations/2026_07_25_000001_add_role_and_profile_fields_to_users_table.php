<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default(UserRole::Student->value)->after('email')->index();
            $table->string('gender', 10)->nullable()->after('role');
            $table->string('phone', 25)->nullable()->after('gender');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('status', 15)->default(UserStatus::Active->value)->after('avatar')->index();
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'gender', 'phone', 'avatar', 'status', 'last_login_at']);
        });
    }
};
