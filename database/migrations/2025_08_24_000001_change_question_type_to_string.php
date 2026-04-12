<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: recreate the table since it doesn't support ALTER COLUMN
            // The enum constraint is only enforced at the application level in SQLite,
            // so the column is already effectively a string. No schema change needed.
            // But to be safe, let's ensure we can insert new type values by
            // using the Schema builder approach.
            Schema::table('questions', function (Blueprint $table) {
                $table->string('type_new')->default('multiple_choice')->after('part');
            });

            DB::table('questions')->update(['type_new' => DB::raw('type')]);

            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('type');
            });

            Schema::table('questions', function (Blueprint $table) {
                $table->renameColumn('type_new', 'type');
            });
        } else {
            // MySQL/Postgres: change enum to string
            DB::statement("ALTER TABLE questions MODIFY COLUMN type VARCHAR(255) NOT NULL");
        }
    }

    public function down(): void
    {
        // No rollback needed - string is more permissive than enum
    }
};
