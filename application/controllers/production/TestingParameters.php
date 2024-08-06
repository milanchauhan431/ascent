<?php
class TestingParameters extends MY_Controller{
    private $index = "production/testing_param/index";
    private $form = "production/testing_param/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Testing Parameters";
		$this->data['headData']->controller = "production/testingParameters";
        $this->data['headData']->pageUrl = "production/testingParameters";
	}

    public function index(){
        $this->data['tableHeader'] = getProductionDtHeader("testingParameters");
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post(); 
        $result = $this->production->getTestingParametersDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row): 
            $row->sr_no = $i++;         
            $sendData[] = getTestingParametersData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addTestingParameter(){
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['system_detail']))
            $errorMessage['system_detail'] = "System Detail is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['insulation_resistance_json'] = (!empty($data['insulation_resistance_json']))?json_encode($data['insulation_resistance_json']):NULL;
            $this->printJson($this->production->saveTestingParameter($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->production->getTestingParameter($data);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->production->deleteTestingParameter($id));
        endif;
    }

    public function getSystemDetail(){
        $data = $this->input->post();
        $result = $this->production->getTestingParameter($data);

        $insulation_resistance_json = json_decode($result->insulation_resistance_json);

        $html = '';$i=1;
        foreach($insulation_resistance_json as $row):
            $html .= '<tr>
                <td>'.$row->param.'</td>
                <td>
                    <input type="hidden" name="paramData['.$i.'][id]" value="">
                    <input type="hidden" name="paramData['.$i.'][param_key]" value="'.$row->param.'">
                    <input type="text" name="paramData['.$i.'][param_value]" class="form-control" value="'.$row->param_value.'">
                </td>
            </tr>';
            $i++;
        endforeach;

        if(empty($html)):
            $html = '<tr><td colspan="2" class="text-center">No data available in table</td></tr>';
        endif;

        $result->insulation_resistance_param = $html;
        $this->printJson(['status'=>1,'data'=>$result]);
    }
}
?>