<?php
function RGB($r = 0,$g = 0,$b = 0) {

function Coloring($color,$brightness) {
	if (intval(($color + 255) / $brightness) < 255) {
		$color = intval(($color + 255) / $brightness);
	} else {
		$color = 255;
	}
	return $color;
}
$color = 'rgb('.$r.','.$g.','.$b.')';
$hover = 'rgb('.Coloring($r,1.1).','.Coloring($g,1.1).','.Coloring($b,1.1).')';
$shadow = 'rgb('.Coloring($r,1.5).','.Coloring($g,1.5).','.Coloring($b,1.5).')';

echo '<style type="text/css">

@CHARSET "UTF-8";

@page {
	size:landscape;
}

* {
	font-family:arial;
	font-size:18px;
	color:'.$color.';
	-moz-user-select:none;
	-webkit-user-select:none;
	-ms-user-select:none;
}

body {
	margin:30px;
	height:100%;
}

div.arrow {
	position:absolute;
	top:34px; left:34px;
	border:solid 1px '.$color.';
}

h1 {
	margin-left: 30px;
	font-size:25px;
}
			
a {
	text-decoration:none;
}

table {
	width:100%;
	empty-cells:show;
}

.arrow {
	width:18px;
	height:18px;
	text-align:center;
}

.data_row td {
	border:solid 1px '.$color.';
	vertical-align:middle;
}

[contenteditable] {
	-moz-user-select: text;
	-webkit-user-select: text;
	-ms-user-select: text;
}

[contenteditable]:focus {
	outline:solid 1px;
}

.error {
	background:'.$color.';
	background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAIklEQVQImWP8//8/AwMDw38GKGBC5sAEYIARXeA/AwMDAwA2QwYDWBk7ZQAAAABJRU5ErkJggg=="); repeat;	
}

.new_col td {
	border:dotted 1px '.$color.';
	vertical-align:middle;
}

.numeric, .numeric>input {
	text-align:right;
}

svg, img {
	float:left;
	position:relative;
	top:0px;
	left:0px;
	z-index:99;
	transform: rotate(30);
}

.clock circle, .clock line {
	stroke:'.$color.';
}

.arrow polygon, svg text {
	fill:'.$color.';
}
	
.selected {
	background:'.$hover.';
}

div.dropdownlist {
	position:absolute;
	z-index:99;
}

div.contextmenue {
	position:absolute;
	z-index:100;
}

div.dropdownlist ul, div.contextmenue ul {
	position: absolute;
	margin:0; padding:0;
	border:dotted 1px '.$color.';
	border-bottom:none;
	list-style-type:none;
	box-shadow:3px 3px 5px '.$shadow.';
}

div.contextmenue ul {
	box-shadow:3px 3px 5px '.$shadow.';
}

.dropdownlist ul li, .contextmenue li {
	background:#fff;
	padding:4px;
	border-bottom:dotted 1px '.$color.';
}

.contextmenue li:hover, .dropdownlist ul li:hover {
	background:'.$hover.';
}
			
.contextmenue li a, .dropdownlist ul li a {
	color:'.$color.';
	white-space:nowrap;
	overflow:hidden;
}

div.confirmbox {
	position:fixed;
	z-index:101;
	left:50%;
	top:50%;
	margin-top:-50px;
	min-width:400px;
	height:100px;
	padding:20px;
	border:dotted 1px '.$color.';
	background:#fff;
	box-shadow:3px 3px 5px '.$shadow.';
}

div.confirmbox a {
	display:block;
	float:right;
	margin:5px;
	margin-top:45px;
	color:'.$color.';
	background:#fff;
	padding:3px;
	border:dotted 1px '.$color.';
	width:100px;
	text-align:center;
}

div.confirmbox a:hover {
	border:solid 1px '.$color.';
	background:'.$hover.';
}
</style>';

}
?>