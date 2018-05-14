
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 * @name: SIS_model
 * @author: Nagendra Kumar Singh (nksinghiitk@gmail.com)
 * @author: Manorama pal (palseema30@gmail.com)
 * @author: Om Prakash (omprakashkgp@gmail.com) check the record is already exist 
 */
class SIS_model extends CI_Model
{
 
    function __construct() {
        parent::__construct();
	$this->db2=$this->load->database('payroll', TRUE);
    }
    //insert the complete record from specific table
    public function insertrec($tbname, $datar){
         $this->db2->trans_start();
         if(! $this->db2->insert($tbname, $datar))
         {
            $this->db2->trans_rollback();
            return false;
         }
         else {
            $this->db2->trans_complete();
            return true;
         }
    }
    //update the complete record from specific table
    public function updaterec($tbname, $datar,$fieldname,$fieldvalue){
         $this->db2->trans_start();
         if(! $this->db2->where($fieldname, $fieldvalue)->update($tbname, $datar))
         {
            $this->db2->trans_rollback();
            return false;
         }
         else {
            $this->db2->trans_complete();
            return true;
         }
    }
    // check the record is already exist
    public function isduplicate($tbname,$fieldname,$fieldvalue) {
        $this->db2->from($tbname);
        $this->db2->where($fieldname, $fieldvalue);
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
                return true;
        } else {
                return false;
        }
    }
    
    //get the list of one/specific  records with  one specific fields for specific values
    public function get_listspfic1($tbname,$selfield1,$fieldname='',$fieldvalue=''){
	$this->db2->flush_cache();
	$this->db2->select($selfield1);
	$this->db2->from($tbname);
	$this->db2->limit(1);
	if (($fieldname != '') && ($fieldvalue != '')){
            $this->db2->where($fieldname, $fieldvalue);
	}
        return $this->db2->get()->row();
    }

//get the list of all records with  two specific fields for specific values
    public function get_listspfic2($tbname,$selfield1,$selfield2,$fieldname='',$fieldvalue='',$grpby=''){
                $this->db2->flush_cache();
                $this->db2->from($tbname);
                $this->db2->select($selfield1);
                $this->db2->select($selfield2);
                if($grpby != ''){
                        $this->db2->group_by($grpby);
                }
                if (($fieldname != '') && ($fieldvalue !='')){
                        $this->db2->where($fieldname, $fieldvalue);
                }
       // print_r($this->db->get()->result());
        return $this->db2->get()->result();
    }

    //    getting different field from table - $selectfield ('a,b,c');
    public function get_listspficemore($tbname,$selectfield,$data){
	$this->db2->flush_cache();
	$this->db2->from($tbname);
        $this->db2->select($selectfield);
        $this->db2->where($data);
        return $this->db2->get()->result();
    }
    //    getting different field from table - $selectfield ('a,b,c');
    //    $whdata = array('name' => $name, 'title' => $title, 'status' => $status);
    //    $whorder = ("column1 asc,column2 desc");
    public function get_orderlistspficemore($tbname,$selectfield,$whdata,$whorder){
        $this->db2->flush_cache();
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
        if($whorder != ''){
                $this->db2->order_by($whorder);
        }
        return $this->db2->get()->result();
    }

    public function get_orderlistspficemoreorwh($tbname,$selectfield,$whdata,$orwhin,$whorder){
        $this->db2->flush_cache();
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
	if($orwhin != ''){
		$this->db2->where_in('emp_specialisationid', $orwhin);
	}
        if($whorder != ''){
                $this->db2->order_by($whorder);
        }
        return $this->db2->get()->result();
    }

	

    // get the join  table result value
    public function get_jointbrecord($tbname,$selectfield,$jointbname,$joincond,$jtype,$whdata){
            $this->db2->flush_cache();
            $this->db2->select($selectfield);
            $this->db2->from($tbname);
            $this->db2->join($jointbname,$joincond,$jtype);
            if($whdata != ''){
                        $this->db2->where($whdata);
            }
            return $this->db2->get()->result();
    }

    public function get_distinctrecord($tbname,$selectfield,$whdata){
            $this->db2->flush_cache();
            $this->db2->distinct();
            $this->db2->select($selectfield);
            $this->db2->from($tbname);
            if($whdata != ''){
                        $this->db2->where($whdata);
            }
        return $this->db2->get()->result();
    }

    public function get_orderdistinctrecord($tbname,$selectfield,$whdata,$whorder){
            $this->db2->flush_cache();
            $this->db2->distinct();
            $this->db2->select($selectfield);
            $this->db2->from($tbname);
            if($whdata != ''){
                $this->db2->where($whdata);
            }
	    if($whorder != ''){
                $this->db2->order_by($whorder);
            }
        return $this->db2->get()->result();
    }
    /** this function for get hod user list according to study center************************/
    //get the complete record from specific table
    public function get_list($tbname){
         $this->db2->from($tbname);
         return $this->db2->get()->result();
    }
    
    //get the complete of record for specific value
    public function get_listrow($tbname,$fieldname,$fieldvalue){
         $this->db2->from($tbname);
	 		$this->db2->where($fieldname, $fieldvalue);
         return $this->db2->get();
    }

// check the record is already exist with as many field you want
    public function isduplicatemore($tbname,$data) {
                $this->db2->flush_cache();
                $this->db2->from($tbname);
                $this->db2->where($data);
        $query = $this->db2->get();
        if ($query->num_rows() > 0) {
                return true;
        } else {
                return false;
        }
    }
    
    
    
    /*************************************Start transfer order pdf *****************************************************************************/
    
    public function gentransferordertpdf($empid){
        
        $this->orgname=$this->commodel->get_listspfic1('org_profile','org_name','org_id',1)->org_name;
        $this->orgaddres=$this->commodel->get_listspfic1('org_profile','org_address1','org_id',1)->org_address1;
        $this->orgpincode=$this->commodel->get_listspfic1('org_profile','org_pincode','org_id',1)->org_pincode;
        $this->regname=$this->sismodel->get_listspfic1('user_input_transfer','uit_registrarname','uit_staffname',$empid)->uit_registrarname;
        $this->uitdesig=$this->sismodel->get_listspfic1('user_input_transfer','uit_desig','uit_staffname',$empid)->uit_desig;
        $this->data=$this->sismodel->get_listrow('user_input_transfer','uit_staffname',$empid);
        $spec_data['detail'] = $this->data->row();
        $year=date('Y');
        // move file to directory code for photo
	$desired_dir = 'uploads/SIS/transferorder/'.$year;
        // Create directory if it does not exist
        if(is_dir($desired_dir)==false){
            mkdir("$desired_dir", 0700);
        }
        $emp_pf=$this->sismodel->get_listspfic1('employee_master', 'emp_code', 'emp_id',$empid)->emp_code;
       	//add pdf code to store and view pdf file
	$temp = $this->load->view('staffmgmt/transferordercopy', $spec_data, TRUE);
	$pth='uploads/SIS/transferorder/'.$year.'/'.$emp_pf.'.pdf';
	$this->genpdf($temp,$pth);
    }
    public function genpdf($content,$path){
	$this->load->library('pdf');
	$this->pdf = new DOMPDF();	
     	// pass html to dompdf object
    	$this->pdf->load_html($content);
	$this->pdf->set_paper("A4", "portrait");
        $this->pdf->render();
	//set paper size
        $pdf = $this->pdf->output();
	file_put_contents($path, $pdf); 
    }
    
    /************************************* closer transfer order pdf *****************************************************************************/
   public function searchemp_profile($tbname,$worktype,$keyword)
    {
      
       $this->db2->select('emp_id,emp_code,emp_name,emp_scid,emp_uocid,emp_dept_code,emp_desig_code,emp_email,emp_phone,emp_aadhaar_no')->from('employee_master')->where("emp_name LIKE '$keyword%'")->where("emp_worktype", $worktype);
        return $this->db2->get()->result();
    }
    
    /*************************************updating the staff position table*****************/
    public function updatestaffposition($campus,$uocid,$deptid,$emppost,$worktype,$emptype){
    // public function updatestaffposition($campus,$uocid,$deptid,$schmid,$emppost,$worktype,$emptype){
        /*$datawh=array('sp_campusid' => $campus,'sp_uo' => $uocid, 'sp_dept' => $deptid,
            'sp_schemecode'=> $schmid,'sp_emppost' => $emppost, 'sp_tnt' => $worktype,'sp_type' =>$emptype);*/
        $datawh=array('sp_campusid' => $campus,'sp_uo' => $uocid, 'sp_dept' => $deptid,
            'sp_emppost' => $emppost, 'sp_tnt' => $worktype,'sp_type' =>$emptype);
        $emppost_data = $this->sismodel->get_listspficemore('staff_position','sp_id,sp_type,sp_position,sp_vacant,sp_pospermanent,sp_postemporary,sp_vpermanenet,sp_vtemporary',$datawh);
        if(!empty($emppost_data)){
            $update_data = array();
            $upempdata_flag = false;
            foreach($emppost_data as $empdata){
                
                if($empdata->sp_type == 'Permanent'){
                    
                    $position = $empdata->sp_position+1;
                    $vacant   = $empdata->sp_vacant-1;
                    $pospermanent=$empdata->sp_pospermanent+1;
                    $vpermanenet =$empdata->sp_vpermanenet-1;
                    $update_data = array(
                        'sp_position'=>$position,
                        'sp_vacant'=>$vacant,
                        'sp_pospermanent'=>$pospermanent,
                        'sp_vpermanenet'=>$vpermanenet,
                        'sp_org_id'=> '1'
                    );
                    //echo "vacacny=per==".$position.$vacant.$pospermanent.$vpermanenet;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                }
                if($empdata->sp_type == 'Temporary'){
                    
                    $position = $empdata->sp_position+1;
                    $vacant   = $empdata->sp_vacant-1;
                    $postemporary =$empdata->sp_postemporary+1;
                    $vtemporary = $empdata->sp_vtemporary-1;
                    $update_data = array(
                       'sp_position'=>$position,
                       'sp_vacant'=>$vacant,
                       'sp_postemporary'=>$postemporary,
                       'sp_vtemporary'=>$vtemporary,
                       'sp_org_id'=> '1'
                    );
                   // echo "vacacny tempo===".$position.$vacant.$postemporary.$vtemporary;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                }
               
            } //foreach   
            if(!upempdata_flag){
                $this->logger->write_logmessage("error","Error in update staff position ", "Error in staff position record update" );
                $this->logger->write_dblogmessage("error","Error in update staff position", "Error in staff position record update");
            
            }
            else{
                $this->logger->write_logmessage("update","update staff position ", "staff position record updated successfully ");
                $this->logger->write_dblogmessage("update","staff position", "staff position record updated successfully");
            
            }
           
        }  //ifempty  
        
    }//function close
    /***********************************close of staff position*********************************************/   
    public function hoduser($scid){
       /* $selectfield ="userid,scid,deptid,usertype";
        $whdata=array('roleid' => '5','scid' => $scid);
        $whorder = "userid asc";
        $data = $this->get_orderlistspficemore('user_role_type',$selectfield,$whdata,$whorder);*/
        $today= date("Y-m-d H:i:s"); 
        $selectfield ="hl_userid,hl_empcode,hl_deptid,hl_scid";
        $whdata=array('hl_dateto >='=> $today,'hl_scid' => $scid);
        $whorder = "hl_dateto asc";
        $data = $this->get_orderlistspficemore('hod_list',$selectfield,$whdata,$whorder);
        return $data;
    }
    /** colse this function for get hod user list according to study center************************/
    
    public function emplist($uo,$dept,$post){
	$post1 = $this->commodel->get_listspfic1('designation','desig_name','desig_id', $post)->desig_name;
        $selectfield ="emp_name,emp_desig_code,emp_post,emp_email";
        //$whdata = array ('emp_uocid' => $uo,'emp_dept_code' => $dept ,'emp_desig_code' => $post );
        $whdata = array ('emp_uocid' => $uo,'emp_dept_code' => $dept ,'emp_post' => $post1 );
        $whorder = "emp_post asc";
        $data = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        return $data;
             
    }
    public function postlist_sp($uo,$dept,$tnt){
        $selectfield ="sp_emppost,sp_sancstrenght,sp_position , sp_vacant";
        $whorder = "sp_emppost asc";
        if(!empty($tnt)){
            $whdata = array('sp_uo'=> $uo,'sp_dept' => $dept,'sp_tnt' => $tnt);
        }
        else{
            $whdata = array('sp_uo'=> $uo,'sp_dept' => $dept);
        }
        $data = $this->sismodel->get_orderlistspficemore('staff_position',$selectfield,$whdata,$whorder);
        return $data;
             
    }
    public function deptlist_sp($uo,$tnt,$seldept){
        $selectfield ="sp_dept";
	//$whdata = array('sp_uo'=> $uo);
        $whorder = "sp_dept asc";
        if(!empty($tnt)){
            if(!empty($seldept)&&($seldept !="All")){
                $whdata = array('sp_uo'=> $uo,'sp_tnt' => $tnt,'sp_dept' =>$seldept);  
            }
            else{
                $whdata = array('sp_uo'=> $uo,'sp_tnt' => $tnt);
            }
        }
        else{
            $whdata = array('sp_uo'=> $uo);    
        }
        $data = $this->sismodel->get_distinctrecord('staff_position',$selectfield,$whdata);
        return $data;
             
    }
   
    /*============== update staff position table at the time of employee retirement ====================================*/
    public function updatestaffposition2($campus,$uocid,$deptid,$emppost,$worktype,$emptype,$schemeid){
        $datawh=array('sp_campusid' => $campus,'sp_uo' => $uocid, 'sp_dept' => $deptid,
            'sp_emppost' => $emppost, 'sp_tnt' => $worktype,'sp_type' =>$emptype,'sp_schemecode' =>$schemeid);
        $emppost_data = $this->sismodel->get_listspficemore('staff_position','sp_id,sp_type,sp_position,sp_vacant,sp_pospermanent,sp_postemporary,sp_vpermanenet,sp_vtemporary',$datawh);
        if(!empty($emppost_data)){
            $update_data = array();
            $upempdata_flag = false;
            foreach($emppost_data as $empdata){
                
                if($empdata->sp_type == 'Permanent' && $empdata->sp_position > 0){
                    
                    $position = $empdata->sp_position-1;
                    $vacant   = $empdata->sp_vacant+1;
                    $pospermanent=$empdata->sp_pospermanent-1;
                    $vpermanenet =$empdata->sp_vpermanenet+1;
                    
                    $update_data = array(
                        'sp_position'=>$position,
                        'sp_vacant'=>$vacant,
                        'sp_pospermanent'=>$pospermanent,
                        'sp_vpermanenet'=>$vpermanenet,
                        'sp_org_id'=> '1'
                    );
                    //echo "vacacny=per==".$position.$vacant.$pospermanent.$vpermanenet;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                    if(!upempdata_flag){
                        $this->logger->write_logmessage("error","Error in update staff position ", "Error in staff position record update" );
                        $this->logger->write_dblogmessage("error","Error in update staff position", "Error in staff position record update");
            
                    }
                    else{
                        $this->logger->write_logmessage("update","update staff position ", "staff position record updated successfully ");
                        $this->logger->write_dblogmessage("update","staff position", "staff position record updated successfully");
            
                        }
                }
                if($empdata->sp_type == 'Temporary' && $empdata->sp_position >0){
                    
                    $position = $empdata->sp_position-1;
                    $vacant   = $empdata->sp_vacant+1;
                    $postemporary =$empdata->sp_postemporary-1;
                    $vtemporary = $empdata->sp_vtemporary+1;
                    $update_data = array(
                       'sp_position'=>$position,
                       'sp_vacant'=>$vacant,
                       'sp_postemporary'=>$postemporary,
                       'sp_vtemporary'=>$vtemporary,
                       'sp_org_id'=> '1'
                    );
                   // echo "vacacny tempo===".$position.$vacant.$postemporary.$vtemporary;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                    if(!upempdata_flag){
                        $this->logger->write_logmessage("error","Error in update staff position ", "Error in staff position record update" );
                        $this->logger->write_dblogmessage("error","Error in update staff position", "Error in staff position record update");
            
                    }
                    else{
                    $this->logger->write_logmessage("update","update staff position ", "staff position record updated successfully ");
                    $this->logger->write_dblogmessage("update","staff position", "staff position record updated successfully");
            
                    }
                }
               
            } //foreach   
           
           
        }  //ifempty  
        
    }//function close
    /***********************************close of staff position*********************************************/  
    
    /************Add employee record in service details table at the time of profile creation*************/
    public function insertsdetail($empid,$campus,$uocid,$deptid,$desigid,$schemeid,$ddoid,$group,$pbid,$gradepay,$sapostid,$pbdate,$joindate,$relvdate){
        /* update record in  additional assignments */
        $dupcheck = array(
            'empsd_empid'       =>$empid,   
            'empsd_campuscode'  =>$campus,
            'empsd_ucoid'       =>$uocid,
            'empsd_deptid'      =>$deptid,
            'empsd_schemeid'    =>$schemeid,
            'empsd_ddoid'       =>$ddoid,
            'empsd_group'       =>$group,
            'empsd_shagpstid'   =>$sapostid,
            'empsd_desigcode '  =>$desigid,
            'empsd_pbid'        =>$pbid,
            'empsd_pbdate'      =>$pbdate,
            'empsd_gradepay'    =>$gradepay
                 
        ); 
        $emidexits= $this->sismodel->isduplicatemore('employee_servicedetail',$dupcheck);
        if(!$emidexits){
            $data = array(
                'empsd_empid'       =>$empid,   
                'empsd_campuscode'  =>$campus,
                'empsd_ucoid'       =>$uocid,
                'empsd_deptid'      =>$deptid,
                'empsd_schemeid'    =>$schemeid,
                'empsd_ddoid'       =>$ddoid,
                'empsd_group'       =>$group,
                'empsd_shagpstid'   =>$sapostid,
                'empsd_desigcode '  =>$desigid,
                'empsd_pbid'        =>$pbid,
                'empsd_pbdate'      =>$pbdate,
                'empsd_gradepay'    =>$gradepay,
                'empsd_dojoin'      =>$joindate,
                'empsd_dorelev'     =>$relvdate
                 
             ); 
            /* insert record in  service detail */
            $this->sismodel->insertrec('employee_servicedetail', $data);
            $this->logger->write_logmessage("insert", "data insert in servicedetail table.");
            $this->logger->write_dblogmessage("insert", "data insert in servicedetail table." );
        }
        else{
            
            $data = array(
                'empsd_empid'       =>$empid,   
                'empsd_campuscode'  =>$campus,
                'empsd_ucoid'       =>$$uocid,
                'empsd_deptid'      =>$deptid,
                'empsd_schemeid'    =>$schemeid,
                'empsd_ddoid'       =>$ddoid,
                'empsd_group'       =>$group,
                'empsd_shagpstid'   =>$sapostid,
                'empsd_desigcode '  =>$desigid,
                'empsd_pbid'        =>$pbid,
                'empsd_pbdate'      =>$pbdate,
                'empsd_gradepay'    =>$gradepay,
                'empsd_dojoin'      =>$joindate,
                'empsd_dorelev'     =>$relvdate
                 
            ); 
           /* update record in  service detail */
            $this->sismodel->updaterec('employee_servicedetail', $data,'empsd_id',$id);
            $this->logger->write_logmessage("update", "data update in serrvicedetail table.");
            $this->logger->write_dblogmessage("update", "data update in servicedetail table." ); 
        }
    }
    
    /************closer  employee record in service details table at the time of profile creation*************/
    
    function __destruct() {
        $this->db2->close();
    }
    
}    
