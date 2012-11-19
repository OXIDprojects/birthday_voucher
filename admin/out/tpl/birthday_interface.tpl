[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]
<h1>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_TITLE"}]</h1>

<div style="border:1px solid grey;margin:10px;padding:10px;">
<h2>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SETTINGS"}]</h2>
<form name="config_form" id="config_form"  action="[{ $shop->selflink }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="birthday_admin">
<input type="hidden" name="fnc" value="birthday_edit_config">
<table>
	<tr>
		<td>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_VOUCHERPREFIX"}]</td>
		<td><input type="text" name="bvoucher_prefix" value="[{$bvoucherconfig.prefix}]" /></td>
	</tr>
	<tr>
		<td>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_MAILSUBJECT"}]</td>
		<td><input type="text" name="bvoucher_mailsubject" value="[{$bvoucherconfig.mailsubject}]" /></td>
	</tr>
	<tr>
		<td>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SENDMETHOD"}]</td>
		<td>
			<select name="bvoucher_method">
			<option value="---">---
			<option value="man"[{if $bvoucherconfig.method == "man"}] selected[{/if}]>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SENDMETHOD_MANUAL"}]
			<option value="auto"[{if $bvoucherconfig.method == "auto"}] selected[{/if}]>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SENDMETHOD_AUTO"}]
			</select>
		</td>
	</tr>
	<tr>
		<td>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_ONETIMESALE"}]</td>
		<td><input type="checkbox" name="bvoucher_onetimesale" [{if $bvoucherconfig.onetimesale == "true"}]checked[{/if}] /></td>
	</tr>
	<tr>
		<td>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_NEWSLETTER"}]</td>
		<td><input type="checkbox" name="bvoucher_onlynewsletter" [{if $bvoucherconfig.onlynewsletter == "true"}]checked[{/if}] /></td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SETTINGSSEND"}]" />
		</td>
	</tr>
</table>
</form>
</div>

<div style="border:1px solid grey;margin:10px;padding:10px;">
<p>[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_TODAYBIRTHDAYCHILD"}][{if $bvouchertoday == $bvouchersentdate}][{ oxmultilang ident="SK_BIRTHDAYINTERFACE_TODAYBIRTHDAYCHILD1"}][{else}][{ oxmultilang ident="SK_BIRTHDAYINTERFACE_TODAYBIRTHDAYCHILD1A"}][{/if}][{ oxmultilang ident="SK_BIRTHDAYINTERFACE_TODAYBIRTHDAYCHILD2"}]</p>
[{foreach from=$bvouchermailrecipients item=recipient}]
[{$recipient->oxuser__oxusername->value}] <br/>
[{/foreach}]
</div>

[{if $bvouchertoday != $bvouchersentdate}]
<div style="border:1px solid grey;margin:10px;padding:10px;">
<form name="myedit" id="myedit"  action="[{ $shop->selflink }]" method="post">
[{ $oViewConf->getHiddenSid() }]
<input type="hidden" name="cl" value="birthday_admin">
<input type="hidden" name="fnc" value="send_birthdaymail">
<input type="submit" value="[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_SENDMAILMANUALLY"}]" />
</form>
</div>
[{/if}]


<div style="border:1px solid grey;margin:10px;padding:10px;">
[{foreach from=$bvoucherlogmail item=logentry}]
[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_MAILSENTTO"}][{$logentry->oxuser__oxusername->value}] <br/>
[{/foreach}]
[{ oxmultilang ident="SK_BIRTHDAYINTERFACE_LASTSENTDATE"}][{$bvouchersentdate}]
</div>




