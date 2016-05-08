<?php
// Variables storing the blocks of html / javascript code for rendering output

// Styling of page elements
define("STYLE", "<style>

		body {
  			font: 10px sans-serif;
		}
		
		div {
			margin: 0 auto;
			width: 720px;
		}

		.axis path,
		.axis line {
  			fill: none;
  			stroke: #000;
  			shape-rendering: crispEdges;
		}
		
		.text {
			font-family: sans-serif;
		}
		</style>
		");

// Page title
define("HEADING", "<div width='720'>
			<h2>Fantasy Football Players</h2>
		</div>
		");

// Div element to contain the plot
define("PLOT", "<div width='720' height='350' class=\"scatter\"></div>
		<div>
			<p>Player performance measured as cost vs points</p>
			<p>The size of each bubble represents the popularity of that player</p>
		</div>
		");

// Refence the D3 source code
define("D3_SOURCE", "<script type=\"text/javascript\" src=\"https://d3js.org/d3.v3.min.js\"></script>
	");

// Fix input data
define("DATACLEANING", "data.forEach(function(d) {
    d.cost = +d.cost;
    d.points = +d.points;
    d.selected_by = +d.selected_by;
  });
  ");

// Main D3 code to plot each element
define("D3_CODE", "var margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = div.attr(\"width\") - margin.left - margin.right,
    height = div.attr(\"height\") - margin.top - margin.bottom,
    padding = 5;

var svg = div.append(\"svg\")
    .attr(\"width\", width + margin.left + margin.right)
    .attr(\"height\", height + margin.top + margin.bottom)
  .append(\"g\")
    .attr(\"transform\", \"translate(\" + margin.left + \",\" + margin.top + \")\");
    
var xScale = d3.scale.linear()
	.domain([d3.min(data, function(d) { return d[\"cost\"]; }) - padding, d3.max(data, function(d) { return d[\"cost\"]; }) + padding])
    .range([0, width]);

var yScale = d3.scale.linear()
	.domain([d3.min(data, function(d) { return d[\"points\"]; }) - padding, d3.max(data, function(d) { return d[\"points\"]; }) + padding])
    .range([height, 0]);
    
var rScale = d3.scale.linear()
	.domain([d3.min(data, function(d) { return d[\"selected_by\"]; }), d3.max(data, function(d) { return d[\"selected_by\"]; })])
	.range([2, 15]);

var xAxis = d3.svg.axis()
    .scale(xScale)
    .orient(\"bottom\");

var yAxis = d3.svg.axis()
    .scale(yScale)
    .orient(\"left\");
    
   svg.append(\"g\")
      .attr(\"class\", \"x axis\")
      .attr(\"transform\", \"translate(0,\" + height + \")\")
      .call(xAxis)
    .append(\"text\")
      .attr(\"class\", \"label\")
      .attr(\"x\", width)
      .attr(\"y\", -6)
      .style(\"text-anchor\", \"end\")
      .text(\"Cost\")
      .attr(\"stroke-width\", \"1px\");

   svg.append(\"g\")
      .attr(\"class\", \"y axis\")
      .call(yAxis)
    .append(\"text\")
      .attr(\"class\", \"label\")
      .attr(\"transform\", \"rotate(-90)\")
      .attr(\"y\", 6)
      .attr(\"dy\", \".71em\")
      .style(\"text-anchor\", \"end\")
      .text(\"Points\")
      
	svg.selectAll(\"circle\")
    	.data(data)
  	.enter().append(\"circle\")
  		.attr(\"stroke\", \"black\")
  		.attr(\"stroke-width\", \"1\")
  		.attr(\"fill\", function(d) { return d[\"colour\"]; })
    	.attr(\"cy\", function(d) { return yScale(d[\"points\"]); })
    	.attr(\"cx\", function(d) { return xScale(d[\"cost\"]); })
    	.attr(\"r\", function(d) { return rScale(d[\"selected_by\"]); });
	
    svg.selectAll(\".text\")
    	.data(data)
  	.enter().append(\"text\")
  		.attr(\"class\", \"text\")
    	.attr(\"y\", function(d) { return yScale(d[\"points\"]); - 5})
    	.attr(\"x\", function(d) { return xScale(d[\"cost\"]) + 5; })
    	.text(function(d) { return d[\"surname\"]; });	
    ");  
?>