<?php
class MaterialIssueModel extends MasterModel{
    private $materialIssue = "material_issue";
    private $stockTrans = "stock_transaction";

    public function getNextNo(){
        $queryData['tableName'] = $this->materialIssue;
        $queryData['select'] = "IFNULL((MAX(trans_no) + 1),1) as next_no";
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;
        $result = $this->row($queryData);
        return $result->next_no;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->materialIssue;
        $data['select'] = "material_issue.id, material_issue.trans_date, CONCAT(material_issue.trans_prefix, material_issue.trans_no) as trans_number, material_issue.collected_by, material_issue.item_id, item_master.item_code, item_master.item_name, material_issue.req_qty, material_issue.issue_qty, material_issue.return_qty, material_issue.remark";

        $data['leftJoin']['item_master'] = "item_master.id = material_issue.item_id";

        $data['where']['material_issue.trans_status'] = $data['status'];
        if($data['status'] == 0):
            $data['where']['material_issue.trans_date <='] = $this->endYearDate;
        else:
            $data['where']['material_issue.trans_date >='] = $this->startYearDate;
            $data['where']['material_issue.trans_date <='] = $this->endYearDate;
        endif;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(material_issue.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "CONCAT(material_issue.trans_prefix, material_issue.trans_no)";
        $data['searchCol'][] = "material_issue.collected_by";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "material_issue.req_qty";
        $data['searchCol'][] = "material_issue.issue_qty";
        $data['searchCol'][] = "material_issue.return_qty";
        $data['searchCol'][] = "material_issue.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }
}
?>