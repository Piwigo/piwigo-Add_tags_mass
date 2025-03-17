<div class="titrePage">
  <h2>{'Add tags Mass'|@translate}</h2>
</div>

<form method="post" action="{$F_ACTION}" enctype="multipart/form-data">
<fieldset>
  <legend>{'Text model'|@translate}</legend>
  <p style="margin:0 0 15px 0; text-align:left;">
  Tag 1<br />
  Tag 2<br />
  Tag 3<br /><br />
  <strong>{'Text retour'|@translate}</strong>
  </p>
<textarea name="tagname" rows="20" cols="80"></textarea>
</fieldset>



<p style="text-align:left">
<input class="submit" type="submit" name="submit" value="{'Submit'|@translate}">
</p>
</form>
