<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{

	public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'5'=>"5 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $deptCategory = ["1"=>"Admin","2"=>"HR","3"=>"Purchase","4"=>"Sales","5"=>"Store","6"=>"QC","7"=>"General","8"=>"Machining"];
	public $empRole = ["1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];
    public $systemDesignation = [1=>"Machine Operator",2=>"Line Inspector",3=>"Setter Inspector",4=>"Process Setter",5=>"FQC Inspector",6=>"Sale Executive",7=>"Designer",8=>"Production Executive"];
	public $maritalStatus = ["Married","UnMarried","Widow"];
	public $empType = [1=>"Permanent (Fix)",2=>"Permanent (Hourly)",3=>"Temporary"];
	public $empGrade = ["Grade A","Grade B","Grade C","Grade D"];
	public $paymentMode = ['CASH','CHEQUE','NEFT','UPI'];

	public $partyCategory = [1=>'Customer',2=>'Supplier',3=>'Vendor'/* ,4=>'Ledger' */];
	public $suppliedType = [1=>'Goods',2=>'Services',3=>'Goods & Services'];
	public $gstRegistrationTypes = [1=>'Regualr',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];
	public $automotiveArray = ["1" => 'Yes', "2" => "No"];
	public $vendorTypes = ['Manufacture', 'Service'];

	public $itemTypes = [1 => "Finish Goods", 2 => "Consumable", 3 => "Raw Material", 4 => "Capital Goods", 5 => "Machineries", 6 => "Instruments", 7 => "Gauges", 8 => "Services", 9 => "Packing Material", 10 => "Scrap"];
	public $stockTypes = [0=>"None",1=>'Batch Wise',2=>"Serial Wise"];
	
	public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->load->library('form_validation');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');

		/* Configration Models */
		$this->load->model("TermsModel","terms");
		$this->load->model("TransportModel","transport");
		$this->load->model("HsnMasterModel","hsnModel");
		$this->load->model("MaterialGradeModel","materialGrade");
		$this->load->model("VehicleTypeModel","vehicleType");

		/* HR Models */
		$this->load->model("hr/DepartmentModel","department");
		$this->load->model("hr/DesignationModel","designation");
		$this->load->model("hr/EmployeeCategoryModel","employeeCategory");
		$this->load->model("hr/ShiftModel","shiftModel");
		$this->load->model("hr/EmployeeModel","employee");

		/* Master Model */
		$this->load->model('PartyModel','party');
		$this->load->model('ItemCategoryModel','itemCategory');
		$this->load->model("BrandMasterModel","brandMaster");
		$this->load->model('ItemModel','item');

		/* Sales Model */
		$this->load->model('TransactionMainModel','transMainModel');
		$this->load->model('TaxMasterModel','taxMaster');
		$this->load->model('ExpenseMasterModel','expenseMaster');
		$this->load->model('SalesOrderModel','salesOrder');
		$this->load->model('DispatchChallanModel','dispatchChallan');

		/* Purchase Model */
		$this->load->model('PurchaseOrderModel','purchaseOrder');
		$this->load->model('PurchaseIndentModel','purchaseIndent');

		/* Store Model */
		$this->load->model('StoreLocationModel','storeLocation');
		$this->load->model('GateEntryModel','gateEntry');
		$this->load->model('GateInwardModel','gateInward');
		$this->load->model('MaterialIssueModel','materialIssue');

		/* Production Model */
		$this->load->model('production/ProductionModel','production');

		$this->setSessionVariables(["masterModel","dashboard","permission","terms","transport","hsnModel","materialGrade","itemCategory","item","department","designation","employeeCategory","shiftModel","employee","party","transMainModel","taxMaster","expenseMaster","salesOrder","purchaseOrder","purchaseIndent","vehicleType","storeLocation","gateEntry","gateInward","production","brandMaster","materialIssue","dispatchChallan"]);
	}

	public function setSessionVariables($modelNames){
		$this->data['dates'] = $this->dates = explode(' AND ',$this->session->userdata('financialYear'));
        $this->data['shortYear'] = $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->data['startYear'] = $this->startYear = date('Y',strtotime($this->dates[0]));
		$this->data['endYear'] = $this->endYear = date('Y',strtotime($this->dates[1]));
		$this->data['startYearDate'] = $this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->data['endYearDate'] = $this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));

		$this->loginId = $this->session->userdata('loginId');
		$this->userName = $this->session->userdata('user_name');
		$this->userRole = $this->session->userdata('role');
		$this->userRoleName = $this->session->userdata('roleName');

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->dates = $this->dates;
			$this->{$modelName}->shortYear = $this->shortYear;
			$this->{$modelName}->startYear = $this->startYear;
			$this->{$modelName}->endYear = $this->endYear;
			$this->{$modelName}->startYearDate = $this->startYearDate;
			$this->{$modelName}->endYearDate = $this->endYearDate;

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
		endforeach;
		return true;
	}
	
	public function isLoggedin(){
		if(!$this->session->userdata("loginId")):
			echo '<script>window.location.href="'.base_url().'";</script>';
		endif;
		return true;
	}
	
	public function printJson($data){
		print json_encode($data);exit;
	}
	
	public function checkGrants($url){
		$empPer = $this->session->userdata('emp_permission');
		if(!array_key_exists($url,$empPer)):
			redirect(base_url('error_403'));
		endif;
		return true;
	}
	
	/**** Generate QR Code ****/
	public function getQRCode($qrData,$dir,$file_name){
		if(isset($qrData) AND isset($file_name)):
			$file_name .= '.png';
			/* Load QR Code Library */
			$this->load->library('ciqrcode');
			
			if (!file_exists($dir)) {mkdir($dir, 0775, true);}

			/* QR Configuration  */
			$config['cacheable']    = true;
			$config['imagedir']     = $dir;
			$config['quality']      = true;
			$config['size']         = '1024';
			$config['black']        = array(255,255,255);
			$config['white']        = array(255,255,255);
			$this->ciqrcode->initialize($config);
	  
			/* QR Data  */
			$params['data']     = $qrData;
			$params['level']    = 'L';
			$params['size']     = 10;
			$params['savename'] = FCPATH.$config['imagedir']. $file_name;
			
			$this->ciqrcode->generate($params);

			return $dir. $file_name;
		endif;

		return false;
	}

	public function getTableHeader(){
		$data = $this->input->post();

		$response = call_user_func_array($data['hp_fn_name'],[$data['page']]);
		
		$result['theads'] = (isset($response[0])) ? $response[0] : '';
		$result['textAlign'] = (isset($response[1])) ? $response[1] : '';
		$result['srnoPosition'] = (isset($response[2])) ? $response[2] : 1;
		$result['sortable'] = (isset($response[3])) ? $response[3] : '';

		$this->printJson(['status'=>1,'data'=>$result]);
	}

	public function importExcelFile($file,$path,$sheetName){
		$item_excel = '';
		if(isset($file['name']) || !empty($file['name']) ):
			$this->load->library('upload');
			$_FILES['userfile']['name']     = $file['name'];
			$_FILES['userfile']['type']     = $file['type'];
			$_FILES['userfile']['tmp_name'] = $file['tmp_name'];
			$_FILES['userfile']['error']    = $file['error'];
			$_FILES['userfile']['size']     = $file['size'];
			
			$imagePath = realpath(APPPATH . '../assets/uploads/'.$path);
			$config = ['file_name' => $_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' =>$imagePath];

			$this->upload->initialize($config);
			if (!$this->upload->do_upload()):
				$errorMessage['item_excel'] = $this->upload->display_errors();
				$this->printJson(["status"=>0,"message"=>$errorMessage]);
			else:
				$uploadData = $this->upload->data();
				$item_excel = $uploadData['file_name'];
			endif;

			if(!empty($item_excel)):
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($imagePath.'/'.$item_excel);
				$fileData = array($spreadsheet->getSheetByName($sheetName)->toArray(null,true,true,true));
				return $fileData;
			else:
				return ['status'=>2,'message'=>'Data not found...!'];
			endif;
		else:
			return ['status'=>2,'message'=>'Please Select File!'];
		endif;
    }

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/removable_files/'),
        ];

        foreach($dirs as $dir):
            $files = array();
            $files = scandir($dir);
            unset($files[0],$files[1]);

            /*** cycle through all files in the directory ***/
            foreach($files as $file):
                /*** if file is 24 hours (86400 seconds) old then delete it ***/
                if(time() - filectime($dir.'/'.$file) > 86400):
                    unlink($dir.'/'.$file);
                endif;
            endforeach;
        endforeach;

        return true;
    }
}
?>