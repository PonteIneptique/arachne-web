<form class="form-horizontal"style="margin:10px 50px;">
	<div class="form-group">
		<label class="control-label col-sm-2">Search</label>
		<div class="col-sm-10">
			<input id="search" class="form-control" />
		</div>
	</div>
</form>
<table class="table table-striped table-bordered" id="lemmas">
	<thead>
		<tr>
			<th>Text</th><th>Votes</th><th>Annotations</th><th>Sentences</th><th>Forms</th>
		</tr>
	</thead>
	<tbody>
		<?foreach($lemma as $lem):?>
			<tr>
				<td><a href="/lemma/<?=$lem["id_lemma"];?>"><?=$lem["text_lemma"];?></a></td>
				<td><?=$lem["votes"];?></td>
				<td><?=$lem["votes"];?></td>
				<td><?=$lem["sentences"];?></td>
				<td><?=$lem["forms"];?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>