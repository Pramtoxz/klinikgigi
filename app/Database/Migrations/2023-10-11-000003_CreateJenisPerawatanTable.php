<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisPerawatanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idjenis' => [
                'type'       => 'CHAR',
                'constraint' => 30,
            ],
            'namajenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'estimasi' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'harga' => [
                'type' => 'DOUBLE',
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
        
        $this->forge->addKey('idjenis', true);
        $this->forge->createTable('jenis_perawatan');
    }

    public function down()
    {
        $this->forge->dropTable('jenis_perawatan');
    }
} 