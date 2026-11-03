<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('mobile', 20);
            $table->string('gender', 10);
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('designation_id')->constrained('designations');
            $table->string('employment_type', 20);
            $table->decimal('salary', 12, 2)->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('status', 20)->default('active');

            // Login
            $table->string('password')->nullable();
            $table->boolean('login_enabled')->default(false);
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes (PostgreSQL does not index foreign keys automatically)
            $table->index('department_id');
            $table->index('designation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
