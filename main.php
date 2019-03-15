<?php
/*
Plugin Name: English Quizzes
Description: shortcode [list_questions category='YOUR_POST_CATEGORY']
Version: 1.0.0
Author: K1n
*/
ob_start();
session_start();

//start add custom post type
function custom_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Quizzes', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Quizz', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Quizzes', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Quizz', 'twentythirteen' ),
        'all_items'           => __( 'All Quizzes', 'twentythirteen' ),
        'view_item'           => __( 'View Quizz', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Quizz', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Quizz', 'twentythirteen' ),
        'update_item'         => __( 'Update Quizz', 'twentythirteen' ),
        'search_items'        => __( 'Search Quizz', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
    
// Set other options for Custom Post Type
    
    $args = array(
        'label'               => __( 'quizzes',  'twentythirteen' ),
        'description'         => __( 'Quizz news and reviews',  'twentythirteen' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 66,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'taxonomies'          => array( 'category' ),
    );
    
    register_post_type( 'quizzes', $args );
    
}
add_action( 'init',  'custom_post_type', 0 );


/*register bootstrap*/
function kin4529_enqueues_styles() {

    wp_enqueue_style( 'bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', false, '1.0.0');
    wp_enqueue_script( 'bootstrap-css' );
    wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, '3.3.1', true );
    wp_enqueue_script( 'jquery' );

}
add_action( 'admin_enqueue_scripts', 'kin4529_enqueues_styles' );



/*register question order and explain field*/
function kin4529_question_order_field(){
    add_meta_box('question_order_meta_boxes', 'Question Order', 'kin4529_question_order_field_view', 'quizzes', 'normal' , 'low');
}
add_action( 'add_meta_boxes', 'kin4529_question_order_field' );
function kin4529_question_order_field_view(){
    global $post;
    $meta_elements = get_post_meta($post->ID, 'kin4529_question_order_field_view', true);
    $question_explain = get_post_meta($post->ID, 'kin4529_question_explain_field_view', true);
    include_once 'question-order-field.php';
}
function kin4529_save_question_order_field(){ 
    global $post;
    if(isset($_POST['question_order']) || isset($_POST['question_explain'])){
        update_post_meta($post->ID, 'kin4529_question_order_field_view', $_POST['question_order']);
        update_post_meta($post->ID, 'kin4529_question_explain_field_view', $_POST['question_explain']);
    }
}
add_action('save_post', 'kin4529_save_question_order_field');




/*register custom field*/
function kin4529_custom_field($post){
	add_meta_box('quizz_meta_box', 'Quizz', 'kin4529_custom_field_view', 'quizzes', 'normal' , 'low');
}
add_action( 'add_meta_boxes', 'kin4529_custom_field' );
function kin4529_custom_field_view(){
    global $post;
    $meta_elements = get_post_meta($post->ID, 'kin4529_custom_field_view', true);
    $meta_elements = unserialize(base64_decode($meta_elements));
    include_once 'custom-field.php';
}

function kin4529_save_custom_field(){ 
	global $post;
    if(isset($_POST['answer'])){
        $counts = array_count_values($_POST['checked_id']);
        $answer_list_arrays = array();
        for($i = 0; $i < count($_POST['answer']); $i++){
            if(trim($_POST["answer"][$i] != '')){
                $answer_arrays = array(
                    'answer' => $_POST['answer'][$i],
                    'correct_answer' => $_POST['checked_id'][$i]
                );
                array_push($answer_list_arrays, $answer_arrays);
            }
        }
        $answer_list_arrays = base64_encode(serialize($answer_list_arrays));
        if ($counts['true'] >= 1) {
            update_post_meta($post->ID, 'kin4529_custom_field_view', $answer_list_arrays);
        }
    }
}
add_action('save_post', 'kin4529_save_custom_field');



/*register short code*/
function list_questions_func($atts) {
    ob_start();
    $a = shortcode_atts(array(
      'category' => '',
    ), $atts);

    $category = $a['category'];
    $args = array(
    'posts_per_page'   => -1,
    'category_name'    => $category,
    'meta_key'         => 'kin4529_question_order_field_view',
    'order'            => 'ASC',
    'orderby'          => 'meta_value_num',
    'post_type'        => 'quizzes',
    );
    $posts_array = get_posts( $args );
    $count = count($posts_array);
    if (isset($_POST['reset'])) {
        $_SESSION['current_question'] = 0;
        $_SESSION['number_of_correct_answer'] = 0;
    }
    if(!isset($_SESSION['current_question'])){
        $_SESSION['current_question'] = 0;

    }
    if(!isset( $_SESSION['number_of_correct_answer'])){
        $_SESSION['number_of_correct_answer'] = 0;
    }
    if (isset($_POST['next_anwser'])) {
        if ($_SESSION['current_question'] < $count) {
            $_SESSION['current_question'] = $_SESSION['current_question'] + 1;
        }
    }
    if (($_SESSION['current_question']) >= $count) {

        echo "<p>You were right ".$_SESSION['number_of_correct_answer']."/".$count." questions</p>";
        echo '<form method="post"><button name="reset">Reset</button></form>';
        die();
    }
    $meta_elements = get_post_meta($posts_array[$_SESSION['current_question']]->ID , 'kin4529_custom_field_view', true);
    $meta_elements = unserialize(base64_decode($meta_elements));


    $meta_explain = get_post_meta($posts_array[$_SESSION['current_question']]->ID, 'kin4529_question_explain_field_view', true);

    $index_of_correct_answer = array();
    foreach($meta_elements as $key => $value){
        if($value['correct_answer'] == 'true'){
            array_push($index_of_correct_answer, $key);
        }
    }
    $correct_count = 0;
    $key_alphabet = 65;
    include "single-quizzes.php";
    return ob_get_clean();
}
add_shortcode('list_questions', 'list_questions_func');
?>
