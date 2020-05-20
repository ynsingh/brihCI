<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 * @name Rejoinemp.php
 * @author Manorama Pal (palseema30@gmail.com) Employee Rejoin the institute
 */

class Rejoinemp extends CI_Controller
{
 
        function __construct() {
            parent::__construct();
            $this->load->model('Common_model',"commodel");
            $this->load->model('Login_model',"lgnmodel"); 
            $this->load->model('SIS_model',"sismodel");
           // $this->load->model('Dependrop_model',"depmodel");
          //  $this->load->model("Mailsend_model","mailmodel");
            if(empty($this->session->userdata('id_user'))) {
                $this->session->set_flashdata('flash_data', 'You don\'t have access!');
                redirect('welcome');
            }
        }
 
    	public function index(){
    	
    	}
        
        public function Emprejoin($id){
                $this->roleid=$this->session->userdata('id_role');
                         
                $editemp_data['id'] = $id;
                $empmaster_data=$this->sismodel->get_listrow('employee_master','emp_id', $id);
                $editemp_data['editdata'] = $empmaster_data->row();
                
                
                $editemp_data['emprjcase'] ="rejoincase" ;
                /******************************employee master support******************************************************/
                $fieldems="ems_empid,ems_vci_status,ems_vci_statchapter,ems_vci_statregno,ems_vci_statregdate,ems_vci_statvaliddate,ems_vci_alliregno,ems_vci_alliregdate,ems_vci_allivaliddate";
                $whdataems = array ('ems_empid' => $id);
                $whorderems = '';
                $editemp_data['editems'] = $this->sismodel->get_orderlistspficemore('employee_master_support',$fieldems,$whdataems,$whorderems);
                
                /********************************this code for when employee rejoin (start)*************************************/
                //add detail in service record table------------------------------
                $emppost=$this->sismodel->get_listspfic1('employee_master','emp_post','emp_id',$id)->emp_post;
		$pstid=$this->commodel->get_listspfic1('designation','desig_id','desig_name',$emppost)->desig_id;
                $orderno=  $this->sismodel->get_listspfic1('employee_master','emp_apporderno','emp_id',$id)->emp_apporderno;
             
                $uoid= $this->sismodel->get_listspfic1('employee_master','emp_uocid','emp_id',$id)->emp_uocid;
                $campusid=$this->sismodel->get_listspfic1('employee_master','emp_scid','emp_id',$id)->emp_scid;
                $deptid=$this->sismodel->get_listspfic1('employee_master','emp_dept_code','emp_id',$id)->emp_dept_code;
                $schmid=$this->sismodel->get_listspfic1('employee_master','emp_schemeid','emp_id',$id)->emp_schemeid;
                $ddoid=$this->sismodel->get_listspfic1('employee_master','emp_ddoid','emp_id',$id)->emp_ddoid;
                $wrktype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$id)->emp_worktype;
                $group=$this->sismodel->get_listspfic1('employee_master','emp_group','emp_id',$id)->emp_group;
                $desigid=$this->sismodel->get_listspfic1('employee_master','emp_desig_code','emp_id',$id)->emp_desig_code;
                $pbid=$this->sismodel->get_listspfic1('employee_master','emp_salary_grade','emp_id',$id)->emp_salary_grade;
                $grade=$this->sismodel->get_listspfic1('employee_master','emp_grade','emp_id',$id)->emp_grade;
                $level=$this->sismodel->get_listspfic1('salary_grade_master','sgm_level','sgm_id',$pbid)->sgm_level;
                $gradepay=$this->sismodel->get_listspfic1('salary_grade_master','sgm_gradepay','sgm_id',$pbid)->sgm_gradepay;
                $emphead=$this->sismodel->get_listspfic1('employee_master','emp_head','emp_id',$id)->emp_head;
               // echo "emphead===".$emphead."orderno===".$orderno."desigcode===".$desigid."grade===".$grade;
                
                $cdate = date("Y-m-d");
		$cdatetime = date('Y-m-d H:i:s');
                
		$sddata = array(
                    'empsd_empid'       =>$id,
                    'empsd_orderno'     =>$orderno,
                    'empsd_authority'   =>$emphead,
                    'empsd_campuscode'  =>$campusid,
                    'empsd_ucoid'       =>$uoid,
		    'empsd_deptid'      =>$deptid,
                    'empsd_wcampid'     =>$campusid,
                    'empsd_wuoid '      =>$uoid,
                    'empsd_wdeptid'     =>$deptid,
                    'empsd_schemeid'    =>$schmid,
                    'empsd_ddoid'       =>$ddoid,
                    'empsd_worktype'    =>$wrktype,
                    'empsd_group'       =>$group,
                    'empsd_shagpstid'   =>$pstid,
                    'empsd_desigcode'   =>$desigid,
                    'empsd_pbid'        =>$pbid,
                    'empsd_level'       =>$level,
                    'empsd_gradepay'    =>$gradepay,
                    'empsd_pbdate'      =>$cdate,
                    'empsd_dojoin'      =>$cdate,
                    'empsd_dorelev'     =>$cdate,
                    'empsd_filename'    =>'',
                    'empsd_fsession'    =>'Forenoon',
                    'empsd_tsession'    =>'Forenoon',
                    'empsd_grade'       =>$grade,
                    'empsd_creatorid'  =>$this->session->userdata('username'),
                    'empsd_creatordate' =>$cdatetime,
                    'empsd_modifierid'  =>$this->session->userdata('username'),
                    'empsd_modifierdate' =>$cdatetime,
		);
		$sdiflag=$this->sismodel->insertrec('employee_servicedetail',$sddata);
		$this->logger->write_logmessage("insert", "data insert in employee_service detail table.".$id);
                $this->logger->write_dblogmessage("insert", "data insert in employee_service detail table.".$id );
                
                $emprjdata = array(
                    'emprj_empid'           =>$id,
                    'emprj_doj'             =>$cdate,
                    'emprj_leavedatefrom'   =>$cdate,
                    'emprj_leavedateto'     =>$cdate,
                    'emprj_reason'          =>'',
		    'emprj_rejoinreason'    =>'',
                    'emprj_remark'          =>'',
                    'emprj_creatorid'       =>$this->session->userdata('username'),
                    'emprj_creatordate'     =>$cdatetime,
                    'emprj_modifierid'      =>$this->session->userdata('username'),
                    'emprj_modifydate'    =>$cdatetime,
		);
                $emprjflag=$this->sismodel->insertrec('employee_rejoin',$emprjdata);
		$this->logger->write_logmessage("insert", "data insert in employee_rejoin table.".$id);
                $this->logger->write_dblogmessage("insert", "data insert in employee_rejoin table.".$id );
                                
                /********************************this code for when employee rejoin(closer) ********************************/
            
            $this->load->view('staffmgmt/editempprofile',$editemp_data);     
              
    	}//function closer 
 
}//class       



