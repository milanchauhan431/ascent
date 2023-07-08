<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Items extends MY_Controller{
    private $indexPage = "item_master/index";
    private $form = "item_master/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Item Master";
		$this->data['headData']->controller = "items";        
	}

    public function list($item_type = 0){
        $this->data['headData']->pageUrl = "items/list/".$item_type;
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type]));
        $this->data['tableHeader'] = getMasterDtHeader($headerName);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($item_type = 0){
        $data = $this->input->post();$data['item_type'] = $item_type;
        $result = $this->item->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->item_type_text = $this->itemTypes[$row->item_type];
            $sendData[] = getProductData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addItem(){
        $data = $this->input->post();
        $this->data['item_type'] = $data['item_type'];
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$data['item_type'],'final_category'=>1]);
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->form,$this->data);
    }

    public function save($excel_data = array()){
        $data = (!empty($excel_data))?$excel_data:$this->input->post();
        $errorMessage = array();
        
        if(empty($data['item_code']))
            $errorMessage['item_code'] = "CAT No. is required.";
        if(empty($data['item_name']))
            $errorMessage['item_name'] = "Item Name is required.";
        if(empty($data['unit_id']))
            $errorMessage['unit_id'] = "Unit is required.";
        if(empty($data['category_id']))
            $errorMessage['category_id'] = "Category is required.";
            
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $fname = Array();
            if(!empty($data['item_code'])){$fname[] = $data['item_code'];}
            if(!empty($data['item_name'])){$fname[] = $data['item_name'];}
            if(!empty($data['part_no'])){$fname[] = $data['part_no'];}
            $data['full_name'] = (!empty($fname)) ? implode(' - ',$fname) : '';			
			
			if(!empty($data['hsn_code'])):
			    $hsnData = $this->hsnModel->getHSNDetail(['hsn'=>$data['hsn_code']]);
				$data['gst_per'] = $hsnData->gst_per;
			endif;

            if(!empty($excel_data)):
                return $this->item->save($data);
            else:
                $this->printJson($this->item->save($data));
            endif;
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $itemDetail = $this->item->getItem($data);
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$itemDetail->item_type,'final_category'=>1]);
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->delete($id));
        endif;
    }

    public function getItemList(){
        $data = $this->input->post();
        $itemList = $this->item->getItemList($data);
        $this->printJson(['status'=>1,'data'=>['itemList'=>$itemList]]);
    }

    public function getItemDetails(){
        $data = $this->input->post();
        $itemDetail = $this->item->getItem($data);
        $this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
    }

    public function createExcel($jsonData=''){
        $postData = (Array) decodeURL($jsonData);

        $paramData = $this->item->getItemList(['item_type'=>$postData['item_type']]);
        $table_column = array('id', 'item_code', 'item_name', 'unit_id', 'defualt_disc', 'price', 'category_id', 'make_brand', 'hsn_code', 'gst_per','std_qty', 'sec_unit_id', 'std_pck_qty','description', 'note');
        $spreadsheet = new Spreadsheet();
        $inspSheet = $spreadsheet->getActiveSheet();
        $inspSheet = $inspSheet->setTitle('Item');
        $xlCol = 'A';
        $rows = 1;
        foreach ($table_column as $tCols):
            $inspSheet->setCellValue($xlCol . $rows, $tCols);
            $xlCol++;
        endforeach;

        $rows = 2;
        foreach ($paramData as $row):
            $xlCol = "A";
            foreach ($table_column as $tCols):
                $inspSheet->setCellValue($xlCol . $rows, $row->{$tCols});
                $xlCol++;
            endforeach;
            $rows++;
        endforeach;

        /** Category Master */
        $catData = $this->itemCategory->getCategoryList(['category_type'=>$postData['item_type'],'final_category'=>1]);
        $catSheet = $spreadsheet->createSheet();
        $catSheet = $catSheet->setTitle('Item Category');
        $xlCol = 'A'; $rows = 1;
        $table_column_category = array('id', 'category_name');
        foreach ($table_column_category as $tCols):
            $catSheet->setCellValue($xlCol . $rows, $tCols);
            $xlCol++;
        endforeach;

        $rows = 2;        
        foreach ($catData as $row):
            $xlCol = 'A';
            foreach ($table_column_category as $tCols):
                $catSheet->setCellValue($xlCol . $rows, $row->{$tCols});
                $xlCol++;
            endforeach;
            $rows++;
        endforeach;

        /* Units */
        $unitData = $this->item->itemUnits();
        $unitSheet = $spreadsheet->createSheet();
        $unitSheet = $unitSheet->setTitle('Unit Master');
        $xlCol = 'A';$rows = 1;
        $table_column_unit = array('id', 'unit_name','description');

        foreach ($table_column_unit as $tCols):
            $unitSheet->setCellValue($xlCol . $rows, $tCols);
            $xlCol++;
        endforeach;
        $rows = 2; 
        foreach ($unitData as $row):
            $xlCol = 'A';
            foreach ($table_column_unit as $tCols):
                $unitSheet->setCellValue($xlCol . $rows, $row->{$tCols});
                $xlCol++;
            endforeach;
            $rows++;
        endforeach;

        /* Hsn Master */
        $hsnData = $this->hsnModel->getHSNList();
        $hsnSheet = $spreadsheet->createSheet();
        $hsnSheet = $hsnSheet->setTitle('HSN Master');
        $xlCol = 'A';$rows = 1;
        $table_column_hsn = array('id', 'hsn','gst_per','description');

        foreach ($table_column_hsn as $tCols):
            $hsnSheet->setCellValue($xlCol . $rows, $tCols);
            $xlCol++;
        endforeach;
        $rows = 2; 
        foreach ($hsnData as $row):
            $xlCol = 'A';
            foreach ($table_column_hsn as $tCols):
                $hsnSheet->setCellValue($xlCol . $rows, $row->{$tCols});
                $xlCol++;
            endforeach;
            $rows++;
        endforeach;

        /** Make Master */
        $brandList = $this->brandMaster->getBrandList();
        $makeSheet = $spreadsheet->createSheet();
        $makeSheet = $makeSheet->setTitle('Make Master');
        $xlCol = 'A';$rows = 1;
        $table_column_make = array('id','brand_name','remark');

        foreach ($table_column_make as $tCols):
            $makeSheet->setCellValue($xlCol . $rows, $tCols);
            $xlCol++;
        endforeach;

        $rows = 2;     
        foreach ($brandList as $row):
            $xlCol = 'A';   
            foreach ($table_column_make as $tCols):
                $makeSheet->setCellValue($xlCol . $rows, $row->{$tCols});
                $xlCol++;
            endforeach;
            $rows++;
        endforeach;

        $fileDirectory = realpath(APPPATH . '../assets/uploads/item_master');
        $fileName = '/'.str_replace([" ","-","/"],"_",$postData['item_type_name']).'_' . time() . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        $writer->save($fileDirectory . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url('assets/uploads/item_master') . $fileName);
    }

    public function importExcel(){
        $item_type = $this->input->post('item_type');
        if(!isset($file['name'])):
            $this->printJson(['status'=>2,'message'=>'Please Select File!']);
        endif;

        $fileData = $this->importExcelFile($_FILES['item_excel'], 'item_master', 'Item');
        $row = 0;$errorMessage = array();$itemData = array();
        if (isset($fileData['status'])):
            $this->printJson($fileData);
        else:
            $fieldArray = $fileData[0][1];
            for ($i = 2; $i <= count($fileData[0]); $i++):
                $rowData = array();$c = 'A';
                foreach ($fileData[0][$i] as $key => $colData) :
                    $rowData[strtolower($fieldArray[$c])] = $colData;
                    $c++;
                endforeach;
                $rowData['item_type'] = $item_type;

                if(empty($rowData['item_code']))
                    $errorMessage['item_code'] = "CAT No. is required at row no. : ".$i;

                if(empty($rowData['item_name']))
                    $errorMessage['item_name'] = "Item Name is required at row no. : ".$i;

                if(empty($rowData['unit_id']))
                    $errorMessage['unit_id'] = "Unit is required at row no. : ".$i;

                if(empty($rowData['category_id']))
                    $errorMessage['category_id'] = "Category is required at row no. : ".$i;

                if($this->item->checkDuplicate(['item_code'=>$rowData['item_code'],'id'=>$rowData['id']])  > 0)
                    $errorMessage['item_code'] = "CAT No. is duplicate at row no. : ".$i;

                if($this->item->checkDuplicate($rowData) > 0)
                    $errorMessage['item_name'] = "Item Name is duplicate at row no. : ".$i;

                if(!empty($errorMessage)):
                    $this->printJson(['status'=>0,'message'=>$errorMessage]);
                else:
                    $itemData[] = $rowData;
                endif;
                        
                $row++;
            endfor;
        endif;

        $i=0;
        if(!empty($itemData)):
            foreach($itemData as $row):
                $this->save($row); $i++;
            endforeach;
        endif;

        $this->printJson(['status' => 1, 'message' => $i . ' Record import successfully.']);
    }

}
?>