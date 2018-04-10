<?php

 /* 
 * @name Report.php
 * @author Nagendra Kumar Singh(nksinghiitk@gmail.com)
 * @author Deepika Chaudhary (chaudharydeepika88@gmail.com)
 * @author Malvika Upadhyay (malvikaupadhyay644@gmail.com)
 * @author Manorama Pal (palseema30@gmail.com)// staff profile and service particulars,Reports(Designation wise,position-summary
 *  vacancy position,professorlist,hodlist.) 
 * @author Sumit Saxena(sumitsesaxena@gmail.com)[view employee profile]
 * @author Om Prakas (omprakashkgp@gmail.com) Discipline Wise List, List Staff Position 
 */

class Report  extends CI_Controller
{

   function __construct() {
        parent::__construct();
        $this->load->model('Common_model',"commodel");
        $this->load->model('Login_model',"lgnmodel"); 
        $this->load->model('SIS_model',"sismodel");
	$this->load->helper('download');
        if(empty($this->session->userdata('id_user'))) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
            redirect('welcome');
         }
    }

// View faculty list
    public function listfac() {
        $datawh = array('roleid' => '2');
        $this->tresult=$this->commodel->get_listspficarry('user_role_type','userid,scid,deptid','roleid',2);
        $this->load->view('report/listfac');
        return;
	}

// View staff list
    public function liststaff() {
        $datawh = array('roleid' => '4');
        $this->tresult=$this->commodel->get_listspficarry('user_role_type','userid,scid,deptid','roleid',4);
        $this->load->view('report/liststaff');
        return;
	}

    public function deptemployeelist(){
        $selectfield ="emp_uocid, emp_dept_code,emp_name, emp_post";
        $whorder = "emp_uocid asc, emp_dept_code  asc";
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');
            //echo "dept===".$dept."www==".$wtype."uo===".$uoff;
            if($dept != "null"){
                if($dept!= "All" && $uoff !="All"){
                    //echo "step1".$dept."uo==".$uoff;
                    $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff,'emp_dept_code'=> $dept);
                }
                else{
                    if($uoff !="All" ){
                        $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff);      
                    }
                    else{
                        if($uoff =="All" && $dept =="All" ){
                            $whdata = array ('emp_worktype' => $wtype);
                        }
                        else{
                            $whdata = array ('emp_worktype' => $wtype,'emp_dept_code'=> $dept);
                        }
                    }
                }
          
            }
            else{
                // echo "else case dept of filter";
                if($uoff!= "All"){
                    $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff);
                }
                else{
                    $whdata = array ('emp_worktype' => $wtype);   
                }
            }
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,'',$whorder);
        }
        
        $this->logger->write_logmessage("view"," view departmentt employee list" );
        $this->logger->write_dblogmessage("view"," view department employee list");
        $this->load->view('report/deptemployeelist',$data);
    }

    public function staffstrengthlist(){
        $selectfield ="sp_uo, sp_dept,sp_emppost, sp_sancstrenght , sp_position , sp_vacant,sp_type";
        $whorder = "sp_uo asc, sp_dept  asc";
	if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');
	   if($dept != "null" && $dept != "All"){
                //echo "if case dept of filter";
                if($uoff != "All"){
                    $whdata = array ('sp_tnt' => $wtype,'sp_uo' => $uoff,'sp_dept'=> $dept);
                }
                else{
                    $whdata = array ('sp_tnt' => $wtype,'sp_dept'=> $dept);
                }

            }
            else{

                if($uoff != "All"){

                    $whdata = array ('sp_tnt' => $wtype,'sp_uo' => $uoff);
                }
                else{

                    $whdata = array ('sp_tnt' => $wtype);
                }
            }
	 $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,'',$whorder);

        }
            $this->logger->write_logmessage("view"," view staff strength list" );
            $this->logger->write_dblogmessage("view"," view staff strength list");
            $this->load->view('report/staffstrengthlist',$data);
    }

    public function staffvacposition(){
        $selectfield ="sp_uo, sp_dept,sp_schemecode, sp_emppost,sp_sancstrenght , sp_position , sp_vacant, sp_remarks";
        $whorder = "sp_emppost asc, sp_uo asc, sp_dept asc";
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $desig  = $this->input->post('desig');
            //echo "desig===".$desig."www==".$wtype."uo===".$uoff;
            if($desig != "null" && $desig != "All"){
                //echo "if case dept of filter";
                if($uoff != "All"){
                    $whdata = array ('sp_tnt' => $wtype,'sp_uo' => $uoff,'sp_emppost'=> $desig);
                }
                else{
                    $whdata = array ('sp_tnt' => $wtype,'sp_emppost'=> $desig);
                }
          
            }
            else{
                
                if($uoff != "All"){
                   
                    $whdata = array ('sp_tnt' => $wtype,'sp_uo' => $uoff);
                }    
                else{
                   
                    $whdata = array ('sp_tnt' => $wtype);
                }
            }
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,'',$whorder);
            
        }
        //$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,'',$whorder);
        $this->logger->write_logmessage("view"," view staff vacancy position list" );
        $this->logger->write_dblogmessage("view"," view staff vacancy position list");
        $this->load->view('report/staffvacposition',$data);
    }

        /***************************************View Employee List******************************************************/
    public function viewprofile($id=0) {
	$roleid=$this->session->userdata('id_role');
        $userid=$this->session->userdata('id_user');
        $deptid = '';
        $whdatad = array('userid' => $userid,'roleid' => $roleid);
        $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
        $datawh = '';

        $worktype=$this->input->post('workingtype',TRUE);
        $empdata['filter']=$id;
        if(!empty($worktype) && ($id!== 0)){
            $filter=$this->input->post('filter',TRUE);
            $empdata['wtype']=$worktype; 
	    if (!empty($deptid))
                $datawh = array('emp_dept_code' => $deptid,'emp_worktype' => $worktype,'emp_name LIKE '=> $filter.'%');
	    else
                $datawh=array('emp_worktype' => $worktype,'emp_name LIKE '=> $filter.'%');
	    $empdata['emprecord'] = $this->sismodel->get_listspficemore('employee_master','emp_id,emp_code,emp_name,emp_scid,emp_uocid,emp_dept_code,emp_desig_code,emp_email,emp_phone,emp_aadhaar_no',$datawh);
		
        //    $empdata['emprecord'] = $this->sismodel->searchemp_profile('employee_master',$worktype,$filter);
        }
        else{
            $empdata1=array();
            $empdata['emprecord']=$empdata1;
        }
        $this->load->view('report/viewprofile',$empdata);
        return;
	}
    
    public function viewfull_profile() {
	  
	//get id for employee to show data	
	$emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;
	//get all profile and service data
	$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $emp_data['servicedata'] = $this->sismodel->get_listrow('employee_servicedetail','empsd_empid',$emp_id);
        $emp_data['performancedata'] = $this->sismodel->get_listrow('Staff_Performance_Data','spd_empid',$emp_id)->row();
        $this->load->view('report/viewfull_profile',$emp_data);
  }

############################## Discipline Wise List ##########################################

public function disciplinewiselist(){
        $selectfield ="emp_dept_code, emp_name, emp_desig_code,emp_specialisationid";
	$whdata = array ('emp_specialisationid >' => 0);
        $whorder = "emp_specialisationid";
        $this->result = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
//      $this->result = $this->sismodel->get_list('employee_master');
        $this->logger->write_logmessage("view"," view  Discipline Wise Report " );
        $this->logger->write_dblogmessage("view"," view  Discipline Wise Report ");
        $this->load->view('report/disciplinewiselist');
}

    public function listofstaffposition(){
       
        $selectfield ="sp_uo";
        if(isset($_POST['filter'])) {
             //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');
            //echo "dept===".$dept."\nwt==".$wtype."\nuo===".$uoff;
            $data['tnttype'] =  $wtype;  
            $data['seldept']=$dept;
            if($wtype!= " "){
                if($uoff !="All"){
                 $whdata = array ('sp_tnt' => $wtype,'sp_uo' => $uoff);
                }
                else{
                    $whdata = array ('sp_tnt' => $wtype);
                }
                $data['records'] = $this->sismodel->get_distinctrecord('staff_position',$selectfield, $whdata);
            }
            
        }
        else{
            $data['tnttype']='';
            $data['seldept']='';
            $data['records'] = $this->sismodel->get_distinctrecord('staff_position',$selectfield, '');
        }
        $this->logger->write_logmessage("view"," view list staff position list" );
        $this->logger->write_dblogmessage("view"," view list staff position list");
        $this->load->view('report/listofstaffposition',$data);
    }


    public function desigemployeelist(){
        $selectfield ="emp_desig_code,emp_dept_code,emp_name";
        //$whdata = array ('emp_worktype' => 'Teaching');
        $whorder = "emp_desig_code  asc";
       // $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');
            $desig  = $this->input->post('desig');
           // echo "dept===".$dept."wt==".$wtype."uo===".$uoff."desig==".$desig;
            if($desig != "null" || $uoff != "null" || $dept != "null" ){
                //echo "ifcase dept of filter";
                if($desig == "All" || $uoff == "All" || $dept == "All"){
                    if($desig == "All" ){
                        if(($uoff == "null" ||$uoff == "All" ) || ($dept == "null" ||$dept == "All" )){
                            if($dept != "null" && $dept != "All" && $dept != ""){
                            
                                $whdata = array ('emp_worktype' => $wtype,'emp_dept_code'=> $dept);
                            }
                            else{
                                //echo "ifcase All 53 cases";
                                $whdata = array ('emp_worktype' => $wtype);
                            }
                            if($uoff != "null" && $uoff != "All"){
                                //echo "ifcase All 999cases";
                                $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff);
                            }
                        }
                        else{
                            if($uoff != "null" && $uoff != "All"){
                                //echo "ifcase All 999cases";
                                $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff);
                            }
                            if($dept != "null" && $dept != "All"){
                                //echo "ifcase All 1000cases";
                                $whdata = array ('emp_worktype' => $wtype,'emp_uocid' => $uoff,'emp_dept_code'=> $dept);
                            }
                                                      
                        }//else
                       
                    }
                   
                }//allcondition
                if($desig != "All" || $uoff != "All" || $dept != "All"){
                    if($desig != "All"){
                         //echo "ifcase All 33cases";
                       if(($uoff == "null" ||$uoff == "All" ) || ($dept == "null" ||$dept == "All" )){
                           // echo "ifcase All 44cases";
                           $whdata = array ('emp_worktype' => $wtype, 'emp_desig_code' => $desig); 
                       } 
                       else{
                           if($uoff != "null" && $uoff != "All"){
                               // echo "ifcase All 000cases";
                               $whdata = array ('emp_worktype' => $wtype, 'emp_desig_code' => $desig ,'emp_uocid' => $uoff);
                           }
                           if($dept != "null" && $dept != "All"){
                               // echo "ifcase All 1111cases";
                               $whdata = array ('emp_worktype' => $wtype, 'emp_desig_code' => $desig ,'emp_uocid' => $uoff,'emp_dept_code'=> $dept); 
                           }
                           
                       }//else
                    }//if
                
                }//noall
                            
            }//ifnot null
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }//ifbutton
        else{
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,'',$whorder);
        }
        $this->logger->write_logmessage("view"," view designation wise employee list" );
        $this->logger->write_dblogmessage("view"," view designation wise employee list");
        $this->load->view('report/desigemployeelist',$data);
    }
    public function positionsummary(){
        $selectfield ="sp_emppost,sp_sancstrenght,sp_position,sp_vacant";
        //$whdata = array ('sp_tnt' => 'Non Teaching');
        $whdata = '';
        $whorder = "sp_emppost asc";
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            	$wtype = $this->input->post('wtype');
            	if($wtype != "null"){
                	if($wtype!= "All"){
                    		//echo "step1".$dept."uo==".$uoff;
                    		$whdata = array ('sp_tnt' => $wtype);
                	}
                	else{
                    		$whdata = '';
                	}
           	}
		$this->wtype = $wtype;
        	$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            	//echo "else case of filter";
		 $this->wtype = 'All';
        	$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }

        
        $this->logger->write_logmessage("view"," view position Summary" );
        $this->logger->write_dblogmessage("view"," view position Summary");
        $this->load->view('report/positionsummary',$data);
    }   
    public function positionvacancy(){
        $selectfield ="sp_emppost";
        //$whdata = array('sp_uo'=> $uo);
         if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $post  = $this->input->post('post');
            if(!empty($post) && ($post!="All")){
                $whdata = array('sp_tnt'=> $wtype,'sp_emppost' =>$post);
            }
            else{
                $whdata = array('sp_tnt'=> $wtype);
            }
            $data['allpost']=$this->sismodel->get_distinctrecord('staff_position',$selectfield, $whdata);
         }
         else{
            // $data['allpost']=$this->sismodel->get_distinctrecord('staff_position','sp_emppost','');
            $data['allpost']=$this->sismodel->get_distinctrecord('staff_position',$selectfield,'');
         }
        $this->logger->write_logmessage("view"," view position vacancy" );
        $this->logger->write_dblogmessage("view"," view position vacancy");
        $this->load->view('report/positionvacancy',$data);
    }
    /*Professor list report and service period*/
    public function professorlist(){
        $getdesgid=$this->commodel->get_listspfic1('designation','desig_id','desig_name','Professor')->desig_id;
        $selectfield ="emp_name,emp_dor,emp_specialisationid,emp_dept_code,emp_doj";
        $whdata=array('emp_desig_code' => $getdesgid);
        $whorder = "emp_doj asc";
        $data['emplist'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        $this->logger->write_logmessage("view"," view list of professors in report " );
        $this->logger->write_dblogmessage("view"," view list of professors in report");
        $this->load->view('report/professorlist',$data);
    } 
    public function hodlist(){
	// get list of uo form authority table priority wise
        $today= date("Y-m-d H:i:s"); 
//        $whdata=array('hl_dateto >='=> $today);
        $selectfield ="hl_userid,hl_empcode,hl_deptid,hl_scid,hl_uopid";
	$whorder = "hl_uopid asc";
        $data['allsc']=$this->sismodel->get_orderlistspficemore('hod_list',$selectfield,'',$whorder);
        $this->logger->write_logmessage("view"," view list of HOD in report " );
        $this->logger->write_dblogmessage("view"," view list of HOD in report");
        $this->load->view('report/hodlist',$data);
    }

	public function uolist(){
        $today= date("Y-m-d H:i:s");
//        $whdata=array('hl_dateto >='=> $today);
        $selectfield ="ul_userid,ul_empcode,ul_uocode,ul_uoname";
        $data['allsc']=$this->sismodel->get_distinctrecord('uo_list',$selectfield,'');
        $this->logger->write_logmessage("view"," view list of UO in report " );
        $this->logger->write_dblogmessage("view"," view list of UO in report");
        $this->load->view('report/uolist',$data);
    }

    /********************slect uo list according to selection type**********************/
    public function getuolist(){
        $combid= $this->input->post('worktype');
       // $parts = explode(',',$combid); 
       // echo "sc===".$combid;
        $datawh=array('emp_worktype' => $combid);
        $comb_data = $this->sismodel->get_distinctrecord('employee_master','emp_uocid',$datawh);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>-------Select University Officer--------';
        $uo_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $auoname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$detail->emp_uocid)->name;
                $auocode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$detail->emp_uocid)->code;
              
               $uo_select_box.='<option value='.$detail->emp_uocid.'>'.$auoname. '(' .$auocode. ')'.' ';
            }
        }
        echo json_encode($uo_select_box);
    } 
    
    
    /********************slect dept list according to selection type**********************/
    public function getdeptlist(){
        $combid= $this->input->post('worktypeuo');
        $parts = explode(',',$combid); 
       // echo "sc===".$combid;
        if($parts[1]!="All"){
            $datawh=array('emp_worktype' => $parts[0],'emp_uocid' => $parts[1]);
        }
        else{
            $datawh=array('emp_worktype' => $parts[0]);
        }
        $comb_data = $this->sismodel->get_distinctrecord('employee_master','emp_dept_code',$datawh);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>-------Select Department--------';
        $dept_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $deptname=$this->commodel->get_listspfic1('Department', 'dept_name', 'dept_id',$detail->emp_dept_code)->dept_name;
                $deptcode=$this->commodel->get_listspfic1('Department', 'dept_code', 'dept_id',$detail->emp_dept_code)->dept_code;
              
               $dept_select_box.='<option value='.$detail->emp_dept_code.'>'.$deptname. '(' .$deptcode. ')'.' ';
            }
        }
        echo json_encode($dept_select_box);
    } 
    
    /********************slect designation list according to selection type**********************/
    public function getdesiglist(){
        $combid= $this->input->post('worktype');
       // $parts = explode(',',$combid); 
       // echo "sc===".$combid;
        $datawh=array('emp_worktype' => $combid);
        $comb_data = $this->sismodel->get_distinctrecord('employee_master','emp_desig_code',$datawh);
        $desig_select_box =' ';
        $desig_select_box.='<option value=null>--------- Select Designation ---------';
        $desig_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $designame=$this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$detail->emp_desig_code)->desig_name;
                $desigcode=$this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$detail->emp_desig_code)->desig_code;
                $desig_select_box.='<option value='.$detail->emp_desig_code.'>'.$designame. '(' .$desigcode. ')'.' ';
               
            }
        }
        echo json_encode($desig_select_box);
    } 
    
    /********************slect uo list according to selection type**********************/
    public function getuodesiglist(){
        $combid= $this->input->post('wtdesig');
        $parts = explode(',',$combid); 
       // echo "sc===".$combid;
        if($parts[1]!='All'){
            $datawh=array('emp_worktype' => $parts[0],'emp_desig_code' => $parts[1]);
        }
        else{
            $datawh=array('emp_worktype' => $parts[0]);
        }
        $comb_data = $this->sismodel->get_distinctrecord('employee_master','emp_uocid',$datawh);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>------- Select University Officer ------';
        $uo_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $auoname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$detail->emp_uocid)->name;
                $auocode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$detail->emp_uocid)->code;
              
                $uo_select_box.='<option value='.$detail->emp_uocid.'>'.$auoname. '(' .$auocode. ')'.' ';
               
               
            }
        }
        echo json_encode($uo_select_box);
    }
    
    /********************slect dept list according to selection type**********************/
    public function getdeptuodesiglist(){
        $combid= $this->input->post('wtdesiguo');
        $parts = explode(',',$combid); 
       // echo "sc===".$combid;
        if($parts[1]!='All' && $parts[2]!='All'){
            $datawh=array('emp_worktype' => $parts[0],'emp_desig_code' => $parts[1],'emp_uocid' =>$parts[2] );
        }
        if($parts[1]=='All' && $parts[2]!='All'){
            $datawh=array('emp_worktype' => $parts[0],'emp_uocid' =>$parts[2] );
        }
        if($parts[1] != 'All' && $parts[2] == 'All'){
            $datawh=array('emp_worktype' => $parts[0],'emp_desig_code' => $parts[1]);
        }
        if($parts[1] == 'All' && $parts[2] == 'All'){
            $datawh=array('emp_worktype' => $parts[0]);
        }
        $comb_data = $this->sismodel->get_distinctrecord('employee_master','emp_dept_code',$datawh);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>------- Select Department ------';
        $dept_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                
                $deptname=$this->commodel->get_listspfic1('Department', 'dept_name', 'dept_id',$detail->emp_dept_code)->dept_name;
                $deptcode=$this->commodel->get_listspfic1('Department', 'dept_code', 'dept_id',$detail->emp_dept_code)->dept_code;
                $dept_select_box.='<option value='.$detail->emp_dept_code.'>'.$deptname. '(' .$deptcode. ')'.' ';
                               
            }
        }
        echo json_encode($dept_select_box);
    } 
    /********************slect uo list according to selection type**********************/
    public function getspuolist(){
        $combid= $this->input->post('worktype');
        $datawh=array('sp_tnt' => $combid);
        $comb_data = $this->sismodel->get_distinctrecord('staff_position','sp_uo',$datawh);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>-------Select University Officer--------';
        $uo_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $auoname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$detail->sp_uo)->name;
                $auocode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$detail->sp_uo)->code;
              
               $uo_select_box.='<option value='.$detail->sp_uo.'>'.$auoname. '(' .$auocode. ')'.' ';
            }
        }
        echo json_encode($uo_select_box);
    } 
    
        
    /********************slect designation list according to selection type**********************/
    public function getuo_postlist(){
        $combid= $this->input->post('wtuoid');
        $parts = explode(',',$combid); 
        if($parts[1]!='All'){
            $datawh=array('sp_tnt' => $parts[0],'sp_uo' =>$parts[1]);
        }
        else{
            $datawh=array('sp_tnt' => $parts[0]);
        }
        $comb_data = $this->sismodel->get_distinctrecord('staff_position','sp_emppost',$datawh);
        $pt_select_box =' ';
        $pt_select_box.='<option value=null>-------Select Post--------';
        $pt_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                
                $designame=$this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$detail->sp_emppost)->desig_name;
                $desigcode=$this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$detail->sp_emppost)->desig_code;
                $pt_select_box.='<option value='.$detail->sp_emppost.'>'.$designame. '(' .$desigcode. ')'.' ';
               
            }
        }
        echo json_encode($pt_select_box);
    } 
    
     public function getuolist_sp(){
        $combid= $this->input->post('worktype');
        $datawh=array('sp_tnt' => $combid);
        $comb_data = $this->sismodel->get_distinctrecord('staff_position','sp_uo',$datawh);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>----Select University Officer-----';
        $uo_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $auoname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$detail->sp_uo)->name;
                $auocode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$detail->sp_uo)->code;
              
               $uo_select_box.='<option value='.$detail->sp_uo.'>'.$auoname. '(' .$auocode. ')'.' ';
            }
        }
        echo json_encode($uo_select_box);
    } 
    /********************slect dept list according to selection type**********************/
    public function getdeptlist_sp(){
        $combid= $this->input->post('worktypeuo');
        $parts = explode(',',$combid); 
        if($parts[1]!="All"){
            $datawh=array('sp_tnt' => $parts[0],'sp_uo' => $parts[1]);
        }
        else{
            $datawh=array('sp_tnt' => $parts[0]);
        }
        $comb_data = $this->sismodel->get_distinctrecord('staff_position','sp_dept',$datawh);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>----Select Department-------';
        $dept_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $deptname=$this->commodel->get_listspfic1('Department', 'dept_name', 'dept_id',$detail->sp_dept)->dept_name;
                $deptcode=$this->commodel->get_listspfic1('Department', 'dept_code', 'dept_id',$detail->sp_dept)->dept_code;
              
               $dept_select_box.='<option value='.$detail->sp_dept.'>'.$deptname. '(' .$deptcode. ')'.' ';
            }
        }
        echo json_encode($dept_select_box);
    } 
    
    /********************select post list according to selection type**********************/
    public function getpostlist_sp(){
        $combid= $this->input->post('worktype');
        $datawh=array('sp_tnt' => $combid);
        $comb_data = $this->sismodel->get_distinctrecord('staff_position','sp_emppost',$datawh);
        $post_select_box =' ';
        $post_select_box.='<option value=null>----------- Select Post ---------------';
        $post_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $postname=$this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$detail->sp_emppost)->desig_name;
                $postcode=$this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$detail->sp_emppost)->desig_code;
              
               $post_select_box.='<option value='.$detail->sp_emppost.'>'.$postname. '(' .$postcode. ')'.' ';
            }
        }
        echo json_encode($post_select_box);
    } 
    
}

