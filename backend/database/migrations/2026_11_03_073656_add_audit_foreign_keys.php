<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $auditTables = ['departments', 'designations', 'employees'];

    public function up(): void
    {
        foreach ($this->auditTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('employees');
                $table->foreign('updated_by')->references('id')->on('employees');
            });
        }

        Schema::table('employee_histories', function (Blueprint $table) {
            $table->foreign('changed_by')->references('id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::table('employee_histories', function (Blueprint $table) {
            $table->dropForeign(['changed_by']);
        });

        foreach ($this->auditTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
            });
        }
    }
};