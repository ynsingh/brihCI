<?php

class Help extends CI_Controller {

function __construct() {
        parent::__construct();
        if(empty($this->session->userdata('id_user'))) {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
            redirect('user/login');
        }
    }
	function index()
	{
		$this->template->set('page_title', 'Help');
		$this->template->load('template', 'help/main_index');
		return;
	}

	function entry(){
		$this->template->set('help', 'true');
		$this->template->load('template', 'help/entry');
		return;
	}

        function FAQ(){
                $this->template->load('template', 'help/index');
                return;
        }

	function helpdoc(){
		$this->template->load('template', 'help/helpdoc');
		return;
	}
}

/* End of file help.php */
/* Location: ./system/application/controllers/help.php */
