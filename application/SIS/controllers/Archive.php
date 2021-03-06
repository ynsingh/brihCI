<?php

/* 
 * @name Archive.php
 * @author Nagendra Kumar Singh(nksinghiitk@gmail.com)
 * @author Om Prakash (omprakashkgp@gmail.com) Staff Position archive, DDO Archive, map sc with uo archive
 * Scheme archive, Salary grade master archive
 * @author Abhay Throne(kumar.abhay.4187@gmail.com)[bank detail archive]
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');


class Archive extends CI_Controller
{
	function __construct() {
        	parent::__construct();
		$this->load->model('common_model'); 
		$this->load->model('login_model','logmodel'); 
	        $this->load->model('SIS_model',"sismodel");
	
        	if(empty($this->session->userdata('id_user'))) {
	        	$this->session->set_flashdata('flash_data', 'You don\'t have access!');
			redirect('welcome');
        	}
    	}

    	public function index() {
        	$this->feesmastera();
    	}

	/** This function Display the fees with headwise list archive records */
        public function feesmastera() {
        	$this->fmaresult = $this->common_model->get_list('fees_master_archive');
	        $this->logger->write_logmessage("view"," View fees list archive head wise", "Fees setting archive details...");
        	$this->logger->write_dblogmessage("view"," View fees list archive head wise", "Fees setting archive details...");
	        $this->load->view('archive/feesmastera');
	}

	/** This function Display the Program Subject Paper archive records */
        public function prgsubpapa() {
        	$this->prgsubaresult = $this->common_model->get_list('subject_paper_archive');
	        $this->logger->write_logmessage("view"," View Subject paper archive ", "Program Subject paper archive details...");
        	$this->logger->write_dblogmessage("view"," View subject paper archive", "Program Subject Paper archive details...");
	        $this->load->view('archive/prgsubpapa');
	}

	/** This function Display the semester rule list archive records */
        public function semrulea() {
        	$this->sraresult = $this->common_model->get_list('semester_rule_archive');
	        $this->logger->write_logmessage("view"," View semester rule archive ", "Semester rule archive details...");
        	$this->logger->write_dblogmessage("view"," View semester rule archive", "Semester rule archive details...");
	        $this->load->view('archive/semrulea');
	}

	/** This function Display the Subject semester Program list archive records */
        public function subsema() {
        	$this->ssaresult = $this->common_model->get_list('subject_semester_archive');
	        $this->logger->write_logmessage("view"," View Subject semester Program archive ", "Subject semester Program archive details...");
        	$this->logger->write_dblogmessage("view"," View Subject semester Program archive", "Subject Semester Program archive details...");
	        $this->load->view('archive/subsema');
	}
	/** This function Display the Authority list archive records */
        public function authoritya() {
        	$this->authresult = $this->logmodel->get_list('authority_archive');
	        $this->logger->write_logmessage("view"," View Authority archive ", "Authority archive details...");
        	$this->logger->write_dblogmessage("view"," View Authority archive", "Authority archive details...");
	        $this->load->view('archive/authoritya');
	}
  	/*this function has been created for display the staff position archive records */
  	public function staffpositiona(){
        	$this->result = $this->sismodel->get_list('staff_position_archive');
	        $this->logger->write_logmessage("view"," View staff position archive ", "Staff position archive details...");
        	$this->logger->write_dblogmessage("view"," View staff position archive", "Staff position archive details...");
        	$this->load->view('archive/staffpositiona');
  	}
  	/*this function has been created for display the ddo archive records */
  	public function listddoa(){
        	$this->result = $this->sismodel->get_list('ddo_archive');
	        $this->logger->write_logmessage("view"," View ddo archive ", "DDO archive details...");
        	$this->logger->write_dblogmessage("view"," View ddo archive", "DDo archive details...");
        	$this->load->view('archive/listddoa');
  	}
  	/*this function has been created for display the map sc with uo archive records */
  	public function viewscuoa(){
        	$this->result = $this->sismodel->get_list('map_sc_uo_archive');
	        $this->logger->write_logmessage("view"," View map sc with uo archive ", "Mapping sc with uo archive details...");
        	$this->logger->write_dblogmessage("view"," View map sc with uo archive", "Mapping sc with uo archive details...");
        	$this->load->view('archive/viewscuoa');
  	}
  	/*this function has been created for display the scheme archive records */
  	public function displayschemea(){
        	$this->result = $this->sismodel->get_list('scheme_department_archive');
	        $this->logger->write_logmessage("view"," View scheme archive ", "Setup scheme archive details...");
        	$this->logger->write_dblogmessage("view"," View scheme archive", "Setup scheme archive details...");
        	$this->load->view('archive/displayschemea');
  	}
  	/*this function has been created for display the Salary grade master archive records */
  	public function displaysalarygrademastera(){
        	$this->result = $this->sismodel->get_list('salary_grade_master_archive');
	        $this->logger->write_logmessage("view"," View salary grade master archive ", "Setup salary grade master archive details...");
        	$this->logger->write_dblogmessage("view"," View salary grade master archive", "Setup salary grade master archive details...");
        	$this->load->view('archive/displaysalarygrademastera');
  	}
	/*this function has been created for display the bank deails archive records */
	public function bankdetaila() {
		$this->result = $this->sismodel->get_list('bankprofile_archive');
		$this->logger->write_logmessage("view"," View bankdetaila archive ", "bankdetail archive details...");
        	$this->logger->write_dblogmessage("view"," View bankdetaila archive", "bankdetail archive details...");
		$this->load->view('archive/bankdetaila');
	}
	/*this function has been created for display the Department archive records */
        public function departmenta(){
                $this->deptaresult = $this->common_model->get_list('Department_archive');
                $this->logger->write_logmessage("view"," View Department archive ", "Department archive details...");
                $this->logger->write_dblogmessage("view"," View Department archive", "Department archive details...");
                $this->load->view('archive/departmenta');
        }
	/*this function has been created for display the user role type archive records */
        public function mapuserrolea(){
                $this->result = $this->sismodel->get_list('user_role_type_archive');
                $this->logger->write_logmessage("view"," View user role type archive ", "User Role Type archive details...");
                $this->logger->write_dblogmessage("view"," View user role type archive", "User Role Type archive details...");
                $this->load->view('archive/mapuserrolea');
        }
      /*this function has been created for display the map scheme department archive records */
        public function mapschemedepta(){
                $this->result = $this->sismodel->get_list('map_scheme_department_archive');
                $this->logger->write_logmessage("view"," View map scheme department archive ", " Map Scheme Department archive details...");
                $this->logger->write_dblogmessage("view"," View  map scheme department archive", "Map Scheme Department archive details...");
                $this->load->view('archive/mapschemedepta');
        }
     /** This function Display the Announcement list archive records */
        public function announcementa() {
                $this->annoresult = $this->common_model->get_list('announcement_archive');
                $this->logger->write_logmessage("view"," View Announcement archive", "Announcement archive details...");
                $this->logger->write_dblogmessage("view"," View Announcement archive", "Announcement archive details...");
                $this->load->view('archive/announcementa');
        }

}

