<h2>Recent history</h2>
<ul class="list-unstyled">
	<?foreach($history as &$action):?>
		<li>
			<b><?=date("d/m/y", $action["time_log"]);?></b> 

			<?if($action["table_log"] == "lemma_has_form"):?>
				<?=ucfirst($action["action_log"]);?> on
				<a href="/lemma/<?=$action["id_lemma"];?>"><?=$action["text_lemma"];?></a> in 
				<a href="/sentence/<?=$action["id_sentence"];?>"><?=mb_substr($action["text_sentence"],0,64,"UTF-8");?>...</a>
			<?elseif($action["table_log"] == "sentence"):?>
				<?=ucfirst($action["action_log"]);?> on
				<a href="/sentence/<?=$action["id_sentence"];?>"><?=mb_substr($action["text_sentence"],0,64,"UTF-8");?>...</a>
			<?elseif($action["table_log"] == "lemma"):?>
				<?=ucfirst($action["action_log"]);?> on
				<a href="/lemma/<?=$action["id_lemma"];?>"><?=$action["text_lemma"];?></a> 
			<?endif;?>
		</li>
	<?endforeach;?>
</ul>