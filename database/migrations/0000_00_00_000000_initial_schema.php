<?php

use App\Models\Car;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create("cars", function (Blueprint $table) {
			$table->id();
			$table->string("name")->unique();
			$table->foreignId("owner_id")->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table
				->foreign("owner_id")
				->references("id")
				->on("users");
		});

		Schema::create("car_efficiencies", function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Car::class);
			$table->unsignedFloat("efficiency");
			$table->foreignId("author_id")->nullable();
			$table->timestamps();

			$table
				->foreign("author_id")
				->references("id")
				->on("users");
		});

		Schema::create("car_fuel_types", function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Car::class);
			$table->enum("fuel_type", ["gasoline", "diesel"]);
			$table->foreignId("author_id")->nullable();
			$table->timestamps();

			$table
				->foreign("author_id")
				->references("id")
				->on("users");
		});

		Schema::create("changes", function (Blueprint $table) {
			$table->id();
			$table->foreignId("author_id")->nullable();
			$table->string("description");
			$table->foreignId("car_id")->nullable();
			$table->foreignId("transaction_id")->nullable();
			$table->foreignId("user_id")->nullable();
			$table->string("reason")->nullable();
			$table->timestamps();

			$table
				->foreign("author_id")
				->references("id")
				->on("users");
			$table
				->foreign("car_id")
				->references("id")
				->on("cars");
			$table
				->foreign("transaction_id")
				->references("id")
				->on("transactions");
			$table
				->foreign("user_id")
				->references("id")
				->on("users");
		});

		Schema::create("files", function (Blueprint $table) {
			$table->id();
			$table->foreignId("change_id");
			$table->string("name")->comment("name as it was uploaded (censored)");
			$table->string("path")->comment("path in server storage");
			$table->string("type")->comment("mimetype as determined by server");
			$table->bigInteger("size", unsigned: true)->comment("in bytes");
			$table->timestamps();

			$table
				->foreign("change_id")
				->references("id")
				->on("changes");
		});

		Schema::create("fuel_prices", function (Blueprint $table) {
			$table->id();
			$table->enum("fuel_type", ["gasoline", "diesel"]);
			$table->unsignedFloat("price");
			$table->timestamps();

			$table->unique(["fuel_type", "created_at"]);
		});

		Schema::create("transactions", function (Blueprint $table) {
			$table->id();
			$table->enum("kind", ["owing", "payment", "drivetrak"]);
			$table->foreignId("from_user_id");
			$table->foreignId("to_user_id");
			$table->unsignedFloat("amount");
			$table->boolean("is_confirmed");
			$table->string("memo");
			$table
				->foreignId("car_id")
				->nullable()
				->comment("if this is a DriveTrak-style entry");
			$table->unsignedFloat("distance")->nullable();
			$table->unsignedFloat("ratio")->nullable();
			$table->dateTime("occurred_at")->index();
			$table->timestamps();
			$table->softDeletes();

			$table
				->foreign("from_user_id")
				->references("id")
				->on("users");
			$table
				->foreign("to_user_id")
				->references("id")
				->on("users");
			$table
				->foreign("car_id")
				->references("id")
				->on("cars");
		});

		Schema::create("users", function (Blueprint $table) {
			$table->id();
			$table->string("username")->unique();
			$table->string("name")->unique();
			$table->string("password")->nullable();
			$table->string("locale")->default("en_CA");
			$table
				->string("remember_token")
				->nullable()
				->comment('for "remember me"');
			$table->boolean("is_admin")->default(false);
			$table->boolean("must_change_password")->default(false);
			$table->timestamps();
			$table->softDeletes();
		});
	}
};
