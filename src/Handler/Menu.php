<?php
register_page_handler('menu','Menu');
/**
 * Handler for the menu operation
 *
 * @package Leaguerunner
 * @version $Id $
 * @access public
 * @author Dave O'Neill <dmo@acm.org>
 * @copyright GPL
 */
class Menu extends Handler
{
	/**
	 * Initializes the template for this handler. 
	 */
	function initialize ()
	{
		$this->name = 'LeagueRunner Menu';
	}

	/**
	 * Check if the logged-in user has permission to view the menu
	 *
	 * This checks whether or not the user has authorization to view the
	 * menu.  At present, everyone with a valid session can view the menu.
	 * 
	 * @access public
	 * @return boolean True if current session is valid, false otherwise.
	 */
	function has_permission()
	{	
		global $session;
		
		/* Anyone with a valid session id has permission */
		if($session->is_valid()) {
			return true;
		}
		/* If no session, it's error time. */
		$this->name = "Not Logged In";
		$this->error_text = gettext("Your session has expired.  Please log in again");
		return false;
	}

	/**
	 * Generate the menu
	 *
	 * This generates the menu.  Each menu category is generated with
	 * its own function, which checks if the current user session 
	 * has permission for those options.  
	 *
	 * @access public
	 * @return boolean success or failure.
	 */
	function process ()
	{
		global $session;
		$this->set_template_file("Menu.tmpl");
		$this->tmpl->assign("menu_box_rows",
				array(
					$this->manage_account(),
					$this->manage_teams(),
					$this->manage_tiers(),
					$this->manage_leagues(),
					$this->manage_system(),
				)
		);
		$this->tmpl->assign("user_name", join(" ",array(
			$session->attr_get("firstname"),
			$session->attr_get("lastname")
			)));
		return true;
	}

	/**
	 * Generate the account menu
	 * @access private
	 * @return array
	 */
	function manage_account ()
	{
		global $session;
		$ops = array(
			array(
				'title' => "View/Edit My Account",
				'url_append' => '?op=playerview&userid=' . $session->attr_get("user_id"),
				'grey_out' => 0
			),
			array(
				'title' => "Change Password",
				'url_append' => '?op=changepassword',
				'grey_out' => 0
			),
			array(
				'title' => "Log Out",
				'url_append' => '?op=logout',
				'grey_out' => 0
			),
		);
		return array(
			'icon' => "menu/account.png",
			'title' => "My Account",
			'content' => $this->generate_menu_html($ops),
		);
	}
	
	function manage_teams ()
	{
		global $session, $DB;
		$sth = $DB->prepare("SELECT t.team_id, 
            t.name AS team_name, 
            if(t.captain_id = r.player_id, 
                'captain', 
                if(t.assistant_id = r.player_id, 
                    'assistant',
                    if(r.status = 'confirmed',
                        'player',
                        'requested'))) as position,
            l.league_id
        FROM 
            team t,
            teamroster r,
            leagueteams l
        WHERE 
            r.team_id = t.team_id AND 
            t.team_id = l.team_id AND
            r.player_id = ?");
		$res = $DB->execute($sth,array($session->attr_get("user_id")));
		if(DB::isError($res)) {
			$output = "No teams";
		} else {
			$output = "<table>";
			while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
				$output .= $this->gen_team_row($row['team_name'],$row['position'],$row['team_id']);
			}
			$output .= "</table>";
		}

		return array(
			'icon' => "menu/teams.png",
			'title' => "My Teams",
			'content' => $output, 
		);
	}

	function gen_team_row($name,$pos,$id)
	{
		$rv = "<tr><td>$pos</td><td>on</td><td>$name</td>";
		$rv .= "<td><a href='" . $GLOBALS['APP_CGI_LOCATION']. "?op=teamview&team_id=$id'>view/edit</a></td>";
		return $rv;
	}
	
	function manage_tiers ()
	{
	}
	
	function manage_leagues ()
	{
	}

	function manage_system ()
	{
	}

	/**
	 * Generate the HTML for a menu box
	 *
	 * Generates the HTML for one of the menu boxes on the main menu
	 * screen. The argument given is an array of arrays, containing:
	 *   title (string for menu box title)
	 *   url_append (string to append to the CGI url)
	 *   grey_out (Used to grey out menu options that are not 
	 *             implemented yet - deprecated)
	 *
	 * @access private
	 * @param  array	Array of operations supported
	 * @return string  HTML code to insert into menu box
	 */
	function generate_menu_html ( $available_ops )
	{
		global $session_id;
		global $APP_CGI_LOCATION;
		$s = "<table>";
		reset($available_ops);
		while (list($key, $val) = each($available_ops)) {
			if($val['grey_out'] == 0) 
			{
				$s .= "<tr><td><a href='" . $APP_CGI_LOCATION . $val['url_append']."'>".$val['title']."</a></td></tr>\n";
			} else {
				$s .= "<tr><td>".$val['title']."</td</tr>\n";
			}
		}
		$s .= "</table>";
		return $s;
	}

}
?>
