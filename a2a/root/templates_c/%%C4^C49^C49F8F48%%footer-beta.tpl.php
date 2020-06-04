<?php /* Smarty version 2.6.18, created on 2011-04-08 20:05:04
         compiled from footer-beta.tpl */ ?>
			<div class="layout-footer">Nutsy Bolts is &copy; 2011 <a href="http://markandrewgoetz.com/">Mark Andrew Goetz</a>.</div>
		</div>
	<div class="layout-rightbar">
	<?php if ($this->_tpl_vars['SHOW_ADS']): ?>
	<script type="text/javascript"><!--
google_ad_client = "pub-5036305202960568";
/* Right Sidebar */
google_ad_slot = "8752759720";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php else: ?>
&nbsp;
<?php endif; ?>
	</div>
	</div>
<?php if ($this->_tpl_vars['ANALYTICS']): ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
<?php echo '
try {
var pageTracker = _gat._getTracker("UA-10783441-1");
pageTracker._trackPageview();
} catch(err) {}'; ?>
</script>
<?php endif; ?>
</body>
</html>