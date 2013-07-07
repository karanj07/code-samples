<?php

function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
      $location = $location;
    header("Location: {$location}");
    exit;
  }
}

function is_logged_in( ) {
  return isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])?true:false;
}

function is_plural($count, $string1, $string2) {
  return $count!=1 ? $string1:$string2;
}

function output_message($message="") { return "<p class=\"message\">{$message}</p>";}

function error_message($message="") { return "<p class='error-msg'>{$message}</p>";}

function success_message($message="") { return "<p class='success-msg'>{$message}</p>";}

function check_rating_submission($ex, $def=""){
return (isset($ex)&& !empty($ex))? $ex: $def;
}

function __autoload($class_name) {
    $class_name = strtolower($class_name);
  $path = LIB_PATH.DS."{$class_name}.php";
  if(file_exists($path)) {
    require_once($path);
  } else {
    	die("The file {$class_name}.php could not be found.");
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
  if($handle = fopen($logfile, 'a')) { // append
    $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
    fwrite($handle, $content);
    fclose($handle);
    if($new) { chmod($logfile, 0755); }
  } else {
    echo "Could not open log file for writing.";
  }
}

function placeholder($field, $defaultvalue=""){
return ( isset($_POST['submit'])? $_POST[$field]: $defaultvalue);
}

function get_profile_image_path($id="", $pic=""){
    echo isset($pic) && !empty($pic)?UPLOAD_DIR.$id.DS.$pic:SITE_URL.DS.'images/default_user.jpg';
}

function get_grade($x){
    switch($x){
        case (4.5<$x && $x<=5):
    		$y =  "A+";
    		break;
    	case (4<$x && $x<=4.5):
    		$y =  "A-";
    		break;
    	case (3.5<$x && $x<=4):
    		$y =  "A";
    		break;
    	case (3<$x && $x<=3.5):
    		$y =  "B+";
    		break;
    	case (2.5<$x && $x<=3):
    		$y =  "B";
    		break;
    	case (2<$x && $x<=2.5):
    		$y =  "B-";
    		break;
    	case (1.5<$x && $x<=2):
    		$y =  "C+";
    		break;
    	case (1<$x && $x<=1.5):
    		$y =  "C";
    		break;
    	case (0.5<$x && $x<=1):
    		$y =  "C-";
    		break;
    	case (0<$x && $x<=0.5):
    		$y =  "D";
    		break;
	}
	return $y;
}


function get_average_ratings($ratings="", $count="", $this_article=""){
			$points_total = array();
			foreach($ratings as $rating){  
				$params = explode(",", $rating->params);
				$points = explode(",", $rating->points);
				foreach($points as $key=>$val){
					!isset($points_total[$params[$key]])?$points_total[$params[$key]] = 0:false;
					$points_total[$params[$key]] += $val;
				}
			}//total points for each parameter
			
			foreach($points_total as $param=>$total){
				$raw_avg = $total/$count;
				$avg = ($this_article->rating_system==3)? get_grade($raw_avg):round($raw_avg,1);
				$points_total[$param] = $avg;
			}//calculating average for each parameter
			return $points_total;
}

?>