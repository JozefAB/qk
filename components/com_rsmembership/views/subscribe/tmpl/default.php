<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
?>
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/form/normalize.css" />
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/form/demo.css" />
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/form/component.css" />
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/form/cs-select.css" />
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/form/cs-skin-boxes.css" />
<link rel="stylesheet" type="text/css" href="/qikeytwo/templates/gantry/css/font-awesome.css" />
<script type="text/javascript">
function validate_subscribe()
{
	var form = document.membershipForm;
	var msg = new Array();
	
	var clearfields = $$('.rsm_field_error');
	for (var i=0; i<clearfields.length; i++)
		clearfields[i].className = clearfields[i].className.replace(/rsm_field_error/g, '');
	
	<?php if (!empty($this->membershipterms)) { ?>
	if (!document.getElementById('rsm_checkbox_agree').checked)
		msg.push("<?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP', true); ?>");
	<?php } ?>
	
	<?php if ($this->choose_username && !$this->logged) { ?>
	if (!validate_username(form.username.value))
	{
		msg.push("<?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_USERNAME', true); ?>");
		form.username.className += ' rsm_field_error';
	}
	
	if (document.getElementById('rsm_username_message').className == 'rsm_error')
	{
		msg.push("<?php echo JText::_('COM_RSMEMBERSHIP_USERNAME_NOT_OK', true); ?>");
		form.username.className += ' rsm_field_error';
	}
	<?php } ?>
	
	<?php if ($this->choose_password && !$this->logged) { ?>
	if (document.getElementById('rsm_password').value.length == 0)
	{
		msg.push("<?php echo JText::_("COM_RSMEMBERSHIP_PLEASE_TYPE_PASSWORD", true); ?>");
		document.getElementById('rsm_password').className += ' rsm_field_error';
	}
	<?php
	$version = new JVersion();
	if (!$version->isCompatible('3.1.4')) { ?>
	else if (document.getElementById('rsm_password').value.length < 6)
	{
		msg.push("<?php echo JText::_("COM_RSMEMBERSHIP_PLEASE_TYPE_PASSWORD_6", true); ?>");
		document.getElementById('rsm_password').className += ' rsm_field_error';
	}
	<?php } ?>
	else if (document.getElementById('rsm_password').value != document.getElementById('rsm_password2').value)
	{
		msg.push("<?php echo JText::_("COM_RSMEMBERSHIP_PLEASE_CONFIRM_PASSWORD", true); ?>");
		document.getElementById('rsm_password2').className += ' rsm_field_error';
	}
	<?php } ?>
	
	<?php if (!$this->logged) { ?>
	if (form.name.value.length == 0)
	{
		msg.push("<?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_NAME', true); ?>");
		form.name.className += ' rsm_field_error';
	}
	<?php } ?>
	
	<?php if (!$this->logged) { ?>
	regex=/^[a-zA-Z0-9._\+-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
	if (form.email.value.length == 0 || !regex.test(form.email.value))
	{
		msg.push("<?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_EMAIL', true); ?>");
		form.email.className += ' rsm_field_error';
	}
	<?php } ?>
	
	<?php foreach ($this->fields_validation as $validation) { ?>
		<?php echo $validation; ?>
	<?php } ?>
	
	if (msg.length > 0)
	{
		msg_container = new Element('div');
		msg_container.innerHTML = '<div class="rsm_modal_error"><?php echo JText::_('COM_RSMEMBERSHIP_THERE_WAS_AN_ERROR', true); ?></div><p>' + msg.join('</p><p>') + '</p>';
		msg_container.className = 'rsm_modal_error_container';
		
		try {
			SqueezeBox.open(msg_container, {
				handler: 'adopt',
				size: {x: 450, y: 350}
			});
		}
		catch (err) {
			alert(msg.join("\n"));
		}
		return false;
	}
	
	return true;
}

function validate_username(username)
{
	regex=/[^a-zA-Z_0-9\.\@]+/g;
	if (username.length < 2 || regex.test(username))
		return false;
	
	return true;
}

var rsm_wait_ajax = false;
var rsm_timeout = false;

function rsm_ajax_flag()
{
	if (rsm_timeout)
		clearTimeout(rsm_timeout);
	rsm_wait_ajax = true;
	rsm_timeout = setTimeout(function () { rsm_wait_ajax = false; rsm_check_username(document.getElementById('rsm_username')); } , 2000);
}

function rsm_check_username(what)
{
	what.value = what.value.replace(/[^a-zA-Z_0-9\.\@]+/g, '');
	username = what.value;
	
	var message = document.getElementById('rsm_username_message');
	
	if (!validate_username(username))
	{
		message.style.display = '';
		message.className = 'rsm_error_special triangle-border red left';
		message.innerHTML = "<?php echo $this->escape(JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_USERNAME', true)); ?>";
		return false;
	}
	
	document.getElementById('rsm_loading').style.display = 'none';
	
	message.style.display = 'none';
	message.className = '';
	message.innerHTML = '';
	
	if (rsm_wait_ajax)
		return true;
	
	xmlHttp = rsm_get_xml_http_object();
	var url = '<?php echo JURI::root(true); ?>/index.php?option=com_rsmembership&task=checkusername';
	
	params  = 'username=' + document.getElementById('rsm_username').value;
	params += '&name=' + document.getElementById('name').value;
	params += '&email=' + document.getElementById('email').value;
	xmlHttp.open("POST", url, true);
	
	//Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");

	xmlHttp.send(params);
	
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.responseText.indexOf('|') > -1)
		{
			document.getElementById('rsm_loading').style.display = 'none';
			rsm_wait_ajax = false;
			
			var ol = document.getElementById('rsm_suggestions_ol');
			for (var i=ol.childNodes.length - 1; i>=0; i--)
				ol.removeChild(ol.childNodes[i]);
			
			is_available = false;
			var suggestions = xmlHttp.responseText.split('|');
			for (var i=0; i<suggestions.length; i++)
			{
				if (suggestions[i] == what.value)
					is_available = true;

				var a = document.createElement('a');
				a.innerHTML = suggestions[i];
				a.href = 'javascript: void(0);';
				a.onclick = function () { rsm_add_username(this.innerHTML); };
				
				var li = document.createElement('li');
				li.appendChild(a);
				
				ol.appendChild(li);
			}
			
			message.style.display = '';
			
			var suggestions = document.getElementById('rsm_suggestions');
			suggestions.style.display = '';
			suggestions.style.opacity = '1';
			suggestions.style.filter = 'alpha(opacity = 100)';
			suggestions.FadeState = 2;
			
			if (is_available)
			{
				suggestions.style.display = 'none';
				message.className = 'rsm_ok triangle-border left';
				message.innerHTML = "<?php echo $this->escape(JText::_('COM_RSMEMBERSHIP_USERNAME_IS_OK', true)); ?>";
			}
			else
			{			
				message.className = 'rsm_error triangle-border red left';
				message.innerHTML = "<?php echo $this->escape(JText::_('COM_RSMEMBERSHIP_USERNAME_NOT_OK', true)); ?>";
			}
		}
	}
}

function qikeyValidateEmail(email) {

	regex=/^[a-zA-Z0-9._\+-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
	if(!regex.test(email.value))
		{
			var email = document.getElementById('rsm_email_message');
			email.style.display = '';
			email.className = 'rsm_error triangle-border red left';
			email.innerHTML = 'Please enter a valid email address';
		} else {
			var email = document.getElementById('rsm_email_message');
			email.style.display = '';
			email.className = 'rsm_ok triangle-border left';
			email.innerHTML = "Perfect!";
		}
}

function qikeyComparePasswords(pass2) {
	var password = document.getElementById('rsm_password').value;
	var password2 = pass2.value;
	if(password !== password2)
		{
			var testElementSelector = document.getElementById('rsm_password_message');
			testElementSelector.style.display = '';
			testElementSelector.className = 'rsm_error triangle-border red left';
			testElementSelector.innerHTML = "Passwords don't match yet!";
		} else {
			var testElementSelector = document.getElementById('rsm_password_message');
			testElementSelector.style.display = '';
			testElementSelector.className = 'rsm_ok triangle-border left';
			testElementSelector.innerHTML = "Passwords Match";
		}
}

function rsm_add_username(username)
{
	var message = document.getElementById('rsm_username_message');
	
	document.getElementById('rsm_username').value = username;
	rsm_fade('rsm_suggestions');
	
	message.style.display = '';
	message.className = 'rsm_ok';
	message.innerHTML = "<?php echo $this->escape(JText::_('COM_RSMEMBERSHIP_USERNAME_IS_OK', true)); ?>";
}

function rsm_refresh_captcha()
{
	var url = '<?php echo JRoute::_('index.php?option=com_rsmembership&task=captcha&sid=#SID#', false); ?>';
	//url = url.replace(/\?sid=(.*)/, 'sid=' + rsm_random());
	url = url.replace(/#SID#/, 'captcha' + rsm_random());
	document.getElementById('submit_captcha_image').src = url;
	return false;
}

function rsm_random()
{
	var rand_no = Math.ceil(10000*Math.random())
	return rand_no;
}
</script>

<div class="fs-form-wrap" id="fs-form-wrap">
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading', $this->membership->name)); ?></h1>
		</div>
	<?php } ?>

<?php if (!$this->logged && $this->show_login) { ?>
	<?php echo $this->loadTemplate('login'); ?>
<?php } ?>

<?php if (!$this->logged) { ?>
<!--<h1><?php //echo JText::_('COM_RSMEMBERSHIP_NEW_CUSTOMER'); ?></h1>-->
<h2 id="qk_subscribe_heading" style="border:none; color:#fff700;">Excellent decision!</h2><br />
<div id="qk_subscribe_field_msg" style="color:#fff;">To get you started, we first need a little information from you. Only the most basic information is required. You can choose to provide some extra information if you'd like. This information will help us understand your interests so we can improve your experience.<br /><br /></div>
<?php } ?>
<?php if (!$this->logged && $this->show_login) { ?>
	<p><?php echo JText::_('COM_RSMEMBERSHIP_SUBSCRIBE_PLEASE_ELSE'); ?></p>
<?php } ?>
	<form method="post" class="rsmembership_form fs-form fs-form-full" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=validatesubscribe'); ?>" name="membershipForm" onsubmit="return validate_subscribe();" id="myform">
		<!--<div class="simform-inner">-->
			<!--<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_ACCOUNT_INFORMATION'); ?></h3>-->
			<ol width="100%" class="rsmembership_form_table fs-fields">
				<?php if ($this->choose_username) { ?>
				<li class="fs-current">
					<label for="rsm_username" class="fs-field-label fs-anim-upper"><?php echo JText::_('COM_RSMEMBERSHIP_USERNAME'); ?></label>
					<span class="rsm_clear"></span>
					<?php if (!$this->logged) { ?>
						<input type="text" name="username" id="rsm_username" size="40" value="<?php echo !empty($this->data->username) ? $this->escape($this->data->username) : ''; ?>" onkeyup="return rsm_check_username(this)" onkeydown="rsm_ajax_flag()" autocomplete="off" class="input-xlarge fs-anim-lower" maxlength="50" required/><?php //echo JText::_('COM_RSMEMBERSHIP_REQUIRED'); ?> <?php echo JHTML::image('components/com_rsmembership/assets/images/load.gif', 'Loading', 'id="rsm_loading" style="display: none;"'); ?>
						<!-- <span class="rsm_clear"></span> -->
						<div id="rsm_username_message" style="display: none"></div>
						<span class="rsm_clear"></span>
						<div id="rsm_suggestions" style="display: none">
							<p><strong><?php echo JText::_('COM_RSMEMBERSHIP_HERE_ARE_SOME_USERNAME_SUGGESTIONS'); ?></strong><br /><a href="javascript: void(0);" onclick="rsm_check_username(document.getElementById('rsm_username'))"><strong><?php echo JText::_('COM_RSMEMBERSHIP_CHECK_OTHER_SUGGESTIONS'); ?></strong></a></p>
							<ol id="rsm_suggestions_ol"></ol>
						</div>
						</li>
					<?php } else { ?>
					<li class="fs-current"><?php echo $this->escape($this->user->get('username')); ?></li>
					<?php } ?>
				<?php } ?>
				<?php if ($this->choose_password) { ?>
					<li>
					<label for="rsm_password" class="fs-field-label fs-anim-upper"><?php echo JText::_('COM_RSMEMBERSHIP_PASSWORD'); ?>:</label>
					<span class="rsm_clear"></span>
					<?php if (!$this->logged) { ?>
					<input class="input-xlarge fs-anim-lower" type="password" name="password" id="rsm_password" size="40" value="" autocomplete="off" required/><?php //echo JText::_('COM_RSMEMBERSHIP_REQUIRED'); ?>
					</li>
					<?php } else { ?>
					<li>**********</li>
					<?php } ?>
				
				<?php if (!$this->logged) { ?>
				<li>
					<label for="rsm_password2" class="fs-field-label fs-anim-upper"><?php echo JText::_('COM_RSMEMBERSHIP_CONFIRM_PASSWORD'); ?>:</label>
					<span class="rsm_clear"></span>
					<input class="input-xlarge fs-anim-lower" type="password" name="password2" id="rsm_password2" size="40" value="" onkeyup="return qikeyComparePasswords(this)" autocomplete="off" required/><?php //echo JText::_('COM_RSMEMBERSHIP_REQUIRED'); ?>
					<!-- <span class="rsm_clear"></span> -->
					<div id="rsm_password_message" style="display: none"></div>
					<span class="rsm_clear"></span>
				</li>
				<?php } ?>
				<?php } ?>
				<li>
					<label for="name" class="fs-field-label fs-anim-upper"><?php echo JText::_('COM_RSMEMBERSHIP_NAME'); ?>:</label>
					<span class="rsm_clear"></span>
					<?php if (!$this->logged) { ?>
					<input class="input-xlarge fs-anim-lower" type="text" name="name" id="name" size="40" value="<?php echo !empty($this->data->name) ? $this->escape($this->data->name) : ''; ?>" maxlength="50" autocomplete="off" required/><?php //echo JText::_('COM_RSMEMBERSHIP_REQUIRED'); ?>
					<?php } else { ?>
					<?php echo $this->escape($this->user->get('name')); ?>
					<?php } ?>
				</li>
				<li>
					<label for="email" class="fs-field-label fs-anim-upper"><?php echo JText::_( 'COM_RSMEMBERSHIP_EMAIL' ); ?>:</label>
					<span class="rsm_clear"></span>
					<?php if (!$this->logged) { ?>
					<input class="input-xlarge fs-anim-lower" type="text" id="email" name="email" size="40" value="<?php echo !empty($this->data->email) ? $this->escape($this->data->email) : ''; ?>" onkeyup="return qikeyValidateEmail(this)" maxlength="100" autocomplete="off" required/><?php //echo JText::_('COM_RSMEMBERSHIP_REQUIRED'); ?> <?php echo JHTML::image('components/com_rsmembership/assets/images/load.gif', 'Loading', 'id="rsm_loading" style="display: none;"'); ?>
					<!-- <span class="rsm_clear"></span> -->
					<div id="rsm_email_message" style="display: none"></div>
					<span class="rsm_clear"></span>
					<?php } else { ?>
					<?php echo $this->escape($this->user->get('email')); ?>
					<?php } ?>
				</li>

		
	<?php if (count($this->fields)) { ?>
			<!--<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_OTHER_INFORMATION'); ?></h3>-->
				<?php foreach ($this->fields as $field) { 
				$hidden = (isset($field[2]) && $field[2] == 'hidden') ? true : false;
				?>
				<li<?php echo ($hidden ? ' style="display:none"':'')?>>
					<?php echo $field[0]; ?>
				<span class="rsm_clear"></span>
					<?php echo $field[1]; ?>
				</li>
				<?php } ?>
	<?php } ?>
	
	<?php if (count($this->membership_fields)) { ?>
			<!--<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_INFORMATION'); ?></h3>-->
				<?php foreach ($this->membership_fields as $field) { 
				$hidden = (isset($field[2]) && $field[2] == 'hidden') ? true : false;
				?>
				<li<?php echo ($hidden ? ' style="display:none"':'class="data-input-trigger"')?>>
					<?php echo $field[0]; ?>
				<span class="rsm_clear"></span>
					<?php echo $field[1]; ?>
				</li>
				<?php } ?>
	<?php } ?>

	<?php if ($this->use_captcha) { ?>
	<div class="item-page">
	<!--<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_SECURITY'); ?></h3>-->
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
		<tr>
			<?php if ($this->use_recaptcha_new) { ?>
				<td>
					<div class="g-recaptcha"
						data-sitekey="<?php echo $this->escape($this->config->recaptcha_new_site_key); ?>"
						data-theme="<?php echo $this->escape($this->config->recaptcha_new_theme); ?>"
						data-type="<?php echo $this->escape($this->config->recaptcha_new_type); ?>"
					></div>
				</td>
			<?php } else { ?>
			<td width="30%">
				<label for="submit_captcha"><span class="hasTip" title="<?php echo JText::_('COM_RSMEMBERSHIP_CAPTCHA_DESC'); ?>"><?php echo JText::_($this->captcha_case_sensitive ? 'COM_RSMEMBERSHIP_CAPTCHA_CASE_SENSITIVE' : 'COM_RSMEMBERSHIP_CAPTCHA'); ?></span></label>
			</td>
			<td>
				<?php if ($this->use_builtin) { ?>
				<img src="<?php echo JRoute::_('index.php?option=com_rsmembership&task=captcha&sid='.uniqid('captcha')); ?>" id="submit_captcha_image" alt="Antispam" />
				<span class="hasTip" title="<?php echo JText::_('COM_RSMEMBERSHIP_REFRESH_CAPTCHA_DESC'); ?>"><a style="border-style: none" href="javascript: void(0)" onclick="return rsm_refresh_captcha();"><img src="<?php echo JURI::root(true); ?>/components/com_rsmembership/assets/images/refresh.gif" alt="<?php echo JText::_('COM_RSMEMBERSHIP_REFRESH_CAPTCHA'); ?>" border="0" onclick="this.blur()" align="top" /></a></span><br />
				<input type="text" name="captcha" id="submit_captcha" size="40" value="" class="rsm_textbox" />
				<?php } elseif ($this->use_recaptcha) { ?>
					<?php echo $this->show_recaptcha; ?>
				<?php } ?>
			</td>
			<?php } ?>
		</tr>
	</table>
	</div>
	<?php } ?>

	<?php echo $this->extrasview; ?>
	
	<?php if ($this->has_coupons) { ?>
	<div class="item-page">
		<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_DISCOUNTS'); ?></h3>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
			<tr>
				<td colspan="2"><?php echo JText::_('COM_RSMEMBERSHIP_COUPON_DESC'); ?></td>
			</tr>
			<tr>
				<td width="30%" height="40"><label for="coupon"><?php echo JText::_('COM_RSMEMBERSHIP_COUPON'); ?>:</label></td>
				<td><input type="text" name="coupon" class="rsm_textbox" id="coupon" size="40" value="" maxlength="64" /></td>
			</tr>
		</ol>
	</div>
	<?php } ?>

	<?php if ($this->one_page_checkout) { ?>
		<?php echo $this->loadTemplate('payment'); ?>
	<?php } ?>

	<?php if (!empty($this->membershipterms)) { ?>
	<div class="item-page">
		<!--<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_TERM'); ?></h3>-->
		<div id="rsm_terms_frame">
			<div class="item-page">
				<div id="rsm_terms_container">
					<h1><?php echo $this->escape($this->membershipterms->name); ?></h1>
					<?php
					if (RSMembershipHelper::getConfig('trigger_content_plugins')) {
						$this->membershipterms->description = JHtml::_('content.prepare', $this->membershipterms->description);
					}
					echo $this->membershipterms->description;
					?>
				</div> <!-- rsm_terms_container -->
			</div>
		</div>
		<input type="checkbox" id="rsm_checkbox_agree" class="pull-left" /> <label for="rsm_checkbox_agree"><?php echo JText::_('COM_RSMEMBERSHIP_I_AGREE'); ?> (<?php echo $this->escape($this->membershipterms->name); ?>)</label>
	</div>
	<?php } ?>
	</ol>


	<!--<div class="form-actions">-->
		<button type="submit" id = "subButton" class="button btn btn-success pull-right submit hidden"><?php echo $this->one_page_checkout ? JText::_('COM_RSMEMBERSHIP_SUBSCRIBE') : JText::_('COM_RSMEMBERSHIP_NEXT');?></button>
	<!--</div>-->

	<?php echo JHtml::_('form.token');?>
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="subscribe" />
	<input type="hidden" name="task" value="validatesubscribe" />
	<input type="hidden" name="cid" value="<?php echo $this->membership->id; ?>" />
	<!--</div>-->
	<span class="final-message"></span>
	</form>
</div> 
<script src="/qikeytwo/templates/gantry/js/classie.js"></script>
<script src="/qikeytwo/templates/gantry/js/selectFx.js"></script>
<script src="/qikeytwo/templates/gantry/js/zfullscreenForm.js"></script>
		<script>
			(function() {
				var formWrap = document.getElementById( 'fs-form-wrap' );

				[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
					new SelectFx( el, {
						stickyPlaceholder: false,
						onChange: function(val){
							document.querySelector('span.cs-placeholder').style.backgroundColor = val;
						}
					});
				} );

				new FForm( formWrap, {
					onReview : function() {
						classie.add( document.body, 'overview' ); // for demo purposes only
						var submitButton = document.getElementById('subButton');
						classie.remove(submitButton, 'hidden');
					}
				} );
			})();
		</script>