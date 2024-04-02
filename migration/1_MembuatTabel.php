<?php
use SLiMS\Table\Schema;
use SLiMS\Table\Blueprint;
use SLiMS\Migration\Migration;

class MembuatTabel extends Migration
{
    function up() {
        Schema::create('usul_buku', function(Blueprint $table) {
            $table->autoIncrement('id');
            $table->string('identitas', 64)->notNull();
            $table->string('nama', 64)->notNull();
            $table->string('alamat', 64)->notNull();
            $table->string('judul', 64)->notNull();
            $table->string('pengarang', 64)->notNull();
            $table->string('penerbit', 64)->notNull();
            $table->string('tahunterbit', 64)->notNull();
            $table->timestamps();
        });
    }

    function down() {
        Schema::drop('usul_buku');
    }
}