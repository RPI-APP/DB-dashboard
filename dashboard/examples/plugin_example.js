// # Building a Freeboard Plugin
//
// A freeboard plugin is simply a javascript file that is loaded into a web page after the main freeboard.js file is loaded.
//
// Let's get started with an example of a datasource plugin and a widget plugin.
//
// -------------------



// Best to encapsulate your plugin in a closure, although not required.
(function()
{
	// TODO: http://stackoverflow.com/questions/5927284/how-can-i-make-setinterval-also-work-when-a-tab-is-inactive-in-chrome/5927432#12522580
	setTimeout(func, 200);
	function func() {
		$.get('../index.php/dashboard/getDashboard', function(result) {
			// console.log("Trying: " + result);
			freeboard.loadDashboard(JSON.parse(result));
			freeboard.setEditing(false);
		});

		var oldBlob = "";

		// Repeatedly poll for changes
		window.setInterval(function() {
			var pretty = true;
			var contentType = 'application/octet-stream';

			if (pretty) {
				var blob = JSON.stringify(freeboard.serialize(), null, '\t');
			} else {
				var blob = JSON.stringify(freeboard.serialize());
			}

			if (blob !== oldBlob) {
				// console.log("Blob: " + blob);
				// Something has changed! Send the server our new dashboard data
				$.post('../index.php/dashboard/dashboardUpdate', {json: blob}, function(result) {
	        		// console.log("Result: " + result);
	    		});
			}

			oldBlob = blob;
		}, 200);
	}

	function httpGet(theUrl)
	{
		var req = new XMLHttpRequest();

		req.open('GET', theUrl, false);
		req.send(null);

		if(req.status == 200) {
		   return req.responseText;
		}

		return "error";
	}

	// ## A Datasource Plugin
	//
	// -------------------
	// ### Datasource Definition
	//
	// -------------------
	// **freeboard.loadDatasourcePlugin(definition)** tells freeboard that we are giving it a datasource plugin. It expects an object with the following:
	freeboard.loadDatasourcePlugin({
		// **type_name** (required) : A unique name for this plugin. This name should be as unique as possible to avoid collisions with other plugins, and should follow naming conventions for javascript variable and function declarations.
		"type_name"   : "centek_database_plugin",
		// **display_name** : The pretty name that will be used for display purposes for this plugin. If the name is not defined, type_name will be used instead.
		"display_name": "Database Data",
        // **description** : A description of the plugin. This description will be displayed when the plugin is selected or within search results (in the future). The description may contain HTML if needed.
        "description" : "",
		// **external_scripts** : Any external scripts that should be loaded before the plugin instance is created.
		// "external_scripts" : [
		// 	"http://mydomain.com/myscript1.js",
		//     "http://mydomain.com/myscript2.js"
		// ],
		// **settings** : An array of settings that will be displayed for this plugin when the user adds it.
		"settings"    : [
			{
				// **name** (required) : The name of the setting. This value will be used in your code to retrieve the value specified by the user. This should follow naming conventions for javascript variable and function declarations.
				"name"         : "guid",
				// **display_name** : The pretty name that will be shown to the user when they adjust this setting.
				"display_name" : "GUID",
				// **type** (required) : The type of input expected for this setting. "text" will display a single text box input. Examples of other types will follow in this documentation.
				"type"         : "text",
				// **default_value** : A default value for this setting.
				"default_value": "",
				// **description** : Text that will be displayed below the setting to give the user any extra information.
				"description"  : "We isolate the GUID automatically so you can just paste it with any extraneous text.",
                // **required** : If set to true, the field will be required to be filled in by the user. Defaults to false if not specified.
                "required" : true
			},
			{
				"name"         : "refresh_time",
				"display_name" : "Refresh Time",
				"type"         : "text",
				"description"  : "Milliseconds.",
				"default_value": 200
			}
		],
		// **newInstance(settings, newInstanceCallback, updateCallback)** (required) : A function that will be called when a new instance of this plugin is requested.
		// * **settings** : A javascript object with the initial settings set by the user. The names of the properties in the object will correspond to the setting names defined above.
		// * **newInstanceCallback** : A callback function that you'll call when the new instance of the plugin is ready. This function expects a single argument, which is the new instance of your plugin object.
		// * **updateCallback** : A callback function that you'll call if and when your datasource has an update for freeboard to recalculate. This function expects a single parameter which is a javascript object with the new, updated data. You should hold on to this reference and call it when needed.
		newInstance   : function(settings, newInstanceCallback, updateCallback)
		{
			// myDatasourcePlugin is defined below.
			newInstanceCallback(new myDatasourcePlugin(settings, updateCallback));
		}
	});


	// ### Datasource Implementation
	//
	// -------------------
	// Here we implement the actual datasource plugin. We pass in the settings and updateCallback.
	var myDatasourcePlugin = function(settings, updateCallback)
	{
		// Always a good idea...
		var self = this;

		// Good idea to create a variable to hold on to our settings, because they might change in the future. See below.
		var currentSettings = settings;
		var currentValue = 0;

		/* This is some function where I'll get my data from somewhere */
		function getData()
		{
			var guid = currentSettings.guid;

			var regex = /.*([0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}).*/i;
			var match = regex.exec(guid);
			var val = "null";
			if (match.length >= 2) {
				guid = match[1];

				var val = httpGet("../index.php/dashboard/lastReading/" + guid);
			}

			var connected = (val == "null" ? 0 : 1);

			if (connected == 0) {
				val = currentValue;
			} else {
				currentValue = val;
			}

			var newData = { raw_value : val,
				round_value : Math.round(val * 1000) / 1000,
				int_value : Math.round(val),
				connected : connected};

			// I'm calling updateCallback to tell it I've got new data for it to munch on.
			updateCallback(newData);
		}

		// You'll probably want to implement some sort of timer to refresh your data every so often.
		var refreshTimer;

		function createRefreshTimer(interval)
		{
			if(refreshTimer)
			{
				clearInterval(refreshTimer);
			}

			refreshTimer = setInterval(function()
			{
				// Here we call our getData function to update freeboard with new data.
				getData();
			}, interval);
		}

		// **onSettingsChanged(newSettings)** (required) : A public function we must implement that will be called when a user makes a change to the settings.
		self.onSettingsChanged = function(newSettings)
		{
			// Here we update our current settings with the variable that is passed in.
			currentSettings = newSettings;
		}

		// **updateNow()** (required) : A public function we must implement that will be called when the user wants to manually refresh the datasource
		self.updateNow = function()
		{
			// Most likely I'll just call getData() here.
			getData();
		}

		// **onDispose()** (required) : A public function we must implement that will be called when this instance of this plugin is no longer needed. Do anything you need to cleanup after yourself here.
		self.onDispose = function()
		{
			// Probably a good idea to get rid of our timer.
			clearInterval(refreshTimer);
			refreshTimer = undefined;
		}

		// Here we call createRefreshTimer with our current settings, to kick things off, initially. Notice how we make use of one of the user defined settings that we setup earlier.
		createRefreshTimer(currentSettings.refresh_time);
	}
}());
