<!doctype html>
<html>
<head>
	<title>HTTP Jeoparody</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script>
		$(document).ready(function() {
			// Handle Daily Doubles
			$('.game .tile.dd').one('click', function(event) {
				var tile = $(this);
				event.stopImmediatePropagation();
				$('.daily-double').show();
				$('.daily-double .wager-confirm').mousedown(function() {$(this).css('text-shadow', 'none')});
				$('.daily-double .wager-confirm').click(function() {
					$('.daily-double').hide();
					tile.data('points', parseInt($('.daily-double .wager-value').text()));
					tile.click();
				});
			});
			// Click on a tile from the game board.
			$('.game .tile').one('click', function() {
				var points = parseInt($(this).data('points'));
				var answer = $(this).data('answer');
				// Show the question.
				$('.question').show();
				$('.award, .show-answer').show();
				// Setup question text
				$('.question .text').text($(this).data('question'));
				// Create buttons for score keeping.
				$('.award').click('click', function() {
					var score = $('.' + $(this).data('player') + '-score');
					var pointsAwarded = points;
					var isWrong = $(this).hasClass('wrong');
					if (isWrong) {
						pointsAwarded *= -1;
						$('.award').removeClass('wrong');
					}
					score.data('score', score.data('score') + pointsAwarded);
					score.text((score.data('score') < 0 ? '-' : '') + '$' + Math.abs(score.data('score')));
					if (!isWrong) {
						$('.show-answer').click();
					}
				});
				// Create button for when no one gets it
				$('.show-answer').one('click', function() {
					$('.award, .show-answer').off('click').hide();
					$('.question .text').text(answer)
						.one('click', function() {
							$('.question').hide();
						});
				});
				// Remove tile from game board.
				$(this).removeClass('tile').removeClass('dd').text('');
			});

			// Syncs the editable names on the score board with the question screen.
			$('.player').blur(function() {
				$('.' + $(this).data('player') + '-name').text($(this).text());
			});

			// Track shift button usage.
			$(document).keydown(function (event) {
				if (event.shiftKey) $('.question .award').addClass('wrong');
			});
			$(document).keyup(function (event) {
				$('.question .wrong').removeClass('wrong');
			});
		});
	</script>
</head>
<body>
	<!-- All the PHP code is in here -->
	<table class="game">
		<thead class="content-name">
			<tr>
				<?php
					foreach (array_keys($game['categories']) as $category) {
						echo "<th>$category</th>";
					}
				?>
			</tr>
		</thead>
		<tbody class="content-money">
		<?php
			$dailyDouble = rand(3, 4) . rand(0, 4);

			for ($row = 0; $row < 5; $row++) {
				$points = ($row + 1) * $game['points'];

				echo "<tr>";
				foreach (array_values($game['categories'])[0] as $answer => $question) {
//					$class = ($dailyDouble === $row.$col) ? 'tiled dd' : 'tile';

					echo sprintf('<td class="%s" data-points="%s" data-question="%s" data-answer="%s">$%s</td>',
						'tile',
						$points,
						htmlentities($question, ENT_QUOTES, 'UTF-8'),
						htmlentities($answer, ENT_QUOTES, 'UTF-8'),
						$points);
				}
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
	<!-- End PHP code. -->
	<table class="question">
		<tr>
			<td class="text content-text" colspan="7">QUESTION</td>
		</tr>
		<tr class="content-name">
			<td class="award p1-name" colspan="1" data-player="p1" >Player 1</td>
			<td class="award p2-name" colspan="1" data-player="p2" >Player 2</td>
			<td class="award p3-name" colspan="1" data-player="p3" >Player 3</td>
			<td class="award p4-name" colspan="1" data-player="p4" >Player 4</td>
			<td class="award p5-name" colspan="1" data-player="p5" >Player 5</td>
			<td class="award p6-name" colspan="1" data-player="p6" >Player 6</td>
			<td class="show-answer" colspan="1">&#9760;</td>
		</tr>
	</table>
	<table class="scores">
		<tr class="content-name">
			<th><span class="player" data-player="p1" contenteditable="true">Player 1</span></th>
			<th><span class="player" data-player="p2" contenteditable="true">Player 2</span></th>
			<th><span class="player" data-player="p3" contenteditable="true">Player 3</span></th>
		</tr>
		<tr class="content-money">
			<td class="p1-score" data-score="0">$0</td>
			<td class="p2-score" data-score="0">$0</td>
			<td class="p3-score" data-score="0">$0</td>
		</tr>

		<tr class="content-name">
			<th><span class="player" data-player="p4" contenteditable="true">Player 4</span></th>
			<th><span class="player" data-player="p5" contenteditable="true">Player 5</span></th>
			<th><span class="player" data-player="p6" contenteditable="true">Player 6</span></th>
		</tr>
		<tr class="content-money">
			<td class="p4-score" data-score="0">$0</td>
			<td class="p5-score" data-score="0">$0</td>
			<td class="p6-score" data-score="0">$0</td>
		</tr>
	</table>
	<table class="daily-double">
		<tr>
			<td class="title content-title">Daily Double</td>
		</tr>
		<tr>
			<td class="wager content-money">
				$<span class="wager-value" contenteditable="true">0</span>&ensp;<span class="wager-confirm">&#x2713;</span>
			</td>
		</tr>
	</table>
</body>
</html>
