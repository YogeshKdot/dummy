<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        add_option('saas_default_landing_page', 1);

        $revertFiles = [
            APPPATH . 'third_party/MX/Loader.php' => APPPATH . 'third_party/MX/Loader.php.backup',
            APPPATH . 'third_party/MX/Modules.php' => APPPATH . 'third_party/MX/Modules.php.backup',
        ];

        foreach ($revertFiles as $actualPath => $backupFilePath) {
            if (file_exists($actualPath) && file_exists($backupFilePath)) {
                @unlink($actualPath);
            }
            if (!file_exists($actualPath)) {
                rename($backupFilePath, $actualPath);
            }
        }
    }
}
