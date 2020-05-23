
<?php

/* 
 * @name Setup3redesign.php
 * @author Manorama Pal(palseema30@gmail.com) Salary heads, Salary formula, Salary head configuration
 * Salary head default value, CCa grade, HRA grade, Salary policies,SalaryCopy, SalarySlip, Transfer salary slip
 * generate salary slip pdf for regular leave transfer cases, mail function for sending monthly payslip as a attachment
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');


class Setup3redesign extends CI_Controller
{
    function __construct() {
        parent::__construct();
	$this->load->model('Common_model','commodel'); 
	$this->load->model('Login_model','lgnmodel');
	$this->load->model('SIS_model',"sismodel");
        $this->load->model("Mailsend_model","mailmodel");
        if(empty($this->session->userdata('id_user'))) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
		redirect('welcome');
        }
    }

    
    public function index () {
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        //$this->grademaster();
    }
    /********************* Add salary head form  *******************************************/
    public function salaryhead(){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        if(isset($_POST['addsalaryhead'])) {
            //form validation
            
            $this->form_validation->set_rules('salh_code','Salary Head Code','trim|required|xss_clean|alpha_numeric_spaces|callback_issalheadcode_Exist');
            $this->form_validation->set_rules('salh_name','Salary Head Name','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('salh_type','Salary Head Type','required|xss_clean');
            $this->form_validation->set_rules('salh_caltype','Calculation Type','trim|xss_clean');
            $this->form_validation->set_rules('salh_tax','Taxable','trim|xss_clean');
            $this->form_validation->set_rules('salh_cat','Category','trim|xss_clean');
            $this->form_validation->set_rules('salh_nickname','Salary Head Short Name','trim|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('salh_desc','Salary Head Description','trim|xss_clean');
           
            if($this->form_validation->run() == FALSE){
                $this->load->view('setup3/salaryhead');
                return;
            }//formvalidation
            else{
            	$shtyp=$this->input->post('salh_type');
  		if($shtyp == "I"){
                        $salhcat="GS";
			$caltype=$this->input->post('salh_caltype');
			$taxble=$this->input->post('salh_tax');
			if(empty($caltype) || empty($taxble)){
				$this->load->view('setup3/salaryhead');
		                return;
			}
		}
                if($shtyp == "D"){
                        $salhcat="GD";
			$caltype="N";
			$taxble="N";
		}
                if($shtyp == "L"){
                        $salhcat="GL";
			$caltype="N";
			$taxble="N";
		}
                $data = array(
                    'sh_code'                  =>$_POST['salh_code'],
                    'sh_name'                  =>$_POST['salh_name'],
                    'sh_tnt'                   =>$_POST['salhtnt'], 
                    'sh_shortname'             =>$_POST['salh_nickname'],
                    
                    'sh_type'                  =>$shtyp,
                    'sh_calc_type'             =>$caltype,
                    'sh_taxable'               =>$taxble,
                    'sh_category'              =>$salhcat,
                    'sh_ledgercode'            =>'',
                    'sh_description'           =>$_POST['salh_desc'], 
                    'sh_creatorid'             =>$this->session->userdata('username'),
                    'sh_creatordate'            =>date('y-m-d'),
                    'sh_modifierid'           =>$this->session->userdata('username'),
                    'sh_modifydate'             =>date('y-m-d'),
                ); 
                $dupcheck = array(
                   // 'sh_code'                  =>$_POST['salh_code'],
                    'sh_name'                  =>$_POST['salh_name'],
                    'sh_tnt'                   =>$_POST['salhtnt'],
                    //'sh_shortname'             =>$_POST['salh_nickname'],
                                                    
                ); 
                //$shcode = $this->input->post('salh_code', TRUE);
                $shname = $this->input->post('salh_name', TRUE);
               
                
                $salhdup = $this->sismodel->isduplicatemore('salary_head', $dupcheck);
                if($salhdup == 1 ){

                      $this->session->set_flashdata("err_message", "Record is already exist with this 'Head Name = $shname' ");
                      $this->load->view('setup3/salaryhead');
                      return;
                }
                else{
                    $salheadflag=$this->sismodel->insertrec('salary_head', $data);
                    if (!$salheadflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add salary head ", "salary head is not added ".$_POST['salh_name']);
                        $this->logger->write_dblogmessage("insert","Trying to add salary head ", "salary head is not added ".$_POST['salh_name']);
                        $this->session->set_flashdata('err_message','Error in adding salary head - '  , 'error');
                        redirect('setup3/salaryhead');
                    }
                    else{
                        $this->logger->write_logmessage("insert","Add salary head", "salary head".$_POST['salh_name']." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add  salary head", "salary head  ".$_POST['salh_name']."added  successfully...");
                        $this->session->set_flashdata("success", " Salary Head = "."[" .$_POST['salh_name']. "]" ." added successfully...");
                        redirect("setup3/salaryhead_list");
                    }
                    
                }
                          
            }//closer else form run true
            
        }
        $this->load->view('setup3/salaryhead');
    }
    
    /********************* closer salary head form  *******************************************/
    public function issalheadcode_Exist(){
        
        $salhcode = $this->input->post('salh_code', TRUE);
        if(!empty($salhcode)){
            $is_exist= $this->sismodel->isduplicate('salary_head','sh_code',$salhcode);
            if ($is_exist)
            {
                $this->form_validation->set_message('issalheadcode_Exist', 'Head Code =  ' . $salhcode .' is already exist.');
                return false;
            }
            else {
                return true;
            } 
        }    
        
       
    }
    
    /************************************** Display Salary Head records **************************/

    public function salaryhead_list($ht="I"){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$shdata['ht']=$ht;
	$whdata=array('sh_type'=>$ht);
	//$shdata['records']= $this->sismodel->get_list('salary_head');
	$shdata['records']= $this->sismodel->get_orderlistspficemore('salary_head','*',$whdata,'');
        $shdata['teach']= array();
        $shdata['nonteach']=array();
        $shdata['tntboth']=array();
        foreach($shdata['records'] as $record){
            if(($record->sh_tnt == NULL)||($record->sh_tnt == 'Common')){
                array_push($shdata['tntboth'],$record->sh_id);
            }
            if($record->sh_tnt =='Teaching'){
                array_push($shdata['teach'],$record->sh_id);
            
            }
            if($record->sh_tnt =='Non Teaching'){
            array_push($shdata['nonteach'],$record->sh_id);
            
            }
        }
        $this->logger->write_logmessage("view"," view Salary head list" );
        $this->logger->write_dblogmessage("view"," view Salary head list");
        $this->load->view('setup3/salaryhead_list',$shdata);
    }
    /********************* closer salary head list  *******************************************/
    
    /************************************** Edit Salary Head records **************************/
    public function edit_salaryhead($id){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['id'] = $id;
        $data['salhdata'] = $this->sismodel->get_listrow('salary_head','sh_id',$id)->row();
        $this->load->view('setup3/edit_salaryhead',$data);
    }
    /********************* closer edit salary head list  *******************************************/
    
    /****************************  START UPDATE DATA **********************************************************/
    public function update_salheaddata($id){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $salh_dataquery=$this->sismodel->get_listrow('salary_head','sh_id', $id);
        $salh_data['salhdata'] = $salh_dataquery->row();
        $salh_data['id'] = $id;
        if(isset($_POST['updatesalhead'])) {
            //form validation
            $this->form_validation->set_rules('salh_code','Head Code','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('salh_name','Head Name','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('salh_type','Head Type','required|xss_clean');
            $this->form_validation->set_rules('salh_caltype','Calculation Type','trim|xss_clean');
            $this->form_validation->set_rules('salh_tax','Taxable','trim|xss_clean');
            $this->form_validation->set_rules('salh_cat','Category','trim|xss_clean');
            $this->form_validation->set_rules('salh_nickname','Head Short Name','trim|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('salh_desc','Head Description','trim|xss_clean');
            if($this->form_validation->run() == FALSE){
                
                $this->load->view('setup3/edit_salaryhead',$salh_data);
                return;
            }//formvalidation
            else{
                $shcode = $this->input->post('salh_code', TRUE);
                $shtnt= $this->input->post('salhtnt', TRUE);
                $shname = $this->input->post('salh_name', TRUE);
                $shnickname = $this->input->post('salh_nickname', TRUE);
                $salhtype = $this->input->post('salh_type', TRUE);
                $salhcaltype = $this->input->post('salh_caltype', TRUE);
                $salhtax = $this->input->post('salh_tax', TRUE);
                $salhcategory = $this->input->post('salh_cat', TRUE);
                $salhdesc = $this->input->post('salh_desc', TRUE);
            
                if($salhtype == "I"){
                        $salhcategory="GS";
  //                      $caltype=$this->input->post('salh_caltype');
//                        $taxble=$this->input->post('salh_tax');
                        if(empty($salhcaltype) || empty($salhtax)){
                                $this->load->view('setup3/edit_salaryhead',$salh_data);
                                return;
                        }
                }
                if($salhtype == "D"){
                        $salhcategory="GD";
                        $salhcaltype="N";
                        $salhtax="N";
                }
                if($salhtype == "L"){
                        $salhcategory="GL";
                        $salhcaltype="N";
                        $salhtax="N";
                }

                $logmessage = "";
                if($salh_data['salhdata']->sh_code != $shcode)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_code. " changed by " .$shcode;
                if($salh_data['salhdata']->sh_name != $shname)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_name. " changed by " .$shname;
                if($salh_data['salhdata']->sh_shortname != $shnickname)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_shortname . " changed by " .$shnickname;
                if($salh_data['salhdata']->sh_type != $salhtype)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_type . " changed by " .$salhtype;
                if($salh_data['salhdata']->sh_calc_type != $salhcaltype)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_calc_type . " changed by " .$salhcaltype;
                if($salh_data['salhdata']->sh_taxable != $salhtax)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_taxable . " changed by " .$salhtax;
                if($salh_data['salhdata']->sh_category != $salhcategory)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_category . " changed by " .$salhcategory;
                if($salh_data['salhdata']->sh_description != $salhdesc)
                    $logmessage = "Edit Salary Head Data " .$salh_data['salhdata']->sh_description . " changed by " .$salhdesc;
                
                $edit_data = array(
                    'sh_code'                  =>$_POST['salh_code'],
                    'sh_name'                  =>$_POST['salh_name'],
                    'sh_tnt'                   =>$_POST['salhtnt'],
                    'sh_shortname'             =>$_POST['salh_nickname'],
                    'sh_type'                  =>$salhtype,
                    'sh_calc_type'             =>$salhcaltype,
                    'sh_taxable'               =>$salhtax,
                    'sh_category'              =>$salhcategory,
                    'sh_ledgercode'            =>'',
                    'sh_description'           =>$_POST['salh_desc'], 
                    'sh_creatorid'             =>$this->session->userdata('username'),
                    'sh_creatordate'            =>date('y-m-d'),
                    'sh_modifierid'           =>$this->session->userdata('username'),
                    'sh_modifydate'             =>date('y-m-d'),
                );
               
                if($salh_data['salhdata']->sh_code != $shcode){
                    
                    $dupcheck = array(
                        'sh_code'                  =>$_POST['salh_code'],
                        //'sh_name'                  =>$_POST['salh_name'],
                
                    ); 
                    $salhdup = $this->sismodel->isduplicatemore('salary_head', $dupcheck);
                    if($salhdup == 1 ){

                      $this->session->set_flashdata("err_message", "Record is already exist with this ' Head Code = $shcode ' so please assign any other code. ");
                      $this->load->view('setup3/edit_salaryhead',$salh_data);
                      return;
                    }
               
                }//if dupcondiotion 
                if($salh_data['salhdata']->sh_name !=$shname){
                     $dupcheck = array(
                       // 'sh_code'                  =>$_POST['salh_code'],
                        'sh_name'                  =>$_POST['salh_name'],
                        'sh_tnt'                   =>$_POST['salhtnt'],
                
                    ); 
                    $salhdup = $this->sismodel->isduplicatemore('salary_head', $dupcheck);
                    if($salhdup == 1 ){

                      $this->session->set_flashdata("err_message", "Record is already exist with this  ' Head Name = $shname ' so please change it. ");
                      $this->load->view('setup3/edit_salaryhead',$salh_data);
                      return;
                    }
                 
                }//ifdupsalhname
                $editshflag=$this->sismodel->updaterec('salary_head', $edit_data, 'sh_id', $id);
                if(!$editshflag){
                    $this->logger->write_logmessage("error","Edit salary head error", "Edit salary head details. $logmessage ");
                    $this->logger->write_dblogmessage("error","Edit salary head error", "Edit salary head details. $logmessage ");
                    $this->session->set_flashdata('err_message','Error in updating salary head - ' . $logmessage . '.', 'error');
                    $this->load->view('setup3/edit_salaryhead', $edit_data);
                    
                }
                else{
                    $this->logger->write_logmessage("update","Edit Salary Head by".$this->session->userdata('username') , "Edit Salary head details. $logmessage ");
                    $this->logger->write_dblogmessage("update","Edit salary head by".$this->session->userdata('username') , "Edit salary head details. $logmessage ");
                    $this->session->set_flashdata('success','Salary Head details updated successfully.');
                    redirect('setup3/salaryhead_list');
                    
                }
               
            }//else form validation
        }// isset button
    }
    /****************************  closer update data salary head **********************************************/
    
    /************************************** Display salary heads for formula  **************************/

    public function salaryformula_list(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$selectfield="salary_head.sh_id, salary_head.sh_code, salary_head.sh_name, salary_head.sh_tnt,salary_formula.sf_id, salary_formula.sf_formula";
	$joincond = 'salary_formula.sf_salhead_id = salary_head.sh_id';
	$whdata = array('salary_head.sh_calc_type'=> 'Y');
	$data['formulrecord'] =$this->sismodel->get_jointbrecord('salary_head',$selectfield,'salary_formula',$joincond,'left',$whdata);
//        $data['formulrecord'] =$this->sismodel->salhead_formula();
        $this->logger->write_logmessage("view"," view Salary head  formula list" );
        $this->logger->write_dblogmessage("view"," view Salary head formula list");
        $this->load->view('setup3/salformula_list',$data);
    }
    /********************* closer Display salary heads for formula  *******************************************/
    
    /************************************** Add salary head formula  **************************/

    public function add_salaryformula($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['id'] = $id;
        $data['salhdata'] = $this->sismodel->get_listrow('salary_head','sh_id',$id)->row();
        if(isset($_POST['add_salhformula'])) {
            
            $this->form_validation->set_rules('salh_formula','Apply Formula','trim|xss_clean');
            $this->form_validation->set_rules('salh_desc','Head Description','trim|xss_clean');
            
            if($this->form_validation->run() == FALSE){
                
                $this->load->view('setup3/add_salaryformula',$data);
                return;
            }//formvalidation
            else{
                              
                $data = array(
                    'sf_salhead_id'            =>$id,
                    'sf_formula'               =>$_POST['salh_formula'],
                    'sf_description'           =>$_POST['salh_desc'],
                    'sf_creatorid'             =>$this->session->userdata('username'),
                    'sf_creatordate'            =>date('y-m-d'),
                    'sf_modifierid'           =>$this->session->userdata('username'),
                    'sf_modifydate'             =>date('y-m-d'),
                );
                
                $salhformulaflag=$this->sismodel->insertrec('salary_formula', $data);
                
                if (!$salhformulaflag)
                {
                        $this->logger->write_logmessage("insert","Trying to add formula for salary head ", "Formula for salary head is not added ".$_POST['salh_name']);
                        $this->logger->write_dblogmessage("insert","Trying to add formula for salary head ", "Formula for salary head is not added ".$_POST['salh_name']);
                        $this->session->set_flashdata('err_message','Error in adding formula for salary head - '  , 'error');
                        $this->load->view('setup3/add_salaryformula', $data);
                }
                else{
                        $this->logger->write_logmessage("insert","Add formula for salary head", " formula for salary head [".$_POST['salh_name']." ] is added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add formula for salary head", " formula for salary head [ ".$_POST['salh_name']." ] is added  successfully...");
                        $this->session->set_flashdata("success", " Formula for Salary Head = "."[" .$_POST['salh_name']. "]" ." is added successfully...");
                        redirect("setup3/salaryformula_list");
                }
                    
            }//else true form cond
            
        }//post button
        $this->load->view('setup3/add_salaryformula',$data);
    }
    /********************* closer Display salary heads for formula aaply records  *******************************************/
    
    /************************************** Edit salary head formula  **************************/

    public function edit_salaryformula($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['id'] = $id;
        $data['salhdata'] = $this->sismodel->get_listrow('salary_formula','sf_id',$id)->row();
        if(isset($_POST['edit_salhformula'])) {
            
            $this->form_validation->set_rules('salh_formula','Apply Formula','trim|xss_clean');
            $this->form_validation->set_rules('salh_desc','Head Description','trim|xss_clean');
            
            if($this->form_validation->run() == FALSE){
                
                $this->load->view('setup3/edit_salaryformula',$data);
                return;
            }//formvalidation
            else{
                
                $edit_data = array(
                    'sf_salhead_id'            =>$data['salhdata']->sf_salhead_id,
                    'sf_formula'               =>$_POST['salh_formula'],
                    'sf_description'           =>$_POST['salh_desc'],
                    'sf_creatorid'             =>$this->session->userdata('username'),
                    'sf_creatordate'            =>date('y-m-d'),
                    'sf_modifierid'           =>$this->session->userdata('username'),
                    'sf_modifydate'             =>date('y-m-d'),
                );
                              
                $editshforflag=$this->sismodel->updaterec('salary_formula', $edit_data, 'sf_id', $id);
                if (!$editshforflag)
                {   $this->logger->write_logmessage("error","Edit Formula error", "Edit Salary formula. $logmessage ");
                    $this->logger->write_dblogmessage("error","Edit Formula error", "Edit salary formula. $logmessage ");
                    $this->session->set_flashdata('err_message','Error in updating Formula - ' . $logmessage . '.', 'error');
                    $this->load->view('setup3/edit_salaryhead', $edit_data);
                }
                else{
                    
                    $this->logger->write_logmessage("update","Edit salary formula by".$this->session->userdata('username') , "Edit Salary formula. $logmessage ");
                    $this->logger->write_dblogmessage("update","Edit salary formula by".$this->session->userdata('username') , "Edit salary formula. $logmessage ");
                    $this->session->set_flashdata('success','Salary formula details updated successfully.'." Salary Head = "."[ " .$_POST['salh_name']. " ]");
                    redirect("setup3/salaryformula_list");
                }
                    
            }//else true form cond
            
        }//post button
        $this->load->view('setup3/edit_salaryformula',$data);
    }
    /********************* closer Edit salary heads  formula  *******************************************/
       
    /********************* Add Employee type form  *******************************************/
    public function employeetype(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        if(isset($_POST['addemptype'])) {
            //form validation
            
            $this->form_validation->set_rules('emptype_code','Post Type Code','trim|required|xss_clean|alpha_numeric_spaces|callback_isemptypecode_Exist');
            $this->form_validation->set_rules('emptype_name','Post Type Name','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('pfapplies','PF applies','trim|xss_clean');
            $this->form_validation->set_rules('maxpf_limit','Max PF Limit','trim|xss_clean|numeric');
            $this->form_validation->set_rules('emptype_sname','Employee Short name','trim|xss_clean|alpha_numeric_spaces');
                                  
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/employeetype');
                return;
            }//formvalidation
            else{
               
                $data = array(
                    'empt_code'                  =>$_POST['emptype_code'],
                    'empt_name'                  =>$_POST['emptype_name'],
                    'empt_tnt'                  =>$_POST['emptnt'],
                    'empt_shortname'             =>$_POST['emptype_sname'],
                    'empt_pfapplies'             =>$_POST['pfapplies'],
                    'empt_maxpflimit'            =>$_POST['maxpf_limit'],
                    'empt_creatorid'              =>$this->session->userdata('username'),
                    'empt_creatordate'            =>date('y-m-d'),
                    'empt_modifierid'             =>$this->session->userdata('username'),
                    'empt_modifydate'             =>date('y-m-d'),
                ); 
                $dupcheck = array(
                    'empt_code'                  =>$_POST['emptype_code'],
                    'empt_name'                  =>$_POST['emptype_name'],
                    'empt_tnt'                  =>$_POST['emptnt'],
                               
                ); 
              
                $etname = $this->input->post('empt_name', TRUE);
                $emptdup = $this->sismodel->isduplicatemore('employee_type', $dupcheck);
                if($emptdup == 1 ){
                    
                    $this->session->set_flashdata("err_message", "Record is already exist with this 'Post Type Name = $etname' ");
                    $this->load->view('setup3/employeetype');
                    return;
                }
                else{
                    $emptyeflag=$this->sismodel->insertrec('employee_type', $data);
                    if (!$emptyeflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add Post type ", " post type is not added ".$etname);
                        $this->logger->write_dblogmessage("insert","Trying to add Post type ", " post type is not added ".$etname);
                        $this->session->set_flashdata('err_message','Error in adding post type - '  , 'error');
                        redirect('setup3/employeetype');
                    }
                    else{
                        $this->logger->write_logmessage("insert","Add post type ", "post type ".$_POST['emptype_name'] ." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add  post type ", "post type  ".$_POST['emptype_name'] ."added  successfully...");
                        $this->session->set_flashdata("success", " Post Type = "."[" .$_POST['emptype_name'] . "]" ." record insert successfully...");
                        redirect("setup3/employeetype_list");
                    }
                    
                }
                          
            }//closer else form run true
            
        }
        $this->load->view('setup3/employeetype');
    }
       
    /*********************  closer Add Employee type form  *******************************************/
    
    /********************* check for duplicate employee type code  *******************************************/
    public function isemptypecode_Exist(){
        
        $etcode = $this->input->post('emptype_code', TRUE);
        if(!empty($etcode)){
            $is_exist= $this->sismodel->isduplicate('employee_type','empt_code',$etcode);
            if ($is_exist)
            {
                $this->form_validation->set_message('isemptypecode_Exist', 'Post Type Code =  ' . $etcode .' is already exist. so please insert any other code.');
                return false;
            }
            else {
                return true;
            } 
        }    
        
       
    }
    
    /************************************** closer check for duplicate employee type code  ************/
    /************************************** Display employee type  **************************/

    public function employeetype_list(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['emptype_record'] =$this->sismodel->get_list('employee_type');
        $this->logger->write_logmessage("view"," view post type list" );
        $this->logger->write_dblogmessage("view"," view post type list");
        $this->load->view('setup3/emptype_list',$data);
    }
     /**************************************closer  Display employee type  **************************/
    /********************* Add Employee type form  *******************************************/
    public function edit_employeetype($id){
       $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['id'] = $id;
        $data['emptypedata'] = $this->sismodel->get_listrow('employee_type','empt_id',$id)->row();
        if(isset($_POST['updateemptype'])) {
            //form validation
            
            $this->form_validation->set_rules('emptype_code','Post Type Code','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('emptype_name','Post Type Name','trim|required|xss_clean|alpha_numeric_spaces');
            $this->form_validation->set_rules('pfapplies','PF applies','trim|xss_clean');
            $this->form_validation->set_rules('maxpf_limit','Max PF Limit','trim|xss_clean|numeric');
            $this->form_validation->set_rules('emptype_sname','Post type Short name','trim|xss_clean|alpha_numeric_spaces');
                                  
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/edit_emptype',$data);
                return;
            }//formvalidation
            else{
                $etcode = $this->input->post('emptype_code', TRUE);
                $etname = $this->input->post('emptype_name', TRUE);
                $etype = $this->input->post('emptnt', TRUE);
                $etnickname = $this->input->post('emptype_sname', TRUE);
                $etpfaply = $this->input->post('pfapplies', TRUE);
                $etpfmaxlimit = $this->input->post('maxpf_limit', TRUE);
                
                
                $logmessage = "";
                if($data['emptypedata']->empt_code != $etcode)
                    $logmessage = "Edit Post Type Data " .$data['emptypedata']->empt_code. " changed by " .$etcode;
                if($data['emptypedata']->empt_name != $etname)
                    $logmessage = "Edit Post Type Data " .$data['emptypedata']->empt_name. " changed by " .$etname;
                if($data['emptypedata']->empt_shortname != $etnickname)
                    $logmessage = "Edit Post Type Data " .$data['emptypedata']->empt_shortname . " changed by " .$etnickname;
                if($data['emptypedata']->empt_pfapplies != $etpfaply)
                    $logmessage = "Edit Post Type Data " .$data['emptypedata']->empt_pfapplies . " changed by " .$etpfaply;
                if($data['emptypedata']->empt_maxpflimit != $etpfmaxlimit)
                    $logmessage = "Edit Post Type Data " .$data['emptypedata']->empt_maxpflimit . " changed by " .$etpfmaxlimit;
                
                $editdata = array(
                    'empt_code'                  =>$_POST['emptype_code'],
                    'empt_name'                  =>$_POST['emptype_name'],
                    'empt_tnt'                  =>$_POST['emptnt'],
                    'empt_shortname'             =>$_POST['emptype_sname'],
                    'empt_pfapplies'             =>$_POST['pfapplies'],
                    'empt_maxpflimit'            =>$_POST['maxpf_limit'],
                    'empt_modifierid'             =>$this->session->userdata('username'),
                    'empt_modifydate'             =>date('y-m-d'),
                ); 
               
                if($data['emptypedata']->empt_code != $etcode){
                    
                    $dupcheck = array(
                        'empt_code'                  =>$_POST['emptype_code'],
                        //'empt_name'                  =>$_POST['emptype_name'],
                
                    ); 
                    $emptypedup = $this->sismodel->isduplicatemore('employee_type', $dupcheck);
                    if($emptypedup == 1 ){
                      
                      $this->session->set_flashdata("err_message", "Record is already exist with this ' Code = $etcode so please assign any other code. ");
                      $this->load->view('setup3/edit_emptype',$data);
                      return;
                    }
                                        
                }//if dupcondiotion 
                if($data['emptypedata']->empt_name != $etname){
                     $dupcheck = array(
                    
                        'empt_name'                  =>$_POST['emptype_name'],
                                        
                    ); 
                    $emptypedup = $this->sismodel->isduplicatemore('employee_type', $dupcheck);
                    if($emptypedup == 1 ){

                      $this->session->set_flashdata("err_message", "Record is already exist with this  ' Name = $etname ' so please change it. ");
                      $this->load->view('setup3/edit_emptype',$data);
                      return;
                    }
                    
                }//ifdupcondition
                $editetflag=$this->sismodel->updaterec('employee_type', $editdata, 'empt_id', $id);
                if(!$editetflag){
                      
                    $this->logger->write_logmessage("error","Edit post type error", "Edit post type details. $logmessage ");
                    $this->logger->write_dblogmessage("error","Edit post type error", "Edit post type. $logmessage ");
                    $this->session->set_flashdata('err_message','Error in updating post type - ' . $logmessage . '.', 'error');
                    $this->load->view('setup3/edit_emptype', $data);
                    
                }
                else{
                    $this->logger->write_logmessage("update","Edit post type by  ".$this->session->userdata('username') , "Edit post type details. $logmessage ");
                    $this->logger->write_dblogmessage("update","Edit post type by  ".$this->session->userdata('username') , "Edit post type details. $logmessage ");
                    $this->session->set_flashdata('success','Record updated successfully.'.'[ Post Type is = ' .$_POST['emptype_name'] .' ]');
                    redirect('setup3/employeetype_list');
                    
                }
                
                           
            }//closer else form run true
            
        }
        $this->load->view('setup3/edit_emptype',$data);
    }
       
    /*********************  closer Add Employee type form  *******************************************/
       
    /*********************  Salary Head Configuration form  *******************************************/
    public function salhead_config(){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->emptype= $this->sismodel->get_list('employee_type');
        $this->salhead =$this->sismodel->get_list('salary_head');
        $data=array();
        $shconf=array();
        $emptype = $this->input->post('emptype', TRUE);
        if(!empty($emptype)){
            $data['seloption'] = $emptype;
            //$data['seloption'] = $emptype;
            $this->confval=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid ','shc_emptypeid',$emptype);
            if(!empty($this->confval)){
                $data['shconf']=$this->confval->shc_salheadid;
            }
                       
        }
        if(isset($_POST['update'])){
                      
            $checklist = $this->input->post('check_list', TRUE);
            $shlist=(join(", ", $checklist));
                     
            $dupexists=$this->sismodel->isduplicate('salaryhead_configuration','shc_emptypeid',$emptype);
            if(!$dupexists){
                $updata = array(
                    'shc_emptypeid'                 =>$emptype,
                    'shc_salheadid'                 =>$shlist,
                    'shc_scid'                      =>NULL,
                    'shc_creatorid'                 =>$this->session->userdata('username'),
                    'shc_creatordate'               =>date('y-m-d'),
                    'shc_modifierid'                =>$this->session->userdata('username'),
                    'shc_modifydate'                =>date('y-m-d'),
                 );
                $shconfigflag = $this->sismodel->insertrec('salaryhead_configuration', $updata);
            }
            else{
               
                $updata = array(
                    'shc_emptypeid'                 =>$emptype,
                    'shc_salheadid'                 =>$shlist,
                    'shc_scid'                      =>NULL,
                   // 'shc_creatorid'                 =>$this->session->userdata('username'),
                    //'shc_creatordate'               =>date('y-m-d'),
                    'shc_modifierid'                =>$this->session->userdata('username'),
                    'shc_modifydate'                =>date('y-m-d'),
                );
                $shconfigflag = $this->sismodel->updaterec('salaryhead_configuration',$updata,'shc_emptypeid',$emptype);
              //  $emptype = $this->input->post('seloption', TRUE);
                //$data['seloption'] = $emptype;
            }
            
            if (!$shconfigflag)
            {
                $this->logger->write_logmessage("insert","Trying to add Employee type wise salary head configuration ", " employee type wise salary head configuration is not added ".$etname);
                $this->logger->write_dblogmessage("insert","Trying to add Employee type wise salary head configuration ", "employee type wise salary head configuration is not added ".$etname);
                $this->session->set_flashdata('err_message','Error in Employee type wise salary head configuration - '  , 'error');
                redirect('setup3/salhead_config');
            }
            else{
               
                $this->logger->write_logmessage("insert","Employee type wise salary head configuration ", "Employee type wise salary head configuration added  successfully...");
                $this->logger->write_dblogmessage("insert","Employee type wise salary head configuration ", "Employee type wise salary head configuration added  successfully...");
                $this->session->set_flashdata("success", "  Employee type wise salary head configuration updated successfully...");
                redirect('setup3/salhead_config',$data);
               // $this->load->view('setup3/add_salheadconfig',$data);
                //return;
                
            }
                          
            
        }
        $this->load->view('setup3/add_salheadconfig',$data);
         
    }
    /*********************  closer Salary Head Configuration form  *******************************************/
    
    /*********************  Salary Head  Default value  form *******************************************/
    public function shdefaultvalue(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->salhead =$this->sismodel->get_list('salary_head');
        $data=array();
        $pband = $this->input->post('payband', TRUE);
       if(isset($_POST['load'])){
            if(!empty($pband)){
                $selectfield ="shdv_paybandid, shdv_salheadid,shdv_defaultvalue";
                $whdata = array ('shdv_paybandid' => $pband,'shdv_defaultvalue != ' => NULL);
                $is_exist= $this->sismodel->isduplicate('salaryhead_defaultvalue','shdv_paybandid',$pband);
                //echo "exitt=====".$is_exist;
                //die;
                if($is_exist){
                    $data['seloption'] = $pband;
                    //echo "exitt==if===";
                    $cdata = $this->sismodel->get_listspficemore('salaryhead_defaultvalue',$selectfield,$whdata);
                    $data['shdvalue']=$cdata;
                }
                else{
                    $data['seloption'] = $pband;
                    //echo "exitt==else===";
                    $cdata='';
                    $data['shdvalue']=$cdata;
                    redirect('setup3/shdefaultvalue/'.$data['seloption']);
                    //return;
                }
           
           
            }
        }
        if(isset($_POST['update'])){
            $tsize = $this->input->post('totalsize', TRUE);
          //  echo "tsize====".$tsize;
            for ($i=0; $i<$tsize ;$i++){                        
                $shid = $this->input->post('sheadid'.$i, TRUE);
                $dfvalue= $this->input->post('defaultval'.$i, TRUE); 
                // echo "shid====".$shid."dval====".$dfvalue,"pb===".$pband;
                $dupdata=array(
                    'shdv_paybandid'                 =>$pband,
                    'shdv_salheadid'                 =>$shid,   
                );
                $dupexists=$this->sismodel->isduplicatemore('salaryhead_defaultvalue',$dupdata);
                if(!$dupexists){
                    
                    $updata = array(
                        'shdv_paybandid'                 =>$pband,
                        'shdv_salheadid'                 =>$shid,
                        'shdv_defaultvalue'              =>$dfvalue,
                        'shdv_remarks'                   =>NULL,
                        'shdv_creatorid'                 =>$this->session->userdata('username'),
                        'shdv_creatordate'               =>date('y-m-d'),
                        'shdv_modifierid'                =>$this->session->userdata('username'),
                        'shdv_modifydate'                =>date('y-m-d'),
                    ); 
               
                    $shdvalflag = $this->sismodel->insertrec('salaryhead_defaultvalue', $updata);
             
                }
                else{
                
              
                    $updata = array(
                        'shdv_paybandid'                 =>$pband,
                        'shdv_salheadid'                 =>$shid,
                        'shdv_defaultvalue'              =>$dfvalue,
                        'shdv_remarks'                   =>NULL,
                        //'shdv_creatorid'                 =>$this->session->userdata('username'),
                        //'shdv_creatordate'               =>date('y-m-d'),
                        'shdv_modifierid'                =>$this->session->userdata('username'),
                        'shdv_modifydate'                =>date('y-m-d'),
                    ); 
                    $datawh=array('shdv_paybandid' => $pband,'shdv_salheadid' => $shid);
                    $cdata = $this->sismodel->get_listspficemore('salaryhead_defaultvalue','shdv_id',$datawh);
                    $sdid=$cdata[0]->shdv_id;
                    $shdvalflag = $this->sismodel->updaterec('salaryhead_defaultvalue',$updata,'shdv_id',$sdid);
                }
                
            
            }//totalsize  
            if (!$shdvalflag)
            {
                $this->logger->write_logmessage("insert","Trying to add Payband wise salary head default value ", " Payband wise salary head default value is not added ".$etname);
                $this->logger->write_dblogmessage("insert","Trying to add Payband wise salary head default value ", " Payband wise salary head default value is not added ".$etname);
                $this->session->set_flashdata('err_message','Error in  Payband wise salary head default value - '  , 'error');
                redirect('setup3/shdefaultvalue',$data);
            }
            else{
                $this->logger->write_logmessage("insert"," Payband wise salary head default value ", "Payband wise salary head default value added  successfully...");
                $this->logger->write_dblogmessage("insert","  Payband wise salary head default value ", "Payband wise salary head default value added  successfully...");
                $this->session->set_flashdata("success", "   Payband wise salary head default value updated successfully...");
                redirect("setup3/shdefaultvalue",$data);
            }
        }
        $this->load->view('setup3/shdefaultvalue',$data);
        
    }
    /*********************  closer Salary Head Default value  *******************************************/
    
    /*********************   Salary Slip  form *********************************************************/
    public function salaryslip(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $empid=$this->uri->segment(3);
        $this->emptnt=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $selectfield ="sh_id, sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
        $whorder = "sh_id asc,sh_name asc";
       // $whdata = array ('saq_empid' => $emp_id,'saq_dgree NOT LIKE ' => 'B%','saq_dgree NOT LIKE ' => 'M%');
	$whdata = array('sh_type' =>'I');// 'sh_tnt' => $this->emptnt,'sh_tnt' => NULL);
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
       
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $data['deduction']=array_merge($data['ded'], $data['loans']);
         
        $this->emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
	$this->emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code ','emp_id',$empid)->emp_type_code;
        $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype ','emp_id',$empid)->emp_worktype;
        $paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        
       // echo $this->wtype.", ".$this->paycomm;
        
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$paycomm,'shc_wtype'=> $wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
	if(!empty($rawarray)){
        	$strarray=$rawarray[0]->shc_salheadid;
        	$data['allowedhead']=explode(", ",$strarray);
	}
	else{
		$data['allowedhead']=array();
	}
       // print_r($strarray);
        //print_r($data['allowedhead']);
       // die();
        if(isset($_POST['upsalhdval'])){
            $tcount = $this->input->post('totalcount', TRUE);
            $tded = $this->input->post('totalded', TRUE);
            $month=$this->uri->segment(4);
            $year=$this->uri->segment(5);
            $totalincome=0;
            $totaldeduction = 0;
            $netpay = 0;
            /***************************Incomes************************************/
            for ($i=0; $i<$tcount ;$i++){
                $headidin = $this->input->post('sheadidin'.$i, TRUE);
                $headval = $this->input->post('headamtI'.$i, TRUE);

        	$dupdata=array('sald_empid'=>$empid, 'sald_sheadid'=>$headidin,'sald_month'=>$month,'sald_year'=>$year);
                $existf=$this->sismodel->isduplicatemore('salary_data', $dupdata);
                if($existf){
			$irawarray=$this->sismodel->get_orderlistspficemore('salary_data','sald_id,sald_shamount',$dupdata,'sald_id desc');
			if(!empty($irawarray)){
				$entryid=$irawarray[0]->sald_id;
				$prvamt=$irawarray[0]->sald_shamount;
				//update record
				$iupdata=array('sald_shamount'=>$headval);
				$iupflag=$this->sismodel->updaterec('salary_data', $iupdata, 'sald_id', $entryid);	
				$upsaldataflag = $iupflag;
				//insert into archive
				$saldataa = array(
	                            'salda_saldid'        =>$entryid,
	                            'salda_empid'        =>$empid,
        	                    'salda_sheadid'      =>$headidin,
                	            'salda_shamount'     =>$prvamt,
                        	    'salda_installment'  => NULL,
	                            'salda_month'        =>$month,
        	                    'salda_year'         =>$year,
                	        );
                        	$saldataarflag = $this->sismodel->insertrec('salary_data_archive', $saldataa);
			}
			
		}else{      
                	$saldata = array(
	                    'sald_empid'        =>$empid,
        	            'sald_sheadid'      =>$headidin,
                	    'sald_shamount'     =>$headval,
	                    'sald_installment'  => NULL,
        	            'sald_month'        =>$month,
                	    'sald_year'         =>$year,
	                );
        	        $upsaldataflag = $this->sismodel->insertrec('salary_data', $saldata);
		}
                $totalincome+=$headval;
            } //tcount
            /*******************************Deductions***********************************/
            for ($j=0; $j<$tded ;$j++){
                $headidD = $this->input->post('sheadidded'.$j, TRUE);
                $headvald = $this->input->post('headamtD'.$j, TRUE);
               
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$headidD);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan= $this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    $installdetl=$installno."-".$totalinstall;
                    
                }
                else{
                    $installdetl =NULL;   
                }

        	$dupdata=array('sald_empid'=>$empid, 'sald_sheadid'=>$headidD,'sald_month'=>$month,'sald_year'=>$year);
                $existdf=$this->sismodel->isduplicatemore('salary_data', $dupdata);
                if($existdf){
			$drawarray=$this->sismodel->get_orderlistspficemore('salary_data','sald_id,sald_shamount,sald_installment',$dupdata,'sald_id desc');
			if(!empty($drawarray)){
				$entryid=$drawarray[0]->sald_id;
				$prvamt=$drawarray[0]->sald_shamount;
				$prvinstallno=$drawarray[0]->sald_installment;
				//update record
				$updatad=array('sald_shamount'=>$headvald);
				$upflagd=$this->sismodel->updaterec('salary_data', $updatad, 'sald_id', $entryid);	
				$upsaldataflag = $upflagd;
				//insert into archive
				$saldataa = array(
	                            'salda_saldid'        =>$entryid,
	                            'salda_empid'        =>$empid,
        	                    'salda_sheadid'      =>$headidD,
                	            'salda_shamount'     =>$prvamt,
                        	    'salda_installment'  =>$prvinstallno,
	                            'salda_month'        =>$month,
        	                    'salda_year'         =>$year,
                	        );
                        	$saldataarflag = $this->sismodel->insertrec('salary_data_archive', $saldataa);
			}			
		}else{      
                	$saldata = array(
	                    'sald_empid'        =>$empid,
        	            'sald_sheadid'      =>$headidD,
                	    'sald_shamount'     => $headvald,
	                    'sald_installment'  =>$installdetl,
        	            'sald_month'        =>$month,
                	    'sald_year'         =>$year,
                	);
	                $upsaldataflag = $this->sismodel->insertrec('salary_data', $saldata);
		}

                $totaldeduction+=$headvald;
               // print_r("seema===".$instloan[0]->slh_id."\n".$installdetl."\n".$empid."\n". $headidD);
               // die();
                
            }//totalcount 
            //die();
            $netpay=$totalincome - $totaldeduction;
            $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
            $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
            $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
            $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
            $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
            $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
            $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
            $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
            $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
            $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
            $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
            $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
            
            $saldata1 = array(
               'sal_empid'             =>$empid,
               'sal_scid'              =>$scid,
               'sal_uoid'              =>$uoccid,
               'sal_deptid'            =>$deptid,
               'sal_desigid'           =>$desigid,
               'sal_sapost'            =>$sopost,
               'sal_ddoid'             =>$ddoid,
               'sal_schemeid'          =>$schmid,
               'sal_payscaleid'        =>$payscaleid,
               'sal_bankaccno'         =>$bankaccno,
               'sal_worktype'          =>$wtype,
               'sal_emptype'           =>$emptype,
               'sal_group'             =>$group,
               'sal_month'             =>$month,
               'sal_year'              =>$year,
               'sal_totalincome'       =>$totalincome,
               'sal_totaldeduction'    =>$totaldeduction,
               'sal_netsalary'         =>$netpay,
               'sal_status'            =>'paid',
               'sal_paiddate'          =>date('y-m-d'),
               'sal_creatorid'         =>$this->session->userdata('username'),
               'sal_creationdate'       =>date('y-m-d'),
               'sal_updatedate'        =>date('y-m-d'),    
               'sal_modifierid'        =>$this->session->userdata('username'),
            );
              
            if (!$upsaldataflag)
            {
                $this->logger->write_logmessage("insert","Trying to add  salary data head wise", "  salary data head wise value is not added ".$this->emppfno);
                $this->logger->write_dblogmessage("insert","Trying to add salary data head wise ", " salary data head wise value is not added ".$this->emppfno);
                $this->session->set_flashdata('err_message','Error in  salary data head wise value - '  , 'error');
                redirect('setup3redesign/salaryslip',$data);
            }
            else{
           	// check record exist
        	$sdupdata=array('sal_empid'=>$empid, 'sal_month'=>$month,'sal_year'=>$year);
                $existf=$this->sismodel->isduplicatemore('salary', $sdupdata);
                if($existf){
			$srawarray=$this->sismodel->get_orderlistspficemore('salary','sal_id,sal_totalincome,sal_totaldeduction,sal_netsalary',$sdupdata,'sal_id desc');
			if(!empty($srawarray)){
				//if yes then get old value
				$entryid=$srawarray[0]->sal_id;
				$prviamt=$srawarray[0]->sal_totalincome;
				$prvdamt=$srawarray[0]->sal_totaldeduction;
				$prvnamt=$srawarray[0]->sal_totalsalary;
				
				//update record
				$supdata=array('sal_totalincome'=>$totalincome,'sal_totaldeduction'=>$totaldeduction,'sal_netsalary'=>$netpay);
				$supflag=$this->sismodel->updaterec('salary', $supdata, 'sal_id', $entryid);	
				//insert into archive
				$sala = array(
			               'sala_salid'             =>$entryid,
			               'sala_empid'             =>$empid,
			               'sala_scid'              =>$scid,
			               'sala_uoid'              =>$uoccid,
			               'sala_deptid'            =>$deptid,
			               'sala_desigid'           =>$desigid,
			               'sala_sapost'            =>$sopost,
			               'sala_ddoid'             =>$ddoid,
			               'sala_schemeid'          =>$schmid,
			               'sala_payscaleid'        =>$payscaleid,
			               'sala_bankaccno'         =>$bankaccno,
			               'sala_worktype'          =>$wtype,
			               'sala_emptype'           =>$emptype,
			               'sala_group'             =>$group,
			               'sala_month'             =>$month,
			               'sala_year'              =>$year,
			               'sala_totalincome'       =>$prviamt,
			               'sala_totaldeduction'    =>$prvdamt,
			               'sala_netsalary'         =>$prvnamt,
			               'sala_status'            =>'archive paid',
			               'sala_paiddate'          =>date('y-m-d'),
			               'sala_creatorid'         =>$this->session->userdata('username'),
			               'sala_creationdate'       =>date('y-m-d'),
                	        );
                        	$saldataarflag = $this->sismodel->insertrec('salary_archive', $sala);
			}
		}else{      
			//if no insert salary  
        	        $upsalaryflag = $this->sismodel->insertrec('salary', $saldata1);
		}
                $this->logger->write_logmessage("insert"," salary data head wise value  ", " salary data head wise value added  successfully...");
                $this->logger->write_dblogmessage("insert"," salary data head wise value ", "salary data head wise value added  successfully...");
                $this->session->set_flashdata("success", "   salary data head wise value updated successfully... PF NO [ " .$this->emppfno. " ]");
               
                /*************************sending mail with Attachment Salaryslip********************************************/
                
                $uempid=$this->uri->segment(3);
                $umonth=$this->uri->segment(4);
                $uyear=$this->uri->segment(5);
                $ucase=$this->uri->segment(6);
                
                $this->mailmodel->mailAttachment($uempid,$this->emppfno,$umonth,$uyear,$ucase);
		$this->session->set_flashdata('fldeptid', $deptid);
                redirect("setup3redesign/salaryprocess",$data);
            }
            
        }//for button
        $this->load->view('setup3/salaryslipredesign',$data);
        
    }
    /********************* closer  Salary Slip  form *********************************************************/
    
    /*********************   CCA Places Grade *********************************************************/
    public function cca_placesgrade(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['ccarecord'] =$this->sismodel->get_list('cca_grade_city');
        $this->logger->write_logmessage("view"," view cca place grade list" );
        $this->logger->write_dblogmessage("view"," view cca place grade list");
        $this->load->view('setup3/cca_placegradelist',$data);
        
    }
    /*********************  closer HRA Places Grade *********************************************************/ 
    /*********************   HRA Places Grade *********************************************************/
    public function hra_placesgrade(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $data['hrarecord'] =$this->sismodel->get_list('hra_grade_city');
        $this->logger->write_logmessage("view"," view hra place grade list" );
        $this->logger->write_dblogmessage("view"," view hra place grade list");
        $this->load->view('setup3/hra_placegradelist',$data);
        
    }
    /*********************  closer HRA Places Grade *********************************************************/ 
    
    /*********************   HRA Grade List*********************************************************/
    public function hra_grade(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$selectfield='hg_id,hg_paycomm,hg_payrange,hg_gradeid,hg_amount';
        $data['hragrade'] =$this->sismodel->get_orderlistspficemore('hra_grade',$selectfield, '','');
        $this->logger->write_logmessage("view"," view hra grade list" );
        $this->logger->write_dblogmessage("view"," view hra grade list");
        $this->load->view('setup3/hragradelist',$data);
        
    }
    /*********************  closer HRA  Grade *********************************************************/ 
    /*********************  ADD HRA Grade *********************************************************/
    public function add_hragrade(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
//        $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        if(isset($_POST['add_hragrade'])) {
            //form validation
            
//            $this->form_validation->set_rules('worktype','Working Type','trim|required|xss_clean');
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|xss_clean');
        //    $this->form_validation->set_rules('payscale','Pay Scale','trim|required|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|xss_clean');
            $this->form_validation->set_rules('hragrade','HRA Grade','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','Amount','trim|required|xss_clean|numeric');
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/add_hragrade');
                return;
            }//formvalidation
            else{
                $data = array(
  //                  'hg_workingtype'    =>$_POST['worktype'],
                    'hg_paycomm'     =>$_POST['paycomm'],
                    'hg_payscaleid'     =>'',
                    'hg_payrange'     =>$_POST['payrange'],
                    'hg_gradeid'        =>$_POST['hragrade'],
                    'hg_amount'         =>$_POST['amount'],
                    'hg_creatorid'      =>$this->session->userdata('username'),
                    'hg_creatordate'    =>date('Y-m-d'),
                    'hg_modifierid'     =>$this->session->userdata('username'),
                    'hg_modifydate'     =>date('Y-m-d'),
                );
                
                $dupcheck = array(
    //                'hg_workingtype'    =>$_POST['worktype'],
//                    'hg_payscaleid'     =>$_POST['payscale'],
			'hg_paycomm'     =>$_POST['paycomm'],
			'hg_payrange'     =>$_POST['payrange'],
                    'hg_gradeid'        =>$_POST['hragrade'],
               //     'hg_amount'         =>$_POST['amount'],
                ); 
  //              $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$_POST['payscale'])->sgm_name;
    //            $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$_POST['payscale'])->sgm_min;
      //          $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$_POST['payscale'])->sgm_max;
        //        $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$_POST['payscale'])->sgm_gradepay;
          //      $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                
                $hragradedup = $this->sismodel->isduplicatemore('hra_grade', $dupcheck);
                if($hragradedup == 1 ){
                    
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ Working Type =".$_POST['worktype']
                             ." HRA Grade = ".$hragradename ." Amount = ".$_POST['amount'].' ]');
                    $this->load->view('setup3/add_hragrade');
                    return;
                }
                else{
                    $hragradeflag=$this->sismodel->insertrec('hra_grade', $data);
                    if(!$hragradeflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add HRA Grade ", " HRA Grade is not added  HRA Grade= ".$hragradename );
                        $this->logger->write_dblogmessage("insert","Trying to add HRA Grade ", " HRA Grade is not added HRA Grade= ".$hragradename );
                        $this->session->set_flashdata('err_message','Error in adding HRA Grade - '  , 'error');
                        redirect('setup3/add_hragrade');
                    }
                    else{
                        $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                        $this->logger->write_logmessage("insert","Add HRA Grade ", " HRA Grade = ".$hragradename." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add  HRA Grade ", " HRA Grade = ".$hragradename."added  successfully...");
                        $this->session->set_flashdata("success", "[ Working Type =".$_POST['worktype'] ." HRA Grade = ".$hragradename
                                 ." Amount = ".$_POST['amount'].']'." record insert successfully...");
                        redirect("setup3/hra_grade");
                    }
                    
                }//else dupcheck
                
            }//else
        }//isset button    
        $this->load->view('setup3/add_hragrade');
        
    }
    /*********************  closer Add HRA  Grade *********************************************************/ 
    /********************* Add Employee type form  *******************************************/
    public function edit_hragrade($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
  //      $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        $data['id'] = $id;
        $data['hragradedata'] = $this->sismodel->get_listrow('hra_grade','hg_id',$id)->row();
        if(isset($_POST['edithragrade'])) {
            
            //form validation
      //      $this->form_validation->set_rules('worktype','Working Type','trim|required|xss_clean');
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|xss_clean');
//            $this->form_validation->set_rules('payscale','Pay Scale','trim|required|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|xss_clean');
            $this->form_validation->set_rules('hragrade','HRA GRADE','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','Amount','trim|required|xss_clean|numeric');
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/edit_hragrade',$data);
                return;
            }//formvalidation
            else{
        //        $wtype = $this->input->post('worktype', TRUE);
                $paycomm = $this->input->post('paycomm', TRUE);
  //              $payscale = $this->input->post('payscale', TRUE);
                $payrange = $this->input->post('payrange', TRUE);
                $grade = $this->input->post('hragrade', TRUE);
                $amount = $this->input->post('amount', TRUE);
                
    //            $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$payscale)->sgm_name;
      //          $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$payscale)->sgm_min;
        //        $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$payscale)->sgm_max;
          //      $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$payscale)->sgm_gradepay;
            //    $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id', $grade)->hgc_gradename;
                
                $logmessage = "";
          //      if($data['hragradedata']->hg_workingtype != $wtype)
            //        $logmessage = "Edit HRA Grade Data " .$data['hragradedata']->hg_workingtype. " changed by " .$wtype;
              //  if($data['hragradedata']->hg_payscaleid != $payscale)
                //    $logmessage = "Edit HRA Grade Data " .$data['hragradedata']->hg_payscaleid. " changed by " .$payscale;
                if($data['hragradedata']->hg_gradeid != $grade)
                    $logmessage = "Edit HRA Grade Data " .$data['hragradedata']->hg_gradeid . " changed by " .$grade;
                if($data['hragradedata']->hg_amount != $amount)
                    $logmessage = "Edit HRA Grade Data " .$data['hragradedata']->hg_amount . " changed by " .$amount;
                
                $updata = array(
              //      'hg_workingtype'    =>$wtype,
                    'hg_paycomm'     =>$paycomm,
                  //  'hg_payscaleid'     =>$payscale,
                    'hg_payrange'     =>$payrange,
                    'hg_gradeid'        =>$grade,
                    'hg_amount'         =>$amount,
                    'hg_modifierid'     =>$this->session->userdata('username'),
                    'hg_modifydate'     =>date('Y-m-d'),
                );
                $dupcheck = array(
			'hg_paycomm'     =>$paycomm,
			 'hg_payrange'     =>$payrange,
                    'hg_gradeid'        =>$grade,
                ); 
                $hragradedup = $this->sismodel->isduplicatemore('hra_grade', $dupcheck);
                if($hragradedup == 1 ){
        
			$updata1=array(
                                'hg_amount'         =>$amount,
                                'hg_modifierid'     =>$this->session->userdata('username'),
                                'hg_modifydate'     =>date('Y-m-d'),
                        );
                    $editetflag1=$this->sismodel->updaterec('hra_grade', $updata1, 'hg_id', $id);
                        $this->logger->write_logmessage("update","Edit hra grade by  ".$this->session->userdata('username') , "Edit  hra grade details. )$logmessage ");
                        $this->logger->write_dblogmessage("update","Edit hra grade  by  ".$this->session->userdata('username') , "Edit hra grade details. $logmessage ");
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ Pay comm = ".$paycomm
                            ." HRA Grade = ".$hragradename ." Pay Range = ".$payrange.' ]. So only amount is updated. ');
                    redirect('setup3/hra_grade');
                    return;
                }
                else{
                    $editetflag=$this->sismodel->updaterec('hra_grade', $updata, 'hg_id', $id);
                    if(!$editetflag){
                        $this->logger->write_logmessage("error","error in Edit HRA Grade ", "Edit HRA Grade details. $logmessage ");
                        $this->logger->write_dblogmessage("error","error in Edit HRA Grade", "Edit HRA Grade . $logmessage ");
                        $this->session->set_flashdata('err_message','Error in updating HRA Grade - ' . $logmessage . '.', 'error');
                        $this->load->view('setup3/edit_hragrade', $data);
                    
                    }
                    else{
                        $this->logger->write_logmessage("update","Edit  HRA Grade by  ".$this->session->userdata('username') , "Edit  HRA Grade details. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit HRA Grade by  ".$this->session->userdata('username') , "Edit HRA Grade details. $logmessage ");
                        $this->session->set_flashdata('success','Record updated successfully.'.'[ HRA Grade = '.$hragradename." Pay Commission =".$paycomm ." Amount = ".$amount.']');
                        redirect('setup3/hra_grade');
                    }
                } //else dupcheck              
            }//else formvalidation
            
        }//if issetbutton press
        $this->load->view('setup3/edit_hragrade',$data);
    }//function
    
    /**This function Delete records */
    public function delete_hragrade($id) {
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->roleid=$this->session->userdata('id_role');
       // $this->hgid=$id;
        /* Deleting academicprofile Record */
        $delflag=$this->sismodel->deleterow('hra_grade','hg_id',$id);
        if (! delflag   )
        {   
            $this->logger->write_logmessage("delete", "Error in deleting HRA Grade record" . " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Error in deleting HRA Grade record" . " [Entry id:" . $id . "]");
            $this->session->set_flashdata('Error in deleting deleting HRA Grade record - ');
            redirect('setup3/hra_grade');
        }
        else{
         
            $this->logger->write_logmessage("delete", " Deleted HRA Grade Record  ". " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Deleted HRA Grade Record  " . " [Entry id:" . $id . "]");
            $this->session->set_flashdata("success", 'Record  Deleted successfully ...' );
            redirect('setup3/hra_grade');
        }
        $this->load->view('setup3/hragradelist');
        
    }//closer

	public function rentfreehra(){
		$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        	$selectfield='rfh_id,rfh_paycomm,rfh_payrange,rfh_gradeid,rfh_amount';
	        $data['rfhra'] =$this->sismodel->get_orderlistspficemore('rent_free_hra',$selectfield, '','');
        	$this->logger->write_logmessage("view"," view rent free hra grade list" );
	        $this->logger->write_dblogmessage("view"," view rent free hra grade list");
        	$this->load->view('setup3/rentfreehralist',$data);

    	}
    /*********************  ADD HRA Grade *********************************************************/
    public function add_rfhragrade(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        if(isset($_POST['add_rfhragrade'])) {
            //form validation
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|xss_clean');
            $this->form_validation->set_rules('hragrade','HRA Grade','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','Amount','trim|required|xss_clean|numeric');
            if($this->form_validation->run() == FALSE){
                $this->load->view('setup3/add_rfhragrade');
                return;
            }//formvalidation
            else{
                $data = array(
                    'rfh_paycomm'     =>$_POST['paycomm'],
                    'rfh_payrange'     =>$_POST['payrange'],
                    'rfh_gradeid'        =>$_POST['hragrade'],
                    'rfh_amount'         =>$_POST['amount'],
                    'rfh_creator'      =>$this->session->userdata('username'),
                    'rfh_createdate'    =>date('Y-m-d'),
                    'rfh_modifier'     =>$this->session->userdata('username'),
                );
                
                $dupcheck = array(
			'rfh_paycomm'     =>$_POST['paycomm'],
			'rfh_payrange'     =>$_POST['payrange'],
                    	'rfh_gradeid'        =>$_POST['hragrade'],
                ); 
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                $hragradedup = $this->sismodel->isduplicatemore('rent_free_hra', $dupcheck);
                if($hragradedup == 1 ){
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ HRA Grade = ".$hragradename ." Amount = ".$_POST['amount'].' ]');
                    $this->load->view('setup3/add_rfhragrade');
                    return;
                }
                else{
                    $hragradeflag=$this->sismodel->insertrec('rent_free_hra', $data);
                    if(!$hragradeflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add rent free HRA Grade ", " rent free HRA Grade is not added  HRA Grade= ".$hragradename );
                        $this->logger->write_dblogmessage("insert","Trying to add rent free HRA Grade ", "Rent free  HRA Grade is not added HRA Grade= ".$hragradename );
                        $this->session->set_flashdata('err_message','Error in addingrent free  HRA Grade - '  , 'error');
                        redirect('setup3/add_rfhragrade');
                    }
                    else{
                        $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                        $this->logger->write_logmessage("insert","Add rent free HRA Grade ", " HRA Grade = ".$hragradename." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add rent free HRA Grade ", " HRA Grade = ".$hragradename."added  successfully...");
                        $this->session->set_flashdata("success", " HRA Grade = ".$hragradename ." Amount = ".$_POST['amount'].']'." record insert successfully...");
                        redirect("setup3/rentfreehra");
                    }
                }//else dupcheck
            }//else
        }//isset button    
        $this->load->view('setup3/add_rfhragrade');
        
    }
    /*********************  closer Add HRA  Grade *********************************************************/ 
    /********************* Add Employee type form  *******************************************/
    public function edit_rfhragrade($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        $data['id'] = $id;
        $data['rfhragradedata'] = $this->sismodel->get_listrow('rent_free_hra','rfh_id',$id)->row();
        if(isset($_POST['editrfhragrade'])) {
            //form validation
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|xss_clean');
            $this->form_validation->set_rules('hragrade','HRA GRADE','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','Amount','trim|required|xss_clean|numeric');
            if($this->form_validation->run() == FALSE){
                $this->load->view('setup3/edit_rfhragrade',$data);
                return;
            }//formvalidation
            else{
                $paycomm = $this->input->post('paycomm', TRUE);
                $payrange = $this->input->post('payrange', TRUE);
                $grade = $this->input->post('hragrade', TRUE);
                $amount = $this->input->post('amount', TRUE);
                
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id', $grade)->hgc_gradename;
                $logmessage = "";
                if($data['rfhragradedata']->rfh_paycomm != $paycomm)
                    $logmessage = "Edit Rent Free HRA Grade Data " .$data['rfhragradedata']->rfh_paycomm. " changed by " .$paycomm;
                if($data['rfhragradedata']->rfh_payrange != $payrange)
                    $logmessage = "Edit Rent Free HRA Grade Data " .$data['rfhragradedata']->rfh_payrange. " changed by " .$payrange;
                if($data['rfhragradedata']->rfh_gradeid != $grade)
                    $logmessage = "Edit Rent Free HRA Grade Data " .$data['rfhragradedata']->rfh_gradeid . " changed by " .$grade;
                if($data['rfhragradedata']->rfh_amount != $amount)
                    $logmessage = "Edit Rent Free HRA Grade Data " .$data['rfhragradedata']->rfh_amount . " changed by " .$amount;
                
                $updata = array(
                    'rfh_paycomm'     =>$paycomm,
                    'rfh_payrange'     =>$payrange,
                    'rfh_gradeid'        =>$grade,
                    'rfh_amount'         =>$amount,
                    'rfh_modifier'     =>$this->session->userdata('username'),
                );
                $dupcheck = array(
			'rfh_paycomm'     =>$paycomm,
			'rfh_payrange'     =>$payrange,
                    	'rfh_gradeid'        =>$grade,
                ); 
                $hragradedup = $this->sismodel->isduplicatemore('rent_free_hra', $dupcheck);
                if($hragradedup == 1 ){
			$updata1=array(
                                'rfh_amount'         =>$amount,
                                'rfh_modifier'     =>$this->session->userdata('username'),
                        );
                    $editetflag1=$this->sismodel->updaterec('rent_free_hra', $updata1, 'rfh_id', $id);
                        $this->logger->write_logmessage("update","Edit rent free hra grade by  ".$this->session->userdata('username') , "Edit  rent free hra grade details. )$logmessage ");
                        $this->logger->write_dblogmessage("update","Edit rent free hra grade  by  ".$this->session->userdata('username') , "Edit rent free  hra grade details. $logmessage ");
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ Pay comm = ".$paycomm
                            ." HRA Grade = ".$hragradename ." Pay Range = ".$payrange.' ]. So only amount is updated. ');
                    redirect('setup3/rentfreehra');
                    return;
                }
                else{
                    $editetflag=$this->sismodel->updaterec('rent_free_hra', $updata, 'rfh_id', $id);
                    if(!$editetflag){
                        $this->logger->write_logmessage("error","error in Edit rent free HRA Grade ", "Edit rent free HRA Grade details. $logmessage ");
                        $this->logger->write_dblogmessage("error","error in Edit rent free HRA Grade", "Edit rent free  HRA Grade . $logmessage ");
                        $this->session->set_flashdata('err_message','Error in updating rent free HRA Grade - ' . $logmessage . '.', 'error');
                        $this->load->view('setup3/edit_rfhragrade', $data);
                    }
                    else{
                        $this->logger->write_logmessage("update","Edit rent free  HRA Grade by  ".$this->session->userdata('username') , "Edit  rent free HRA Grade details. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit rent free HRA Grade by  ".$this->session->userdata('username') , "Edit rent free HRA Grade details. $logmessage ");
                        $this->session->set_flashdata('success','Record updated successfully.'.'[ HRA Grade = '.$hragradename." Pay Commission =".$paycomm ." Amount = ".$amount.']');
                        redirect('setup3/rentfreehra');
                    }
                } //else dupcheck              
            }//else formvalidation
        }//if issetbutton press
        $this->load->view('setup3/edit_rfhragrade',$data);
    }//function
    
    /**This function Delete records */
    public function delete_rfhragrade($id) {
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->roleid=$this->session->userdata('id_role');
        /* Deleting rent free hra Record */
        $delflag=$this->sismodel->deleterow('rent_free_hra','rfh_id',$id);
        if (! delflag   )
        {   
            $this->logger->write_logmessage("delete", "Error in deleting rent free HRA Grade record" . " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Error in deleting rent free HRA Grade record" . " [Entry id:" . $id . "]");
            $this->session->set_flashdata('Error in deleting deleting rent free  HRA Grade record - ');
            redirect('setup3/rentfreehra');
        }
        else{
         
            $this->logger->write_logmessage("delete", " Deleted rent free HRA Grade Record  ". " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Deleted rent free HRA Grade Record  " . " [Entry id:" . $id . "]");
            $this->session->set_flashdata("success", 'Rent free HRA Record  Deleted successfully ...' );
            redirect('setup3/rentfreehra');
        }
        $this->load->view('setup3/rentfreehralist');
        
    }//closer


    /*********************   Rent Recovery for government quarters List*********************************************************/
    public function rentrecovery(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$selectfield='rr_id,rr_paycomm,rr_payrange,rr_gradeid,rr_percentage';
        $data['rentrecovery'] =$this->sismodel->get_orderlistspficemore('rent_recovery',$selectfield, '','');
   //     $data['rentrecovery'] =$this->sismodel->get_list('rent_recovery');
        $this->logger->write_logmessage("view"," view Rent Recovery for government quarter  list" );
        $this->logger->write_dblogmessage("view"," view  Rent Recovery for government quarter list");
        $this->load->view('setup3/rentrecoverylist',$data);
        
    }
    /*********************  closer Rent Recovery for government quarters List *********************************************************/ 
    
    /*********************  ADD Rent Recovery for government quarters *********************************************************/
    public function add_rentrecovery(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
//        $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        if(isset($_POST['add_rentrecovery'])) {
            //form validation
            
  //          $this->form_validation->set_rules('worktype','Working Type','trim|required|xss_clean');
    //        $this->form_validation->set_rules('payscale','Pay Scale','trim|required|xss_clean');
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|required|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|required|xss_clean');
            $this->form_validation->set_rules('hragrade','Rent Grade','trim|required|xss_clean');
            $this->form_validation->set_rules('percentage','Rent Recovery Percentage','trim|required|xss_clean|numeric');
      //      $this->form_validation->set_rules('Description','Description','trim|required|xss_clean');
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/add_rentrecovery');
                return;
            }//formvalidation
            else{
                $data = array(
//                    'rr_payscaleid'     =>$_POST['payscale'],
                    'rr_gradeid'        =>$_POST['hragrade'],
                    'rr_paycomm'        =>$_POST['paycomm'],
                    'rr_payrange'        =>$_POST['payrange'],
  //                  'rr_workingtype'    =>$_POST['worktype'],
                    'rr_percentage'     =>$_POST['percentage'],
                    'rr_description'     =>$_POST['Description'],
                    'rr_creatorid'      =>$this->session->userdata('username'),
                    'rr_creatordate'    =>date('Y-m-d'),
                    'rr_modifierid'     =>$this->session->userdata('username'),
                    'rr_modifydate'     =>date('Y-m-d'),
                );
                
                $dupcheck = array(
     //               'rr_payscaleid'     =>$_POST['payscale'],
                    'rr_gradeid'        =>$_POST['hragrade'],
                    'rr_paycomm'        =>$_POST['paycomm'],
                    'rr_payrange'        =>$_POST['payrange'],
   //                 'rr_workingtype'    =>$_POST['worktype'],
       //             'rr_percentage'     =>$_POST['percentage'],
                ); 
//                $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$_POST['payscale'])->sgm_name;
  //              $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$_POST['payscale'])->sgm_min;
    //            $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$_POST['payscale'])->sgm_max;
      //          $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$_POST['payscale'])->sgm_gradepay;
        //        $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                
                $hragradedup = $this->sismodel->isduplicatemore('rent_recovery', $dupcheck);
                if($hragradedup == 1 ){
                    
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [  Pay Range = ".$_POST['payrange'] ." HRA Grade = ".$hragradename ." Percentage = ".$_POST['percentage'].' ]');
                    $this->load->view('setup3/add_rentrecovery');
                    return;
                }
                else{
                    $hragradeflag=$this->sismodel->insertrec('rent_recovery', $data);
                    if(!$hragradeflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add Rent Recovery Percentage ", " Rent Recovery Percentage is not added  HRA Grade= ".$hragradename );
                        $this->logger->write_dblogmessage("insert","Trying to add Rent Recovery Percentage ", " Rent Recovery Percentage is not added HRA Grade= ".$hragradename);
                        $this->session->set_flashdata('err_message','Error in adding Rent Recovery Percentage - '  , 'error');
                        redirect('setup3/add_rentrecovery');
                    }
                    else{
                        $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                        $this->logger->write_logmessage("insert","Add Rent Recovery Percentage ", " Rent Recovery Percentage for HRA Grade = ".$hragradename." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add  Rent Recovery Percentage ", " Rent Recovery Percentage for HRA Grade = ".$hragradename."added  successfully...");
                        $this->session->set_flashdata("success", "[  HRA Grade = ".$hragradename." Percentage = ".$_POST['percentage'].']'." record insert successfully...");
                        redirect("setup3/rentrecovery");
                    }
                }//else dupcheck
            }//else
        }//isset button    
        $this->load->view('setup3/add_rentrecovery');
    }
    /*********************  closer Rent Recovery for government quarters *********************************************************/ 
    
    /********************* Edit Rent Recovery for government quarters form  *******************************************/
    public function edit_rentrecovery($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
    //    $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->hragrade= $this->sismodel->get_listspfic2('hra_grade_city','hgc_id','hgc_gradename');
        $data['id'] = $id;
        $data['rrdata'] = $this->sismodel->get_listrow('rent_recovery','rr_id',$id)->row();
        if(isset($_POST['edit_rentrecovery'])) {
            
            //form validation
  //          $this->form_validation->set_rules('worktype','Working Type','trim|required|xss_clean');
//            $this->form_validation->set_rules('payscale','Pay Scale','trim|required|xss_clean');
	$this->form_validation->set_rules('paycomm','Pay Commission','trim|required|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|required|xss_clean');
            $this->form_validation->set_rules('hragrade','HRA GRADE','trim|required|xss_clean');
            $this->form_validation->set_rules('percentage','Rent Recovery Percentage','trim|required|xss_clean|numeric');
//            $this->form_validation->set_rules('Description','Description','trim|required|xss_clean');
            
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/edit_rentrecovery',$data);
                return;
            }//formvalidation
            else{
    //            $wtype = $this->input->post('worktype', TRUE);
      //          $payscale = $this->input->post('payscale', TRUE);
                $paycomm = $this->input->post('paycomm', TRUE);
                $payrange = $this->input->post('payrange', TRUE);
                $grade = $this->input->post('hragrade', TRUE);
                $pert = $this->input->post('percentage', TRUE);
  //              $description = $this->input->post('Description', TRUE);
                
//                $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$payscale)->sgm_name;
  //              $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$payscale)->sgm_min;
    //            $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$payscale)->sgm_max;
      //          $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$payscale)->sgm_gradepay;
        //        $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
                $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id', $grade)->hgc_gradename;
                
                $logmessage = "";
                if($data['rrdata']->rr_paycomm != $paycomm)
                    $logmessage = "Edit Rent Recovery Data " .$data['rrdata']->rr_paycomm. " changed by " .$paycomm;
                if($data['rrdata']->rr_payrange != $payrange)
                    $logmessage = "Edit Rent Recovery Data " .$data['rrdata']->rr_payrange. " changed by " .$payrange;
                if($data['rrdata']->rr_gradeid != $grade)
                    $logmessage = "Edit Rent Recovery Data " .$data['rrdata']->rr_gradeid . " changed by " .$grade;
                if($data['rrdata']->rr_percentage != $pert)
                    $logmessage = "Edit Rent Recovery Data " .$data['rrdata']->rr_percentage . " changed by " .$pert;
                
//                if($data['rrdata']->rr_description != $description)
  //                  $logmessage = "Edit Rent Recovery Data " .$data['rrdata']->rr_description . " changed by " .$description;
                
                $updata = array(
                    'rr_payrange'     =>$payrange,
                    'rr_gradeid'        =>$grade,
                    'rr_paycomm'    =>$paycomm,
                    'rr_percentage'     =>$pert,
    //                'rr_description'    =>$description,
                    'rr_modifierid'     =>$this->session->userdata('username'),
                    'rr_modifydate'     =>date('Y-m-d'),
                );
                $dupcheck = array(
                    'rr_paycomm'     =>$paycomm,
                    'rr_gradeid'        =>$grade,
                    'rr_payrange'    =>$payrange,
//                    'rr_percentage'     =>$pert,
                ); 
                $hragradedup = $this->sismodel->isduplicatemore('rent_recovery', $dupcheck);
                if($hragradedup == 1 ){
           		$updata1=array(
                                'rr_percentage'     =>$pert,
                    		'rr_modifierid'     =>$this->session->userdata('username'),
	                    	'rr_modifydate'     =>date('Y-m-d'),
                        );
                    $editetflag1=$this->sismodel->updaterec('rent_recovery', $updata1, 'rr_id', $id);
			$this->logger->write_logmessage("update","Edit Rent Recovery Percentage by  ".$this->session->userdata('username') , "Edit  Rent Recovery Percentage details. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit Rent Recovery Percentage by  ".$this->session->userdata('username') , "Edit Rent Recovery Percentage details. $logmessage ");
         
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ Pay commission = ".$paycomm 
                            ." Payrange = ".$payrange ." HRA Grade = ".$hragradename ." ]. So Only percentage is updated");
                    redirect('setup3/rentrecovery');
                    return;
                }
                else{
                    $editetflag=$this->sismodel->updaterec('rent_recovery', $updata, 'rr_id', $id);
                    if(!$editetflag){
                        $this->logger->write_logmessage("error","error in Edit Rent Recovery Percentage ", "Edit Rent Recovery Percentage details. $logmessage ");
                        $this->logger->write_dblogmessage("error","error in Edit Rent Recovery Percentage", "Edit Rent Recovery Percentage . $logmessage ");
                        $this->session->set_flashdata('err_message','Error in updating Rent Recovery Percentage - ' . $logmessage . '.', 'error');
                        $this->load->view('setup3/edit_rentrecovery', $data);
                    
                    }
                    else{
                        $this->logger->write_logmessage("update","Edit Rent Recovery Percentage by  ".$this->session->userdata('username') , "Edit  Rent Recovery Percentage details. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit Rent Recovery Percentage by  ".$this->session->userdata('username') , "Edit Rent Recovery Percentage details. $logmessage ");
                        $this->session->set_flashdata('success','Record updated successfully.'.'[ HRA Grade = '.$hragradename." Pay commission =".$paycomm. 
                            " Payrange = ".$payrange."  Percentage = ".$pert.']');
                        redirect('setup3/rentrecovery');
                    
                    }
                } //else dupcheck              
            }//else formvalidation
            
        }//if issetbutton press
        $this->load->view('setup3/edit_rentrecovery',$data);
    }//function
    /*******************************************Edit Rent Recovery for government quarters form ***********************/
    
    /**This function Delete  Rent Recovery records */
    public function delete_rentrecovery($id) {
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->roleid=$this->session->userdata('id_role');
       // $this->hgid=$id;
        /* Deleting academicprofile Record */
        $delflag=$this->sismodel->deleterow('rent_recovery','rr_id',$id);
        if (! delflag   )
        {   
            $this->logger->write_logmessage("delete", "Error in deleting Rent Recovery Percentage record" . " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Error in deleting Rent Recovery Percentage record" . " [Entry id:" . $id . "]");
            $this->session->set_flashdata('Error in deleting deleting Rent Recovery Percentage record - ');
            redirect('setup3/rentrecovery');
        }
        else{
         
            $this->logger->write_logmessage("delete", " Deleted Rent Recovery Percentage  ". " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Deleted Rent Recovery Percentage Record  " . " [Entry id:" . $id . "]");
            $this->session->set_flashdata("success", 'Record  Deleted successfully ...' );
            redirect('setup3/rentrecovery');
        }
        $this->load->view('setup3/rentrecovery');
        
    }//closer
    /********************* CLOSER  Rent Recovery for government quarters List*********************************************************/
        
    /*********************   Rent City Compensatory Allowance(CCA) List*********************************************************/
    public function cca_allowance(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$selectfield='cca_id,cca_paycomm,cca_payrange,cca_gradeid,cca_amount';
        $data['ccadata'] =$this->sismodel->get_orderlistspficemore('ccaallowance_calculation',$selectfield, '','');
        $this->logger->write_logmessage("view"," view CCA allowance data  list" );
        $this->logger->write_dblogmessage("view"," view view CCA allowance data list");
        $this->load->view('setup3/cca_allowancelist',$data);
        
    }
    /*********************  closer City Compensatory Allowance(CCA) List *********************************************************/ 
    
    /*********************  ADD City Compensatory Allowance(CCA) *********************************************************/
    public function add_ccaallowance(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
    //    $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $this->ccagrade= $this->sismodel->get_listspfic2('cca_grade_city','cgc_id','cgc_gradename');
        if(isset($_POST['add_ccaalowance'])) {
            //form validation
            
    //        $this->form_validation->set_rules('worktype','Working Type','trim|required|xss_clean');
      //      $this->form_validation->set_rules('payscale','Pay Scale','trim|required|xss_clean');
	    $this->form_validation->set_rules('paycomm','Pay Commission','trim|xss_clean');
            $this->form_validation->set_rules('payrange','CCA Pay Range','trim|xss_clean');
            $this->form_validation->set_rules('ccagrade','CCA Grade','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','CCA Amount','trim|required|xss_clean|numeric');
//            $this->form_validation->set_rules('Description','Description','trim|required|xss_clean');
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/add_ccaallowance');
                return;
            }//formvalidation
            else{
                $data = array(
			 'cca_paycomm'     =>$_POST['paycomm'],
        	         'cca_payscaleid'     =>'',
                    	'cca_payrange'     =>$_POST['payrange'],
      //              'cca_payscaleid'     =>$_POST['payscale'],
                    'cca_gradeid'        =>$_POST['ccagrade'],
    //                'cca_workingtype'    =>$_POST['worktype'],
                    'cca_amount'         =>$_POST['amount'],
  //                  'cca_description'     =>$_POST['Description'],
                    'cca_creatorid'      =>$this->session->userdata('username'),
                    'cca_creatordate'    =>date('Y-m-d'),
                    'cca_modifierid'     =>$this->session->userdata('username'),
                    'cca_modifydate'     =>date('Y-m-d'),
                );
                
                $dupcheck = array(
                    'cca_paycomm'     =>$_POST['paycomm'],
                    'cca_gradeid'        =>$_POST['ccagrade'],
                    'cca_payrange'    =>$_POST['payrange'],
                    
                ); 
//                $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$_POST['payscale'])->sgm_name;
  //              $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$_POST['payscale'])->sgm_min;
    //            $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$_POST['payscale'])->sgm_max;
      //          $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$_POST['payscale'])->sgm_gradepay;
        //        $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
          //      $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                
                $hragradedup = $this->sismodel->isduplicatemore('ccaallowance_calculation', $dupcheck);
                if($hragradedup == 1 ){
                    
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ pay commission =".$_POST['paycomm']
                            ." Payrange = ".$_POST['payrange'] ." CCA Grade = ".$_POST['hragrade'] .' ]');
                    $this->load->view('setup3/add_ccaallowance');
                    return;
                }
                else{
                    $hragradeflag=$this->sismodel->insertrec('ccaallowance_calculation', $data);
                    if(!$hragradeflag)
                    {
                        $this->logger->write_logmessage("insert","Trying to add City Compensatory Allowance(CCA) amount ", " City Compensatory Allowance(CCA) amount is not added  CCA Grade= ".$_POST['hragrade']." with payscale ".$_POST['payrange']);
                        $this->logger->write_dblogmessage("insert","Trying to add City Compensatory Allowance(CCA) amount ", " City Compensatory Allowance(CCA) amount is not added CCA Grade= ".$_POST['hragrade']." with payscale ".$_POST['payrange']);
                        $this->session->set_flashdata('err_message','Error in adding City Compensatory Allowance(CCA) amount - '  , 'error');
                        redirect('setup3/add_ccaallowance');
                    }
                    else{
                        $hragradename=$this->sismodel->get_listspfic1('hra_grade_city','hgc_gradename','hgc_id',$_POST['hragrade'])->hgc_gradename;
                        $this->logger->write_logmessage("insert","Add City Compensatory Allowance(CCA) amount ", " City Compensatory Allowance(CCA) amount for HRA Grade = ".$_POST['hragrade']." added  successfully...");
                        $this->logger->write_dblogmessage("insert","Add  City Compensatory Allowance(CCA) amount ", " City Compensatory Allowance(CCA) amount for HRA Grade = ".$_POST['hragrade']."added  successfully...");
                        $this->session->set_flashdata("success", "[Pay range =".$_POST['payrange'] ." CCA Grade = ".$_POST['hragrade']
                                 ." Amount = ".$_POST['amount'].']'." record insert successfully...");
                        redirect("setup3/cca_allowance");
                    }
                    
                }//else dupcheck
                
            }//else
        }//isset button    
        $this->load->view('setup3/add_ccaallowance');
        
    }
    /*********************  closer Add City Compensatory Allowance(CCA) *********************************************************/ 
    
    /********************* Edit City Compensatory Allowance(CCA) form  *******************************************/
    public function edit_ccaallowance($id){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
     //   $this->salgrade= $this->sismodel->get_list('salary_grade_master');
        $data['ccagrade']= $this->sismodel->get_listspfic2('cca_grade_city','cgc_id','cgc_gradename');
        $data['id'] = $id;
        $data['ccadata'] = $this->sismodel->get_listrow('ccaallowance_calculation','cca_id',$id)->row();
        if(isset($_POST['edit_cca'])) {
            
            //form validation
            $this->form_validation->set_rules('paycomm','Pay Commission','trim|required|xss_clean');
            $this->form_validation->set_rules('payrange','Pay Range','trim|required|xss_clean');
            $this->form_validation->set_rules('hragrade','CCA GRADE','trim|required|xss_clean');
            $this->form_validation->set_rules('amount','Amount','trim|required|xss_clean|numeric');
      //      $this->form_validation->set_rules('Description','Description','trim|required|xss_clean');
            
            if($this->form_validation->run() == FALSE){
             
                $this->load->view('setup3/edit_ccaallowance',$data);
                return;
            }//formvalidation
            else{
                $pc = $this->input->post('paycomm', TRUE);
                $payrange = $this->input->post('payrange', TRUE);
                $grade = $this->input->post('hragrade', TRUE);
                $amount = $this->input->post('amount', TRUE);
    //            $description = $this->input->post('Description', TRUE);
                
      //          $pname=$this->sismodel->get_listspfic1('salary_grade_master','sgm_name','sgm_id',$payscale)->sgm_name;
        //        $min=$this->sismodel->get_listspfic1('salary_grade_master','sgm_min','sgm_id',$payscale)->sgm_min;
          //      $max=$this->sismodel->get_listspfic1('salary_grade_master','sgm_max','sgm_id',$payscale)->sgm_max;
            //    $gp=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$payscale)->sgm_gradepay;
              //  $fullstr=$pname."( ".$min." - ".$max." )".$gp;
                
                $hragradename=$grade;
                
                $logmessage = "";
                if($data['ccadata']->cca_paycomm != $pc)
                    $logmessage = "Edit City Compensatory Allowance(CCA) Data " .$data['ccadata']->cca_paycomm. " changed by " .$pc;
                if($data['ccadata']->cca_payrange != $payrange)
                    $logmessage = "Edit City Compensatory Allowance(CCA) " .$data['ccadata']->cca_payrange. " changed by " .$payrange;
                if($data['ccadata']->cca_gradeid != $grade)
                    $logmessage = " Edit City Compensatory Allowance(CCA) Data " .$data['ccadata']->cca_gradeid . " changed by " .$grade;
                if($data['ccadata']->cca_amount != $amount)
                    $logmessage = "Edit City Compensatory Allowance(CCA) Data " .$data['ccadata']->cca_amount . " changed by " .$pert;
                
              //  if($data['ccadata']->cca_description != $description)
                  //  $logmessage = "Edit City Compensatory Allowance(CCA) Data " .$data['ccadata']->cca_description . " changed by " .$description;
                
                $updata = array(
                    'cca_paycomm'     =>$pc,
                    'cca_gradeid'        =>$grade,
                    'cca_payrange'    =>$payrange,
                    'cca_amount'         =>$amount,
                    'cca_modifierid'     =>$this->session->userdata('username'),
                    'cca_modifydate'     =>date('Y-m-d'),
                );
                $dupcheck = array(
                    'cca_payrange'     =>$payrange,
                    'cca_gradeid'        =>$grade,
                    'cca_paycomm'    =>$pc,
                ); 
                $hragradedup = $this->sismodel->isduplicatemore('ccaallowance_calculation', $dupcheck);
                if($hragradedup == 1 ){
			$updata1=array(
				'cca_amount'         =>$amount,
                    		'cca_modifierid'     =>$this->session->userdata('username'),
                    		'cca_modifydate'     =>date('Y-m-d'),
			);	
                    $editetflag1=$this->sismodel->updaterec('ccaallowance_calculation', $updata1, 'cca_id', $id);    
			$this->logger->write_logmessage("update","Edit City Compensatory Allowance(CCA) by  ".$this->session->userdata('username') , "Edit  City Compensatory Allowance(CCA) details. )$logmessage ");
                        $this->logger->write_dblogmessage("update","Edit City Compensatory Allowance(CCA) by  ".$this->session->userdata('username') , "Edit City Compensatory Allowance(CCA) details. $logmessage ");
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination [ Pay comm = ".$pc 
                            ." CCA Grade = ".$hragradename ." Pay Range = ".$payrange.' ]. So only amount is updated. ');
                    redirect('setup3/cca_allowance');
                    return;
                }
                else{
                    $editetflag=$this->sismodel->updaterec('ccaallowance_calculation', $updata, 'cca_id', $id);
                    if(!$editetflag){
                        $this->logger->write_logmessage("error","error in Edit City Compensatory Allowance(CCA) ", "Edit City Compensatory Allowance(CCA) details. $logmessage ");
                        $this->logger->write_dblogmessage("error","error in Edit City Compensatory Allowance(CCA)", "Edit City Compensatory Allowance(CCA) . $logmessage ");
                        $this->session->set_flashdata('err_message','Error in updating City Compensatory Allowance(CCA) - ' . $logmessage . '.', 'error');
                        $this->load->view('setup3/edit_ccaallowance', $data);
                    
                    }
                    else{
                        $this->logger->write_logmessage("update","Edit City Compensatory Allowance(CCA) by  ".$this->session->userdata('username') , "Edit  City Compensatory Allowance(CCA) details. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit City Compensatory Allowance(CCA) by  ".$this->session->userdata('username') , "Edit City Compensatory Allowance(CCA) details. $logmessage ");
                        $this->session->set_flashdata('success','Record updated successfully.'.'[ CCA Grade = '.$hragradename." Pay Comm =".$pc. 
                            " Payrange = ".$payrange."  Amount = ".$amount.']');
                        redirect('setup3/cca_allowance');
                    
                    }
                } //else dupcheck              
            }//else formvalidation
            
        }//if issetbutton press
        $this->load->view('setup3/edit_ccaallowance',$data);
    }//function
    /*********************** closer Edit City Compensatory Allowance(CCA) form ***********************/
    
    /**This function Delete  City Compensatory Allowance(CCA) records */
    public function delete_ccaallowance($id) {
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $this->roleid=$this->session->userdata('id_role');
       // $this->hgid=$id;
        /* Deleting academicprofile Record */
        $delflag=$this->sismodel->deleterow('ccaallowance_calculation','cca_id',$id);
        if (! delflag   )
        {   
            $this->logger->write_logmessage("delete", "Error in deleting City Compensatory Allowance(CCA) record" . " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Error in deleting City Compensatory Allowance(CCA) record" . " [Entry id:" . $id . "]");
            $this->session->set_flashdata('Error in deleting deleting City Compensatory Allowance(CCA) record - ');
            redirect('setup3/cca_allowance');
        }
        else{
         
            $this->logger->write_logmessage("delete", " Deleted City Compensatory Allowance(CCA) record ". " [Entry id:" . $id . "]");
            $this->logger->write_dblogmessage("delete", "Deleted City Compensatory Allowance(CCA) Record  " . " [Entry id:" . $id . "]");
            $this->session->set_flashdata("success", 'Record  Deleted successfully ...' );
            redirect('setup3/cca_allowance');
        }
        $this->load->view('setup3/cca_allowance');
        
    }//closer
    /********************* closer Delete City Compensatory Allowance(CCA)*********************************************************/
    
    
    
    /*********************  Salary Processing *******************************************/
    public function salaryprocess(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$this->session->set_flashdata($array_items);
	$deptnme='';
	$deptid=$this->session->flashdata('fldeptid');
//		echo $deptid; //die();
        $month = $this->input->post('month', TRUE);
        $year = $this->input->post('year', TRUE);
	
	if(empty($deptid)){
		$deptid=$this->input->post('dept', TRUE);
	}
        $cmonth= date('M');
        $cyear= date("Y"); 
       // echo "999==".$month."--------".$year;
        if($month==''){
            $month=$cmonth;
	}
	if($year==''){
            $year=$cyear;
        }
      	$datawh='';
	$whorder='';
	$lstatus='';

	$ssiondeptid=$this->session->userdata('id_dept');
	$sessionroleid=$this->session->userdata('id_role');
	$data['sroleid']= $sessionroleid;
	if($sessionroleid == 5){
		$ldatawh =array("sld_month" =>$month, "sld_year"=>$year,"sld_deptid"=>$ssiondeptid);
		$lockdata=$this->sismodel->get_orderlistspficemore('salary_lock_data','sld_status',$ldatawh,'sld_id desc');
//		print_r($lockdata); die();
		if(!empty($lockdata)){
			$lstatus=$lockdata[0]->sld_status;
		}else{
			$lstatus="N";
		}
		$data['lckstus']=$lstatus;
	}

	if(!empty($ssiondeptid)){
		$deptid = $ssiondeptid;
		$datawh['dept_id']=$deptid;
	}
        $data['combdata'] = $this->commodel->get_orderlistspficemore('Department','dept_id,dept_name,dept_code',$datawh,'dept_name asc'); 
        $data['selmonth']=$month;
        $data['selyear']=$year;
	$data['emplist']='';	
        if(isset($_POST['salpro'])){
        $data['etranlist']=array();
	$tlempid=array();
//	$tlempid='';
        $whtempid=array('ste_month'=>$month,'ste_year'=>$year);
	if(!empty($deptid)){
		$whtempid['ste_deptid']=$deptid;
	}
        $tempid=$this->sismodel->get_orderlistspficemore('salary_transfer_entry','ste_empid',$whtempid,'ste_empid asc');
        foreach($tempid as $row){
                $tlempid[]=$row->ste_empid;
        }

	$whlempid=array('sle_month'=>$month,'sle_year'=>$year);
	if(!empty($deptid)){
		$whlempid['sle_deptid']=$deptid;
	}
        $lempid=$this->sismodel->get_orderlistspficemore('salary_leave_entry','sle_empid',$whlempid,'sle_empid asc');
        foreach($lempid as $row){
                $tlempid[]=$row->sle_empid;
        }

        $selectfield ="emp_id,emp_code,emp_name,emp_scid,emp_uocid,emp_dept_code,emp_schemeid,emp_desig_code,emp_email,"
          . "emp_phone,emp_aadhaar_no,";
        $whdata = array ('employee_master.emp_leaving'=>NULL,'employee_master.emp_dor >='=> date('Y-m-d'));
	if(!empty($deptid)){
                $deptnme=$this->commodel->get_listspfic1('Department','dept_name','dept_id',$deptid)->dept_name;
                $whdata['employee_master.emp_dept_code']=$deptid;
        }
        $data['deptsel']=$deptnme;

        /**************************************employee normal case without transfer and leave************************************/
        //$orfield='employee_master.emp_dept_code ASC, employee_master.emp_name ASC';
        $orfield='emp_desig_code ASC, emp_name ASC';
        $spl='emp_id NOT IN' ;  //AND salary_transfer_entry.ste_month='.$month.' AND salary_transfer_entry.ste_year='.$year;
       // $data['emplist']=$this->sismodel->get_rundualquery('ste_empid','salary_transfer_entry',$selectfield,'employee_master',$spl,$whdata,$orfield);
        
        //$data['emplist']=$this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$orfield);
	$data['emplist']=$this->sismodel->get_orderlistspficemoreorwhnotin('employee_master',$selectfield,$whdata,'emp_id',$tlempid,$orfield);

        /**************************************employee both transfer and leave case************************************/
	$whtlempid=array('ste_month'=>$month,'ste_year'=>$year,'sle_month'=>$month,'sle_year'=>$year);
	$joincond2 = 'salary_transfer_entry.ste_empid = salary_leave_entry.sle_empid';
	$tlempid1=$this->sismodel->get_jointbrecord('salary_transfer_entry','ste_empid', 'salary_leave_entry',$joincond2, 'left',$whtlempid);
	$tlempid=array();
	foreach($tlempid1 as $row){
		$tlempid[]=$row->ste_empid;
	}
	//print_r($tlempid);
//if(($orwhin != '') && (!empty($orwhin)) && (count($orwhin) != 0))
//	$tlempid = array('ste_empid' =>'9');
	if((!empty($tlempid)) && (count($tlempid) != 0)){
		$data['tlemplist']=$this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_id',$tlempid,$orfield);
	}else{
		$data['tlemplist']='';
	}
        
        /**************************************employee transfer case************************************/
        $selectfield2 ="salary_transfer_entry.ste_empid,salary_transfer_entry.ste_year, salary_transfer_entry.ste_month,employee_master.emp_id,employee_master.emp_code,employee_master.emp_name,employee_master.emp_scid,"
          . "employee_master.emp_uocid,employee_master.emp_dept_code,employee_master.emp_schemeid,employee_master.emp_desig_code,employee_master.emp_email,"
          . "employee_master.emp_phone,employee_master.emp_aadhaar_no";
        $joincond = 'salary_transfer_entry.ste_empid = employee_master.emp_id';
        $whdata =array ('employee_master.emp_leaving'=>NULL,'employee_master.emp_dor >='=> date('Y-m-d'),'salary_transfer_entry.ste_month'=>$month,'salary_transfer_entry.ste_year'=>$year);
	if(!empty($deptid)){
                $whdata['employee_master.emp_dept_code']=$deptid;
        }
        $whorder ="emp_name asc,emp_dept_code asc,emp_desig_code asc";
        if(!empty($tlempid)){
        	$data['etranlist'] = $this->sismodel->get_jointbrecord1('salary_transfer_entry',$selectfield2,'employee_master',$joincond,'left',$whdata,'employee_master.emp_id',$tlempid);
	}else{
	        $data['etranlist'] = $this->sismodel->get_jointbrecord('salary_transfer_entry',$selectfield2,'employee_master',$joincond,'left',$whdata);
	}
      //  $data['etranlist1'] =$data['etranlist']->ste_empid;
        /***********************************employee  leave case*************************************************/  
        $selectfield3 ="salary_leave_entry.sle_empid,salary_leave_entry.sle_year, salary_leave_entry.sle_month,salary_leave_entry.sle_pal,salary_leave_entry.sle_eol,"
            ."employee_master.emp_id, employee_master.emp_code, employee_master.emp_name, employee_master.emp_scid,"
          . "employee_master.emp_uocid, employee_master.emp_dept_code, employee_master.emp_schemeid, employee_master.emp_desig_code, employee_master.emp_email,"
          . "employee_master.emp_phone, employee_master.emp_aadhaar_no";
        $joincond1 = 'salary_leave_entry.sle_empid = employee_master.emp_id';
        $whdata1 =array ('employee_master.emp_leaving'=>NULL,'employee_master.emp_dor >='=> date('Y-m-d'),'salary_leave_entry.sle_month'=>$month,'salary_leave_entry.sle_year'=>$year);
      //  $whorder1 ="emp_name asc,emp_dept_code asc,emp_desig_code asc";
        if(!empty($deptid)){
                $whdata1['employee_master.emp_dept_code']=$deptid;
        }
	if(!empty($tlempid)){
        	$data['empleavelist'] = $this->sismodel->get_jointbrecord1('salary_leave_entry',$selectfield3,'employee_master',$joincond1,'left',$whdata1,'employee_master.emp_id',$tlempid);
	}else{
        	$data['empleavelist'] = $this->sismodel->get_jointbrecord('salary_leave_entry',$selectfield3,'employee_master',$joincond1,'left',$whdata1);
	}
       // $data['empleavelist1']=$data['empleavelist']->sle_empid;
     //   if(isset($_POST['salpro'])){
            $data['selmonth']=$month;
            $data['selyear']=$year;
	    $data['deptsel']=$deptnme;
        /**    if(!empty($data['emplist'])){
                /**********************************income and deduction head *********************
                $selectfield ="sh_id, sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
                $whorder = " sh_name asc";
                $whdata = array('sh_type' =>'I');
                $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
                //$whdata = array('sh_type' =>'D');
                //$data['deduction'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
               // $data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L','');
                $whdata = array('sh_type' =>'D');
                $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
                $whdata = array('sh_type' =>'L');
                $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
                $data['deduction']=array_merge($data['ded'], $data['loans']);    
            }
      **/       
        } //form
        $this->load->view('setup3/empSalary',$data);
        
    }
    /*********************  closer Salary Processing  *******************************************/
    
  
    public function getDefaultheadval($empid,$shid){
        $fvalue=0;
        $shtype=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_id',$shid)->sh_type;
        if($shtype =='I'){
            $selectfield ="seh_headamount";
            $whdata = array('seh_empid' =>$empid,'seh_headid' =>$shid);
            //$headval= $this->sismodel->get_maxvalue('salary_earnings_head',$selectfield,$whdata); 
            $headval=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$selectfield,'seh_modifydate=',$whdata);
            if(!empty($headval)){           
            	$fvalue=$headval[0]->seh_headamount;
		}
        } 
        elseif($shtype == 'D'){
            $selectfield ="ssdh_headamount";
            $whdata = array('ssdh_empid' =>$empid,'ssdh_headid' =>$shid);
           // $headval= $this->sismodel->get_maxvalue('salary_subsdeduction_head',$selectfield,$whdata); 
            $headval=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$selectfield,'ssdh_modifydate=',$whdata);
            if(!empty($headval)){           
        	    $fvalue=$headval[0]->ssdh_headamount;
		}
        }
        else{
            $selectfield ="slh_installamount";
            $whdata = array('slh_empid' =>$empid,'slh_headid' =>$shid);
           // $headval= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata);
            $headval= $this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
            if(!empty($headval)){           
	            $fvalue=$headval[0]->slh_installamount; 
		}
        }    
        /**************$this->salgrd=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$emp_id)->emp_salary_grade;
        $selectfield='shdv_defaultvalue';
        $whdata=array ('shdv_paybandid' => $this->salgrd,'shdv_salheadid'=>$shid);
        $this->defval=$this->sismodel->get_orderlistspficemore('salaryhead_defaultvalue',$selectfield,$whdata,'');
        $fvalue=$this->defval[0]->shdv_defaultvalue;************/
      //  echo "999=====".$fvalue;
        //die;
        
        return $fvalue;
    }
    /*********************************************closer ******************************************************/
    
    /************get formula value **********************************************************************/
        
   // public function getformulaval($shid,$empid,$pbid,$wtype){
    public function getformulaval($shid,$empid){
        $formula1=$this->sismodel->get_listspfic1('salary_formula','sf_formula','sf_salhead_id',$shid);
	$emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
        if(!empty($formula1)){
            $formula=$formula1->sf_formula;
		if(is_numeric($formula)){
			$this->finalval=$formula;
		}else{
            preg_match('/(.*)\((.*?)\)(.*)/', $formula, $match);
            //echo "in parenthesis inside: " . $match[2];
            //echo "before and after inside: " . $match[1] . $match[3] . "\n";
            $strfmla=explode("+",$match[2]);
            $strfmla2=explode("*",$match[3]);
            $headval1=0;
	    $headval2=0; 
           // $sfield ="shdv_defaultvalue";
            if(!empty($strfmla[0])){
                $shtypetok1id=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_code',$strfmla[0])->sh_type;
                if($shtypetok1id == 'I' ){
                    $sfield ="seh_headamount";    
                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                    $wdata = array('seh_empid' =>$empid,'seh_headid' =>$tok1id);
                    //$headval1= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,'');
                    $headval= $this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
            	if(!empty($headval)){           
                    $headval1=$headval[0]->seh_headamount;
		}
                    //echo "hval1inside===".$tok1id. "\n";
                }
                elseif($shtypetok1id == 'D'){
                    $sfield ="ssdh_headamount";    
                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                    $wdata = array('ssdh_empid' =>$empid,'ssdh_headid' =>$tok1id);
                    //$headval1= $this->sismodel->get_maxvalue('salary_subsdeduction_head',$sfield,$wdata,'');
                    $headval=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfield,'ssdh_modifydate=',$wdata);
            	if(!empty($headval)){           
                    $headval1=$headval[0]->ssdh_headamount;
		}
                   //echo "hval1inside===".$headval1. "\n";
                }
                else{
                    /****************query what amount u pick installment amount and or total amount*********************/
                    $sfield ="slh_headamount";
                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                    $wdata = array('slh_empid' =>$empid,'slh_headid' =>$tok1id);
                    //$headval1= $this->sismodel->get_maxvalue('salary_loan_head',$sfield,$wdata,'');
                    $headval=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$sfield,'slh_modifydate=',$wdata);
            	if(!empty($headval)){           
                    $headval1=$headval[0]->slh_headamount;
		}
                }
               /* $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[0])->sh_id;
                $wdata = array('shdv_paybandid' =>$pbid,'shdv_salheadid' =>$tok1id);
                $headval1= $this->sismodel->get_orderlistspficemore('salaryhead_defaultvalue',$sfield,$wdata,'');  
                $headval1=$headval1[0]->shdv_defaultvalue;*/
            }
            else{
                $headval1=0;
            }
            if(!empty($strfmla[1])){
                $shtypetok2id=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_code',$strfmla[1])->sh_type;
                if($shtypetok2id == 'I' ){
                    $sfield ="seh_headamount";    
                    $tok1id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                    $wdata = array('seh_empid' =>$empid,'seh_headid' =>$tok1id);
                    //$headval1= $this->sismodel->get_maxvalue('salary_earnings_head',$sfield,$wdata,'');
                    $headval2= $this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfield,'seh_modifydate=',$wdata);
            	if(!empty($headval2)){           
                    $headval2=$headval2[0]->seh_headamount;
		}
                   // echo "hval1inside===".$headval1. "\n";
                }
                elseif($shtypetok2id == 'D' ){
                    $sfield ="ssdh_headamount";   
                    $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                    // echo "tokwen2----".$tok2id;
                    $wdata = array('ssdh_empid' =>$empid,'ssdh_headid'=> $tok2id);
                   // $headval2= $this->sismodel->get_orderlistspficemore('salary_subsdeduction_head',$sfield,$wdata,''); 
                    $headval2=$this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfield,'ssdh_modifydate=',$wdata);
            	if(!empty($headval2)){           
                    $headval2=$headval2[0]->ssdh_headamount;
		}
                                        //echo "hval2inside===".$headval2. "\n";
                }
                else{
                    $sfield ="slh_headamount";
                    $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                    // echo "tokwen2----".$tok2id;
                    $wdata = array('slh_empid' =>$empid,'slh_headid'=> $tok2id);
                    //$headval2= $this->sismodel->get_orderlistspficemore('salary_loan_head',$sfield,$wdata,''); 
                    $headval2=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$sfield,'slh_modifydate=',$wdata);
            	if(!empty($headval2)){           
                    $headval2=$headval2[0]->slh_headamount;
		}
                    //echo "hval2inside===".$headval2. "\n"; 
                }
                /* $tok2id=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code',$strfmla[1])->sh_id;
                $wdata = array('shdv_paybandid' =>$pbid,'shdv_salheadid' => $tok2id);
                $headval2= $this->sismodel->get_orderlistspficemore('salaryhead_defaultvalue',$sfield,$wdata,''); 
                $headval2=$headval2[0]->shdv_defaultvalue;*/
            }
            else{
                $headval2=0; 
            }
            $rawfor = (int)$headval1 + (int)$headval2 ;
            //$rawfor=$headval1[0]->shdv_defaultvalue + $headval2[0]->shdv_defaultvalue ;
          //  $this->finalval=$rawfor * $strfmla2[1];
            
            //part for upfsubcription and cps subscription-------------------
            $upfsubcode=$this->sismodel->get_listspfic1('salary_head','sh_code','sh_id',$shid)->sh_code;
            if($upfsubcode == 'UPFsub'){
                $this->finalval=$rawfor * $strfmla2[1];
                $upfsubid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','UPFsub')->sh_id;
                $sfupfsub ="ssdh_headamount";   
                $wdatausub = array('ssdh_empid' =>$empid,'ssdh_headid'=>$upfsubid);
                $headval2= $this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfupfsub,'ssdh_modifydate=',$wdatausub);
                if(!empty($headval2)){
                    $headvalfnl=$headval2[0]->ssdh_headamount;
                    if($headvalfnl > $this->finalval){
                        $this->finalval= $headvalfnl;
                    }
                    else{
                        $this->finalval=$this->finalval;
                    }
                }
		if(substr($emppfno, 0, 1) == "C"){
			$this->finalval=0;
		}
                                                                
            }//upfsubcription closer
            elseif($upfsubcode == 'CPS'){
                $this->finalval=$rawfor * $strfmla2[1];
                $cpssubid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','CPS')->sh_id;
                $sfcpssub ="ssdh_headamount";   
                $wdatacssub = array('ssdh_empid' =>$empid,'ssdh_headid'=>$cpssubid);
                $hvalcps= $this->sismodel->get_rundualquery1('max(ssdh_modifydate)','salary_subsdeduction_head',$sfcpssub,'ssdh_modifydate=',$wdatacssub);
                if(!empty($hvalcps)){
                    $hvalcpsfnl=$hvalcps[0]->ssdh_headamount;
                    if($hvalcpsfnl > $this->finalval){
                        $this->finalval= $hvalcpsfnl;
                    }
                    else{
                        $this->finalval=$this->finalval;
                    }
                }
                                    
		if(substr($emppfno, 0, 1) == "V"){
			$this->finalval=0;
		}
            }//cpssubcription closer
            else{
                $this->finalval=$rawfor * $strfmla2[1];    
            }
            
            
            }//close not numeric
           // echo "in formula method==="."---headval1===". $headval1."headval2==".$headval2."finalval===".$this->finalval."shid==".$shid."empid===".$empid;
        }//emptyformulacheck  
        
        else{
            $ccaid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','CCA')->sh_id;
            $hraid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','HRA')->sh_id;
            $rentid=$this->sismodel->get_listspfic1('salary_head','sh_id','sh_code','Rent')->sh_id;
            if($shid == $ccaid || $shid == $hraid || $shid == $rentid){
                //echo "ccaidrecord in else part=both check cca and hra or==".$record1->sh_id."salhead===".$ccaid."\n";
                if($shid == $ccaid){
                    $this->finalval=$this->cal_CCAgrade($empid);
                  //  echo "finalval==cca=".$this->finalval;
                
                }
                if($shid == $hraid){
                    $this->finalval=$this->cal_HRAgrade($empid);
                    //echo "\n"."finalval==hra=".$this->finalval;
                }
                if($shid == $rentid){
                    $qtrocc=$this->sismodel->get_listspfic1('employee_master_support','ems_qoccupai','ems_empid',$empid);
                    if(!empty($qtrocc) && $qtrocc->ems_qoccupai == 'yes'){
                        $this->finalval=$this->cal_RentRecvory($empid);
                       // echo "finalval==rentrecovery====".$this->finalval;
               
                    }
                }   
            
            }
            else{
                $this->finalval=0;
            }    
        } //else part closer
        return $this->finalval;
        
    }
    /************closer formula value *******************************************************************/
    public function getInsertSalarydata($empid,$salheadid,$salamnt,$installdetl,$month,$year){
        $saldata = array(
            'sald_empid'       =>$empid,   
            'sald_sheadid'     =>$salheadid,
            'sald_shamount'    =>$salamnt, 
            'sald_installment' =>$installdetl, 
            'sald_month'       =>$month,
            'sald_year'        =>$year,
        );
        
        $dupcheck = array(
            'sald_empid'       =>$empid,   
            'sald_sheadid'     =>$salheadid,
            'sald_month'       =>$month,
            'sald_year'        =>$year,
        ); 
        
        $emidexits= $this->sismodel->isduplicatemore('salary_data',$dupcheck);
        if(!$emidexits){
            
            /* insert record in  salary data */
            $this->sismodel->insertrec('salary_data', $saldata);
            $this->logger->write_logmessage("insert", "data insert in salary_data table.");
            $this->logger->write_dblogmessage("insert", "data insert in salary_data table." );
            
        }
        else{
            
            /* update record in  salary data */
            $selectfield ="sald_id";
            $whdata = array('sald_empid' =>$empid,'sald_sheadid' =>$salheadid,'sald_month' =>$month,'sald_year' =>$year);
            $saldataid= $this->sismodel->get_orderlistspficemore('salary_data',$selectfield,$whdata,'');
            
            $this->sismodel->updaterec('salary_data', $saldata,'sald_id',$saldataid[0]->sald_id);
            $this->logger->write_logmessage("update", "data update in salary_data table.");
            $this->logger->write_dblogmessage("update", "data update in salary_data table." ); 
            
        }
        
    }
    /************closer insert Salarydata *******************************************************************/
    
    /************ InsertSalary ******************************************************************************/
    public function getInsertSalary($empid,$scid,$uoid,$deptid,$desigid,$sapost,$ddoid,$schemeid,$payscaleid,
                    $bankaccno,$worktype,$emptype,$group,$month,$year,$tincome,$tdeduction,$netsal,$status){
       
        $salinst=array(
            'sal_empid'             =>$empid,
            'sal_scid'              =>$scid,
            'sal_uoid'              =>$uoid,
            'sal_deptid'            =>$deptid,
            'sal_desigid'           =>$desigid,
            'sal_sapost'            =>$sapost,
            'sal_ddoid'             =>$ddoid,
            'sal_schemeid'          =>$schemeid,
            'sal_payscaleid'        =>$payscaleid,
            'sal_bankaccno'         =>$bankaccno,
            'sal_worktype'          =>$worktype,
            'sal_emptype'           =>$emptype,
            'sal_group'             =>$group,
            'sal_month'             =>$month,
            'sal_year'              =>$year,
            'sal_totalincome'       =>$tincome,
            'sal_totaldeduction'    =>$tdeduction,
            'sal_netsalary'         =>$netsal,
            'sal_status'            =>$status,
            'sal_paiddate'          =>date('y-m-d'),
            'sal_creationdate'      =>date('y-m-d'),
            'sal_creatorid'         =>$this->session->userdata('username'),
            'sal_updatedate'        =>date('y-m-d'),
            'sal_modifierid'        =>$this->session->userdata('username'),
        );
        
        $dupcheck = array(
            'sal_empid'             =>$empid,
            'sal_month'             =>$month,
            'sal_year'              =>$year,
        );  
        
        $emidexits= $this->sismodel->isduplicatemore('salary',$dupcheck);
        
        if(!$emidexits){
            /* insert record in  salary detail */
           $this->sismodel->insertrec('salary',  $salinst);
            $this->logger->write_logmessage("insert", "data insert in salary table.");
            $this->logger->write_dblogmessage("insert", "data insert in salary table." );
            
        }
        else{
            
            $salinst=array(
                'sal_empid'             =>$empid,
                'sal_scid'              =>$scid,
                'sal_uoid'              =>$uoid,
                'sal_deptid'            =>$deptid,
                'sal_desigid'           =>$desigid,
                'sal_sapost'            =>$sapost,
                'sal_ddoid'             =>$ddoid,
                'sal_schemeid'          =>$schemeid,
                'sal_payscaleid'        =>$payscaleid,
                'sal_bankaccno'         =>$bankaccno,
                'sal_worktype'          =>$worktype,
                'sal_emptype'           =>$emptype,
                'sal_group'             =>$group,
                'sal_month'             =>$month,
                'sal_year'              =>$year,
                'sal_totalincome'       =>$tincome,
                'sal_totaldeduction'    =>$tdeduction,
                'sal_netsalary'         =>$netsal,
                'sal_status'            =>$status,
              //  'sal_paiddate'          =>$paiddate,
                'sal_updatedate'        =>date('y-m-d'),
                'sal_modifierid'        =>$this->session->userdata('username'),
            );
            
            /* update record in  salary detail */
            
            $selectfield ="sal_id";
            $whdata = array('sal_empid' =>$empid,'sal_month' =>$month,'sal_year' =>$year);
            $saldataid= $this->sismodel->get_orderlistspficemore('salary',$selectfield,$whdata,'');
            $this->sismodel->updaterec('salary',  $salinst,'sal_id',$saldataid[0]->sal_id);
            $this->logger->write_logmessage("update", "data update in salary table.");
            $this->logger->write_dblogmessage("update", "data update in salary table." ); 
        }   
        
    }
    /************ closer InsertSalary ******************************************************************************/
    /*public function getallowedhead($emptype){
        $selectfield ="shc_id, shc_emptypeid, shc_salheadid";
        $whorder = "shc_id asc";
	$whdata = array('shc_emptypeid' =>$emptype);
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salaryhead_configuration',$selectfield,$whdata,$whorder);
               
    }*/
    /******************************lock salary month by head**************************************************************************/
    public function locksalary(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	    $ssiondeptid=$this->session->userdata('id_dept');
	    $cmonth= date('M');
	    $cyear= date("Y");
	    if(isset($_POST['sallock'])){
		    $lockdata=array(
			    'sld_month' => $cmonth,
			    'sld_year' => $cyear,
			    'sld_deptid' => $ssiondeptid,
			    'sld_status' => 'Y',
			    'sld_creator' => $this->session->userdata('username'),
			    'sld_creationdate' => date('y-m-d'),
			    'sld_modifier' => $this->session->userdata('username'),
			    'sld_modifidate' => date('y-m-d'),
		    );
		    $duprec=array(
                            'sld_month' => $cmonth,
                            'sld_year' => $cyear,
                            'sld_deptid' => $ssiondeptid,
                            'sld_status' => 'Y',
                            'sld_creationdate' => date('y-m-d'),
                    );
		    $recexits= $this->sismodel->isduplicatemore('salary_lock_data',$duprec);
		    if(!$recexits){
		    $selflag=$this->sismodel->insertrec('salary_lock_data',  $lockdata);
		    if (! $selflag   )
		    {
			    $this->logger->write_logmessage("insert","Trying to add salary lock ", "Error in insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]");
			    $this->logger->write_dblogmessage("insert", "Trying to add salary lock ","Error in insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]");
			    $this->session->set_flashdata("err_message","Error in insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]");
			    redirect('setup3redesign/salaryprocess');
		    }
		    else{
			    $this->logger->write_logmessage("insert", "Add salary lock ","Insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]");
			    $this->logger->write_dblogmessage("insert","Add salary lock ", "Insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]");
			    $this->session->set_flashdata("success", "Insert lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]" );
			    redirect('setup3redesign/salaryprocess');

	            }
		}else{
			    $this->session->set_flashdata("err_message", "duplicate lock record" . " [Dept id:" . $ssiondeptid . " Month :".$cmonth." Year :".$cyear."]" );
			    redirect('setup3redesign/salaryprocess');
			
		}
	    }
    }

    /******************************unlock salary month by head**************************************************************************/
    public function unlocksalary(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
		$datawh='';
                $whorder='dept_name asc';
                $ulsdata['deptdata'] = $this->commodel->get_orderlistspficemore('Department','dept_id,dept_name,dept_code',$datawh,$whorder);
	    	$sessionunme=$this->session->userdata('username');
	    	if(($sessionunme == "admin") || ($sessionunme == "payrolladmin")){
		    //get the month year and department
	    		$cmonth= date('M');
	    		$cyear= date("Y");
	    		$deptid='';
                	if(isset($_POST['unlocksal'])) {
                        	$this->form_validation->set_rules('dept','Deapartment','trim|xss_clean|required');
                        	$this->form_validation->set_rules('month','Month','trim|xss_clean');
                        	$this->form_validation->set_rules('year','Year','trim|xss_clean');
                        	if($this->form_validation->run() == TRUE){
                                	$cmonth = $this->input->post('month', '');
                                	$cyear = $this->input->post('year', '');
                                	$deptid=$this->input->post('dept', TRUE);
		    			$unlockdata=array(
			    			'sld_month' => $cmonth,
			    			'sld_year' => $cyear,
			    			'sld_deptid' => $deptid,
			    			'sld_status' => 'N',
			    			'sld_creator' => $this->session->userdata('username'),
			    			'sld_creationdate' => date('y-m-d'),
			    			'sld_modifier' => $this->session->userdata('username'),
						'sld_modifidate' => date('y-m-d'),
		    			);
					//check for lock exist
					$ldatawh =array("sld_month" =>$cmonth, "sld_year"=>$cyear,"sld_deptid"=>$deptid);
			                $lockdata=$this->sismodel->get_orderlistspficemore('salary_lock_data','sld_status',$ldatawh,'sld_id desc');
			                if(!empty($lockdata)){
                        			$lstatus=$lockdata[0]->sld_status;
                			}else{
			                        $lstatus="N";
                			}
					if($lstatus == 'Y'){
					$duprec=array(
                                                'sld_month' => $cmonth,
                                                'sld_year' => $cyear,
                                                'sld_deptid' => $deptid,
                                                'sld_status' => 'N',
                                                'sld_creationdate' => date('y-m-d'),
                                        );
		    			$recexits= $this->sismodel->isduplicatemore('salary_lock_data',$duprec);
			    		if(!$recexits){
				    		$selflag=$this->sismodel->insertrec('salary_lock_data',  $unlockdata);
		    				if (! $selflag   )
		    				{
			    				$this->logger->write_logmessage("insert","Trying to add salary unlock ", "Error in insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]");
			    				$this->logger->write_dblogmessage("insert", "Trying to add salary unlock ","Error in insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]");
			    				$this->session->set_flashdata("err_message","Error in insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]");
		//	    redirect('setup3redesign/salaryprocess');
		    				}
		    				else{
			    				$this->logger->write_logmessage("insert", "Add salary unlock ","Insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]");
			    				$this->logger->write_dblogmessage("insert","Add salary unlock ", "Insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]");
			    				$this->session->set_flashdata("success", "Insert unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]" );
		//	    redirect('setup3redesign/salaryprocess');
	            				}
					}else{
                            			$this->session->set_flashdata("err_message", "Duplicate lock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]" );
                            		//	redirect('setup3redesign/salaryprocess');
                			}//duplicate record
					}else{
                            			$this->session->set_flashdata("err_message", "Lock record does not exist" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]" );

					}
	    			}
                        }
	    }else{
		    $this->session->set_flashdata("err_message", "Try to unlock record" . " [Dept id:" . $deptid . " Month :".$cmonth." Year :".$cyear."]" );
                //            redirect('setup3redesign/salaryprocess');
	    }
                $selfield='sld_id,sld_month,sld_year,sld_deptid,sld_status,sld_creationdate';
                $datawh='';
                $whorder='sld_id desc';
                $ulsdata['ulksdata'] = $this->sismodel->get_orderlistspficemore('salary_lock_data',$selfield,$datawh,$whorder);
                $this->load->view('setup3/salaryunlock',$ulsdata);

    }

    /******************************copy previous month salary to next month**************************************************************************/
    public function copysalary(){
	$array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $selectfield ="emp_id,emp_code,emp_name,emp_scid,emp_uocid,emp_dept_code,emp_schemeid,emp_desig_code,emp_post,emp_worktype,emp_type_code,"
                . "emp_email,emp_phone,emp_salary_grade,emp_bank_accno,emp_ddoid,emp_group,emp_aadhaar_no";
        $whdata = array ('emp_leaving' => NULL,'emp_dor>='=>date('Y-m-d'));
	$ssiondeptid=$this->session->userdata('id_dept');
        if(!empty($ssiondeptid)){
                $whdata['emp_dept_code']=$ssiondeptid;
        }

      //  $whorder = "emp_name asc,emp_dept_code asc,emp_desig_code asc";
        $data['emplist'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,'');
        $cmonth= date('M');
        $cyear= date("Y"); 
        $data['selmonth']=$cmonth;
        $data['selyear']=$cyear;
	$cnomonth= date("m",strtotime($cmonth));

	$sessionroleid=$this->session->userdata('id_role');
        $data['sroleid']= $sessionroleid;
        if($sessionroleid == 5){
                $ldatawh =array("sld_month" =>$cmonth, "sld_year"=>$cyear,"sld_deptid"=>$ssiondeptid);
                $lockdata=$this->sismodel->get_orderlistspficemore('salary_lock_data','sld_status',$ldatawh,'');
                if(!empty($lockdata)){
                        $lstatus=$lockdata[0]->sld_status;
                }else{
                        $lstatus="N";
                }
        }
	
	if(($sessionroleid == 5)&&($lstatus == "Y")){
            $this->logger->write_logmessage("insert", " Salary data copy locked"."Salary data copy is locked for deptid :".$ssiondeptid." Month: ".$cmonth." Year : ".$cyear);
            $this->logger->write_dblogmessage("insert"," Salary data copy locked"."Salary data copy is locked for deptid :".$ssiondeptid." Month: ".$cmonth." Year : ".$cyear);
            $this->session->set_flashdata("success", "Salary data copy is locked for deptid :".$ssiondeptid." Month: ".$cmonth." Year : ".$cyear );

	}else{


        if(isset($_POST['salcopy'])){
            foreach($data['emplist'] as $record){
                /* checking in table that employee data of previous month is exists or not */
            //    $empexist=$this->sismodel->isduplicate('salary_data','sald_empid',$record->emp_id);
              //  print_r("rrr====".$empexist);
                /*******************employee transfer case*********************************************/
                $transdata=array(
                   'ste_empid'                =>$record->emp_id,
                   'ste_month'                =>$cmonth,
                   'ste_year'                 =>$cyear  
                );
                $emptrans=$this->sismodel->isduplicatemore('salary_transfer_entry',$transdata);
               // print_r("stransfer\n".$emptrans."\n==nestvalempis====");
               // print_r("transempid\n".$record->emp_id);
                /************************employee Leave case***********************************************/
                $leavedata=array(
                   'sle_empid'                =>$record->emp_id,
                   'sle_month'                =>$cmonth,
                   'sle_year'                 =>$cyear   
                );
                $empleave=$this->sismodel->isduplicatemore('salary_leave_entry',$leavedata);
               // print_r("sleave\n".$empleave."\nnestvalempis====");
                //print_r("\nleave".$record->emp_id);
                //die;
                                
            //    if(!$empexist){
                    /*********************************Default Salary***************************************************/
                    if((!empty($emptrans)) || (!empty($empleave))){
                            //default salary generate employee transfer case
                        if($emptrans == 1 && $empleave != 1){
                            $this->Defaluttranfr_days($record->emp_id,$cmonth,$cyear);
                            $this->Defaluttranfr_transit($record->emp_id, $cmonth, $cyear);
                            $this->Defaluttranfr_dayto($record->emp_id, $cmonth, $cyear);
				$ucase = "transcase";
                        }
                            //default salary generate employee leave case
                        if($empleave == 1 && $emptrans != 1){
                            //  echo "part leave====".$record->emp_id."\n";
                            //default salary generate employee leave case
                            $this->Defalutleavesalary($record->emp_id,$cmonth,$cyear);
				$ucase = "leavecase";
                        }
                            //default salary generate employee leave and transfer case
                        if($emptrans == 1 && $empleave == 1){
                            $this->Defaluttranfr_days($record->emp_id,$cmonth,$cyear);
                            $this->Defaluttranfr_transit($record->emp_id, $cmonth, $cyear);
                            $this->Defaluttranfr_dayto($record->emp_id, $cmonth, $cyear);
				$ucase = "transcase";
                            
                        }
                    }
                    else{
                        //  echo "with default values salary generate without transfer case";
                        //die();
                        //echo "part not leave and transfer====".$record->emp_id;
                        $this->DefalutSalaryPro($record->emp_id,$cmonth,$cyear); 
				$ucase = "defltcase";
                        //echo"in default case===".$record->emp_id;
                    }
              //  }
                /*********this part is commented for checking always updated data no need to use this part************/
                
                /*                
                
                else{
                    
                    //select sald_id,sald_empid,sald_sheadid,sald_shamount,sald_month from salary_data
                    // where sald_empid=15 && sald_month=(SELECT sald_month from salary_data 
                    // where sald_id=(select max(sald_id) from salary_data where sald_empid=15));
                    
                   // echo "problem in else loop 1";
                    $selectfield ="sald_id";
                    $whdata = array('sald_empid'=>$record->emp_id);
                    $salmaxid= $this->sismodel->get_maxvalue('salary_data',$selectfield,$whdata); 
                    $salmonth=$this->sismodel->get_listspfic1('salary_data','sald_month','sald_id',$salmaxid[0]->sald_id)->sald_month;
                    $salyear=$this->sismodel->get_listspfic1('salary_data','sald_year','sald_id',$salmaxid[0]->sald_id)->sald_year;
                    //print_r("valuesof max====".$salmaxid[0]->sald_id.$salmonth);
                  
                    //  if(!empty($headvalues)){
                    $selectfield ="sald_sheadid,sald_shamount,sald_installment";
                    $whdata = array ('sald_empid'=>$record->emp_id,'sald_month'=>$salmonth,'sald_year'=>$salyear);
                    $headvalues = $this->sismodel->get_orderlistspficemore('salary_data',$selectfield,$whdata,'');
                    
                  //  die;
                    foreach($headvalues as $saldata){*/
                    /*********************check entry existst in transfer case*****/
                    /*    if((!empty($emptrans)) || (!empty($empleave))){
                                                        
                            if($emptrans == 1){
                                
                                $intstr=$saldata->sald_installment;
                                if($intstr != NULL){
                                    $intallstr=explode("-",$intstr);
//				    if((strcmp($intstr,"0-0")) != 0){
                                    if(((strcmp($intstr,"0-0")) != 0)&&($intallstr[0] != $intallstr[1])){
                                        $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                                    }
//				    else{
                                    else if($intallstr[0] == $intallstr[1]){
					$intallno="0-0";
//                                        $intallno=$saldata->sald_installment;   
                                    }
                                }
                                else{
                                    $intallno=$saldata->sald_installment;   
                                }
                                $stffield ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
                                $wstf = array ('ste_empid'=>$record->emp_id,'ste_month'=>$cmonth,'ste_year'=>$cyear);
                                $stfvalues = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffield,$wstf,'');
                            
                                $hamttday=$this->transferfmlahead($record->emp_id,$cyear,$cnomonth,$saldata->sald_sheadid,$stfvalues[0]->ste_days);
                                $this->Salarydata_lt($record->emp_id,$saldata->sald_sheadid,(round($hamttday,2)),$intallno,$cmonth,$cyear,'from');
                            
                                $hamttransit=$this->transferfmlahead($record->emp_id,$cyear,$cnomonth,$saldata->sald_sheadid,$stfvalues[0]->ste_transit);
                                $this->Salarydata_lt($record->emp_id,$saldata->sald_sheadid,(round($hamttransit,2)),$intallno,$cmonth,$cyear,'transit');
                            
                                $totaldt=$stfvalues[0]->ste_days + $stfvalues[0]->ste_transit; 
                                $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
                                $todays= $nodaysmonth - $totaldt; 
                           
                                $hamtto=$this->transferfmlahead($record->emp_id,$cyear,$cnomonth,$saldata->sald_sheadid,$todays);
                                $this->Salarydata_lt($record->emp_id,$saldata->sald_sheadid,(round($hamtto,2)),$intallno,$cmonth,$cyear,'to');
                            }
                            if($empleave == 1){
                                $intstr=$saldata->sald_installment;
                                if($intstr != NULL){
                                    $intallstr=explode("-",$intstr);
//				    if((strcmp($intstr,"0-0")) != 0){
                                    if(((strcmp($intstr,"0-0")) != 0)&&($intallstr[0] != $intallstr[1])){
                                        $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                                    }
//				else{
                                    else if($intallstr[0] == $intallstr[1]){
                                        $intallno="0-0";
//                                        $intallno=$saldata->sald_installment;   
                                    }
                                }
                                else{
                                    $intallno=$saldata->sald_installment;   
                                }
                                $slefield ="sle_pal,sle_eol";
                                $wsle = array ('sle_empid'=>$record->emp_id,'sle_month'=>$cmonth,'sle_year'=>$cyear);
                                $slevalues = $this->sismodel->get_orderlistspficemore('salary_leave_entry',$slefield,$wsle,'');
                               
                                $totaldl=$slevalues[0]->sle_pal + $slevalues[0]->sle_eol; 
                                $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
                                $leftdays= $nodaysmonth - $totaldl; 
                                                             
                                $hamtlday=$this->transferfmlahead($record->emp_id,$cyear,$cnomonth,$saldata->sald_sheadid,$leftdays);
                                $this->Salarydata_lt($record->emp_id,$saldata->sald_sheadid,(round($hamttday,2)),$intallno,$cmonth,$cyear,'leave');
                                
                            }
                        }
                        else{
                            $intstr=$saldata->sald_installment;
                            //print_r($intstr.",");
                               
                            if($intstr != NULL){
                                $intallstr=explode('-',$intstr);
                                //print_r($intallstr[0]."\n".$intallstr[1]."\n");
                              //  die();
//				    if((strcmp($intstr,"0-0")) != 0){
				if(((strcmp($intstr,"0-0")) != 0)&&($intallstr[0] != $intallstr[1])){
                                    $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                                }
//				else{
                            	else if($intallstr[0] == $intallstr[1]){
                                        $intallno="0-0";
//                                    	$intallno=$saldata->sald_installment;   
                            	}
                            }
			    else{
                                	$intallno=$saldata->sald_installment;   
			    }
                            $this->getInsertSalarydata($record->emp_id,$saldata->sald_sheadid,$saldata->sald_shamount,$intallno,$cmonth,$cyear);
                        }
                    } // end for head value
                    //die;
                    
                */    
                    /**************************insert in salary *****************************************************s********/
                    /*
                   
                    $selectfield1 ="sal_id";
                    $whdata1 = array('sal_empid'=>$record->emp_id);
                    $salmaxid1= $this->sismodel->get_maxvalue('salary',$selectfield1,$whdata1); 
                    $salmonth1=$this->sismodel->get_listspfic1('salary','sal_month','sal_id',$salmaxid1[0]->sal_id)->sal_month;
                    $salyear1=$this->sismodel->get_listspfic1('salary','sal_year','sal_id',$salmaxid1[0]->sal_id)->sal_year;
                    
                    $selectfield1 ="sal_scid,sal_uoid,sal_deptid,sal_desigid,sal_sapost,sal_ddoid,sal_schemeid,sal_payscaleid,sal_bankaccno,
                        sal_worktype,sal_emptype,sal_group,sal_totalincome,sal_totaldeduction,sal_netsalary";
                    $whdata1 = array('sal_empid' =>$record->emp_id,'sal_month'=>$salmonth1,'sal_year'=>$salyear1);
                    $headvalues1= $this->sismodel->get_orderlistspficemore('salary',$selectfield1,$whdata1,''); 
                  
                    foreach($headvalues1 as $saldata2){
                        
                        if((!empty($emptrans)) || (!empty($empleave))){
                            if($emptrans == 1){*/
                                /*******************detail of transfer table ************************************/
                              //  echo "part  transfer salary====".$record->emp_id."\n";
                               /* $stffieldsal ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
                                $wstfsal = array ('ste_empid'=>$record->emp_id,'ste_month'=>$cmonth,'ste_year'=>$cyear);
                                $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffieldsal,$wstfsal,'');
                            */
                                /***************************total income, deduction, netincome according to days***************************/
                              /*  $titday=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totalincome,$stfvalsal[0]->ste_days);
                                $tdedtday=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totaldeduction,$stfvalsal[0]->ste_days);
                                $tnettday=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_netsalary,$stfvalsal[0]->ste_days);
                            */
                                /************************from employee uit details***********************/
                            /*    $input=$cnomonth.'-'.$cmonth.'-'.'00'.'00:00:00';
                                $date = strtotime($input);
                                $cdate=date('Y-M-D h:i:s', $date); 
                                // echo "cdddd===".$cdate;
                                $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
                                    uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
                                //$wempsal = array ('uit_staffname'=>$record->emp_id, ' uit_date'=>$cdate);
                                $wempsal = array ('uit_staffname'=>$record->emp_id);
                                //$stvalemp = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stempdsal,$wempsal,'');
                                $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
                            //  echo "print===".$stvalemp[0]->uit_scid_from."==".$stvalemp[0]->uit_uoc_from."===".$cdate;
                                $this->Salary_lt($record->emp_id,$stvalemp[0]->uit_scid_from,$stvalemp[0]->uit_uoc_from,$stvalemp[0]->uit_workdept_from,
                                $stvalemp[0]->uit_desig_from,$stvalemp[0]->uit_workingpost_from,$stvalemp[0]->uit_ddoid_from,$stvalemp[0]->uit_schm_from,
                                $stvalemp[0]->uit_paybandid_to,$saldata2->sal_bankaccno,$stvalemp[0]->uit_emptype,$stvalemp[0]->uit_vactype_from,
                                $stvalemp[0]->uit_group_from,$cmonth,$cyear,(round($titday,2)),(round($tdedtday,2)),(round($tnettday,2)),'process','from');
                            */
                                /***************************total income, deduction, netincome according to transit***************************/
                            /*    $titran=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totalincome,$stfvalsal[0]->ste_transit);
                                $tdedtran=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totaldeduction,$stfvalsal[0]->ste_transit);
                                $tnetttran=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_netsalary,$stfvalsal[0]->ste_transit);
                            
                                $this->Salary_lt($record->emp_id,$stvalemp[0]->uit_scid_from,$stvalemp[0]->uit_uoc_from,$stvalemp[0]->uit_workdept_from,
                                $stvalemp[0]->uit_desig_from,$stvalemp[0]->uit_workingpost_from,$stvalemp[0]->uit_ddoid_from,$stvalemp[0]->uit_schm_from,
                                $stvalemp[0]->uit_paybandid_to,$saldata2->sal_bankaccno,$stvalemp[0]->uit_emptype,$stvalemp[0]->uit_vactype_from,
                                $stvalemp[0]->uit_group_from,$cmonth,$cyear,(round($titran,2)),(round($tdedtran,2)),(round($tnetttran,2)),'process','transit');
                            */
                                /***************************getting total income, deduction, netincome of new study center(days - transit )********************/
                            
                            /*    $totaldt=$stfvalsal[0]->ste_days + $stfvalsal[0]->ste_transit; 
                                $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
                                $todays= $nodaysmonth - $totaldt; 
                            
                                $tito=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totalincome,$todays);
                                $tdedto=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totaldeduction,$todays);
                                $tnetto=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_netsalary,$todays);
                                                       
                                $this->Salary_lt($record->emp_id,$saldata2->sal_scid,$saldata2->sal_uoid,$saldata2->sal_deptid,$saldata2->sal_desigid,
                                $saldata2->sal_sapost,$saldata2->sal_ddoid,$saldata2->sal_schemeid,$saldata2->sal_payscaleid,$saldata2->sal_bankaccno,$saldata2->sal_worktype,
                                $saldata2->sal_emptype,$saldata2->sal_group,$cmonth,$cyear,(round($tito,2)),(round($tdedto,2)),(round($tnetto,2)),'process','to');
                            
                            }
                            if($empleave == 1){
                               // echo "part leave  salary====".$record->emp_id."\n";
                                $slefield ="sle_pal,sle_eol";
                                $wsle = array ('sle_empid'=>$record->emp_id,'sle_month'=>$cmonth,'sle_year'=>$cyear);
                                $slevalues = $this->sismodel->get_orderlistspficemore('salary_leave_entry',$slefield,$wsle,'');
                               
                                $totaldl=$slevalues[0]->sle_pal + $slevalues[0]->sle_eol; 
                                $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
                                $leftdays= $nodaysmonth - $totaldl;
                                if($nodaysmonth > $totaldl){
                                
                                    $tito=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totalincome,$leftdays);
                                    $tdedto=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_totaldeduction,$leftdays);
                                    $tnetto=$this->transferfmlatotal($record->emp_id,$cyear,$cnomonth,$saldata2->sal_netsalary,$leftdays);
                               
                                    $this->Salary_lt($record->emp_id,$saldata2->sal_scid,$saldata2->sal_uoid,$saldata2->sal_deptid,$saldata2->sal_desigid,
                                    $saldata2->sal_sapost,$saldata2->sal_ddoid,$saldata2->sal_schemeid,$saldata2->sal_payscaleid,$saldata2->sal_bankaccno,$saldata2->sal_worktype,
                                    $saldata2->sal_emptype,$saldata2->sal_group,$cmonth,$cyear,(round($tito,2)),(round($tdedto,2)),(round($tnetto,2)),'process','leave');
                                }    
                            }
                        }
                        else{
                            $this->getInsertSalary($record->emp_id,$saldata2->sal_scid,$saldata2->sal_uoid,$saldata2->sal_deptid,$saldata2->sal_desigid,
                            $saldata2->sal_sapost,$saldata2->sal_ddoid,$saldata2->sal_schemeid,$saldata2->sal_payscaleid,$saldata2->sal_bankaccno,$saldata2->sal_worktype,
                            $saldata2->sal_emptype,$saldata2->sal_group,$cmonth,$cyear,$saldata2->sal_totalincome,$saldata2->sal_totaldeduction,$saldata2->sal_netsalary,'process');
                            
                        }
                    }// end for head vlaues 1 loop
               
                } //end exist if llop else part
                
                */
                  /*************************sending mail with Attachment Salaryslip********************************************/
                $this->mailmodel->mailAttachment($record->emp_id,$record->emp_code,$cmonth,$cyear,$ucase);
                
            
            }//emplistloop
            $this->logger->write_logmessage("insert", " Salary data copy "."Salary data copy");
            $this->logger->write_dblogmessage("insert"," Salary data copy "."Salary data copy");
            $this->session->set_flashdata("success", 'Salary data copy successfully ...' );
                 
	}
	}
        $this->load->view('setup3/empSalary',$data);
    
    }
    public function DefalutSalaryPro($empid,$cmonth,$cyear){
        $sumincome=0;$sumdeduct=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
        $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
       /****select deduction and loans head ******************/
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
       // $whdata = array('sh_type' =>'D','sh_type' =>'L');
        //$data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L','');
        
        $this->emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
        $this->wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype ','emp_id',$empid)->emp_worktype;
        $this->paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
       // echo $this->wtype.", ".$this->paycomm;
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $this->wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        $strarray=$rawarray[0]->shc_salheadid;
       // print_r($strarray);
        $allowedhead=explode(", ",$strarray);
        
       // $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
       // $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$emptype)->empt_id;
        
       // $strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;
       // $allowedhead=explode(", ",$strarray);
        
       // $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $this->wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                    }
                    else{
                       
                        // echo "ccaidrecord in else partof cca and hra===".$record1->sh_id;
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                      
                    } // main else condition if not formula
                }//allowed heads
                else{
                    $this->dheadval=0;   
                }
            
            $this->getInsertSalarydata($empid,$record1->sh_id,$this->dheadval,NULL,$cmonth,$cyear);
            $sumincome+=$this->dheadval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $this->wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                    }
                    else{
                       
                        $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                        
                    }//closer of else if not formula
                } 
                else{
                    $this->dheadval=0; 
                }
            //}   
                
                /**********************************intallment details*****************/  
                
                /*
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$record2->sh_id);
               // $instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }  
                
                */
                $installdetl=$this->updateintlmtno($empid,$record2->sh_id);
            
                $this->getInsertSalarydata($empid,$record2->sh_id,$this->dheadval,$installdetl,$cmonth,$cyear);
                $sumdeduct+=$this->dheadval;
            }
            
        }
       // $this->SalaryPolicies($empid,$cmonth,$cyear);
        $netsalary=$sumincome - $sumdeduct;
                
        $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
        $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
        $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
        $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
        $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
        $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
        $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
        $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
        
        /*************************************insert in salary ********************************************************/
        if($netsalary <= 0){
            $this->getInsertSalary($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$this->wtype,
            $emptype,$group,$cmonth,$cyear,$sumincome,$sumdeduct,0,'pending');
        }
        else{
            $this->getInsertSalary($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$this->wtype,
            $emptype,$group,$cmonth,$cyear,$sumincome,$sumdeduct,$netsalary,'process');   
        }
        
    }
    
    /*public function SalaryPolicies($empid,$cmonth,$cyear){
        $licprdpli = array(
            "LIC1" => "LIC1",
            "LIC2" => "LIC2",
            "LIC3" => "LIC3",
            "LIC4" => "LIC4",
            "LIC5" => "LIC5",
            "PRD1" => "PRD1",
            "PRD2" => "PRD2",
            "PRD3" => "PRD3",
            "PLI1" => "PLI1",
            "PLI2" => "PLI2",
        );
        foreach ($licprdpli as $lpdpi) {
            $this->getInsertSalarydata($empid,$lpdpi,0,$cmonth,$cyear);
        }
    }*/
    
    public function transferfmlahead($empid,$year,$month,$headid,$nodays){
       
            $nodaysmonth=cal_days_in_month(CAL_GREGORIAN, $month ,$year);
            $ttheadamt=$this->sismodel->get_listspfic1('salary_data','sald_shamount','sald_sheadid',$headid)->sald_shamount;
            $calamt=$ttheadamt*$nodays/$nodaysmonth;
        
        //}
        return $calamt;
    }
    public function transferfmlatotal($empid,$year,$month,$tamount,$nodays){
      
            $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $calamt=$tamount*$nodays/$nodaysmonth;
        
        //}
        return $calamt;
    }
    
    public function Salarydata_lt($empid,$salheadid,$salamnt,$intalldtl,$month,$year,$sdtype){
        $saldata = array(
            'saldlt_empid'       =>$empid,   
            'saldlt_sheadid'     =>$salheadid,
            'saldlt_shamount'    =>$salamnt, 
            'saldlt_installment' =>$intalldtl,
            'saldlt_month'       =>$month,
            'saldlt_year'        =>$year,
            'saldlt_type'        =>$sdtype,
        );
        
        $dupcheck = array(
            'saldlt_empid'       =>$empid,   
            'saldlt_sheadid'     =>$salheadid,
            'saldlt_month'       =>$month,
            'saldlt_year'        =>$year,
            'saldlt_type'        =>$sdtype,
        ); 
        
        $emidexits= $this->sismodel->isduplicatemore('salarydata_lt',$dupcheck);
        if(!$emidexits){
            //echo "empid not exists in salarydatalt table";
            
            /* insert record in  salary data */
            $this->sismodel->insertrec('salarydata_lt', $saldata);
            $this->logger->write_logmessage("insert", "data insert in salarydata_lt table.");
            $this->logger->write_dblogmessage("insert", "data insert in salarydata_lt table." );
            
        }
        else{
            
           // echo "empid exists in salarydatalt table in else case";
            /* update record in  salary data */
            $selectfield ="saldlt_id";
            $whdata = array('saldlt_empid' =>$empid,'saldlt_sheadid' =>$salheadid,'saldlt_month' =>$month,'saldlt_year' =>$year);
            $saldataid= $this->sismodel->get_orderlistspficemore('salarydata_lt',$selectfield,$whdata,'');
            
            $this->sismodel->updaterec('salarydata_lt', $saldata,'saldlt_id',$saldataid[0]->saldlt_id);
            $this->logger->write_logmessage("update", "data update in salarydata_lt table.");
            $this->logger->write_dblogmessage("update", "data update in salarydata_lt table." ); 
            
        }
        
    }
    /************closer insert Salarydata *******************************************************************/
    
    /************ InsertSalary ******************************************************************************/
    public function Salary_lt($empid,$scid,$uoid,$deptid,$desigid,$sapost,$ddoid,$schemeid,$payscaleid,
                    $bankaccno,$worktype,$emptype,$group,$month,$year,$tincome,$tdeduction,$netsal,$status,$saltype){
       
        $salinst=array(
            'sallt_empid'             =>$empid,
            'sallt_scid'              =>$scid,
            'sallt_uoid'              =>$uoid,
            'sallt_deptid'            =>$deptid,
            'sallt_desigid'           =>$desigid,
            'sallt_sapost'            =>$sapost,
            'sallt_ddoid'             =>$ddoid,
            'sallt_schemeid'          =>$schemeid,
            'sallt_payscaleid'        =>$payscaleid,
            'sallt_bankaccno'         =>$bankaccno,
            'sallt_worktype'          =>$worktype,
            'sallt_emptype'           =>$emptype,
            'sallt_group'             =>$group,
            'sallt_month'             =>$month,
            'sallt_year'              =>$year,
            'sallt_totalincome'       =>$tincome,
            'sallt_totaldeduction'    =>$tdeduction,
            'sallt_netsalary'         =>$netsal,
            'sallt_status'            =>$status,
            'sallt_type'              =>$saltype,   
            'sallt_paiddate'          =>date('y-m-d'),
            'sallt_creationdate'      =>date('y-m-d'),
            'sallt_creatorid'         =>$this->session->userdata('username'),
            'sallt_updatedate'        =>date('y-m-d'),
            'sallt_modifierid'        =>$this->session->userdata('username'),
        );
        
        $dupcheck = array(
            'sallt_empid'             =>$empid,
            'sallt_month'             =>$month,
            'sallt_year'              =>$year,
            'sallt_type'              =>$saltype 
        );  
        
        $emidexits= $this->sismodel->isduplicatemore('salary_lt',$dupcheck);
        
        if(!$emidexits){
           // echo "empid not exists in salarylt table";
            /* insert record in  salary detail */
           $this->sismodel->insertrec('salary_lt',  $salinst);
            $this->logger->write_logmessage("insert", "data insert in salary table.");
            $this->logger->write_dblogmessage("insert", "data insert in salary table." );
            
        }
        else{
            
            //echo "empid  exists in salarylt table else case";
            $salinst=array(
                'sallt_empid'             =>$empid,
                'sallt_scid'              =>$scid,
                'sallt_uoid'              =>$uoid,
                'sallt_deptid'            =>$deptid,
                'sallt_desigid'           =>$desigid,
                'sallt_sapost'            =>$sapost,
                'sallt_ddoid'             =>$ddoid,
                'sallt_schemeid'          =>$schemeid,
                'sallt_payscaleid'        =>$payscaleid,
                'sallt_bankaccno'         =>$bankaccno,
                'sallt_worktype'          =>$worktype,
                'sallt_emptype'           =>$emptype,
                'sallt_group'             =>$group,
                'sallt_month'             =>$month,
                'sallt_year'              =>$year,
                'sallt_totalincome'       =>$tincome,
                'sallt_totaldeduction'    =>$tdeduction,
                'sallt_netsalary'         =>$netsal,
                'sallt_status'            =>$status,
                'sallt_type'              =>$saltype,  
                //'sallt_paiddate'          =>$paiddate,
                'sallt_updatedate'        =>date('y-m-d'),
                'sallt_modifierid'        =>$this->session->userdata('username'),
            );
            
            /* update record in  salary detail */
            
            $selectfield ="sallt_id";
            $whdata = array('sallt_empid' =>$empid,'sallt_month' =>$month,'sallt_year' =>$year);
            $saldataid= $this->sismodel->get_orderlistspficemore('salary_lt',$selectfield,$whdata,'');
            $this->sismodel->updaterec('salary_lt',  $salinst,'sallt_id',$saldataid[0]->sallt_id);
            $this->logger->write_logmessage("update", "data update in salary table.");
            $this->logger->write_dblogmessage("update", "data update in salary table." ); 
        }   
        
    }
    
    
    public function Defaluttranfr_days($empid,$cmonth,$cyear){
        $sumincome=0;$sumdeduct=0;$tempsumdeduct=0;$tempsumloan=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        //$whdata = array('sh_type' =>'D');
        //$data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L','');
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
        $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
        uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
        $wempsal = array ('uit_staffname'=>$empid);
        $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
        
        $wtype=$stvalemp[0]->uit_emptype;
        $strarray=array();
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray))
        $strarray=$rawarray[0]->shc_salheadid;
        $allowedhead=explode(", ",$strarray);
        
        //print_r($allowedhead);
        
        //print_r($data['incomes']);
        
        //$this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$stvalemp[0]->uit_vactype_from)->empt_id;
        
      //  $strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;
        
        
        //$wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        //$payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        $wtype=$stvalemp[0]->uit_emptype;
        $payscaleid=$stvalemp[0]->uit_paybandid_to;
        
        /*$stffieldsal ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
        $wstfsal = array ('ste_empid'=>$empid,'ste_month'=>$cmonth,'ste_year'=>$cyear);
        $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffieldsal,$wstfsal,'');*/
        $valdays=$this->sismodel->getfromtransto($empid,$cmonth,$cyear);
        
        $cnomonth= date("m",strtotime($cmonth));
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                       // echo "record1====". $record1->sh_id."empid===".$empid;
                        
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                       // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                        //echo "record1====". $record1->sh_id."empid===".$empid."dheadval===".$this->dheadval."finalval====".$finalhval;
                        
                    }
                    else{
                       
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                                            
                        //$this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                       // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                       
                    }//main else without formula
                }
                else{
                    $finalhval=0;   
                }
            
           // $this->getInsertSalarydata($empid,$record1->sh_id,$this->dheadval,$cmonth,$cyear);
            $this->Salarydata_lt($empid,$record1->sh_id,(round($finalhval,2)),NULL,$cmonth,$cyear,'from');
            $sumincome+=$finalhval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_type  =='D'){
                        
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                        }
                        else{
                        
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            
                            //$this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                        }
                        
                        $tempsumdeduct+=$finalhval;
                    }//deduction
                    if($record2->sh_type  =='L'){
                         if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                           // $finalhval=$this->dheadval;
                        }
                        else{
                        
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            //$this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['fromd']);
                            //$finalhval=$this->dheadval;
                        }
                        $tempsumloan+=$finalhval;
                        
                    }//loan
                } 
                else{
                    $finalhval=0; 
                }
            //}
            /* comment intallment part for change in copy method 
                
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$record2->sh_id);
               // $instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan= $this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                
                */
                $installdetl=$this->updateintlmtno($empid,$record2->sh_id);
                
                
                $this->Salarydata_lt($empid,$record2->sh_id,(round($finalhval,2)),$installdetl,$cmonth,$cyear,'from');    
          //  $this->getInsertSalarydata($empid,$record2->sh_id,$this->dheadval,$cmonth,$cyear);
               // $sumdeduct+=$finalhval;
               $sumdeduct=$tempsumdeduct + $tempsumloan;
            }
            
        }
        //$this->SalPolicies_tranfer($empid,$cmonth,$cyear,'from');
             
        $netsalary=$sumincome - $sumdeduct;
        if($netsalary <= 0){
            $netsalary=$sumincome - $tempsumdeduct;  
            $sumdeduct=$tempsumdeduct;
        }
        
        $scid=$stvalemp[0]->uit_scid_from;
        $uoccid=$stvalemp[0]->uit_uoc_from;
        $deptid=$stvalemp[0]->uit_workdept_from;
        $desigid=$stvalemp[0]->uit_desig_from;
        $sopost=$stvalemp[0]->uit_workingpost_from;
        $ddoid=$stvalemp[0]->uit_ddoid_from;
       // $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$stvalemp[0]->uit_scid_from;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
       // $group=$stvalemp[0]->uit_group_to;
        $group=$stvalemp[0]->uit_group_from;
        $vcawtype=$stvalemp[0]->uit_vactype_from;
        /*************************************insert in salary *********************************************/
        $this->Salary_lt($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$wtype,
        $vcawtype,$group,$cmonth,$cyear,(round($sumincome,2)),(round($sumdeduct,2)),(round($netsalary,2)),'process','from');
        
              
    }
    
    /*********************Deafult salary transit days**************/
    
    public function Defaluttranfr_transit($empid,$cmonth,$cyear){
        $sumincome=0;$sumdeduct=0;$tempsumdeduct=0;$tempsumloan=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
       // $data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L','');
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
        
        $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
            uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
        $wempsal = array ('uit_staffname'=>$empid);
        $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
        $wtype=$stvalemp[0]->uit_emptype;  
        //$this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$stvalemp[0]->uit_vactype_from)->empt_id;
        
        //$strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;
        $strarray=array();
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray))
        $strarray=$rawarray[0]->shc_salheadid;
        
        $allowedhead=explode(", ",$strarray);
        
        
                                                                                                                                                        
        $payscaleid=$stvalemp[0]->uit_paybandid_to;
        
       /* $stffieldsal ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
        $wstfsal = array ('ste_empid'=>$empid,'ste_month'=>$cmonth,'ste_year'=>$cyear);
        $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffieldsal,$wstfsal,'');*/
        
        $valdays=$this->sismodel->getfromtransto($empid,$cmonth,$cyear);
        
        $cnomonth= date("m",strtotime($cmonth));
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                       // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                        
                    }
                    else{
                        
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                        //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                       
                       
                    }
                }
                else{
                    $finalhval=0;   
                }
            
           // $this->getInsertSalarydata($empid,$record1->sh_id,$this->dheadval,$cmonth,$cyear);
            $this->Salarydata_lt($empid,$record1->sh_id,(round($finalhval,2)),NULL,$cmonth,$cyear,'transit');
            $sumincome+=$finalhval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_type  =='D'){
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                        }
                        else{
                        
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                        
                            // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                        }
                        $tempsumdeduct+=$finalhval;
                    }
                    if($record2->sh_type  =='L'){
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                            //$finalhval=$this->dheadval;
                        }
                        else{
                        
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                        
                            // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_transit);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['transitd']);
                            //$finalhval=$this->dheadval;
                        }
                        
                        $tempsumloan+=$finalhval;   
                    }
                } 
                else{
                    $finalhval=0; 
                }
                
                /*
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$record2->sh_id);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                
                 */
             
            //}
            $installdetl=$this->updateintlmtno($empid,$record2->sh_id);
            
            $this->Salarydata_lt($empid,$record2->sh_id,(round($finalhval,2)),$installdetl,$cmonth,$cyear,'transit');    
          
            //$sumdeduct+=$finalhval;
            $sumdeduct=$tempsumdeduct + $tempsumloan;
            
            }
            
        }
      //  $this->SalPolicies_tranfer($empid,$cmonth,$cyear,'transit');
      
        $netsalary=$sumincome - $sumdeduct;
        if($netsalary <=0){
            $netsalary=$sumincome - $tempsumdeduct; 
            $sumdeduct=$tempsumdeduct;
        }
        
        $scid=$stvalemp[0]->uit_scid_from;
        $uoccid=$stvalemp[0]->uit_uoc_from;
        $deptid=$stvalemp[0]->uit_workdept_from;
        $desigid=$stvalemp[0]->uit_desig_from;
        $sopost=$stvalemp[0]->uit_workingpost_from;
        $ddoid=$stvalemp[0]->uit_ddoid_from; 
        //$ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$stvalemp[0]->uit_scid_from;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
        $group=$stvalemp[0]->uit_group_from;
        $vcawtype=$stvalemp[0]->uit_vactype_from;
        
        /*************************************insert in salary ********************************************************/
        $this->Salary_lt($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$wtype,
        $vcawtype,$group,$cmonth,$cyear,(round($sumincome,2)),(round($sumdeduct,2)),(round($netsalary,2)),'process','transit');
        
    } 
    
    
    public function Defaluttranfr_dayto($empid,$cmonth,$cyear){
        $sumincome=0;$sumdeduct=0;$tempsumdeduct=0;$tempsumloan=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        //data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L','');
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
        $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
       // $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$emptype)->empt_id;
        
       // $strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;
        $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray))
        $strarray=$rawarray[0]->shc_salheadid;
        
        $allowedhead=explode(", ",$strarray);
        
       
        $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
       
        /*$stffieldsal ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
        $wstfsal = array ('ste_empid'=>$empid,'ste_month'=>$cmonth,'ste_year'=>$cyear);
        $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffieldsal,$wstfsal,'');*/
        
        $valdays=$this->sismodel->getfromtransto($empid,$cmonth,$cyear);
        
        $cnomonth= date("m",strtotime($cmonth));
        
        /*$totaldt=$stfvalsal[0]->ste_days + $stfvalsal[0]->ste_transit; 
        $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
        $todays= $nodaysmonth - $totaldt; */
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                        //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                        
                    }
                    else{
                        
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                       // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                       
                    }
                }
                else{
                    $finalhval=0;   
                }
            
           // $this->getInsertSalarydata($empid,$record1->sh_id,$this->dheadval,$cmonth,$cyear);
            $this->Salarydata_lt($empid,$record1->sh_id,(round($finalhval,2)),NULL,$cmonth,$cyear,'to');
            $sumincome+=$finalhval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_type  =='D'){ 
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                        }
                        else{
                       
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                           
                            // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                        }
                        $tempsumdeduct+=$finalhval;
                    }
                    if($record2->sh_type  =='L'){
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                            //$finalhval=$this->dheadval;
                        }
                        else{
                       
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            // $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$todays);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$valdays['todaysd']);
                           // $finalhval=$this->dheadval;
                        }
                        
                        $tempsumloan+=$finalhval;       
                    }
                } 
                else{
                    $finalhval=0; 
                }
                /*
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$record2->sh_id);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                 
                 */
                
            //}
            $installdetl=$this->updateintlmtno($empid,$record2->sh_id);
                
            $this->Salarydata_lt($empid,$record2->sh_id,(round($finalhval,2)),$installdetl,$cmonth,$cyear,'to');    
          
            //$sumdeduct+=$finalhval;
            $sumdeduct=$tempsumdeduct + $tempsumloan;
            
            }
            
        }
       //$this->SalPolicies_tranfer($empid,$cmonth,$cyear,'to');
       
        $netsalary=$sumincome - $sumdeduct;
        if($netsalary<=0){
            $netsalary=$sumincome - $tempsumdeduct; 
            $sumdeduct=$tempsumdeduct;
        }
        
        $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
        $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
        $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
        $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
        $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
        $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
        $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
        
        /*************************************insert in salary ********************************************************/
        $this->Salary_lt($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$wtype,
        $emptype,$group,$cmonth,$cyear,(round($sumincome,2)),(round($sumdeduct,2)),(round($netsalary,2)),'process','to');
   
    } 
    
    
    public function transfersalaryslip(){
        
        $empid=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $year=$this->uri->segment(5);
        $case=$this->uri->segment(6);
     
        $this->emptnt=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        
        $selectfield ="sh_id, sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
        $whorder = "sh_id asc, sh_name asc";
       
	$whdata = array('sh_type' =>'I');// 'sh_tnt' => $this->emptnt,'sh_tnt' => NULL);
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $data['deduction']=array_merge($data['ded'], $data['loans']);
      //  $data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L',$whorder);
       // print_r($data['deduction']);
       
        
        $this->emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
        $this->emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code ','emp_id',$empid)->emp_type_code;
        $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$this->emptype)->empt_id;
        
        
        $this->wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype ','emp_id',$empid)->emp_worktype;
        $this->paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $this->wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        $strarray=$rawarray[0]->shc_salheadid;
        
        $data['allowedhead']=explode(", ",$strarray);
        
               
        if(isset($_POST['upsalhdval'])){
         
            $ttcase=$this->input->post('tcase', TRUE);
            $empid=$this->uri->segment(3,0);
            $month=$this->uri->segment(4,0);
            $year=$this->uri->segment(5,0);
            $tcase=$this->uri->segment(6,0);
            $data['empid']=$empid;
            $data['month']=$month;
            $data['year']=$year;
            $data['case']=$case;
           
            $tcount = $this->input->post('totalcount', TRUE);
            $tded = $this->input->post('totalded', TRUE);
           
            $totalincome=0;
            $totaldeduction = 0;
            $netpay = 0;
            /***************************Incomes for the all three parts(from, transit and to)************************************/
                        
            for ($i=0; $i<$tcount ;$i++){
                $headidin = $this->input->post('sheadidin'.$i, TRUE);
               
                $headval = $this->input->post('headamtI'.$i, TRUE);
               
                $saldata = array(
                
                    'saldlt_empid'      =>$empid,
                    'saldlt_sheadid'    =>$headidin,
                    'saldlt_shamount'   =>$headval,
                    'saldlt_installment'  => NULL,
                    'saldlt_month'      =>$month,
                    'saldlt_year'       =>$year,
                    'saldlt_type'       =>$ttcase,   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
                $totalincome+=$headval;
            } //tcount
            /*******************************Deductions***********************************/
            for ($j=0; $j<$tded ;$j++){
                
                $headidD = $this->input->post('sheadidded'.$j, TRUE);
                $headvald = $this->input->post('headamtD'.$j, TRUE);
                
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$headidD);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                
                $saldata = array(
                
                    'saldlt_empid'        =>$empid,
                    'saldlt_sheadid'      =>$headidD,
                    'saldlt_shamount'     => $headvald,
                    'saldlt_installment'  => $installdetl,
                    'saldlt_month'        =>$month,
                    'saldlt_year'         =>$year,
                    'saldlt_type'         =>$ttcase,   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
               
                $totaldeduction+=$headvald;
                
            }//totalcount 
            
            $netpay=$totalincome - $totaldeduction;
            if($ttcase == 'from'|| $ttcase == 'transit'){
            
                $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
                    uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
                $wempsal = array ('uit_staffname'=>$empid);
                $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
                
                $scid=$stvalemp[0]->uit_scid_from;
                $uoccid=$stvalemp[0]->uit_uoc_from;
                $deptid=$stvalemp[0]->uit_workdept_from;
                $desigid=$stvalemp[0]->uit_desig_from;
                $sopost=$stvalemp[0]->uit_workingpost_from;
                $ddoid=$stvalemp[0]->uit_ddoid_from;
               // $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
                $schmid=$stvalemp[0]->uit_scid_from;
                $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
                $group=$stvalemp[0]->uit_group_from;
                $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
                $wtype=$stvalemp[0]->uit_emptype; 
                $emptype=$stvalemp[0]->uit_vactype_from;
                
            }
            else{
            
                $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
                $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
                $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
                $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
                $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
                $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
                $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
                $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
                $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
                $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
                $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
                $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
            }
            
            $saldata1 = array(
               'sallt_empid'             =>$empid,
               'sallt_scid'              =>$scid,
               'sallt_uoid'              =>$uoccid,
               'sallt_deptid'            =>$deptid,
               'sallt_desigid'           =>$desigid,
               'sallt_sapost'            =>$sopost,
               'sallt_ddoid'             =>$ddoid,
               'sallt_schemeid'          =>$schmid,
               'sallt_payscaleid'        =>$payscaleid,
               'sallt_bankaccno'         =>$bankaccno,
               'sallt_worktype'          =>$wtype,
               'sallt_emptype'           =>$emptype,
               'sallt_group'             =>$group,
               'sallt_month'             =>$month,
               'sallt_year'              =>$year,
               'sallt_totalincome'       =>$totalincome,
               'sallt_totaldeduction'    =>$totaldeduction,
               'sallt_netsalary'         =>$netpay,
               'sallt_status'            =>'paid',
               'sallt_type'              =>$ttcase,
               'sallt_paiddate'          =>date('y-m-d'),
               'sallt_creatorid'         =>$this->session->userdata('username'),
               'sallt_creationdate'       =>date('y-m-d'),
               'sallt_updatedate'        =>date('y-m-d'),    
               'sallt_modifierid'        =>$this->session->userdata('username'),
           
            );
              
            if (!$upsaldataflag)
            {
                $this->logger->write_logmessage("insert","Trying to add  salary data head wise", "  salary data head wise value is not added ".$this->emppfno);
                $this->logger->write_dblogmessage("insert","Trying to add salary data head wise ", " salary data head wise value is not added ".$this->emppfno);
                $this->session->set_flashdata('err_message','Error in  salary data head wise value - '  , 'error');
                //redirect('setup3redesign/translipredesign',$data);
                redirect('setup3redesign/transfersalaryslip',$data);
            }
            else{
            
                $upsalaryflag = $this->sismodel->insertrec('salary_lt', $saldata1);
                $this->logger->write_logmessage("insert"," salary data head wise value  ", " salary data head wise value added  successfully...");
                $this->logger->write_dblogmessage("insert"," salary data head wise value ", "salary data head wise value added  successfully...");
                $this->session->set_flashdata("success", " salary data ( ".$ttcase." days ) updated successfully  PF NO [ " .$this->emppfno. " ]");
                
            /*************************sending mail with Attachment Salaryslip********************************************/
                if($ttcase=='to'){
                    $this->mailmodel->mailAttachment($empid,$this->emppfno,$month,$year,$case);
                }
                
               // redirect("setup3redesign/translipredesign/".$empid.'/'.$month.'/'.$year.'/'.$case,$data);
                redirect("setup3redesign/transfersalaryslip/".$empid.'/'.$month.'/'.$year.'/'.$case,$data);
            }
            
           
        }//for button
        
        
        $this->load->view('setup3/translipredesign',$data);   
    }
    
    public function cal_CCAgrade($empid) {
       
       // $pbid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        $paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
       
       // $sfbp="shdv_defaultvalue";
       // $wdatabp = array('shdv_paybandid'=>$pbid,'shdv_salheadid' =>1);
        $bpamt='';
        $sfbp="seh_headamount";
        $wdatabp = array('seh_empid' =>$empid,'seh_headid'=>1);
        //$headbp= $this->sismodel->get_maxvalue('salary_earnings_head',$sfbp,$wdatabp,'');
        $headbp=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfbp,'seh_modifydate=',$wdatabp);
        if(!empty($headbp)){           
            $bpamt=$headbp[0]->seh_headamount;
	}
        $ccaamt=$this->sismodel->getcca_amount($bpamt,$paycomm);
        $ccagrade=$this->sismodel->get_listspfic1('employee_master_support','ems_ccagrade','ems_empid',$empid);
        //echo"ujkjkjk==".$ccagrade;
        if(!empty($ccagrade)){
            $ccagrade= $ccagrade->ems_ccagrade;
            $sfield="cca_amount";
            $wdata = array('cca_payrange'=>$ccaamt[0],'cca_paycomm' =>$paycomm,'cca_gradeid' =>$ccagrade);  
            $headvalc= $this->sismodel->get_orderlistspficemore('ccaallowance_calculation',$sfield,$wdata,'');  
            if(!empty($headvalc)){
                $headvalcca=$headvalc[0]->cca_amount;
                $this->dheadval=$headvalcca;
                //echo"headval=ccca in get cca formula===".$this->dheadval; 
            }
            else{
                $this->dheadval=0;
            }
        }
        else{
            $this->dheadval=0;
        }
        return $this->dheadval; 
       // }//closer of cca    
    }//function closer
    public function cal_HRAgrade($empid) {
       
      //  $pbid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        $paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm; 
        
      //  $sfbp="shdv_defaultvalue";
      //  $wdatabp = array('shdv_paybandid'=>$pbid,'shdv_salheadid' =>1);
        $sfbp="seh_headamount";
        $wdatabp = array('seh_empid' =>$empid,'seh_headid'=>1);
        //$headbp= $this->sismodel->get_maxvalue('salary_earnings_head',$sfbp,$wdatabp,'');
        $headbp=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfbp,'seh_modifydate=',$wdatabp);
        if(!empty($headbp)){           
        $bpamt=$headbp[0]->seh_headamount;
       } 
        $rfhragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_erfq','ems_empid',$empid);
        if(!empty($rfhragrade)&&($rfhragrade->ems_erfq == 'yes')){
            $hragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_erfqhra','ems_empid',$empid);
        }
        else{
            $hragrade=$this->sismodel->get_listspfic1('employee_master_support','ems_hragrade','ems_empid',$empid);                     
        }
        $hraamt=$this->sismodel->gethra_amount($bpamt,$paycomm);
        
        if((!empty($hragrade)) || (!empty($rfhragrade))){
            if($rfhragrade->ems_erfq == 'yes'){
                $hragrade= $hragrade->ems_erfqhra;   
                $sfield="rfh_amount";
                $wdata = array('rfh_payrange'=>$hraamt[0],'rfh_paycomm' =>$paycomm,'rfh_gradeid' =>$hragrade);
               // $wdata = array('hg_payscaleid' =>$pbid,'hg_workingtype' =>$worktype,'hg_gradeid' =>$hragrade);
                $headvalh= $this->sismodel->get_orderlistspficemore('rent_free_hra',$sfield,$wdata,'');  
                if(!empty($headvalh)){
                    $headvalhra=$headvalh[0]->rfh_amount;
                    $this->dheadval=$headvalhra; 
                }
                else{
                    $this->dheadval=0;    
                    }
            }//closer of rfg
            else{
                $hragrade= $hragrade->ems_hragrade;
                $sfield="hg_amount";
                $wdata = array('hg_payrange'=>$hraamt[0],'hg_paycomm' =>$paycomm,'hg_gradeid' =>$hragrade);
                // $wdata = array('hg_payscaleid' =>$pbid,'hg_workingtype' =>$worktype,'hg_gradeid' =>$hragrade);
                $headvalh= $this->sismodel->get_orderlistspficemore('hra_grade',$sfield,$wdata,'');  
                if(!empty($headvalh)){
                    $headvalhra=$headvalh[0]->hg_amount;
                    $this->dheadval=$headvalhra; 
                    //    echo "seemain hra". $finalval;
                }
                else{
                    $this->dheadval=0;    
                }
            }//close of normal hra
        }
        else{
            $this->dheadval=0;
        }
        return $this->dheadval; 
    }//closer of function
    public function cal_RentRecvory($empid) {
      
    //    $pbid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        $paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm; 
        
        $rentgrade=$this->sismodel->get_listspfic1('employee_master_support','ems_rentgrade','ems_empid',$empid);
        if(!empty($rentgrade)){ 
            $rentgradeid=$rentgrade->ems_rentgrade;
            
            /*$sfbp="shdv_defaultvalue";
            $wdatabp = array('shdv_paybandid'=>$pbid,'shdv_salheadid' =>1);
            $headbp= $this->sismodel->get_orderlistspficemore('salaryhead_defaultvalue',$sfbp,$wdatabp,'');
            $bpamt=$headbp[0]->shdv_defaultvalue;*/
            
            $sfbp="seh_headamount";
            $wdatabp = array('seh_empid' =>$empid,'seh_headid'=>1);
            //$headbp=$this->sismodel->get_maxvalue('salary_earnings_head',$sfbp,$wdatabp,'');
            $headbp=$this->sismodel->get_rundualquery1('max(seh_modifydate)','salary_earnings_head',$sfbp,'seh_modifydate=',$wdatabp);
	        if(!empty($headbp)){           
        	    $bpamt=$headbp[0]->seh_headamount;
		}
            
            $hraamt=$this->sismodel->gethraper_amount($bpamt,$paycomm);
                                        
            $sfield="rr_percentage";
            $wdata = array('rr_payrange'=>$hraamt[0],'rr_paycomm' =>$paycomm,'rr_gradeid' =>$rentgradeid);
            $headvalh= $this->sismodel->get_orderlistspficemore('rent_recovery',$sfield,$wdata,'');  
            if(!empty($headvalh)){
                $rentrper=$headvalh[0]->rr_percentage;
                $rawrrcal=$bpamt*$rentrper;
                $headvalhra=$rawrrcal;
		//get the hra and add to rent recovery
		$hraforrent=$this->cal_HRAgrade($empid);
                //get cca grade from payrollprofile
                $this->dheadval=$headvalhra+$hraforrent; 
            }
            else{
                $this->dheadval=0;  
            }
        }
        else{
            $this->dheadval=0;   
        } 
        return $this->dheadval; 
    }//function close
    
   /* public function SalPolicies_tranfer($empid,$cmonth,$cyear,$sdtype){
        $licprdpli = array(
            "LIC1" => "LIC1",
            "LIC2" => "LIC2",
            "LIC3" => "LIC3",
            "LIC4" => "LIC4",
            "LIC5" => "LIC5",
            "PRD1" => "PRD1",
            "PRD2" => "PRD2",
            "PRD3" => "PRD3",
            "PLI1" => "PLI1",
            "PLI2" => "PLI2",
        );
        foreach ($licprdpli as $lpdpi) {
            $this->Salarydata_lt($empid,$lpdpi,0,$cmonth,$cyear,$sdtype);
        }
    } */
    /*******************************salaryslip for leave cases************************************************/
    public function leavesalaryslip(){
        
        
        $empid=$this->uri->segment(3);
        $this->emptnt=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $selectfield ="sh_id, sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
        $whorder = "sh_id asc, sh_name asc";
       
	$whdata = array('sh_type' =>'I');// 'sh_tnt' => $this->emptnt,'sh_tnt' => NULL);
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
       // $data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L',$whorder);
       
        // $whdata = array('sh_type' =>'D');
        //$data['deduction'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        
        $this->emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
        $this->emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code ','emp_id',$empid)->emp_type_code;
        $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$this->emptype)->empt_id;
        
        $this->wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype ','emp_id',$empid)->emp_worktype;
        $this->paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        
        
        
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $this->wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray)){           
	        $strarray=$rawarray[0]->shc_salheadid;
	}
        
        //s$strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;

        $data['allowedhead']=explode(", ",$strarray);
 
        if(isset($_POST['upsalhdval'])){
       
            $tcount = $this->input->post('totalcount', TRUE);
            $tded = $this->input->post('totalded', TRUE);
           
            $month=$this->uri->segment(4);
            $year=$this->uri->segment(5);
            $totalincome=0;
            $totaldeduction = 0;
            $netpay = 0;
            
            /***************************Incomes for the all three parts(from, transit and to)******************/
            
            for ($i=0; $i<$tcount ;$i++){
                $headidin = $this->input->post('sheadidin'.$i, TRUE);
                $headval = $this->input->post('headamtI'.$i, TRUE);
               
                $saldata = array(
                
                    'saldlt_empid'      =>$empid,
                    'saldlt_sheadid'    =>$headidin,
                    'saldlt_shamount'   =>$headval,
                    'sald_installment'  => NULL,
                    'saldlt_month'      =>$month,
                    'saldlt_year'       =>$year,
                    'saldlt_type'       =>'leave',   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
                $totalincome+=$headval;
            } //tcount
            /*******************************Deductions***********************************/
            for ($j=0; $j<$tded ;$j++){
                
                $headidD = $this->input->post('sheadidded'.$j, TRUE);
                $headvald = $this->input->post('headamtD'.$j, TRUE);
                
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$headidD);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan= $this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                $saldata = array(
                
                    'saldlt_empid'      =>$empid,
                    'saldlt_sheadid'    =>$headidD,
                    'saldlt_shamount'   => $headvald,
                    'sald_installment'  =>$installdetl,
                    'saldlt_month'      =>$month,
                    'saldlt_year'       =>$year,
                    'saldlt_type'       =>'leave',   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
               
                $totaldeduction+=$headvald;
                
            }//totalcount 
            
            $netpay=$totalincome - $totaldeduction;
                 
            $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
            $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
            $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
            $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
            $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
            $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
            $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
            $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
            $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
            $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
            $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
            $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
          
            
            $saldata1 = array(
               'sallt_empid'             =>$empid,
               'sallt_scid'              =>$scid,
               'sallt_uoid'              =>$uoccid,
               'sallt_deptid'            =>$deptid,
               'sallt_desigid'           =>$desigid,
               'sallt_sapost'            =>$sopost,
               'sallt_ddoid'             =>$ddoid,
               'sallt_schemeid'          =>$schmid,
               'sallt_payscaleid'        =>$payscaleid,
               'sallt_bankaccno'         =>$bankaccno,
               'sallt_worktype'          =>$wtype,
               'sallt_emptype'           =>$emptype,
               'sallt_group'             =>$group,
               'sallt_month'             =>$month,
               'sallt_year'              =>$year,
               'sallt_totalincome'       =>$totalincome,
               'sallt_totaldeduction'    =>$totaldeduction,
               'sallt_netsalary'         =>$netpay,
               'sallt_status'            =>'paid',
               'sallt_type'              =>'leave',
               'sallt_paiddate'          =>date('y-m-d'),
               'sallt_creatorid'         =>$this->session->userdata('username'),
               'sallt_creationdate'       =>date('y-m-d'),
               'sallt_updatedate'        =>date('y-m-d'),    
               'sallt_modifierid'        =>$this->session->userdata('username'),
           
            );
              
            if (!$upsaldataflag)
            {
                $this->logger->write_logmessage("insert","Trying to add  salary data head wise", "  salary data head wise value is not added ".$this->emppfno);
                $this->logger->write_dblogmessage("insert","Trying to add salary data head wise ", " salary data head wise value is not added ".$this->emppfno);
                $this->session->set_flashdata('err_message','Error in  salary data head wise value - '  , 'error');
                redirect('setup3redesign/salaryprocess');
            }
            else{
            
                $upsalaryflag = $this->sismodel->insertrec('salary_lt', $saldata1);
                $this->logger->write_logmessage("insert"," salary data head wise value  ", " salary data head wise value added  successfully...");
                $this->logger->write_dblogmessage("insert"," salary data head wise value ", "salary data head wise value added  successfully...");
                $this->session->set_flashdata("success", "salary data updated successfully... PF NO [ " .$this->emppfno. " ]");
                
                /*************************sending mail with Attachment Salaryslip********************************************/
                
                $uempid=$this->uri->segment(3);
                $umonth=$this->uri->segment(4);
                $uyear=$this->uri->segment(5);
                $ucase=$this->uri->segment(6);
                
                $this->mailmodel->mailAttachment($uempid,$this->emppfno,$umonth,$uyear,$ucase);
                
                redirect('setup3redesign/salaryprocess');
            }
           
        }//for button
        
        $this->load->view('setup3/leaveslipredesign',$data);   
        
    }
    
    /*****************************Default employee leave salary case**********************************/
    
    public function Defalutleavesalary($empid,$cmonth,$cyear){
        $sumincome=0;$sumdeduct=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
       
        //$whdata = array('sh_type' =>'D');
        //$data['deduction'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
        $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
        $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$emptype)->empt_id;
        
        $this->paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
        //$strarray=$this->sismodel->get_listspfic1('salaryhead_configuration','shc_salheadid','shc_emptypeid',$this->emptypeid)->shc_salheadid;
        
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=>$wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray)){
            $strarray=$rawarray[0]->shc_salheadid;
        }
        $allowedhead=explode(", ",$strarray);
        
        
        $stffieldsal ="sle_pal,sle_eol";
        $wstfsal = array ('sle_empid'=>$empid,'sle_month'=>$cmonth,'sle_year'=>$cyear);
        $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_leave_entry',$stffieldsal,$wstfsal,'');
        
        $cnomonth= date("m",strtotime($cmonth));
        
        $totaldl=$stfvalsal[0]->sle_pal + $stfvalsal[0]->sle_eol; 
        $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$cyear);
        $leftdays= $nodaysmonth - $totaldl; 
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                        
                    }
                    else{
                     
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                       
                    }
                
                }
                else{
                    $finalhval=0;   
                }
            
           // $this->getInsertSalarydata($empid,$record1->sh_id,$this->dheadval,$cmonth,$cyear);
            $this->Salarydata_lt($empid,$record1->sh_id,(round($finalhval,2)),NULL,$cmonth,$cyear,'leave');
            $sumincome+=$finalhval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_type  =='D'){
                        if($record2->sh_calc_type == 'Y'){
                            $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                        }
                        else{
                       
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                           
                            $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                        }
                    }  
                    if($record2->sh_type  =='L'){
                        if($record2->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                        //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                        $finalhval=$this->dheadval;
                        
                        }
                        else{
                        
                            $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                            //$finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$leftdays);
                            $finalhval=$this->dheadval;
                       
                        }
                    }
                } 
                else{
                    $finalhval=0; 
                }
            /*    
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$record2->sh_id);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }    
           
            */  
                
            $installdetl=$this->updateintlmtno($empid,$record2->sh_id);    
            $this->Salarydata_lt($empid,$record2->sh_id,(round($finalhval,2)),$installdetl,$cmonth,$cyear,'leave');    
            $sumdeduct+=$finalhval;
            
            }
            
        }
       // $this->SalPolicies_tranfer($empid,$cmonth,$cyear,'leave');
       
        $netsalary=$sumincome - $sumdeduct;
        
        $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
        $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
        $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
        $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
        $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
        $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
        $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
        
        /*************************************insert in salary ********************************************************/
        $this->Salary_lt($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$wtype,
        $emptype,$group,$cmonth,$cyear,(round($sumincome,2)),(round($sumdeduct,2)),(round($netsalary,2)),'process','leave');
   
    } 
    
    /**
    * Get Download PDF File for salaryslipcopy
    * @return Response
    */
    public function salaryslipcopy($empid){
        $empid=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $year=$this->uri->segment(5);
        $case=$this->uri->segment(6);
              
        $spec_data['empid'] = $empid;
        $spec_data['month'] = $month;
        $spec_data['year'] = $year;   
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $spec_data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'D');
        $spec_data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $spec_data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $spec_data['deduction']=array_merge( $spec_data['ded'],  $spec_data['loans']);
             
        $this->load->library('pdf');
        $this->pdf->set_paper("A4", "portrait");
        if($case == "transcase"){
            //$this->pdf->load_view('setup3/salaryslipcopy2',$spec_data);
            $this->pdf->load_view('setup3/salaryslipcopy2new',$spec_data);
        }
        else{
            //$this->pdf->load_view('setup3/salaryslipcopy',$spec_data);  
            $this->pdf->load_view('setup3/salaryslipcopynew',$spec_data);
        }
        $this->pdf->render();
        $this->pdf->stream("salaryslipcopy.pdf");
        
    }
    public function updateintlmtno($empid,$shid){
        $intallno=0;
        $empexist=$this->sismodel->isduplicate('salary_data','sald_empid',$empid);
        if(!$empexist){
            $selectfield="slh_id";
            $whdata = array('slh_empid'=>$empid,'slh_headid' =>$shid);
            $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
            if(!empty($instloan[0]->slh_id)){
                $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                if($totalinstall!= 0){
                    $intallno=$installno +1 ."-".$totalinstall;
                }
                else if($installno == $totalinstall){
                    $intallno="0-0";
                }
                else{
                    $intallno="0-0";
                }
            }
            else{
                $intallno =NULL;   
            }
        }
        else{
            $intstr ='';
            $maxslhid='';
 //           $selectfield ="sald_installment";
            $wdata2=array('sald_empid' =>$empid,'sald_sheadid'=>$shid);
            $maxrecord=$this->sismodel->get_maxvalue('salary_data','sald_id',$wdata2);
            if(!empty($maxrecord)){
                $maxslhid=$maxrecord[0]->sald_id;
		$intstr=$this->sismodel->get_listspfic1('salary_data','sald_installment','sald_id',$maxslhid)->sald_installment;
//                $intstr=$maxrecord[0]->sald_installment;
         //    print_r("empid ".$empid. " installment".$intstr); 
            }
            if(!empty($intstr)){
                $intallstr=explode("-",$intstr);
                if(((strcmp($intstr,"0-0")) != 0)&&($intallstr[0] != $intallstr[1])){
            //        echo "case1";
                    $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                }
                else if(($intallstr[0] == $intallstr[1]) && (strcmp($intstr,"0-0")) != 0){
              //      echo "case2";
                    $this->updatecreateintlmno($empid,$shid,$intallstr[0],$intallstr[1]);
                    $intallno="0-0"; 
                }
                else{
                //    echo "case3";
                    $selectfield="slh_id";
                    $whdata = array('slh_empid'=>$empid,'slh_headid' =>$shid);
                    $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                    if(!empty($instloan[0]->slh_id)){
                        $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall;
                        $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                        if($totalinstall!= 0){
                            $intallno=$installno +1 ."-".$totalinstall;
                        }
                    }
                }
            }
            else{
                $selectfield="slh_id";
            	$whdata = array('slh_empid'=>$empid,'slh_headid' =>$shid);
            	$instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
            	if(!empty($instloan[0]->slh_id)){
                $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall;
                $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
		if($totalinstall!= 0){
                    $intallno=$installno +1 ."-".$totalinstall;
                }else{
			$maxslhid=$maxrecord[0]->sald_id;
                	$intallno=$this->sismodel->get_listspfic1('salary_data','sald_installment','sald_id',$maxslhid)->sald_installment;
                	//$intallno=$maxrecord[0]->sald_installment;  
		}
            }
           } 
        }
        return $intallno;    
    }

    public function updatecreateintlmno($empid,$shid,$instno,$ttlinstno){
        $selectfield ="slh_id";
        $wdata=array('slh_empid' =>$empid,'slh_headid'=>$shid);
        $maxrecord=$this->sismodel->get_maxvalue('salary_loan_head','slh_id',$wdata);
        $maxhid='';
        if(!empty($maxrecord)){
            $maxhid=$maxrecord[0]->slh_id;
              
        }
        $edit_data = array(
            'slh_totalintall'   =>$ttlinstno,
            'slh_intallmentno'  =>$instno,
            'slh_modifier'      =>$this->session->userdata('username'),
            'slh_modifydate'    =>date('y-m-d')
                    
        );
        $editslhflag=$this->sismodel->updaterec('salary_loan_head', $edit_data, 'slh_id',$maxhid);
        if (!$editslhflag){ 
            $logmessage='problem in updation salary loan head ';
            $this->logger->write_logmessage("error","salary loan head  error", "update Salary loan head. $logmessage ");
            $this->logger->write_dblogmessage("error","salary loan head error", "update salary loan head formula. $logmessage ");
            $this->session->set_flashdata('err_message','Error in updating salary loan head - ' . $logmessage . '.', 'error');
            //$this->load->view('setup3redesign/salaryprocess', $edit_data);
        }
        $headname=$this->sismodel->get_listspfic1('salary_head','sh_name','sh_id',$shid)->sh_name; 
        $cmonth = date('M');
        $cyear= date("Y");
        $loandata = array(
            'slh_empid'                =>$empid,
            'slh_headid'               =>$shid,
            'slh_headname'             =>$headname,
            'slh_headno'               =>'',
            'slh_headamount'           =>0,
            'slh_totalintall'          =>0,
            'slh_intallmentno'         =>0,
            'slh_installamount'        =>0,
            'slh_month'                =>$cmonth,
            'slh_year'                 =>$cyear,
            'slh_creator'              =>$this->session->userdata('username'),
            'slh_createdate'           =>date('y-m-d'),
            'slh_modifier'             =>$this->session->userdata('username'),
            'slh_modifydate'           =>date('y-m-d'),

        );
        $loanentflag= $this->sismodel->insertrec('salary_loan_head', $loandata);
                
    }
    
    public function tlsalaryslip(){
        
        $empid=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $year=$this->uri->segment(5);
        $case=$this->uri->segment(6);
     
        $this->emptnt=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        
        $selectfield ="sh_id, sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
        $whorder = "sh_id asc, sh_name asc";
       
	$whdata = array('sh_type' =>'I');// 'sh_tnt' => $this->emptnt,'sh_tnt' => NULL);
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,$whorder);
        $data['deduction']=array_merge($data['ded'], $data['loans']);
      //  $data['deduction'] = $this->sismodel->get_orderlistspficemoreorwh('salary_head',$selectfield,'','sh_type','D,L',$whorder);
       // print_r($data['deduction']);
       
        
        $this->emppfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$empid)->emp_code;
        $this->emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code ','emp_id',$empid)->emp_type_code;
        $this->emptypeid=$this->sismodel->get_listspfic1('employee_type','empt_id','empt_name',$this->emptype)->empt_id;
        
        
        $this->wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype ','emp_id',$empid)->emp_worktype;
        $this->paycomm=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $this->wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        $strarray=$rawarray[0]->shc_salheadid;
        
        $data['allowedhead']=explode(", ",$strarray);
        
               
        if(isset($_POST['upsalhdval'])){
         
            $ttcase=$this->input->post('tcase', TRUE);
            $empid=$this->uri->segment(3,0);
            $month=$this->uri->segment(4,0);
            $year=$this->uri->segment(5,0);
            $tcase=$this->uri->segment(6,0);
            $data['empid']=$empid;
            $data['month']=$month;
            $data['year']=$year;
            $data['case']=$case;
           
            $tcount = $this->input->post('totalcount', TRUE);
            $tded = $this->input->post('totalded', TRUE);
           
            $totalincome=0;
            $totaldeduction = 0;
            $netpay = 0;
            /***************************Incomes for the all three parts(from, transit and to)************************************/
                        
            for ($i=0; $i<$tcount ;$i++){
                $headidin = $this->input->post('sheadidin'.$i, TRUE);
               
                $headval = $this->input->post('headamtI'.$i, TRUE);
               
                $saldata = array(
                
                    'saldlt_empid'      =>$empid,
                    'saldlt_sheadid'    =>$headidin,
                    'saldlt_shamount'   =>$headval,
                    'saldlt_installment'  => NULL,
                    'saldlt_month'      =>$month,
                    'saldlt_year'       =>$year,
                    'saldlt_type'       =>$ttcase,   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
                $totalincome+=$headval;
            } //tcount
            /*******************************Deductions***********************************/
            for ($j=0; $j<$tded ;$j++){
                
                $headidD = $this->input->post('sheadidded'.$j, TRUE);
                $headvald = $this->input->post('headamtD'.$j, TRUE);
                
                $selectfield="slh_id";
                $whdata = array('slh_empid'=>$empid,'slh_headid' =>$headidD);
                //$instloan= $this->sismodel->get_maxvalue('salary_loan_head',$selectfield,$whdata,'');
                $instloan=$this->sismodel->get_rundualquery1('max(slh_modifydate)','salary_loan_head',$selectfield,'slh_modifydate=',$whdata);
                
                if(!empty($instloan[0]->slh_id)){
                    $totalinstall=$this->sismodel->get_listspfic1('salary_loan_head','slh_totalintall','slh_id',$instloan[0]->slh_id)->slh_totalintall; 
                    $installno=$this->sismodel->get_listspfic1('salary_loan_head','slh_intallmentno','slh_id',$instloan[0]->slh_id)->slh_intallmentno;
                    //$installdetl=$installno."-".$totalinstall;
                    if($totalinstall!= 0){
                        //if((strcmp($intstr,"0-0")) != 0){
                        $installdetl=$installno +1 ."-".$totalinstall;
                       // $intallno=$intallstr[0]+1 ."-".$intallstr[1];
                    }
                    else{
                        $installdetl="0-0";
                    }
                    
                }
                else{
                    $installdetl =NULL;   
                }
                
                $saldata = array(
                
                    'saldlt_empid'        =>$empid,
                    'saldlt_sheadid'      =>$headidD,
                    'saldlt_shamount'     => $headvald,
                    'saldlt_installment'  => $installdetl,
                    'saldlt_month'        =>$month,
                    'saldlt_year'         =>$year,
                    'saldlt_type'         =>$ttcase,   
                
                );
                $upsaldataflag = $this->sismodel->insertrec('salarydata_lt', $saldata);
               
                $totaldeduction+=$headvald;
                
            }//totalcount 
            
            $netpay=$totalincome - $totaldeduction;
            if($ttcase == 'from'|| $ttcase == 'transit'){
            
                $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
                    uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
                $wempsal = array ('uit_staffname'=>$empid);
                $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
                
                $scid=$stvalemp[0]->uit_scid_from;
                $uoccid=$stvalemp[0]->uit_uoc_from;
                $deptid=$stvalemp[0]->uit_workdept_from;
                $desigid=$stvalemp[0]->uit_desig_from;
                $sopost=$stvalemp[0]->uit_workingpost_from;
                $ddoid=$stvalemp[0]->uit_ddoid_from;
               // $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
                $schmid=$stvalemp[0]->uit_scid_from;
                $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
                $group=$stvalemp[0]->uit_group_from;
                $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
                $wtype=$stvalemp[0]->uit_emptype; 
                $emptype=$stvalemp[0]->uit_vactype_from;
                
            }
            else{
            
                $scid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$empid)->emp_scid;
                $uoccid=$this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$empid)->emp_uocid;
                $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$empid)->emp_dept_code;
                $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$empid)->emp_desig_code;
                $sopost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$empid)->emp_post;
                $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
                $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$empid)->emp_schemeid;
                $payscaleid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$empid)->emp_salary_grade;
                $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
                $wtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
                $emptype=$this->sismodel->get_listspfic1('employee_master','emp_type_code','emp_id',$empid)->emp_type_code;
                $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$empid)->emp_group;
            }
            
            $saldata1 = array(
               'sallt_empid'             =>$empid,
               'sallt_scid'              =>$scid,
               'sallt_uoid'              =>$uoccid,
               'sallt_deptid'            =>$deptid,
               'sallt_desigid'           =>$desigid,
               'sallt_sapost'            =>$sopost,
               'sallt_ddoid'             =>$ddoid,
               'sallt_schemeid'          =>$schmid,
               'sallt_payscaleid'        =>$payscaleid,
               'sallt_bankaccno'         =>$bankaccno,
               'sallt_worktype'          =>$wtype,
               'sallt_emptype'           =>$emptype,
               'sallt_group'             =>$group,
               'sallt_month'             =>$month,
               'sallt_year'              =>$year,
               'sallt_totalincome'       =>$totalincome,
               'sallt_totaldeduction'    =>$totaldeduction,
               'sallt_netsalary'         =>$netpay,
               'sallt_status'            =>'paid',
               'sallt_type'              =>$ttcase,
               'sallt_paiddate'          =>date('y-m-d'),
               'sallt_creatorid'         =>$this->session->userdata('username'),
               'sallt_creationdate'       =>date('y-m-d'),
               'sallt_updatedate'        =>date('y-m-d'),    
               'sallt_modifierid'        =>$this->session->userdata('username'),
           
            );
              
            if (!$upsaldataflag)
            {
                $this->logger->write_logmessage("insert","Trying to add  salary data head wise", "  salary data head wise value is not added ".$this->emppfno);
                $this->logger->write_dblogmessage("insert","Trying to add salary data head wise ", " salary data head wise value is not added ".$this->emppfno);
                $this->session->set_flashdata('err_message','Error in  salary data head wise value - '  , 'error');
                //redirect('setup3redesign/translipredesign',$data);
                redirect('setup3redesign/tlsalaryslip',$data);
            }
            else{
            
                $upsalaryflag = $this->sismodel->insertrec('salary_lt', $saldata1);
                $this->logger->write_logmessage("insert"," salary data head wise value  ", " salary data head wise value added  successfully...");
                $this->logger->write_dblogmessage("insert"," salary data head wise value ", "salary data head wise value added  successfully...");
                $this->session->set_flashdata("success", " salary data ( ".$ttcase." days ) updated successfully  PF NO [ " .$this->emppfno. " ]");
                
            /*************************sending mail with Attachment Salaryslip********************************************/
                if($ttcase=='to'){
                    $this->mailmodel->mailAttachment($empid,$this->emppfno,$month,$year,$case);
                }
                
               // redirect("setup3redesign/translipredesign/".$empid.'/'.$month.'/'.$year.'/'.$case,$data);
                redirect("setup3redesign/tlsalaryslip/".$empid.'/'.$month.'/'.$year.'/'.$case,$data);
            }
            
           
        }//for button
        
        
        $this->load->view('setup3/tlsalaryslip',$data);   
    }
    
    /*
    public function Defaluttranfr_dayslt ($empid,$cmonth,$cyear) {
        $sumincome=0;$sumdeduct=0;
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
      
        $whdata = array('sh_type' =>'I');
        $data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $data['deduction']=array_merge($data['ded'], $data['loans']);
        
        $stempdsal ="uit_emptype,uit_uoc_from,uit_workdept_from,uit_desig_from,uit_workingpost_from,uit_scid_from,
        uit_schm_from,uit_ddoid_from,uit_paybandid_to,uit_vactype_from,uit_group_from";
        $wempsal = array ('uit_staffname'=>$empid);
        $stvalemp = $this->sismodel->get_orderlistspficemore('user_input_transfer',$stempdsal,$wempsal,'');
        
        $wtype=$stvalemp[0]->uit_emptype;
        $strarray=array();
        $self ="shc_salheadid";
        $whdata = array('shc_paycom'=>$this->paycomm,'shc_wtype'=> $wtype);
        $rawarray=$this->sismodel->get_orderlistspficemore('salaryhead_configuration',$self,$whdata,'');
        if(!empty($rawarray))
        $strarray=$rawarray[0]->shc_salheadid;
        $allowedhead=explode(", ",$strarray);
        
        $wtype=$stvalemp[0]->uit_emptype;
        $payscaleid=$stvalemp[0]->uit_paybandid_to;
        
        
        
        $valdays=$this->sismodel->getfromtransto($empid);
        $cnomonth= date("m",strtotime($cmonth));
        print_r($valdays);
        die;
        
        foreach($data['incomes'] as $record1){
            if($record1->sh_tnt == $wtype || $record1->sh_tnt == 'Common'){
                if(in_array($record1->sh_id,$allowedhead)){
                    if($record1->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record1->sh_id,$empid);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                        
                    }
                    else{
                       
                        $this->dheadval=$this->getDefaultheadval($empid,$record1->sh_id);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                       
                    }//main else without formula
                }
                else{
                    $finalhval=0;   
                }
            
            $this->Salarydata_lt($empid,$record1->sh_id,(round($finalhval,2)),NULL,$cmonth,$cyear,'from');
            $sumincome+=$finalhval;
            }
        }
        foreach($data['deduction'] as $record2){
            if($record2->sh_tnt == $wtype || $record2->sh_tnt == 'Common'){
                if(in_array($record2->sh_id,$allowedhead)){
                    if($record2->sh_calc_type == 'Y'){
                        $this->dheadval=$this->getformulaval($record2->sh_id,$empid);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                    }
                    else{
                        
                        $this->dheadval=$this->getDefaultheadval($empid,$record2->sh_id);
                        $finalhval=$this->transferfmlatotal($empid,$cyear,$cnomonth,$this->dheadval,$stfvalsal[0]->ste_days);
                    }
                } 
                else{
                    $finalhval=0; 
                }
            
                $installdetl=$this->updateintlmtno($empid,$record2->sh_id);
                              
                $this->Salarydata_lt($empid,$record2->sh_id,(round($finalhval,2)),$installdetl,$cmonth,$cyear,'from');    
                $sumdeduct+=$finalhval;
            }
            
        }
        $netsalary=$sumincome - $sumdeduct;
        
        $scid=$stvalemp[0]->uit_scid_from;
        $uoccid=$stvalemp[0]->uit_uoc_from;
        $deptid=$stvalemp[0]->uit_workdept_from;
        $desigid=$stvalemp[0]->uit_desig_from;
        $sopost=$stvalemp[0]->uit_workingpost_from;
        $ddoid=$stvalemp[0]->uit_ddoid_from;
       // $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$empid)->emp_ddoid;
        $schmid=$stvalemp[0]->uit_scid_from;
        $bankaccno=$this->sismodel->get_listspfic1('employee_master','emp_bank_accno','emp_id',$empid)->emp_bank_accno;
       // $group=$stvalemp[0]->uit_group_to;
        $group=$stvalemp[0]->uit_group_from;
        $vcawtype=$stvalemp[0]->uit_vactype_from;
       
        $this->Salary_lt($empid,$scid,$uoccid,$deptid,$desigid,$sopost,$ddoid,$schmid,$payscaleid,$bankaccno,$wtype,
        $vcawtype,$group,$cmonth,$cyear,(round($sumincome,2)),(round($sumdeduct,2)),(round($netsalary,2)),'process','from');
         
    }*/
  
}//class    
