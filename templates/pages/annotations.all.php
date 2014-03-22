<?if(isset($_SESSION["user"])):?>
	<div class="shadow-content padding help">
		<a href="#" class="close"><span class="glyphicon glyphicon-remove-sign"></span></a>
		<h1>Help</h1>
		<p>Propose new annotation's category and new value for each of them to enlarge the possibilities</p>
	</div>

	<div class="background-like">
		<a href="#" class="help">Help ?</a>
	</div>

	<form action="/API/annotations/type" class="form form-horizontal shadow-content padding" id="new-type" method="POST">
		<h2>Add annotation type</h2>

		<div class="form-group">
			<label class="col-sm-2 control-label">Name of the new type</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" placeholder="New type legend" name="type_name" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Target of the new type</label>
			<div class="col-sm-10">
				<select name="target">
					<option value="lemma">Lemma</option>
					<option value="sentence">Sentence</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Save</button>
			</div>
		</div>
	</form>
<?endif;?>

<div class="background-like">&nbsp;</div>
<div class="shadow-content padding">
<h2>Annotations available</h2>
<table class="table table-hover table-bordered" id="annotations-list">
	<thead>
		<tr>
			<th>Target of annotation</th>
			<th>Name of annotation</th>
			<th>Available values</th>
		</tr>
	</thead>
	<tbody>
		<?foreach($annotations as &$type):?>
			<tr>
				<td><?=$type["target"]?></td>
				<td><?=$type["text"]?></td>
				<td>
					<ul class="list-unstyled list-value">
						<?foreach($type["options"] as &$value):?>
							<li><?=$value["text"];?></li>
						<?endforeach;?>
						<?if(isset($_SESSION["user"])):?>
							<li class="new-value-line"><a class="add-value" data-target="<?=$type["id"];?>" href="#"><span class="glyphicon glyphicon-plus-sign"></span></a></li>
						<?endif;?>
					</ul>
				</td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
</div>