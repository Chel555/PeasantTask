<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Крестьянская задача</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>

<div class="container">

	
	
	
	<div class="card" style="padding: 2%">
	
    <h2>Крестьянская задача</h2>
	<h4>Крестьянину нужно перевезти через реку волка, козу и капусту. Но в лодке может поместиться только крестьянин, а с ним или один волк, или одна коза, или только капуста. Но если оставить волка с козой, то волк съест козу, а если оставить козу с капустой, то коза съест капусту. Как перевез свой груз крестьянин?
	</h4>

	<div class="card" style="padding: 2%">
		<div class="row">
			<div class="col">
				<button type="button" class="btn btn-primary " id="btn_add_crossing">Добавить переправу</button>
				<button type="button" class="btn btn-danger " style="display: none" id="btn_del_crossing">Удалить последнюю переправу</button>
			</div>
			<div class="col">
				<h4><i>Добавлено переправ: <span id="count_crossing">0</span> / 10</i></h4>
			</div>
		</div>
		
		
	</div>

	<form id="form_crossing">
	<!--<form id="form_crossing" action="server.php" method="post">-->

	<?php
		$max_crossings = 10;
		for ($i = 0; $i < $max_crossings; $i++) {
			?>
				<div class="card" style="padding: 2%; display: none; margin-top: 2%" id="crossing_<?= ($i+1) ?>">
					<div class="row">
						<div class="col">
							<h4>Переправа <?= ($i+1) ?> (Берег 1  <?php echo $direct = ($i % 2 == 0) ? "-->>>" : "<<<--"; ?>  Берег 2)</h4>
							Выберите груз для крестьянина на лодку:
							<select class="form-select" type="select" name="cargo_<?= ($i+1) ?>" aria-label="Default select example">
							  <option value="1">Ничего</option>
							  <option value="2">Волк</option>
							  <option value="3">Коза</option>
							  <option value="4">Капуста</option>
							</select>
						</div>
						<div class="col">
							<div class="row" id="block_result_<?= ($i+1) ?>" style="display: none">
								<div class="col">
									<h4>Берег 1</h4>
									<ul id="coast1_<?= ($i+1) ?>">
									</ul>
								</div>
								<div class="col">
									<h4><?php echo $direct = ($i % 2 == 0) ? "Лодка -->>>" : "<<<-- Лодка"; ?></h4>
									<ul id="boat_<?= ($i+1) ?>">
									</ul>									
								</div>	
								<div class="col">
									<h4>Берег 2</h4>
									<ul id="coast2_<?= ($i+1) ?>">
									</ul>									
								</div>									
							</div>							
							<div class="alert" id="report_<?= ($i+1) ?>" style="display: none" role="alert">
							  
							</div>							
						</div>
					</div>
				</div>			
			<?php
			
		}
	?>
		<input type="number" value="" name="count_crossings" id="id_count_crossing" style="display: none">
		<br>
		<div class="card" style="padding: 2%; display: none" id="block_send">
			<div class="row">
				<div class="col">
					<div class="alert" id="main_report" style="display: none" role="alert">
					  
					</div>
					<div class="alert" id="total_report" style="display: none" role="alert">
					  
					</div>						
					<button type="submit" class="btn btn-success " id="btn_check_solution">Проверить решение</button>
					<div class="alert alert-primary" id="please_wait" style="display: none" role="alert">
					  Пожалуйста, подождите...
					</div>					
				</div>
			</div>	
		</div>		
	
	</form>

	<p><i>Интерактивная задача сделана Михаилом Кочитовым (псевдоним: Chel555) для inside360.</i></p>
		
	</div>
	
	
</div>	
		
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>	
	
	<script>
		var max_crossings = 10;
		var current_crossings = 0;
		
		$( "#btn_add_crossing" ).click(function() {
			current_crossings++;
			$( "#crossing_"+current_crossings ).show();		  
			check_buttons();
		});		
		
		$( "#btn_del_crossing" ).click(function() {
			$( "#crossing_"+current_crossings ).hide();
			current_crossings--;
			check_buttons();
		});

		$("#form_crossing").on("submit", function(){
			// return;
			$('#btn_check_solution').hide();
			$('#please_wait').show();
			
			$.ajax({
				url: '/server.php',
				method: 'post',
				dataType: 'html',
				data: $(this).serialize(),
				success: function(data){
					var result = JSON.parse(data);
					
					for (var i = 0; i < 10; i++) {
						$('#coast1_'+(i+1)).text("");
						$('#coast2_'+(i+1)).text("");
						$('#boat_'+(i+1)).text("");	
						$('#report_'+(i+1)).removeClass('alert-success');
						$('#report_'+(i+1)).removeClass('alert-danger');
						$('#report_'+(i+1)).hide();	
						$('#block_result_'+(i+1)).hide();	
					}
					
					$('#main_report').removeClass('alert-success');
					$('#main_report').removeClass('alert-danger');
					$('#main_report').hide();
					
					$('#total_report').removeClass('alert-success');
					$('#total_report').removeClass('alert-danger');
					$('#total_report').hide();					
					
					for (var i = 0; i < current_crossings; i++) {

						$('#coast1_'+(i+1)).append(result['coast_1'][i]);
						$('#coast2_'+(i+1)).append(result['coast_2'][i]);
						$('#boat_'+(i+1)).append(result['boat'][i]);
						
						if (result['error_id'][i] != -1) {
							if (result['error_id'][i] == 0) {
								$('#report_'+(i+1)).addClass('alert-success');
							} else {
								$('#report_'+(i+1)).addClass('alert-danger');
							}
							$('#report_'+(i+1)).text(result['error'][i]);
							$('#report_'+(i+1)).show();							
						}
						$('#block_result_'+(i+1)).show();						
					}
					
					if (result['main_report'] != "") {
						$('#main_report').addClass('alert-danger');
						$('#main_report').text(result['main_report']);
						$('#main_report').show();						
					}

					
					if (result['completed'] == true) {
						$('#total_report').addClass('alert-success');
					} else {
						$('#total_report').addClass('alert-danger');
					}
					$('#total_report').text(result['total_report']);
					$('#total_report').show();
					
					$('#btn_check_solution').show();
					$('#please_wait').hide();					
					
				}
			});
			return false;
		});	
		
		function check_buttons() {
			$("#id_count_crossing").val(current_crossings);	
		  if (current_crossings >= 1) $( "#btn_del_crossing" ).show();
		  else $( "#btn_del_crossing" ).hide();
		  
		  if (current_crossings == max_crossings) $( "#btn_add_crossing" ).hide();
		  else $( "#btn_add_crossing" ).show();

		  if (current_crossings == 0) $( "#block_send" ).hide();
		  else $( "#block_send" ).show();	  
		  
		  $( "#count_crossing" ).text(current_crossings);			
		}

		  function ready() {
			// alert('DOM готов');
		  }

		  document.addEventListener("DOMContentLoaded", ready);	
	</script>
  </body>
</html>