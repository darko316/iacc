<?php
# precheck form ID
function form_check($id=false, $required=false) {
	global $table;
	if($id) $_GET['id'] = $id;
	if($required && !$id) {
		header('Location: '.substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php');
	} else {
		if(is_numeric($_GET['id'])) {
			$sql = 'SELECT * FROM '.$table['name'].' WHERE '.$table['id'].' = :id';
			$stmt = cnn()->prepare($sql);
			$stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
			if($data = $stmt->fetch()) {
				# item found
				return $data;
			} else {
				# item not found, proceed...
				switch($_SERVER['REQUEST_METHOD']) {
					case 'POST':
						echo false;
					break;
					case 'GET': default:
						header('Location: '.substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php');
					break;
				}
				die();
			}
		}
	}
}

# save form data
function form_save($callback_before=false, $callback_after=false) {
	global $id, $table, $fields;	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		# precheck all variables
		foreach($fields as $k=>$f) {
			if($f[0]) {
				# required				
				if(!$log_caption) {					
					# get first value for log					
					$log_caption = $_POST[$k];
				}				
				if(!$_POST[$k]) {					
					$error = true;
				}
			}
			if($f[1]) {
				# filter
				if($_POST[$k]) {
					switch($f[1]) {
						case 'idcheck':
							if(!filter_var($_POST[$k], FILTER_VALIDATE_INT)) {
								$error = true;
							}
						break;
						case 'number':
							if(!filter_var($_POST[$k], FILTER_VALIDATE_FLOAT)) {
								$error = true;
							}
						break;
						case 'ssn':
							if(!preg_match('/^\d{10}$/', $_POST[$k])) {
								$error = true;
							}
						break;
						case 'checkbox':
							if(!preg_match('/^\d+$/', $_POST[$k])) {
								$error = true;
							}
						break;
						case 'date':
							$date = date_parse($_POST[$k]);
							if(!checkdate($date['month'], $date['day'], $date['year'])) {
								$error = true;
							}
						break;
						case 'datetime':
							$date = date_parse($_POST[$k]);
							if(!checkdate($date['month'], $date['day'], $date['year'])) {
								$error = true;
							} else {
								if(!mktime($data['hour'], $data['minute'], $data['second'])) {
									$error = true;
								}
							}
						break;
						case 'email':
							if(!filter_var($_POST[$k], FILTER_VALIDATE_EMAIL)) {
								$error = true;
							}
						break;
						case 'password':
							if(!preg_match('/^[0-9A-Za-z]+$/', $_POST[$k])) {
								$error = true;
							}
						break;
						case 'phone':
							if(!preg_match('/^[0-9A-Za-z-()+ ]{6,15}$/', $_POST[$k])) {
								$error = true;
							}
						break;
						case 'url':
							if(!filter_var($_POST[$k], FILTER_VALIDATE_URL)) {
								$error = true;
							} else {
								if($parts = parse_url($_POST[$k])) {
									if(!isset($parts['scheme'])) {
										$_POST[$k] = 'http://'.$_POST[$k];
									}
								}
							}
						break;
					}
				}
			}
			if($f[2]) {
				# minimum
				if($_POST[$k]) {
					if(strlen($_POST[$k]) < $f[2]) {
						$error = true;
					}
				}
			}
			if($f[3]) {
				# maximum
				if($_POST[$k]) {
					if(strlen($_POST[$k]) > $f[3]) {
						$error = true;
					}
				}
			}
			if($f[6]) {
				# equals to
				if($_POST[$k]) {
					if($_POST[$k] != $_POST[str_replace('#', '', $f[6])]) {
						$error = true;
					}
				}
			}
		}
		
		# check if there are validation errors
		if(!$error) {
			# optional callback function
			if($callback_before) $callback_before();

			# get all variables
			foreach($fields as $k=>$f) {
				# check for discard
				if(!$f[4]) {					
					$set_update[] = $k.' = :'.$k;
					$set_insert_into[] = $k;
					$set_insert_values[] = ':'.$k;
				}
			}

			# record
			try {
				if($id) {
					# update					
					$sql = 'UPDATE '.$table['name'].' SET '.implode(', ', $set_update).' WHERE '.$table['id'].' = :id';
					$stmt = cnn()->prepare($sql);
					$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				} else {
					# insert
					$sql = 'INSERT INTO '.$table['name'].' ('.implode(', ', $set_insert_into).') VALUES ('.implode(', ', $set_insert_values).')';
					$stmt = cnn()->prepare($sql);
				}

				# sweep through variables
				foreach($fields as $k=>$f) {
					# check for discard
					if(!$f[4]) {
						# check for checkboxes (they hold no data)
						if($f[1] == 'checkbox') {
							# control is checkbox
							if($_POST[$k]) {
								$stmt->bindValue(':'.$k, true, PDO::PARAM_BOOL);
							} else {
								$stmt->bindValue(':'.$k, false, PDO::PARAM_BOOL);
							}
						} else {
							# any other field
							if($_POST[$k]) {
								if($k == "clave"){$_POST[$k] = encriptar($_POST[$k],false);} // para encriptar la clave Jose Martinez
								
								# check for applied functions
								if($function = $f[5]) $_POST[$k] = $function($_POST[$k]);
								switch(true) {
									case is_int($_POST[$k]):
										$stmt->bindValue(':'.$k, $_POST[$k], PDO::PARAM_INT);
									break;
									case is_bool($_POST[$k]):
										$stmt->bindValue(':'.$k, $_POST[$k], PDO::PARAM_BOOL);
									break;
									case is_null($_POST[$k]):
										$stmt->bindValue(':'.$k, $_POST[$k], PDO::PARAM_NULL);
									break;
									default:
										$stmt->bindValue(':'.$k, $_POST[$k], PDO::PARAM_STR);
									break;
								}
							} else {
								$stmt->bindValue(':'.$k, NULL, PDO::PARAM_NULL);
							}
						}
					}
				}	
				$stmt->execute();
				
				# log current ID
				if(!$id) {
					# insert
					$id = cnn()->lastInsertId();
				}

				# optional callback function
				if($callback_after) $callback_after();

				# result
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					# ajax
					exit($id);
				} else {
					# plain post
					header('Location: '.substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php?id='.$id);
					die();
				}

			} catch(PDOException $e) {

				# database error
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					# ajax
					exit(m('error_db').' '.$e->getMessage());
				} else {
					# plain post
					exit(false);
				}

			}
		} else {

			# validation error
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				# ajax
				exit(m('error_validation'));
			} else {
				# plain post
				exit(false);
			}

		}
	}
}

//Para agregar los tipos de ayuda que se le asigna a la solicitud
function form_saveCheck($callback_before=false, $callback_after=false) {
	global $id, $table, $fields;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		# check if there are validation errors
		if(!$error) {
			# optional callback function
			if($callback_before) $callback_before();
			
			# Para elimnar todos los checks
			$sql2 = 'DELETE FROM tipo_ayuda_solicitud
				 WHERE solicitud = '.$_POST['solicitud'];			
			$stmt2 = cnn()->prepare($sql2);
			$stmt2->execute();
			# Para elimnar todos los checks
			
			

		
			# record
			try {
				$solicitud = $_POST['solicitud'];
				unset($_POST['solicitud']);
				foreach($_POST as $k=>$f) {
					if($_POST[$k] == 1){
						$sql = 'INSERT INTO '.$table['name'].' (id,tipo_ayuda,solicitud) VALUES ("",'.$k.','.$solicitud.')';
						$stmt = cnn()->prepare($sql);
						$stmt->execute();
					}
				}
					
				# log current ID
				if(!$id) {
					# insert
					$id = cnn()->lastInsertId();
				}

				# optional callback function
				if($callback_after) $callback_after();

				# result
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					# ajax
					exit($id);
				} else {
					# plain post
					header('Location: '.substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php?id='.$id);
					die();
				}

			} catch(PDOException $e) {

				# database error
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					# ajax
					exit(m('error_db').' '.$e->getMessage());
				} else {
					# plain post
					exit(false);
				}

			}
		} else {

			# validation error
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				# ajax
				exit(m('error_validation'));
			} else {
				# plain post
				exit(false);
			}

		}
	}
}



# form heading
function form_header($label=false, $add=true, $back=false) {
	global $id, $table;
	echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">';
	echo '<div class="panel-heading">';
	if($add) {
		echo '<a class="pull-right btn btn-xs btn-default" href="';
		if(!$back) {
			echo substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php';
			if($_GET['id']) echo '?id='.$_GET['id'];
		} else {
			echo 'javascript:history.go(-1)';
		}
		echo '">';
		echo '<i class="fa fa-mail-reply"></i> '.m('back').'</a>';
	}
	if($label) {
		echo $label;
	} else {
		echo ($id) ? m('record_edit').' (ID '.$id.')' : m('record_new');
	}
	echo '</div>';
	echo '<div class="panel-body"><div class="row"><div class="col-lg-12">';
	echo '<form role="form" id="form_'.$table['name'].'" name="form_'.$table['name'].'" method="POST">';
}
function form_header_simple($name, $inline=false, $method='POST') {
	echo '<div class="row"><div class="col-lg-12">';
	echo '<div class="panel-body"><div class="row"><div class="col-lg-12">';
	echo '<form role="form" id="form_'.$name.'" name="form_'.$name.'" method="'.$method.'"';
	if($inline) echo ' class="form-inline"';
	echo '>';
}

# form label
function form_label($id, $for=true) {
	global $fields;
	echo '<label class="control-label"';
	if($for) echo ' for="control_'.$id.'"';
	echo '>'.m($id);
	if($fields[$id][0]) echo ' <i class="fa fa-asterisk"></i>';
	echo '</label>';
}

# form label
function form_label2($id, $ast=true) {
	global $fields;
	echo '<label class="control-label"';
	//if($for) echo ' for="control_'.$id.'"';
	echo '>'.m($id);
	if($ast) echo ' <i class="fa fa-asterisk"></i>';
	echo '</label>';
}

# form input
function form_input($id, $value=false, $label=true, $size=false, $disabled=false) {
	global $fields;
	if($label) form_label($id);
	echo '<input type="';
	switch($fields[$id][1]) {
		case 'email':
			echo 'email';
		break;
		case 'password':
			echo 'password';
		break;
		case 'number':
			echo 'number';
		break;
		default:
			echo 'text';
		break;
	}
	echo '" class="form-control';
	switch($fields[$id][1]) {
		case 'number':
			echo ' text-right';
		break;
	}
	if($size) echo ' input-lg';
	echo '" id="control_'.$id.'" name="'.$id.'" value="'.$value.'" placeholder="'.m('enter').' '.mb_strtolower(m($id),'UTF-8').'"';
	if($fields[$id][3]) echo ' maxlength="'.$fields[$id][3].'"';
	if($fields[$id][0]) echo ' required';
	if($disabled) echo ' disabled';
	echo '>';

	# special user inputs
	switch($fields[$id][1]) {
		case 'number':
			echo '<script>$(document).ready(function(){$("#control_'.$id.'").numeric({allow:"."});});</script>';
		break;
	}
}

# form select
function form_select($id, $data, $value=false, $blank=false, $label=true, $size=false) {
	global $fields;
	if($label) form_label($id);
	echo '<select class="form-control';
	if($size) echo ' input-lg';
	echo '" id="control_'.$id.'" name="'.$id.'"';
	if($fields[$id][0]) echo ' required';
	echo '>';
	if($blank) {
		echo '<option value="">('.m('seleccionar').')</option>';
	}
	$data = array_map('array_values', $data);
	foreach($data as $item) {
		echo '<option value="'.$item[0].'"';
		if($item[2]) echo ' class="'.$item[2].'"';
		if($item[0] == $value) echo ' selected';
		echo '>'.$item[1].'</option>';
	}
	echo '</select>';
}

# form textarea
function form_textarea($id, $value=false, $label=true) {
	global $fields;
	if($label) form_label($id);
	echo '<textarea class="form-control" id="control_'.$id.'" name="'.$id.'" placeholder="'.m('enter').' '.mb_strtolower(m($id),'UTF-8').'"';
	if($fields[$id][0]) echo ' required';
	echo '>'.$value.'</textarea>';
}

# form checkbox
function form_checkbox($id, $value=false, $extra,$valorBD) {
	//echo '<label class="control-label">';
		echo '<input type="checkbox" id="control_'.$id.'" name="'.$id.'" value="'.$value.'"';
	if($valorBD == 1) echo ' checked';
	echo '> ';//.m($id).'</label>';
	if($extra) echo '<p class="form-control-static">'.m($id.'_help').'</p>';
}

# form radio
function form_radio($id, $value=false, $extra=false,$valorBD) {
	//echo '<label class="control-label">';
	//echo $value."==".$valorBD;
	echo '<input type="radio" id="control_'.$id.'" name="'.$id.'" value="'.$value.'"';
	
	if($value == $valorBD) echo ' checked';
	echo '> ';//.m($id).'</label>';
	if($extra) echo '<p class="form-control-static">'.m($id.'_help').'</p>';
}


# form hidden
function form_hidden($id, $value=false) {
	echo '<input type="hidden" id="control_'.$id.'" name="'.$id.'" value="'.$value.'">';
}

# form datepicker
function form_date($id, $value=false, $label=true, $limit_start=false, $limit_end=false, $type=false, $range=false) {
	global $fields, $config;
	if($label) form_label($id);
	echo '<input type="text" class="form-control" id="control_'.$id.'" name="'.$id.'" value="';
	if($value) {
		if(validate_date($value)) {
			echo $value;
		}
	}
	echo '"  placeholder="';
	switch($type) {
		case 'year':
			echo m('date_format_yyyy');
		break;
		case 'month':
			echo m('date_format_yyyy_mm');
		break;
		default:
			echo m('date_format_yyyy_mm_dd');
		break;
	}
	echo '">';
	echo '<script type="text/javascript">$("#control_'.$id.'").datepicker({';
		$prop[] = 'language: "'.$config['hl'].'"';
		$prop[] = 'autoclose: true';
		switch($type) {
			case 'year':
				$prop[] = 'format: "yyyy"';
				$prop[] = 'startView: 2';
				$prop[] = 'minViewMode: 2';
			break;
			case 'month':
				$prop[] = 'format: "yyyy-mm"';
				$prop[] = 'startView: 1';
				$prop[] = 'minViewMode: 1';
			break;
			default:
				$prop[] = 'format: "yyyy-mm-dd"';
				$prop[] = 'todayHighlight: true';
				$prop[] = 'todayBtn: "linked"';
			break;
		}
		if($limit_start) {
			if(validate_date($limit_start)) {
				$prop[] = 'startDate: "'.$limit_start.'"';
			}
		}
		if($limit_end) {
			if(validate_date($limit_end)) {
				$prop[] = 'endDate: "'.$limit_end.'"';
			} else {

			}
		}
		if(!$value) $prop[] = 'setDate: new Date()';
	echo implode(', ', $prop);
	echo '});</script>';
}

# form static data
function form_static($id, $value, $type=false) {
	form_label($id, false);
	echo '<p class="form-control-static">';
	switch($type) {
		case 'boolean':
			if($value) {
				echo '<i class="fa fa-fw fa-check-circle text-success"></i> '.m('yes');
			} else {
				echo '<i class="fa fa-fw fa-ban text-danger"></i> '.m('no');
			}
		break;
		case 'money':
			echo '$ '.number_format($value, 2);
		break;
		default:
			if($value) {
				echo $value;
			} else {
				echo '--';
			}
		break;
	}
	echo '</p>';
}

# form footer
function form_footer($back=true, $url=false) {
	global $id, $table, $fields;
	echo '<hr>';
	if(count($fields)) echo '<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-fw fa-check-circle"></i> <span class="">'.m('save_changes').'</span></button>';
	if($back) {
		echo "\n";
		if(!$url) {
			echo '<a class="btn btn-lg btn-default" href="'.substr(basename($_SERVER['SCRIPT_NAME']), 0, strrpos(basename($_SERVER['SCRIPT_NAME']), '.edit.php')).'.php';
			if($_GET['id']) echo '?id='.$_GET['id'];
			echo '"><i class="fa fa-fw fa-mail-reply"></i> '.m('list').'</a>';
		} else {
			echo '<a class="btn btn-lg btn-default" href="'.$url.'"><i class="fa fa-fw fa-mail-reply"></i> '.m('back').'</a>';
		}
	}
	echo '</form>';
	echo '</div></div></div></div></div></div>';

	# javascript
	if(count($fields)) {
		echo '<script type="text/javascript">
		$(document).ready(function(){
			$("#form_'.$table['name'].'").data("serialize", $("#form_'.$table['name'].'").jserialize()).find("*:input[type!=hidden]:first").focus();
			var validate_form_'.$table['name'].' = $("#form_'.$table['name'].'").validate({
				focusInvalid: true,
				submitHandler: function(form) {
					//save temporal changes
					$("#form_'.$table['name'].'").data("serialize", $("#form_'.$table['name'].'").jserialize());
					$("#form_'.$table['name'].'").find(":submit").attr("disabled", true);
					$("#form_'.$table['name'].'").find(":submit").find("i").removeClass("fa-check-circle").addClass("fa-spinner fa-spin");

					//save data
					$.post("'.basename($_SERVER['REQUEST_URI']).'", $("#form_'.$table['name'].'").jserialize(), function(data) {
						if($.isNumeric(data)) {';
						if(!$back) {
							echo 'bootbox.alert("'.m('save_ok').'", function(){';
								echo '$("#form_'.$table['name'].'").find(":submit").find("i").removeClass("fa-spinner fa-spin").addClass("fa-check-circle");';
								echo '$("#form_'.$table['name'].'").find(":submit").attr("disabled", false);';
								echo '$.doTimeout("timer_focus", 50, function(){$("#form_'.$table['name'].'").find("*:input[type!=hidden]:first").focus();});';
							echo '});';
						} else {
							if(!$url) {
								echo 'bootbox.confirm("'.m('save_return').'", function(r){
									if(r) {
										$(window).unbind("beforeunload");
										window.location.replace("'.substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '.edit.php')).'.php?id=" + data);
									} else {';
									if($id) {
										# edit
										echo '$("#form_'.$table['name'].'").find(":submit").find("i").removeClass("fa-spinner fa-spin").addClass("fa-check-circle");';
										echo '$("#form_'.$table['name'].'").find(":submit").attr("disabled", false);';
										echo '$.doTimeout("timer_focus", 50, function(){$("#form_'.$table['name'].'").find("*:input[type!=hidden]:first").focus();});';
									} else {
										# new
										echo 'window.location.replace("'.$_SERVER['SCRIPT_NAME'].'?id=" + data);';
									}
									echo '}
								});';
							} else {
								echo 'window.location.replace("'.$url.'");';
							}
						}
						echo '} else {
							bootbox.dialog({
								message: data,
								onEscape: function(){
									$("#form_'.$table['name'].'").find(":submit").find("i").removeClass("fa-spinner fa-spin").addClass("fa-check-circle");
									$("#form_'.$table['name'].'").find(":submit").attr("disabled", false);
								},
								buttons: {
									success: {
										label: "'.m('close').'",
										className: "btn-danger",
										callback: function() {
											$("#form_'.$table['name'].'").find(":submit").find("i").removeClass("fa-spinner fa-spin").addClass("fa-check-circle");
											$("#form_'.$table['name'].'").find(":submit").attr("disabled", false);
										}
									}
								}
							});
						}
					});
					return false;
				},
				rules: {';
				# fields array[name](required, filter, min, max, save, equal)
				foreach($fields as $k=>$item) {
					$prop = array();
					if($item[0]) $prop[] = 'required: true';
					if($item[1] != 'checkbox') {
						if($item[1]) $prop[] = $item[1].': true';
						if($item[1] == 'number' && $item[2] && $item[3]) {
							$prop[] = 'range: ['.$item[2].', '.$item[3].']';
						} else {
							if($item[2]) {
								if($item[1] == 'number') {
									$prop[] = 'min: '.$item[2];
								} else {
									$prop[] = 'minlength: '.$item[2];
								}
							}
							if($item[3]) {
								if($item[1] == 'number') {
									$prop[] = 'max: '.$item[3];
								} else {
									$prop[] = 'maxlength: '.$item[3];
								}
							}
						}
						if($item[6]) $prop[] = 'equalTo: "#control_'.$item[6].'"';
					}
					if(count($prop)) $js[$k] = $prop;
				}
				if(is_array($js)) {
					foreach($js as $k=>$prop) {
						$final[] = $k.': {'.implode(',', $prop).'}';
					}
					echo implode(',', $final);
				}
		echo '}});});
		$(window).bind("beforeunload", function(e){
			if($("#form_'.$table['name'].'").jserialize() != $("#form_'.$table['name'].'").data("serialize")) {
				return "'.m('back_confirmation').'";
			} else {
				e = null;
			}
		});
		</script>';
	}
}
function form_footer_simple() {
	echo '</form></div></div></div></div></div>';
}