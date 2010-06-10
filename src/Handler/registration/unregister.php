<?php
require_once('Handler/RegistrationHandler.php');
class registration_unregister extends RegistrationHandler
{
	function has_permission()
	{
		global $lr_session;
		return $lr_session->has_permission('registration','unregister', null, $this->registration);
	}

	function process()
	{
		global $dbh;
		$edit = $_POST['edit'];
		$order_num = sprintf(variable_get('order_id_format', '%d'), $this->registration->order_id);
		$this->title = "Order $order_num &raquo; Unregister";

		switch($edit['step']) {
			case 'submit':
				$ok = $this->registration->delete();
				if ( ! $ok ) {
					error_exit ("There was an error deleting your registration information. Contact the office, quoting order #<b>$order_num</b>, to have the problem resolved.") ;
				}

				$rc = para( 'You have been successfully unregistered for this event.' );
				break;

			default:
				$rc = $this->generateConfirm();
		}

		return $rc;
	}

	function generateConfirm()
	{
		$this->title = 'Confirm unregister';

		$output = form_hidden('edit[step]', 'submit');
		$output .= para('Please confirm that you want to unregister from this event');
		$output .= para(form_submit('submit'));

		return form($output);
	}
}
?>
