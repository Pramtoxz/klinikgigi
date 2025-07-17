<?php

namespace App\Validation;

use Config\Database;

class CustomRules
{
    /**
     * Check if value exists in database table field
     *
     * @param string $str Value to check
     * @param string $field Field to check
     * @param array $data Form data
     * @param string $error Error message
     *
     * @return boolean
     */
    public function exists(string $str, string $field, array $data = [], string &$error = null): bool
    {
        // Format: table.field
        $parts = explode('.', $field);
        
        if (count($parts) !== 2) {
            return false;
        }
        
        $table = $parts[0];
        $field = $parts[1];
        
        $db = Database::connect();
        $row = $db->table($table)
                  ->where($field, $str)
                  ->where('deleted_at IS NULL', null, false)
                  ->limit(1)
                  ->get()
                  ->getRow();
        
        return $row !== null;
    }

    public function check_time_greater(string $str, string $field, array $data): bool
    {
        $end_time = strtotime($str);
        $start_time = strtotime($data[$field]);
        
        // Jika waktu mulai atau waktu selesai kosong, kembalikan true (validasi required akan menanganinya)
        if (empty($str) || empty($data[$field])) {
            return true;
        }
        
        // Membandingkan waktu
        return $end_time >= $start_time;
    }
} 