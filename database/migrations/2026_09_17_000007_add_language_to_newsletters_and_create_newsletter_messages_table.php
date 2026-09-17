<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('newsletters', 'language')) {
            Schema::table('newsletters', function (Blueprint $table) {
                $table->string('language', 2)->default('fr')->after('email');
            });
        }

        if (!Schema::hasTable('newsletter_messages')) {
            Schema::create('newsletter_messages', function (Blueprint $table) {
                $table->id();
                $table->string('subject_fr');
                $table->string('subject_en');
                $table->longText('content_fr');
                $table->longText('content_en');
                $table->string('status')->default('draft');
                $table->unsignedInteger('recipients_count')->default(0);
                $table->timestamp('sent_at')->nullable();
                $table->text('error')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_messages');

        if (Schema::hasColumn('newsletters', 'language')) {
            Schema::table('newsletters', function (Blueprint $table) {
                $table->dropColumn('language');
            });
        }
    }
};