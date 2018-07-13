<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report2 extends CI_Controller {

function __construct() {
        parent::__construct();
		$this->load->model('Tag_model');
                $this->load->model('Ledger_model');
                $this->load->model('Budget_model');
                $this->load->model('Group_model');
                $this->load->model('Secunit_model');
                $this->load->model('Payment_model');
        if(empty($this->session->userdata('id_user'))) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
            redirect('user/login');
        }
    }
	var $acc_array;
	var $account_counter;
	var $logndb;
	var $ledger_data;
	var $id;
	var $name;
	var $code;
	var $children_groups = array();
	var $counter = 1;

	function Report2()
	{
		//parent::Controller();
               /*
		$this->load->model('Tag_model');
		$this->load->model('Ledger_model');
		$this->load->model('Budget_model');
		$this->load->model('Group_model');
		$this->load->model('Secunit_model');
		$this->load->model('Payment_model');
                */
		/* Check access */
		if ( ! check_access('view reports'))
		{
			$this->messages->add('Permission denied.', 'error');
			redirect('');
			return;
		}

		return;
	}
	
	function index()
	{
		$this->template->set('page_title', 'Other Reports');
		$this->template->load('template', 'report2/index');
		return;
	}	
	function tag($ledger_id = 0)
	{
		$this->load->library('session');
		$this->load->helper('text');
		/* Pagination setup */
		$this->load->library('pagination');

		$this->template->set('page_title', 'Tag Statement');
		$this->template->set('nav_links', array('report2/download/tag/' . $ledger_id  => 'Download CSV', 'report2/printpreview/tag/' . $ledger_id => 'Print Preview', 'report2/pdf/tag/' . $ledger_id => 'Download PDF'));
		$data['width'] = "70%";

		if ($_POST)
		{
			$ledger_id = $this->input->post('ledger_id', TRUE);
		}
		$data['print_preview'] = FALSE;
		$data['ledger_id'] = $ledger_id;

		/* Checking for valid ledger id */
		if ($data['ledger_id'] > 0)
		{
			$this->db->from('ledgers')->where('id', $data['ledger_id'])->limit(1);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->messages->add('Invalid Tag.', 'error');
				redirect('report2/tag');
				return;
			}
		} else if ($data['ledger_id'] < 0) {
			$this->messages->add('Invalid Ledger account.', 'error');
			redirect('report2/tag');
			return;
		}
		$default_end_date;

		/* Form fields */ 
		$this->db->from('settings');
		$detail = $this->db->get();
		foreach ($detail->result() as $row)
		{
			$date1 = $row->fy_start;
			$date2 = $row->fy_end;
		}
		$date=explode("-",$date1);
		$date2 = explode("-", $row->fy_end);
		$default_start = '01/04/'.$date[0];
		$default_end = '31/03/'.$date2[0];
		
		$curr_date = date_today_php();
		if($curr_date >= $default_end) {
			$default_end_date = $default_end;
		}
		else {
			$default_end_date = $curr_date;
		}
		
		$data['entry_date1'] = array(
			'name' => 'entry_date1',
			'id' => 'entry_date1',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_start,
		);
		$data['entry_date2'] = array(
			'name' => 'entry_date2',
			'id' => 'entry_date2',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_end_date,
		);
		$data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $start_date=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $end_date=$date[2]."-".$date[1]."-".$date[0];

                $newrange = array(
                      'startdate'  => $start_date,
                      'enddate'  => $end_date
                     );
                $this->session->set_userdata($newrange);
		/* Repopulating form */
 
		if ($_POST)
		{
			$data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
			$data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
		} 
		
		/* Form validations */ 

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

		/* Validating form */
		if ($this->form_validation->run() == FALSE)
		{
			$this->messages->add(validation_errors(), 'error');
			$this->template->load('template', 'report2/tag', $data);
			return;
		}
		else
		{
			$data_date1 = $this->input->post('entry_date1', TRUE);
			$data_date2 = $this->input->post('entry_date2', TRUE);

			$date=explode("/",$data_date1);
			$date1=$date[2]."-".$date[1]."-".$date[0];
			$date=explode("/",$data_date2);
			$date2=$date[2]."-".$date[1]."-".$date[0];
			
			$newdata = array(
	                   'date1'  => $date1,
        	           'date2'  => $date2,
	                );
			$this->session->set_userdata($newdata);
			redirect('report2/tag/' . $ledger_id);
		}
		$this->template->load('template', 'report2/tag/' . $ledger_id, $data);
		return;
	}

	function fundlist()
	{
                $this->template->set('page_title', 'Fund List');
		$this->load->library('session');
		$this->load->model('Ledger_model');

		// code for search 
		$data['search'] = '';
		$data['search_by'] = array(
			"Select" => "Select",
                        "code" => "Account Code",
                        "name"=> "Fund Name",
			"op_balance"=> "O/P Balance",
			//"cl_balance"=> "C/L Balance",
                );
		$data['search_by_active'] = '';

		$data['text'] = array(
			'name' => 'text',
			'id' => 'text',
			'maxlength' => '100',
			'size' => '40',
			'value' => '',
		);

		/* Form validation */
		$this->form_validation->set_rules('search_by', 'Search By', 'trim|required');
		$this->form_validation->set_rules('text', 'Text', 'trim|required');

		if ($_POST)
                {
                        $data['search_by_active']['value'] = $this->input->post('search_by', TRUE);
                        $data['text']['value'] = $this->input->post('text', TRUE);
                }

		/* Validating form */
		if ($this->form_validation->run() == FALSE)
		{
			$this->messages->add(validation_errors(), 'error');
			$this->template->load('template', 'report2/fundlist', $data);
			return;
		}
		else
		{
			$data_search_by = $this->input->post('search_by', TRUE);
			$data_text = $this->input->post('text', TRUE);
		}

		if($data_search_by == "Select")
                {
                        $this->messages->add('Please select search type from drop down list.', 'error');
                }
		/*if(gmp_sign($data_text) == -1) {
			$this->messages->add('Text should be a positive value.', 'error');
			redirect('report2/fundlist');
		} */
		if($data_search_by == "code")
		{
			$text = '';
			if($data_search_by == "code")
			{
				$text = 'Account Code';
			}
			if(! ctype_alnum($data_text)) {
				$this->messages->add($text . ' should be alphanumeric.', 'error');
				redirect('report2/fundlist');
			}
		}
		if($data_search_by == "op_balance" || $data_search_by == "cl_balance") {
			$balance=explode(',', $data_text);
			$data_text = implode("",$balance);
			$data['text']['value'] = $data_text;
			$text = '';
			if($data_search_by == "op_balance") {
				$text = "O/P Balance";
			}
			if($data_search_by == "cl_balance") {
				$text = "C/L Balance";
			}
			if(! is_numeric($data_text)) {
				$this->messages->add($text . 'should be numeric.', 'error');
				redirect('report2/fundlist');
			}
		}
		$data['search'] = $data_search_by;          
                $this->template->load('template', 'report2/fundlist', $data);

	}

	function printpreview($statement, $id = NULL)
        {
                $this->load->library('session');
		$this->load->model('Tag_model');
                $date1 = $this->session->userdata('date1');
                $date2 = $this->session->userdata('date2');
		$code = $this->session->userdata('code');
		$count = $id;
		if ($statement == "tag")
                {
                        $this->load->helper('text');
                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['ledger_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        /* Checking for valid ledger id */
                        if ($data['ledger_id'] < 1)
                        {
                                $this->messages->add('Invalid Ledger account.', 'error');
                                redirect('report2/tag');
                                return;
                        }
                        $this->db->from('ledgers')->where('id', $data['ledger_id'])->limit(1);
                        if ($this->db->get()->num_rows() < 1)
                        {
                                $this->messages->add('Invalid Ledger account.', 'error');
                                redirect('report2/tag');
                                return;
                        }
                        $data['report'] = "report2/tag";
                        $data['title'] = "Tag Statement for '" . $this->Tag_model->tag_name($data['ledger_id']) . "'";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;
                }
//negative_trans_report
		if ($statement == "negative_trans_report")
                {
                        $this->load->helper('text');
                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
//                        $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/negative_trans_report";
                        $data['title'] =  "Negative Transaction Statement ";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
                        $this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;
                }

		if ($statement == "sec_report")
                {
                        $this->load->helper('text');
                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/sec_report";
                        $data['title'] =  "Secondary unit Statement for '" . $this->Secunit_model->get_secunitname($data['sec_uni_id']) . "'";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;
                }
	
		if ($statement == "profitandloss_mhrd")
                {
                        $this->load->helper('text');
                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                      //  $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/profitandloss_mhrd";
                        $data['title'] =  "Income and Expenditure Statement";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
                        $this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;
                }

        	if ($statement == "profitandloss_mhrdnew")
                {
                        $this->load->helper('text');
                        $data['width'] = "100%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                      //  $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/profitandloss_mhrdnew";
                        $data['title'] =  "Income and Expenditure Statement";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
                        $this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;
                }
		
		if($statement == 'tds_report')
		{
			$this->load->helper('text');

                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                      //  $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/tds_report";
                        $data['title'] =  "TDS Report";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
                        $this->load->view('report/report_template', $data);
                        $this->session->unset_userdata('date1');
                        $this->session->unset_userdata('date2');
                        return;

		}
		if($statement == 'tdsdep_report')
		{
			$this->load->helper('text');

                        $data['width'] = "70%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                      //  $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/tdsdep_report";
                        $data['title'] =  "TDS Deposit Report";
                        $data['print_preview'] = TRUE;
			 $data['result']=$this->gettdsdepentry();
                      //  $data['entry_date1'] = $date1;
                      //  $data['entry_date2'] = $date2;
                        $this->load->view('report/report_template', $data);
                     //   $this->session->unset_userdata('date1');
                     //   $this->session->unset_userdata('date2');
                        return;

		}

		if ($statement == "schedule")
        	{
	        $arr = array();
	        $arr['code'] = $code;

	        $this->load->model('Group_model');
	        $group_details = $this->Group_model->get_schedule($code);
	        foreach ($group_details as $id => $group)
	        {
	                $id  = $group['id'];
	                $name = $group['name'];
	        }

	        if($name != '' && $id != ''){
                $title =  'Schedule - ' . $count . ' ' . $name;
                $arr['id'] = $id;
                $arr['name'] = $group['name'];
                $arr['code'] = $code;
				$arr['count'] = $count;
            }
            else{
                    $title = 'Schedule - Notes on Accounts';
            }

        //if(($name == 'Staff Payments and Benefits') || ($name == 'Academic Expenses') || ($name =='Administrative and General expenses') || ($name == 'Repairs and Maintenance') || ($name == 'Finance Costs')||($name == 'Transportations Expenses')){
        $data['report'] = "report2/schedule_template";
		$data['title'] = $title;
		$data['left_width'] = "";
		$data['right_width'] = "";
		$data['print_preview'] = TRUE;
		$data['entry_date1'] = $date1;
		$data['entry_date2'] = $date2;
		$data['isSchedule'] = "true";
		$data['arr'] = $arr;
		$this->load->view('report/report_template', $data);
		return;
		//	}
		}

		if ($statement == "IE_schedule")
        {
	        $arr = array();
	        $arr['code'] = $code;

	        $this->load->model('Group_model');
	        $group_details = $this->Group_model->get_schedule($code);
	        foreach ($group_details as $id => $group)
	        {
	                $id  = $group['id'];
	                $name = $group['name'];
	        }

	        if($name != '' && $id != ''){
                $title =  'Schedule - ' . $count . ' ' . $name;
                $arr['id'] = $id;
                $arr['name'] = $group['name'];
                $arr['code'] = $code;
				$arr['count'] = $count;
            }
            else{
                    $title = 'Schedule - Notes on Accounts';
            }

        if($count == 9) 
                $data['report'] = "report2/schedule_template1";
        elseif($count == 17)
        		$data['report'] = "report2/schedule_template3";
       	elseif($count == '15A')
       			$data['report'] = "report2/schedule_template4";
       	elseif($count == 10){
       			$this->load->model('Group_model');
        		//$group_details = $this->Group_model->get_schedule($code);

	    		$group_id = $this->Group_model->get_id('Grant and Donations');    

	    		$this->db->select('name')->from('ledgers')->where('group_id',$group_id);
			    $query = $this->db->get();
			    $counter = $query->num_rows();
			    
			    $q_result = $query->result();
			    $data['counter'] = $counter;
			    $data['q_result'] = $q_result;
       			$data['report'] = "report2/schedule_template6";
       	}
       	elseif($count == 12 || $count == 13 || $count == 14 || $count == 11)
       			$data['report'] = "report2/schedule_template5";
       	else
       			$data['report'] = "report2/schedule_template2";
		$data['title'] = $title;
		$data['left_width'] = "";
		$data['right_width'] = "";
		$data['print_preview'] = TRUE;
		$data['entry_date1'] = $date1;
		$data['entry_date2'] = $date2;
		$data['isSchedule'] = "true";
		$data['arr'] = $arr;
		$this->load->view('report/report_template', $data);
		return;
		//	}
		}
	}

	function pdf($statement, $id = NULL)
        {
		$date1 = $this->session->userdata('date1');
                $date2 = $this->session->userdata('date2');
                $this->load->helper('pdf_helper');
                $this->load->library('session');
		if($statement == "tag")
                {
                 $this->load->helper('text');
                        $data['width'] = "100%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['ledger_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        /* Checking for valid ledger id */
                        if ($data['ledger_id'] < 1)
                        {
                                $this->messages->add('Invalid Ledger account.', 'error');
                                redirect('report2/tag');
                                return;
                        }
                        $this->db->from('ledgers')->where('id', $data['ledger_id'])->limit(1);
                        if ($this->db->get()->num_rows() < 1)
                        {
                                $this->messages->add('Invalid Ledger account.', 'error');
                                redirect('report2/tag');
                                return;
                        }
                        $data['report'] = "report2/tag";
                        $data['statement'] = "Tag Statement for '" . $this->Tag_model->tag_name($data['ledger_id']) . "'";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->library('pdf');
                        $this->pdf->load_view('report/pdfreport',$data);
                        $this->pdf->render();
                        $this->pdf->stream("tag.pdf");
                        return;
                }

		//negative_trans_report
		if($statement == "negative_trans_report")
                {
                 $this->load->helper('text');
                        $data['width'] = "100%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/negative_trans_report";
                        $data['statement'] = "Negative Transaction Statement ";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->library('pdf');
                        $this->pdf->load_view('report/pdfreport',$data);
                        $this->pdf->render();
                        $this->pdf->stream("negative_trans_report.pdf");
                        return;
              }

		 //profitandloss_mhrdnew
                if($statement == "profitandloss_mhrdnew")
                {
                 $this->load->helper('text');
                        $data['width'] = "100%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/profitandloss_mhrdnew";
                        $data['statement'] = "Income And Expenditure Statement";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
                        $this->load->library('pdf');
                        $this->pdf->load_view('report/pdfreport',$data);
                        $this->pdf->render();
                        $this->pdf->stream("profitandloss_mhrdnew.pdf");
                        return;
              }
	
		if($statement == "sec_report")
                {
                 $this->load->helper('text');
                        $data['width'] = "100%";
                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                        $data['sec_uni_id'] = $this->uri->segment(4);
                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/sec_report";
                        $data['statement'] = "Secondary unit Statement for '" . $this->Secunit_model->get_secunitname($data['sec_uni_id']) . "'";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->library('pdf');
                        $this->pdf->load_view('report/pdfreport',$data);
                        $this->pdf->render();
                        $this->pdf->stream("sec_report.pdf");
                        return;
                }
		
		if($statement == "profitandloss_mhrd")
		{
			$this->load->helper('pdf_helper');
                	$this->load->library('session');
                	$date1 = $this->session->userdata('date1');
                	$date2 = $this->session->userdata('date2');

			$this->load->helper('text');
                        $data['width'] = "100%";

                        $page_count = 0;
                        /* Pagination setup */
                        $this->load->library('pagination');
                 //       $data['sec_uni_id'] = $this->uri->segment(4);
//                        $data['page_count'] = $page_count;
                        $data['report'] = "report2/profitandloss_mhrd";
                        $data['statement'] = "Income Expenditure Statement";
                        $data['print_preview'] = TRUE;
                        $data['entry_date1'] = $date1;
                        $data['entry_date2'] = $date2;
			$this->load->library('pdf');
                        $this->pdf->load_view('report/pdfreport',$data);
                        $this->pdf->render();
                        $this->pdf->stream("profitandloss_mhrd.pdf");
                        return;
	

		}
	}
	function fund_ledgerst($ledger_id = 0)
	{
		$this->load->library('session');
		$this->load->helper('text');
		/* Pagination setup */
		$this->load->library('pagination');

		$this->template->set('page_title', 'Ledger Statement');
		$this->template->set('nav_links', array('report/download/ledgerst/' . $ledger_id  => 'Download CSV', 'report/printpreview/ledgerst/' . $ledger_id => 'Print Preview', 'report/pdf/ledgerst/' . $ledger_id => 'Download PDF'));
		$data['width'] = "70%";

		if ($_POST)
		{
			$ledger_id = $this->input->post('ledger_id', TRUE);
		}
		$data['print_preview'] = FALSE;
		$data['ledger_id'] = $ledger_id;

		/* Checking for valid ledger id */
		if ($data['ledger_id'] > 0)
		{
			$this->db->from('ledgers')->where('id', $data['ledger_id'])->limit(1);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->messages->add('Invalid Ledger account.', 'error');
				redirect('report2/fund_ledgerst');
				return;
			}
		} else if ($data['ledger_id'] < 0) {
			$this->messages->add('Invalid Ledger account.', 'error');
			redirect('report2/fund_ledgerst');
			return;
		}
		$default_end_date;

		/* Form fields */ 
		$this->db->from('settings');
		$detail = $this->db->get();
		foreach ($detail->result() as $row)
		{
			$date1 = $row->fy_start;
			$date2 = $row->fy_end;
		}
		$date=explode("-",$date1);
		$date2 = explode("-", $row->fy_end);
		$default_start = '01/04/'.$date[0];
		$default_end = '31/03/'.$date2[0];
		
		$curr_date = date_today_php();
		if($curr_date >= $default_end) {
			$default_end_date = $default_end;
		}
		else {
			$default_end_date = $curr_date;
		}
		
		$data['entry_date1'] = array(
			'name' => 'entry_date1',
			'id' => 'entry_date1',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_start,
		);
		$data['entry_date2'] = array(
			'name' => 'entry_date2',
			'id' => 'entry_date2',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_end_date,
		);
		$data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $start_date=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $end_date=$date[2]."-".$date[1]."-".$date[0];

                $newrange = array(
                      'startdate'  => $start_date,
                      'enddate'  => $end_date
                     );
                $this->session->set_userdata($newrange);
		/* Repopulating form */
 
		if ($_POST)
		{
			$data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
			$data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
		} 
		
		/* Form validations */ 

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

		/* Validating form */
		if ($this->form_validation->run() == FALSE)
		{
			$this->messages->add(validation_errors(), 'error');
			$this->template->load('template', 'report2/fund_ledgerst', $data);
			return;
		}
		else
		{
			$data_date1 = $this->input->post('entry_date1', TRUE);
			$data_date2 = $this->input->post('entry_date2', TRUE);

			$date=explode("/",$data_date1);
			$date1=$date[2]."-".$date[1]."-".$date[0];
			$date=explode("/",$data_date2);
			$date2=$date[2]."-".$date[1]."-".$date[0];
			
			$newdata = array(
	                   'date1'  => $date1,
        	           'date2'  => $date2,
	                );
			$this->session->set_userdata($newdata);
			redirect('report2/fund_ledgerst/' . $ledger_id);
		}
		$this->template->load('template', 'report2/fund_ledgerst', $data);
		return;
	}

	function download($statement, $id = NULL)
	{
		if($id != '0')
		{
			$this->load->library('session');
			$date1 = $this->session->userdata('date1');
			$date2 = $this->session->userdata('date2');
			/********************** TAG STATEMENT **********************/
			if ($statement == "tag")
			{
				$this->load->helper('text');
				$ledger_id = (int)$this->uri->segment(4);
				if ($ledger_id < 1)
				return;

				$this->load->model('Ledger_model');

				$cur_balance = 0;
				$counter = 0;
				$fund_ledgerst = array();

				$fund_ledgerst[$counter] = array ("", "", "Tag STATEMENT FOR " . strtoupper($this->Tag_model->tag_name($ledger_id)), "", "", "", "", "", "", "", "");
				$counter++;

				$fund_ledgerst[$counter][0]= "Date";
				$fund_ledgerst[$counter][1]= "Number";
				$fund_ledgerst[$counter][2]= "Ledger Name";
				$fund_ledgerst[$counter][3]= "Type";
				$fund_ledgerst[$counter][4]= "Dr Amount";
				$fund_ledgerst[$counter][5]= "Cr Amount";
				$counter++;

				$this->db->from('entries')->where('tag_id', $ledger_id);
				$this->db->where('date >=', $date1);
				$this->db->where('date <=', $date2);
				$fund_ledgerst_q = $this->db->get();
				foreach ($fund_ledgerst_q->result() as $row)
				{
					/* Entry Type */
					$current_entry_type = entry_type_info($row->entry_type);
					$fund_ledgerst[$counter][0] = date_mysql_to_php_display($row->date);
					$fund_ledgerst[$counter][1] = $row->number;
					/* Opposite entry name */
					$tag_name = $this->Ledger_model->get_opp_tag_entry_name($row->id, $row->entry_type);
					$fund_ledgerst[$counter][2] = $tag_name;
					$fund_ledgerst[$counter][3] =  $current_entry_type['name'];
					$fund_ledgerst[$counter][4] = $row->dr_total;
					$fund_ledgerst[$counter][5] = $row->cr_total;
					$counter++;
				}
					$this->load->helper('csv');
					echo array_to_csv($fund_ledgerst, "Tag.csv");
					return;
			}

			/********************** SECONDARY UNIT STATEMENT **********************/
			if ($statement == "sec_report")
			{
				$this->load->helper('text');
				$sec_uni_id = (int)$this->uri->segment(4);
				if ($sec_uni_id < 1)
				return;

				$this->load->model('Secunit_model');

				$cur_balance = 0;
				$counter = 0;
				$fund_ledgerst = array();

				$fund_ledgerst[$counter] = array ("", "", "SECONDARY UNIT STATEMENT FOR " . strtoupper($this->Secunit_model->get_secunitname($sec_uni_id)), "", "", "", "", "", "", "", "");
				$counter++;

				$fund_ledgerst[$counter][0]= "Date";
				$fund_ledgerst[$counter][1]= "Number";
				$fund_ledgerst[$counter][2]= "Ledger Name";
				$fund_ledgerst[$counter][3]= "Type";
				$fund_ledgerst[$counter][4]= "Dr Amount";
				$fund_ledgerst[$counter][5]= "Cr Amount";
				$counter++;

				$this->db->from('entries');
				$this->db->where('date >=', $date1);
				$this->db->where('date <=', $date2);
				$fund_ledgerst_q = $this->db->get();
				foreach ($fund_ledgerst_q->result() as $row)
				{
					/* Entry Type */
					$current_entry_type = entry_type_info($row->entry_type);
					$fund_ledgerst[$counter][0] = date_mysql_to_php_display($row->date);
					$fund_ledgerst[$counter][1] = $row->number;
					/* Opposite entry name */
					$ledger_name = $this->Secunit_model->get_sec_unit_report($row->id, $row->entry_type, $sec_uni_id );
					if($ledger_name != "(Invalid)"){

					$fund_ledgerst[$counter][2] = $ledger_name;
					$fund_ledgerst[$counter][3] =  $current_entry_type['name'];
					$fund_ledgerst[$counter][4] = $row->dr_total;
					$fund_ledgerst[$counter][5] = $row->cr_total;
					$counter++;
					}
				}
					$this->load->helper('csv');
					echo array_to_csv($fund_ledgerst, "Secondary Unit report.csv");
					return;
			}
		
		
		}
		else{
                        redirect('report2/tag');
                        return;
		} 
		return;
	}
	
	function sec_report($sec_uni_id = 0)
	{
		$this->load->library('session');
		$this->load->helper('text');
		/* Pagination setup */
		$this->load->library('pagination');

		$this->template->set('page_title', 'Party Statement');
		$this->template->set('nav_links', array('report2/download/sec_report/' . $sec_uni_id  => 'Download CSV', 'report2/printpreview/sec_report/' . $sec_uni_id => 'Print Preview', 'report2/pdf/sec_report/' . $sec_uni_id => 'Download PDF'));
		$data['width'] = "70%";

		if ($_POST)
		{
			$sec_uni_id = $this->input->post('sec_uni_id', TRUE);
		}
		$data['print_preview'] = FALSE;
		$data['sec_uni_id'] = $sec_uni_id;

		$default_end_date;

		/* Form fields */ 
		$this->db->from('settings');
		$detail = $this->db->get();
		foreach ($detail->result() as $row)
		{
			$date1 = $row->fy_start;
			$date2 = $row->fy_end;
		}
		$date=explode("-",$date1);
		$date2 = explode("-", $row->fy_end);
		$default_start = '01/04/'.$date[0];
		$default_end = '31/03/'.$date2[0];
		
		$curr_date = date_today_php();
		if($curr_date >= $default_end) {
			$default_end_date = $default_end;
		}
		else {
			$default_end_date = $curr_date;
		}
		
		$data['entry_date1'] = array(
			'name' => 'entry_date1',
			'id' => 'entry_date1',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_start,
		);
		$data['entry_date2'] = array(
			'name' => 'entry_date2',
			'id' => 'entry_date2',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_end_date,
		);
		$data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $start_date=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $end_date=$date[2]."-".$date[1]."-".$date[0];

                $newrange = array(
                      'startdate'  => $start_date,
                      'enddate'  => $end_date
                     );
                $this->session->set_userdata($newrange);
		/* Repopulating form */
 
		if ($_POST)
		{
			$data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
			$data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
		} 
		
		/* Form validations */ 

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

		/* Validating form */
		if ($this->form_validation->run() == FALSE)
		{
			$this->messages->add(validation_errors(), 'error');
			$this->template->load('template', 'report2/sec_report', $data);
			return;
		}
		else
		{
			$data_date1 = $this->input->post('entry_date1', TRUE);
			$data_date2 = $this->input->post('entry_date2', TRUE);

			$date=explode("/",$data_date1);
			$date1=$date[2]."-".$date[1]."-".$date[0];
			$date=explode("/",$data_date2);
			$date2=$date[2]."-".$date[1]."-".$date[0];
			
			$newdata = array(
	                   'date1'  => $date1,
        	           'date2'  => $date2,
	                );
			$this->session->set_userdata($newdata);
			redirect('report2/sec_report/' . $sec_uni_id);
		}
		$this->template->load('template', 'report2/sec_report/' . $sec_uni_id, $data);
		return;
	}
//	negative_trans_report

	function negative_trans_report()
	{
		$this->load->library('session');
		$this->load->helper('text');
		/* Pagination setup */
		$this->load->library('pagination');

		$this->template->set('page_title', 'Negative Transaction Statement');
		$this->template->set('nav_links', array('report2/download/negative_trans_report/'  => 'Download CSV', 'report2/printpreview/negative_trans_report/' => 'Print Preview', 'report2/pdf/negative_trans_report/' => 'Download PDF'));
		//$this->template->set('nav_links', array('report2/printpreview/negative_trans_report/' => 'Print Preview'));
		$data['width'] = "70%";

		$data['print_preview'] = FALSE;

		$default_end_date;

		/* Form fields */ 
		$this->db->from('settings');
		$detail = $this->db->get();
		foreach ($detail->result() as $row)
		{
			$date1 = $row->fy_start;
			$date2 = $row->fy_end;
		}
		$date=explode("-",$date1);
		$date2 = explode("-", $row->fy_end);
		$default_start = '01/04/'.$date[0];
		$default_end = '31/03/'.$date2[0];
		
		$curr_date = date_today_php();
		if($curr_date >= $default_end) {
			$default_end_date = $default_end;
		}
		else {
			$default_end_date = $curr_date;
		}
		
		$data['entry_date1'] = array(
			'name' => 'entry_date1',
			'id' => 'entry_date1',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_start,
		);
		$data['entry_date2'] = array(
			'name' => 'entry_date2',
			'id' => 'entry_date2',
			'maxlength' => '11',
			'size' => '11',
			'value' => $default_end_date,
		);
		$data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $start_date=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $end_date=$date[2]."-".$date[1]."-".$date[0];

                $newrange = array(
                      'startdate'  => $start_date,
                      'enddate'  => $end_date
                     );
                $this->session->set_userdata($newrange);
		/* Repopulating form */
 
		if ($_POST)
		{
			$data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
			$data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
		} 
		
		/* Form validations */ 

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

		/* Validating form */
		if ($this->form_validation->run() == FALSE)
		{
			$this->messages->add(validation_errors(), 'error');
			$this->template->load('template', 'report2/negative_trans_report', $data);
			return;
		}
		else
		{
			$data_date1 = $this->input->post('entry_date1', TRUE);
			$data_date2 = $this->input->post('entry_date2', TRUE);

			$date=explode("/",$data_date1);
			$date1=$date[2]."-".$date[1]."-".$date[0];
			$date=explode("/",$data_date2);
			$date2=$date[2]."-".$date[1]."-".$date[0];
			
			$newdata = array(
	                   'date1'  => $date1,
        	           'date2'  => $date2,
	                );
			$this->session->set_userdata($newdata);
			redirect('report2/negative_trans_report/');
		}
		$this->template->load('template', 'report2/negative_trans_report/', $data);
		return;
	}
	
	function sundry_credit_report($party_id=0)
	{
		$this->load->library('pagination');
		$this->template->set('page_title', 'Sundry Creditors Report');
		$party_entry = $this->Secunit_model->get_allsecid();
		$data['total_party_row'] = $party_entry->num_rows();
		if ($_POST)
		{
			$party_id = $this->input->post('party_id', TRUE);
		}
		$data['party_id'] = $party_id;
		$this->db->select('entry_id')->from('entry_items')->where('secunitid',$party_id);
		$this->db->like('ledger_code','10040106','after');
		$cred_rep = $this->db->get();
		foreach($cred_rep->result() as $row)
		{
			$tot_row[] = $row->entry_id;
		}
		$this->template->load('template', 'report2/sundry_credit_report', $data);
		return;
	}

	function sundry_debit_report($party_id=0)
	{
		$this->load->library('pagination');
		$this->template->set('page_title', 'Sundry Debtors Report');
		$party_entry = $this->Secunit_model->get_allsecid();
		$data['total_party_row'] = $party_entry->num_rows();
		if ($_POST)
		{
			$party_id = $this->input->post('party_id', TRUE);
		}
		$data['party_id'] = $party_id;
		$this->db->select('entry_id')->from('entry_items')->where('secunitid',$party_id);
		$this->db->like('ledger_code','200308','after');
		$debt_rep = $this->db->get();
		foreach($debt_rep->result() as $row)
		{
			$tot_row[] = $row->entry_id;
		}
		$this->template->load('template', 'report2/sundry_debit_report', $data);
		return;
	}

	function profitandloss_mhrd()
	{
        $this->load->library('session');
		$this->template->set('page_title', 'Income And Expenditure Statement');
        $username = $this->session->userdata('user_name');
        $db1=$this->load->database('login', TRUE);
        $db1->select('username')->from('aggregateaccounts')->where('username =', $username);
        $getuser = $db1->get();
        if ($getuser->num_rows() > 0)
            $this->template->set('nav_links', array('report2/printpreview/profitandloss_mhrd' => 'PrintPreview', 'aggregation/aggregateincexp' => 'View Aggregate '));
        else
		    $this->template->set('nav_links', array('report2/printpreview/profitandloss_mhrd' => 'PrintPreview'));

		$data['left_width'] = "300";
                $data['right_width'] = "125";
                $default_end_date;

                /* Form fields */
                $this->db->from('settings');
                $detail = $this->db->get();
                foreach ($detail->result() as $row)
                {
                        $date1 = $row->fy_start;
                        $date2 = $row->fy_end;
                }
		 $newdata = array(
                      'date1'  => $date1,
                      'date2'  => $date2
                     );
                $this->session->set_userdata($newdata);

                $date=explode("-",$date1);
                $date2 = explode("-", $row->fy_end);
                $default_start = '01/04/'.$date[0];
                $default_end = '31/03/'.$date2[0];

                $curr_date = date_today_php();
                if($curr_date >= $default_end) {
                        $default_end_date = $default_end;
                }
                else {
                        $default_end_date = $curr_date;
                }
                $data['entry_date1'] = array(
                        'name' => 'entry_date1',
                        'id' => 'entry_date1',
                        'maxlength' => '11',
                        'size' => '11',
                        'value' => $default_start,
                );
                $data['entry_date2'] = array(
                        'name' => 'entry_date2',
                        'id' => 'entry_date2',
                        'maxlength' => '11',
                        'size' => '11',
                        'value' => $default_end_date,
                );

                $data['print_preview'] =FALSE;

                $data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $date1=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $date2=$date[2]."-".$date[1]."-".$date[0];

                /* Form validations */

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

                /* Repopulating form */
                if ($_POST)
                {
                        $data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
                        $data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
                }

                /* Validating form */
                if ($this->form_validation->run() == FALSE)
                {
                        $this->messages->add(validation_errors(), 'error');
                        $this->template->load('template', 'report2/profitandloss_mhrd', $data);
                        return;
                }
                else
                {
                        $data_date1 = $this->input->post('entry_date1', TRUE);
                        $data_date2 = $this->input->post('entry_date2', TRUE);

                        $date=explode("/",$data_date1);
                        $date1=$date[2]."-".$date[1]."-".$date[0];
                        $date=explode("/",$data_date2);
                        $date2=$date[2]."-".$date[1]."-".$date[0];

                        $newdata = array(
                           'date1'  => $date1,
                           'date2'  => $date2
                        );
                        $this->session->set_userdata($newdata);
                }


                $this->template->load('template', 'report2/profitandloss_mhrd', $data);
                return;

	}
	
	function schedule($code, $count)
        {
                $this->template->set('schedule', 'true');
                $data = array();
                $id = '';
                $schedule = '';
                $name = '';

                $data['code'] = $code;
		$data['count'] = $count;
                $this->load->model('Group_model');
                $group_details = $this->Group_model->get_schedule($code);
                foreach ($group_details as $id => $group)
                {
                        $id  = $group['id'];
                        $name = $group['name'];
                }

                if($name != '' && $id != ''){
                        $this->template->set('page_title', 'Schedule - ' . $count . ' ' . $name);
                        $this->session->set_userdata('code', $code);
                        $this->template->set('nav_links', array('report/download/schedule/'.$count => 'Download CSV', 'report2/printpreview/schedule/'. $count => 'Print Preview'));
                                                                            
		        $data['id'] = $id;
                }
                else{
                        $this->template->set('page_title', 'Schedule - Notes on Accounts');
                        $this->template->set('nav_links', array('report/download/schedule' => 'Download CSV', 'report/printpreview/schedule' => 'Print Preview'));
                }

		$data['print_preview'] = 'FALSE';
//		if($name == 'Transportations Expenses'){
                
  //                      $this->template->load('template', 'report2/schedule_template_2',$data);
    //                    return;
//		}else{
	      	$this->template->load('template', 'report2/schedule_template', $data);
                return;
//		}
		return;
	}

	function tds_report()
	{
	$data['print_preview'] = 'FALSE';
	$this->template->set('page_title', 'TDS Report');
	$this->template->set('nav_links', array('report2/printpreview/tds_report' => 'Print Preview'));

	 // code for search 
                $data['search'] = '';
                $data['search_by'] = array(
                        "Select" => "Select",
                        "partyname" => "Party Name",
                        "pan"=> "PAN No.",
			"vat" => "VAT No.",
			"serv_no" => "Service Tax No.",
			"party_id" => "Party ID",
			"date" => "Date",
			"entry_no" => "Entry No.",
			"amount" => "TDS Amount",
                );
                $data['search_by_active'] = '';

                $data['text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'maxlength' => '100',
                        'size' => '40',
                        'value' => '',
                );
                if ($_POST)
                {
                        $data['search_by_active']['value'] = $this->input->post('search_by', TRUE);
                        $data['text']['value'] = $this->input->post('text', TRUE);
                }
                /* Form validation */

                $this->form_validation->set_rules('search_by', 'Search By', 'trim|required');
                $this->form_validation->set_rules('text', 'Text', 'trim|required');
                /* Validating form */

                if ($this->form_validation->run() == FALSE)
                {
                        $this->messages->add(validation_errors(), 'error');
                        $this->template->load('template', 'report2/tds_report', $data);
                        return;
                }
		else
                {
                        $data_search_by = $this->input->post('search_by', TRUE);
                        $data_text = $this->input->post('text', TRUE);
                }

                $data['search'] = $data_search_by;



	$this->template->load('template', 'report2/tds_report', $data);
	
        return;
	}

	function gettdsdepentry(){
		$this->db->select('id');
                $this->db->from('ledgers');
                $this->db->limit(1);
                $this->db->where('name','TDS');
                $legid=$this->db->get()->row();


                $this->db->select('amount,dc,entry_id,date,narration');
                $this->db->from('entry_items');
                $this->db->join('entries','entry_items.entry_id=entries.id','LEFT');
                //$this->db->where('entry_items.ledger_id', '60')->where('entry_items.dc', 'D');
                $this->db->where('entry_items.ledger_id', $legid)->where('entry_items.dc', 'D');
                $query_result = $this->db->get();
                //$data['result'] = $query_result->result();
                return $query_result->result();
	}
	
	function tdsdep_report()
        {
        	$data['print_preview'] = 'FALSE';
	        $this->template->set('page_title', 'TDS Deposit Report');
        	$this->template->set('nav_links', array('report2/printpreview/tdsdep_report' => 'Print Preview'));
		$data['result']=$this->gettdsdepentry();
/*
		$this->db->select('id');
		$this->db->from('ledgers');
		$this->db->limit(1);
		$this->db->where('name','TDS');
		$legid=$this->db->get()->row();


		$this->db->select('amount,dc,entry_id,date,narration');
		$this->db->from('entry_items');
		$this->db->join('entries','entry_items.entry_id=entries.id','LEFT');
		//$this->db->where('entry_items.ledger_id', '60')->where('entry_items.dc', 'D');
		$this->db->where('entry_items.ledger_id', $legid)->where('entry_items.dc', 'D');
                $query_result = $this->db->get();
                $data['result'] = $query_result->result();
*/
		$this->template->load('template', 'report2/tdsdep_report', $data);
        	return;
	}


	function profitandloss_mhrdnew()
	{
        $this->load->library('session');
        $data['print_preview'] = 'FALSE';
		$this->template->set('page_title', 'Income And Expenditure Statement');
		//$this->template->set('nav_links', array('report2/printpreview/profitandloss_mhrdnew' => 'PrintPreview'));
		$usernameagg = $this->session->userdata('user_name');
		$db1=$this->load->database('login', TRUE);
		$db1->select('id','username')->from('edrpuser')->where('username =', $usernameagg);
		$queryagg = $db1->get();
                foreach($queryagg -> result() as $row)
		{
	                $aggid = $row->id;
                }
		$db1->select('aggtype')->from('bgasuserrolegroup')->where('userid =', $aggid);
		$queryagg1 = $db1->get();
                foreach($queryagg1 -> result() as $row)
                {
                        $aggtype = $row->aggtype;
                }
		//$aggtype;
		if($aggtype == 'agg')
	                $this->template->set('nav_links', array('report2/printpreview/profitandloss_mhrdnew' => 'PrintPreview', 'aggregation/aggregateincexp' => 'ViewAggregate','report2/printall_schedules/1' => 'Print All Schedules' ,'report2/pdf/profitandloss_mhrdnew' => 'Download PDF'));
		else
			$this->template->set('nav_links', array('report2/printpreview/profitandloss_mhrdnew' => 'PrintPreview','report2/printall_schedules/1' => 'Print All Schedules', 'report2/pdf/profitandloss_mhrdnew' => 'Download PDF'));

		$data['left_width'] = "300";
                $data['right_width'] = "125";
                $default_end_date;

                /* Form fields */
                $this->db->from('settings');
                $detail = $this->db->get();
                foreach ($detail->result() as $row)
                {
                        $date1 = $row->fy_start;
                        $date2 = $row->fy_end;
                }
		 		$newdata = array(
                      'date1'  => $date1,
                      'date2'  => $date2
                     );
                $this->session->set_userdata($newdata);

                $date=explode("-",$date1);
                $date2 = explode("-", $row->fy_end);
                $default_start = '01/04/'.$date[0];
                $default_end = '31/03/'.$date2[0];

                $curr_date = date_today_php();
                if($curr_date >= $default_end) {
                        $default_end_date = $default_end;
                }
                else {
                        $default_end_date = $curr_date;
                }
                $data['entry_date1'] = array(
                        'name' => 'entry_date1',
                        'id' => 'entry_date1',
                        'maxlength' => '11',
                        'size' => '11',
                        'value' => $default_start,
                );
                $data['entry_date2'] = array(
                        'name' => 'entry_date2',
                        'id' => 'entry_date2',
                        'maxlength' => '11',
                        'size' => '11',
                        'value' => $default_end_date,
                );

                $data['print_preview'] =FALSE;

                $data_date1 = $default_start;
                $data_date2 = $default_end_date;

                $date=explode("/",$data_date1);
                $date1=$date[2]."-".$date[1]."-".$date[0];
                $date=explode("/",$data_date2);
                $date2=$date[2]."-".$date[1]."-".$date[0];

                /* Form validations */

                $this->form_validation->set_rules('entry_date1', 'Entry Date From', 'trim|required|is_date|is_date_within_range');
                $this->form_validation->set_rules('entry_date2', 'To Entry Date', 'trim|required|is_date|is_date_within_range');

                /* Repopulating form */
                if ($_POST)
                {
                        $data['entry_date1']['value'] = $this->input->post('entry_date1', TRUE);
                        $data['entry_date2']['value'] = $this->input->post('entry_date2', TRUE);
                }

                /* Validating form */
                if ($this->form_validation->run() == FALSE)
                {
                        $this->messages->add(validation_errors(), 'error');
                        $this->template->load('template', 'report2/profitandloss_mhrdnew', $data);
                        return;
                }
                else
                {
                        $data_date1 = $this->input->post('entry_date1', TRUE);
                        $data_date2 = $this->input->post('entry_date2', TRUE);

                        $date=explode("/",$data_date1);
                        $date1=$date[2]."-".$date[1]."-".$date[0];
                        $date=explode("/",$data_date2);
                        $date2=$date[2]."-".$date[1]."-".$date[0];

                        $newdata = array(
                           'date1'  => $date1,
                           'date2'  => $date2
                        );
                        $this->session->set_userdata($newdata);
                }


                $this->template->load('template', 'report2/profitandloss_mhrdnew', $data);
                return;
	}


	function IE_schedules($code,$count)
	{
	$this->template->set('schedule', 'true');
        $data = array();
        $id = '';
        $schedule = '';
        $name = '';

        $data['code'] = $code;
	$data['count'] = $count;
        $this->load->model('Group_model');
        $group_details = $this->Group_model->get_schedule($code);

	$group_id = $this->Group_model->get_id('Grant and Donations');    
	$this->db->select('name')->from('ledgers')->where('group_id',$group_id);
	$query = $this->db->get();
	$counter = $query->num_rows();
	$q_result = $query->result();

	$data['q_result'] = $q_result;
	$data['counter'] = $counter;

        foreach ($group_details as $id => $group)
        {
            $id  = $group['id'];
            $name = $group['name'];
        }

        if($name != '' && $id != ''){
        	if($name == 'Retirement and Terminal Benefits')
        	$name = 'Employees '.$name;
	        $this->template->set('page_title', 'Schedule - ' . $count . ' ' . $name);
	        $this->session->set_userdata('code', $code);
	        $this->template->set('nav_links', array('report2/printpreview/IE_schedule/'. $count => 'Print Preview'));                                                     
        	$data['id'] = $id;
        }
        else{
                $this->template->set('page_title', 'Schedule - Notes on Accounts');
                $this->template->set('nav_links', array('report/printpreview/schedule' => 'Print Preview'));
        }

		$data['print_preview'] = 'FALSE';

		if ($count == '9'){
			$this->template->load('template', 'report2/schedule_template1', $data);
		}elseif($count == '17'){
			$this->template->load('template', 'report2/schedule_template3', $data);
		}elseif($count == '15A'){
			$this->template->load('template', 'report2/schedule_template4', $data);
		}elseif($count == '10'){
			$this->template->load('template', 'report2/schedule_template6', $data);
		}elseif($count == '14' || $count == '13'|| $count == '11' || $count == '12'){
			$this->template->load('template', 'report2/schedule_template5', $data);
		}else{
			$this->template->load('template', 'report2/schedule_template2', $data);
		}
        return;
	}

	function printall_schedules($counter)
        {
                $this->template->set('page_title', 'Print All Schedules');
                $date1 = $this->session->userdata('date1');
                $date2 = $this->session->userdata('date2');
                $this->template->load('template', 'report2/printall_schedules');
                return;
        }	
	
}

