<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Playground</title>
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
	<script src="js_test.php" async="true" defer="defer"></script>
	<div class="render">
		<div>Render 1</div>
		<div>Render 2</div>
		<div>Render 3</div>
	</div>
	<div class="playground">
		<a href="#" id="ajaxCheck">AJAX Check</a>
		<div id="response"></div>
	</div>
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