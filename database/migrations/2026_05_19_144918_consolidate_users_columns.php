<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'title') || Schema::hasColumn('users', 'first_name') || Schema::hasColumn('users', 'surname')) {
                DB::statement("UPDATE users SET name = TRIM(CONCAT_WS(' ', COALESCE(title, ''), COALESCE(first_name, ''), COALESCE(surname, ''))) WHERE name IS NULL OR name = ''");
            }

            if (Schema::hasColumn('users', 'id_number')) {
                DB::statement("UPDATE users SET id_no = id_number WHERE (id_no IS NULL OR id_no = '') AND id_number IS NOT NULL");
            }

            if (Schema::hasColumn('users', 'mobile_number')) {
                DB::statement("UPDATE users SET phone = mobile_number WHERE (phone IS NULL OR phone = '') AND mobile_number IS NOT NULL");
            }

            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'title')) {
                    $table->dropColumn('title');
                }
                if (Schema::hasColumn('users', 'first_name')) {
                    $table->dropColumn('first_name');
                }
                if (Schema::hasColumn('users', 'surname')) {
                    $table->dropColumn('surname');
                }
                if (Schema::hasColumn('users', 'id_number')) {
                    $table->dropColumn('id_number');
                }
                if (Schema::hasColumn('users', 'mobile_number')) {
                    $table->dropColumn('mobile_number');
                }
                if (Schema::hasColumn('users', 'role_id')) {
                    $table->dropColumn('role_id');
                }
                if (Schema::hasColumn('users', 'store_id')) {
                    $table->dropColumn('store_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'title')) {
                $table->string('title')->nullable();
            }
            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable();
            }
            if (! Schema::hasColumn('users', 'id_number')) {
                $table->string('id_number')->nullable();
            }
            if (! Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number')->nullable();
            }
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable();
            }
            if (! Schema::hasColumn('users', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable();
            }
        });
    }
};
