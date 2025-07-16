<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJadwalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idjadwal' => [
                'type'       => 'CHAR',
                'constraint' => 30,
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'waktu_mulai' => [
                'type' => 'TIME',
            ],
            'waktu_selesai' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'iddokter' => [
                'type'       => 'CHAR',
                'constraint' => 30,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
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
        
        // Composite primary key
        $this->forge->addKey(['idjadwal', 'hari', 'waktu_mulai', 'iddokter'], true);
        $this->forge->createTable('jadwal');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal');
    }
} 