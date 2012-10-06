<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div><h2>Newborns Template</h2>
<?php
require_once "form_submit.inc.php";
$submit_url = site_url(). '/wp-admin/edit.php?page=newborns-template';
?>
<form action="<?php echo $submit_url; ?>" method="post" accept-charset="utf-8">
	<ul class="unstyled pull-left" id="newborn_template_form_list" style="margin-top: 20px;">
		<!-- Newborn Name -->
		<li class="clear pull-left">
			<label for="newborn_name" class="pull-left" style="margin-top: 3px;">Name:&nbsp;</label>
				<input type="text" name="newborn_name" id="newborn_name" class="pull-left" />
		</li> <!-- Newborn Name -->


		<!-- Newborn Born -->
		<li class="clear pull-left">
			<label for="newborn_birth_month" class="pull-left" style="margin-top: 3px;">Date:&nbsp;</label>

			<!-- Birth Month -->
			<select name="newborn_birth_month" class="pull-left">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			<span class="pull-left">/</span>

			<!-- Birth Day -->

			<select name="newborn_birth_date" class="pull-left">
				<?php
					for ($i=1; $i <=31; $i++) {
						echo "<option value=\"{$i}\">{$i}</option>";
					}
				?>
			</select>

			<span class="pull-left">/</span>

			<!-- Birth Year -->
			<select name="newborn_birth_year" class="pull-left">
				<option value="2011">2011</option>
				<option value="2012">2012</option>
				<option value="2013">2013</option>
				<option value="2014">2014</option>
				<option value="2015">2015</option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
			</select>

			<!-- Newborn Time -->
			<label for="newborn_birth_time" class="pull-left" style="margin-top: 3px;">Time:&nbsp;</label>
				<input type="text" name="newborn_birth_time" id="newborn_birth_time" class="pull-left" />
				<span class="pull-left">(Military)</span>

		</li> <!-- Newborn Born -->

		<!-- Newborn Weight -->
		<li class="clear pull-left">

			<label for="newborn_none" class="pull-left" style="margin-top: 3px;">Weight:&nbsp;</label>

			<!-- Pounds -->
			<input type="text" name="newborn_lbs" id="newborn_lbs" class="pull-left" />
				<label for="newborn_lbs" class="pull-left" style="margin-top: 3px;">&nbsp;Lbs.</label>

			<!-- Ounces -->
			<input type="text" name="newborn_oz" id="newborn_oz" class="pull-left" />
				<label for="newborn_oz" class="pull-left" style="margin-top: 3px;">&nbsp;Oz.</label>

		</li> <!-- Newborn Weight -->

		<!-- Newborn Length -->
		<li class="clear pull-left">
			<label for="newborn_none" class="pull-left" style="margin-top: 3px;">Length:&nbsp;</label>
				<input type="text" name="newborn_length" id="newborn_length" class="pull-left" />
					<label for="newborn_none" class="pull-left" style="margin-top: 3px;">&nbsp;Inches</label>
		</li> <!-- Newborn Length -->

		<!-- Newborn Code Word -->
		<li class="clear pull-left">
			<label for="newborn_code_word" class="pull-left" style="margin-top: 3px;">Code Word:&nbsp;</label>
				<input type="text" name="newborn_code_word" id="newborn_code_word" class="pull-left" />
		</li> <!-- Newborn Code Word -->

		<!-- Image Uploader -->
		<li class="clear pull-left">
			<label for="upload_image" class="pull-left" style="margin-top: 3px;">Photo:&nbsp;</label>
				<input id="upload_image" type="text" size="36" name="upload_image" value="" class="pull-left"/>
				<button id="upload_image_button" class="pull-left">Upload Image</button>
		</li> <!-- Image Uploader -->

		<!-- Submit Button -->
		<li class="clear pull-left">
			<input type="submit" name="newborns_submit" id="submit" class="btn btn-large btn-primary bold" value="Add Newborn Post">
		</li> <!-- Submit Button -->
	</ul>
</form>
</div>