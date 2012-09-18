<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Playground</title>
	<link type="text/css" href="css_test.php" rel="stylesheet"/>
	<style type="text/css">
		body {
			width: 990px;
			margin: 0 auto;
		}
		h1 {
			text-align: center;
		}
		a {
			text-decoration: none;
		}
		.playground 
		{
			border: 2px solid;
			padding: 20px;
		}
		.render {
			border: 2px solid;
			width: 990px;
			margin: 20px 0;
		}
		.render div {
			border: 2px dashed;
			margin: 20px;
			width: 400px;
			height: 50px;
		}
	</style>	
</head>
<body>	
	<h1>The Playground</h1>
<script>
var templates = {};
templates.test = new Hogan.Template(function(c,p,i){i = i || "";var b = i + "";var _ = this;b += "<div class=\"keylet\">";b += "\n" + i;b += "	<a href=\"";b += (_.v(_.f("profileUrl",c,p,0)));b += "\"><div class=\"user-image\" style=\"background-image: url(\\'";b += (_.v(_.f("userImg",c,p,0)));b += "\\')\"></div></a>";b += "\n" + i;b += "	<div class=\"user-message\">";b += "\n" + i;b += "		<div>";b += "\n" + i;b += "			<div class=\"name\"><strong><a href=\"";b += (_.v(_.f("profileUrl",c,p,0)));b += "\">";b += (_.v(_.f("userId",c,p,0)));b += "</strong></a></div>";b += "\n" + i;b += "			<div class=\"timestamp\">";b += (_.v(_.f("createdTime",c,p,0)));b += "</div>";b += "\n" + i;b += "		</div>";b += "\n" + i;b += "		<div class=\"message\">";b += (_.v(_.f("message",c,p,0)));b += "</div>";b += "\n" + i;b += "		<div class=\"rating\">";b += "\n" + i;b += <div class=\"up\"><span class=\"count m";b += (_.v(_.f("id",c,p,0)));b += "\">";b += (_.v(_.f("like",c,p,0)));b += "</span><a href=\"#m";b += (_.v(_.f("id",c,p,0)));b += "\"><div class=\"icon\"></div></a></div>";b += "\n" + i;b += "		<div class=\"down\"><span class=\"count m";b += (_.v(_.f("id",c,p,0)));b += "\">";b += (_.v(_.f("dislikes",c,p,0)));b += "</span><a href=\"#m";b += (_.v(_.f("id",c,p,0)));b += "\"><div class=\"icon\"></div></a></div>";b += "\n" + i;b += "		</div>";b += "\n" + i;b += "	</div>";b += "\n" + i;b += "	<div class=\"clear\"></div>";b += "\n" + i;b += "</div>";return b;;});
</script>
	<div class="playground">
		<script type="text/javascript">document.write('<script type="text\/javascript" src="\/playground\/base.js"><\/script>');</script>
		<script type="text/javascript">
		window.onload = function() {
			alert("window onload");
		};
		
		function loadScript(fileList, callBack) {
		    // Type conversion of fileList parameter if not an Array
			fileList = (Array.isArray && Array.isArray(fileList)) || Object.prototype.toString.call(fileList) === "[object Array]" ? fileList : [fileList];
		    
			var count = 0, 
		    	i = 0, 
		    	len = fileList.length, 
		    	obj = (typeof fileList[i] === "object"), 
		    	scriptObj, 
		    	headObj = document.documentElement.firstChild,
		    	file,
		    	done = function (idx) {
			    	if (obj) {
			    		fileList[idx].done = true;
			            for (var j = 0; j < len; j++) {
			            	/* Check if the dependent files are downloaded and executed else just break the loop */
			            	if (!fileList[j].done) {
			            		break;
			            	}
			                // Call the delayed execution wrapper function if any              
			            	fileList[j].delayedExec && fileList[j]["delayedExec"](); 
			            }
			    	}
			    	if (++count == len && callBack) {
			    		callBack();
			    	}
			    };
			
		    for(;i < len; i++) {
				file = fileList[i];
				scriptObj = document.createElement("script"); // Creating script element
				scriptObj.type = "text/javascript";
				scriptObj.async = true;
		        if (scriptObj.readyState) { // For IE
		        	// Passing idx as closure to done function to know the order of the loaded JS
		        	scriptObj.onreadystatechange = function (idx) {
		        		return function () {
		        			var state = this.readyState;
		        			if (state == "loaded" || state == "complete" || state == "completed") {
		        				this.onreadystatechange = null;
		                            done(idx);
		        			}
		        		};
		        	}(i);
		        } else { // For all other browsers
		        	// Passing idx as closure to done function to know the order of the loaded JS
		        	scriptObj.onload = function (idx) {
		        		return function () {
		        			done(idx);
		        		};
		        	}(i);
		        }
		        scriptObj.src = file.url || file;
		        headObj.appendChild(scriptObj);
		    }
		};		
		//loadScript("js_test.php");			
		</script>		
	</div>
	<!-- <script src="js_test.php" async="true" defer="defer"></script>  -->
	<div class="render">
		<div>Render 1</div>
		<div>Render 2</div>
		<div>Render 3</div>
	</div>
	<div class="playground">
		<a href="#" id="ajaxCheck">AJAX Check</a>
		<div id="response"></div>
	</div>
	<iframe src="http://lm-sjc-00711069/playground/iframe.php"></iframe>
	<script type="text/javascript">
		var io = {
			get: function(url, cb) {
				var xhr = new XMLHttpRequest();

				xhr.onreadystatechange = function() {
					if(xhr.readyState === 4) {
						if(xhr.status === 200) {
							cb(JSON.parse(xhr.responseText));
						} else {
							cb();
						}
					}
				};

				xhr.open("GET", url);
				xhr.send(null);
			}
		};	
		
		(function(d) {
			var responseDiv = d.getElementById("response");				
			d.getElementById("ajaxCheck").onclick = function() {
				io.get("ajax.php", function(resp) {
					if(!resp) {
						return false;
					}
					var html = resp.data.html,
						PATTERN = /<script\b[\s\S]*?>(.*?)<\/script>/gi,
						m = PATTERN.exec(html)[1]; 
					eval(m); // Execute the JS
					responseDiv.innerHTML = html;
				});			
				return false;
			};
		})(document);
	</script>	
</body>
</html>