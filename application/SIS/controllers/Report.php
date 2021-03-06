<?php

 /* 
 * @name Report.php
 * @author Nagendra Kumar Singh(nksinghiitk@gmail.com)
 * @author Deepika Chaudhary (chaudharydeepika88@gmail.com)
 * @author Malvika Upadhyay (malvikaupadhyay644@gmail.com)
 * @author Manorama Pal (palseema30@gmail.com)// staff profile and service particulars,Reports,Academic qualification, technical qualification .
 * (Designation wise,position-summary vacancy position,professorlist,hodlist.) 
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

   /**
    * Get Download PDF File
    *
    * @return Response
    */
  // function convertpdf($hdata,$filename){
   function convertpdf(){
	$filename = $this->input->post('fname');
	$hdata =$this->input->post('rdata') ; 
//	print_r($filename);die();
//	$html=$this->load->view('report/disciplinewiselist',$hdata);        
	$html=$this->load->view($filename,$hdata);        
        // Load pdf library
//	print_r($html);die();
        $this->load->library('pdf');
        
        // Load HTML content
        $this->pdf->load_html($html);
        
        // (Optional) Setup the paper size and orientation
        $this->pdf->set_paper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->pdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        //$this->pdf->stream("welcome.pdf", array("Attachment"=>0));
        $this->pdf->stream("disciplinewiselist.pdf", array("Attachment"=>0));
   }

//get all uo empid
   public function getempuoid(){
	   	$cdate = date('Y-m-d');
		$selectfield='emp_id';
//                $whdata = array ('emp_leaving' => NULL,'emp_dor>='=>date('Y-m-d'),'ul_status'=>'Fulltime','ul_dateto'=> '1000-01-01 00:00:00');
                $whdata = "emp_leaving = 'NULL' and emp_dor>='".$cdate."' and ul_status='Fulltime' and (ul_dateto='1000-01-01 00:00:00'  or ul_dateto >='".$cdate."')";
		
                $joincond = 'employee_master.emp_code = uo_list.ul_empcode';
                //$emp_data['uoempid']=$this->sismodel->get_jointbrecord('uo_list',$selectfield,'employee_master',$joincond,'LEFT',$whdata);
                $empuoempid=$this->sismodel->get_jointbrecord('uo_list',$selectfield,'employee_master',$joincond,'LEFT',$whdata);
                $empuoid = array();
                foreach($empuoempid as $row){
                        $empuoid[]=$row->emp_id;
                }
                return $empuoid;
	}

//get all hod empid
	public function getemphodid(){
		$selectfield='emp_id';
                $whdata = array ('emp_leaving' => NULL,'emp_dor>='=>date('Y-m-d'),'hl_status'=>'Fulltime','hl_dateto'=> '1000-01-01 00:00:00');

		$joincond = 'employee_master.emp_code = hod_list.hl_empcode';
                //$emp_data['hodempid']=$this->sismodel->get_jointbrecord('hod_list',$selectfield,'employee_master',$joincond,'LEFT',$whdata);
                $emphodempid = $this->sismodel->get_jointbrecord('hod_list',$selectfield,'employee_master',$joincond,'LEFT',$whdata);
		$emphodid = array();
		foreach($emphodempid as $row){
                        $emphodid[]=$row->emp_id;
                }
                return $emphodid;
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
        $selectfield ="emp_id,emp_code,emp_uocid, emp_dept_code,emp_name,emp_head, emp_post,emp_desig_code,emp_schemeid";
        $whorder = "emp_uocid asc, emp_dept_code  asc, emp_desig_code asc, emp_post asc";
	$cdate = date('Y-m-d');
        // add doris geater than current date and reason is null  in whdata
//	$whdata = array ('emp_leaving' => NULL,'emp_dor>='=>$cdate);
	$whdata = $this->getprofilefilerdata();
	$whdata['emp_leaving'] = NULL;
	$whdata['emp_dor>=']=$cdate;

        $this->wtyp = '';
        $this->uolt = '';
        $this->deptmt='';
        
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept[]  = $this->input->post('dept');
		 
	    $this->wtyp = $wtype;
            $this->uolt = $uoff;
	    $whdata['emp_worktype']=$wtype;
            if($uoff !="All"){
	    	$whdata['emp_uocid']=$uoff;
	    }
	    $i=0;
	    if((!empty($dept))&&($dept != "null")){
                        foreach($dept as $row){
                        $this->deptmt = $row[$i];
                        $names = $row;
                        $i++;
                        }
                }
		if(!empty($names)){
			$data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_dept_code',$names,$whorder);
	
		}else{
			$data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'','',$whorder);
		}
            //$data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }
        else{
           // echo "seema=in ccc==".$this->wtyp.$this->uolt.$this->deptmt;
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }
        
        $this->logger->write_logmessage("view"," view departmentt employee list" );
        $this->logger->write_dblogmessage("view"," view department employee list");
        $this->load->view('report/deptemployeelist',$data);
    }

   public function profilecompleteness(){

	$selectfield ="emp_id,emp_code,emp_uocid, emp_dept_code,emp_name,emp_head, emp_post,emp_desig_code";
        $whorder = "emp_uocid asc, emp_dept_code  asc, emp_desig_code asc, emp_post asc";
        $cdate = date('Y-m-d');
        // add doris geater than current date and reason is null  in whdata
        $whdata = $this->getprofilefilerdata();
        $whdata['emp_leaving'] = NULL;
        $whdata['emp_dor>=']=$cdate;
        
        $data['wtyp']='';
        $data['uolt']='';
        $data['deptmt']='';
               
        $wtype='';
        $uoff='';
        $dept='';
        
                        
        
        if(isset($_POST['filter'])) {
            	$wtype = $this->input->post('wtype');
            	$uoff  = $this->input->post('uoff');
            	$dept  = $this->input->post('dept');

            	if((!empty($dept))&&($dept != "null")){
                	$data['deptmt'] = $dept;
            	}else{
                	$data['deptmt']= "All";
            	}

		if(!empty($wtype)){
			$whdata['emp_worktype']=$wtype;
		 	$data['wtyp'] = $wtype;
		}
           	if(($dept != "null") && ($dept != "All") && (!empty($dept))){
                	$whdata['emp_dept_code']= $dept;
           	}
           	if(($uoff != "null") && ($uoff != "All")&& (!empty($uoff))){
                	$whdata['emp_uocid'] = $uoff;
            		$data['uolt'] = $uoff;
           	}
//print_r($whdata);die();
         	$data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
           // $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
		$data['records'] ='';
            	$this->logger->write_logmessage("view"," view profilecompleteness list" );
            	$this->logger->write_dblogmessage("view"," view profilecompleteness list");
        }
            	$this->logger->write_logmessage("view"," view profilecompleteness list" );
            	$this->logger->write_dblogmessage("view"," view profilecompleteness list");
            	$this->load->view('report/profilecompleteness',$data);
    }

    public function staffstrengthlist(){
        $selectfield ="sp_uo, sp_dept,sp_emppost, sp_schemecode,sp_sancstrenght , sp_position , sp_vacant,sp_type";
        $whorder = "sp_uo asc, sp_dept  asc, sp_schemecode  asc";
	$whdata= '';
	$whdata = $this->getwhdata();
        $this->wtyp ='All';
        $this->uolt = 'All';
        $this->deptmt= 'All';
        
        
	if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');

	    $this->wtyp = $wtype;
            $this->uolt = $uoff;
	    if((!empty($dept))&&($dept != "null")){
                $this->deptmt = $dept;
            }else{
                $this->deptmt= "All";
            }

           $whdata['sp_tnt'] = $wtype;
	   if(($dept != "null") && ($dept != "All")){
		$whdata['sp_dept']= $dept;
	   }
	   if(($uoff != "null") && ($uoff != "All")){	
		$whdata['sp_uo'] = $uoff;
	   }
	 $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
            $this->logger->write_logmessage("view"," view staff strength list" );
            $this->logger->write_dblogmessage("view"," view staff strength list");
        }
            $this->logger->write_logmessage("view"," view staff strength list" );
            $this->logger->write_dblogmessage("view"," view staff strength list");
            $this->load->view('report/staffstrengthlist',$data);
    }

    public function staffvacposition(){
        $selectfield ="sp_uo, sp_dept,sp_schemecode, sp_emppost,sp_sancstrenght , sp_position , sp_vacant, sp_remarks";
        $whorder = "sp_emppost asc, sp_uo asc, sp_dept asc";
	$whdata = '';
	$whdata = $this->getwhdata();
         $this->wtyp = '';
            $this->uolt = '';
        $this->desigm='';
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $desig  = $this->input->post('desig');

	    $this->wtyp = $wtype;
            $this->uolt = $uoff;
	    if((!empty($desig))&&($desig != "null")){
            	$this->desigm = $desig;
            }else{
                $this->desigm = "All";
            }
            $whdata['sp_tnt'] = $wtype;
	    if($desig != "null" && $desig != "All"){
		 $whdata['sp_emppost']= $desig;
	    }
	    if(($uoff != "null") && ($uoff != "All")){
                $whdata['sp_uo'] = $uoff;
           }

/*            //echo "desig===".$desig."www==".$wtype."uo===".$uoff;
            if($desig != "null" && $desig != "All"){
                //echo "if case dept of filter";
                if($uoff != "All"){
                    $whdata['sp_tnt'] = $wtype;
                    $whdata['sp_emppost']= $desig;
		    if($this->session->userdata('id_role') != 10){
                    	$whdata['sp_uo'] = $uoff;
		    }
                }
                else{
                    $whdata['sp_tnt'] = $wtype;
                    $whdata['sp_emppost']= $desig;
                }
            }
            else{
                if($uoff != "All"){
                    $whdata['sp_tnt'] = $wtype;
		    if($this->session->userdata('id_role') != 10){
                    	$whdata['sp_uo'] = $uoff;
		    }
                }    
                else{
                    $whdata['sp_tnt'] = $wtype;
                }
            }*/
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            //echo "else case of filter";
            $data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        //$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,'',$whorder);
        $this->logger->write_logmessage("view"," view staff vacancy position list" );
        $this->logger->write_dblogmessage("view"," view staff vacancy position list");
        $this->load->view('report/staffvacposition',$data);
    }

        /***************************************View Employee List******************************************************/
    public function viewprofile($id=0) {
	$cdate = date('Y-m-d');
        // add doris geater than current date and reason is null  in whdata
        $datawh = array ('emp_leaving' => NULL,'emp_dor>='=>$cdate);
        //  get role id and user id
        $rlid=$this->session->userdata('id_role');
        if ($rlid == 5){
                $usrid=$this->session->userdata('id_user');
                $deptid = '';
                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
                $datawh['emp_dept_code'] = $deptid;
        }
        /*$roleid=$this->session->userdata('id_role');
        $userid=$this->session->userdata('id_user');
        $deptid = '';
        $whdatad = array('userid' => $userid,'roleid' => $roleid);
        $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
        $datawh = '';
*/
        $worktype=$this->input->post('workingtype',TRUE);
        $empdata['filter']=$id;
        $empdata['words']=$id; 
        if(!empty($worktype) && ($id!== 0)){
            $filter=$this->input->post('filter',TRUE);
            $empdata['wtype']=$worktype; 
            $empdata['words']=$filter; 
	   /* if (!empty($deptid))
                $datawh = array('emp_dept_code' => $deptid,'emp_worktype' => $worktype,'emp_name LIKE '=> $filter.'%');
	    else
                $datawh=array('emp_worktype' => $worktype,'emp_name LIKE '=> $filter.'%');
	*/
                $datawh['emp_worktype'] = $worktype;
                $datawh['emp_name LIKE ']= $filter.'%';
	    	$empdata['emprecord'] = $this->sismodel->get_listspficemore('employee_master','emp_id,emp_code,emp_photoname,emp_name,emp_head,emp_scid,emp_uocid,emp_dept_code,emp_desig_code,emp_email,emp_phone,emp_aadhaar_no',$datawh);
//		add this code as join query
	/*	$selectfield="empsd_campuscode,empsd_ucoid,empsd_deptid,empsd_desigcode,empsd_schemeid";
	        $whdata = array ('empsd_empid'=>$emp_id);
        	$whorder = "empsd_dojoin dsc";
	        $empdata['servicedata'] = $this->sismodel->get_orderlistspficemore('employee_servicedetail',$selectfield,$whdata,$whorder); */
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

	//for adding head next to designation
	$cdate=date('Y-m-d');
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;

	$this->headflag="false";
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto' =>'1000-01-01 00:00:00' );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);
	
	$this->uoflag="false";
        $uhwdata = array('ul_empcode' =>$empcode, 'ul_dateto'=> '1000-01-01 00:00:00');
        $this->uoflag=$this->sismodel->isduplicatemore("uo_list",$uhwdata);

	//get all profile and service data
	$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();

	$fieldems="ems_empid,ems_vci_status,ems_vci_statchapter,ems_vci_statregno,ems_vci_statregdate,ems_vci_statvaliddate,ems_vci_alliregno,ems_vci_alliregdate,ems_vci_allivaliddate,ems_pwplace1,ems_pwplace2,ems_pwplace3 ";
        $whdataems = array ('ems_empid' => $emp_id);
        $whorderems = '';
        $emp_data['emsdata'] = $this->sismodel->get_orderlistspficemore('employee_master_support',$fieldems,$whdataems,$whorderems);

	$selectfield="*";
	$whdata = array ('empsd_empid' => $emp_id);
	$whorder = 'empsd_dojoin desc';
	$emp_data['servicedata'] = $this->sismodel->get_orderlistspficemore('employee_servicedetail',$selectfield,$whdata,$whorder);
//	$emp_data['addassign'] = $this->sismodel->get_listrow('additional_assignments','aa_empid',$emp_id);
        $emp_data['performancedata'] = $this->sismodel->get_listrow('Staff_Performance_Data','spd_empid',$emp_id)->row();
	$whdata = array ('sdp_empcode' => $empcode);
	$emp_data['deputdata'] = $this->sismodel->get_orderlistspficemore('staff_deputation_perticulars',$selectfield,$whdata,'');
	$whdata = array ('sdep_empcode' => $empcode);
	$emp_data['deptexamdata'] = $this->sismodel->get_orderlistspficemore('staff_department_exam_perticulars',$selectfield,$whdata,'');
	$whdata = array ('swap_empcode' => $empcode);
	$emp_data['workarrangdata'] = $this->sismodel->get_orderlistspficemore('staff_working_arrangements_perticulars',$selectfield,$whdata,'');
	$whdata = array ('srp_empcode' => $empcode);
	$emp_data['recruitdata'] = $this->sismodel->get_orderlistspficemore('staff_recruitment_perticulars',$selectfield,$whdata,'');
	$whdata = array ('sdap_empcode' => $empcode);
	$emp_data['disciplinactdata'] = $this->sismodel->get_orderlistspficemore('staff_disciplinary_actions_perticulars',$selectfield,$whdata,'');
	//for leave perticular
//	$whdata = array ('empsd_empid' => $empcode);
//	$emp_data['leavedata'] = $this->sismodel->get_orderlistspficemore('employee_servicedetail',$selectfield,$whdata,$whorder);
	$emp_data['leavedata'] = '';

	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/viewfull_profile',$emp_data);
  }

  public function service_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid,emp_doj,emp_dor,emp_jsession,emp_leaving';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
// total service period
	$service=0;
	$startdate = new DateTime( $emp_data['data']->emp_doj);
//	echo $startdate->format('Y-m-d H:i:s');
	$joinsess=$emp_data['data']->emp_jsession;
	if((strcasecmp($joinsess,"Afternoon" )) == 0){
		$service=-1;
	}
	$dol=$emp_data['data']->emp_leaving;
	$dore=new DateTime($emp_data['data']->emp_dor);
	$currdate = new DateTime("now");
//echo $dol->format('Y-m-d H:i:s');
//echo "==";
	if((!empty($dol)) && (!((strcasecmp($dol,"1000-01-01" )) == 0))){
		$enddate = $dol;
	}else{
		if($dore >= $currdate){
			$enddate =$currdate;
		}else{
			$enddate = $dore;
		}
	}
//echo $enddate->format('Y-m-d H:i:s');
	$diff = $startdate->diff($enddate);
	$totser= "<b>Total Service : ".$diff->y . " Year -  " . $diff->m." Month -  ".($diff->d - $service)  ." Days </b>";
	$emp_data['totalser'] = $totser;

        $selectfield="*";
        $whdata = array ('empsd_empid' => $emp_id);
        $whorder = 'empsd_dojoin desc,empsd_id desc';
        $emp_data['servicedata'] = $this->sismodel->get_orderlistspficemore('employee_servicedetail',$selectfield,$whdata,$whorder);

        $whdata = array ('empottsd_empid' => $emp_id);
        $whorder = 'empottsd_datefrom desc,empottsd_id desc';
        $emp_data['ottservicedata'] = $this->sismodel->get_orderlistspficemore('employee_ottsd',$selectfield,$whdata,$whorder);
	
	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/service_profile',$emp_data);
  }

public function promotional_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('spd_empid' => $emp_id);
        $whorder = 'spd_dojinpost desc, spd_agpdate desc, spd_id desc';
        $emp_data['promotionaldata'] = $this->sismodel->get_orderlistspficemore('staff_promotionals_details',$selectfield,$whdata,$whorder);

        $emp_data['uoempid']=$this->getempuoid();
        $emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/promotional_profile',$emp_data);
  }


  public function performance_profile() {

        //get id for employee to show data      
        $tab_id = $this->uri->segment(3);
        $emp_id = $this->uri->segment(4);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";

        $emp_data['performancedata'] = $this->sismodel->get_listrow('Staff_Performance_Data','spd_empid',$emp_id)->row();

        $whdata = array ('spbd_empid' => $emp_id);
	$whorder = 'spbd_pubtype asc,spbd_id desc';
	$emp_data['emppubdata'] = $this->sismodel->get_orderlistspficemore('staff_pub_data',$selectfield,$whdata,$whorder);

        $whdata = array ('sta_empid' => $emp_id);
	$whorder = 'sta_id desc';
	$emp_data['empstadata'] = $this->sismodel->get_orderlistspficemore('staff_training_attended',$selectfield,$whdata,$whorder);

        $whdata = array ('sto_empid' => $emp_id);
	$whorder = 'sto_id desc';
	$emp_data['empstodata'] = $this->sismodel->get_orderlistspficemore('staff_training_organised',$selectfield,$whdata,$whorder);

        $whdata = array ('spad_empid' => $emp_id);
	$whorder = 'spad_id desc';
	$emp_data['empawarddata'] = $this->sismodel->get_orderlistspficemore('staff_perform_award_data',$selectfield,$whdata,$whorder);
        
	$whdata = array ('sppd_empid' => $emp_id);
	$whorder = 'sppd_id desc';
	$emp_data['empprojdata'] = $this->sismodel->get_orderlistspficemore('staff_perform_project_data',$selectfield,$whdata,$whorder);

	$whdata = array ('spsgd_empid' => $emp_id);
        $whorder = 'spsgd_id desc';
        $emp_data['empstuguidata'] = $this->sismodel->get_orderlistspficemore('staff_perform_stugui_data',$selectfield,$whdata,$whorder);

        $whdata = array ('spgld_empid' => $emp_id);
        $whorder = 'spgld_id desc';
        $emp_data['empguestlectdata'] = $this->sismodel->get_orderlistspficemore('staff_perform_guest_lect_data',$selectfield,$whdata,$whorder);

	
	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/performance_profile',$emp_data);
  }

  public function leave_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
     //   $empuserid =$this->sismodel->get_listspfic1('employee_master','emp_userid','emp_id', $emp_id)->emp_userid;
        $selectfield="la_id,la_type,granted_la_from_date,granted_la_to_date,la_taken,la_year,la_upfile";
        $whdata = array ('la_userid' => $emp_id,'la_status' =>'1');
	$whorder = "la_type asc,la_year desc";
//	get the id of these leave type
//	$orwhin = array('UEL on ML', 'EL', 'METERNITY LEAVE','EOL');
        $leaveid1 =$this->sismodel->get_listspfic1('leave_type_master','lt_id','lt_name', 'Unearned Leave on Medical Leave')->lt_id;
        $leaveid2 =$this->sismodel->get_listspfic1('leave_type_master','lt_id','lt_name', 'Earned Leave')->lt_id;
        $leaveid3 =$this->sismodel->get_listspfic1('leave_type_master','lt_id','lt_name', 'Meternity Leave')->lt_id;
        $leaveid4 =$this->sismodel->get_listspfic1('leave_type_master','lt_id','lt_name', 'Extra Ordinary Leave')->lt_id;
	$orwhin = array($leaveid1,$leaveid2,$leaveid3,$leaveid4);
//print_r($orwhin);
//echo 
//die();
        //for leave perticular
        $emp_data['leavedata'] = $this->sismodel->get_orderlistspficemore('leave_apply',$selectfield,$whdata,$whorder);
     //   $emp_data['leavedata'] = $this->sismodel->get_orderlistspficemoreorwh('leave_apply',$selectfield,$whdata,'la_type',$orwhin,'');

	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        
        $this->load->view('report/leave_profile',$emp_data);
  }

  public function deputation_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('sdp_empcode' => $empcode);
        $emp_data['deputdata'] = $this->sismodel->get_orderlistspficemore('staff_deputation_perticulars',$selectfield,$whdata,'');


	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/deputation_profile',$emp_data);
  }

public function deptexam_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('sdep_empcode' => $empcode);
        $emp_data['deptexamdata'] = $this->sismodel->get_orderlistspficemore('staff_department_exam_perticulars',$selectfield,$whdata,'');


	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/deptexam_profile',$emp_data);
  }

public function workorder_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('swap_empcode' => $empcode);
        $emp_data['workarrangdata'] = $this->sismodel->get_orderlistspficemore('staff_working_arrangements_perticulars',$selectfield,$whdata,'');

	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/workorder_profile',$emp_data);
  }

public function addionalassign_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="aa_id,aa_asigname,aa_asigperiodfrom,aa_asigperiodto,aa_place";
        $whdata = array ('aa_empid' => $emp_id);
        $emp_data['addionaldata'] = $this->sismodel->get_orderlistspficemore('additional_assignments',$selectfield,$whdata,'');

        $emp_data['uoempid']=$this->getempuoid();
        $emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/addionalassign_profile',$emp_data);
  }

public function recruit_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('srp_empcode' => $empcode);
        $emp_data['recruitdata'] = $this->sismodel->get_orderlistspficemore('staff_recruitment_perticulars',$selectfield,$whdata,'');

	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/recruit_profile',$emp_data);
  }
public function disciplin_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
	$selempfield='emp_photoname,emp_desig_code,emp_name,emp_phone,emp_secndemail,emp_dept_code,emp_uocid';
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id,$selempfield)->row();
        //$emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('sdap_empcode' => $empcode);
        $emp_data['disciplinactdata'] = $this->sismodel->get_orderlistspficemore('staff_disciplinary_actions_perticulars',$selectfield,$whdata,'');

	$emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        $this->load->view('report/disciplin_profile',$emp_data);
  }
#############################f Discipline Wise List ##########################################
	public 	function getprofilefilerdata(){
		$whdata ='';
		$rlid=$this->session->userdata('id_role');
                if ($rlid == 5){
                        $usrid=$this->session->userdata('id_user');
                        $deptid = '';
                        $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                        $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                        foreach($resu as $rw){
                                $deptid=$rw->deptid;
                        }
                        $whdata=array('emp_dept_code'=> $deptid);
                }
                if ($rlid == 10){
                        $usrname=$this->session->userdata('username');
                        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
                        }else{
                                $uoid=$this->lgnmodel->get_listspfic1('authorities','id','authority_email',$usrname)->id;
                                $whdata=array('emp_uocid' => $uoid);
                        }
               }
		return $whdata;
	}

public function disciplinewiselist(){
	$this->sc=$this->commodel->get_orderlistspficemore('study_center','sc_id,sc_name,sc_code','','sc_name asc');
	$this->sub=$this->commodel->get_orderlistspficemore('subject','sub_id,sub_name,sub_code','','sub_name asc');

	$cdate = date('Y-m-d');
        $selectfield ="emp_dept_code, emp_id,emp_code,emp_name,emp_head, emp_desig_code,emp_specialisationid";
	//$whdata = array ('emp_leaving' => NULL,'emp_dor>='=>$cdate,'emp_worktype' => 'Teaching');
	$whdata = $this->getprofilefilerdata();
	$whdata['emp_leaving'] = NULL;
	$whdata['emp_dor>=']=$cdate;;
	$whdata['emp_worktype'] = 'Teaching';
/*		$rlid=$this->session->userdata('id_role');
                if ($rlid == 5){
                        $usrid=$this->session->userdata('id_user');
                        $deptid = '';
                        $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                        $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                        foreach($resu as $rw){
                                $deptid=$rw->deptid;
                        }
                        $whdata['emp_dept_code']= $deptid;
                }
		if ($rlid == 10){
                        $usrname=$this->session->userdata('username');
//                      print_r( $usrname); die; 
                        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
                        }else{
                                $uoid=$this->lgnmodel->get_listspfic1('authorities','id','authority_email',$usrname)->id;
                                $whdata['emp_uocid'] = $uoid;
                        }
               }
*/
        $whorder = "emp_specialisationid asc, emp_desig_code asc ";

        	$this->camp ='';
                $this->subj='';
	if(isset($_POST['filter'])) {
		$camp = $this->input->post('camp');
            	$subj[] = $this->input->post('subj');	
		$this->camp = $camp;
		if(!empty($camp)){
			if($camp != "All"){
				$whdata['emp_scid']=$camp;
			}
		}
		$i=0;
		if(!empty($subj)){
			foreach($subj as $row){
			$this->subj = $row[$i];	
			$names = $row;
			$i++;
			}
		}
		if(empty($names))
			$whdata['emp_specialisationid >'] = 0;
		$this->result = $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_specialisationid',$names,$whorder);
	}else{
		$whdata['emp_specialisationid >'] = 0;
        	$this->result = $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'','',$whorder);
	}
        $this->logger->write_logmessage("view"," view  Discipline Wise Report " );
        $this->logger->write_dblogmessage("view"," view  Discipline Wise Report ");
        $this->load->view('report/disciplinewiselist');
}

    public function listofstaffposition(){
	$whorder = "sp_uo asc, sp_dept asc, sp_schemecode  asc,sp_emppost asc";
//	$whorder = "sp_uo asc, sp_dept asc";
        $whdata = '';
	$whdata = $this->getwhdata();     
        $this->wtyp='';
        $this->uolt='';
        $this->deptmt='';
//print_r($whdata); die();
        $selectfield ="sp_uo,sp_dept,sp_schemecode,sp_emppost,sp_sancstrenght,sp_position,sp_vacant";
        //$selectfield ="sp_uo,sp_dept";
        $data['tnttype']='';
        $data['seldept']='';
        if(isset($_POST['filter'])) {
             //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept  = $this->input->post('dept');

	    $data['tnttype'] = $this->wtyp = $wtype;
            $this->uolt = $uoff;
	    if((!empty($dept))&&($dept != "null")){
               $data['seldept'] = $this->deptmt = $dept;
            }else{
                $this->deptmt= "All";
            }

            $whdata['sp_tnt'] = $wtype;
	    if(($dept != "null") && ($dept != "All")){
                $whdata['sp_dept']= $dept;
           }
           if(($uoff != "null") && ($uoff != "All")){
                $whdata['sp_uo'] = $uoff;
           }
                $data['records'] = $this->sismodel->get_distinctrecord('staff_position',$selectfield, $whdata);
//		$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        else{
            $data['records'] = $this->sismodel->get_distinctrecord('staff_position',$selectfield, $whdata);
//		print_r($data['records']); die();
//		$data['records'] = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        }
        $this->logger->write_logmessage("view"," view list staff position list" );
        $this->logger->write_dblogmessage("view"," view list staff position list");
        $this->load->view('report/listofstaffposition',$data);
    }


    public function desigemployeelist(){
        $selectfield ="emp_id,emp_code,emp_desig_code,emp_dept_code,emp_head,emp_name";
        $whorder = "emp_desig_code  asc";
	$cdate = date('Y-m-d');
//	$whdata = array ('emp_leaving' => NULL,'emp_dor>='=>$cdate);
	$whdata = $this->getprofilefilerdata();
	$whdata['emp_leaving'] = NULL;
	$whdata['emp_dor>=']=$cdate;
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype = $this->input->post('wtype');
            $uoff  = $this->input->post('uoff');
            $dept[]  = $this->input->post('dept');
            $desig[]  = $this->input->post('desig');

      	    $this->wtyp = $wtype;
            $this->desigm= $desig;
		$whdata['emp_worktype'] = $wtype;
		if($uoff != "null" && $uoff != "All" && $uoff != ""){
			$whdata['emp_uocid'] = $uoff;
		}
		if($dept != "null" && $dept != "All" && $dept != ""){
			$i=0;
			foreach($dept as $row){
                        	$this->deptmt = $row[$i];
                        	$names = $row;
				$i++;
                        }
		}
		if($desig != "null" && $desig != "All" && $desig != ""){
			$j=0;
			foreach($desig as $row1){
				$this->desigm = $row1[$j];
				$desigid=$row1;
				$j++;
			}
	//		 $whdata['emp_desig_code'] = $desigid;
		}
//	    if((!empty($dept))&&($dept != "null")){
  //              $this->deptmt = $dept;
    //        }
	    if((!empty($uoff))&&($uoff != "null")){
                $this->uolt = $uoff;
            }else{
            	$this->uolt = 'All';
            }
/*
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
*/
	// add doris geater than current date and reason is null  in whdata
          //  $whdata['emp_leaving'] = NULL;
          //  $whdata['emp_dor>='] = date('y-m-d');
		if((!empty($names)) && (!empty($desigid))){
                        $data['records']= $this->sismodel->get_orderlistspficemoreorwh2('employee_master',$selectfield,$whdata,'emp_desig_code',$desigid,'emp_dept_code',$names,$whorder);
		}elseif(!empty($names)){
                        $data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_dept_code',$names,$whorder);
                }elseif(!empty($desigid)){
                        $data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_desig_code',$desigid,$whorder);
                }else{
                        $data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'','',$whorder);
		}
      //      $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }//ifbutton
        else{
	// add doris geater than current date and reason is null  in whdata
        //    $whdata = array ('emp_leaving' => NULL,'emp_dor>='=>$cdate);
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        }
        $this->logger->write_logmessage("view"," view designation wise employee list" );
        $this->logger->write_dblogmessage("view"," view designation wise employee list");
        $this->load->view('report/desigemployeelist',$data);
    }

    public function getwhdata(){
		//get roleid from session
		$whdata ='';
		$rlid=$this->session->userdata('id_role');
		if ($rlid == 5){
			$usrid=$this->session->userdata('id_user');
			$deptid = '';
 	                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
         	       	$resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                	foreach($resu as $rw){
                        	$deptid=$rw->deptid;
                	}
			$whdata = array ('sp_dept' => $deptid);
			//array_push($whdata,'sp_dept' => $deptid);
		}
		if ($rlid == 10){
			$usrname=$this->session->userdata('username');
//			print_r( $usrname); die; 
			if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
			}else{
				$uoid=$this->lgnmodel->get_listspfic1('authorities','id','authority_email',$usrname)->id;
				$whdata = array ('sp_uo' => $uoid);
			}
		}
	return $whdata;
    }

    public function positionsummary(){
        $selectfield ="sp_emppost,sp_group,sp_sancstrenght,sp_position,sp_vacant";
        //$whdata = array ('sp_tnt' => 'Non Teaching');
        $whdata = '';
	$whdata = $this->getwhdata();
//	print_r($whdata); die;
        $whorder = "sp_group,sp_emppost asc";
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            	$wtype = $this->input->post('wtype');
            	if($wtype != "null"){
                	if($wtype!= "All"){
//                    		$whdata = array ('sp_tnt' => $wtype);
				 $whdata['sp_tnt']=$wtype;
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
	$whdata ='';
	$whdata = $this->getwhdata();
     $this->wtyp='';
     $this->desigm='All';
        $data['tnttype']='';
        if(isset($_POST['filter'])) {
        	$wtype = $this->input->post('wtype');
            	$post  = $this->input->post('post');
		$data['tnttype']=$this->wtyp = $wtype;
		$this->desigm = $post;
            	$whdata['sp_tnt']=$wtype;
            	if(!empty($post) && ($post!="All")){
			$whdata['sp_emppost']=$post;
            	}
            	$data['allpost']=$this->sismodel->get_distinctrecord('staff_position',$selectfield, $whdata);
        }
        else{
            	// $data['allpost']=$this->sismodel->get_distinctrecord('staff_position','sp_emppost','');
            	$data['allpost']=$this->sismodel->get_distinctrecord('staff_position',$selectfield,$whdata);
        }
        $this->logger->write_logmessage("view"," view position vacancy" );
        $this->logger->write_dblogmessage("view"," view position vacancy");
        $this->load->view('report/positionvacancy',$data);
    }
    /*Professor list report and service period*/
    public function professorlist(){
	$cdate = date('Y-m-d');
        $selectfield ="emp_id,emp_code,emp_name,emp_dor,emp_specialisationid,emp_dept_code,emp_doj";        
	$whorder = "emp_doj asc";
	$desig=null;
        
        $whdata=array('emp_leaving' => NULL,'emp_dor>='=>$cdate);
	
         $this->wtyp='';
     $this->desigm='';
        if(isset($_POST['filter'])) {
		$wtype = $this->input->post('wtype');
		$desig  = $this->input->post('desig');
		$dosc  = $this->input->post('dateofservcalc');
		$doa  = $this->input->post('dateofappoint');
		$doagp  = $this->input->post('dateofagp');
		if(!empty($wtype))
        		$whdata['emp_worktype'] = $wtype;
		if((!empty($desig)) && ($desig != "All"))
        		$whdata['emp_desig_code'] = $desig;
		//if(!empty($dosc))
        	//	$whdata['emp_desig_code'] = $dosc;
		if(!empty($doa))
        		$whdata['emp_doj >='] = $doa;
		//if(!empty($doagp))
	        //	$whdata['emp_desig_code'] = $doagp;
		$this->wtyp = $wtype;
	}
		$this->desig=$desig;

       // $getdesgid=$this->commodel->get_listspfic1('designation','desig_id','desig_name','Professor')->desig_id;
       // $whdata=array('emp_desig_code' => $getdesgid,'emp_leaving' => NULL,'emp_dor>='=>$cdate);
        $data['emplist'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        $this->logger->write_logmessage("view"," view list of professors in report " );
        $this->logger->write_dblogmessage("view"," view list of professors in report");
        $this->load->view('report/professorlist',$data);
    } 
    public function hodlist(){
	// get list of uo form authority table priority wise
	$data['uoc']=$this->lgnmodel->get_orderlistspficemore('authorities','priority,name,code','','name ASC');
	//get yesr list exist in database
	$data['ayear']=$this->sismodel->get_orderdistinctrecord('hod_list','YEAR(hl_datefrom) as hldf','','YEAR(hl_datefrom) ASC');
        $today= date("Y-m-d H:i:s"); 
//		$whdata=array('hl_status'=>'Fulltime','hl_dateto'=> '1000-01-01 00:00:00');
	$whdata=array();
//        $whdata=array('hl_dateto >='=> $today);
	$data['uolt']=''; 
        if(isset($_POST['filter'])) {
            	$uoff  = $this->input->post('uoff',true);
		if(!empty($uoff)){
			$whdata['hl_uopid']=$uoff;
			$data['uolt'] = $uoff;
		}
            	$lyear  = $this->input->post('lyear',true);
		if(!empty($lyear)){
			$whdata['(select YEAR(hl_datefrom))=']=$lyear;
			$whdata['(select YEAR(hl_dateto)) <=']=$lyear;
			$data['lyear']=$lyear;
		}
		if((empty($uoff)) && (empty($lyear))){
			$whdata=array('hl_status'=>'Fulltime','hl_dateto'=> '1000-01-01 00:00:00');
		}	
	}else{
		$whdata=array('hl_status'=>'Fulltime','hl_dateto'=> '1000-01-01 00:00:00');
	}
        $selectfield ="hl_userid,hl_empcode,hl_deptid,hl_scid,hl_uopid";
	$whorder = "hl_uopid asc";
        $data['allsc']=$this->sismodel->get_orderlistspficemore('hod_list',$selectfield,$whdata,$whorder);
        $this->logger->write_logmessage("view"," view list of HOD in report " );
        $this->logger->write_dblogmessage("view"," view list of HOD in report");
        $this->load->view('report/hodlist',$data);
    }

	public function uolist(){
		$orgcode=$this->commodel->get_listspfic1('org_profile','org_code','org_id',1)->org_code;
        	$data['campusl']=$this->commodel->get_listspfic2('study_center','sc_id','sc_name','org_code',$orgcode);
		$data['ayear']=$this->sismodel->get_orderdistinctrecord('uo_list','YEAR(ul_datefrom) as hldf','','YEAR(ul_datefrom) ASC');
		$whdata=array();
        	$today= date("Y-m-d H:i:s");
		if(isset($_POST['filter'])) {
            		$scid  = $this->input->post('campus',true);
                	if(!empty($scid)){
                        	$whdata['ul_scid']=$scid;
                        	$data['scid'] = $scid;
                	}
            		$lyear  = $this->input->post('lyear',true);
                	if(!empty($lyear)){
                        	$whdata['(select YEAR(ul_datefrom))=']=$lyear;
	                        $whdata['(select YEAR(ul_dateto)) <=']=$lyear;
        	                $data['lyear']=$lyear;
                	}
	                if((empty($scid)) && (empty($lyear))){
        	                $whdata=array('ul_status'=>'Fulltime','ul_dateto'=> '1000-01-01 00:00:00');
                	}
        	}else{
                	$whdata=array('ul_status'=>'Fulltime','ul_dateto'=> '1000-01-01 00:00:00');
        	}

//        $whdata=array('hl_dateto >='=> $today);
        //$selectfield ="ul_userid,ul_empcode,ul_uocode,ul_uoname,ul_id,  ul_modifydate";
	$selectfield ="ul_authuoid,ul_userid,ul_empcode, ul_uocode,ul_uoname,ul_id,  ul_modifydate";
	$whorder="ul_id asc,ul_authuoid ASC,  ul_modifydate DESC";
	//$whdata=array('ul_status'=>'Fulltime','ul_dateto >='=>$today);
//	$whdata=array('ul_status'=>'Fulltime','ul_dateto '=>'1000-01-01 00:00:00');
//	$grpby="ul_authuoid";
//	get_orderdistinctrecord($tbname,$selectfield,$whdata,$whorder)
        //$data['allsc']=$this->sismodel->get_distinctrecord('uo_list',$selectfield,'');
        $data['allsc']=$this->sismodel->get_orderdistinctrecord('uo_list',$selectfield,$whdata,$whorder);
       // print_r("seema===".$data['allsc']);
       // die();
  //      $data['allsc']=$this->sismodel->get_orderdistinctrecordgrpby('uo_list',$selectfield,$whdata,$whorder,$grpby);
        //$data['allsc']=$this->sismodel->get_orderlistspficemore('uo_list',$selectfield,$whdata,$whorder);
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
        $uo_select_box.='<option value=null>--Select University Officer--';
//	$usrname=$this->session->userdata('username');
  //      if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')){
        	$uo_select_box.='<option value='.All.'>'.All. ' ';
    //    }
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $auoname=$this->lgnmodel->get_listspfic1('authorities', 'name', 'id',$detail->emp_uocid)->name;
                $auocode=$this->lgnmodel->get_listspfic1('authorities', 'code', 'id',$detail->emp_uocid)->code;
              
               $uo_select_box.='<option value='.$detail->emp_uocid.'>'.$auoname. '(' .$auocode. ')'.' ';
            }
        }
//	arsort($uo_select_box);
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
//	get_orderdistinctrecord($tbname,$selectfield,$whdata,$whorder)
	$whorder = 'emp_dept_code asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('employee_master','emp_dept_code',$datawh,$whorder);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>--Select Department--';
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
	$rlid=$this->session->userdata('id_role');
        if ($rlid == 5){
                $usrid=$this->session->userdata('id_user');
                $deptid = '';
                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
                $datawh['emp_dept_code'] = $deptid;
        }

	$whorder = 'emp_desig_code asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('employee_master','emp_desig_code',$datawh,$whorder);
        $desig_select_box =' ';
        $desig_select_box.='<option value=null>-- Select Designation --';
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
	$whorder = 'emp_uocid asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('employee_master','emp_uocid',$datawh,$whorder);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>-- Select University Officer --';
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
	$whorder ='emp_dept_code asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('employee_master','emp_dept_code',$datawh,$whorder);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>-- Select Department --';
   //     $dept_select_box.='<option value='.All.'>'.All. ' ';
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
	//$datawh ='';
	$whdata = '';
        $whdata = $this->getwhdata();
        /*$rlid=$this->session->userdata('id_role');
        if ($rlid == 5){
        	$usrid=$this->session->userdata('id_user');
                $deptid = '';
                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                	$deptid=$rw->deptid;
                }
                $datawh = array ('sp_dept' => $deptid);
        }*/
        $whdata['sp_tnt'] = $combid;

//        $datawh=array('sp_tnt' => $combid);
	$whorder = 'sp_uo asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_uo',$whdata,$whorder);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>--Select University Officer--';
	$usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
        	$uo_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$uo_select_box.='<option value='.All.'>'.All. ' ';
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

	$rlid=$this->session->userdata('id_role');
        if ($rlid == 5){
                $usrid=$this->session->userdata('id_user');
                $deptid = '';
                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
                $datawh['sp_dept'] = $deptid;
        }

	$whorder = 'sp_emppost asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_emppost',$datawh,$whorder);
        $pt_select_box =' ';
        $pt_select_box.='<option value=null>--Select Post--';
	$usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname ==='admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
        	$pt_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$pt_select_box.='<option value='.All.'>'.All. ' ';
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
	 $whdata = '';
        $whdata = $this->getwhdata();
        $whdata['sp_tnt'] = $combid;
	$whorder = 'sp_uo asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_uo',$whdata,$whorder);
        $uo_select_box =' ';
        $uo_select_box.='<option value=null>--Select University Officer--';
	$usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
        	$uo_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$uo_select_box.='<option value='.All.'>'.All. ' ';
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
	$rlid=$this->session->userdata('id_role');
        if ($rlid == 5){
                $usrid=$this->session->userdata('id_user');
                $deptid = '';
                $whdatad = array('userid' => $usrid,'roleid' => $rlid);
                $resu = $this->sismodel->get_listspficemore('user_role_type','deptid',$whdatad);
                foreach($resu as $rw){
                        $deptid=$rw->deptid;
                }
                $datawh['sp_dept'] = $deptid;
        }

	$whorder = 'sp_dept asc ';
        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_dept',$datawh,$whorder);
        $dept_select_box =' ';
        $dept_select_box.='<option value=null>--Select Department--';
	$usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
        	$dept_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$dept_select_box.='<option value='.All.'>'.All. ' ';
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
	 $whdata = '';
        $whdata = $this->getwhdata();
        $whdata['sp_tnt'] = $combid;
	$whorder = 'sp_emppost asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_emppost',$whdata,$whorder);
        $post_select_box =' ';
        $post_select_box.='<option value=null>-- Select Post --';
	$usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($usrname === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
        	$post_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$post_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $postname=$this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$detail->sp_emppost)->desig_name;
                $postcode=$this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$detail->sp_emppost)->desig_code;
              
               $post_select_box.='<option value='.$detail->sp_emppost.'>'.$postname. '(' .$postcode. ')'.' ';
            }
        }
        echo json_encode($post_select_box);
    } 
/********************select post list according to selection type**********************/
    public function getuodeptpostlist_sp(){
        $combid= $this->input->post('wtuodept');
        $parts = explode(',',$combid);

        $whdata = '';
        $whdata = $this->getwhdata();
        $whdata['sp_tnt']= $parts[0];

        if($parts[1]!="All"){
                $whdata['sp_uo'] = $parts[1];
        }
        if($parts[2] !="All"){
                $whdata['sp_dept']=$parts[2];
        }
        $whorder = 'sp_emppost asc';

        $comb_data = $this->sismodel->get_orderdistinctrecord('staff_position','sp_emppost',$whdata,$whorder);
        $post_select_box =' ';
        $post_select_box.='<option value=null>-- Select --';
        $usrname=$this->session->userdata('username');
        if(($usrname === 'vc@tanuvas.org.in')||($usrname === 'registrar@tanuvas.org.in')||($username === 'admin')||($usrname === 'asection@tanuvas.org.in')||($usrname === 'rsection@tanuvas.org.in')){
                $post_select_box.='<option value='.All.'>'.All. ' ';
        }
        //$post_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $postname=$this->commodel->get_listspfic1('designation', 'desig_name', 'desig_id',$detail->sp_emppost)->desig_name;
                $postcode=$this->commodel->get_listspfic1('designation', 'desig_code', 'desig_id',$detail->sp_emppost)->desig_code;

               $post_select_box.='<option value='.$detail->sp_emppost.'>'.$postname. '(' .$postcode. ')'.' ';
            }
        }
        echo json_encode($post_select_box);
    }
    
    public function academic_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);
        
        $emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();

        //get all profile and service data
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        $selectfield="*";
        $whdata = array ('saq_empid' => $emp_id, 'saq_dgree LIKE'=> 'B%') ;
        $emp_data['ugraduate'] = $this->sismodel->get_orderlistspficemore('staff_academic_qualification',$selectfield,$whdata,'');
        
        $whdata = array ('saq_empid' => $emp_id, 'saq_dgree LIKE'=> 'M%') ;
        $emp_data['masters'] = $this->sismodel->get_orderlistspficemore('staff_academic_qualification',$selectfield,$whdata,'');
        //$str='B%,M%';
        $whdata = array ('saq_empid' => $emp_id,'saq_dgree NOT LIKE ' => 'B%','saq_dgree NOT LIKE ' => 'M%');
        $emp_data['schooledu'] = $this->sismodel->get_orderlistspficemore('staff_academic_qualification',$selectfield,$whdata,'');
        
        $whdata = array ('saq_empid' => $emp_id,'saq_dgree LIKE ' => 'P%','saq_dgree NOT LIKE ' => '%Diploma');
        $emp_data['doctrate'] = $this->sismodel->get_orderlistspficemore('staff_academic_qualification',$selectfield,$whdata,'');
        
        $whdata = array ('saq_empid' => $emp_id,'saq_dgree LIKE ' => '%Diploma');
        $emp_data['diploma'] = $this->sismodel->get_orderlistspficemore('staff_academic_qualification',$selectfield,$whdata,'');
        
        $this->load->view('report/academicprofile',$emp_data);
    }
    public function technical_profile() {

        //get id for employee to show data      
        $emp_id = $this->uri->segment(3);
        $emp_data['emp_id']=$emp_id;

        //for adding head next to designation
        $cdate=date('Y-m-d');
        $this->headflag="false";
        $empcode =$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id', $emp_id)->emp_code;
        $hwdata = array('hl_empcode' =>$empcode, 'hl_dateto >=' =>$cdate );
        $this->headflag=$this->sismodel->isduplicatemore("hod_list",$hwdata);

        //get all profile and service data
        $emp_data['data'] = $this->sismodel->get_listrow('employee_master','emp_id',$emp_id)->row();
        
        $emp_data['uoempid']=$this->getempuoid();
	$emp_data['hodempid']=$this->getemphodid();
        
        $selectfield="*";
        $whdata = array ('stq_empid' => $emp_id) ;
        $emp_data['technical'] = $this->sismodel->get_orderlistspficemore('staff_technical_qualification',$selectfield,$whdata,'');
        $this->load->view('report/technicalprofile',$emp_data);
    }
    
    public function retiredemplist() {
        
        $selectfield ="emp_id,emp_code,emp_uocid, emp_dept_code,emp_name,emp_head, emp_post,emp_desig_code,emp_schemeid,emp_email,emp_doj,emp_dor,emp_dob";
        $whorder = "emp_dor desc,emp_uocid asc, emp_dept_code  asc, emp_desig_code asc, emp_post asc";
	$cdate = date('Y-m-d');
        //$whdata = array ('emp_leaving ' =>NULL ,'emp_dor>=' =>$cdate); 
        //$whdata = array ('emp_dor <='=>$cdate,'emp_leaving !=' =>NULL); 
        $whdata = array ('emp_dor <='=>$cdate, 'emp_leaving '=> 'superannuation');
        
        $this->wtyp='';
        $this->uolt='';
        $this->deptmt='';
        $this->year='';
        $this->month='';
        $this->ftyp='';
        
        $wtype='';
        $uoff='';
        $dept='';
        $year='';
        $month='';
        $strfild='';
	$strng ='';
        if(isset($_POST['filter'])) {
            //echo "ifcase post of filter";
            $wtype  = $this->input->post('wtype',TRUE);
            $uoff   = $this->input->post('uoff',TRUE);
            $dept[] = $this->input->post('dept',TRUE);
            $year   = $this->input->post('year',TRUE);
            $month   = $this->input->post('month',TRUE);
            $strfild   = $this->input->post('ftype',TRUE);
            $strng   = $this->input->post('strin',TRUE);
		 
	    $this->wtyp = $wtype;
            $this->uolt = $uoff;
            $this->year=$year;
            $this->month=$month;
//	    $this->ftyp = $strfild;
	   
		if(!empty($wtype)){ 
			$whdata['emp_worktype']=$wtype;
                }    
        	if(($uoff !="All")&&(!empty($uoff))){
	    		$whdata['emp_uocid']=$uoff;
	    	}
	    $i=0;
	    if((!empty($dept))&&($dept != "null")){
                foreach($dept as $row){
                    $this->deptmt = $row[$i];
                    $names = $row;
                    $i++;
                  //  print_r(array_values($names));
                }
            }
            if(!empty($year)&&($year != "null")){
            	if(!empty($month)&&($month != "null")){
                	$whdata['SUBSTRING(emp_dor,1,7)  LIKE']=$year.'-'.$month.'%';
            	}
		else{       
                	$whdata['SUBSTRING(emp_dor,1,4)  LIKE']=$year.'%';
		}
            }
	    // for string search
            if((!empty($strfild))&&(!empty($strng))) {
                   $whdata[$strfild.' LIKE ' ] ='%'.$strng.'%';
            }       
//		print_r($whdata); die();
	    if(!empty($names)){
			$data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_dept_code',$names,$whorder);
            }else{
			$data['records']= $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'','',$whorder);
            }
        }
        else{
	    //$names = array('Dismissed','Expired','Resigned','VRS');	
	    $names = array('superannuation');	
           // $whdata = array ('emp_dor <='=>$cdate, 'emp_leaving IS NULL'=> null);
          //  $whdata = array ('emp_dor <='=>$cdate, 'emp_leaving !='=> null);
            $whdata = array ('emp_dor <='=>$cdate, 'emp_leaving '=> 'superannuation');
            $data['records'] = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
//            $data['records'] = $this->sismodel->get_orderlistspficemoreorwh('employee_master',$selectfield,$whdata,'emp_leaving',$names,$whorder);
        }
        $this->logger->write_logmessage("view"," view departmentt employee list" );
        $this->logger->write_dblogmessage("view"," view department employee list");
        $this->load->view('report/retiredemplist',$data);
    }

    public function getdeptlist_multisel(){
        $combid= $this->input->post('worktypeuo');
        $parts = explode(',',$combid); 
       
        if($parts[1]!="All"){
            $datawh=array('emp_worktype' => $parts[0],'emp_uocid' => $parts[1]);
        }
        else{
            $datawh=array('emp_worktype' => $parts[0]);
        }
	$whorder = 'emp_dept_code asc';
        $comb_data = $this->sismodel->get_orderdistinctrecord('employee_master','emp_dept_code',$datawh,$whorder);
        $dept_select_box=array();
        $dept_select_box =' ';
       // $dept_select_box.='<option value=null>-------Select Department--------';
       // $dept_select_box.='<option value='.All.'>'.All. ' ';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
                $deptname=$this->commodel->get_listspfic1('Department', 'dept_name', 'dept_id',$detail->emp_dept_code)->dept_name;
                $deptcode=$this->commodel->get_listspfic1('Department', 'dept_code', 'dept_id',$detail->emp_dept_code)->dept_code;
              
               $dept_select_box.='<option value='.$detail->emp_dept_code.'>'.$deptname. '(' .$deptcode. ')'.'</option> ';
               // $conbval=$deptname."-".$deptcode;
               // array_push($dept_select_box, $conbval);
            }
        }
        echo ($dept_select_box);
    } 
}

