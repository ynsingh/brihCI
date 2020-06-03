<?php

/* 
 * @name Map.php
 * @author Nagendra Kumar Singh(Nksinghiitk@gmail.com)
 * @author Manorama Pal(palseema30@gmail.com)
 * @author Om Prakash (omprakashkgp@gmail.com) Map Subject and Paper with Teacher,  Map authority and user, Map SC with UO  
 * @author Kishore kr Shukla (kishore.shukla@gmail.com) Map user with Role.
 * @author Neha Khullar (nehukhullar@gmail.com) Map authority and user, Archive Map authority and user
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');


class Map extends CI_Controller
{
    function __construct() {
        parent::__construct();
        /*Loading model calsses*/
        $this->load->model('Dependrop_model',"depmodel");
        $this->load->model('Common_model',"commodel");
        $this->load->model('Login_model',"loginmodel"); 
	$this->load->model('SIS_model', "sismodel"); 
	$this->load->model("Mailsend_model","mailmodel");        
        if(empty($this->session->userdata('id_user'))) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
		redirect('welcome');
        }
    }

    public function index() {
    // $this->load->view('map/viewscprgseat');
    }
    
/* This function has been created for get list of Department on the basis of campus */
	public function getdeptlist(){
	    $scid = $this->input->post('campusname');
	    $this->depmodel->getdeptlist_model($scid);
	}

 /* This function has been created for get list of schemes on the basis of  selected department */
   public function getdeptschemelist(){
        $deptid = $this->input->post('deptid');
        $this->depmodel->get_deptschemelist($deptid);
    }

    /****************************************** Map user wirh Role ********************************************/

     /**This function is used for view details of map user with role */

    public function viewuserrole()
     {
        //$this->result = $this->commodel->get_list('user_role_type');
        $this->result = $this->sismodel->get_list('user_role_type');
        $this->logger->write_logmessage("view"," View map user with role setting", "user map setting details...");
        $this->logger->write_dblogmessage("view"," View map user with role setting", "Role setting details...");
        $this->load->view('map/viewuserrole',$this->result);
     }
   
    /** This function is for map user with role */

        public function userroletype()
        {
        $this->scresult   = $this->commodel->get_listspfic2('study_center','sc_id', 'sc_name');
        $this->roleresult = $this->commodel->get_listspfic2('role','role_id', 'role_name');
        $this->loginuser  = $this->loginmodel->get_userlist('edrpuser','id','username');

        if(isset($_POST['userroletype'])) {

        /*Form Validation*/
        $this->form_validation->set_rules('campus','Campus Name','trim|xss_clean|required');
        $this->form_validation->set_rules('dept_name','Departname','trim|xss_clean|required');
        $this->form_validation->set_rules('role_name','Role Name','trim|xss_clean|required');
        $this->form_validation->set_rules('usertype','Usertype','trim|xss_clean|required');
        $this->form_validation->set_rules('username','User Name','trim|xss_clean|required');

        if($this->form_validation->run() == TRUE)
        {
               $Campus = $this->input->post('campus',TRUE);
		//check for duplicate
	        $datadup = array('roleid' => $_POST['role_name'],'usertype'=>$_POST['usertype'],'userid'=>$_POST['username']);
               		$datauserrole = array(
                	//	'scid'=>$this->scid,
                		'scid'=>$Campus,
                		'deptid'=>$_POST['dept_name'],
                		'roleid'=>$_POST['role_name'],
                		'usertype'=>$_POST['usertype'],
                		'userid'=>$_POST['username'],
                		'ext1'=>'null',
          		);
       	$this->is_exist = $this->sismodel->isduplicatemore('user_role_type',$datadup);
      	if ($this->is_exist == 1)
        	{
            		//$this->form_validation->set_message('isduplicateuserrole', 'Map user role already exit');
			$this->session->set_flashdata('err_message','Map user role already exits.');
			redirect('map/userroletype');
            		return false;
		}

          else{

            $userrole=$this->sismodel->insertrec('user_role_type', $datauserrole);
          /**Geting value according to 'id' and using these values for maintaing logs*/
             $this->username = $this->loginmodel->get_listspfic1('edrpuser','username','id',$_POST['username'])->username;
             $this->rolename = $this->commodel->get_listspfic1('role','role_name', 'role_id', $_POST['role_name'])->role_name;
            if(! $userrole )
            {
                 $this->logger->write_logmessage("error","Error  in maping user with role"  .$username.$rolename.$usertype);
                 $this->logger->write_dblogmessage("error","Error  in maping user with role" .$username.$rolename.$usertype);
                 log_message('debug', ' Problem in maping user with role' .$username.$rolename.$usertype);
                 $this->session->set_flashdata('err_message','Error in maping user with role -' .$username.$rolename.$usertype);
                 redirect('map/userroletype');
            }
            else{

                 $this->logger->write_logmessage("insert","Map user with role", "Map user with role successfully.....".$username.$rolename.$usertype);
                 $this->logger->write_dblogmessage("insert","Map user with role", "Map user with role successfully....." .$username.$rolename.$usertype);
                 $this->session->set_flashdata("success", "Record added successfully "."["." UserName : "." ".$this->username.", "."RoleName : "." " .$this->rolename." ". ", "."UserType : "." " .$_POST['usertype']." ". "]");
                 redirect("map/viewuserrole");

            }
        }//is duplicate
     }//if
  
   }
        $this->load->view('map/userroletype');
 }

      public function deleteuserrole($id)
      {
           /**Geting value according to 'id' and using these values for maintaing logs*/
          $username = $this->input->post('username',TRUE);
          $this->username = $this->loginmodel->get_listspfic1('edrpuser','username','id',$username)->username;
          $rolename= $this->input->post('role_name',TRUE);
          $this->rolename = $this->commodel->get_listspfic1('role','role_name', 'role_id', $rolename)->role_name;
          $roledflag=$this->sismodel->deleterow('user_role_type','id', $id);
          if(!$roledflag)
          {
            $this->logger->write_message("error", "Error in maping with user role deleting  " ."[role_id:" . $id . "]");
            $this->logger->write_dbmessage("error", "Error in mapping with user roledeleting role "." [role_id:" . $id . "]");
            $this->session->set_flashdata('err_message', 'Error in mapping with user role Deleting role - ', 'error');
            redirect('map/viewuserrole');
           return;
          }
        else{
             $this->logger->write_logmessage("delete", "Deleted map with user role " . "[role_id:" . $id . "] deleted successfully.. " );
             $this->logger->write_dblogmessage("delete", "Deleted map with user role" ." [role_id:" . $id . "] deleted successfully.. " );
             $this->session->set_flashdata("success", "Record deleted successfully "."["." UserName : "." ".$this->username." "."RoleName : "." " .$this->rolename." ". "]" );
            redirect('map/viewuserrole');
           }
        $this->load->view('map/viewuserrole',$data);
    }
   
   /**This function is used for update record of  maped user with role 
    * @param type $id
    */

        public function edituserrole($id){
	$this->roleresult = $this->commodel->get_listspfic2('role','role_id', 'role_name');
        $eset_data_q=$this->sismodel->get_listrow('user_role_type','id',$id);
        //$this->db2->from('user_role_type')->where('id', $id);
        //$eset_data_q = $this->db2->get();
        $editeset_data = $eset_data_q->row();
        /* Form fields */
        $data['scid']= array(
            //'value' =>$editeset_data->scid,
            'value' =>$this->commodel->get_listspfic1('study_center','sc_name', 'sc_id',$editeset_data->scid)->sc_name,
            'size'  =>'35',
            'readonly'=>'true',
        );
               
        $data['username'] =array(
            'value' => $this->loginmodel->get_listspfic1('edrpuser','username','id',$editeset_data->userid)->username,
            'size'  =>'35',
            'readonly'=>'true',
        );
       
        $data['roleid'] = array(
           'size' => '35',
           'value' =>$this->commodel->get_listspfic1('role','role_name', 'role_id',$editeset_data->roleid)->role_name
        );
       
        $data['usertype'] = array(
            'value' => $editeset_data->usertype,
             'size' => '35',
            
        );
         
        $data['deptid'] = array(
            'value' =>$this->commodel->get_listspfic1('Department','dept_name', 'dept_id',$editeset_data->deptid)->dept_name,
            'size'=>'35',
            'readonly'=>'true',
        );
        
        $data['id'] = $id;
        
        /*Form Validation*/
        $this->form_validation->set_rules('roleid','Rolename','trim|xss_clean|required');
        $this->form_validation->set_rules('usertype','Usertype','trim|xss_clean|required');

         /* Re-populating form */
   
        if ($_POST)
        {
               $data['roleid']['value'] = $this->input->post('roleid', TRUE);
               $data['usertype']['value'] = $this->input->post('usertype', TRUE);
                 
        }
     
        if ($this->form_validation->run() == FALSE)
        {
            //echo "this is testing...2";
            $this->session->set_flashdata(validation_errors(), 'error');
            $this->load->view('map/edituserrole',$data);
        }
        else
        {   
            $data_userid = $this->input->post('userid', TRUE);
            $data_roleid = $this->input->post('roleid', TRUE);
            $data_scid   = $this->input->post('scid', TRUE);
            $data_deptid = $this->input->post('deptid', TRUE);
            $data_usertype = $this->input->post('usertype', TRUE);
                      
            /* insert data into map user role archive table */
               $urta_data= array(
                 'urta_urtid'=>$id,
                 'urta_userid'=>$editeset_data->userid,
                 'urta_roleida'=>$editeset_data->roleid,
                 'urta_scida'=>$editeset_data->scid,
                 'urta_deptida'=>$editeset_data->deptid,
                 'urta_usertypea'=>$editeset_data->usertype,
                 'creatorid'=>'SIS - '.$this->session->userdata('username'),
                 'creatordate'=>date('y-m-d'),
               );
                  /* insert data into map user role archive table */
            $userroletypeflaga=$this->sismodel->insertrec('user_role_type_archive', $urta_data);

            $update_data = array(
            'roleid'=>$data_roleid,
            'usertype'=>$data_usertype,
          
            );
        $datadup = array('roleid' =>$data_roleid,'usertype'=>$data_usertype,'userid'=>$id);  
        $this->is_exist = $this->sismodel->isduplicatemore('user_role_type',$update_data);
        if ($this->is_exist == 1)
                {
                        //$this->form_validation->set_message('isduplicateuserrole', 'Map user role already exit');
                        $this->session->set_flashdata('err_message','Map user role already exits.');
                        redirect('map/viewuserrole');
                        return false;
                }

          else{
            $result=$this->sismodel->updaterec('user_role_type', $update_data,'id',$id);
             //$this->username = $this->commodel->loginmodel->get_listspfic1('edrpuser','username','id',$_POST['username'])->username;
            // $rolename= $this->input->post('role_name',TRUE);
            $this->rolename = $this->commodel->get_listspfic1('role','role_name', 'role_id', $data_roleid)->role_name;
            if(! $result)
            {
              $this->logger->write_logmessage("error", "Error in update study center with program seat " . $rolename."rolename--- "." changed by".$data_usertype);
              $this->logger->write_dblogmessage("error", "Error in update study center with program seat" . $rolename."rolename--- "." changed by".$data_usertype );
              log_message('debug', 'Problem in maping studycenter with program seat'. $rolename."rolename--- "." changed by".$data_usertype);
              $this->session->set_flashdata('err_message','Error in update study center with program seat - '. $rolename."rolename--- "." changed by".$data_usertype);
                redirect("map/edituserrole");
            }
            else {  
                  /*old role name*/ 
		$this->oldrole=$this->commodel->get_listspfic1('role','role_name', 'role_id',$editeset_data->roleid)->role_name;       
                $this->logger->write_logmessage("update", "updated user with role "  . $data_roleid."rolename--- "." changed by".$data_usertype);
                $this->logger->write_dblogmessage("update", "updated user with role "  . $data_roleid."rolename--- "." changed by".$data_usertype);
                $this->session->set_flashdata("success", 'Record updated successfully.... '." "."["." " ."Role:"." ".$this->oldrole." "."changed by" ." "."Role: ". $this->rolename." "."and"." "."Usertype:"." ".$editeset_data->usertype." "."changed by"." ".$data_usertype." "."]");
                redirect("map/viewuserrole");
             }
	}	
      }  
                        
  }

 /****************************************** Map scheme with department ********************************************/
public function viewschemedept()
     {
        $this->result = $this->sismodel->get_list('map_scheme_department');
        $this->logger->write_logmessage("view"," View map scheme with department setting", "map scheme with department details...");
        $this->logger->write_dblogmessage("view"," View map scheme with department setting", "map scheme with department setting details...");
        $this->load->view('map/viewschemedept',$this->result);
     }

public function schemedept(){
                  $this->scresult = $this->commodel->get_listspfic2('study_center','sc_id', 'sc_name');
        	  $this->schresult = $this->sismodel->get_list('scheme_department');
   
        if(isset($_POST['schemedept'])) {
        $this->form_validation->set_rules('campus','Campus Name','trim|xss_clean|required');
        $this->form_validation->set_rules('dept_name','Departname','trim|xss_clean|required');
        $this->form_validation->set_rules('scheme','Scheme List','trim|xss_clean|required');


            if($this->form_validation->run()==TRUE){

            $data = array(
                'msd_scid'=>ucwords(strtolower($_POST['campus'])),
                'msd_deptid'=>strtoupper($_POST['dept_name']),
                'msd_schmid'=>$_POST['scheme']

            );
           $msdflag=$this->sismodel->insertrec('map_scheme_department', $data) ;
           if(!$msdflag)
           {
                $this->logger->write_logmessage("insert"," Error in adding map with scheme department ", " map with scheme department data insert error . "  );
                $this->logger->write_dblogmessage("insert"," Error in adding map with scheme department ", " map with scheme department data insert error . " );
                $this->session->set_flashdata('err_message','Error in adding map with scheme department - ' . $_POST['msdname'] , 'error');
                $this->load->view('map/schemedept');
           }
          else{
                $this->logger->write_logmessage("insert"," add map with scheme department ", "map with scheme department record added successfully..."  );
                $this->logger->write_dblogmessage("insert"," add map with scheme department ", "map with scheme department record added successfully..." );
                $this->session->set_flashdata("success", "Map with Scheme Department added successfully...");
                redirect("map/viewschemedept", "refresh");
              }
           }

        }
      $this->load->view('map/schemedept');
   }

/**This function is used for update Map with Scheme Department records
     * @param type $id
     * @return type
     */
       public function editschemedept($msd_id) {
	$this->schresult = $this->sismodel->get_list('scheme_department');
        $msd_data_q=$this->sismodel->get_listrow('map_scheme_department','msd_id', $msd_id);
         
        if ($msd_data_q->num_rows() < 1)
        {
           redirect('setup/editschemedept');
        }
      $MapWithSchemeDepartment_data = $msd_data_q->row();
        
        /* Form fields */

        $data['msd_scid'] = array(
            'name' => 'msd_scid',
            'id' => 'msd_scid',
            'maxlength' => '50',
            'size' => '40',
            'value' => $this->commodel->get_listspfic1('study_center','sc_name','sc_id',$MapWithSchemeDepartment_data->msd_scid)->sc_name, 
           'readonly' => 'readonly'
        );

        $data['msd_deptid'] = array(
           'name' => 'msd_deptid',
           'id' => 'msd_deptid',
           'maxlength' => '50',
           'size' => '40',
           'value' => $this->commodel->get_listspfic1('Department','dept_name', 'dept_id',$MapWithSchemeDepartment_data->msd_deptid)->dept_name,
            'readonly' => 'readonly'
        );

       $data['msd_schmid'] = array(
           'name' => 'msd_schmid',
           'id' => 'msd_schmid',
           'maxlength' => '50',
           'size' => '40',
           'value' => $this->sismodel->get_listspfic1('scheme_department','sd_name', 'sd_id',$MapWithSchemeDepartment_data->msd_schmid)->sd_name, 
        );

    $data['msd_id'] = $msd_id;

        $this->form_validation->set_rules('scheme','Scheme List','trim');

        if ($_POST)
        {
            $data['msd_schmid']['value'] = $this->input->post('msd_schmid', TRUE);
        }
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('map/editschemedept', $data);
            return;
        }
        else
        {
            $msd_schmid = strtoupper($this->input->post('msd_schmid', TRUE));
            $logmessage = "";
         
            if($MapWithSchemeDepartment_data->msd_schmid != $msd_schmid)
                $logmessage = "Map with Scheme Department " .$MapWithSchemeDepartment_data->msd_schmid. " changed by " .$msd_schmid;

            $msda_data = array(
               'msda_msdid'=>$msd_id,
               'msda_schmid'=>$MapWithSchemeDepartment_data->msd_schmid,
               'msda_deptid'=>$MapWithSchemeDepartment_data->msd_deptid,
               'msda_scid'=>$MapWithSchemeDepartment_data->msd_scid,
               'msda_org_id'=>$MapWithSchemeDepartment_data->msd_org_id,
               'msda_archuserid'=>'SIS - '.$this->session->userdata('username'),
               'msda_archdate'=> date('y-m-d')
               );
              $msdaflaga=$this->sismodel->insertrec('map_scheme_department_archive', $msda_data);

            $update_data = array(
               'msd_schmid' =>$msd_schmid,
            );

           $msdflag=$this->sismodel->updaterec('map_scheme_department', $update_data, 'msd_id', $msd_id);
           if(!$msdflag)
            {
                $this->logger->write_logmessage("error","Error in update Map with Scheme Department ", "Error in Map with Scheme Department record update. $logmessage . " );
                $this->logger->write_dblogmessage("error","Error in update Map with Scheme Department ", "Error in Map with Scheme Department record update. $logmessage ." );
                $this->session->set_flashdata('err_message','Error updating Map with Scheme Department - ' . $logmessage . '.', 'error');
                $this->load->view('map/editschemedept', $data);
            }
            else{
                $this->logger->write_logmessage("update","Edit Map with Scheme Department", "Map with Scheme Department record updated successfully... $logmessage . " );
                $this->logger->write_dblogmessage("update","Edit Map with Scheme Department", "Map with Scheme Department record updated successfully... $logmessage ." );
                $this->session->set_flashdata('success','Map with Scheme Department record updated successfully...');
                redirect('map/viewschemedept/');
                }
        }//else
        redirect('map/editschemedept/');
    }

/****************************************** Map authority and user ********************************************/

 public function viewauthuser() {
        $this->authuser=$this->loginmodel->get_list('authority_map');
        $this->logger->write_logmessage("view"," View map authority and user setting", "authority map setting details...");
        $this->logger->write_dblogmessage("view"," View map authority and user setting", "User setting details...");
        $this->load->view('map/viewauthuser',$this->authuser);
     }

  /** This function is for map authority and user */

        public function authusertype()
        {
        $this->authuserresult = $this->loginmodel->get_list('authorities','id', 'name');
        $this->result = $this->loginmodel->get_userlist('edrpuser','username', 'id');
        //$this->authresult = $this->loginmodel->get_list('authority_map','id','authority_type');                  

        if(isset($_POST['authusertype'])) {

        /*Form Validation*/

        $this->form_validation->set_rules('authorities',' Authority Name','trim|xss_clean|required');
        $this->form_validation->set_rules('edrpuser','User Name','trim|xss_clean|required');
        $this->form_validation->set_rules('map_date','From Date','trim|xss_clean|required');
        $this->form_validation->set_rules('till_date','Till Date','trim|xss_clean|required');
        $this->form_validation->set_rules('authority_type','Authority Type','trim|xss_clean|required');
        

         if($this->form_validation->run() == TRUE){
               //echo 'form-validated';
                        $data = array(
                                'authority_id'=>$_POST['authorities'],
                                'user_id'=>$_POST['edrpuser'],
                                'map_date'=>$_POST['map_date'],
                                'till_date'=>$_POST['till_date'],
                                'authority_type'=>$_POST['authority_type'],
                                );


                         $amapflag=$this->loginmodel->insertrec('authority_map', $data) ;
           if(!$amapflag)
           {
                $this->logger->write_logmessage("insert"," Error in adding map and authority user ", " map and authority user data insert error . "  );
                $this->logger->write_dblogmessage("insert"," Error in adding authority user ", " map and authority user data insert error . " );
                $this->session->set_flashdata('err_message','Error in adding map and authority user - ' . $_POST['amapauthority'] , 'error');
                $this->load->view('map/authusertype');
           }
          else{
                $this->logger->write_logmessage("insert"," add map and authority user", "map and authority user record added successfully..."  );
                $this->logger->write_dblogmessage("insert"," add map and authority user ", "map and authority record added successfully..." );
                $this->session->set_flashdata("success", "Map and Authority User added successfully...");
                redirect("map/viewauthuser");

              }
           }                                                                        
                
        }
       $this->load->view('map/authusertype');
    }

/**This function is used for update Map and Authority User records
     * @param type $id
     * @return type
     */

       public function editauthuser($id) {
       // $this->authresult = $this->loginmodel->get_list('authority_map','authority_id', 'user_id');
        $id_data_q=$this->loginmodel->get_listrow('authority_map','id', $id);

        if ($id_data_q->num_rows() < 1)
        {
           redirect('setup/editauthuser');
        }
      $editid_data = $id_data_q->row();

        /* Form fields */
        $data['authority_id'] = array(
            'name' => 'authority_id',
            'id' => 'authority_id',
            'maxlength' => '50',
            'size' => '40',
            'value' => $this->loginmodel->get_listspfic1('authorities','name','id',$editid_data->authority_id)->name,
            'readonly' => 'readonly'
        );

        $data['user_id'] = array(
           'name' => 'user_id',
           'id' => 'user_id',
           'maxlength' => '50',
           'size' => '40',
           'value' => $this->loginmodel->get_listspfic1('edrpuser','username','id',$editid_data->user_id)->username,
           'readonly' => 'readonly'
        );

       $data['map_date'] = array(
           'name' => 'map_date',
           'id' => 'map_date',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->map_date,
        );

        $data['till_date'] = array(
           'name' => 'till_date',
           'id' => 'till_date',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->till_date,
        );
          
        $data['authority_type'] = array(
           'name' => 'authority_type',
           'id' => 'authority_type',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->authority_type,
        );      
           
    $data['id'] = $id;

        $this->form_validation->set_rules('map_date','From Date','trim|xss_clean');
        $this->form_validation->set_rules('till_date','Till Date','trim|xss_clean');
        $this->form_validation->set_rules('authority_map','Authority Type','trim|xss_clean');
 
        //$this->form_validation->set_rules('Authorities','Authorities List','trim');


        if ($_POST)
        {
            
            $data['map_date']['value'] = $this->input->post('map_date', TRUE);
            $data['till_date']['value'] = $this->input->post('till_date', TRUE);
            $data['authority_type']['value'] = $this->input->post('authority_type', TRUE);
        }
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('map/editauthuser', $data);
            return;
        }
        else
        {
            $fromdate =$this->input->post('map_date', TRUE);
            $tilldate =$this->input->post('till_date', TRUE);
            $authority_type =$this->input->post('authority_type', TRUE);
            $logmessage = "";

            if($editid_data->map_date != $fromdate)
                $logmessage = "Map Authority and User" .$editid_data->map_date. " changed by " .$fromdate;
            if($editid_data->till_date != $tilldate)
                $logmessage = "Map Authority and User" .$editid_data->till_date. " changed by " .$tilldate;
            if($editid_data->authority_type != $authority_type)
                $logmessage = "Map Authority and User" .$editid_data->authority_type. " changed by " .$authority_type;
             if($editid_data->authority_id != $authority_id)
                $logmessage = "Map Authority and User" .$editid_data->authority_id. " changed by " .$authority_id;
             if($editid_data->user_id != $user_id)
                $logmessage = "Map Authority and User" .$editid_data->user_id. " changed by " .$user_id;   

           $insert_data = array(  
                'authority_id'=> $editid_data->authority_id,
                'user_id'=> $editid_data->user_id,                
                'map_date'=> $editid_data->map_date,
                'till_date'=>$editid_data->till_date,
                'authority_type'=>$editid_data->authority_type,
                'creatorid'=>$this->session->userdata('id_user'),
                'createdate'=>date('y-m-d')

             );
         $amapflag=$this->loginmodel->insertrec('authority_archive', $insert_data);
         if(!$amapflag)
         {
              $this->logger->write_dblogmessage("error","Error in insert map authority and user archive ", "Error in map authority and user archive record insert" .$logmessage );
         }else{
              $this->logger->write_dblogmessage("insert","Insert map authority and user archive", "Record inserted in archive successfully.." .$logmessage );
         }

            $update_data = array(
                        'map_date'=>$fromdate,
                        'till_date'=>$tilldate,
               		'authority_type' =>$authority_type,

         );

           $amapflag=$this->loginmodel->updaterec('authority_map', $update_data, 'id', $id);
           if(!$amapflag)
            {
                $this->logger->write_logmessage("error","Error in update Map Authority and User ", "Error in Map Authority and User record update. $logmessage . " );
                $this->logger->write_dblogmessage("error","Error in update Map Authority and User ", "Error in Map Authority and User record update. $logmessage ." );
                $this->session->set_flashdata('err_message','Error updating Map Authority and User - ' . $logmessage . '.', 'error');
                $this->load->view('map/editauthuser', $data);
            }
            else{
                $this->logger->write_logmessage("update","Edit Map Authority and User ", "Map Authority and User record updated successfully... $logmessage . " );
                $this->logger->write_dblogmessage("update","Edit Map Authority and User", "Map Authority and User record updated successfully... $logmessage ." );
                $this->session->set_flashdata('success','Map Authority and User record updated successfully...');
                redirect('map/viewauthuser/');
                }
        }//else
        redirect('map/editauthuser');
    }

//################################################ Map Study Center and UO ####################################

  /*
   * this function has been created for display the list of  record.
   */
   public function viewscuo(){
        $this->result = $this->sismodel->get_list('map_sc_uo');
        $this->logger->write_logmessage("view"," View Study Center and UO ", "Map Study Center and UO record display successfully." );
        $this->logger->write_dblogmessage("view"," View Study Center and UO ", "Map Study Center and UO record display successfully." );
        $this->load->view('map/viewscuo');
   }

 /*
  * this function has been created for add mapping of study center with UO record.
  */
   public function studycenteruo(){
        $this->scresult = $this->commodel->get_listspfic2('study_center','sc_id', 'sc_name');
        $this->authresult = $this->loginmodel->get_listspfic2('authorities','id', 'name');
	
       if(isset($_POST['studycenteruo'])) {
            $this->form_validation->set_rules('campusname','Campus Name','xss_clean|required');
            $this->form_validation->set_rules('authority','Authority Name','xss_clean|required');

        if($this->form_validation->run()==TRUE){

           $campusid = $this->input->post("campusname");
           $uoid = $this->input->post("authority");
           $campusname = $this->commodel->get_listspfic1('study_center','sc_name','sc_id',$campusid)->sc_name;
           $uoname = $this->loginmodel->get_listspfic1('authorities', 'name', 'id', $uoid)->name;
	  
        $datascuo = array(
        'scuo_scid'=>$_POST['campusname'],
        'scuo_uoid'=>$_POST['authority']
        );

     	$scuodatadup = $this->sismodel->isduplicatemore('map_sc_uo', $datascuo);
	
        if($scuodatadup == 1 ){

		$this->session->set_flashdata("err_message", "Record is already exist with this combination. 'Campus Name' = $campusname  , 'UO Name' = $uoname .");
                redirect('map/studycenteruo');
		return;
	}
        else{	
     // need to be done- must put the entry in uo_list 
        $scuoflag = $this->sismodel->insertrec('map_sc_uo', $datascuo) ;
        if(!$scuoflag)
          {
        	$this->logger->write_logmessage("insert"," Error in adding Study Center and UO ", " Study Center and UO data insert error .'Campus Name' = $campusname  , 'UO Name' = $uoname ");
                $this->logger->write_dblogmessage("insert"," Error in adding subject paper teacher ", " Study Center and UO data insert error .'Campus Name' = $campusname  , 'UO Name' = $uoname ");
                $this->session->set_flashdata('err_message','Error in adding subject paper teacher - ' . $uoname . '.', 'error');
                $this->load->view('map/studycenteruo');
	  }
          else{
                $this->logger->write_logmessage("insert"," map Study Center and UO ", "map Study Center and UO record added successfully. 'Campus Name' = $campusname  , 'UO Name' = $uoname " );
                $this->logger->write_dblogmessage("insert"," map Study Center and UO ", "map Study Center and UO record added successfully. 'Campus Name' = $campusname  , 'UO Name' = $uoname " );
		$this->session->set_flashdata("success", "Record added successfully...'Campus Name' = $campusname  , 'UO Name' = $uoname ");
                redirect('map/viewscuo');

	    }   	
          }
	 }
	}
	$this->load->view('map/studycenteruo');
   }
 /*Edit record  */
 public function updatescuo($scuo_id){
        $this->authorty = $this->loginmodel->get_list('authorities', 'id', 'name');
        $scuo_data_q=$this->sismodel->get_listrow('map_sc_uo','scuo_id', $scuo_id);
        if ($scuo_data_q->num_rows() < 1)
        {
           redirect('map/updatescuo');
        }
        $editscuo_data = $scuo_data_q->row();

        /* Form fields */

        $data['campusname']= array(
            'name' => 'campusname',
            'id' => 'campusname',
            'maxlength' => '26',
            'size' => '26',
            'value' => $this->commodel->get_listspfic1('study_center', 'sc_name', 'sc_id', $editscuo_data->scuo_scid)->sc_name,
	    'readonly' => 'readonly',	
        );

        $data['authority']= array(
            'name' => 'authority',
            'id' => 'authority',
            'maxlength' => '26',
            'size' => '26',
            'value' => $this->loginmodel->get_listspfic1('authorities', 'name', 'id', $editscuo_data->scuo_uoid)->name,
        );

        $data['scuo_id'] = $scuo_id;

            $this->form_validation->set_rules('campusname','Campus Name','xss_clean|required');
            $this->form_validation->set_rules('authority','Authority Name','xss_clean|required');

        if ($_POST)
        {   
            $data['campusname']['value'] = $this->input->post('campusname', TRUE);
            $data['authority']['value'] = $this->input->post('authority', TRUE);
        }
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('map/updatescuo', $data);
            return;
        }
      else
        {
            $data_campusname = $this->input->post('campusname', TRUE);
            $data_uoid = $this->input->post('authority', TRUE);
	    $data_uoname = $this->loginmodel->get_listspfic1('authorities', 'name', 'id', $data_uoid)->name;
            $data_scuoid = $scuo_id;
            $logmessage = "";
            if($editscuo_data->scuo_scid != $data_campusname)
                $logmessage = "Campus Name " .$this->commodel->get_listspfic1('study_center', 'sc_name', 'sc_id', $editscuo_data->scuo_scid)->sc_name. " changed by " .$data_campusname ;
            if($editscuo_data->scuo_uoid != $data_uoname)
                $logmessage = "UO Name " .$this->loginmodel->get_listspfic1('authorities', 'name', 'id', $editscuo_data->scuo_uoid)->name. " changed by " .$data_uoname ;

           $updatea_data = array(
                'scuoa_scuoid'=>$scuo_id,
                'scuoa_scid'=> $editscuo_data->scuo_scid,
                'scuoa_uoid'=> $editscuo_data->scuo_uoid,
                'scuoa_archuserid'=>$this->session->userdata('id_user'),
                'scuoa_archdate'=>date('y-m-d')
            );
         $scuoflag=$this->sismodel->insertrec('map_sc_uo_archive', $updatea_data);
         if(!$scuoflag)
         {
              $this->logger->write_dblogmessage("error","Error in insert map sc with uo archive ", "Error in  map sc with uo archive record insert" .$logmessage );
         }else{
              $this->logger->write_dblogmessage("insert","Insert map sc with uo archive", "Record inserted in archive successfully.." .$logmessage );
         }

           $update_data = array(
               'scuo_scid' => $this->commodel->get_listspfic1('study_center', 'sc_id', 'sc_name', $data_campusname)->sc_id,
               'scuo_uoid' => $data_uoid
            );

        $scuodatadup = $this->sismodel->isduplicatemore('map_sc_uo', $update_data);
        if($scuodatadup == 1 ){
                $this->session->set_flashdata("err_message", "Record is already exist with this combination. Campus Name = '$data_campusname' , UO Name =' $data_uoname' ");
                redirect('map/viewscuo/');
                return;
            }
        else{

           $scuoflag=$this->sismodel->updaterec('map_sc_uo', $update_data, 'scuo_id', $scuo_id);
           if(!$scuoflag)
            {
                $this->logger->write_logmessage("error","Error in updating ", "Error in Map SC with UO record updating. $logmessage . " );
                $this->logger->write_dblogmessage("error","Error in updating ", "Error in Map SC, with UO record updating. $logmessage ." );
                $this->session->set_flashdata('err_message','Error in updating  ' . $logmessage . '.', 'error');
                $this->load->view('map/updatescuo', $data);
            }
            else{
                $this->logger->write_logmessage("update","Edit mapping Study Center with UO", "mapping Study Center with UO record updated successfully. $logmessage . " );
                $this->logger->write_dblogmessage("update","Edit mapping SC with UO", "mapping SC with UO record updated successfully. $logmessage ." );
                $this->session->set_flashdata('success',"Record updated successfully. The  $logmessage ." );
                redirect('map/viewscuo/');
                }
         }//else
 	$this->load->view('map/updatescuo');
    }
  }
//############################################# End of Map Study Center and UO #################################
 //############################################# set Hod#################################
  
    public function sethod(){
        $this->orgcode=$this->commodel->get_listspfic1('org_profile','org_code','org_id',1)->org_code;
        $this->campus=$this->commodel->get_listspfic2('study_center','sc_id','sc_name','org_code',$this->orgcode);
	$this->uo=$this->sismodel->get_list('uo_list');
        if(isset($_POST['sethod'])) {
            
            $this->form_validation->set_rules('campus','Campus Name','trim|required|xss_clean');
            $this->form_validation->set_rules('deptname','Department Name','trim|required|xss_clean');
            $this->form_validation->set_rules('usrname','User Name','trim|xss_clean');
            $this->form_validation->set_rules('emailid','Email Id','trim|required|xss_clean');
            $this->form_validation->set_rules('DateFrom','Datefrom','trim|xss_clean');
            $this->form_validation->set_rules('DateTo','Dateto','trim|xss_clean'); 
            $this->form_validation->set_rules('status','Status','trim|xss_clean'); 
            $this->form_validation->set_rules('jsession','jsession','trim|xss_clean'); 
            $this->form_validation->set_rules('tsession','tsession','trim|xss_clean'); 
            
            if($this->form_validation->run() == FALSE){
                $this->load->view('map/set_hod');
                return;
            }//formvalidation
            else{
                
                $userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
                /** check dulicate entry and insert in edrpuser table**********************/
                $isdup= $this->loginmodel->isduplicate('edrpuser','username',$_POST['emailid']);
                if(!$isdup){
                	$parts = explode('@',$_POST['emailid']); 
	                $passwd=md5($parts[0]);
                    $dataeu = array(
                        'username'=> $_POST['emailid'],
                        'password'=> $passwd,
                        'email'=> $_POST['emailid'],
                        'componentreg'=> '*',
                        'mobile'=>'',
                        'status'=>1,
                        'category_type'=>'HOD',
                        'is_verified'=>1
                    );
                    /*insert record in edrpuser table*/
                    $userflageu=$this->loginmodel->insertrec('edrpuser', $dataeu);
                    $userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
                    if($userflageu){
                        // insert into  user profile db1
                        $dataup = array(
                            'userid' => $userid,
                            'firstname' => 'Head of the Department',
                            'lang' => 'english',
                            'mobile' => '',
                            'status' => 1
                        );
                        $userflagup=$this->loginmodel->insertrec('userprofile', $dataup);
                    /* insert into user_role_type */
                    $dataurt = array(
                        'userid'=> $userid,
                        'roleid'=> 5,
                        'deptid'=> $_POST['deptname'],
                        'scid'=>  $_POST['campus'],
                        'usertype'=>'HoD'
                    );
                    $userflagurt=$this->sismodel->insertrec('user_role_type', $dataurt) ;
                    }//if $userflageu closer
                }//ifdupedrpuser check closer     
                /*************************check for duplicate entry in hod list and insert in table*************/

                $pfno='';
                if(!empty($_POST['usrname'])){
                   $pfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$_POST['usrname'])->emp_code;
                }
		$datefrm = $_POST['DateFrom'];
		if(empty($datefrm)){
			$datefrm = date('y-m-d') ;
		}
                $duphod = array('hl_userid' => $userid, 'hl_empcode' => $pfno,'hl_datefrom'=> $datefrm,'hl_deptid'=> $_POST['deptname']);
                $isduphod= $this->sismodel->isduplicatemore('hod_list',$duphod);
                if(!$isduphod){
                    $usr =$this->session->userdata('username'); 
                   // $userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
		    $uopid=$this->loginmodel->get_listspfic1('authorities','priority','code',$_POST['uo'])->priority;
                $jsession = $this->input->post('jsession', TRUE);
		    $tsession = $this->input->post('tsession', TRUE);
		    $dateto = $this->input->post('DateTo', TRUE);
                if((empty($dateto)) || ($dateto == '0000-00-00 00:00:00')){
                       $dateto='1000-01-01 00:00:00';
                }
                    $datahod = array(
                        'hl_userid'=> $userid,
                        'hl_empcode'=> $pfno,
                        'hl_deptid'=> $_POST['deptname'],
                        'hl_scid'=> $_POST['campus'],
			'hl_uopid'=> $uopid,
                        'hl_datefrom'=> $datefrm,
                        'hl_dateto'=> $dateto,
			'hl_fromsession'=>$jsession,
			'hl_tosession'=>$tsession,
                        'hl_status'=> $_POST['status'],
                        'hl_creatorid'=> $usr,
                        'hl_creatordate'=> date('y-m-d'),
                        'hl_modifierid'=> $usr,
                        'hl_modifydate'=> date('y-m-d'),
                    );
                    $hodlistflag=$this->sismodel->insertrec('hod_list', $datahod) ;
                    $sub=' Registred as a HOD' ;
                    $mess="You are registered as a Hod. Your login id ".$_POST['emailid'] ." and password is ".$passwd ;
                    //$mails = $this->mailmodel->mailsnd($email, $sub, $mess,'','Sis');
                    $mails = '';
                    //$this->mailmodel->mailsnd('$_POST['emailid']', $sub, $mess,'','Sis');
                    //  mail flag check                         
                    if($mails){
                        $mailmsg='Please check your mail for username and password....Mail send successfully';
                        $this->logger->write_logmessage("insert"," add hod edrpuser, userprofile,hod_list and user_role_type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] );
                        $this->logger->write_dblogmessage("insert"," add staff edrpuser, userprofile, hod_list and user_role_type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] );
                    }
                    else{
                        $mailmsg='Mail does not sent';
                        $this->logger->write_logmessage("insert"," add hod edrpuser,userprofile, hod_list and user role type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] ." and mail does not sent");
                        $this->logger->write_dblogmessage("insert"," add hod edrpuser,userprofile, hod_list and user role type ", "record added successfully for.".$pfno ." ".$_POST['emailid']." and mail does not sent" );
                                       
                    }
                    $this->logger->write_logmessage("insert", "record insert successfully.");
                    $this->logger->write_dblogmessage("insert", "record insert successfully." );
                    $this->session->set_flashdata('success','Hod added successfully.'.$mailmsg);
                    redirect('map/hodlist');
                   
                }// if $isduphod closer 
                else{
                    $this->session->set_flashdata('err_message', 'email id is already exist for this hod.');
                    redirect('map/hodlist');
                }
                
            }//closer else form run true          
        }//button check
        $this->load->view('map/set_hod');
    }
    
    /************************************** Display HOD List **************************/

    public function hodlist(){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
	$selectfield ="*";
        $whorder = "hl_uopid asc";
        $data['records']=$this->sismodel->get_orderlistspficemore('hod_list',$selectfield,'',$whorder);

//	$data['records'] = $this->sismodel->get_list('hod_list');
        $this->logger->write_logmessage("view"," view hod list" );
        $this->logger->write_dblogmessage("view"," view hod list");
        $this->load->view('map/viewhod_list',$data);
    }
    /********************* closer HOd list  *******************************************/
    public function getempdetail(){
        $combid= $this->input->post('campdept');
        $parts = explode(',',$combid); 
       // echo "sc===".$parts[0]."dept==".$parts[1];
        $datawh=array('emp_dept_code' => $parts[1],'emp_scid ' => $parts[0]);
        $comb_data = $this->sismodel->get_listspficemore('employee_master','emp_id,emp_name,emp_email,emp_code',$datawh);
        $emp_select_box ='';
        $emp_select_box.='<option value="">-------Select User for PF No.--------';
        if(count($comb_data)>0){
            foreach($comb_data as $detail){
              
               $emp_select_box.='<option value='.$detail->emp_id.'>'.$detail->emp_name. '(' .$detail->emp_code. ')'.' ';
            }
        }
        echo json_encode($emp_select_box);
    } 
    /***************************************************edit hod details*************************/
     public function edithod($id){
        
        $data['id'] = $id;
        $data['hoddata'] = $this->sismodel->get_listrow('hod_list','hl_id',$id)->row();
        $this->orgcode=$this->commodel->get_listspfic1('org_profile','org_code','org_id',1)->org_code;
        $this->campus=$this->commodel->get_listspfic2('study_center','sc_id','sc_name','org_code',$this->orgcode);
        $this->uo=$this->sismodel->get_list('uo_list');
        if(isset($_POST['edithod'])) {
            
            $this->form_validation->set_rules('campus','Campus Name','trim|required|xss_clean');
            $this->form_validation->set_rules('deptname','Department Name','trim|required|xss_clean');
            $this->form_validation->set_rules('usrname','User Name','trim|xss_clean');
            $this->form_validation->set_rules('emailid','Email Id','trim|required|xss_clean');
            $this->form_validation->set_rules('DateFrom','Datefrom','trim|xss_clean');
            $this->form_validation->set_rules('DateTo','Dateto','trim|xss_clean'); 
            $this->form_validation->set_rules('status','Status','trim|xss_clean'); 
            $this->form_validation->set_rules('jsession','jsession','trim|xss_clean'); 
            $this->form_validation->set_rules('tsession','tsession','trim|xss_clean'); 
            
            if($this->form_validation->run() == FALSE){
                $this->load->view('map/edit_hod',$data);
                return;
            }//formvalidation
            else{
                $uocode = $this->input->post('uo', TRUE); 
                $campus = $this->input->post('campus', TRUE);
                $deptname = $this->input->post('deptname', TRUE);
                $usrname = $this->input->post('usrname', TRUE);
                $datefrom = $this->input->post('DateFrom', TRUE);
		$dateto = $this->input->post('DateTo', TRUE);
		if((empty($dateto)) || ($dateto == '0000-00-00 00:00:00')){
                       $dateto='1000-01-01 00:00:00';
                }
                $emailid = $this->input->post('emailid', TRUE);
                $status = $this->input->post('status', TRUE);
                $jsession = $this->input->post('jsession', TRUE);
                $tsession = $this->input->post('tsession', TRUE);
                //echo $campus."dept==".$deptname."user". $usrname."datef". $datefrom."dto". $dateto."email==".$emailid;
                // die;               
                $logmessage = "";
                if($data['hoddata']->hl_scid !=  $campus)
                    $logmessage = "Edit HOD campus " .$data['hoddata']->hl_scid. " changed by " . $campus;
                if($data['hoddata']->hl_deptid != $deptname)
                    $logmessage = "Edit HOD Department " .$data['hoddata']->hl_deptid. " changed by " .$deptname;
                if($data['hoddata']->hl_empcode != $usrname)
                    $logmessage = "Edit HOD user " .$data['hoddata']->hl_empcode . " changed by " .$usrname;
                if($data['hoddata']->hl_datefrom != $datefrom)
                    $logmessage = "Edit HOD Date from " .$data['hoddata']->hl_datefrom . " changed by " .$datefrom;
                if($data['hoddata']->hl_dateto != $dateto)
                    $logmessage = "Edit Post Type Data " .$data['hoddata']->hl_dateto . " changed by " .$dateto;
                 if($data['hoddata']->hl_status != $status)
                    $logmessage = "Edit Post Type Data " .$data['hoddata']->hl_status . " changed by " .$status;
                
                $pfno='';
                $usr =$this->session->userdata('username'); 
                if(!empty($_POST['usrname'])){
                    if($data['hoddata']->hl_empcode != $_POST['usrname']){
                        $pfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$_POST['usrname'])->emp_code;
                    // echo "username===".$_POST['usrname'];
                    //die;
                    }
                    else{
                        $pfno=$data['hoddata']->hl_empcode;
                    }
                }
		$uopid=$this->loginmodel->get_listspfic1('authorities','priority','code',$_POST['uo'])->priority;
                $datahod = array(
                   // 'hl_userid'=> $userid,
                    'hl_empcode'=> $pfno,
                    'hl_deptid'=> $deptname,
                    'hl_scid'=> $campus,
                    'hl_uopid'=> $uopid,
                    'hl_datefrom'=> $datefrom,
                    'hl_dateto'=> $dateto,
                    'hl_status'=> $status,
			'hl_fromsession'=>$jsession,
			'hl_tosession'=>$tsession,
                    'hl_creatorid'=> $usr,
                    'hl_creatordate'=> date('y-m-d'),
                    'hl_modifierid'=> $usr,
                    'hl_modifydate'=> date('y-m-d'),
                );
              
                /*$duphod = array('hl_userid' => $data['hoddata']->hl_userid, 'hl_scid' => $_POST['campus'],'hl_deptid'=> $_POST['deptname']);
                $isduphod= $this->sismodel->isduplicatemore('hod_list',$duphod);
                if($isduphod == 1){
                
                    $this->session->set_flashdata("err_message", "Record is already exist with this combination.");
                    redirect('map/hodlist');
                    return;
           
                }
                else{*/
                    $editshforflag=$this->sismodel->updaterec('hod_list', $datahod, 'hl_id', $id);
                    if (!$editshforflag){ 
                        $this->logger->write_logmessage("error","Edit HOD Detail", "Edit HOD Detail. $logmessage ");
                        $this->logger->write_dblogmessage("error","Edit HOD Detail", "Edit HOD Detail. $logmessage ");
                        $this->session->set_flashdata('err_message','Edit HOD Detail - ' . $logmessage . '.', 'error');
                        $this->load->view('map/edit_hod', $datahod);
                    }
                    else{
                    
                        $this->logger->write_logmessage("update","Edit HOD Detail by".$this->session->userdata('username') , "Edit HOD Detail. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit HOD Detail by".$this->session->userdata('username') , "Edit HOD Detail. $logmessage ");
                        $this->session->set_flashdata('success','HOD details updated successfully.'." Email Id = "."[ " .$_POST['emailid']. " ]");
                        redirect("map/hodlist");
                    }
               // }//dupelse    
                
            }//else form validation true
        }//buton check
        $this->load->view('map/edit_hod',$data);
     }//func closer    
 /*---------------------------------------controller function for UO list-----------------------------------------------------*/
  public function setuo(){
        $this->orgcode=$this->commodel->get_listspfic1('org_profile','org_code','org_id',1)->org_code;
        $this->campus=$this->commodel->get_listspfic2('study_center','sc_id','sc_name','org_code',$this->orgcode);
	if(isset($_POST['setuo'])) {
            
            $this->form_validation->set_rules('campus','Campus Name','trim|required|xss_clean');
            $this->form_validation->set_rules('deptname','Department Name','trim|required|xss_clean');
            $this->form_validation->set_rules('usrname','User Name','trim|xss_clean');
            $this->form_validation->set_rules('emailid','Email Id','trim|xss_clean|valid_email');
            $this->form_validation->set_rules('DateFrom','Datefrom','trim|xss_clean');
            $this->form_validation->set_rules('DateTo','Dateto','trim|xss_clean'); 
            $this->form_validation->set_rules('status','Status','trim|xss_clean'); 
            $this->form_validation->set_rules('jsession','jsession','trim|xss_clean'); 
            $this->form_validation->set_rules('tsession','tsession','trim|xss_clean'); 
            
            if($this->form_validation->run() == FALSE){
                $this->load->view('map/set_uo');
                return;
            }//formvalidation
            else{
                
                /** check dulicate entry and insert in edrpuser table**********************/
             $isdup= $this->loginmodel->isduplicate('edrpuser','username',$_POST['emailid']);
                $parts = explode('@',$_POST['emailid']); 
                $passwd=md5($parts[0]);
                if(!$isdup){
                    $dataeu = array(
                        'username'=> $_POST['emailid'],
                        'password'=> $passwd,
                        'email'=> $_POST['emailid'],
                        'componentreg'=> '*',
                        'mobile'=>'',
                        'status'=>1,
                        'category_type'=>'UO',
                        'is_verified'=>1
                    );
                    /*insert record in edrpuser table*/
                 $userflageu=$this->loginmodel->insertrec('edrpuser', $dataeu);
                    $userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
                    if($userflageu){
                        // insert into  user profile db1
                        $dataup = array(
                            'userid' => $userid,
                            'firstname' => 'Head of the Department',
                            'lang' => 'english',
                            'mobile' => '',
                            'status' => 1
                        );
                        $userflagup=$this->loginmodel->insertrec('userprofile', $dataup);
                    /* insert into user_role_type */
                   $dataurt = array(
                        'userid'=> $userid,
                        'roleid'=> 5,
                        //'deptid'=> $_POST['deptname'],
                       // 'scid'=>  $_POST['campus'],
                        'usertype'=>'UO'
                    );
                    $userflagurt=$this->sismodel->insertrec('user_role_type', $dataurt) ;
                    }//if $userflageu closer
                }//ifdupedrpuser check closer     
                /*************************check for duplicate entry in uo list and insert in table*************/
              $userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
              $pfno='';
              if(!empty($_POST['usrname'])){
              		$pfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$_POST['usrname'])->emp_code;
	      }
	      
              $dupuo = array('ul_empcode' =>$pfno,'ul_authuoid' => $_POST['uo'],'ul_datefrom'=> $_POST['DateFrom']);
              //$dupuo = array('ul_userid' =>$userid/* ,'hl_scid' => $_POST['campus'],'ul_deptid'=> $_POST['deptname']*/);
              $isdupuo= $this->sismodel->isduplicatemore('uo_list',$dupuo);
                // $isdupuo= $this->loginmodel->isduplicatemore('authorities',$dupuo);
              if(!$isdupuo){
                $jsession = $this->input->post('jsession', TRUE);
                $tsession = $this->input->post('tsession', TRUE);
		$usr =$this->session->userdata('username'); 
		$dateto = $this->input->post('DateTo', TRUE);
                if((empty($dateto)) || ($dateto == '0000-00-00 00:00:00')){
                       $dateto='1000-01-01 00:00:00';
                }
		    $datauo = array(
                        'ul_userid'=> $userid,
                        'ul_empcode'=> $pfno,
                        'ul_scid'=> $_POsT['campus'],
			'ul_authuoid'=>$_POST['uo'],                        
                        'ul_datefrom'=> $_POST['DateFrom'],
                        'ul_dateto'=> $dateto,
                        'ul_status'=> $_POST['status'],
			'ul_fromsession'=>$jsession,
			'ul_tosession'=>$tsession,
                        'ul_creatorid'=> $usr,
                        'ul_creatordate'=> date('Y-m-d'),
                        'ul_modifierid'=> $usr,
                        'ul_modifydate'=> date('Y-m-d'),
                        'ul_uoname' =>$this->loginmodel->get_listspfic1('authorities','name','id',$_POST['uo'])->name,
                        'ul_uocode' =>$this->loginmodel->get_listspfic1('authorities','code','id',$_POST['uo'])->code,
                    );

                    $uolistflag=$this->sismodel->insertrec('uo_list', $datauo) ;
                    $sub=' Registred as a UO' ;
                    $mess="You are registered as a UO. Your login id ".$_POST['emailid'] ." and password is ".$passwd ;
                    //$mails = $this->mailmodel->mailsnd($email, $sub, $mess,'','Sis');
                    $mails = '';
		    $mails =$this->mailmodel->mailsnd($_POST['emailid'], $sub, $mess,'');
                   //$this->mailmodel->mailsnd('$_POST['emailid']', $sub, $mess,'','Sis');
                    //  mail flag check                         
                    if($mails){
                        $mailmsg='Please check your mail for username and password....Mail send successfully';
                        $this->logger->write_logmessage("insert"," add uo edrpuser, userprofile, uo_list and user_role_type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] );
                        $this->logger->write_dblogmessage("insert"," add staff edrpuser, userprofile, uo_list and user_role_type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] );
                    }
                    else{
                        $mailmsg='Mail does not sent';
                        $this->logger->write_logmessage("insert"," add uo edrpuser,userprofile, uo_list and user role type ", "record added successfully for.".$pfno ." ".$_POST['emailid'] ." and mail does not sent");
                        $this->logger->write_dblogmessage("insert"," add uo edrpuser,userprofile, uo_list and user role type ", "record added successfully for.".$pfno ." ".$_POST['emailid']." and mail does not sent" );
                                       
                    }
                    $this->logger->write_logmessage("insert", "record insert successfully.");
                    $this->logger->write_dblogmessage("insert", "record insert successfully." );
                    $this->session->set_flashdata('success',' UO added successfully with pfno=' .$pfno. ",emailid= " . $_POST['emailid'] .".    ".$mailmsg);
                    redirect('map/uolist');
                   
                }// if $isdupuo closer 
                else{
                    $this->session->set_flashdata('err_message', 'email id already exists for this uo.');
                    redirect('map/uolist');
                  
                }
                
            }//closer else form run true          
       }//button check
        $this->load->view('map/set_uo');
      
    }
/************************************** Display UO List *************************/
public function uolist(){
        $array_items = array('success' => '', 'err_message' => '', 'warning' =>'');
        $selectfield ="*";
        $whorder = "ul_authuoid asc";
        $data['records']=$this->sismodel->get_orderlistspficemore('uo_list',$selectfield,'',$whorder);

      //  $data['records'] = $this->sismodel->get_list('uo_list');
        $this->logger->write_logmessage("view"," view uo list" );
        $this->logger->write_dblogmessage("view"," view uo list");
        $this->load->view('map/viewuo_list',$data);
    }
    
    /***************************************************edit uo details*************************/
     public function edituo($id){
        
        $data['id'] = $id;
        $data['uodata'] = $this->sismodel->get_listrow('uo_list','ul_id',$id)->row();
        $this->orgcode=$this->commodel->get_listspfic1('org_profile','org_code','org_id',1)->org_code;
        $this->campus=$this->commodel->get_listspfic2('study_center','sc_id','sc_name','org_code',$this->orgcode);
//        $this->authresult=$this->loginmodel->get_listspfic2('authorities','id','name');
	$selectfield ="priority,code,name,id";
        $whorder = "priority asc";
        $this->authresult=$this->loginmodel->get_orderlistspficemore('authorities',$selectfield,'',$whorder);
        //$this->uo=$this->sismodel->get_list('uo_list');
        if(isset($_POST['edituo'])) {
            
            $this->form_validation->set_rules('campus','Campus Name','trim|xss_clean');
            $this->form_validation->set_rules('deptname','Department Name','trim|xss_clean');
            $this->form_validation->set_rules('usrname','User Name','trim|xss_clean');
            $this->form_validation->set_rules('emailid','Email Id','trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('DateFrom','Datefrom','trim|xss_clean');
            $this->form_validation->set_rules('DateTo','Dateto','trim|xss_clean'); 
            $this->form_validation->set_rules('status','Status','trim|xss_clean'); 
            $this->form_validation->set_rules('jsession','jsession','trim|xss_clean'); 
            $this->form_validation->set_rules('tsession','tsession','trim|xss_clean'); 
            
            if($this->form_validation->run() == FALSE){
                $this->load->view('map/edit_uo',$data);
                return;
            }//formvalidation
            else{
                $uoid = $this->input->post('uo', TRUE); 
                $campus = $this->input->post('campus', TRUE);
                $deptname = $this->input->post('deptname', TRUE);
                $usrname = $this->input->post('usrname', TRUE);
                $datefrom = $this->input->post('DateFrom', TRUE);
		$dateto = $this->input->post('DateTo', TRUE);
		if((empty($dateto)) || ($dateto == '0000-00-00 00:00:00')){
                       $dateto='1000-01-01 00:00:00';
                }
                $emailid = $this->input->post('emailid', TRUE);
                $status = $this->input->post('status', TRUE);
                $jsession = $this->input->post('jsession', TRUE);
                $tsession = $this->input->post('tsession', TRUE);
                //$pfno = $this->input->post('usrname', TRUE);
		//date check
		$frmd = strtotime($datefrom);
		if($dateto != "1000-01-01 00:00:00"){
			
			$tod = strtotime($dateto);	
			if($tod < $frmd){
				$this->session->set_flashdata('err_message','Edit UO Detail - To date must be grater than from date. From date = '.$datefrom .' To date = '.$dateto, 'error');
				$this->load->view('map/edit_uo',$data);
			        return;
			}
		}
                $logmessage = "";
                if($data['uodata']->ul_scid != $campus)
                    $logmessage = "Edit UO user campus  " .$data['uodata']->ul_scid . " changed by " .$campus;
                if($data['uodata']->ul_empcode != $pfno)
                    $logmessage = "Edit UO user " .$data['uodata']->ul_empcode . " changed by " .$pfno;
                if($data['uodata']->ul_datefrom != $datefrom)
                    $logmessage = "Edit UO Date from " .$data['uodata']->ul_datefrom . " changed by " .$datefrom;
                if($data['uodata']->ul_dateto != $dateto)
                    $logmessage = "Edit UO Date To " .$data['uodata']->ul_dateto . " changed by " .$dateto;
                 if($data['uodata']->ul_status != $status)
                    $logmessage = "Edit  UO Status " .$data['uodata']->ul_status . " changed by " .$status;
                 if($data['uodata']->ul_userid !=$emailid)
                    $logmessage = "Edit UO Emailid " .$data['uodata']->ul_userid . " changed by " .$emailid;
                $pfno='';
                if(!empty($_POST['usrname'])){
                    if($data['uodata']->ul_empcode != $_POST['usrname']){
                        $pfno=$this->sismodel->get_listspfic1('employee_master','emp_code','emp_id',$_POST['usrname'])->emp_code;
                    }
                    else{
                      $pfno=$data['uodata']->ul_empcode;
                    }
                }
                $usr =$this->session->userdata('username'); 
                $datauo = array(
                    'ul_empcode'=> $pfno,
                    'ul_authuoid'=> $uoid,
                    'ul_scid'=> $campus,
                    'ul_datefrom'=> $datefrom,
                    'ul_dateto'=> $dateto,
                    'ul_status'=> $status,
			'ul_fromsession'=>$jsession,
			'ul_tosession'=>$tsession,
                    'ul_creatorid'=> $usr,
                    'ul_creatordate'=> date('Y-m-d'),
                    'ul_modifierid'=> $usr,
                    'ul_modifydate'=> date('Y-m-d'),
                );
			if($data['uodata']->ul_empcode != $pfno){
				$userid=$this->loginmodel->get_listspfic1('edrpuser','id','username',$_POST['emailid'])->id;
				 $datauo['ul_userid']=$userid;
    				$datauo['ul_uoname']=$this->loginmodel->get_listspfic1('authorities', 'name','priority', $uoid)->name;
                               $datauo['ul_uocode']=$this->loginmodel->get_listspfic1('authorities','code','priority',$uoid)->code;
                    		 $editshforflag=$this->sismodel->insertrec('uo_list', $datauo) ;
			}
			else{
                                $editshforflag=$this->sismodel->updaterec('uo_list', $datauo, 'ul_id', $id);
			}
                      if (!$editshforflag){ 
                        $this->logger->write_logmessage("error","Edit UO Detail", "Edit UO Detail. $logmessage ");
                        $this->logger->write_dblogmessage("error","Edit UO Detail", "Edit UO Detail. $logmessage ");
                        $this->session->set_flashdata('err_message','Edit UO Detail - ' . $logmessage . '.', 'error');
                        $this->load->view('map/edit_uo', $datauo);
                      }
                     else{
                        $this->logger->write_logmessage("update","Edit UO Detail by".$this->session->userdata('username') , "Edit UO Detail. $logmessage ");
                        $this->logger->write_dblogmessage("update","Edit UO Detail by".$this->session->userdata('username') , "Edit UO Detail. $logmessage ");
                        $this->session->set_flashdata('success','UO details updated successfully pfno ='.$pfno." Email Id = "."[ " .$_POST['emailid']. " ]");
                        redirect("map/uolist");
                    }
	//	}else{
          //              $this->session->set_flashdata('err_message','Edit UO Detail - To date must be grater than from date. From date = '.$datefrom .' To date = '.$dateto, 'error');
	//	}
            }//else form validation true
        }//buton check
        $this->load->view('map/edit_uo',$data);
     }//func closer   

/****************************************** Map User with Societies ********************************************/
 public function societies() {
        $this->result = $this->sismodel->get_list('societies');
        $this->logger->write_logmessage("view"," View map societies with user setting", "societies map setting details...");
        $this->logger->write_dblogmessage("view"," View map societies with user setting", "User setting details...");
        $this->load->view('map/viewsocieties',$this->result);
     }

  /** This function is for map user with societies */

        public function addsocieties()
        {
	$this->socresult = $this->sismodel->get_list('society_master_list');
        if(isset($_POST['addsocieties'])) {

        /*Form Validation*/

        $this->form_validation->set_rules('society_id','Society Name','trim|xss_clean');
        $this->form_validation->set_rules('society_head','Society Head','trim|xss_clean');
        $this->form_validation->set_rules('society_secretary','Society Secretary','trim|xss_clean');
        $this->form_validation->set_rules('society_treasurer','Society Treasurer','trim|xss_clean');
        $this->form_validation->set_rules('society_members','Society Members','trim|xss_clean');
        $this->form_validation->set_rules('society_totalvalues','Total Values','trim|xss_clean');

         if($this->form_validation->run() == TRUE){
                        $data = array(
				'society_id'=>$_POST['society_name'],
                                'society_head'=>$_POST['society_head'],
                                'society_secretary'=>$_POST['society_secretary'],
                                'society_treasurer'=>$_POST['society_treasurer'],
                                'society_members'=>$_POST['society_members'],
                                'society_totalvalues'=>$_POST['society_totalvalues'],
                                );

           $smapflag=$this->sismodel->insertrec('societies', $data) ;
           if(!$smapflag)
           {
                $this->logger->write_logmessage("insert"," Error in adding map user with societies ", " map user with societies data insert error . "  );
                $this->logger->write_dblogmessage("insert"," Error in adding societies user ", " map user with societies data insert error . " );
                $this->session->set_flashdata('err_message','Error in adding map with societies user - ' . $_POST['smapsocieties'] , 'error');
                $this->load->view('map/addsocieties', "refresh");
           }
          else{
                $this->logger->write_logmessage("insert"," add map user with societies", "map user with societies record added successfully..."  );
                $this->logger->write_dblogmessage("insert"," add map user with societies ", "map user with societies record added successfully..." );
                $this->session->set_flashdata("success", "Map User with Societies added successfully...");
                redirect("map/societies");
		return;
              }
           }

        }
       $this->load->view('map/addsocieties');

    }

/**This function is used for update Map User with Societies records
     * @param type $id
     * @return type
     */
       public function editsocieties($id) {
       // $this->result = $this->loginmodel->get_list('societies','soc_id', 'user_id');
        $id_data_q=$this->sismodel->get_listrow('societies','id', $id);

        if ($id_data_q->num_rows() < 1)
        {
           redirect('map/editsocieties');
        }
      $editid_data = $id_data_q->row();

        /* Form fields */

         $data['society_name'] = array(
            'name' => 'society_name',
            'id' => 'society_name',
            'maxlength' => '50',
            'size' => '40',
            'value' => $this->sismodel->get_listspfic1('society_master_list','soc_name','soc_code',$editid_data->society_name)->soc_name,
            'readonly' => 'readonly'
        );


        /*$data['society_id'] = array(
           'name' => 'society_id',
           'id' => 'society_id',
           'maxlength' => '50',
           'size' => '40',
           'value' => $this->loginmodel->get_listspfic1('edrpuser','username','id',$editid_data->user_id)->username,
           'readonly' => 'readonly'
        );*/


       $data['society_head'] = array(
           'name' => 'society_head',
           'id' => 'society_head',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->society_head,

        );


       $data['society_secretary'] = array(
           'name' => 'society_secretary',
           'id' => 'society_secretary',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->society_secretary,

        );

 
       $data['society_treasurer'] = array(
           'name' => 'society_treasurer',
           'id' => 'society_treasurer',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->society_treasurer,

        );


      $data['society_members'] = array(
           'name' => 'society_members',
           'id' => 'society_members',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->society_members,

        );


      $data['society_totalvalues'] = array(
           'name' => 'society_totalvalues',
           'id' => 'society_totalvalues',
           'maxlength' => '50',
           'size' => '40',
           'value' => $editid_data->society_totalvalues,

         );

 /* $data['soc_id'] = $soc_id;

        $this->form_validation->set_rules('soc_userid','Society UserId','trim|xss_clean|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('society_name','Society Name','trim|xss_clean|required|alpha_dash');
        $this->form_validation->set_rules('society_head','Society Head','trim|xss_clean|required|alpha_dash');
        $this->form_validation->set_rules('society_secretary','Society Secretary','trim|xss_clean|required');
        $this->form_validation->set_rules('society_treasurer','Society Treasurer','trim|xss_clean|required');
        $this->form_validation->set_rules('society_members','Society Members','trim|xss_clean|required');
        $this->form_validation->set_rules('society_totalvalues','Society Total Values','trim|xss_clean|required');
       // $this->form_validation->set_rules('soc_creatorid','Society CreatorId','trim|xss_clean|required|numeric');
       // $this->form_validation->set_rules('soc_creatordate','Society CreatorDate','trim|xss_clean');
       // $this->form_validation->set_rules('soc_modifierid','Society ModifierId','trim|xss_clean');
       // $this->form_validation->set_rules('soc_modifydate','Society ModifyDate','trim|xss_clean');

        if ($_POST)
        {
           // $data['soc_userid']['value'] = $this->input->post('soc_userid', TRUE);
            $data['society_name']['value'] = $this->input->post('society_name', TRUE);
            $data['society_head']['value'] = $this->input->post('society_head', TRUE);
            $data['society_secretary']['value'] = $this->input->post('society_secretary', TRUE);
            $data['society_treasurer']['value'] = $this->input->post('society_treasurer', TRUE);
            $data['society_members']['value'] = $this->input->post('society_members', TRUE);
            $data['society_totalvalues']['value'] = $this->input->post('society_totalvalues', TRUE);
           // $data['soc_creatorid']['value'] = $this->input->post('soc_creatorid', TRUE);
           // $data['soc_creatordate']['value'] = $this->input->post('soc_creatordate', TRUE);
           // $data['soc_modifierid']['value'] = $this->input->post('soc_modifierid', TRUE);
           // $data['soc_modifydate']['value'] = $this->input->post('soc_modifydate', TRUE);

        }
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('map/editsocieties', $data);
            return;
        }
         else
        {
            $society_id = strtoupper($this->input->post('society_id', TRUE));
           // $soc_name = strtoupper($this->input->post('soc_name', TRUE));
            $society_head = strtoupper($this->input->post('society_head', TRUE));
            $society_secretary = strtoupper($this->input->post('society_secretary', TRUE));
            $society_treasurer = strtoupper($this->input->post('society_treasurer', TRUE));
            $society_members = strtoupper($this->input->post('society_members', TRUE));

            //$soc_date = strtoupper($this->input->post('soc_date', TRUE));   
            //$soc_id = $soc_id;
            $logmessage = "";


            // if($Society_data->soc_id != $soc_id)
              //  $logmessage = "Add Society " .$society_data->soc_id. " changed by " .$soc_id;


             if($societies_data->society_id != $society_id)
                $logmessage = "Add Societies " .$socieies_data->society_id. " changed by " .$society_id;


             if($societies_data->society_head != $society_head)
                $logmessage = "Add Societies " .$societies_data->society_head. " changed by " .$society_head;


             if($societies_data->society_secretary  != $society_secretary )
                $logmessage = "Add Societies " .$societies_data->society_secretary . " changed by " .$society_secretary;


             if($societies_data->society_treasurer != $society_treasurer)
                $logmessage = "Add Societies " .$societies_data->society_treasurer. " changed by " .$society_treasurer;


             if($societies_data->society_members != $society_members)
                $logmessage = "Add Societies " .$societies_data->society_members. " changed by " .$society_members;


             if($societies_data->society_totalvalues != $society_totalvalues)
                $logmessage = "Add Societies " .$societies_data->society_totalvalues. " changed by " .$society_totalvalues;



              $update_data = array(
               'society_id' =>$society_id,
               'society_head'=>($_POST['society_head']),
               'society_secretary'=>ucfirst(strtolower($_POST['society_secretary'])),
               'society_treasurer'=>ucwords($_POST['society_treasurer']),
               'society_members'=>($_POST['society_members']),
               'society_totalvalues'=>($_POST['society_totalvalues']),
               'soc_modifierid'=>$this->session->userdata('id_user'),             
               'soc_modifydate' => $soc_modifydate,
                              
          
            );
               $sdatadupe = $this->SIS_model->isduplicatemore('societies', $update_data);

                   if($socdatadupe == 1 ){

                        $this->session->set_flashdata("err_message", "Record is already exist with this combination. 'Society Id' = $society_id, 'Society Head' = $society_head , 'Society Secretary' = $society_secretary, 'Society Treasurer' = $society_treasurer, 'Society Members' =$society_members .");
                        redirect('map/viewsocieties/');
                        return;
                 }
         else{

$smapflag=$this->sismodel->updaterec('societies', $update_data, 'id', $id);
           if(!$smapflag)
            {
                $this->logger->write_logmessage("error","Error in update Map User with Societies ", "Error in Map User with Societies record update. $logmessage . " );
                $this->logger->write_dblogmessage("error","Error in update Map User with Societies ", "Error in Map User with Societies record update. $logmessage ." );
                $this->session->set_flashdata('err_message','Error updating Map User with Societies- ' . $logmessage . '.', 'error');
                $this->load->view('map/editsocieties', $data);
            }
            else{
                $this->logger->write_logmessage("update","Edit Map User with Societies", "Map User with Societies record updated successfully... $logmessage . " );
                $this->logger->write_dblogmessage("update","Edit Map Authority and User", "Map User with Societies record updated successfully... $logmessage ." );
                $this->session->set_flashdata('success','Map User with Societies record updated successfully...');
                redirect('map/viewsocieties/');
                }
        }//else
	
        redirect('map/editsocieties');
    }    */ 

 }

}//end class
