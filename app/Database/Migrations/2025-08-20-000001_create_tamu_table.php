<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use PHPSQLParser\builders\PrimaryKeyBuilder;

class CreatepasienTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nik' => [
                'type'           => 'VARCHAR',
                'constraint'     => 16,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat' => [
                'type'       => 'TEXT',
            ],
            'nohp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'jenkel' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
            ],
            'iduser' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('nik', true);
        $this->forge->createTable('pasien');
        $data = [
            'nik'   => '1234567890',
            'nama'   => 'rindi',
            'alamat'      => 'Padang',
            'tgl_lahir' => '2000-01-01',
            'nohp'   => '09232323',
            'jenkel'       => 'P',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('pasien')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('pasien');
    }
}
