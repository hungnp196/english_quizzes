<?php
if(isset($_POST['submit'])){
	if(isset($_POST['user_answer']) && $_POST['user_answer'] != ''){
		for($i = 0; $i < count($_POST['user_answer']); $i++){
			$a = explode('@', $_POST['user_answer'][$i]);
			for($j = 0; $j < count($index_of_correct_answer); $j++){
				if($a[1] == $meta_elements[$index_of_correct_answer[$j]]['answer']){
					$correct_count++;
				}
			}
		}
		/*$_POST['user_answer'][0] == $meta_elements[$index_of_correct_answer]['answer']*/
		if($correct_count ==  count($index_of_correct_answer) && count($_POST['user_answer']) == count($index_of_correct_answer)){
			$_SESSION['number_of_correct_answer'] = $_SESSION['number_of_correct_answer'] + 1;
			$result = '<h3 style="color:green;">Correct</h3>';?>
			<div class="wrap">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<h3>English Quizzes</h3>
						<h5><?php print_r($posts_array[$_SESSION['current_question']]->post_content);
					?></h5>
						<form method="post">
							<table>
								<?php
								for($i = 0; $i < count($meta_elements); $i++) {
									echo '<tr>';
									echo '<th>'.chr($key_alphabet+$i).PHP_EOL.': '.$meta_elements[$i]['answer'].'</th>';
									/*echo '<th>'.$meta_elements[$i]['answer'].'</th>';
									echo '</tr>';*/
								}
								?>
							</table>
							<button type="submit" name="next_anwser" class="button button-primary">Next Question</button>
						</form>
						<div class="result">
							<?=$result;?>
							<p>
								Your answer: 
								<?php
									for($k = 0; $k < count($_POST['user_answer']); $k++){
										/*echo '<label>'.chr($key_alphabet+$index_of_correct_answer[$k]).PHP_EOL.':'.$_POST['user_answer'][$k].'</lable>';*/
										$a = explode('@', $_POST['user_answer'][$k]);
										echo '<p>'.$a[0].': '.$a[1].'</p>';
									} 
								?>
							</p>
							<p>
								Explain:
								<?php print_r($meta_explain);?>
							</p>
						</div>
					</main>
				</div>
			</div>
		<?php die(); }
		else{ 
			$result = '<h3 style="color:red;">Wrong</h3>';?>
			<div class="wrap">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<h3>English Quizzes</h3>
						<h5><?php echo $posts_array[$_SESSION['current_question']]->post_content; ?></h5>
						<form method="post">
							<table>
								<?php
								for($i = 0; $i < count($meta_elements); $i++) {
									echo '<tr>';
									echo '<th>'.chr($key_alphabet+$i).PHP_EOL.': '.$meta_elements[$i]['answer'].'</th>';
									/*echo '<th>'.$meta_elements[$i]['answer'].'</th>';
									echo '</tr>';*/
								}
								?>
							</table>
							<button type="submit" name="next_anwser" class="button button-primary">Next Question</button>
						</form>
						<div class="result">
							<?=$result;?>
							<p>
								Correct answer:
								<?php
								for($p = 0; $p < count($index_of_correct_answer); $p++){
									echo '<p>'.chr($key_alphabet+$index_of_correct_answer[$p]).PHP_EOL.': '.$meta_elements[$index_of_correct_answer[$p]]['answer'].'</p>';
									/*echo '<p>'.$meta_elements[$index_of_correct_answer[$p]]['answer'].'</p>';*/
								} 
								?>
							</p>
							<p>
								Your answer: 
								<?php
								for($k = 0; $k < count($_POST['user_answer']); $k++){
									$a = explode('@', $_POST['user_answer'][$k]);
									echo '<p>'.$a[0].': '.$a[1].'</p>';
								} 
								?>
							</p>
							<p>
								Explain:
								<?php print_r($meta_explain);?>
							</p>
						</div>
					</main>
				</div>
			</div>
		<?php die(); }
	}
	else{
		echo '<label style="color: red;">You have to choose the answer</label>';
	}
}
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<h3>English Quizzes <?php $curent = $_SESSION['current_question']; echo $curent+ 1; ?>/<?=$count; ?></h3>
			<h5><?php echo $posts_array[$_SESSION['current_question']]->post_content; ?></h5>
			<form method="post">
				<table>
					<?php
					for($i = 0; $i < count($meta_elements); $i++) {
						echo '<tr>';
						echo '<th>'.chr($key_alphabet+$i).PHP_EOL.': '.$meta_elements[$i]['answer'].'</th>';
						/*echo '<th>'.$meta_elements[$i]['answer'].'</th>';*/
						echo '<td><input type="checkbox" name="user_answer[]" value="'.chr($key_alphabet+$i).PHP_EOL.'@'.$meta_elements[$i]['answer'].'"></td>';
						echo '</tr>';
					}
					?>
				</table>
				<button type="submit" name="submit" class="button button-primary">Submit</button>
			</form>
		</main>
	</div>
</div>