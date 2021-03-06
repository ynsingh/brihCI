
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 * @name: SIS_model
 * @author: Nagendra Kumar Singh (nksinghiitk@gmail.com)
 * @author: Manorama pal (palseema30@gmail.com) Add function for saving salaryslip pdf
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
	//update the complete record from specific table with multiple where condition
//$datawh = array('name' => $name, 'title' => $title, 'status' => $status);
    public function updaterecarry($tbname, $datar,$datawh){
        $this->db2->trans_start();
        if(! $this->db2->where($datawh)->update($tbname, $datar))
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
// get the sum of values
    public function get_sumofvalue($tbname,$selectfield,$whdata){
            $this->db2->flush_cache();
            $this->db2->select_sum($selectfield);
            $this->db2->from($tbname);
            $this->db2->where($whdata);
            return $this->db2->get()->result();
    }

    // get the max values of selected field
    public function get_maxvalue($tbname,$selectfield,$whdata){
            $this->db2->flush_cache();
            $this->db2->select_max($selectfield);
            $this->db2->from($tbname);
	    if($whdata != ''){
            	$this->db2->where($whdata);
	    }
            return $this->db2->get()->result();
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
	// $spl = "empid NOT IN" in two table;
	public function get_rundualquery($sel1,$tb1,$sel2, $tb2,$spl,$whdata,$whorder){
		$this->db2->select($sel1)->from($tb1);
		$subQuery =  $this->db2->get_compiled_select();
		$this->db2->select($sel2)
	         ->from($tb2)
        	 ->where("$spl ($subQuery)", NULL, FALSE);
		if($whdata != ''){
                	$this->db2->where($whdata);
	        }
        	if($whorder != ''){
                	$this->db2->order_by($whorder);
        	}
	        return $this->db2->get()->result();
	}
                
	// $spl = "empid NOT IN" in one table;
	public function get_rundualquery1($sel1,$tb1,$sel2, $spl,$whdata){
		$this->db2->select($sel1)->from($tb1)->where($whdata);
		$subQuery =  $this->db2->get_compiled_select();
		$this->db2->select($sel2)
	         ->from($tb1)
        	 ->where("$spl ($subQuery)", NULL, FALSE);
		if($whdata != ''){
                	$this->db2->where($whdata);
	        }
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

//SELECT * FROM (     SELECT * FROM salary_loan_head ORDER BY slh_id DESC LIMIT 17 ) sub ORDER BY slh_id ASC;
//$this->db->select('SELECT *      FROM chat      WHERE (userID = $session AND toID = $friendID)      OR (userID = $friendID AND toID = $session)  
  //   ORDER BY id DESC      LIMIT 10') AS `table` ORDER by id ASC', FALSE); 

    public function get_orderlistspficemorelimit($tbname,$selectfield,$whdata,$whorder,$limit){
        $this->db2->flush_cache();
	//$this->db->select(('SELECT $selectfield FROM $tbname WHERE ORDER BY $whorder LIMIT $limit') table ORDER by slh_id asc );
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
        if($whorder != ''){
                $this->db2->order_by($whorder);
        }
	if(!empty($limit)){
		$this->db2->limit($limit);
	}

        return $this->db2->get()->result();
    }
    //    getting different field from table - $selectfield ('a,b,c');
    //    $whdata = array('name' => $name, 'title' => $title, 'status' => $status);
    //    $whorder = ("column1 asc,column2 desc");
    public function get_orderlistspficemoreorwhnotin($tbname,$selectfield,$whdata,$orfield,$orwhin,$whorder){
        $this->db2->flush_cache();
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
	if(($orwhin != '') && (!empty($orwhin)) && (count($orwhin) != 0)){
                $this->db2->where_not_in($orfield, $orwhin);
        }
        if($whorder != ''){
                $this->db2->order_by($whorder);
        }
//	print_r($this->db2); die();
        return $this->db2->get()->result();
    }

    // get the join  table result value
    public function get_orderlistspficemoreorwh($tbname,$selectfield,$whdata,$orfield,$orwhin,$whorder){
        $this->db2->flush_cache();
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
//	if($orwhin != ''){
//		$this->db2->where_in($orwhin);
	if(($orwhin != '') && (!empty($orwhin)) && (count($orwhin) != 0)){
	//if((!empty($orwhin)) && (count($orwhin) != 0)){
		$this->db2->where_in($orfield, $orwhin);
	}
        if($whorder != ''){
                $this->db2->order_by($whorder);
        }
        return $this->db2->get()->result();
    }


    // get the join  table result value
    public function get_orderlistspficemoreorwh2($tbname,$selectfield,$whdata,$orfield,$orwhin,$orfield1,$orwhin1,$whorder){
        $this->db2->flush_cache();
        $this->db2->from($tbname);
        $this->db2->select($selectfield);
        if($whdata != ''){
                $this->db2->where($whdata);
        }
	if($orwhin != ''){
		$this->db2->where_in($orfield, $orwhin);
	}
	if($orwhin1 != ''){
		$this->db2->where_in($orfield1, $orwhin1);
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

    // get the join  table result value with where not in condition
    public function get_jointbrecord1($tbname,$selectfield,$jointbname,$joincond,$jtype,$whdata,$orfield='',$orwhin=''){
            $this->db2->flush_cache();
            $this->db2->select($selectfield);
            $this->db2->from($tbname);
            $this->db2->join($jointbname,$joincond,$jtype);
            if($whdata != ''){
                        $this->db2->where($whdata);
            }
	    if(($orwhin != '') && (!empty($orwhin)) && (count($orwhin) != 0)){
                $this->db2->where_not_in($orfield, $orwhin);
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
	public function get_orderdistinctrecordgrpby($tbname,$selectfield,$whdata,$whorder,$grpby){
            $this->db2->flush_cache();
            $this->db2->distinct();
            $this->db2->select($selectfield);
            $this->db2->from($tbname);
	    $this->db2->group_by($grpby);
            if($whdata != ''){
                $this->db2->where($whdata);
            }
            if($whorder != ''){
                $this->db2->order_by($whorder);
            }
        return $this->db2->get()->result();
    }
	// echo mask($string,null,strlen($string)-4); // *************5678
	function mask ( $str, $start = 0, $length = null ) {
	    	$mask = preg_replace ( "/\S/", "*", $str );
    		if( is_null ( $length )) {
        		$mask = substr ( $mask, $start );
		        $str = substr_replace ( $str, $mask, $start );
    		}else{
        		$mask = substr ( $mask, $start, $length );
        		$str = substr_replace ( $str, $mask, $start, $length );
    		}
    		return $str;
	}	

    /** this function for get hod user list according to study center************************/
    //get the complete record from specific table
    public function get_list($tbname){
         $this->db2->from($tbname);
         return $this->db2->get()->result();
    }
    
    //get the complete of record for specific value
    public function get_listrow($tbname,$fieldname,$fieldvalue,$selectfield='*'){
         $this->db2->from($tbname);
	 $this->db2->select($selectfield);
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
            mkdir("$desired_dir", 0777);
        }
        $emp_pf=$this->sismodel->get_listspfic1('employee_master', 'emp_code', 'emp_id',$empid)->emp_code;
       	//add pdf code to store and view pdf file
	$temp = $this->load->view('staffmgmt/transferordercopy', $spec_data, TRUE);
	$pth=$desired_dir.'/'.$emp_pf.'.pdf';
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
            if(!$upempdata_flag){
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
    public function emplist($uo,$dept,$post,$scheme){
	$cdate = date('Y-m-d');
	$post1 = $this->commodel->get_listspfic1('designation','desig_name','desig_id', $post)->desig_name;
        $selectfield ="emp_id,emp_code,emp_name,emp_desig_code,emp_post,emp_email";
        //$whdata = array ('emp_uocid' => $uo,'emp_dept_code' => $dept ,'emp_desig_code' => $post );
        $whdata = array ('emp_uocid' => $uo,'emp_dept_code' => $dept ,'emp_post' => $post1,'emp_schemeid'=>$scheme );
	$whdata['emp_leaving'] = NULL;
        $whdata['emp_dor>=']=$cdate;

        $whorder = "emp_post asc";
        $data = $this->sismodel->get_orderlistspficemore('employee_master',$selectfield,$whdata,$whorder);
        return $data;
             
    }
/*
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
*/
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
                    //echo "vacacny=per=seema=".$position.$vacant.$pospermanent.$vpermanenet;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                    if(!$upempdata_flag){
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
                    //echo "vacacny tempo===".$position.$vacant.$postemporary.$vtemporary;
                    $upempdata_flag=$this->updaterec('staff_position', $update_data,'sp_id',$empdata->sp_id);
                    if(!$upempdata_flag){
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
    public function insertsdetail($empid,$campus,$uocid,$deptid,$desigid,$schemeid,$ddoid,$group,$pbid,$gradepay,$sapostid,$pbdate,$joindate,$relvdate,$orderno){

	    if(empty($pbdate)){
		    $pbdate='1000-01-01 00:00:00';
	    }
	    if(empty($joindate)){
		    $joindate='1000-01-01 00:00:00';
	    }
	    if(empty($relvdate)){
		    $relvdate='1000-01-01 00:00:00';
	    }
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
		'empsd_dorelev'     =>$relvdate,
		'empsd_orderno'     =>$orderno
                 
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
		'empsd_dorelev'     =>$relvdate,
		'empsd_orderno'     =>$orderno
                 
            ); 
           /* update record in  service detail */
            $this->sismodel->updaterec('employee_servicedetail', $data,'empsd_id',$id);
            $this->logger->write_logmessage("update", "data update in serrvicedetail table.");
            $this->logger->write_dblogmessage("update", "data update in servicedetail table." ); 
        }
    }
    
    /************closer  employee record in service details table at the time of profile creation*************/
    
    /************************************start generate multiple transfer order pdf for staff**********************/
    public function insertdata($tbname, $datar){
        $this->db2->trans_start();
        if(! $this->db2->insert($tbname, $datar))
        {
            $this->db2->trans_rollback();
            return false;
        }
        else {
            $entry_id = $this->db2->insert_id();
            $this->db2->trans_complete();
            return $entry_id;
        }     
    }
    /************************************closer generate multiple transfer order pdf for staff**********************/
    public function multipleorder($selectuitid){
        $spec_data['orgname']=$this->commodel->get_listspfic1('org_profile','org_name','org_id',1)->org_name;
        $spec_data['orgaddres']=$this->commodel->get_listspfic1('org_profile','org_address1','org_id',1)->org_address1;
        $spec_data['orgpincode']=$this->commodel->get_listspfic1('org_profile','org_pincode','org_id',1)->org_pincode;
        if(!empty($selectuitid)){
            //foreach($selectuitid as $allids)
            //$spec_data['detail'] = $this->data->row();
            $this->data=$this->sismodel->get_listrow('user_input_transfer','uit_id',$selectuitid[0]);
            $ddata=$this->data->row();
            $spec_data['regname']=$this->sismodel->get_listspfic1('user_input_transfer','uit_registrarname','uit_id',$ddata->uit_id)->uit_registrarname;
            $spec_data['uitdesig']=$this->sismodel->get_listspfic1('user_input_transfer','uit_desig','uit_id',$ddata->uit_id)->uit_desig;
           // $this->data=$this->sismodel->get_listrow('user_input_transfer','uit_id',$allids);
           // $spec_data['detail'] = $this->data->row();
            $spec_data['alldetail'] = $selectuitid;
            $year=date('Y');
            // move file to directory code for photo
            $desired_dir = 'uploads/SIS/multitransferordercopy/'.$year;
            // Create directory if it does not exist
            if(is_dir($desired_dir)==false){
                mkdir($desired_dir, 0700,true);
            }
           // $emp_pf=$this->sismodel->get_listspfic1('employee_master', 'emp_code', 'emp_id',$empid)->emp_code;
            //add pdf code to store and view pdf file
            $temp = $this->load->view('staffmgmt/multitransferordercopy', $spec_data, TRUE);
           // $pth='uploads/SIS/transferorder/'.$year.'/'.$emp_pf.'.pdf';
            $today = date("Ymdhms");      
            $pth='uploads/SIS/multitransferordercopy/'.$year.'/'.'multitransferorder'.$today.'.pdf';
            $this->genpdf($temp,$pth);
        }
        else{
            print_r(" I am in else loop of generate multipleorders for the staff transfer");
        }
    }
    
    // delete the specific record form specific table
    public function deleterow($tbname,$fieldname,$fieldvalue){
	$this->db2->trans_start();
        if ( ! $this->db2->delete($tbname, array($fieldname => $fieldvalue)))
        {
            $this->db2->trans_rollback();
            return false;
	}
        else {
            $this->db2->trans_complete();
            return true;
        }
    }
    
    /******************************* vacancy available check from staff position *************************************/
    public function checkvacancy($campusid,$uoid,$deptid,$shmeid,$desigid,$worktype,$group,$emptype,$payscaleid){
	$datawh=array('sp_campusid' => $campusid,'sp_uo' => $uoid, 'sp_dept' => $deptid,'sp_schemecode' => $shmeid,
            'sp_emppost' => $desigid, 'sp_tnt' => $worktype,'sp_group' => $group,'sp_type' => $emptype);
	$emptype_data = $this->sismodel->get_listspficemore('staff_position', 'sp_id,sp_sancstrenght,sp_position,'
                . 'sp_sspermanent,sp_pospermanent,sp_sstemporary,sp_postemporary',$datawh);
       // $vcyflag=false;
        if(!empty($emptype_data)){ 
            foreach($emptype_data as $empdata){
                  //  echo"first====" .$empdata->sp_sancstrenght.$empdata->sp_position;
                   
                    if($emptype == 'Permanent'){
                     //   echo"<br/>first2====" .$empdata->sp_sspermanent.$empdata->sp_pospermanent;
                        $update_data = array(
                        'sp_sancstrenght'=>$empdata->sp_sancstrenght+1,
                        'sp_position'=>$empdata->sp_position+1,    
                        'sp_sspermanent'=>$empdata->sp_sspermanent+1,
                        'sp_pospermanent'=>$empdata->sp_pospermanent+1,
                        //'sp_vpermanenet'=>$empdata->sp_vpermanenet+1,
                        );
                    }
                    else{
                       // echo"<br/>first3====" .$empdata->sp_sstemporary.$empdata->sp_postemporary;
                        $update_data = array(
                        'sp_sancstrenght'=>$empdata->sp_sancstrenght+1,
                        'sp_position'=>$empdata->sp_position+1,   
                        'sp_sstemporary'=>$empdata->sp_sstemporary+1,
                        'sp_postemporary'=>$empdata->sp_postemporary+1, 
                        //'sp_vtemporary'=>$empdata->sp_vtemporary+1,     
                        );
                    }    
                    $spflag=$this->sismodel->updaterec('staff_position', $update_data, 'sp_id', $empdata->sp_id);
                  
            }//foreach
        }
        else{
            if($emptype == 'Permanent'){
                $dataposition = array(
                    'sp_tnt'=>$worktype,
                    'sp_type'=>$emptype,
                    'sp_emppost'=>$desigid,
                    'sp_grppost'=>'Null',
                    'sp_scale'=>$payscaleid,
                    'sp_methodRect'=>'direct',
                    'sp_group'=>$group,
                    'sp_uo'=>$uoid,
                    'sp_dept'=>$deptid,
                    'sp_address1'=>'Null',
                    'sp_address2'=>'Null',
                    'sp_address3'=>'Null',
                    'sp_campusid'=>$campusid,
                    'sp_per_temporary'=>'Null',
                    'sp_plan_nonplan'=>'Null',
                    'sp_schemecode'=>$shmeid,
                    'sp_sancstrenght'=>1,
                    'sp_position'=>1,
                    'sp_vacant'=>0,
                    'sp_remarks'=>'Null',
                    'sp_ssdetail'=>'Null',
                    'sp_sspermanent'=>1,
                    'sp_sstemporary'=>0,
                    'sp_pospermanent'=>1,
                    'sp_postemporary'=>0,
                    'sp_vpermanenet'=>0,
                    'sp_vtemporary'=>0,
                    'sp_org_id'=> '1'
                );
            }    
            else{
                
                $dataposition = array(
                    'sp_tnt'=>$worktype,
                    'sp_type'=>$emptype,
                    'sp_emppost'=>$desigid,
                    'sp_grppost'=>'Null',
                    'sp_scale'=>$payscaleid,
                    'sp_methodRect'=>'direct',
                    'sp_group'=>$group,
                    'sp_uo'=>$uoid,
                    'sp_dept'=>$deptid,
                    'sp_address1'=>'Null',
                    'sp_address2'=>'Null',
                    'sp_address3'=>'Null',
                    'sp_campusid'=>$campusid,
                    'sp_per_temporary'=>'Null',
                    'sp_plan_nonplan'=>'Null',
                    'sp_schemecode'=>$shmeid,
                    'sp_sancstrenght'=>1,
                    'sp_position'=>1,
                    'sp_vacant'=>0,
                    'sp_remarks'=>'Null',
                    'sp_ssdetail'=>'Null',
                    'sp_sspermanent'=>0,
                    'sp_sstemporary'=>1,
                    'sp_pospermanent'=>0,
                    'sp_postemporary'=>1,
                    'sp_vpermanenet'=>0,
                    'sp_vtemporary'=>0,
                    'sp_org_id'=> '1'
                );
            }    
            $positionflag = $this->sismodel->insertrec('staff_position', $dataposition);
          
        }
    }
    /******************************* closer vacancycheck and create in  staff position ******************************/
     /*============== update staff position table at the time of employee transfer in budgetpost case ====================================*/
    public function updatespbudgetpost($campusid,$uoid,$deptid,$shmeid,$desigid,$worktype,$emptype){
        $datawh=array('sp_campusid' => $campusid,'sp_uo' => $uoid, 'sp_dept' => $deptid,'sp_schemecode' => $shmeid,
        'sp_emppost' => $desigid, 'sp_tnt' => $worktype,'sp_type' => $emptype);
        $emppost_data = $this->sismodel->get_listspficemore('staff_position','sp_id,sp_type,sp_sancstrenght,sp_position,sp_sspermanent,'
                . 'sp_pospermanent,sp_sstemporary,sp_postemporary',$datawh);
        if(!empty($emppost_data)){
            foreach($emppost_data as $empdata){
                   
                    if($emptype == 'Permanent'){
                       // echo "in permanent case".$empdata->sp_sancstrenght.$empdata->sp_position.$empdata->sp_sspermanent.$empdata->sp_pospermanent;
                        $update_data = array(
                        'sp_sancstrenght'=>$empdata->sp_sancstrenght-1,
                        'sp_position'=>$empdata->sp_position-1,  
                        'sp_sspermanent'=>$empdata->sp_sspermanent-1,
                        'sp_pospermanent'=>$empdata->sp_pospermanent-1,
                        //'sp_vpermanenet'=>$empdata->sp_vpermanenet+1,
                        );
                    }
                    else{
                      //  echo "in temp case".$empdata->sp_sancstrenght.$empdata->sp_position.$empdata->sp_sstemporary.$empdata->sp_postemporary;
                        $update_data = array(
                        'sp_sancstrenght'=>$empdata->sp_sancstrenght-1,
                        'sp_position'=>$empdata->sp_position-1,    
                        'sp_sstemporary'=>$empdata->sp_sstemporary-1,
                        'sp_postemporary'=>$empdata->sp_postemporary-1, 
                        //'sp_vtemporary'=>$empdata->sp_vtemporary+1,     
                        );
                    }    
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
                             
            } //foreach   
           
           
        }  //ifempty  
        
    }//function close
    /***********************************close of staff position  transfer in budgetpost case*********************************************/  
    
    /********************************get CCA Amount according to Pay Range, Pay Commission and BP ****************/
    public function getcca_amount($basicpay,$paycom) {
     
    //    $paycom='6th';
        $newrange=array();
        $sixcomm=array ("0-8000", "8001-12000", "12001-16000", "16001-1000000");
        $comm7= array("0-20600", "20601-30800", "30801-41100", "41101-1000000");
//	$newrange[]="0-inf";
        if($paycom == '6th'){
            foreach ($sixcomm as $rangevalsix) {
                $strange=explode("-",$rangevalsix); 
               //  print_r($strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($strange[0], $strange[1])))
                {
                  //  print_r($strange[0]."\n".$strange[1]."\n");
                    $newrange[]=$rangevalsix;
                } 

                //print_r($rangevalsix."\n");    
            }
            
        }
        else{
            foreach ($comm7 as $rangevalseven) {
                $strange=explode("-",$rangevalseven); 
                //  print_r($strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($strange[0], $strange[1])))
                {
                   // print_r($strange[0]."\n".$strange[1]."\n");
                    $newrange[]=$rangevalseven;
                } 
                //print_r($rangevalsix."\n");    
            }
              
        }
       // echo "newrange-----".$newrange;
        return $newrange;
    }
    
    /********************************get HRA Amount according to Pay Range, Pay Commission and BP ****************/
    public function gethra_amount($basicpay,$paycom) {
     
 //       $paycom='6th';
        $newrange=array();
        $sixcomm=array ("0-5299","5300-6699","6700-8189","8190-9299","9300-10599","10600-11899","11900-13769","13770-14509","14510-15999",
            "16000-17299","17300-19529","19530-20089","20090-21019","21020-21579","21580-22139","22140-24999","25000-1000000");
        
        $pcomm7= array("0-13600","13601-17200","17201-21000","21001-23900","23901-27200","27201-30600","30601-35400","35401-37300",
        "37301-41100","41101-44500","44501-50200","50201-51600","51601-54000","54001-55500","55501-56900","56901-64200","64201-1000000");
        if($paycom == '6th'){
            foreach ($sixcomm as $rangevalsix) {
                $strange=explode("-",$rangevalsix); 
                //  print_r($strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($strange[0], $strange[1])))
                {
                   // print_r($strange[0]."\n".$strange[1]."\n");
                    $newrange[]=$rangevalsix;
                } 
               //print_r($rangevalsix."\n");    
            }
            
        }
        else{
            foreach ($pcomm7 as $rangevalseven) {
                $sstrange=explode("-",$rangevalseven); 
                //  print_r($strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($sstrange[0], $sstrange[1])))
                {
                   // print_r($sstrange[0]."\n".$sstrange[1]."\n");
                    $newrange[]=$rangevalseven;
                } 
               //print_r($rangevalsix."\n");    
            }
    
        }
        // echo "newrange-----".$newrange;
        return $newrange;
    }  
 
    /********************************get Rent Grade Percentage Amount according to Pay Range, Pay Commission and BP ****************/
    public function gethraper_amount($basicpay,$paycom) {
     
       // $paycom='6th';
        $newrange=array();
       
        if($paycom == '6th'){
            $sixcomm=array ("6000-10199", "10200-18599", "18600-1000000");
            foreach ($sixcomm as $rangevalsix) {
                $strange=explode("-",$rangevalsix); 
                // print_r("6th==".$strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($strange[0], $strange[1])))
                {
                  //  print_r($strange[0]."\n".$strange[1]."\n");
                    $newrange[]=$rangevalsix;
                } 

                //print_r($rangevalsix."\n");    
            }
            
        }
        else{
             $comm7= array("18201-26200", "26201-48700", "48700-1000000");
            foreach ($comm7 as $rangevalseven) {
                $strange=explode("-",$rangevalseven); 
           //       print_r("7th".$strange[0]."\n".$strange[1]."\n");
                if(true === in_array($basicpay, range($strange[0], $strange[1])))
                {
             //       print_r($strange[0]."\n".$strange[1]."\n");
                    $newrange[]=$rangevalseven;
                } 
              
            }
              
        }
       // echo "newrange-----".$newrange;
        return $newrange;
    }
    
    /****************function for create salary slip pdf for send as a attachment***********************************/
    public function payslipAttachment($empid,$month,$year,$case){
        
        $spec_data['empid'] = $empid;
        $spec_data['month'] = $month;
        $spec_data['year'] = $year;   
        $selectfield ="sh_id,sh_code, sh_name, sh_tnt, sh_type, sh_calc_type";
       // $whorder = " sh_name asc";
        $whdata = array('sh_type' =>'I');
        $spec_data['incomes'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
//        $whdata = array('sh_type' =>'D');
//        $spec_data['deduction'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
	$whdata = array('sh_type' =>'D');
        $data['ded'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $whdata = array('sh_type' =>'L');
        $data['loans'] = $this->sismodel->get_orderlistspficemore('salary_head',$selectfield,$whdata,'');
        $spec_data['deduction']=array_merge($data['ded'], $data['loans']);
        // move file to directory code for photo
	$desired_dir = 'uploads/SIS/Payslip/'.$year.'/'.$month;
        
        // Create directory if it does not exist
        if(is_dir($desired_dir)==false){
            mkdir("$desired_dir", 0777);
        }
        $emp_pf=$this->sismodel->get_listspfic1('employee_master', 'emp_code', 'emp_id',$empid)->emp_code;
        if($case == "transcase"){
          // $temp= $this->load->view('setup3/salaryslipcopy2',$spec_data, TRUE);
           $temp= $this->load->view('setup3/salaryslipcopy2new',$spec_data, TRUE);
        }
        else{
          // $temp=$this->load->view('setup3/salaryslipcopy',$spec_data, TRUE);   
           $temp=$this->load->view('setup3/salaryslipcopynew',$spec_data, TRUE);   
        }
       	//add pdf code to store and view pdf file
	$pth=$desired_dir.'/'.$emp_pf.'.pdf';
        //echo $month.$year.$empid.$desired_dir.$pth;      
        //die();
	$this->genpdf($temp,$pth);
    }
    
    public function gettotalloan($empid){
        $ttlloan=0;
        $emppaycom=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        $empwtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $wdata1=array('shc_paycom'=>$emppaycom, 'shc_wtype'=>$empwtype);
        $shcsalhdid=$this->sismodel->get_orderlistspficemore('salaryhead_configuration','shc_salheadid',$wdata1,'');
        foreach($shcsalhdid as $row){
            $listhdid=$row->shc_salheadid;
        }
        $hdid_arr = explode (",", $listhdid);
        foreach($hdid_arr as $hid){
            $htype=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_id',$hid)->sh_type;
            if($htype == 'L'){
                $wdata2=array('slh_empid' =>$empid,'slh_headid'=>$hid);
                $mdrecord=$this->sismodel->get_maxvalue('salary_loan_head','slh_id',$wdata2);
                $mslhid='';
                if(!empty($mdrecord)){
                    foreach($mdrecord as $mr){
                        $mslhid=$mr->slh_id;
                    }
                    if(!empty($mslhid)){
                       //$hvalue=round($this->sismodel->get_listspfic1('salary_loan_head','slh_headamount','slh_id',$mslhid)->slh_headamount,0); 
                        $intlhvalue=round($this->sismodel->get_listspfic1('salary_loan_head','slh_installamount','slh_id',$mslhid)->slh_installamount,0);
                        $ttlloan+=(round($intlhvalue,0));
                    }
                }    
                
            }
        }  
        return $ttlloan;
        
    }
    public function gettotaldeduction($empid){
        $ttdeduct=0;
        $emppaycom=$this->sismodel->get_listspfic1('employee_master','emp_paycomm','emp_id',$empid)->emp_paycomm;
        $empwtype=$this->sismodel->get_listspfic1('employee_master','emp_worktype','emp_id',$empid)->emp_worktype;
        $wdata1=array('shc_paycom'=>$emppaycom, 'shc_wtype'=>$empwtype);
        $shcsalhdid=$this->sismodel->get_orderlistspficemore('salaryhead_configuration','shc_salheadid',$wdata1,'');
        foreach($shcsalhdid as $row){
            $listhdid=$row->shc_salheadid;
        }
        $hdid_arr = explode (",", $listhdid);
        foreach($hdid_arr as $hid){
            $htype=$this->sismodel->get_listspfic1('salary_head','sh_type','sh_id',$hid)->sh_type;
            if($htype == 'D'){
                $wdata2=array('ssdh_empid' =>$empid,'ssdh_headid'=>$hid);
                $mdrecord=$this->sismodel->get_maxvalue('salary_subsdeduction_head','ssdh_id',$wdata2);
                $mssdhid='';
                if(!empty($mdrecord)){
                    foreach($mdrecord as $mr){
                        $mssdhid=$mr->ssdh_id;
                        
                    }
                    if(!empty($mssdhid)){
                        $hvalue=round($this->sismodel->get_listspfic1('salary_subsdeduction_head','ssdh_headamount','ssdh_id',$mssdhid)->ssdh_headamount,0);
                        $ttdeduct+=(round($hvalue,0));
                        
                    }
                }
            }    
            
        } 
        return $ttdeduct;
    }
    
    public function getfromtransto($empid,$month,$year) {
        
       /* $empid=$this->uri->segment(3,0);
        $month=$this->uri->segment(4,0);
        $year=$this->uri->segment(5,0);*/
       // $case=$this->uri->segment(6,0);
        //echo "cases===".$empid.$month.$year.$case;
        $valdays=array();       
        $fromdays=''; $trnasitdays=''; $todays='';$toflag='';
               
        $cnomonth= date("m",strtotime($month));
        $nodaysmonth=cal_days_in_month(CAL_GREGORIAN,$cnomonth,$year);
               
        $stffieldsal ="ste_days,ste_hrafrom,ste_hrato,ste_ccafrom,ste_ccato,ste_transit";
        $wstfsal = array ('ste_empid'=>$empid,'ste_month'=>$month,'ste_year'=>$year);
        $stfvalsal = $this->sismodel->get_orderlistspficemore('salary_transfer_entry',$stffieldsal,$wstfsal,'');
        if(!empty($stfvalsal)){
            $fromdays= $stfvalsal[0]->ste_days;
            $trnasitdays= $stfvalsal[0]->ste_transit;
            $totaldt= $fromdays +  $trnasitdays; 
            $todays= $nodaysmonth - $totaldt; 
            
            $valdays['fromd']=$fromdays;
            $valdays['transitd']=$trnasitdays;
            $valdays['todaysd']=$todays;
            
        }            
               
        $sllfield ="sle_pal,sle_eol";
        $wsllsal = array ('sle_empid'=>$empid,'sle_month'=>$month,'sle_year'=>$year);
        $sllvalsal = $this->sismodel->get_orderlistspficemore('salary_leave_entry',$sllfield,$wsllsal,'');
        if(!empty($sllvalsal)){
            $paldays= $sllvalsal[0]->sle_pal;
            $eoldays= $sllvalsal[0]->sle_eol;
            $ttldl= $paldays +  $eoldays; 
            $leftdays= $nodaysmonth - $ttldl;
        }  

        if(!empty($paldays) || !empty($eoldays)){
            $totaldl= $paldays +  $eoldays;     
            if($fromdays > $ttldl){
                $fromdays=$fromdays - $ttldl;
                $valdays['fromd']=$fromdays;
                        //$leftfrmdays=$fromdays - $ttldl;
            }
            else{
                // $leftfrmdays= $fromdays;   
                $fromdays= $fromdays; 
                $valdays['fromd']=$fromdays;
                $fromflag=true;
            }
            if($fromflag == true){
                if($trnasitdays > $ttldl){
                    $trnasitdays=$trnasitdays - $ttldl;
                    $valdays['transitd']=$trnasitdays;
                            //$lefttrsitdays=$trnasitdays - $ttldl;
                }
                else{
                    $trnasitdays= $trnasitdays; 
                    $valdays['transitd']=$trnasitdays;
                    //$lefttrsitdays= $trnasitdays;
                    $transitflag=true;
                           
                }
                        
            }
            if($fromflag == true && $transitflag==true){
                if($todays > $ttldl){
                    $todays = $todays - $ttldl;
                    $valdays['todaysd']=$todays;
                            
                }
                else{
                    $todays=$leftdays;
                    $valdays['todaysd']=$todays;
                    $toflag=true;
                }
                        
            }
            if($fromflag == true && $transitflag==true && $toflag==true){
                $frmtransit = $fromdays + $trnasitdays;
                if($frmtransit >= $ttldl){
                    $leftboth = $frmtransit - $ttldl;
                    if($leftboth!=0){
                        $trnasitdays=$leftboth;
                        $valdays['transitd']=$trnasitdays;
                        //$lefttrsitdays=$leftboth;
                                
                    }
                }
                else{
                    $ftt=$fromdays + $trnasitdays+$todays;
                    $todays = $ftt - $ttldl;
                    $valdays['todaysd']=$todays;
                    //$leftfrmdays=0;
                    //$lefttrsitdays=0;	
                    $fromdays=0;
                    $trnasitdays=0;
                    $valdays['fromd']=$fromdays;
                    $valdays['transitd']=$trnasitdays;
                    
                } 
            }
        }
        return $valdays;
        
    }
    
    public function getcountabstract($tbname,$whdata){
        $this->db2->flush_cache();
        $this->db2->select('count(*)');
        $this->db2->from($tbname);
        if($whdata != ''){
            $this->db2->where($whdata);
        }
        $query= $this->db2->get();
        $cnt = $query->row_array();
        return $cnt['count(*)'];
    }
    
    
    function __destruct() {
        $this->db2->close();
    }
    
}    
