<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateObatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idobat' => [
                'type'       => 'CHAR',
                'constraint' => 30,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'jenis' => [
                'type'       => 'ENUM',
                'constraint' => ['minum', 'bahan'],
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
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
        
        $this->forge->addKey('idobat', true);
        $this->forge->createTable('obat');
    }

    public function down()
    {
        $this->forge->dropTable('obat');
    }
} 