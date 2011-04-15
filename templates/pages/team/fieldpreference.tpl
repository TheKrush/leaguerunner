{include file=header.tpl}
<h1>{$title}</h1>
<p>
	Leaguerunner now allows teams to rank field sites in order of
	desirability for your team.  When choosing a field for your "home"
	games, Leaguerunner will allocate a field at the first <i>available
	site</i> on your list, instead of choosing a random site from within
	your preferred region as in the past.
</p>
<p>
	<b>Usage Instructions</b>
	<ol>
	<li>To rank field sites, choose a site from the pulldown list, and it will
	be added to your chosen list of sites.  You may rank all, some, or none
	of the sites allocated to the league you are participating in.
	<li>You may sort your list of sites by dragging the chosen site up or
	down (javascript required).
	<li>Sites can be removed from your list by clicking the "remove" link for that site.
	<li>No changes will take effect until you click "Submit".
	</ol>
</p>
<p>
	<b>Please remember that...</b>
	<ul>
		<li>Leaguerunner will attempt to schedule your home games on a field at one of your preferred sites, no guarantees can be made.
		<li>If you have a preference to <b>not</b> play on particular
fields, we recommend that you rank all sites and place the ones you like least at the bottom.
		<li>You cannot rank a particular field (ie: UPI 2) at a site.
	</ul>
</p>

<form method="POST">
    {html_options id="fields" name="edit[fields][]" selected=$selected options=$fields multiple="multiple" title="Select a field to add"}
<input type="hidden" name="edit[step]" value="perform" />
<input type="submit" name="submit" value="submit" />
</form>

<script type="text/javascript">
{literal}
$(function($) {
      $("#fields").bsmSelect({
        addItemTarget: 'original',
	listType: 'ol',
        animate: true,
        highlight: true,
        plugins: [
          $.bsmSelect.plugins.sortable({ axis : 'y', opacity : 0.5 }),
          $.bsmSelect.plugins.compatibility()
        ],
	extractLabel: function($o) { return $o.html() + ' (' + $o.parents('optgroup').attr('label') + ')' }
      });
});
{/literal}
</script>
