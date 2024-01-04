<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_116 extends App_module_migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $CI = &get_instance();
        $CI->load->dbforge();
        $fields = array(
            'show_in' => array(
                'type' => 'tinyint',
                'constraint' => "1",
                'default' => "0",
                'after' => 'http_protocol'
            ),
            'roles' => array(
                'type' => 'text',
                'null' => true,
                'after' => 'users'
            ),
            'clients' => array(
                'type' => 'text',
                'null' => true,
                'after' => 'roles'
            ),
        );
        $CI->dbforge->add_column('custom_links', $fields);

        $CI->db->where("show_in_iframe", "1")->update("custom_links", ["show_in" => "2"]);
        $CI->db->where("open_in_blank", "1")->update("custom_links", ["show_in" => "1"]);

        $CI->dbforge->drop_column('custom_links', 'open_in_blank');
        $CI->dbforge->drop_column('custom_links', 'show_in_iframe');
    }
}