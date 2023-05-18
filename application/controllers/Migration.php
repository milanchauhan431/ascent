<?php
class Migration extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    /* public function addColumnInTable(){
        $result = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ascent' AND TABLE_NAME NOT IN ( SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'updated_at' AND TABLE_SCHEMA = 'ascent' )")->result();


        foreach($result as $row):
            if(!in_array($row->TABLE_NAME,["instrument"])):
                $this->db->query("ALTER TABLE ".$row->TABLE_NAME." ADD `updated_at` INT NOT NULL DEFAULT '0' AFTER `updated_by`;");
            endif;
        endforeach;

        echo "success";exit;
    } */
}
?>