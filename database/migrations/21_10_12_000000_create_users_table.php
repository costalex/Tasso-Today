<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('user_type_id');
	        $table->string('email')->nullable(false)->unique();
	        $table->string('password')->nullable(false);
	        $table->string('password_caisse')->nullable(true);
	        $table->enum('status', [
	        	'VALIDATION_EN_ATTENTE', 'ACTIVATION_EN_ATTENTE', 'ACTIVE',
		        'BAN'
	        ])->default('VALIDATION_EN_ATTENTE');
	        $table->longText('confirm_code')->nullable(true);
	        $table->longText('refresh_token')->nullable(true);
	        $table->longText('api_token')->nullable(true);
	        $table->longText('remember_token')->nullable(true);
	        //stripe
	        $table->string('stripe_id')->nullable(true);
	        $table->string('card_brand')->nullable(true);
	        $table->string('card_last_four')->nullable(true);
	        $table->timestamp('trial_ends_at')->nullable(true);
	        $table->timestamps();
	        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
