<?php
require_once('Handler/LeagueHandler.php');

# TODO: this probabably should be moved to league_reschedulegames
class game_reschedule extends LeagueHandler
{
	private $day_id;

	function __construct ( $id, $dayid )
	{
		parent::__construct( $id );

		$this->day_id = $dayid;
	}

	function has_permission ()
	{
		global $lr_session;
		return $lr_session->has_permission('league','add game', $this->league->league_id);
	}

	function process ()
	{
		$this->title = "{$this->league->fullname} &raquo; Reschedule Games";

		if(! $this->league ) {
			error_exit("That league does not exist");
		}

		$edit = &$_POST['edit'];

		switch($edit['step']) {
			case 'perform':
				return $this->perform($edit);
				break;
			case 'confirm':
				return $this->confirm($edit);
				break;
			default:
				return $this->selectDate();
				break;
		}
		error_exit("Error: This code should never be reached.");
	}

	function selectDate ()
	{
		global $dbh;

		if ( ! $this->league->load_teams() ) {
			error_exit("Error loading teams for league $league->fullname");
		}

		$output .= "<p>Games for " . strftime("%A %B %d %Y", $this->day_id) . " should be moved to: </p>";

		$sth = $dbh->prepare("SELECT COUNT(*) from schedule s, gameslot g WHERE s.game_id = g.game_id AND s.league_id = ? AND UNIX_TIMESTAMP(g.game_date) = ?");
		$sth->execute( array( $this->league->league_id, $this->day_id) );
		$need_slots = $sth->fetchColumn();

		$sth = $dbh->prepare(
			"SELECT
				UNIX_TIMESTAMP(s.game_date) AS datestamp,
				COUNT(*) AS num_avail
			 FROM
			 	league_gameslot_availability a, gameslot s
			 WHERE (a.slot_id = s.slot_id)
			 	AND isnull(s.game_id)
				AND a.league_id = ?
				AND UNIX_TIMESTAMP(s.game_date) != ?
			 GROUP BY s.game_date
			 ORDER BY s.game_date");
		$sth->execute( array( $this->league->league_id, $this->day_id) );

		$possible_dates = array();
		while($date = $sth->fetch(PDO::FETCH_OBJ)) {
			$possible_dates[$date->datestamp] = strftime("%A %B %d %Y", $date->datestamp);
		}
		if( count($possible_dates) == 0) {
			error_exit("Sorry, there are no future fields available for your league.  Check that fields have been allocated before attempting to proceed.");
		}

		$output .= form_hidden('edit[step]','confirm');
		$output .= form_hidden('edit[type]', $type);
		$output .= form_hidden('edit[olddate]', $this->day_id);
		$output .= form_select('Start date','edit[newdate]', null, $possible_dates);
		$output .= form_submit('Next step');
		return form($output);
	}

	function confirm ( &$edit )
	{
		$output = "<p>Games for "
			. strftime("%A %B %d %Y", $edit['olddate'])
			. " will be moved to "
			. strftime("%A %B %d %Y", $edit['newdate'])
			. "</p>";
		$output .= form_hidden('edit[step]','perform');
		$output .= form_hidden('edit[type]',$edit['type']);
		$output .= form_hidden('edit[olddate]',$edit['olddate']);
		$output .= form_hidden('edit[newdate]',$edit['newdate']);
		$output .= form_submit('Confirm', 'submit');
		return form($output);
	}

	function perform ( &$edit )
	{
		list($rc, $message) = $this->league->reschedule_games_for_day( $edit['olddate'], $edit['newdate']);
		if( $rc ) {
			local_redirect(url("schedule/view/" . $this->league->league_id));
		} else {
			error_exit("Failure rescheduling games: $message");
		}
	}
}

?>
