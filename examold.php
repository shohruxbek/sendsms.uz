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
        <?php include 'include-css.php';?>
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
                                
                                <div class="x_content">
									
									<div id="toolbar">
										<select id='export_select' class="form-control" >
											<option value="basic">Export This Page</option>
											<option value="all">Export All</option>
											<option value="selected">Export Selected</option>
										</select>
									</div>
									<table class='table-striped' id='questions'
                                        data-toggle="table"
                                        data-url="get-list.php?table=##"
                                        data-click-to-select="true"
                                        data-side-pagination="server"
                                        data-pagination="true"
                                        data-page-list="[5, 10, 20, 50, 100, 200]"
                                        data-search="true" data-show-columns="true"
                                        data-show-refresh="true" data-trim-on-search="false"
										data-sort-name="id" data-sort-order="desc"
										data-mobile-responsive="true"
										data-toolbar="#toolbar" data-show-export="true"
										data-maintain-selected="true"
										data-export-types='["txt","excel"]'
										data-export-options='{
											"fileName": "word-list-<?=date('d-m-y')?>",
											"ignoreColumn": ["state"]	
										}'
										data-query-params="queryParams_1"
										>
										<thead>
											<tr>
                                                <th data-field="state" data-checkbox="true"></th>
                                                <th data-field="id" data-sortable="true">ID</th>
                                                <th data-field="image" data-sortable="true">Nomer</th>
                                                <th data-field="question" data-sortable="true">Status</th>
                                                <th data-field="optiona" data-sortable="true">Protsess</th>
                                            </tr>
                                        </thead>
                                    </table>
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
		<script>
		$('#category').on('change',function(e){
			var category_id = $('#category').val();
			$.ajax({
				type:'POST',
				url: "db_operations.php",
				data:'get_subcategories_of_category=1&category_id='+category_id,
				beforeSend:function(){$('#subcategory').html('Please wait..');},
				success:function(result){
					// alert(result);
					$('#subcategory').html(result);
				}
			});
		});
		</script>
		<script>
		$('#edit_category').on('change',function(e, row_category, row_subcategroy){
			var category_id = $('#edit_category').val();
			$.ajax({
				type:'POST',
				url: "db_operations.php",
				data:'get_subcategories_of_category=1&category_id='+category_id,
				beforeSend:function(){$('#edit_subcategory').html('Please wait..');},
				success:function(result){
					// alert(result);
					$('#edit_subcategory').html(result);
					if(category_id == row_category && row_subcategroy != 0 )
						$('#edit_subcategory').val(row_subcategroy);
				}
			});
		});
		</script>
		<script>
		$('#filter_category').on('change',function(e){
			var category_id = $('#filter_category').val();
			$.ajax({
				type:'POST',
				url: "db_operations.php",
				data:'get_subcategories_of_category=1&category_id='+category_id,
				beforeSend:function(){$('#filter_subcategory').html('<option>Please wait..</option>');},
				success:function(result){
					// alert(result);
					$('#filter_subcategory').html(result);
				}
			});
		});
		</script>
		<script>
		$('#filter_btn').on('click',function(e){
			$('#questions').bootstrapTable('refresh');
		});
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
							$('#submit_btn').html('Create Now');
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