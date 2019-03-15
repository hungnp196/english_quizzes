<div class="row">
	<table class="form-table" id="dynamic_field">
		<tr>
			<td><label>Answer</label></td>
			<td><label>Correct Answer</label></td>
			<td><button type="button" class="button button-primary" id="add_new_answer">Add new answer</button></td>
		</tr>
		<?php
		if(empty($meta_elements)){ ?>
			<tr class="new-field">
				<td><input type="text" class="form-control" name="answer[]" class="form-control answer"></td>
				<td>
					<input type="checkbox" class="form-control correct-answer">
					<input type="hidden" name="checked_id[]" value="false" class="hidden-input" value="false">
				</td>
				<td class="td-btn"></td>
			</tr>
		<?php } 
		else{
			for($i = 0; $i < count($meta_elements); $i++){
				$checked = '';
				if($meta_elements[$i]['correct_answer'] == 'true'){
					$checked = 'checked';
				}
				echo '<tr class="new-field">';
				echo '<td><input type="text" class="form-control" name="answer[]" class="form-control" value="'.$meta_elements[$i]['answer'].'"></td>';
				echo '<td><input type="checkbox" class="form-control correct-answer" '.$checked.'><input type="hidden" name="checked_id[]" class="hidden-input" value="'.$meta_elements[$i]['correct_answer'].'"></td>';
				echo '<td class="td-btn"></td>';	
				echo '</tr>';
			}
		}
		?>
	</table>
</div>

<script type="text/javascript">
	jQuery('#add_new_answer').on("click", function(){
		var new_answer = '';
		new_answer += '<tr class="new-field">';
		new_answer += '<td>';
		new_answer += '<input type="text" class="form-control" name="answer[]" class="form-control">';
		new_answer += '</td>';
		new_answer += '<td>';
		new_answer += '<input type="checkbox"  class="form-control correct-answer" value="false">';
		new_answer += '<input type="hidden" name="checked_id[]" value="false" class="hidden-input">';
		new_answer += '</td>';
		new_answer += '<td class="td-btn"><button type="button" class="button button-primary btn-remove-field" >Remove</button>';
		new_answer += '</td>';
		new_answer += '</tr>';
		jQuery('#dynamic_field').append(new_answer);
		jQuery('.td-btn').html('');
		if(jQuery('.td-btn').length > 1){
			jQuery('.td-btn').append('<button type="button" class="button button-primary btn-remove-field" >Remove</button>');
		}
	})
	jQuery(document).on("click", '.btn-remove-field', function(){
		jQuery(this).closest('.new-field').remove();
	})
	jQuery('#dynamic_field').on("click", '.correct-answer', function(){
		if (jQuery(this).attr("checked") == "checked") {
			jQuery(this).parent().children()[1].setAttribute('value', true);
		}
		else{
			jQuery(this).parent().children()[1].setAttribute('value', false);
		}
	})
	jQuery(document).on("click", '.btn-remove-field', function(){
		if(jQuery('.new-field').length == 1){
			jQuery('.td-btn').html('');
		}
		jQuery(this).closest('.new-field').remove();
	})
	jQuery(document).ready(function(){
		jQuery('.td-btn').append('<button type="button" class="button button-primary btn-remove-field" >Remove</button>');
	})
	jQuery(document).on("click", '#publish', function(){
		if(jQuery('.correct-answer:checked').length > 0){
			return true;
		}
		else{
			alert("You have to choose the correct answer");
			return false;
		}
	})
</script>
