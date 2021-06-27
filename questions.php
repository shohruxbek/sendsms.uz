<?php
session_start();
if(!isset($_SESSION['id']) && !isset($_SESSION['username']))
	header("location:index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Questions for Quiz | <?=ucwords($_SESSION['company_name'])?> - Admin Panel </title>
	<?php include 'include-css.php'; ?>
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php include 'sidebar.php';?>
			<!-- page content -->
			<div class="right_col" role="main">
				<!-- top tiles -->
				<br />
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Juda ko'p sms yuborish</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<div class="row">
									<form id="register_form" method="POST" action="add.php" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="novalidate">
										

										<div class="form-group">
											<label class="control-label col-md-2 col-sm-3 col-xs-12" for="textareas">Xabar matni</label>
											<div class="col-md-10 col-sm-6 col-xs-12">
												<textarea id="textareas" name="textareas" class="form-control col-md-7 col-xs-12" maxlength="140" rows="2" style="width: 100%" required></textarea>
												<div id="count">
													<span id="current_count">0</span>
													<span id="maximum_count">/ 140</span>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-3 col-xs-12" for="numbers" >Yuborishga raqamlar</label>
											<div class="col-md-10 col-sm-6 col-xs-12">
												<textarea id="numbers" class="form-control col-md-7 col-xs-12" name="numbers" onKeyUp="countLines(this)"  rows="10" required></textarea>
											</div>

										</div>
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-3 col-xs-12" for="cou" >Raqamlar soni</label>
											<div class="col-md-10 col-sm-6 col-xs-12">
												<input type=text name="lineCount" id="cou" value="0" class="form-control col-md-4 col-sm-6 col-xs-12" readonly>
											</div>

										</div>

										

										
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-3 col-xs-12" for="speed">Sms yuborish tezligi</label>
											<div class="col-md-4 col-sm-6 col-xs-12">
												<select name='speed' id='speed' class='form-control' required>
													<option value=''>Tezlikni tanlang</option>
													
													<option value='10'>10</option>
													<option value='20'>20</option>
													<option value='30'>30</option>

												</select>
											</div>
										</div>


										<div class="ln_solid"></div>
										<div class="form-group">
											<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">
												<button type="submit" id="submit_btn" name="submit" class="btn btn-success">Yaratish</button>
											</div>
										</div>
										<div class="row">
											<div  class="col-md-offset-3 col-md-4" style ="display:none;" id="result">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /page content -->

		<!-- footer content -->
		<?php include 'footer.php';?>
		<!-- /footer content -->
	</div>
</div>
<!-- jQuery -->
<script type="text/javascript">
	$('#textareas').keyup(function() {    
		var characterCount = $(this).val().length,
		current_count = $('#current_count'),
		maximum_count = $('#maximum_count'),
		count = $('#count');    
		current_count.text(characterCount);        
	});
</script>
<!-- count numbers -->
<script>
	function countLines(theArea){
		var theLines = theArea.value.replace((new RegExp(".{"+theArea.cols+"}","g")),"\n").split("\n");
		if(theLines[theLines.length-1]=="") theLines.length--;
		theArea.form.lineCount.value = theLines.length+" ta";
	}
</script>

<script>
	$('#register_form').validate({
		rules:{
			question:"required",
			category:"required",
			a:"required",
			b:"required",
			c:"required",
			d:"required",
			level:"required",
			answer:"required",
		}
	});
</script>
<script>
	$('#register_form').on('submit',function(e){
		e.preventDefault();
		var formData = new FormData(this);
		if($("#register_form").validate().form()){
			var category = $('#category').val();
			var subcategory = $('#subcategory').val();
			$.ajax({
				type:'POST',
				url: $(this).attr('action'),
				data:formData,
				beforeSend:function(){$('#submit_btn').html('Please wait..');$('#submit_btn').prop('disabled', true);},
				cache:false,
				contentType: false,
				processData: false,
				success:function(result){
					$('#submit_btn').html('Yaratish');
					$('#result').html(result);
					$('#result').show().delay(8000).fadeOut();
					$('#register_form')[0].reset();
					$('#category').val(category);
					$('#subcategory').val(subcategory);
					$('#submit_btn').prop('disabled', false);
					$('#questions').bootstrapTable('refresh');
				}
			});
		}
	}); 
</script>
<script>
	var $table = $('#questions');
	$('#toolbar').find('select').change(function () {
		$table.bootstrapTable('refreshOptions', {
			exportDataType: $(this).val()
		});
	});
</script>
<script>
	function queryParams_1(p){
		return {
			"category": $('#filter_category').val(),
			"subcategory": $('#filter_subcategory').val(),
			limit:p.limit,
			sort:p.sort,
			order:p.order,
			offset:p.offset,
			search:p.search
		};
	}
</script>
<script>
	window.actionEvents = {
		'click .edit-question': function (e, value, row, index) {
            		// alert('You click remove icon, row: ' + JSON.stringify(row));
            		if(row.image != 'No image'){
            			var regex = /<img.*?src="(.*?)"/;
            			var src = regex.exec(row.image)[1];
            			$('#image_url').val(src);
            		}else{
            			$('#image_url').val('');
            		}
            		$('#question_id').val(row.id);
            		$('#edit_question').val(row.question);
            		$('#edit_category').val(row.category).trigger("change",[row.category,row.subcategory]);
            		$('#edit_a').val(row.optiona);
            		$('#edit_b').val(row.optionb);
            		$('#edit_c').val(row.optionc);
            		$('#edit_d').val(row.optiond);
            		$('#edit_answer').val(row.answer);
            		$('#edit_level').val(row.level);
            		$('#edit_note').val(row.note);
            		$('#edit_subcategory').val(row.subcategory);
            	}
            };
        </script>
        <script>
        	$('#update_form').validate({
        		rules:{
        			edit_question:"required",
        			update_quiz_id:"required",
        			update_a:"required",
        			update_b:"required",
        			update_c:"required",
        			update_d:"required",
        			update_answer:"required",
        		}
        	});
        </script>
        <script>
        	$('#update_form').on('submit',function(e){
        		e.preventDefault();
        		var formData = new FormData(this);
        		if($("#update_form").validate().form()){
        			$.ajax({
        				type:'POST',
        				url: $(this).attr('action'),
        				data:formData,
        				beforeSend:function(){$('#update_btn').html('Please wait..');},
        				cache:false,
        				contentType: false,
        				processData: false,
        				success:function(result){
        					$('#update_result').html(result);
        					$('#update_result').show().delay(8000).fadeOut();
        					$('#update_btn').html('Update Question');
        					$('#edit_image').val('');
        					$('#questions').bootstrapTable('refresh');
        					setTimeout(function() {$('#editQuestionModal').modal('hide');}, 3000);
        				}
        			});
        		}
        	}); 
        </script>



        <script>
        	$(document).on('click','.delete-question',function(){
        		if(confirm('Are you sure? Want to delete question')){
        			id = $(this).data("id");
        			image = $(this).data("image");
        			$.ajax({
        				url : 'db_operations.php',
        				type: "get",
        				data: 'id='+id+'&image='+image+'&delete_question=1',
        				success: function(result){
        					if(result==1){
        						$('#questions').bootstrapTable('refresh');
        					}else
        					alert('Error! Question could not be deleted');
        				}
        			});
        		}
        	});
        </script>
    </body>
    </html>