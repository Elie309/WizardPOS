<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WholeDb extends Migration
{
    public function up()
    {
        $filePath = APPPATH . "Database/wholedb.sql";

        $sql = file_get_contents($filePath);

        // Split the SQL file into individual queries
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $this->db->query($query);
            }
        }
    }

    public function down()
    {
        $this->db->query('DROP DATABASE IF EXISTS `wizardpos`');
    }
}
