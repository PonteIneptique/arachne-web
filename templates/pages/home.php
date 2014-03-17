<?if(isset($_SESSION["user"])):?>
<div class="gamification">
	<table>
		<tr>
			<td valign="middle">
				<input type="text" class="dial" data-width="50" data-height="50" data-min="0" value="<?=$logs["user"]?>" data-max="<?=$logs["total"]?>" data-readOnly="true">
			</td>
			<td valign="middle" style="text-indent:10px;">
				actions made by you here !
			</td>
		</tr>
		<tr>
			<td></td>
			<td><?=Gamification::Message($logs["user"], $logs["total"], $logs["max"]);?></td>
		</tr>
	</table>
</div>
<hr />
<?endif;?>


<h1>Welcome to Lasciva Roma</h1>

<h2>What is Lasciva Roma ?</h2>
<p>This whole project aims to annotate the Latin lexical field of sexuality. One of the problems we have doing that in onomastic is our unability to have a wider point of view, this tries to fill the hole.</p>

<h2>What is my role ?</h2>
<p>Your mission in this empire is to annotate forms according to your knowledge in Latin culture or grammar ! As the results shown here are issued by automatic functionnalities, many of them are unsure.</p>
<p>So for them, we need your citizen power : the <b>vote</b>.</p>
<p>But we also don't have a lot of information about each item, so maybe you could use your rhetor power : the <b>annotation</b>.</p>
<p>As we are kind of democratic hipster (We don't count Greek as our predecessor. That would be wrong.), you can actually propose new annotation type and value through the Annotation link on the top</p>
<p>Of course you can only browse the result, but where is the fun in it ?</p>

<h2>More about the project</h2>
<p>Understanding the sense of a word is a complicated task, especially in ancient languages in which the meaning of some words has been lost over the centuries and only a vague remaining sense is still known. Some of these words, such as lasciuus, a, um, appear to have had their meanings twisted in french translations (« enjoué, gai, pétulant, etc. ») as well as in english translations (petulant, sportive, playful in Lewis & Short on Perseus.tufts.edu). But its large use and context in Martial's Epigrammata drove us to think that its meaning has, after centuries of -perhaps- too much decency and appropriateness in translations, faded away. This is why we decided to create a way to collect more information about it and its companion in the lexical field of sexuality.</p>