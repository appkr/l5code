<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->nullable()->unsigned()->index();
            $table->string('filename');
            $table->integer('bytes')->nullable()->unsigned();
            $table->string('mime')->nullable();
            $table->timestamps();

//            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('attachments', function (Blueprint $table) {
//            $table->dropForeign('attachments_article_id_foreign');
//        });

        Schema::dropIfExists('attachments');
    }
}
