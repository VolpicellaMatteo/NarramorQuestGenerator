<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8" />
	<title>Narramor quest system prototype</title>
	<meta name="generator" content="BBEdit 15.0">
	<link rel="stylesheet" href="css/pico.min.css">
	<link rel="shortcut icon" href="https://www.narramor.com/img/favicon.png" />
</head>
<body>
	<header class='container'>
		<img src='https://www.gamyth.com/wp-content/uploads/2024/03/Narramor-Logo.png'>
		<h1>Narramor quest system generator 0.6</h1>
	</header>

    <main class="container">
      <div class="grid">
      	<section>

<?php
$id = intval($_REQUEST['player'] ?? 1);
$questgiverId =  intval($_REQUEST['questgiver'] ?? 1);
if (empty($_REQUEST['random']))
	$questType = $_REQUEST['questtype'];
else
	$questType = 'q' . random_int(1, 5);
function PickOne (array $listOfFieldsToConsider, array $record): string {
	$plausibili = array();
	foreach ($listOfFieldsToConsider as $campo)
		if ($record[$campo])
			$plausibili [] = $campo;
	return $plausibili[array_rand($plausibili)];
	}

function ImmediateResult ($text) {
	die ("$text
			</section>
		</div>
	</main>
</body>
	");
	}

require 'sarat/sar-el_core.inc.php';
$db = new DB_Sarel;
$abort = array();

$db->query ("SELECT * FROM players WHERE id = $id");
if ($db->next_record())
	$player = $db->Record;
if (empty($player)) $abort[] = 'Giocatore non trovato';
$linguaPrescelta = $player['language'];

$db->query ("SELECT * FROM npc WHERE masterId = $questgiverId  order by language=$linguaPrescelta desc LIMIT 1");
if ($db->next_record())
	$npc = $db->Record;
if (empty($npc)) $abort[] ='NPC non trovato';
else
	$livelloPrescelto = $npc['socialRank'];

$db->query("SELECT quest.* FROM quest JOIN npc ON npc.stringa=quest.npc WHERE quest.authorId = $id AND npc.organization = '" . $npc['organization'] . "'");
if ($db->next_record()) {
	if ($db->f('npc') == $questgiverId) {
		ImmediateResult("<p>Welcome back, {$player['title']}. Have you solved my quest by any chance. Let me remind you what I asked…</p>".$db->f('ia'));
		}
	else {
		ImmediateResult("<p>Welcome, adventurer. I have heard that my associate <strong>{$npc['title']}</strong> gave you a quest. Let me remind you what was asked of you…</p>".$db->f('ia'));
		}
	}

$db->query ("SELECT * FROM factions WHERE language = $linguaPrescelta");
while ($db->next_record())
	$factions[$db->f('stringa')] = $db->Record;

$db->query ("SELECT * FROM menu_places WHERE language = $linguaPrescelta");
while ($db->next_record())
	$places[$db->f('stringa')] = $db->f('title');

$db->query ("SELECT * FROM menu_ambienti WHERE language = $linguaPrescelta");
while ($db->next_record())
	$buildings[$db->f('stringa')] = $db->f('title');

$db->query ("SELECT * FROM menu_hidingPlace WHERE language = $linguaPrescelta");
while ($db->next_record())
	$hidingPlaces[$db->f('stringa')] = $db->f('title');

$db->query ("SELECT * FROM levels WHERE stringa = {$player['level']} order by language=$linguaPrescelta desc LIMIT 1");
if ($db->next_record())
	$level = $db->Record;
if (empty($level)) $abort[] ='Livello non trovato';
/*
Array
(
	[masterId] => 3
	[title] => sellsword
	[questGiverMin] => 3
	[questGiverMax] => 4
	[questObjectRarityMin] => 3
	[questObjectRarityMax] => 4
)
*/

if (empty( $abort)) {
	echo "
	<details>
		<summary>NPC</summary>
		<ul>
			<li>NPC: {$npc['title']}</li>
			<li>NPC level: {$npc['socialRank']}</li>
			<li>NPC organization: {$npc['organization']}</li>";
	if ($livelloPrescelto < $level['questGiverMin']) {
		$abort[0] = "NPC's level is too low for this PC. No quest given.";
		echo "<li class='warning'>$abort[0]</li>";
		}
	if ($livelloPrescelto > $level['questGiverMax']) {
		$abort[0] = "NPC's level is too high for this PC. No quest given.";
		echo "<li class='warning'>$abort[0]</li>";
		}
	echo "
		</ul>
	</details>";

	echo "
	<details>
		<summary>Player</summary>
		<ul>
			<li>Player: {$player['title']}</li>
			<li>Player level name: {$level['title']}</li>
			<li>Player level (zero based): {$player['level']}</li>
			<li>Player's minimum level quests: {$level['questGiverMin']}</li>
			<li>Player's maximum level quests: {$level['questGiverMax']}</li>
			<li>Player's human interface language: {$player['language']}</li>
		</ul>
	</details>";
	
	if (! $abort) {
		$questObjectLevel = rand($level['questObjectRarityMin'], $level['questObjectRarityMax']);

		if (1001 == $player['language']) {
			$abort[] = "items database not available in Italian yet. Switching to English for items.";
			$linguaPrescelta = 1002;
			}

		$db->query ("SELECT * FROM items WHERE rarity = $questObjectLevel AND questobject = 1 AND language = $linguaPrescelta ORDER BY RAND() LIMIT 1");
		if (!$db->next_record())
			$abort[] = "Item level $questObjectLevel not found, at least for language $linguaPrescelta";
		else {
			$item = $db->Record;
			$chosenOrganization = $factions[strtolower($npc['organization'])];
			unset ($factions[strtolower($npc['organization'])]);
			$owner = PickOne(array_keys($factions), $item);
			$hidingPlace = PickOne(array_keys($hidingPlaces), $item);
			
			
		$db->query ("SELECT * FROM rooms WHERE level = $questObjectLevel AND `$hidingPlace` = 1 AND faction = '$owner' ORDER BY RAND() LIMIT 1");
		if (!$db->next_record()) {
			$abort[] = "Could not find an appropriate room for object (level: $questObjectLevel; hiding place: $hidingPlace; faction: $owner)";
			$possessor = '???';
			$room = array();
			}
		else {
			$room = $db->Record;
			$possessor = $factions[$owner]['title'];
		echo "
	<details>
		<summary>Quest location</summary>
		<ul>
			<li>Nation: Bordermark in Belerion</li>
			<li>Place: {$places[$room['place']]}</li>
			<li>Building: {$buildings[$room['building']]}</li>
			<li>Room chosen: {$room['title']}</li>
			<li>Item hidden in: {$hidingPlace}</li>
			<li>Item in possession of: $possessor</li>
			<li>Room id: {$room['masterId']}</li>
		</ul>
	</details>";
			}
			
		echo "
	<details>
		<summary>Quest object</summary>
		<ul>
			<li>Item minimum level: {$level['questObjectRarityMin']}</li>
			<li>Item maximum level: {$level['questObjectRarityMax']}</li>
			<li>Item chosen level: {$questObjectLevel}</li>
			<li>Item chosen: {$item['title']}</li>
			<li>Item in possession of: $possessor</li>
			<li>Item hidden in: {$hidingPlace}</li>
		</ul>
	</details>";

		$basicFame = $player[strtolower($npc['organization']).'_fame'];
		$adjFame = 0;
		foreach (array_keys($factions) as $f) {
			$fama = $player[$f.'_fame'];
			$stima = $chosenOrganization['relation_'.$f];
			$adjustment = $fama * ($stima / 3);
			$expl[$f] = "Player's fame  with $f is $fama; questgiver's relation to $f is $stima. Adjustment: $adjustment";
			$adjFame += $adjustment;
			}
		$weight = $player['level'] / 3;
		$weightedFame = $adjFame * $weight;
		$judgement = $basicFame + $weightedFame;
		if ($judgement < 0) {
			$final = 'NPC refuses to give quest to player, whom is felt to be an enemy of ' . $npc['organization'];
			$abort[] = "NPC refuses to give quest to player, whom is felt to be an enemy";
			}
		else
			$final = "NPC gives quest to player, who's compatible with " . $npc['organization'];

		echo "
	<details>
		<summary>Fame</summary>
		<ul>
			<li>Player basic fame with faction {$npc['organization']}: $basicFame</li>
			<li style='color: grey'>".
			implode("</li><li style='color: grey'>", $expl) .
			"
			</li>
			<li>Player adjusted fame with faction {$npc['organization']}: $adjFame</li>
			<li>Player's weighted adjustment: $weightedFame</li>
			<li>Player's complexive numeric score: $judgement</li>
			<li>Final judgement: $final</li>
		</ul>
	</details>";
		
		}
	
			if (1001 == $linguaPrescelta) {
				$prompt = "Aiutami a scrivere un dialogo per un gioco di ruolo fantasy come Dungeons and Dragons. Prendi questa idea di base, espandila, arricchiscila scrivendo la proposizione di un npc, {$npc['title']}, al protagomista, di circa 50 parole che proponga l'avventura in modo nuovo ed eccitante." . PHP_EOL .
				"Ti chiede di recuperare " . $item['title'];
				$extra = "L'oggetto appartiene a $possessor e probabilmente nascosto in {$hidingPlaces[$hidingPlace]} all'interno di ".$buildings[$room['building']];
				}
			else {
				$prompt = "Help me write an adventure for a fantasy role-playing game like Dungeons and Dragons. Take this basic idea, expand it, enrich it by writing a text of around 500 words that proposes the adventure in a new and exciting way." . PHP_EOL .
				"You are approched by " .
				$npc['title'] . " and asked to retrieve " . $item['title'];
				$extra = "The item is held by $possessor and is likely hidden in {$hidingPlaces[$hidingPlace]} in a room inside ".$buildings[$room['building']];
				}
			
			$db->query ("INSERT INTO quest (creation, lastMod, visible, title, authorId, modifierId, language, npc, quest_type, object, building, hiding_place, ia) VALUES (NOW(), NOW(), 'Y', 'Prova', ?, 'QuestSystem 0.6', ?, ?, ?, ?, ?, ?, ?)",
			array($id, $linguaPrescelta, $questgiverId, $questType, $item['masterId'], $room['masterId'], $hidingPlace, "<p>$prompt</p>"),
			'iiisiiss');
			
			echo "
				<article>
					<p><strong>$prompt</strong></p>
					<p>$extra</p>
				</article>
			";

		}
	}

?>
			</section>

<?php
if ($abort) echo "
		<section class='warning'><mark>🛑 Warning</mark> ".
		implode('<br>', $abort) .
		"
		</section>";
?>
		</div>
	</main>
</body>
</html>
