<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Check if the currently logger in user has the necessary permissions
 * to permform the given action
 *
 * Valid permissions strings are given below :
 *
 * 'view entry'
 * 'create entry'
 * 'edit entry'
 * 'delete entry'
 * 'print entry'
 * 'email entry'
 * 'download entry'
 * 'create ledger'
 * 'edit ledger'
 * 'delete ledger'
 * 'create group'
 * 'edit group'
 * 'delete group'
 * 'create tag'
 * 'edit tag'
 * 'delete tag'
 * 'view reports'
 * 'view log'
 * 'clear log'
 * 'change account settings'
 * 'cf account'
 * 'backup account'
 * 'administer'
 * 'verify entry'
 */

if ( ! function_exists('check_access'))
{
	function check_access($action_name)
	{
		$CI =& get_instance();
		$user_role = $CI->session->userdata('user_role');
		$permissions['administrator'] = array(
			'upload logo',
			'view entry',
			'create entry',
			'print entry',
			'email entry',
			'download entry',
			'print selected entry',
			'print all entry',
			'create ledger',
			'edit ledger',
			'delete ledger',
			'create group',
			'edit group',
			'delete group',
			'create tag',
			'edit tag',
			'delete tag',
			'view reports',
			'view log',
			'clear log',
			'change account settings',
			'cf account',
			'backup account',
			'create budget',
			'edit budget',
			'delete budget',
			'reappropriate budget',
			'administer',
			'change password',
			'create projection',
			'reappropriate projection',
			'verify entry',
			'edit doc',
			'bill upload',
                        'approve/reject',
                        'vouchercreation'

		);
		$permissions['financeofficer'] = array(
                        'upload logo',
                        'view entry',
                        'create entry',
                        'print entry',
                        'email entry',
                        'download entry',
                        'print selected entry',
                        'print all entry',
                        'create ledger',
                        'edit ledger',
                        'delete ledger',
                        'create group',
                        'edit group',
                        'delete group',
                        'create tag',
                        'edit tag',
                        'delete tag',
                        'view reports',
                        'view log',
                        'clear log',
                        'change account settings',
                        'cf account',
                        'backup account',
                        'create budget',
                        'edit budget',
                        'delete budget',
                        'reappropriate budget',
                        'administer',
                        'change password',
                        'create projection',
                        'reappropriate projection',
                        'verify entry',
                        'edit doc',
                        'bill upload',
                        'approve/reject',
                        'vouchercreation'

                );
		$permissions['manager'] = array(
			'view entry',
			'create entry',
			'print entry',
			'print selected entry',
			'print all entry',
			'email entry',
			'download entry',
			'create ledger',
			'edit ledger',
			'delete ledger',
			'create group',
			'edit group',
			'delete group',
			'create tag',
			'edit tag',
			'delete tag',
			'view reports',
			'view log',
			'clear log',
			'change account settings',
			'cf account',
			'backup account',
			'create budget',
			'edit budget',
			'change password',
			'delete budget',
			'reappropriate budget',
			'create projection',
                        'reappropriate projection',
			'verify entry',
			'edit doc',
			'bill upload',
                        'approve/reject',
                        'vouchercreation'

		);
		$permissions['draccount'] = array(
                        'view entry',
                        'create entry',
                        'print entry',
                        'print selected entry',
                        'print all entry',
                        'email entry',
                        'download entry',
                        'create ledger',
                        'edit ledger',
                        'delete ledger',
                        'create group',
                        'edit group',
                        'delete group',
                        'create tag',
                        'edit tag',
                        'delete tag',
                        'view reports',
                        'view log',
                        'clear log',
                        'change account settings',
                        'cf account',
                        'backup account',
                        'create budget',
                        'edit budget',
                        'change password',
                        'delete budget',
                        'reappropriate budget',
                        'create projection',
                        'reappropriate projection',
                        'verify entry',
                        'edit doc',
                        'bill upload',
                        'approve/reject',
                        'vouchercreation'

                );
		$permissions['accountant'] = array(
			'view entry',
			'create entry',
			'print entry',
			'print selected entry',
			'print all entry',
			'email entry',
			'download entry',
			'create ledger',
			'edit ledger',
			'delete ledger',
			'create group',
			'edit group',
			'delete group',
			'create tag',
			'edit tag',
			'delete tag',
			'view reports',
			'view log',
			'clear log',
			'change password',
			'create projection',
                        'reappropriate projection',
			'verify entry',
			'edit doc',
			'bill upload',
			'approve/reject',
			'vouchercreation'
		);
		$permissions['araccount'] = array(
                        'view entry',
                        'create entry',
                        'print entry',
                        'print selected entry',
                        'print all entry',
                        'email entry',
                        'download entry',
                        'create ledger',
                        'edit ledger',
                        'delete ledger',
                        'create group',
                        'edit group',
                        'delete group',
                        'create tag',
                        'edit tag',
                        'delete tag',
                        'view reports',
                        'view log',
                        'clear log',
                        'change password',
                        'create projection',
                        'reappropriate projection',
                        'verify entry',
                        'edit doc',
                        'bill upload',
                        'approve/reject',
                        'vouchercreation'
                );
		$permissions['dataentry'] = array(
			'view entry',
			'create entry',
			'print entry',
			'print selected entry',
			'print all entry',
			'email entry',
			'download entry',
			'create ledger',
			'edit ledger',
			'change password',
			'verify entry',
			'edit doc',
			'bill upload',
			'vouchercreation'
		);
		$permissions['guest'] = array(
			'view entry',
			'print entry',
			'print selected entry',
			'print all entry',
			'email entry',
			'download entry',
		);

		if ( ! isset($user_role))
			return FALSE;

		/* If user is administrator then always allow access */
/*		if ($user_role == "administrator")
			return TRUE;
*/
		if ( ! isset($permissions[$user_role]))
			return FALSE;

		if (in_array($action_name, $permissions[$user_role]))
			return TRUE;
		else
			return FALSE;
	}
}

/* End of file access_helper.php */
/* Location: ./system/application/helpers/access_helper.php */
