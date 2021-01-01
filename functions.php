global $pd_scripts;
$pd_scripts = array();

function list_scripts($tag, $handle) {
	global $pd_scripts;
    $pd_scripts[] = $handle;
	return $tag;
}

add_filter('script_loader_tag', 'list_scripts', 10, 2);

global $pd_styles;
$pd_styles = array();

function list_styles($tag, $handle) {
	global $pd_styles;
    $pd_styles[] = $handle;
	return $tag;
}

add_filter('style_loader_tag', 'list_styles', 10, 2);

/**
* PageDart.com Scripts and Styles Handles
*
* Dump all script and style handles at the bottom of any page
* by adding ?pagedart onto the end of the URL while logged-in
* as an Administrator level user.
*/
function print_scripts_and_styles($content) {
	// Only shown for Administrator level users when ?pagedart is added to the URL
    $trigger = isset( $_GET['pagedart'] ) && current_user_can( 'manage_options' );
    if ( ! $trigger ) return;
	
	global $pd_scripts;
	global $pd_styles;
	ob_start();
    ?>
    <section class="pagedart">
    	<h1 class="pagedart__h1"><a href="https://pagedart.com">PageDart</a> Scripts and Styles Handles</h1>
    	<div class="pagedart__lists">
    		<div class="pagedart__col">
    			<h2 class="pagedart__h2">Scripts</h2>
    			<?php foreach ( $pd_scripts as $handle ) : ?>
    				<p class="pagedart__handle"><?php echo $handle; ?></p>
    			<?php endforeach; ?>
    		</div>
			<div class="pagedart__col">
    			<h2 class="pagedart__h2">Styles</h2>
    			<?php foreach ( $pd_styles as $handle ) : ?>
    				<p class="pagedart__handle"><?php echo $handle; ?></p>
    			<?php endforeach; ?>
    		</div>
    	</div>
    </section>
    <style>
    .pagedart {
        padding: 30px;
        margin: 30px;
        border-radius: 4px;
        background: white;
        font-size: 16px;
        line-height: 1.4;
        height: 50vh;
        min-height: 500px;
        overflow-y: scroll;
    }
    .pagedart__lists {
        display: flex;
    }
    .pagedart__col {
        flex: 1;
        width: 50%;
    }
    .pagedart__h1 {
        margin: 0 0 20px;
    }
    .pagedart__h2 {
        line-height: 1;
        font-size: 18px;
        margin: 0 0 10px;
    }
    .pagedart__handle {
        padding: 0;
        margin: 0;
    }
    </style>
    <?php
    ob_end_flush();
}

add_action( 'shutdown', 'print_scripts_and_styles' );
