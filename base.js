/**
* 
* Anonymous function to create and load the widgets 
*
*/ 
(function() {
        /**
        * The JavaScript URLs that should be loaded for the widget 
        *
        * @property EXT_JS
        * @type Array
        * @private
        */ 
    var EXT_JS = [],
        /**
        * The CSS URLs that should be loaded for the widget 
        *
        * @property EXT_CSS
        * @type Array
        * @private
        */     
        EXT_CSS = [],
        /**
         * Inline JS to be executed for the widget 
         *
         * @property INLINE_JS
         * @type String
         * @private
         */                     
        INLINE_JS = "",
        /**
         * Inline CSS to be applied for the widget 
         *
         * @property INLINE_CSS
         * @type String
         * @private
         */                     
        INLINE_CSS = "",
        /**
        * The widget markup content list to be painted in the page   
        *
        * @property MARKUP_LIST
        * @type Array
        * @private
        */         
        MARKUP_LIST = ['<div>Content 1</div>', '<div>Content 2</div>', '<div>Content 3</div>'],
        d = document, // DOM
        headObj = d.documentElement.firstChild, // Head node to append the scripts and CSS
        create = "createElement",
        wrapperHash = [], // Hash to hold the wrapper divs
        bIE = navigator.userAgent.match(/MSIE\s([^;]*)/),
        /**
         * 
         * Utility to check if given object is an Array
         *
         * @function isArray
         * @param obj {Object} Input object to check if it is an array
         * 
         * @private
         */    
        isArray = function(obj) {
            return (Array.isArray && Array.isArray(obj)) || (Object.prototype.toString.call(obj) === "[object Array]");
        },
        /**
         * 
         * ScriptLoader is a simple JavaScript loader utility API which downloads scripts 
         * in parallel with other resources along with maintaining execution order. 
         * 
         * The parallelism is achieved using the script DOM element technique (i.e. 
         * creating dynamic scripts and inserting them in DOM) and for execution order
         * a code massaging build time step is needed. 
         * 
         * The extra step is to wrap the entire JS code (for which execution order should
         * be maintained) in a wrapper function and passing that function in the API, as
         * shown below
         * 
         * var wrappedFn = function () {
         *     // All the application JS code
         * };
         *
         * @function loadScript
         * @param fileList {Array|String} Array of JSON objects or the URL string
         *                     JSON object format { url:  'http://one.js', delayedExec:  false },
         *                                        { url:  'http://two.js', delayedExec:  'wrappedFn' }, 
         * @param callback {function} the callback function to be executed after the scripts are
         *                               loaded
         * 
         * @private
         */        
        loadScript = function(fileList, callBack) {
            // Type conversion of fileList parameter if not an Array
            fileList = isArray(fileList) ? fileList : [fileList];
            // Check for fileList length, if 0 exec callback and return
            if(!fileList.length) {
            	callBack && callBack();
            	return;
            }
            
            var count = 0, 
                i = 0, 
                len = fileList.length, 
                obj = (typeof fileList[i] === "object"), 
                scriptObj, 
                
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
                scriptObj = d[create]("script"); // Creating script element
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
        },
        /**
         * 
         * Style loader to load the CSS URLs
         *
         * @function loadScript
         * @param fileList {Array|String} Array of string URLs or just one URL
         * 
         * @private
         */            
        loadStyle = function(fileList) {
            fileList = isArray(fileList)? fileList: [fileList];
            var i = 0, 
                len = fileList.length,
                cssNode;
            
            for(;i < len; i++) {
                cssNode = d[create]("link"); // creating the CSS element
                cssNode.type = "text/css";
                cssNode.rel = "stylesheet";
                cssNode.href = fileList[i];
                headObj.appendChild(cssNode);
            }
        },
        /**
         * 
         * Executes the serialized JavaScript
         *
         * @function execScript
         * @param rawScript String The raw JS string that needs to be executed
         * 
         * @private
         */          
        execScript = function(rawScript) {
        	var scriptNode = d[create]("script");
        	scriptNode.type = "text/javascript";
        	scriptNode.async = true;
        	if(bIE) {
        		scriptNode.text = rawScript;
        	} else {
        		scriptNode.appendChild(d.createTextNode(rawScript));
        	}
        	try {
        		headObj.appendChild(scriptNode);
        		headObj.removeChild(scriptNode);
        	} catch (e) {
        		// DO NOTHING
        	}
        },
        /**
         * 
         * Applies the serialized CSS styles
         *
         * @function applyStyle
         * @param rawStyle String The raw CSS style that needs to be applied
         * 
         * @private
         */              
        applyStyle = function(rawStyle) {
        	var styleNode = d[create]("style");
        	styleNode.type = "text/css";
        	if(bIE) {
        		styleNode.styleSheet.cssText = rawStyle;
        	} else {
        		styleNode.appendChild(d.createTextNode(rawStyle));
        	}
        	headObj.appendChild(styleNode);
        },
        /**
         * 
         * Paints the markup in the page by looking up the wrapper hash
         *
         * @function loadMarkup
         * 
         * @private
         */            
        loadMarkup = function() {
            for(var i = 0, l = wrapperHash.length, node, markup; i < l; i++) {                
                node = wrapperHash[i].node;
                markup = wrapperHash[i].markup;
                if(node.insertAdjacentHTML) {
                    node.insertAdjacentHTML("beforeend", markup);
                } else {
                    node.innerHTML = markup;                    
                }                
                node.style.display = 'block';
            }
        },
        /**
         * 
         * Create the wrapper Divs for the widgets
         *
         * @function createWrapperObjs
         * @param markupList {Array} Array of string contents 
         * 
         * @private
         */          
        createWrapperObjs = function(markupList) {
        	for(var i = 0, len = markupList.length, id; i < len; i++) {
        		id = 'motor-widget-' + i;    
        		d.write('<div id=' + id + ' style="display:none;margin: 10px 0;"></div>');
        		wrapperHash[i] = {node: d.getElementById(id), markup: markupList[i]};
        	}        	
        };
        
        // Step1: Create the wrapper divs
        createWrapperObjs(MARKUP_LIST);
        
        // Step 2: Load the external CSS
        EXT_CSS.length && loadStyle(EXT_CSS);
        
        // Step3: Apply inline styles if any
        INLINE_CSS && applyStyle(INLINE_CSS);
        
        // Step 4: Load the JS URLs
    	loadScript(EXT_JS, (function(inlineJS) {
    		return function() {
	        	// Step 5: Load the markup after JS loaded
	            loadMarkup();            
	            // Step 6: Execute inline JS if any
	            inlineJS && execScript(inlineJS);
    		};
        })(INLINE_JS));
})();