<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokterTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_dokter' => [
                'type'       => 'CHAR',
                'constraint' => 30,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tgllahir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'nohp' => [
                'type'       => 'CHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'jenkel' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => true,
            ],
            'iduser' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id_dokter', true);
        $this->forge->createTable('dokter');
    }

    public function down()
    {
        $this->forge->dropTable('dokter');
    }
} 