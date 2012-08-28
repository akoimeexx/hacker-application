<?php if($_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '192.168.1.42') { /* START Debug access */ ?>
<?php
	$debug[] = "Debug test.";
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$('div#output-window').hide();
		$('div#debug-bar').disableSelection();
		$('button#debug-bar-toggle-switch').click(function() {
			$(this).toggleClass('closed');
			$('div#output-window').toggle();
		});
		$('div#debug-bar').draggable({
			'axis': 'x', 
			'containment': 'document', 
			'handle': $('div#debug-bar label.move-bar'), 
			'snap': true, 
		});
		$('div#output-window').draggable({
			'containment': 'document', 
			'handle': $('div#output-window label.move-bar'), 
			'snap': true, 
		});
		$('#output-window').resizable({
			'containment': 'document', 
			'ghost': true, 
			'maxHeight': 600, 
			'maxWidth': 800, 
			'minHeight': 200, 
			'minWidth': 190, 
		});
	});
//]]>
</script>
<style type="text/css">
	.hidden { display: none; }
	#debug-bar *, #output-window * {
		color: #f0f0f0;
		color: rgba(255, 255, 255, 0.9);
		font-family: Ubuntu;
		font-size: 12px;
		font-weight: bold;
		margin: 0px;
		padding: 0px;
	}
	#debug-bar {
		background-color: #404040;
		background-color: rgba(0, 0, 0, 0.75);
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAASCAYAAABb0P4QAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAHlSURBVDiNpVS9yuJAFD35dSBx03xIYiGIFltZKOQB9lF8Ay0+to2FiA8gW/g6oqBiJSzqRgj40yhJtEjhzGwVyX6Jfu7uhcsk5yaHM2fmXmE0Gn0jhPRFUWxwzpGVADLxZO12u82jKHoX5vP5zDCMxm63QxAEmR9nESYxXddhmiaCIJjLkiQ19vs9er0eDocDKKXgnINSCsZY6j2ZMVYqldDpdKBpWkPknMP3fRyPRziOg+FwCMuyQAhBLpcDIQSqqkJVVSiKAkVRIMsyJEm6r67rIgxDcM4hxvIZY3h7e4Nt2xgMBjAMI6WIc57CYjxOMX5gjMFxHGy3W1SrVfT7/YcEWeRxiB+NTsYr6lIKkz93u12Uy2Ws12u0Wi3U63UsFgtsNhu4rovlcgnbtlPkmVuOD2c2m6HZbOJ0OkEQBFBK74o/UwkAcpKw3W6DUnq/DuPxGLVa7X5FkrWPVvzh4d/69UhdysNXCbJq8S5lAMjn8ygWi/A8L7MrnnUJYwyVSgW6roNzDmEymXBN07Df7xEEwcNB8KyfdV1HoVDA9XqFfD6foWkaLMuCaZoPp8or0+dyuUBerVbgnMMwjE9H1TPSMAzheR5k3/d/TKfTr6k2+bf4KRNCvkdRVAHw5T/JQkLIr9+7pek6Y06RxAAAAABJRU5ErkJggg==);
		background-position: 2px 4px;
		background-repeat: no-repeat;
		border: 2px solid #202020;
		border: 2px solid rgba(0, 0, 0, 0.25);
		border-top: 0px none transparent;
		border-bottom-left-radius: 2px;
		border-bottom-right-radius: 2px;
		height: 24px;
		overflow: hidden;
		padding: 0px 2px 0px 24px;
		position: fixed;
		top: 0px;
		left: 8px;
		width: 160px;
		z-index: 99999 !important;
	}
		div#debug-bar label.move-bar {
			color: rgba(0, 0, 0, 0.75);
			cursor: ew-resize;
			display: block;
			float: left;
			margin: 4px 0px;
			text-shadow: 0px 1px 0px rgba(255, 255, 255, 0.25);
		}
		div#output-window label.move-bar {
			color: rgba(0, 0, 0, 0.75);
			cursor: move;
			display: block;
			font-size: 18px;
			padding: 2px 8px;
			text-shadow: 0px 1px 0px rgba(255, 255, 255, 0.25);
		}
		div#debug-bar button#debug-bar-toggle-switch {
			background-color: #505050;
			background-color: rgba(255, 255, 255, 0.15);
			border: 0px none transparent;
			border-radius: 2px;
			color: #ffffff;
			cursor: pointer;
			display: block;
			float: right;
			font-family: Ubuntu;
			font-size: 12px;
			font-weight: bold;
			margin: 4px 0px;
			padding: 0px 16px 2px 4px;
		}
		div#debug-bar button#debug-bar-toggle-switch:hover {
			background-color: #606060;
			background-color: rgba(255, 255, 255, 0.25);
		}
		div#debug-bar button#debug-bar-toggle-switch:after, 
		div#debug-bar button#debug-bar-toggle-switch.closed:after {
			color: #505050;
			color: rgba(0, 0, 0, 0.35);
			display: block;
			position: absolute;
			right: 5px;
		}
		div#debug-bar button#debug-bar-toggle-switch:after {
			content: "\25b2";
			top: 2px;
		}
		div#debug-bar button#debug-bar-toggle-switch.closed:after {
			content: "\25bc";
			top: 4px;
		}
	div#output-window {
		background-color: #404040;
		background-color: rgba(0, 0, 0, 0.75);
		border: 2px solid #202020;
		border: 2px solid rgba(0, 0, 0, 0.25);
		border-radius: 2px;
		height: 292px;
		margin: 32px;
		padding: 0px;
		position: fixed;
		width: 480px;
		z-index: 99998 !important;
	}
		div#output-window div.stdout {
			overflow: auto;
		}
			div#output-window div.stdout code {
				background-color: #303030;
				background-color: rgba(0, 0, 0, 0.25);
				border: 1px dashed #101010;
				border: 1px dashed rgba(0, 255, 0, 0.25);
				color: #00e000;
				color: rgba(0, 255, 0, 0.75);
				display: block;
				font-family: monospace;
				margin: 8px 2px;
				padding: 8px 4px;
				white-space: pre;
			}
	
	/**
	 * jQuery UI Resizable block
	 */
	.ui-resizable { position: relative;}
	.ui-resizable-handle { position: absolute;font-size: 0.1px;z-index: 99999; display: block; }
	.ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }
	.ui-resizable-n { cursor: n-resize; height: 7px; width: 100%; top: -5px; left: 0; }
	.ui-resizable-s { cursor: s-resize; height: 7px; width: 100%; bottom: -5px; left: 0; }
	.ui-resizable-e { cursor: e-resize; width: 7px; right: -5px; top: 0; height: 100%; }
	.ui-resizable-w { cursor: w-resize; width: 7px; left: -5px; top: 0; height: 100%; }
	.ui-resizable-se { background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAYAAABWdVznAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAABmSURBVCiRjc8xCsAwCEBRW3KGTD1K7+bphE4dXF3q6iWSKYGUJOomPMF/lFIgMkR0q2o+o9jMLhF53YOGmflBxG978McAACmKtw0zvGxY4WmDh4cGDw8NEdwborjtSVWziPQfvb0C6CTrgJ2xBiMAAAAASUVORK5CYII=); background-repeat: no-repeat; cursor: se-resize; width: 12px; height: 12px; right: 1px; bottom: 1px; }
	.ui-resizable-sw { cursor: sw-resize; width: 9px; height: 9px; left: -5px; bottom: -5px; }
	.ui-resizable-nw { cursor: nw-resize; width: 9px; height: 9px; left: -5px; top: -5px; }
	.ui-resizable-ne { cursor: ne-resize; width: 9px; height: 9px; right: -5px; top: -5px;}
</style>
<div id="debug-bar">
	<label class="move-bar">Debug</label>
	<button id="debug-bar-toggle-switch" class="closed">Toggle Window</button>
</div>
<div id="output-window">
	<label class="move-bar">Output</label>
<?php if(isset($debug)) { ?>
	<div class="stdout">
<?php	foreach($debug as $key=>$data) { ?>
<code id="debug-data-id-<?php echo $key; ?>">&lt;?php
	var_dump( $debug[<?php echo $key; ?>] ); &crarr;
	<?php var_dump($data); ?>
?&gt;</code>
<?php 	} ?>
	</div>
<?php } ?>
</div>

<?php } /* END Debug access */ ?>
