<form action="/API/annotations/type" class="form form-horizontal" id="new-type" method="POST">
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

<hr />
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
						<li class="new-value-line"><a class="add-value" data-target="<?=$type["id"];?>" href="#"><span class="glyphicon glyphicon-plus-sign"></span></a></li>
					</ul>
				</td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>