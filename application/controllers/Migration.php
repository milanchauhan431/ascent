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

    public function migrateGIentry(){
        try{
            $this->db->trans_begin();

            $this->db->select("mir.id,mir.party_id,mir_transaction.qty,mir_transaction.item_id,mir_transaction.po_id,mir_transaction.po_trans_id,mir_transaction.id as trans_id");
            $this->db->join('mir','mir.id = mir_transaction.mir_id','left');
            $this->db->where('mir_transaction.is_delete',0);
            $this->db->where('mir.trans_type',2);
            //$this->db->where('mir_transaction.po_trans_id',0);
            $result = $this->db->get('mir_transaction')->result();

            $this->db->where('entry_type',21)->update('trans_child',['trans_status'=>0,'dispatch_qty'=>0]);
            $this->db->where('entry_type',21)->update('trans_main',['trans_status'=>0]);

            foreach($result as $row):
                if(empty($row->po_trans_id)):
                    $this->db->select('trans_child.trans_main_id,trans_child.id,trans_main.party_id,trans_child.item_id,(trans_child.qty - trans_child.dispatch_qty) as qty');
                    $this->db->join('trans_main','trans_main.id = trans_child.trans_main_id','left');
                    $this->db->where('trans_child.is_delete',0);
                    $this->db->where('trans_main.entry_type',21);
                    $this->db->where('(trans_child.qty - trans_child.dispatch_qty) >',0);
                    $this->db->where('trans_child.item_id',$row->item_id);
                    $this->db->where('trans_main.party_id',$row->party_id);
                    $poData = $this->db->get('trans_child')->result();

                    foreach($poData as $item):
                        $qty = 0;$pendingQty = 0;
                        $pendingQty = $row->qty - $item->qty;
                        if($pendingQty > 0):
                            $qty = $item->qty;
                            $row->qty = $row->qty - $item->qty;
                        elseif($pendingQty < 0):
                            $qty = $row->qty;
                        else:
                            $qty = $item->qty;
                        endif;

                        $this->db->where('id',$item->id);
                        $this->db->set('dispatch_qty','`dispatch_qty` + '.$qty,false);
                        $this->db->set('trans_status','(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)',false);
                        $this->db->update('trans_child');

                        $this->db->where('id',$item->trans_main_id);
                        $this->db->set('trans_status','(SELECT IF( COUNT(id) = SUM(IF(trans_status = 1, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = '.$item->trans_main_id.' AND is_delete = 0)',false);
                        $this->db->update('trans_main');

                        if($pendingQty <= 0): 
                            $this->db->where('id',$row->trans_id)->update('mir_transaction',['po_id' => $item->trans_main_id,'po_trans_id'=>$item->id]);
                            break; 
                        endif;
                    endforeach;                    
                else:
                    $this->db->where('id',$row->po_trans_id);
                    $this->db->set('dispatch_qty','`dispatch_qty` + '.$row->qty,false);
                    $this->db->set('trans_status','(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)',false);
                    $this->db->update('trans_child');

                    $this->db->where('id',$row->po_id);
                    $this->db->set('trans_status','(SELECT IF( COUNT(id) = SUM(IF(trans_status = 1, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = '.$row->po_id.' AND is_delete = 0)',false);
                    $this->db->update('trans_main');
                endif;

                //print_r($row);print_r("-----");print_r($poData);print_r("<hr>");
            endforeach;
            /* $this->db->trans_rollback();
            exit; */
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "GI entry Migrate successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo "somthing is wrong. Error : ".$e->getMessage(); exit;
        }	
    }
}
?>