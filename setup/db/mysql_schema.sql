DROP database IF EXISTS leaguerunner;
CREATE database leaguerunner;
use leaguerunner;

grant all on leaguerunner.* to leaguerunner@localhost identified by 'ocuaweb';

#
# People in the system
#
CREATE TABLE person (

	user_id	 integer  NOT NULL PRIMARY KEY AUTO_INCREMENT,

	username        varchar(100) NOT NULL,
	password        varchar(100), -- MD5 hashed

	firstname       varchar(100),
	lastname        varchar(100),

	email	        varchar(100),
	telephone       varchar(20),

	addr_street     varchar(50),
	addr_city       varchar(50),
	addr_prov       ENUM('Ontario','Quebec','Alberta','British Columbia','Manitoba','New Brunswick','Newfoundland','Northwest Territories','Nunavut','Nova Scotia','Prince Edward Island','Saskatchewan','Yukon'),
	addr_postalcode char(6),

	gender 		ENUM("Male","Female"),

	birthdate date,

	skill_level  integer DEFAULT 0,  -- 1-5 scale
	year_started integer DEFAULT 0,  -- years playing

	allow_publish_email	ENUM("Y","N") DEFAULT 'N',  -- Publish in directory.
	allow_publish_phone	ENUM("Y","N") DEFAULT 'N',  -- Publish in directory.

	session_cookie varchar(50),
	client_ip      varchar(50),
	class	ENUM('player','volunteer','administrator') DEFAULT 'player' NOT NULL,
	last_login datetime
);

INSERT INTO person (username,password,firstname,lastname) VALUES ('admin',MD5('admin'), 'System', 'Administrator');

-- to be used for player availability
CREATE TABLE available (
	user_id		integer NOT NULL,
	available 	SET('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')
);

CREATE TABLE team (
	team_id         integer NOT NULL AUTO_INCREMENT,
	name            varchar(100) NOT NULL,
	website         varchar(100),
	shirt_colour    varchar(30),
	captain_id      integer NOT NULL,
	assistant_id    integer,
	status          ENUM("open","closed"),
	established     date,
	PRIMARY KEY (team_id),
	KEY(name)
);

CREATE TABLE teamroster (
	team_id		integer NOT NULL,
	player_id	integer NOT NULL,
	status		ENUM("confirmed","requested"),
	date_joined	date,
	PRIMARY KEY (team_id,player_id)
);

CREATE TABLE league (
	league_id	integer NOT NULL AUTO_INCREMENT,
    name		varchar(100),
	-- can play more than one day a week, so make it a set.	
	day 		SET('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
	season		ENUM('Spring','Summer','Fall','Winter','Winter Indoor'),
	tier		integer,
	ratio		ENUM('4/3','5/2','3/3','4/2','3/2','womens','mens','open'),
	active_date     date,  --  Date league starts being in use
	inactive_date	date,  --  date league is no longer in use
	coordinator_id	integer,	-- coordinator
	alternate_id	integer,	-- alternate coordinator
	max_teams	integer,
-- This information is for handling different rounds in the schedule
-- The current round is used to determine what round a new week will be 
-- and to determine what to display (if stats_display == currentround)
	current_round int DEFAULT 1,
	stats_display ENUM('all','currentround') DEFAULT 'all',
	year        integer,
	PRIMARY KEY (league_id)
);
INSERT INTO league (name,coordinator_id) VALUES ('Inactive Teams', 1);

CREATE TABLE leagueteams (
	league_id 	integer NOT NULL,
	team_id		integer NOT NULL,
	status		ENUM("confirmed","requested"),
	PRIMARY KEY (team_id,league_id)
);

-- Tables for scheduling/scorekeeping

CREATE TABLE schedule (
    game_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	league_id int NOT NULL,
-- This indicates what round this game is in.
	round int NOT NULL DEFAULT 1,
    date_played datetime, -- date and time of game.
    home_team   integer,
    away_team   integer,
    field_id    integer,
    home_score  tinyint,
    away_score  tinyint,
    home_spirit tinyint,
    away_spirit tinyint,
    original_date datetime,
    approved_by int, -- user_id of person who approved the score, or -1 if autoapproved.
    defaulted  enum('no','home','away') DEFAULT 'no'
);

-- score_entry table is used to store scores entered by either team
-- before they are approved
CREATE TABLE score_entry (
    team_id int NOT NULL,
    game_id int NOT NULL,
    entered_by int NOT NULL, -- id of user who entered score
    score_for tinyint NOT NULL, -- score for submitter's team
    score_against tinyint NOT NULL, -- score for opponent
    spirit tinyint NOT NULL,
    defaulted enum('no','us','them') DEFAULT 'no',
    PRIMARY KEY (team_id,game_id)
);


-- field information. 
CREATE TABLE field_info (
    field_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    url  varchar(255) NOT NULL -- location below server root of page containing directions, map, etc.
);

-- field assignments
CREATE TABLE field_assignment (
    league_id int NOT NULL,
    field_id  int NOT NULL,
	day	ENUM('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL
);
