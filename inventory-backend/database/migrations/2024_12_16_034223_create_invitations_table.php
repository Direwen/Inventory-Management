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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("inviter_id")->constrained(table:"users", column: "id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("invitee_email");
            $table->foreignId("inventory_id")->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum("status", ["pending", "accepted", "declined", "expired", "full", "cancelled"])->default("pending");
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
