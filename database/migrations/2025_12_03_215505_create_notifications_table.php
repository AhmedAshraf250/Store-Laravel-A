<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            /**
             * $table->morphs('notifiable');
             * → Automatically creates two columns:
             *     • notifiable_type  → varchar  (stores the model class, e.g., App\Models\User)
             *     • notifiable_id    → bigint   (stores the primary key of that model)
             *
             * This enables Polymorphic Many-to-One relationship (MorphTo).
             *
             * Why polymorphic?
             * - Laravel notifications can be sent to ANY model that uses the
             *   Notifiable trait (User, Admin, Store, Team, etc.).
             * - Instead of creating separate tables for each model, we use one
             *   unified `notifications` table that can belong to multiple models.
             *
             * Example rows in the notifications table:
             * ┌────────────────────────────────────┬────────────--───┬───────────────┐
             * │ id                                 │ notifiable_type │ notifiable_id │
             * ├────────────────────────────────────┼─────────────────┼───────────────┤
             * │ 123e4567-e89b-...                  │ App\Models\User │ 5             │
             * │ 987fcdeb-...                       │ App\Models\Admin│ 2             │
             * │ 456ab789-...                       │ App\Models\Store│ 10            │
             * └────────────────────────────────────┴─────────────────┴───────────────┘
             *
             * Laravel uses these two columns to resolve the correct model instance
             * when retrieving unread notifications via $user->unreadNotifications.
             */
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
