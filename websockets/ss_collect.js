//Includes
var WebSocket = require('ws');
var mysql = require('mysql');
var http = require('http');
var url = require('url');
var clone = require('clone');
var clc = require('cli-color');
var critical = clc.red.bold;
var warning = clc.yellow;
var notice = clc.blueBright;
var success = clc.green.bold;
var usage = require('usage');
var pid = process.pid; // you can use any valid PID instead

var nodemailer = require('nodemailer');

// create reusable transporter object using SMTP transport
var transporter = nodemailer.createTransport({
    service: 'Gmail',
    auth: {
        user: 'maelstrome26@gmail.com',
        pass: 'Androidisamazing!'
    }
});

var worldNames = {
	1: 'Connery',
	10: 'Miller',
	13: 'Cobalt',
	17: 'Emerald',
	19: 'Jaeger',
	25: 'Briggs'
};

var instances = {};

var enable =
{
	metagame: false,
	combat: true,
	facilitycontrol: true,
	vehicledestroy: true,
	populationchange: false,
	xpmessage: false,
};

var debug =
{
	instances: false,
	API: false,
	auth: false,
	clients: false,
	datadump: false,
	status: true,
	metagame: false,
	combat: false,
	facility: false,
	vehicles: false,
	charFlags: false,
	charID: false,
	upcoming: false,
	time: false,
	perf: false,
	actives: true,
	responses: false,
	resultID: false,
	weapons: true
};
/* 1 = All
   2 = CharFlags
   3 = CharID logging
*/
var resultOverride = 0;
var ServerSmash = 1; // Flag to prevent normal alert operation
var subscriptions = 0; // Check to see if we have valid subscriptions
var subscriptionsRetry = 0;

//SOE Census Service ID
var serviceID = 'planetside2alertstats'; // Census Service serviceID

var pool = mysql.createPool(
{
	connectionLimit: 100,
	host: '85.159.214.60',
	user: 'psb',
	password: 'firefly26',
	database: 'planetsidebattles',
	waitForConnections: true, // Flag to throw errors when connections are being starved.
	supportBigNumbers: true,
	bigNumberStrings: true
});

var cachePool = mysql.createPool(
{
	connectionLimit: 20,
	host: '85.159.214.60',
	user: 'ps2alerts',
	password: 'Micr0$0ft',
	database: 'ps2alerts_data',
	waitForConnections: true, // Flag to throw errors when connections are being starved.
	supportBigNumbers: true,
	bigNumberStrings: true
});

//-------------------------------------------------------------------
/**
*     WEBSOCKET CLIENT
*     Manages, records and relays information provided by the SOE API.
*/
//-------------------------------------------------------------------

var connectionState = 0;

//Connection Watcher - Reconnects if websocket connection is dropped.
function conWatcher()
{
	if(!wsClient.isConnected())
	{
		console.log(critical('Reconnecting...'));

		var connectionState = 2;

		var message =
		{
			state: connectionState,
			admin: false,
			response: 'auth'
		}

		sendAdmins("status", message);

		wsClient = new persistentClient();
	}
}

function subWatcher()
{
	if(wsClient.isConnected())
	{
		if (subscriptions == 0 && ServerSmash == 0) // If the socket doesn't get a response from the API when subscriptions have been sent
		{
			console.log(critical('SUBSCRIPTIONS NOT PASSED! RECONNECTING...'));
			subscriptionsRetry = 1;
			wsClient = new persistentClient();
		}
	}
}

var wsClient;
var upcomingCheckInterval;
var conWatcherInterval;
var subWatcherInterval;
var perfInterval;
var perfStats = {};

setInterval(function()
{
	usage.lookup(pid, function(err, result) {

		var memory = Math.round(result.memory / 1024 / 1024);
		var cpu = Math.round(result.cpu);
		var conns = Object.keys(clientConnections).length;

		if (debug.perf == true)
		{
			console.log(notice("============== PERFORMANCE =============="));
			console.log("CPU: "+cpu+"% - MEM: "+memory+"MB - Conns: "+conns);
			console.log(notice("========================================="));
		}

		perfStats =
		{
			"cpu": cpu,
			"mem": memory,
			"conns": conns,
			"msgSec": messagesRecievedSec,
			"msgLast": messagesRecievedLast * 2,
			"state": connectionState,
		}

		sendAdmins("perf", perfStats);

		messagesRecievedSec = 0;
	});
}, 1000);


/**************
Admin API Keys
***************/

var apiKeys = {};

function generate_api_keys()
{
	cachePool.getConnection(function(poolErr, dbConnection)
	{
		if (poolErr)
		{
			throw(poolErr);
		}

		dbConnection.query('SELECT * FROM APIUsers', function(err, result)
		{
			dbConnection.release();

			if (err)
			{
				throw(err);
			}
			else
			{
				for (var i = result.length - 1; i >= 0; i--) // Build the API key object
				{
					apiKeys[i] = {};

					apiKeys[i].apikey = String(result[i].apikey);
					apiKeys[i].user = result[i].user;
					apiKeys[i].site = result[i].site;
					apiKeys[i].admin = result[i].admin;
				}
			}

			if (debug.API == true)
			{
				//console.log(apiKeys);
			}
		});
	});
}

function checkAPIKey(APIKey, callback)
{
	var isValid = false;
	var admin = false;
	var username = false;

	APIKey = String(APIKey);

	if (APIKey != "undefined")
	{
		if (debug.auth == true)
		{
			console.log (notice("CHECKING API KEY: "+APIKey));
		}

		Object.keys(apiKeys).forEach(function(i) // Loop through the API keys array to check against supplied key
		{
			if (apiKeys[i].apikey == APIKey) // If theres a match
			{
				isValid = true;

				if (debug.auth == true)
				{
					console.log(success("API KEY MATCH"));
				}

				username = apiKeys[i].user;

				if (apiKeys[i].admin != "0") // If an admin
				{
					admin = true;
				}
			}
		});

		/*if (status == "success")
		{
			var col = "requestsSuccess";
		}
		else
		{
			var col = "requestsFailed";
		}

		var date = new Date();
		var time = date.getTime();

		cachePool.getConnection(function(poolErr, dbConnectionAPI)
		{
			dbConnectionAPI.query('UPDATE APILogs SET '+col+'='+col+'+1, lastRequest='+time+' WHERE `key` = "'+APIKey+'"', function(err, resultAPI)
			{
				if(err)
				{
					throw(err)
				}

				if (resultAPI.affectedRows == 0) // If new record for Attacker
				{
					var insert = {
						key: APIKey,
						requestsSuccess: 0,
						requestsFailed: 0,
						lastRequest: time
					};

					dbConnectionAPI.query('INSERT INTO APILogs SET ?', insert, function(err, result)
					{
						if(err)
						{
							if (err.errno != 1062) // If not a duplicate
							{
								reportError(err, "Initial Insert of API Log record");
								throw(err);
							}
						}
					});
				}
			});

			dbConnectionAPI.release();
		});*/
	}

	callback(isValid, username, admin);
}

/******************
	FIRE ZE LAZORS
*******************/

generate_weapons(function() // Generate weapons first, before loading websocket
{
	conWatcherInterval = setInterval(function() //You can change this if you want to reconnect faster, or slower.
	{
		conWatcher();
	}, 3000);

	subWatcherInterval = setInterval(function()
	{
		subWatcher();
	}, 10000); //You can change this if you want to reconnect faster, or slower.

	generate_api_keys();

	wsClient = new persistentClient();
});

/**************
	Client    *
**************/

function persistentClient(wss)
{
	var connected = true;

	//Return Status of connection.
	this.isConnected = function()
	{
		return connected;
	}

	this.getClient = function()
	{
		if(client != undefined)
		{
			return client;
		}
		else
		{
			return undefined;
		}
	}

	// Jhett's API
	//client = new WebSocket('ws://push.api.blackfeatherproductions.com/?apikey=44ad30fb56211ec71a24484a2390a7bb');

	client = new WebSocket('ws://push.api.blackfeatherproductions.com/?apikey=44ad30fb56211ec71a24484a2390a7bb'); // Jhett's API

	//Events
	client.on('open', function()
	{
		console.log(success("CONNECTED"));

		connectionState = 1;

		pool.getConnection(function(poolErr, dbConnectionI)
		{
			if (poolErr) { throw (poolErr); }

			restoreSubs(client, dbConnectionI, function()
			{
				console.log(success("Subscriptions restored!"));
			}); // Fire subscriptions if they are needed
		});
	});

	client.on('message', function(data, flags)
	{
		if(debug.datadump === true)
		{
			console.log(data);
		}

		pool.getConnection(function(poolErr, dbConnection)
		{
			if (poolErr)
			{
				console.log(poolErr);
				throw(poolErr);
			}

			processMessage(data, client, wss, dbConnection);
			dbConnection.release();
		});
	});

	client.on('error', function(error)
	{
		console.log((new Date()) + error.toString());
		connected = false;
	});

	client.on('close', function(code)
	{
		if (debug.clients == true)
		{
			console.log((new Date()) + ' Websocket Connection Closed [' + code +']');
		}
		connected = false;
	});
}

/************************
	Client Functions    *
************************/

var maintenance;
var eventsMonitor;

function onConnect(client) // Set up the websocket
{
	if (debug.clients == true)
	{
		console.log((new Date()) + ' WebSocket client connected!')
	}

	//"outfits":["37514584004240963"] 37514584004240963 DIGT | 37524142189447090 = PS2AlertsTesting

	if (enable.metagame == true) // If alerts are enabled
	{
		var alertsMessage  = '{"action":"subscribe","event":"MetagameEvent","all":"true"}'; // Subscribe to all alerts that happen
		client.send(alertsMessage);

		var actives = '{"action":"activeAlerts"}'; // Pull a list of all active alerts
		client.send(actives);
	}

	if (debug.instances === true)
	{
		console.log("INSTANCES ARRAY BUILT");

		console.log("============= INSTANCES DETECTED ===============");
		console.log(instances);
		console.log("================================================");
	}

	/* Fire instances dependant code */
	var timer = 30 * 1000;
	var eventsTimer = 10 * 1000;

	clearInterval(maintenance);

	var maintenance = setInterval(function()
	{
		if (debug.status == true && messagesRecieved > 0)
		{
			console.log(notice("TOTAL MESSAGES RECIEVED (1 min): "+messagesRecieved));
		}

		combatHistory();// Log combat history for active alerts

		messagesRecieved = 0
		messagesRecievedLast = messagesRecieved;

		checkInstances(function()
		{
			if (debug.instances === true)
			{
				console.log(success("INSTANCES CHECKED"));
				console.log(notice("=========== CURRENT ALERTS IN PROGRESS: ==========="));
				console.log(instances);
			}
		});
	}, timer);

	clearInterval(eventsMonitor);

	checkEvents(function()
	{
		if (debug.upcoming == true)
		{
			console.log(success("Checked for ending events."));
		}
	});

	eventsMonitor = setInterval(function()
	{
		checkEvents(function()
		{
			if (debug.upcoming == true)
			{
				console.log(success("Checked for ending events."));
			}
		});
	}, eventsTimer);
}

var eventTypes = ['MetagameEvent', 'Combat', 'FacilityControl', 'VehicleDestroy', 'PopulationChange', 'ExperienceEarned'];

//Processes Messages received from the client.
function processMessage(messageData, client, wss, dbConnection)
{
	var message;

	try // Check if the message we get is valid json.
	{
		message = JSON.parse(messageData);
	}
	catch(exception)
	{
		console.log(messageData);
		message = null;

		reportError("FAILURE TO PARSE JSON", messageData);
	}

	if (message) // If valid
	{
		/*pool.getConnection(function(poolErr, dbConnectionM)
		{
			recordMessage(messageData, dbConnectionM);
			dbConnectionM.release();
		});*/

		var eventType  = message.event_type;
		var eventCheck = eventTypes.indexOf(eventType);

		checkDuplicateMessages(message, function(messageValid)
		{
			if (messageValid === true)
			{
				if (eventCheck != "-1") // If a valid event type
				{
					message = message.payload;
					findResultID(message, eventType, function(resultIDArray) // Get resultID for all functions
					{
						if (debug.resultID == true)
						{
							console.log(notice("ResultIDs Found:"));
							console.log(resultIDArray);
						}

						for (var i = resultIDArray.length - 1; i >= 0; i--)
						{
							var resultID = resultIDArray[i];

							if (enable.metagame === true)
							{
								if (eventType == "MetagameEvent") // Alert Processing
								{
									if (debug.metagame === true)
									{
										console.log(message);
									}

									var alertType = message.metagame_event_type_id;
									var world = message.world_id;
									APIAlertTypes(alertType, function(typeData) // Check if alerts are supported
									{
										if (typeData != null) // If a valid alert type
										{
											if (!message.zone_id)
											{
												console.log(message);
												throw("MISSING ZONE ID FOR WORLD: "+world)
											}

											console.log(notice("Processing Alert Message"));

											if (message.status == "1") // If started
											{
												console.log(success("================== STARTING ALERT! =================="));
												insertAlert(message, typeData, client, dbConnection, function(resultID)
												{
													console.log(success("================ INSERTED NEW ALERT #"+resultID+" ("+worldNames[world]+") ================"));
												});
											}
											else if (message.status == "0") // If alert end
											{
												console.log(success("================== ENDING ALERT! =================="));
												endAlert(message, resultID, client, dbConnection, function(resultID)
												{
													console.log(success("================ SUCCESSFULLY ENDED ALERT #"+resultID+" ("+worldNames[world]+") ================"));
												});
											}
											else if (message.status == "2")
											{
												if (debug.metagame === true)
												{
													console.log("Alert update recieved.");
												}
											}
										}
										else
										{
											console.log(critical("INVALID / UNSUPPORTED ALERT TYPE: "+alertType+" - WORLD: #"+world));
											reportError("UNSUPPORTED ALERT TYPE DETECTED: "+alertType, "Insert Alert");
										}
									});
								}
							}

							if (enable.combat === true)
							{
								if (eventType == "Combat") // If a combat event
								{
									cachePool.getConnection(function(poolErr, dbConnectionCache)
									{
										if (poolErr)
										{
											throw(poolErr);
										}

										combatParse(message, resultID, dbConnectionCache);
										dbConnectionCache.release();
									});
								}
							}
							if (enable.facilitycontrol === true)
							{
								if (eventType == "FacilityControl") // If a territory Update
								{
									updateAlert(message, resultID, function()
									{
										console.log(success("PROCESSED FACILITY CONTROL"));
									});
								}
							}
							if (enable.vehicledestroy === true)
							{
								if (eventType == "VehicleDestroy")
								{
									insertVehicleStats(message, resultID, 0, function()
									{
										if (debug == 1)
										{
											console.log(success("PROCESSED VEHICLE KILLS"));
										}
									});
								}
							}

							if (enable.populationchange === true)
							{
								if (eventType == "PopulationChange")
								{
									console.log(critical("POPULATION CHANGE DETECTED"));
								}
							}
						};
					});
				}
				else // If a system message
				{
					var known = 0;
					if (message.websocket_event)
					{
						onConnect(client);
						console.log(message);

					}

					if (message.subscriptions != undefined)
                    {
                    	known = 1;
                    	subscriptions = 1;
                        if(message.subscriptions.Combat != undefined)
                        {
                            console.log(notice("COMBAT SUBS:"));
                            console.log(message.subscriptions.Combat.worlds);
                            console.log(message.subscriptions.Combat.zones);
                            console.log("----------------------------");
                        }

                        if (message.subscriptions.FacilityControl != undefined)
                        {
                            console.log(notice("FACILITY CONTROL SUBS:"));
                            console.log(message.subscriptions.FacilityControl.worlds);
                            console.log(message.subscriptions.FacilityControl.zones);
                            console.log("----------------------------");
                        }

                        if(message.subscriptions.VehicleDestroy != undefined)
                        {
                            console.log(notice("VEHICLE COMBAT SUBS:"));
                            console.log(message.subscriptions.VehicleDestroy.worlds);
                            console.log(message.subscriptions.VehicleDestroy.zones);
                            console.log("----------------------------");
                        }

                        if(message.subscriptions.MetagameEvent != undefined)
                        {
                            console.log(notice("METAGAME SUBS RECIEVED:"));

                            if (debug.API == true)
                            {
                                console.log(message.subscriptions.MetagameEvent);
                            }
                        }
                    }

                    if (message.event_type == "ServiceStateChange")
                    {
                    	known = 1;
                        if (message.payload.online != undefined)
                        {
                            var worldID = message.payload.world_id;
                            if (message.payload.online == "0")
                            {
                                // EMAIL ME SAYING WORLD IS OFFLINE

                                var mailOptions = {
                                    from: 'Alert Monitor <script@ps2alerts.com>', // sender address
                                    to: 'maelstrome26@gmail.com', // list of receivers
                                    subject: 'World Offline - '+worldID, // Subject line
                                    html: 'World #'+worldID+' has crashed. Alerts will not come through.' // html body
                                };

                                console.log(critical("WORLD #"+worldID+" HAS CRASHED!"));
                            }
                            else if (message.payload.online == "1")
                            {
                                // EMAIL ME SAYING WORLD IS BACK ONLINE

                                var mailOptions = {
                                    from: 'Alert Monitor <script@ps2alerts.com>', // sender address
                                    to: 'maelstrome26@gmail.com', // list of receivers
                                    subject: 'World Online - '+worldID, // Subject line
                                    html: 'World #'+worldID+' is back online. Alerts should come through.' // html body
                                };

                                // Resend activeAlerts message
                                var actives = '{"action":"activeAlerts"}'; // Pull a list of all active alerts
                                client.send(actives);

                                console.log(success("WORLD #"+worldID+" IS BACK!"));
                            }

                            // send mail with defined transport object
                            /*transporter.sendMail(mailOptions, function(error, info){
                                if(error){
                                    console.log(error);
                                } else {
                                    console.log('Message sent: ' + info.response);
                                }
                            });
							*/
                        }
                    }

                    if (known == 0) // If unknown message
                    {
                        console.log(message);
                        console.log(notice("Ignoring message."));
                    }
				}
			}
			else
			{
				console.log(warning(JSON.stringify(message)));
				console.log(critical("DUPLICATE MESSAGE DETECTED, IGNORING!"));
				console.log(critical("Type: "+eventType));
			}
		});
	}
}

function findResultID(message, eventType, callback)
{
	var returnedResults = [];

	if (debug.resultID === true)
	{
		console.log(notice("CHECKING RESULT ID"));
	}

	if(resultOverride != 0)
	{
		returnedResults.push(resultOverride);
	}
	else
	{
		var world = message.world_id;
		var zone = message.zone_id;
		var time = new Date().getTime();
		time = parseInt(time / 1000); // To convert to seconds
		// currently events will take priority over normal alerts. I need to make sure I can handle both.

		Object.keys(instances).forEach(function(key)
		{
			var valid = true;
			if (instances[key].world == world && instances[key].zone == zone)
			{
				var startTime = instances[key].startTime;
				var endTime = instances[key].endTime;

				if (isNaN(startTime) === true)
				{
					console.log(instances[key]);
					throw('startTime is NaN for instance')
				}

				if (isNaN(endTime) === true)
				{
					console.log(instances[key]);
					throw('endTime is NaN for instance')
				}

				if (startTime < time && endTime > time) // If message is still within time
				{
					returnedResults.push(instances[key].resultID);
				}
				else
				{
					if (debug.resultID === true)
					{
						console.log(warning("MESSAGE RECIEVED OUT OF GAME TIME FOR RESULT #"+instances[key].resultID))
					}
				}
			}
		});
	}

	return callback(returnedResults);
}

function reportError(error, loc)
{
	pool.getConnection(function(poolErr, dbConnection)
	{
		if (poolErr)
		{
			throw(poolErr);
		}

		var time = new Date().getTime();

		var errPost =
		{
			errorReturned: error,
			errorLocation: loc,
			time: time
		};

		dbConnection.query('INSERT INTO ws_errors SET ?', errPost, function(err, result)
		{
			console.log(critical("++++++++++++++++++++++ ERROR DETECTED!!! ++++++++++++++++++++++"));
			console.log(error);
			console.log(critical("LOCATION: "+loc));
			console.log(critical("+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"));

			dbConnection.release();
		});

		// setup e-mail data with unicode symbols
		var mailOptions = {
		    from: 'Alert Monitor <script@ps2alerts.com>', // sender address
		    to: 'maelstrome26@gmail.com', // list of receivers
		    subject: 'Websocket Error!', // Subject line
		    html: '<b>ERROR DETECTED!</b><br><br>Location: '+loc+'<br><br>ERROR: '+error // html body
		};

		// send mail with defined transport object
		transporter.sendMail(mailOptions, function(error, info){
		    if(error){
		        console.log(error);
		    }else{
		        console.log('Message sent: ' + info.response);
		    }
		});
	});
}

function insertAlert(message, typeData, client, dbConnectionA, callback)
{
    console.log(notice("ALERT MESSAGE FOLLOWS:"));
    console.log(message);

    var world = message.world_id;
    var zone = message.zone_id;
    var alertType = message.metagame_event_type_id;

    console.log(notice("NEW ALERT DETECTED!"));

    dbConnectionA.query("SELECT * FROM ws_results WHERE ResultStartTime = "+message.start_time+" AND ResultServer="+world, function(err, result)
    {
        if (err)
        {
            throw(err);
        }

        if (result[0] !== undefined)
        {
            console.log(critical("ATTEMPTED TO ADD AN EXISTING ALERT!"));
            reportError("Attempted to insert alert when exists! World: "+world+" - Zone: "+zone, "Insert Alert");
            return;
        }

        if (instances[world][zone].status == true) // If an alert is already active on this world and zone, ignore it and report the error.
        {
            reportError("Active Alert already detected! World: "+world+" - Zone: "+zone, message);
            console.log(critical("ALERT INSTANCE ALREADY ACTIVE!"));
            return;
        }
        if (message.start_time) // If a valid alert message
        {
            var returned = true;

            var empires = [];
            var attacker = 0;
            var top = 0;

            empires[1] = message.control_vs;
            empires[2] = message.control_nc;
            empires[3] = message.control_tr;

            console.log("CONTROL: "+empires);

            for (var i = empires.length - 1; i >= 1; i--) {
                if (empires[i] > top)
                {
                    top = empires[i];
                    attacker = factions[i];
                }
            };

            empires.sort(function(a, b){return b-a});

            var startAlert =
            {
                instanceID: message.instance_id,
                ResultStartTime: message.start_time,
                ResultServer: world,
                ResultTimeType: "TEST",
                ResultAlertCont: zone,
                ResultAlertType: alertType,
                ResultMajority: attacker,
                InProgress: 1
            }

            console.log("================ INSERTING INITIAL RECORD ================");

            dbConnectionA.query('INSERT INTO ws_results SET ?', startAlert, function(err, result)
            {
                if (err)
                {
                    if (err.errno != 1062) // If not a duplicate
                    {
                        console.log(message);
                        reportError(err, "Insert Capture Record");
                        throw(err)
                    }
                    else
                    {
                        console.log(critical("INVALID / DUPLICATED ALERT RECORD DETECTED! Skipping!"));
                        reportError("Duplicate Alert, World: "+world+" - ZONE: "+zone, message);
                    }
                }
                else
                {
                    var resultID = result.insertId;

                    if (message.status == "1") // If start of an alert, insert the initial map
                    {
                        insertInitialMap(message, resultID, dbConnectionA, function(rowsAffected)
                        {
                            console.log(success("INITIAL INFORMATION ADDED - "+rowsAffected+" RECORDS"));
                        });
                    }
                    else
                    {
                        console.log(warning("DETECTED IN PROGRESS ALERT, SKIPPING MAP DATA UPDATE!"));
                    }

                    var endtime = calcEndTime(message.start_time, message.metagame_event_type_id);

                    var monitorPost =
                    {
                        instanceID: message.instance_id,
                        world: message.world_id,
                        zone: message.zone_id,
                        resultID: resultID,
                        started: message.start_time,
                        endtime: endtime,
                        type: alertType
                    };

                    var toSend =
                    {
                        "timestamp": message.start_time,
                        "endtime": endtime,
                        "world": world,
                        "zone": zone,
                        "resultID": resultID,
                        "controlVS": message.control_vs,
                        "controlNC": message.control_nc,
                        "controlTR": message.control_tr,
                        "remaining": 7200
                    };

                    console.log(critical("Sending Websocket Message: "));
                    console.log(critical(JSON.stringify(toSend)));

                    sendMonitor("alertStart", toSend);

                    dbConnectionA.query('INSERT INTO ws_instances SET ?', monitorPost, function(err, result)
                    {
                        if (err)
                        {
                            reportError(err, "Insert Instances");
                            var returned = false;
                        }
                        else
                        {
                            console.log(success("INITIAL INSERT OF ALERT: #"+resultID+" SUCCESSFUL!"));
                            console.log(success("====================================================="));

                            var factionArray =
                            {
                                resultID: resultID,
                                killsVS: 0,
                                killsNC: 0,
                                killsTR: 0,
                                deathsVS: 0,
                                deathsNC: 0,
                                deathsTR: 0,
                                teamKillsVS: 0,
                                teamKillsNC: 0,
                                teamKillsTR: 0,
                                suicidesVS: 0,
                                suicidesNC: 0,
                                suicidesTR: 0,
                                totalKills: 0,
                                totalDeaths: 0,
                                totalTKs: 0,
                                totalSuicides: 0
                            }

                            dbConnectionA.query('INSERT INTO ws_factions SET ?', factionArray, function(err, result)
                            {
                                if (err)
                                {
                                    reportError(err, "Insert Factions Record");
                                    var returned = false;
                                }
                                else
                                {
                                    fireSubscriptions(message, resultID, "subscribe")

                                    callback(resultID);
                                }
                            });
                        }
                    });
                }
            });
        }
        else
        {
            console.log(critical("INVALID START TIME RECIEVED, SKIPPING!"));
        }
    });
}

function endAlert(message, resultID, client, dbConnectionA, callback)
{
    console.log(message);
    var world = message.world_id;
    var zone = message.zone_id;

    fireSubscriptions(message, resultID, "unsubscribe");

    console.log('================ ENDING ALERT #'+resultID+' - World: '+world+' - Zone: '+zone+' ================');

    var returned = true;
    if (resultID)
    {
        var date = new Date();

        var datetime = DateCalc(date);

        if ((!message.end_time) || (message.end_time == "0"))  // If time is empty, use the current datetime as a backup
        {
            var newDate = new Date().getTime();
            message.end_time = parseInt(newDate / 1000);
        }

        /* Alert Processing */

        dbConnectionA.query("SELECT * FROM ws_map WHERE resultID="+resultID+" ORDER BY timestamp DESC LIMIT 1", function(err, Lresult)
        {
            if (err)
            {
                throw(err);
            }
            else
            {
                dbConnectionA.query("SELECT * FROM ws_map WHERE resultID="+resultID+" ORDER BY timestamp ASC LIMIT 1", function(err, Fresult)
                {
                    if (err)
                    {
                        throw(err);
                    }

                    if ((Fresult[0]) && (Lresult[0])) // If results have been pulled
                    {
                        calcWinners(message, resultID, Lresult, Fresult, dbConnectionA, function(winner, draw, domination)
                        {
                            if (winner) // If the winner was actually calculated
                            {
                                console.log(success("WINNER IS: "+winner));

                                dbConnectionA.query('UPDATE ws_results SET ResultDateTime="'+datetime+'", InProgress="0", Valid="1", ResultEndTime="'+message.end_time+'", ResultWinner="'+winner+'", ResultDomination="'+domination+'", ResultDraw="'+draw+'" WHERE ResultID='+resultID, function(err, result)
                                {
                                    console.log("UPDATING RESULT RECORD");

                                    if (err)
                                    {
                                        reportError(err, "End Result (Update)");
                                        throw(err);
                                        var returned = false;
                                    }
                                    else if (result.affectedRows == 0) // if it failed
                                    {
                                        throw("UPDATING ALERT RECORD FAILED! #"+resultID);
                                    }
                                    else
                                    {
                                        console.log(success("RECORD UPDATED"));
                                        console.log("DELETING INSTANCE RECORD");

                                        dbConnectionA.query('DELETE FROM ws_instances WHERE resultID='+resultID, function(err, result)
                                        {
                                            if (err)
                                            {
                                                reportError(err, "Delete Instance");
                                                throw(err);
                                                var returned = false;
                                            }
                                            else
                                            {
                                                console.log(success("INSTANCE DATABASE RECORD SUCCESSFULLY DELETED"));

                                                var toSend =
                                                {
                                                    "resultID": resultID,
                                                    "endtime": message.end_time,
                                                    "winner": winner,
                                                    "controlVS": instances[world][zone].controlVS,
                                                    "controlNC": instances[world][zone].controlNC,
                                                    "controlTR": instances[world][zone].controlTR,
                                                    "domination": domination,
                                                    "world": world,
                                                    "zone": zone
                                                };
                                                console.log(notice("Websocket Message: ")+toSend);

                                                sendMonitor("alertEnd", toSend);
                                                sendResult("alertEnd", toSend, resultID);

                                                callback(resultID);
                                            }
                                        });
                                    }
                                });
                            }
                            else
                            {
                                console.log(critical("UNABLE TO CALCULATE WINNER!"));
                                console.log("RESULT ID: "+resultID);
                                reportError("UNABLE TO CALCULATE WINNER: "+resultID, message);
                                resetScript();
                                return false;
                            }
                        });
                    }
                });
            }
        });
    }
}

function updateAlert(message, resultID, callback)
{
    var returned = true;
    if (resultID)
    {
        console.log('================ UPDATING ALERT MAP #'+resultID+' ================');
        updateMapData(message, resultID, 0)
    }
}

function insertInitialMap(message, resultID, dbConnectionMap, callback)
{
    if (message.facilities) // If Valid
    {
        var worldID = message.world_id;
        var zoneID = message.zone_id;

		var rowsAffected = 0;

		Object.keys(message.facilities).forEach(function(key)
		{
			var post =
			{
				resultID: resultID,
				worldID: worldID,
				zoneID: zoneID,
				facilityID: message.facilities[key].facility_id,
				facilityTypeID: message.facilities[key].facility_type_id,
				facilityOwner: message.facilities[key].owner
			}

			dbConnectionMap.query('INSERT INTO ws_map_initial SET ?', post, function(err, result)
			{
				if (err)
				{
					if (err.errno != 1062) // If not a duplicate
					{
						console.log(message);
						reportError(err, "Insert Capture Initial Record");

						console.log(critical(message.facilities));
						throw(err)
					}
					else
					{
						console.log(warning("DUPLICATE MAP RECORD DETECTED, SKIPPING ENTRY!"));
						dbConnection.release();
					}
				}
				else
				{
					rowsAffected++;
				}
			});
		});

		callback(rowsAffected);
	}
}

function updateMapData(message, resultID, insert)
{
	if (message.facility_id) // If Valid
	{
		var defence = 0;

		if (message.new_faction_id == message.old_faction_id)
		{
			defence = 1;
			console.log("DEFENCE!");
		}

		if (debug.facility === true) {
			console.log(notice(JSON.stringify(message, null, 4)));
		}

        if (message.is_capture == "1") {
            var post =
    		{
    			resultID: resultID,
    			timestamp: message.timestamp,
    			facilityID: message.facility_id,
    			facilityOwner: message.new_faction_id,
    			facilityOldOwner: message.old_faction_id,
    			controlVS: message.control_vs,
    			controlNC: message.control_nc,
    			controlTR: message.control_tr,
    			durationHeld: message.duration_held,
    			defence: defence,
    			zone: message.zone_id,
    			world: message.world_id,
    			outfitCaptured: message.outfit_id
    		};

    		sendResult("facility", post, resultID);
    		sendMonitor("update", post);

    		pool.getConnection(function(poolErr, dbConnection)
    		{
    			if (poolErr)
    			{
    				throw(poolErr);
    			}
    			else
    			{
    				dbConnection.query('INSERT INTO ws_map SET ?', post, function(err, result)
    				{
    					if (err)
    					{
    						if (err.errno != 1062) // If not a duplicate
    						{
    							console.log(message);
    							reportError(err, "Insert Capture Record");
    							throw(err)
    						}

    						console.log(warning("DUPLICATE MAP RECORD DETECTED, SKIPPING ENTRY!"));
    						dbConnection.release();
    					}
    					else
    					{
    						var world = message.world_id;
    						var zone = message.zone_id;
    						instances[resultID].controlVS = message.control_vs;
    						instances[resultID].controlNC = message.control_nc;
    						instances[resultID].controlTR = message.control_tr;

    						if (defence == 0)
    						{
    							var instancesPost =
    							{
    								controlVS: message.control_vs,
    								controlNC: message.control_nc,
    								controlTR: message.control_tr
    							};

    							dbConnection.query("UPDATE ws_instances SET ? WHERE resultID = "+resultID, instancesPost, function(err, result)
    							{
    								if (err)
    								{
    									throw(err);
    								}

    								dbConnection.release()
    							});
    						}

    						console.log(success("FACILITY / TERRITORY RECORD INSERTED FOR WORLD: "+worldNames[message.world_id]+" - ZONE: "+message.zone_id));
    						console.log(notice("New Control Percentages: ")+"VS: "+message.control_vs+"% - NC: "+message.control_nc+"% - TR: "+message.control_tr+"%");
    					}
    				});
    			}
    		});
        }
	}
}

function combatParse(message, resultID, dbConnectionCache)
{
	var killerID = message.attacker_character_id;
	var victimID = message.victim_character_id;
	var killerOutfit = message.attacker_outfit_id;
	var victimOutfit = message.victim_outfit_id;
	var killerName = message.attacker_character_name;
	var victimName = message.victim_character_name;
	var suicide = 0;
	var teamKill = 0;

	messagesRecieved++;
	messagesRecievedSec++;

	if (message.attacker_faction_id == message.victim_faction_id) // If a TK
	{
		teamKill = 1;
	}

	if (killerID == victimID)
	{
		suicide = 1;
		teamKill = 0;
	}

	if (debug.combat == true)
	{
		console.log('================ INSERTING COMBAT RECORD ================');
	}

	// ATTEMPT TO GET CHARACTER NAME IF MISSING

	checkPlayerCache(killerID, dbConnectionCache, function(killerName)
	{
		if (debug.combat == true)
		{
			console.log("GOT NAME: "+killerName);
		}

        if (killerName === false)
        {
            killerName = message.attacker_character_name;
        }

		checkPlayerCache(victimID, dbConnectionCache, function(victimName)
		{
			if (debug.combat == true)
			{
				console.log("GOT NAME: "+victimName);
			}

            if (victimName === false)
            {
                victimName = message.victim_character_name;
            }

			if (!killerName || !victimName)
			{
				reportError("No names were found for combat event.", "Insert Combat Record");
			}

			var combatArray =
			{
				timestamp: message.timestamp,
				resultID: resultID,
				attackerID: killerID,
				attackerName: killerName,
				attackerOutfit: killerOutfit,
				attackerFaction: message.attacker_faction_id,
				attackerLoadout: message.attacker_loadout_id,
				victimID: victimID,
				victimName: victimName,
				victimOutfit: victimOutfit,
				victimFaction: message.victim_faction_id,
				victimLoadout: message.victim_loadout_id,
				weaponID: message.weapon_id,
				vehicleID: message.vehicle_id,
				headshot: message.headshot,
				zone: message.zone_id,
				worldID: message.world_id,
				teamkill: teamKill,
				suicide: suicide,
			};

			if (debug.combat == true)
			{
				console.log(critical("===== ORIGINAL MESSAGE: ======="));
				console.log(critical(JSON.stringify(message)));
				console.log("Combat Object Built");
				console.log(warning(JSON.stringify(combatArray)));
			}

			checkOutfitCache(message.attacker_outfit_id, dbConnectionCache, function(aoutfitName, aoutfitTag, aoutfitFaction, aoutfitID)
			{
				combatArray.aOutfit = {};

				if (aoutfitName != undefined) // If returned
				{
					combatArray.aOutfit = {};
					combatArray.aOutfit.id = aoutfitID;
					combatArray.aOutfit.name = aoutfitName;
					combatArray.aOutfit.tag = aoutfitTag;
					combatArray.aOutfit.faction = aoutfitFaction;
				}
				else
				{
					combatArray.aOutfit = {};
					combatArray.aOutfit.id = "0";
					combatArray.aOutfit.name = "No Outfit";
					combatArray.aOutfit.tag = "";
					combatArray.aOutfit.faction = "0";
				}

				if (debug.combat == true)
				{
					console.log("Attacker Outfit Object Built");
				}

				checkOutfitCache(message.victim_outfit_id, dbConnectionCache, function(voutfitName, voutfitTag, voutfitFaction, voutfitID)
				{
					if (voutfitName != undefined) // If returned
					{
						combatArray.vOutfit = {};
						combatArray.vOutfit.id = voutfitID;
						combatArray.vOutfit.name = voutfitName;
						combatArray.vOutfit.tag = voutfitTag;
						combatArray.vOutfit.faction = voutfitFaction;
					}
					else
					{
						combatArray.vOutfit = {};
						combatArray.vOutfit.id = "0";
						combatArray.vOutfit.name = "No Outfit";
						combatArray.vOutfit.tag = "";
						combatArray.vOutfit.faction = "0";
					}

					pool.getConnection(function(poolErr, dbConnectionC)
					{
						if (poolErr)
						{
							throw(poolErr);
						}

						if (debug.combat == true)
						{
							console.log("Victim Outfit Object Built");
						}

						sendResult("combat", combatArray, resultID);

						insertCombatRecord(message, resultID, combatArray, dbConnectionC, function()
						{
							if(debug.combat == true)
							{
								console.log("INSERTED COMBAT RECORD");
							}

							dbConnectionC.release();

						});
					});
				});
			});
		});
	});

	addKillMonitor(killerID, victimID, "kill", message.timestamp, message.vehicle_id, 0, resultID, killerName, victimName);
}

function insertCombatRecord(message, resultID, combatArray, dbConnectionC, callback)
{
	if(resultID) // Make sure result ID is valid first
	{
		var postArray =
		{
			timestamp: message.timestamp,
			resultID: resultID,
			attackerID: combatArray.attackerID,
			attackerName: combatArray.attackerName,
			attackerOutfit: combatArray.attackerOutfit,
			attackerFaction: combatArray.attackerFaction,
			attackerLoadout: combatArray.attackerLoadout,
			victimID: combatArray.victimID,
			victimName: combatArray.victimName,
			victimOutfit: combatArray.victimOutfit,
			victimFaction: combatArray.victimFaction,
			victimLoadout: combatArray.victimLoadout,
			weaponID: combatArray.weaponID,
			vehicleID: combatArray.vehicleID,
			headshot: combatArray.headshot,
			zone: combatArray.zone,
			worldID: combatArray.worldID,
			teamkill: combatArray.teamkill,
			suicide: combatArray.suicide,
		}

		if (debug.combat == true)
		{
			console.log("Post array built");
			console.log(warning(JSON.stringify(postArray)));
		}

		pool.getConnection(function(poolErr, dbConnectionW)
		{
			if (poolErr)
			{
				throw(poolErr);
			}

			insertWeaponStats(message, resultID, combatArray, dbConnectionW);
			dbConnectionW.release();
		});

		pool.getConnection(function(poolErr, dbConnectionO)
		{
			if (poolErr)
			{
				throw(poolErr);
			}

			insertOutfitStats(message, resultID, combatArray, dbConnectionO);
			dbConnectionO.release();
		});

		pool.getConnection(function(poolErr, dbConnectionP)
		{
			if (poolErr)
			{
				throw(poolErr);
			}

			insertPlayerStats(message, resultID, combatArray, dbConnectionP);
			dbConnectionP.release();
		});

		pool.getConnection(function(poolErr, dbConnectionF)
		{
			if (poolErr)
			{
				throw(poolErr);
			}

			updateFactionStats(message, resultID, combatArray, dbConnectionF);
			dbConnectionF.release();
		});

		if (debug.combat == true)
		{
			console.log(success("PROCESSED KILL FOR PLAYER: "+message.attacker_character_name+" - "+worldNames[message.world_id]));
			console.log(notice("Player used weapon: "+message.weapon_id));
		}

		callback();
	}
	else
	{
		console.log(critical("NO VALID RESULT ID FOUND! - insertCombatRecord"));
	}
}

function insertWeaponStats(message, resultID, combatArray, dbConnectionW)
{
    var headshot = '';
    if (message.is_headshot == "1")
    {
        headshot = ', headshots=headshots+1';
    }

    var updateWeaponTotalsQuery = 'UPDATE ws_weapons_totals SET killCount=killCount+1'+headshot+' WHERE weaponID="'+message.weapon_id+'" AND resultID='+resultID;
    var updateWeaponPlayerQuery = 'UPDATE ws_weapons SET killCount=killCount+1'+headshot+' WHERE weaponID="'+message.weapon_id+'" AND playerID="'+message.attacker_character_id+'" AND resultID='+resultID;

    dbConnectionW.query(updateWeaponTotalsQuery, function(err, result)
    {
        if (err)
        {
            reportError(err, "Update Weapon Stats Totals");
            console.log(message.weapon_id);
            console.log(resultID);
            console.log(critical(updateWeaponTotalsQuery));
            throw(err);
        }
        else
        {
            var numTRows = result.affectedRows;

            if (numTRows == 0)
            {
                var weaponTArray = {
                    resultID: resultID,
                    weaponID: message.weapon_id,
                    killCount: 1,
                    headshots: message.is_headshot
                };

                dbConnectionW.query('INSERT INTO ws_weapons_totals SET ?', weaponTArray, function(err, result)
                {
                    if (err)
                    {
                        if (err.errno != 1062) // If not a duplicate
                        {
                            reportError(err, "Insert Weapon Stats Totals");
                            console.log(weaponTArray);
                            throw(err);

                        }
                        else // If a duplicate
                        {
                            if (debug == 1)
                            {
                                console.log(warning("DUPLICATE WEAPON TOTAL STAT DETECTED"));
                            }

                            handleDeadlock(updateWeaponTotalsQuery, "Weapons Totals", 0);
                        }
                    }
                });
            }

            dbConnectionW.query(updateWeaponPlayerQuery, function(err, result)
            {
                if (err)
                {
                    reportError(err, "Update Weapon Stats Killer");
                    throw(err);
                }
                else
                {
                    var numRows = result.affectedRows;

                    if (numRows == 0) // If new record
                    {
                        var weaponArray = {
                            resultID: resultID,
                            playerID: message.attacker_character_id,
                            weaponID: message.weapon_id,
                            killCount: 1,
                            headshots: message.is_headshot
                        };

                        if (debug == 1)
                        {
                            console.log(weaponArray);
                        }

                        dbConnectionW.query('INSERT INTO ws_weapons SET ?', weaponArray, function(err, result)
                        {
                            if (err)
                            {
                                if (err.errno != 1062) // If not a duplicate
                                {
                                    reportError(err, "Insert Weapon Stats");
                                    console.log(weaponArray);
                                    throw(err);
                                }
                                else // If a duplicate
                                {
                                    if (debug == 1)
                                    {
                                        console.log(warning("DUPLICATE WEAPON STAT DETECTED"));
                                    }

                                    handleDeadlock(updateWeaponPlayerQuery, "Weapons Insert", 0);
                                }
                            }
                        });
                    }
                }
            });
        }
    });
}

function insertOutfitStats(message, resultID, combatArray, dbConnectionO)
{
	var killOutfit = combatArray.attackerOutfit;
	var deathOutfit = combatArray.victimOutfit;
	var numRowsKills = 0;
	var numRowsDeaths = 0;
	var attackerID = combatArray.attackerID;
	var victimID = combatArray.victimID;
	var attackerFaction = combatArray.attackerFaction;
	var victimFaction = combatArray.victimFaction;
	var worldID = combatArray.worldID;

	var aTKs = 0;
	var aKills = 1;
	var aDeaths = 0;
	var aSuicides = 0;
	var aKillsQuery = "outfitKills=outfitKills+1";
	var aDeathsQuery = "";
	var aTKsQuery = "";
	var aSuicidesQuery = "";
	var vDeaths = 1;

	if (combatArray.teamkill == 1) // TK
	{
		aKills = 0;
		aKillsQuery = "";
		aTKsQuery = "outfitTKs=outfitTKs+1";
		aSuicidesQuery = "";

		vDeaths = 0;
	}

	if (combatArray.suicide == 1) // If a suicide
	{
		aKills = 0;
		aDeaths = 1;
		aSuicides = 1;

		aKillsQuery = ""
		aDeathsQuery = "outfitDeaths=outfitDeaths+1, ";
		aSuicidesQuery = "outfitSuicides=outfitSuicides+1";

		// Make these 0 so the outfit doesn't get "updated" twice.
		vTKs = 0;
		vKills = 0;
		vDeaths = 0;
		vSuicides = 0;
	}

	if (debug == 1)
	{
		console.log(critical('UPDATE ws_outfits SET '+aKillsQuery+''+aDeathsQuery+''+aTKsQuery+''+aSuicidesQuery+' WHERE outfitID="'+killOutfit+'" AND resultID='+resultID));
	}

	dbConnectionO.query('UPDATE ws_outfits SET '+aKillsQuery+''+aDeathsQuery+''+aTKsQuery+''+aSuicidesQuery+' WHERE outfitID="'+killOutfit+'" AND resultID='+resultID, function(err, resultA)
	{
		if (err)
		{
			reportError(err, "Update Outfit Kills");

			throw(err);
		}
		else
		{
			if (resultA.affectedRows == 0)
			{
				var outfitArrayKills = {
					resultID: resultID,
					outfitID: killOutfit,
					outfitName: combatArray.aOutfit.name,
					outfitTag: combatArray.aOutfit.tag,
					outfitFaction: combatArray.aOutfit.faction,
					outfitKills: aKills,
					outfitDeaths: aDeaths,
					outfitSuicides: aSuicides,
					outfitTKs: aTKs
				};

				if (debug == 1)
				{
					console.log(notice("Inserting First Kill for outfit "+killOutfit))
				}

				dbConnectionO.query('INSERT INTO ws_outfits SET ?', outfitArrayKills, function(err, result)
				{
					if (err)
					{
						if (err.errno != 1062) // If not a duplicate
						{
							reportError(err, "Insert Outfit Stats");
						}
						else // If a duplicate
						{
							if (debug == 1)
								console.log(warning("DUPLICATE KILL DETECTED. Waiting..."));

							handleDeadlock('UPDATE ws_outfits SET outfitKills=outfitKills+1 WHERE outfitID="'+killOutfit+'" AND resultID='+resultID, "Insert Outfits", 0);
						}
					}
				});
			}

			dbConnectionO.query('UPDATE ws_outfits_total SET '+aKillsQuery+''+aDeathsQuery+''+aTKsQuery+''+aSuicidesQuery+' WHERE outfitID="'+killOutfit+'"', function(err, resultB)
			{
				if(err)
				{
					throw(err)
				}

				if (resultB.affectedRows == 0) // If new record for Attacker
				{
					var outfitArrayKills = {
						outfitID: killOutfit,
						outfitName: combatArray.aOutfit.name,
						outfitTag: combatArray.aOutfit.tag,
						outfitFaction: combatArray.aOutfit.faction,
						outfitKills: aKills,
						outfitDeaths: aDeaths,
						outfitSuicides: aSuicides,
						outfitTKs: aTKs,
						outfitServer: worldID
					};

					dbConnectionO.query('INSERT INTO ws_outfits_total SET ?', outfitArrayKills, function(err, result)
					{
						if(err)
						{
							if (err.errno != 1062) // If not a duplicate
							{
								reportError(err, "Insert Initial Outfit Total Stats (Attacker)");
								throw(err);
							}
						}
					});
				}
			});

			if (killOutfit != deathOutfit) // If not the same outfit, don't count them twice.
			{
				dbConnectionO.query('UPDATE ws_outfits SET outfitDeaths=outfitDeaths+1 WHERE outfitID="'+deathOutfit+'" AND resultID='+resultID, function(err, resultV)
				{
					if (err)
					{
						reportError(err, "Update Outfit Deaths");
					}
					else
					{
						if (resultV.affectedRows == 0)
						{
							// PROCESSING FOR IF UPDATES FAILED (aka NEW RECORD)

							var outfitArrayDeaths = {
								resultID: resultID,
								outfitID: deathOutfit,
								outfitName: combatArray.vOutfit.name,
								outfitTag: combatArray.vOutfit.tag,
								outfitFaction: combatArray.vOutfit.faction,
								outfitKills: 0,
								outfitDeaths: 1,
								outfitSuicides: 0,
								outfitTKs: 0
							};

							if (debug == 1)
							{
								console.log(notice("Inserting First Death for outfit "+deathOutfit))
							}

							dbConnectionO.query('INSERT INTO ws_outfits SET ?', outfitArrayDeaths, function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										reportError(err, "Insert Outfit Initial Stats (Victim)");
									}
									else // If a duplicate
									{
										if (debug == 1)
											console.log(warning("DUPLICATE VICTIM OUTFIT DETECTED. Waiting..."));

										handleDeadlock('UPDATE ws_outfits SET outfitDeaths=outfitDeaths+1 WHERE outfitID="'+deathOutfit+'" AND resultID='+resultID, "Insert Outfit Victim", 0);
									}
								}
							});
						}
					}
				});

				dbConnectionO.query('UPDATE ws_outfits_total SET outfitDeaths=outfitDeaths+1 WHERE outfitID="'+deathOutfit+'"', function(err, resultB)
				{
					if(err)
					{
						throw(err)
					}

					if (resultB.affectedRows == 0) // If new record for Attacker
					{
						var outfitArrayDeaths = {
							outfitID: deathOutfit,
							outfitName: combatArray.vOutfit.name,
							outfitTag: combatArray.vOutfit.tag,
							outfitFaction: combatArray.vOutfit.faction,
							outfitKills: 0,
							outfitDeaths: 1,
							outfitSuicides: 0,
							outfitTKs: 0,
							outfitServer: worldID
						};

						dbConnectionO.query('INSERT INTO ws_outfits_total SET ?', outfitArrayDeaths, function(err, result)
						{
							if(err)
							{
								if (err.errno != 1062) // If not a duplicate
								{
									reportError(err, "Insert Initial Outfit Death Stats");
									throw(err);
								}
							}
						});
					}
				});
			}
		}
	});
}

function insertPlayerStats(message, resultID, combatArray, dbConnectionP)
{
	var attackerID = combatArray.attackerID;
	var victimID = combatArray.victimID;
	var attackerOutfit = combatArray.attackerOutfit;
	var victimOutfit = combatArray.victimOutfit;
	var attackerFID = combatArray.attackerFaction;
	var victimFID = combatArray.victimFaction;
	var attackerName = combatArray.attackerName;
	var victimName = combatArray.victimName;
	var timestamp = combatArray.timestamp;

	if (debug == 3)
	{
		console.log("InsertPlayerStats "+attackerID);
	}

	/* If the names are missing, resolve them manually */

	var teamKill = 0;
	var numRowsKills = 0;
	var numRowsDeaths = 0;
	var suicide = 0;

	var aKillQuery = 'playerKills=playerKills+1';
	var aDeathQuery = '';
	var aTKQuery = '';
	var aSuicideQuery = '';

	var vDeathQuery = 'playerDeaths=playerDeaths+1';

	if (combatArray.teamkill == 1) // If a TK
	{
		aKillQuery = '';
		teamKill = 1;
		aTKQuery = 'playerTeamKills=playerTeamKills+1';
		if (debug.combat == true)
		{
			console.log("TEAM KILL - Player");
		}
	}
	else if (combatArray.suicide == 1) // Is it a suicie?
	{
		aKillQuery = '';
		aDeathQuery = 'playerDeaths=playerDeaths+1, ';
		aSuicideQuery = 'playerSuicides=playerSuicides+1';
		vDeathQuery = '';
		suicide = 1;
		if (debug.combat == true)
		{
			console.log("SUICIDE - Player");
		}
	}

	var playerKills = 1;
	var playerDeaths = 0;

	if (teamKill == 1)
	{
		playerKills = 0;
	}

	if (suicide == 1)
	{
		playerDeaths = 1;
		playerKills = 0;
	}

	if (debug == 1)
	{
		console.log(critical('UPDATE ws_players SET '+aKillQuery+''+aDeathQuery+''+aSuicideQuery+''+aTKQuery+' WHERE playerID="'+attackerID+'" AND resultID='+resultID));
	}

	dbConnectionP.query('UPDATE ws_players SET '+aKillQuery+''+aDeathQuery+''+aSuicideQuery+''+aTKQuery+' WHERE playerID="'+attackerID+'" AND resultID='+resultID, function(err, resultA)
	{
		if (err)
		{
			reportError(err, "Update Player Kills");
			throw(err);
		}
		else
		{
			if (resultA.affectedRows == 0) // If new record for Attacker
			{
				var playerArrayKills = {
					resultID: resultID,
					playerID: attackerID,
					playerName: attackerName,
					playerOutfit: attackerOutfit,
					playerFaction: attackerFID,
					playerKills: playerKills,
					playerDeaths: playerDeaths,
					playerTeamKills: teamKill,
					playerSuicides: suicide,
				};

				dbConnectionP.query('INSERT INTO ws_players SET ?', playerArrayKills, function(err, result)
				{
					if (err)
					{
						if (err.errno != 1062) // If not a duplicate
						{
							reportError(err, "Insert Initial Player Kill Stats");
							throw(err);
						}
						else // If a duplicate
						{
							if (debug == 1)
							{
								console.log(warning("DUPLICATED PLAYER DEATH RECORD DETECTED"));
								reportError(err, "Insert Players Attacker Duplicated");
							}

							handleDeadlock('UPDATE ws_players SET '+aKillQuery+''+aDeathQuery+''+aSuicideQuery+''+aTKQuery+' WHERE playerID="'+attackerID+'" AND resultID='+resultID, "Insert Attacker", 0);
						}
					}
				});
			}

			dbConnectionP.query('UPDATE ws_players_total SET '+aKillQuery+''+aDeathQuery+''+aSuicideQuery+''+aTKQuery+' WHERE playerID="'+attackerID+'"', function(err, resultB)
			{
				if(err)
				{
					throw(err)
				}

				if (resultB.affectedRows == 0) // If new record for Attacker
				{
					var playerArrayTotal = {
						playerID: attackerID,
						playerName: attackerName,
						playerOutfit: attackerOutfit,
						playerFaction: attackerFID,
						playerKills: playerKills,
						playerDeaths: playerDeaths,
						playerTeamKills: teamKill,
						playerSuicides: suicide,
					};

					dbConnectionP.query('INSERT INTO ws_players_total SET ?', playerArrayTotal, function(err, result)
					{
						if(err)
						{
							if (err.errno != 1062) // If not a duplicate
							{
								reportError(err, "Insert Initial Player Total Stats (Attacker)");
								throw(err);
							}
						}
					});
				}
			});

			if (attackerID != victimID) // Don't count them twice!
			{
				if (debug == 1)
				{
					console.log(critical('UPDATE ws_players SET '+vDeathQuery+' WHERE playerID="'+victimID+'" AND resultID='+resultID));
				}

				dbConnectionP.query('UPDATE ws_players SET '+vDeathQuery+' WHERE playerID="'+victimID+'" AND resultID='+resultID, function(err, resultR)
				{
					if (err)
					{
						reportError(err, "Update Player Deaths");
						throw(err);
					}
					else
					{
						// PROCESSING FOR IF UPDATES FAILED (aka NEW RECORD)

						if (resultR.affectedRows == 0) // If new record for Victim
						{
							var playerArrayDeaths = {
								resultID: resultID,
								playerID: victimID,
								playerOutfit: victimOutfit,
								playerName: victimName,
								playerFaction: victimFID,
								playerKills: 0,
								playerDeaths: 1,
								playerTeamKills: 0,
								playerSuicides: 0,
							};

							dbConnectionP.query('INSERT INTO ws_players SET ?', playerArrayDeaths, function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										reportError(err, "Insert Initial Player Death Stats");
										throw(err);
									}
									else // If a duplicate
									{
										if (debug == 1)
										{
											console.log(warning("DUPLICATED PLAYER DEATH RECORD DETECTED"));
										}

										handleDeadlock('UPDATE ws_players SET '+vDeathQuery+' WHERE playerID="'+victimID+'" AND resultID='+resultID, "Insert Player Death", 0);
									}
								}
							});
						}
					}
				});

				dbConnectionP.query('UPDATE ws_players_total SET '+vDeathQuery+' WHERE playerID="'+victimID+'"', function(err, resultC)
				{
					if(err)
					{
						throw(err)
					}

					if (resultC.affectedRows == 0) // If new record for Attacker
					{
						var playerArrayTotal = {
							playerID: victimID,
							playerName: victimName,
							playerOutfit: victimOutfit,
							playerFaction: attackerFID,
							playerKills: 0,
							playerDeaths: 1,
							playerTeamKills: 0,
							playerSuicides: 0,
						};

						dbConnectionP.query('INSERT INTO ws_players_total SET ?', playerArrayTotal, function(err, result)
						{
							if(err)
							{
								if (err.errno != 1062) // If not a duplicate
								{
									reportError(err, "Insert Initial Player Total Death Stats");
									throw(err);
								}
							}
						});
					}
				});
			}
			else
			{
				if (debug == 3)
				{
					console.log("Attacker and Victim IDs are the same.");
				}
			}
		}
	});
}

function updateFactionStats(message, resultID, combatArray, dbConnectionF)
{
	var killerID = combatArray.attackerID;
	var victimID = combatArray.victimID;
	var killerFID = combatArray.attackerFaction;
	var victimFID = combatArray.victimFaction;
	var killerName = combatArray.attackerName;
	var victimName = combatArray.victimName;

	/* If the names are missing, resolve them manually */

	if (killerFID == "1")
	{
		kFaction = "VS";
	}
	else if (killerFID == "2")
	{
		kFaction = "NC";
	}
	else if (killerFID == "3")
	{
		kFaction = "TR";
	}

	if (victimFID == "1")
	{
		vFaction = "VS";
	}
	else if (victimFID == "2")
	{
		vFaction = "NC";
	}
	else if (victimFID == "3")
	{
		vFaction = "TR";
	}

	if (combatArray.teamkill == "1") // If a TK
	{
		kField = 'teamKills'+kFaction;
		tField = 'totalTKs';

		if (debug.combat == true)
		{
			console.log(critical("TK"));
		}
	}
	else if (combatArray.suicide == "1") // Is it a suicie?
	{
		if (killerFID == "0") // If the faction is missing, use the victim
		{
			kFaction = vFaction;
		}

		kField = 'suicides'+kFaction;
		tField = 'totalSuicides';

		if (debug.combat == true)
		{
			console.log(warning("SUICIDE"));
		}
	}
	else // Must be a kill then
	{
		kField = 'kills'+kFaction;
		tField = 'totalKills';

		if (debug.combat == true)
		{
			console.log(success("KILL"));
		}
	}

	if (debug.combat == true)
	{
		console.log(kFaction);
		console.log(vFaction);
		console.log("----");
	}

	dField = 'deaths'+vFaction;

	dbConnectionF.query('UPDATE ws_factions SET '+kField+'='+kField+'+1, '+dField+'='+dField+'+1, '+tField+'='+tField+'+1, totalDeaths=totalDeaths+1 WHERE resultID='+resultID, function(err, result)
	{
		if (err)
		{
			reportError(err, "Update Faction Record (#"+resultID+")");
			throw(err);
		}
		else if (result.affectedRows == 0) // If missing record
		{
			if (debug == 1)
			{
				console.log(notice("INSERTING FACTION RECORD"));
			}

			var factionArray =
			{
				resultID: resultID,
				killsVS: 0,
				killsNC: 0,
				killsTR: 0,
				deathsVS: 0,
				deathsNC: 0,
				deathsTR: 0,
				teamKillsVS: 0,
				teamKillsNC: 0,
				teamKillsTR: 0,
				suicidesVS: 0,
				suicidesNC: 0,
				suicidesTR: 0,
				totalKills: 0,
				totalDeaths: 0,
				totalTKs: 0,
				totalSuicides: 0
			}

			dbConnectionF.query('INSERT INTO ws_factions SET ?', factionArray, function(err, result)
			{
				if (err)
				{
					reportError(err, "Insert Faction Stats record (#"+resultID+")");
					var returned = false;
				}
				else // Fire the query that was going to happen
				{
					dbConnectionF.query('UPDATE ws_factions SET '+kField+'='+kField+'+1, '+dField+'='+dField+'+1, '+tField+'='+tField+'+1, totalDeaths=totalDeaths+1 WHERE resultID='+resultID);
				}
			});
		}
	});
}

/* IDs
1 = Flash
2 = Sunderer
3 = Lightning
4 = Magrider
5 = Vanguard
6 = Prowler
7 = Scythe
8 = Reaver
9 = Mozie
10 = Liberator
11 = Galaxy
12 = Harasser
13 = Drop pod
14 = Valkrye

100 = AI Base Turret
127 = AA Base Turret

101 = AI Mana Turret
102 = AV Mana Turret

150 = AA Base Turret (non tower)
151 = AV Base Turret

1012 = Phoenix Missle
*/

var vehNanite = {
	1: 50,
	2: 200,
	3: 350,
	4: 450,
	5: 450,
	6: 450,
	7: 350,
	8: 350,
	9: 350,
	10: 450,
	11: 450,
	12: 150,
	14: 250,
	101: 0,
	102: 0,
	127: 0,
	150: 0,
	151: 0,
	152: 0
};

function insertVehicleStats(message, resultID, combat, callback)
{
	if(combat == 0) // If  a combat message, ignore this shizzle.
	{
		var killerID = message.attacker_character_id;
		var victimID = message.victim_character_id;

		var killerVID = message.attacker_vehicle_id;
		var victimVID = message.victim_vehicle_id;

		addKillMonitor(killerID, victimID, "vKill", message.timestamp, killerVID, victimVID, resultID, null, null);
	}
}

// =================== SERVER TO CLIENT INTERFACES ===============================

var clientConnections = {}; //Stores all connections to this server, and their subscribed events.
var connectionIDCounter = 0; //Connection Unique ID's.

var clientAdminConnections = {}; //Stores all connections to this server, and their subscribed events.
var clientAdminPerfConnections = {}; //Stores all connections to this server, and their subscribed events.

var resultSubscriptions = {}; // Stores all connections on a per-alert basis

var WebSocketServer = require('ws').Server;
var wss = new WebSocketServer(
{
	port: 1338,
	clientTracking: false, //We do our own tracking.
});

wss.on('connection', function(clientConnection)
{
	if (debug.clients === true)
	{
		console.log("Processing incoming connection");
	}

	var apiKey = url.parse(clientConnection.upgradeReq.url, true).query.apikey;

	checkAPIKey(apiKey, function(isValid, username, admin)
	{
		if (debug.auth === true)
		{
			console.log("API Check Result: "+isValid);
		}

		if(isValid)
		{
			// Store a reference to the connection using an incrementing ID
			clientConnection.id = connectionIDCounter++;

			//Add to tracked client connections.
			clientConnections[clientConnection.id] = clientConnection;

			var message =
			{
				state: connectionState,
				admin: admin,
				response: 'auth'
			}

			clientConnection.send(JSON.stringify(message));

			if (debug.clients == true)
			{
				console.log(success("Websocket Connected - TOTAL: "+Object.keys(clientConnections).length));
				console.log((new Date()) + ' User ' + username + ' connected. API Key: ' + apiKey);
			}

			if (admin == true)
			{
				if (debug.admin == true)
				{
					console.log(success("Admin successfuly authenticated!"));
				}

				clientAdminConnections[clientConnection.id] = clientConnection; // Subscribe admin to admin object
			}

			clientConnection.on('message', function(message)
			{
				try // Check if the message we get is valid json.
				{
					var message = JSON.parse(message); //Messages Received From Census are Formated in JSON. Parse it, and you'll be able to access the data the same as a JSON object.
					message = message.payload;
				}
				catch(exception)
				{
					console.log(message);
					console.log(critical("INVALID JSON RECIEVED"));

					clientConnection.send('{"response":"Invalid Input"}');
					message = null;
				}

				if (message) // If valid
				{
					if (message.action == "subscribe") // Subscribe to result
					{
						var resultID = message.resultID;

						if (!resultSubscriptions[resultID])
						{
							resultSubscriptions[resultID] = {};

							resultSubscriptions[resultID][clientConnection.id] = clientConnection;
						}
						else
						{
							resultSubscriptions[resultID][clientConnection.id] = clientConnection; // Put connection based on resultID into object to loop through
						}

						if (debug.clients == true)
						{
							console.log(success("SUBSCRIBED WEBSOCKET TO ALERT #"+resultID));
						}

						clientConnection.send('{"response":"Subscribed"}');
					}
					else if (message.action == "timesync")
					{
						var clientTime = message.time;
						var resultID = message.resultID;
						var mode = message.mode;

						if (debug.time === true)
						{
							console.log(notice("Time message recieved:"));
							console.log(message);
						}

						if (instances[resultID]) // On first load stuff, prevent crash
						{
							if (debug.time === true)
							{
								console.log(critical("REQUESTED INSTANCE:"));
								console.log(instances[resultID]);
							}

							var serverTime = new Date().getTime();
							serverTime = Math.floor(serverTime / 1000);

							if (debug.time === true)
							{
								console.log("SERVER TIME: "+serverTime);
							}

							var diff = ( parseInt(clientTime) - parseInt(serverTime) );

							if (mode == "start")
							{
								var remaining = parseInt(instances[resultID].startTime) - serverTime;
							}
							else if (mode == "end")
							{
								var remaining = parseInt(instances[resultID].endTime) - serverTime;
							}
							else if (mode === undefined)
							{
								var remaining = "MODE NOT SELECTED!";
							}

							//console.log(notice("Recieved Timesync message"));

							clientConnection.send('{"response":"time", "serverTime": '+serverTime+', "clientTime": '+clientTime+', "remaining": '+remaining+', "timediff":'+diff+'}');

							if (debug.time === true)
							{
								console.log(success("SENDING TIME MESSAGE"));
							}
						}
						else
						{
							if (debug.time === true)
							{
								console.log(critical("SENDING TIMESYNC WAIT MESSAGE"));
							}

							clientConnection.send('{"response":"timeWait"}');
						}
					}
					else if (message.action == "alertStatus") // First call for the monitor
					{
						var messageToSendMonitor = {};

						messageToSendMonitor.messageType = "alertStatus";

						var activeAlertsReply = {};

						var serverTime = new Date().getTime();
						serverTime = Math.floor(serverTime / 1000);

						Object.keys(instances).forEach(function(key)
						{
							if(instances[key].status == true) // If theres an active alert
							{
								if (!activeAlertsReply[world])
								{
									activeAlertsReply[world] = {};
								}

								activeAlertsReply[world][zone] = {};
								activeAlertsReply[world][zone] = instances[key];

								var remaining = parseInt(instances[key].endTime) - serverTime;

								activeAlertsReply[world][zone].remaining  = remaining;
								activeAlertsReply[world][zone].serverTime = serverTime;
							}
						});

						messageToSendMonitor.data = activeAlertsReply;

						clientConnection.send(JSON.stringify(messageToSendMonitor));

						if (debug.clients == true)
						{
							console.log(messageToSendMonitor);
							console.log("SENT WEBSOCKET MONITOR CURRENT STATUS");
						}
					}

					/*else if (message.action == "unsubscribe") // unSubscribe from result
					{
						console.log(resultSubscriptions);
						var resultID = message.resultID;

						if (resultSubscriptions[resultID][clientConnection.id])
						{
							delete resultSubscriptions[resultID][clientConnection.id]; // Put connection based on resultID into object to loop through
						}

						console.log(success("UNSUBSCRIBED WEBSOCKET FROM ALERT #"+resultID));

						clientConnection.send('{"response":"Unsubscribed"}');
					}*/

					// ADMIN FUNCTIONS //

					else if (message.type == "subscribePerf" && admin == true) // Admin functions
					{
						clientAdminPerfConnections[clientConnection.id] = clientConnection;
					}
					else if (message.type == "reloadPages" && admin == true)
					{
						var resultID = message.resultID;

						sendResult("reload", "reload", resultID);
					}

					// End of message actions
				}
			}); // End of clientOnMessage
		} // End of API Key if
		else // If API key is not valid or not authorised
		{
			if (apiKey != undefined)
			{
				console.log(critical("UNAUTHORISED API KEY ATTEMPT! "+apiKey));
				console.log(critical(JSON.stringify(message)));
			}
			else
			{
				if (debug.auth == true)
				{
					console.log(critical("INVALID API KEY FORMAT DETECTED."));
					console.log(critical("API KEY: "+apiKey));
				}
			}

			clientConnection.close();
		}

		clientConnection.on('close', function(code, message)
		{
			delete clientConnections[clientConnection.id];

			if (clientAdminConnections[clientConnection.id])
			{
				delete clientAdminConnections[clientConnection.id];
			}

			if (apiKey != undefined)
			{
				console.log(notice("Websocket connection closed - Total: "+Object.keys(clientConnections).length));
			}
		});
	});	// End of check API key
});

function sendResult(messageType, message, resultID) // Sends message to WS Clients
{
	var messageToSend = {};

	if (debug.responses == true)
	{
		console.log(notice("STARTING RESULT SEND"));

	}

	if (message) // If Valid
	{
		messageToSend.data = message;
		messageToSend.messageType = messageType;

		if (debug.responses == true)
		{
			console.log("WEBSOCKET TO RESULT #"+resultID+" MESSAGE:");
			console.log(messageToSend);
		}

		if (resultSubscriptions[resultID]) // If script was too quick for subscription
		{
			Object.keys(resultSubscriptions[resultID]).forEach(function(key)
			{
				var clientConnection = resultSubscriptions[resultID][key];

				clientConnection.send(JSON.stringify(messageToSend), function(error)
				{
					if (error)
					{
						delete clientConnections[clientConnection.id];
						delete resultSubscriptions[resultID][clientConnection.id];

						console.log(notice("Websocket connection closed - Total: "+Object.keys(clientConnections).length));
						console.log(critical("Websocket Error: "+error));
					}
				});
			});
		}

		if (debug == 1 && messageType != "keepalive")
		{
			console.log(notice("Message Sent to Result Websockets"));
		}
	}
}

function sendMonitor(messageType, message) // Sends message to WS Clients
{
	var messageToSend = {};

	if (message) // If Valid
	{
		messageToSend.data = message;
		messageToSend.messageType = messageType;

		if (debug.clients == true)
		{
			console.log("WEBSOCKET MESSAGE:");
			console.log(messageToSend);
		}

		if ((messageType == "alertStart") || (messageType == "alertEnd") || (messageType == "update")) // Send to monitor
		{
			Object.keys(clientConnections).forEach(function(key)
			{
				var clientConnection = clientConnections[key];

				clientConnection.send(JSON.stringify(messageToSend), function(error)
				{
					if (error)
					{
						delete clientConnections[clientConnection.id];

						console.log(critical("Websocket Monitor Error: "+error));
					}
				});
			});
		}

		if (debug == 1 && messageType != "keepalive")
		{
			console.log(notice("Message Sent to Monitor Websockets"));
		}
	}
}

function sendAdmins(messageType, message) // Sends message to WS Clients
{
	var messageToSend = {};

	if (message) // If Valid
	{
		messageToSend.data = message;
		messageToSend.messageType = messageType;

		if (debug.clients == true && messageType != "perf")
		{
			console.log("WEBSOCKET MESSAGE:");
			console.log(messageToSend);
		}

		if (messageType == "perf") // Send only to perf subs
		{
			Object.keys(clientAdminPerfConnections).forEach(function(key)
			{
				var clientConnection = clientAdminPerfConnections[key];

				clientConnection.send(JSON.stringify(messageToSend), function(error)
				{
					if (error)
					{
						console.log(critical("Websocket Admin Error: "+error));
						delete clientAdminPerfConnections[clientConnection.id];
					}
				});
			});
		}
		else
		{
			Object.keys(clientAdminConnections).forEach(function(key)
			{
				var clientConnection = clientAdminConnections[key];

				clientConnection.send(JSON.stringify(messageToSend), function(error)
				{
					if (error)
					{
						console.log(critical("Websocket Admin Error: "+error));
						delete clientAdminConnections[clientConnection.id];
					}
				});
			});
		}

		if (debug.clients == true && messageType != "perf" && messageType != "keepalive")
		{
			console.log(notice("Message Sent to Admin Websockets"));
		}
	}
}

setInterval(function()
{
	var message = "ping!";

	sendAll("keepalive", message)
}, 5000);

function sendAll(messageType, message) // Sends message to WS Clients
{
	var messageToSend = {};

	if (message) // If Valid
	{
		messageToSend.data = message;
		messageToSend.messageType = messageType;

		Object.keys(clientConnections).forEach(function(key)
		{
			var clientConnection = clientConnections[key];

			clientConnection.send(JSON.stringify(messageToSend), function(error)
			{
				if (error)
				{
					console.log(critical("Websocket Error: "+error));
					delete clientConnections[clientConnection.id];
				}
			});
		});

		if (debug.clients == true && messageType != "keepalive")
		{
			console.log(notice("Message Sent to All Websockets"));
			console.log(messageType);
		}
	}
}

function sendRecursion(messageType, message, resultID)
{
	var messageToSend = {};

	if (message) // If Valid
	{
		messageToSend.data = message;
		messageToSend.messageType = messageType;

		if (debug == 1)
		{
			console.log("WEBSOCKET MESSAGE:");
			console.log(messageToSend);
		}

		if ((messageType == "alertStart") || (messageType == "alertEnd") || (messageType == "update")) // Send to monitor
		{
			Object.keys(clientConnections).forEach(function(key)
			{
				var clientConnection = clientConnections[key];

				clientConnection.send(JSON.stringify(messageToSend), function(error)
				{
					if (error)
					{
						delete clientConnections[clientConnection.id];

						console.log(critical("Websocket Monitor Error: "+error));
					}
				});
			});
		}

		if (debug.clients == true)
		{
			console.log(notice("Message Sent to Recursion clients"));
		}
	}
}

function DateCalc(d)
{
	var year, month, day, hour, minute, seconds;

	year = String(d.getFullYear());
	month = String(d.getUTCMonth() + 1);
	hour = String(d.getUTCHours());
	minute = String(d.getUTCMinutes());
	seconds = String(d.getUTCSeconds());

	if (month.length == 1) {
		month = "0" + month;
	}
	day = String(d.getDate());
	if (day.length == 1) {
		day = "0" + day;
	}
	if (hour.length == 1) // If needing a preceeding 0
	{
		hour = "0"+hour;
	}
	if (minute.length == 1)
	{
		minute = "0"+minute;
	}
	if (seconds.length == 1)
	{
		seconds = "0"+seconds;
	}
	return year+"-"+month+"-"+day+" "+hour+":"+minute+":"+seconds;
}

function findPlayerName(playerID, callback)
{
	var url = 'http://census.daybreakgames.com/s:planetside2alertstats/get/ps2:v2/character/?character_id='+playerID;

	if (debug == 1)
	{
		console.log("========== FINDING PLAYER NAME =========");
		console.log("INPUT :"+playerID);
	}

	http.get(url, function(res) {
		var body = '';

		res.on('data', function(chunk) {
			body += chunk;
		});

		res.on('end', function() {

			var success = 1;

			try
			{
				var returned = JSON.parse(body);
			}
			catch(exception)
			{
				console.log(critical("BAD RETURN FROM CENSUS - Player Cache"));
                console.log(url);
                console.log(body);
				success = 0;
			}

			if (success == 1)
			{
				var valid = returned.returned;

				if (valid == 1)
				{
					if (debug == 1)
					{
						console.log("RESPONSE: "+returned.character_list);
						console.log("INPUT :"+playerID);
					}

					var name = returned.character_list[0].name.first;
					var faction = returned.character_list[0].faction_id;

					callback(name, faction);
				}
				else
				{
					if (debug == 1)
                        console.log(warning("FAILED TO GET PLAYER NAME!"));

                    callback(false, false);
				}
			}
		});
	}).on('error', function(e)
	{
		  console.log("Got error: ", e);
	});
}

function findOutfitName(outfitID, callback)
{
	if((outfitID == "-1") || (outfitID == "0"))
	{
		return "";
	}
	var url = 'http://census.daybreakgames.com/s:planetside2alertstats/get/ps2:v2/outfit/?outfit_id='+outfitID;

	if (debug == 1)
	{
		console.log("========== FINDING OUTFIT NAME =========");
		console.log("INPUT :"+outfitID);
	}

	http.get(url, function(res) {
		var body = '';

		res.on('data', function(chunk) {
			body += chunk;
		});

		res.on('end', function() {

			var success = 1;

			try
			{
				var returned = JSON.parse(body);
			}
			catch(exception)
			{
                console.log(critical("BAD RETURN FROM CENSUS - Outfit Cache"));
				success = 0;
			}

			if (success == 1)
			{
				var valid = returned.returned;

				if (valid == 1)
				{
					if (debug == 1)
					{
						console.log("RESPONSE: "+returned.outfit_list);
						console.log("INPUT :"+outfitID);
					}

					var name = returned.outfit_list[0].name;
					var tag = returned.outfit_list[0].alias;
					var leader = returned.outfit_list[0].leader_character_id;

					callback(name, tag, leader);
				}
				else
				{
                    console.log(warning("FAILED TO GET OUTFIT NAME!"));

                    callback(false, false, false);
				}
			}
		});
	}).on('error', function(e) {
		  console.log("Got error: ", e);
	});
}

function checkPlayerCache(playerID, dbConnectionCache, callback)
{
	dbConnectionCache.query('SELECT * FROM player_cache WHERE playerID="'+playerID+'"', function(err, result)
	{
		if (err)
		{
			throw err;
		}
		else
		{
			if (debug == 1)
			{
				console.log(notice("PLAYER CACHE RESULT: " + JSON.stringify(result[0])));
			}

			if (!result[0]) // If empty
			{
				findPlayerName(playerID, function(name, faction)
				{
                    if (name !== false && faction !== false)
                    {
					var insertPArray =
					{
						playerID: playerID,
						playerName: name,
						playerFaction: faction
					};

					dbConnectionCache.query('INSERT INTO player_cache SET ?', insertPArray, function(err, result)
					{
						if (err)
						{
							if (err.errno != 1062) // If not a duplicate
							{
								console.log(message);
								reportError(err, "Insert Player Cache Record");
								throw(err)
							}
							else
							{
								if (debug == 1)
								{
									console.log(warning("INVALID / DUPLICATED PLAYER CACHE RECORD DETECTED! Skipping!"));
								}
							}
						}
						else
						{
							if (debug == 1)
							{
								console.log(success("INSERTED PLAYER RECORD INTO CACHE TABLE"));
							}

							dbConnectionCache.query('UPDATE cache_hits SET cacheMisses=cacheMisses+1 WHERE dataType="PlayerCache"');
							callback(name);
						}
					});
                    }
                    else
                    {
                        console.log(critical("CENSUS PLAYER QUERY FAILED!"));
                        callback(false);
                    }
				});
			}
			else if (result[0])
			{
				if (debug == 1)
				{
					console.log(success("PLAYER CACHE HIT!"));
				}

				dbConnectionCache.query('UPDATE cache_hits SET cacheHits=CacheHits+1 WHERE dataType="PlayerCache"');
				callback(result[0].playerName);
			}
		}
	});
}

function checkOutfitCache(outfitID, dbConnectionCache, callback)
{
	if (debug == 1)
	{
		console.log(critical("OUTFIT ID: "+outfitID));
	}

	if ((outfitID == -1) || (outfitID == 0))
	{
		if (debug == 1)
		{
			console.log(critical("IGNORING OUTFIT PROCESSING"));
		}

		callback(undefined, undefined, undefined, undefined);
	}
	else
	{
		dbConnectionCache.query('SELECT * FROM outfit_cache WHERE outfitID="'+outfitID+'"', function(err, result)
		{
			if (err)
			{
				throw err;
			}
			else
			{
				if (debug == 1)
				{
					console.log(notice("OUTFIT CACHE RESULT: " + JSON.stringify(result[0])));
				}

				if (!result[0]) // If empty
				{
					findOutfitName(outfitID, function(outfitName, tag, leaderID)
					{
						if (debug == 1)
						{
							console.log("FOUND OUTFIT NAME");
						}

                        if (outfitName === false)
                        {
                            callback (undefined, undefined, undefined, undefined);
                        }

						findPlayerName(leaderID, function(name, faction)
						{
							if (debug == 1)
							{
								console.log("FOUND PLAYER NAME");
							}

							var insertOArray =
							{
								outfitName: outfitName,
								outfitTag: tag,
								outfitFaction: faction,
								outfitID: outfitID
							};
							dbConnectionCache.query('INSERT INTO outfit_cache SET ?', insertOArray, function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										console.log(message);
										reportError(err, "Insert Outfit Cache Record");
										throw(err);
									}
									else
									{
										if (debug == 1)
										{
											console.log(warning("INVALID / DUPLICATED OUTFIT CACHE RECORD DETECTED! Skipping!"));
										}
									}
								}
								else
								{
									if (debug == 1)
									{
										console.log(success("INSERTED OUTFIT RECORD INTO CACHE TABLE"));
									}

									dbConnectionCache.query('UPDATE cache_hits SET cacheMisses=cacheMisses+1 WHERE dataType="OutfitCache"')

									callback(outfitName, tag, faction, outfitID);
								}
							});
						});
					});
				}
				else
				{
					dbConnectionCache.query('UPDATE cache_hits SET cacheHits=cacheHits+1 WHERE dataType="OutfitCache"');

					if (debug == 1)
					{
						console.log(success("OUTFIT CACHE HIT!"));
					}

					callback(result[0].outfitName, result[0].outfitTag, result[0].outfitFaction, result[0].outfitID);
				}
			}
		});
	}
}

var factions = [];
factions[0] = "VS";
factions[1] = "NC";
factions[2] = "TR";

function calcWinners(message, resultID, Lresult, Fresult, dbConnection, callback)
{
	dbConnection.query("SELECT * FROM ws_results WHERE resultID="+resultID, function(error, result)
	{
		if (!result[0]) // If record is empty
		{
			throw ("NO RESULT RECORD COULD BE FOUND! FOR ALERT #"+resultID);
		}
		var attackers = result[0]["ResultStarter"];

		var eventID = result[0]["ResultAlertType"];
		var top = 0;
		var winner = "TO CALC";
		var draw = 0;
		var domination = 0;

		var empires = [];

		empires[0] = Lresult[0].controlVS;
		empires[1] = Lresult[0].controlNC;
		empires[2] = Lresult[0].controlTR;

		for (var i = empires.length - 1; i >= 0; i--) { // Sort empires into result order
			if (empires[i] > top)
			{
				top = empires[i];
				winner = factions[i];
			}
		};

		console.log(success("WINNER = "+winner));

		empires.sort(function(a, b){return b-a});

		switch(eventID) // Logic for calculating alert winner scenarios (domination etc)
		{
			// Adversarial Alerts
			case '31':
			case '32':
			case '33':
			case '34':
			{
				var DomThreshold = 65;
				var AttWinThreshold = 50;
				var DefWinThreshold = 50;
				var WinThreshold = 50;
				var Fresult = [];

				Fresult[1] = Lresult[0].controlVS;
				Fresult[2] = Lresult[0].controlNC;
				Fresult[3] = Lresult[0].controlTR;

				console.log("F RESULT: "+Fresult);
				console.log("ATTACKERS: "+attackers);
				console.log("ATTACKERS %:" +Fresult[attackers]);

				if(Fresult[attackers] >= DomThreshold) // If attackers maintain higher than 65%
				{
					winner = factions[attackers];
					domination = 1;
				}
				else
				{
					var top = empires[0];

					if (top >= WinThreshold)
					{
						for (var i = 0; i < Fresult.length; i++) {
							if (Fresult[i] == top)
							{
								winner = factions[i];
							}
						};
					}
					else if (empires[0] == empires[1]) // If Draw
					{
						winner = "DRAW";
						draw = 1;
						console.log("DRAW!");
					}
				}

				callback(winner, draw, domination);
				break;
			}
			// Territory Alerts
			case '1':
			case '2':
			case '3':
			case '4':
			{
				var top = empires[0];

				if(message.domination == 1) // If domination
				{
					domination = 1;
					console.log("DOMINATION");
				}
				else if (empires[0] == empires[1])
				{
					winner = "DRAW";
					draw = 1;
					console.log("DRAW!");
				}

				// Otherwise, pick the winner as determined above the switch.

				break;
			}
		}

		console.log("WINNER IS: "+winner);

		callback(winner, draw, domination);
	});
}

/* Helper Functions */

function APIAlertTypes(eventID, callback)
{
	var type = null;
	var cont = null;

	switch (eventID)
	{
		case '1':
			type = "Territory";
			cont = "Indar";
			break;
		case '2':
			type = "Territory";
			cont = "Esamir";
			break;
		case '3':
			type = "Territory";
			cont = "Amerish";
			break;
		case '4':
			type = "Territory";
			cont = "Hossin";
			break;
		case '5':
			type = "ERROR";
			cont = "ERROR";
			break;
		case '6':
			type = "ERROR";
			cont = "ERROR";
			break;
		case '7':
			type = "Bio";
			cont = "Amerish";
			break;
		case '8':
			type = "Tech";
			cont = "Amerish";
			break;
		case '9':
			type = "Amp";
			cont = "Amerish";
			break;
		case '10':
			type = "Bio";
			cont = "Indar";
			break;
		case '11':
			type = "Tech";
			cont = "Indar";
			break;
		case '12':
			type = "Amp";
			cont = "Indar";
			break;
		case '13':
			type = "Bio";
			cont = "Esamir";
			break;
		case '14':
			type = "Amp";
			cont = "Esamir";
			break;
		case '15':
			type = "Bio";
			cont = "Hossin";
			break;
		case '16':
			type = "Tech";
			cont= "Hossin";
			break;
		case '17':
			type = "Amp";
			cont = "Hossin";
			break;
		case '31':
			type = "Territory";
			cont = "Indar";
			break;
		case '32':
			type = "Territory";
			cont = "Esamir";
			break;
		case '33':
			type = "Territory";
			cont = "Amerish";
			break;
		case '34':
			type = "Territory";
			cont = "Hossin";
			break;
	}

	if ((type != null) && (cont != null)) // If valid
	{
		var result =
		{
			type: type,
			cont: cont
		};
	}
	else
	{
		var result = null;
	}

	callback(result);
}

var charFlags = {};
var charIDs = [];

function addKillMonitor(charID, vCharID, flag, timestamp, killerVID, victimVID, resultID, attName, vicName, dbConnection)
{
	if (!attName)
	{
		attName = false;
	}
	if (!vicName)
	{
		vicName = false;
	}

	if (!charFlags[charID])
	{
		if (debug == 2)
		{
			console.log(critical("CHARFLAGS OBJECT DOESNT EXIST, ADDING!"));
		}

		var push = {
			"charID": charID,
			"vCharID": vCharID,
			"timestamp": timestamp,
			"vKill": 0,
			"kill": 0,
			"killerVID": 0,
			"victimVID": 0,
			"resultID": resultID,
			"aName": attName,
			"vName": vicName
		};

		charFlags[charID] = push;

		if (charIDs != undefined)
		{
			charIDs.push(charID);
		}
		else
		{
			charIDs = charID;
		}
	}

	if (flag == "vKill")
	{

		if (charFlags[charID].vKill == 0)
		{
			charFlags[charID].killerVID = killerVID;
			charFlags[charID].victimVID = victimVID;
			charFlags[charID].vKill = 1;
		}
	}
	else if (flag == "kill")
	{
		if (charFlags[charID].kill == 0)
		{
			charFlags[charID].kill = 1;
			charFlags[charID].killerVID = killerVID;
		}
	}

	if ((attName != false) && (vicName != false))
	{
		charFlags[charID].aName = attName;
		charFlags[charID].vName = vicName;
	}

	if (debug == 2)
	{
		console.log(charFlags[charID]);
	}

}

setInterval(function()
{
	for (var i = charIDs.length - 1; i >= 0; i--) // Loop through all of the monitored characters
	{
		var charID = charIDs[i];

		if (debug == 2)
		{
			console.log(charID);

			console.log(critical(JSON.stringify(charFlags[charID])));

			console.log("looping");
		}

		if (charFlags[charID])
		{
			var killerVID = charFlags[charID].killerVID;
			var victimVID = charFlags[charID].victimVID;
			var resultID = charFlags[charID].resultID;
			var killerID = charFlags[charID].charID;
			var victimID = charFlags[charID].vCharID;

			if ((charFlags[charID].kill == 1) && (charFlags[charID].vKill == 1)) // Vehicle with Occ
			{
				if (debug == 2)
				{
					console.log(critical("VEHICLE KILL WITH OCCUPANT DETECTED!"));
				}

				incrementVehicleKills(1, killerVID, victimVID, resultID, killerID, victimID);
			}
			else if ((charFlags[charID].kill == 0) && (charFlags[charID].vKill == 1)) // Vehicle without Occ
			{
				if (debug == 2)
				{
					console.log(critical("VEHICLE KILL W/O OCCUPANT DETECTED"));
				}

				incrementVehicleKills(2, killerVID, victimVID, resultID, killerID, victimID);
			}
			else if ((charFlags[charID].kill == 1) && (charFlags[charID].vKill == 0)) // Normal Kill Occ
			{
				if (debug == 2)
				{
					console.log(critical("NORMAL KILL DETECTED"));
				}

				incrementVehicleKills(3, killerVID, 0, resultID, killerID, victimID);
			}

			var nanites = vehNanite[charFlags[charID].victimVID];

			var array = {
				"aCharID": charFlags[charID].charID,
				"vCharID": charFlags[charID].vCharID,
				"attackerName": charFlags[charID].aName,
				"victimName": charFlags[charID].vName,
				"timestamp": charFlags[charID].timestamp,
				"killerVID": charFlags[charID].killerVID,
				"victimVID": charFlags[charID].victimVID,
				"nanites": nanites,
				"resultID":	charFlags[charID].resultID
			};

			sendResult("vehicleKill", array);
		}
		else
		{
			console.log("CHARFLAG DOESN'T EXIST!");
		}
	};

	charIDs = [];
	charFlags = [];

}, 1000);

function incrementVehicleKills(type, kID, vID, resultID, killerID, victimID)
{
	if (resultID != undefined)
	{
		pool.getConnection(function(poolErr, dbConnectionVehicleKill)
		{
			if (poolErr)
			{
				throw(poolErr);
			}
			else
			{
				var Kquery;
				var Vquery;
				var iQueryK;
				var iQueryV;

				if (kID == 0) // If the kill was by infrantry
				{
					switch(type)
					{
						case 1:
						{
							type = 11;
							break;
						}
						case 2:
						{
							type = 22;
							break;
						}
					}
				}

				switch(type)
				{
					case 1: // V->V w/ Occ
					{
						Kquery = "killCount=killCount+1, killVCount=killVCount+1";
						Vquery = "deathCount=deathCount+1, deathVCount=deathVCount+1";
						iQueryK = kID+", 1, 0, 1, 0, 0, 0, 0, "+resultID;
						iQueryV = vID+", 0, 0, 0, 1, 0, 1, 0, "+resultID;
						pQueryK = kID+", "+killerID+", 1, 0, 1, 0, 0, 0, 0, "+resultID;
						pQueryV = vID+", "+victimID+", 0, 0, 0, 1, 0, 1, 0, "+resultID;
	 					break;
					}
					case 11: // I->V w/ Occ
					{
						Vquery = "deathCount=deathCount+1, deathICount=deathICount+1";
						iQueryV = vID+", 0, 0, 0, 1, 1, 0, 0, "+resultID;
						pQueryV = vID+", "+victimID+", 0, 0, 0, 1, 1, 0, 0, "+resultID;
						break;
					}
					case 2: // V->V no Occ
					{
						Kquery = "killCount=killCount+1, killVCount=killVCount+1";
						Vquery = "deathCount=deathCount+1, deathVCount=deathVCount+1, bails=bails+1";
						iQueryK = kID+", 1, 0, 1, 0, 0, 0, 0, "+resultID;
						iQueryV = vID+", 0, 0, 0, 1, 0, 1, 1, "+resultID;
						pQueryK = resultID+", "+kID+", "+killerID+", 1, 0, 1, 0, 0, 0, 0";
						pQueryV = resultID+", "+vID+", "+victimID+", 0, 0, 0, 1, 0, 1, 1";
						break;
					}
					case 22: // I->V no Occ
					{
						Vquery = "deathCount=deathCount+1, deathICount=deathICount+1, bails=bails+1";
						iQueryV = vID+", 0, 0, 0, 1, 1, 0, 1, "+resultID;
						pQueryV = vID+", "+victimID+", 0, 0, 0, 1, 1, 0, 1, "+resultID;
						break;
					}
					case 3: // V->I
					{
						Kquery = "killCount=killCount+1, killICount=killICount+1";
						iQueryK = kID+", 1, 1, 0, 0, 0, 0, 0, "+resultID;
						pQueryK = kID+", "+killerID+", 1, 1, 0, 0, 0, 0, 0, "+resultID;
						break;
					}
				}

				if (kID != 0)
				{
					// Killer Vehicle
					dbConnectionVehicleKill.query("UPDATE ws_vehicles_totals SET "+Kquery+" WHERE vehicleID = "+kID+" AND resultID = "+resultID, function(err, result)
					{
						if (err)
						{
							if (err.errno == 1213) // If deadlock
							{
								handleDeadlock("UPDATE ws_vehicles_totals SET "+Kquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, "Vehicle Update", 0);
							}
							else
							{
								throw(err);
							}

						}
						else if (result.affectedRows == 0) // If no update happened, try again
						{
							if (debug == 2)
							{
								console.log("Inserting New Killer Vehicle Record");
								console.log(kID);
								console.log(resultID);
								console.log(iQueryK);
							}

							dbConnectionVehicleKill.query("INSERT INTO ws_vehicles_totals (vehicleID, killCount, killICount, killVCount, deathCount, deathICount, deathVCount, bails, resultID) VALUES ("+iQueryK+")", function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										console.log(message);
										reportError(err, "Insert Player Cache Record");
										throw(err)
									}
								}
							});
						}
					});

					dbConnectionVehicleKill.query("UPDATE ws_vehicles SET "+Kquery+" WHERE resultID = "+resultID+" AND playerID='"+killerID+"' AND vehicleID="+kID, function(err, result)
					{
						if (err)
						{
							if (err.errno == 1213) // If deadlock
							{
								handleDeadlock("UPDATE ws_vehicles SET "+Kquery+" WHERE resultID = "+resultID+" AND playerID='"+killerID+"' AND vehicleID="+kID, "Vehicle Update", 0);
							}
							else
							{
								throw(err);
							}

						}
						else if (result.affectedRows == 0) // If no update happened, must be an insert
						{
							if (debug == 2)
							{
								console.log("Inserting New Killer Vehicle Record");
								console.log(kID);
								console.log(resultID);
								console.log(iQueryK);
							}

							dbConnectionVehicleKill.query("INSERT INTO ws_vehicles (vehicleID, killCount, killICount, killVCount, deathCount, deathICount, deathVCount, bails, resultID) VALUES ("+iQueryK+")", function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										reportError(err, "Insert Vehicle Kill Record");
										throw(err)
									}
								}
								else
								{
									dbConnectionVehicleKill.query("UPDATE ws_vehicles SET "+Kquery+" WHERE resultID = "+resultID+" AND playerID='"+killerID+"' AND vehicleID="+kID, function(err, result)
									{
										if (err)
										{
											if (err.errno == 1213) // If deadlock
											{
												console.log(critical("DEADLOCK DETECTED (Vehicles Kill)"));

												handleDeadlock("UPDATE ws_vehicles SET "+Kquery+" WHERE resultID = "+resultID+" AND playerID='"+killerID+"' AND vehicleID="+kID, "Vehicle Update", 0);
											}
										}
									});
								}
							});
						}
					})
				}

				if (vID != 0)
				{
					dbConnectionVehicleKill.query("UPDATE ws_vehicles_totals SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, function(err, result)
					{
						if (err)
						{
							if (err.errno == 1213) // If deadlock
							{
								console.log(critical("DEADLOCK DETECTED (Vehicles)"));

								handleDeadlock("UPDATE ws_vehicles_totals SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, "Vehicle Update", 0);
							}
							else
							{
								throw(err);
							}

						}
						else if (result.affectedRows == 0) // If no update happened, try again
						{
							if (debug == 2)
							{
								console.log("Inserting New Victim Vehicle Record");
								console.log(vID);
								console.log(resultID);
							}

							dbConnectionVehicleKill.query("INSERT INTO ws_vehicles_totals (vehicleID, killCount, killICount, killVCount, deathCount, deathICount, deathVCount, bails, resultID) VALUES ("+iQueryV+")", function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										console.log(message);
										reportError(err, "Insert Vehicle Kills Total Record");
										throw(err)
									}
								}
								else
								{
									setTimeout(function()
									{
										dbConnectionVehicleKill.query("UPDATE ws_vehicles_totals SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, function(err, result)
										{
											if (err)
											{
												throw(err);
											}
										});
									}, 500);
								}
							});
						}
					});

					dbConnectionVehicleKill.query("UPDATE ws_vehicles SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, function(err, result)
					{
						if (err)
						{
							if (err.errno == 1213) // If deadlock
							{
								handleDeadlock("UPDATE ws_vehicles SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, "Vehicle Update", 0);
							}
							else
							{
								throw(err);
							}

						}
						else if (result.affectedRows == 0) // If no update happened, must be an insert
						{
							if (debug == 2)
							{
								console.log("Inserting New Victim Vehicle Record");
								console.log(vID);
								console.log(resultID);
							}

							dbConnectionVehicleKill.query("INSERT INTO ws_vehicles (vehicleID, playerID, killCount, killICount, killVCount, deathCount, deathICount, deathVCount, bails, resultID) VALUES ("+pQueryV+")", function(err, result)
							{
								if (err)
								{
									if (err.errno != 1062) // If not a duplicate
									{
										console.log(message);
										reportError(err, "Insert Vehicle Victim Record");
										throw(err)
									}
									else
									{
										handleDeadlock("UPDATE ws_vehicles SET "+Vquery+" WHERE vehicleID ="+vID+" AND resultID = "+resultID, "Vehicle Update", 0);
									}
								}
							});
						}
					});
				}
			}

			dbConnectionVehicleKill.release();
		});
	}
}

var events = {};

function checkUpcoming(callback)
{
	if (ServerSmash == 1)
	{
		if (debug.upcoming == true)
		{
			console.log("Checking upcoming events");
		}

		pool.getConnection(function(poolErr, dbConnectionCheck)
		{
			if (poolErr)
			{
				throw(poolErr);
			}

			var time = new Date().getTime();
			var mysqltime = Math.round(time / 1000);

			dbConnectionCheck.query('SELECT * FROM ws_events WHERE approved = 1 AND processed = 0 AND finished = 0 ORDER BY startTime DESC', function(err, eventResult)
			{
				if (eventResult.length > 0) // If theres actually something to loop through
				{
					var data = {};

					for (var e = eventResult.length - 1; e >= 0; e--)
					{
						eventResult[e].startTimeRaw = eventResult[e].startTime;
						eventResult[e].startTimeMod = (eventResult[e].startTime - 20) * 1000;

						// Insert the event into the database

						insertMatch(eventResult[e], dbConnectionCheck, function(resultID, data)
						{
							console.log(success("Successfully added Event #"+resultID+" into database"));

							dbConnectionCheck.query("UPDATE ws_events SET processed = 1, resultID = "+resultID+" WHERE id = "+data.id, function(error, result)
							{
								if (err) { throw (err) };
							});
						});
					};
				}
				else
				{
					if (debug.upcoming === true)
					{
						console.log(notice("NO UPCOMING EVENTS"));
					}
				}
			});

			callback();

			dbConnectionCheck.release();
		});
	}
}

function setMatchInstances(data, dbConnectionCheck)
{
	var controlVS = "DUNNO";
	var controlNC = "DUNNO";
	var controlTR = "DUNNO";

	dbConnectionCheck.query("SELECT * FROM ws_instances WHERE resultID = "+data.resultID, function(err, result)
	{
		if (result.controlVS !== undefined)
		{
			controlVS = result.controlVS;
			controlNC = result.controlNC;
			controlTR = result.controlTR;

			if (debug.upcoming == true)
			{
				console.log("Found control % from instance");
			}
		}
		else
		{
			dbConnectionCheck.query("SELECT * FROM ws_map WHERE resultID = "+data.resultID+", ORDER BY dataID DESC LIMIT 1", function(err, result)
			{
				if (result)
				{
					controlVS = result[0].controlVS;
					controlNC = result[0].controlNC;
					controlTR = result[0].controlTR;

					if (debug.upcoming == true)
					{
						console.log(success("Found control % from map"));
					}
				}
				else
				{
					if (debug.upcoming == true)
					{
						console.log("Falling back on defaults");
					}

					controlVS = 33;
					controlNC = 33;
					controlTR = 33;

					if (debug.upcoming === true)
					{
						console.log(notice("Instances set"));
						console.log(instances[data.resultID]);
					}
				}
			})
		}

		instances[data.resultID] = {};

		instances[data.resultID].status = true;
		instances[data.resultID].resultID = data.resultID;
		instances[data.resultID].startTime = parseInt(data.startTime);
		instances[data.resultID].endTime = parseInt(data.endTime);
		instances[data.resultID].type = data.type;
		instances[data.resultID].world = data.world;
		instances[data.resultID].zone = data.zone;
		instances[data.resultID].controlVS = controlVS;
		instances[data.resultID].controlNC = controlNC;
		instances[data.resultID].controlTR = controlTR;
	});
}

var activeEvents = {};

function checkEvents(callback)
{
	var time = new Date().getTime();
	var mysqltime = Math.round(time / 1000);

	if (debug.upcoming === true)
	{
		console.log("ACTIVE EVENTS");
		console.log(activeEvents);
	}

	checkUpcoming(function()
	{
		if (debug.upcoming === true)
		{
			console.log(success("Checked for upcoming events"));
		}
	})

	pool.getConnection(function(poolErr, dbConnectionCheck)
	{
		if (poolErr) { throw(poolErr); }

		Object.keys(activeEvents).forEach(function(i)
		{
			var mapStart  = activeEvents[i].startTime - 120; // Set to get the map 2 minutes before start
			var subStart  = activeEvents[i].startTime - 20; // Time when the subs start
			var countDown = activeEvents[i].startTime - 15; // Time when the countdown starts
			var endTime   = activeEvents[i].endTime;
			var world     = activeEvents[i].world;
			var zone      = activeEvents[i].zone
			var resultID  = activeEvents[i].resultID;

			if (instances[resultID] !== undefined)
			{
				if (debug.upcoming === true)
				{
					console.log(success("Event instance #"+resultID+" found!"));
				}
			}
			else
			{
				console.log(critical("Event instance missing for #"+resultID+"!"));
			}

			if (activeEvents[i].approved == "1" && activeEvents[i].processed == "1")
			{
				if (debug.upcoming === true)
				{
					console.log(success("Processing valid event"));
				}
				// If the end of an event
				if (mysqltime >= activeEvents[i].endTime)
				{
					if (debug.upcoming === true)
					{
						console.log(success("END DETECTED!"));
					}

					var data = activeEvents[i];

					endMatch(data, dbConnectionCheck, function()
					{
						if (debug.upcoming === true)
						{
							console.log(success("ENDED MATCH IN DATABASE"));
						}
						delete activeEvents[i]; // Remove event from object
					});
				}
				else if (mysqltime >= subStart && activeEvents[i].subs == "0")
				{
					activeEvents[i].subs = "1";

					console.log(success("SENDING SUBS"));

					var message = {
						"world_id": activeEvents[i].world,
						"zone_id": activeEvents[i].zone,
						"start_time": activeEvents[i].startTime,
						"end_time": activeEvents[i].endTime,
						"metagame_event_type_id": activeEvents[i].type,
						"control_vs": 33,
						"control_nc": 33,
						"control_tr": 33,
					};
					fireSubscriptions(message, activeEvents[i].resultID, "subscribe", true);

					dbConnectionCheck.query("SELECT * FROM ws_instances WHERE resultID = "+activeEvents[i].resultID, function(err, instanceCheck)
					{
						if (instanceCheck[0]) // If not empty
						{
							if (debug.upcoming == true)
							{
								console.log(critical("Instance already detected for event: "+activeEvents[i].resultID));
							}
						}
						else
						{
							var insertInstance = {
								"id": activeEvents[i].resultID,
								"world": activeEvents[i].world,
								"zone": activeEvents[i].zone,
								"started": activeEvents[i].startTime,
								"endtime": activeEvents[i].endTime,
								"type": activeEvents[i].type,
								"controlVS": 33,
								"controlNC": 33,
								"controlTR": 33,
								"resultID": activeEvents[i].resultID,
								"event": 0
							};

							dbConnectionCheck.query("INSERT INTO ws_instances set ?", insertInstance, function(error, result)
							{
								if (error) { throw(error); }
							});
						}

						dbConnectionCheck.query("UPDATE Matches SET status = 1 WHERE id ="+activeEvents[i].resultID, function(error, result)
						{
							if (error) { throw(error); }
						});

						var statusMessage =
						{
							type: "start",
							id: activeEvents[i].resultID
						}

						sendResult("eventStatus", statusMessage, activeEvents[i].resultID);
						sendAdmins("eventStatus", statusMessage);
					});
				}
				else if (mysqltime >= mapStart && activeEvents[i].map == "0") // If a map update is needed
				{
					activeEvents[i].map = "1"; // Set the flag
					insertMapData(activeEvents[i], function()
					{
						console.log(success("UPDATED MATCH MAP DATA FLAG"));
					});

					pool.getConnection(function(poolErr, dbConnectionCheck)
					{
						if (poolErr) { throw(poolErr); }

						dbConnectionCheck.query("UPDATE ws_events SET map = '1' WHERE resultID = "+activeEvents[i].resultID, function(err, result)
						{
							if (err) { throw (err); }

							console.log(success("UPDATED MAP RECORD"));
						});

						dbConnectionCheck.query("UPDATE Matches SET status = '1' WHERE id = "+activeEvents[i].resultID, function(err, result)
						{
							if (err) { throw (err); }

							console.log(success("UPDATED STATUS MATCH RECORD"));

						});

						dbConnectionCheck.release();
					});

					var reloadMessage =
					{
						type: "reload",
						id: activeEvents[i].resultID
					}

					setTimeout(function() // Give the map script some time
					{
						console.log(success("SENDING RELOAD MESSAGE FOR EVENT #"+activeEvents[i].resultID));
						sendResult("reload", reloadMessage, activeEvents[i].resultID); // Send reload message to clients
					}, 10000);

				}
				else if ( (mysqltime >= countDown) && (mysqltime <= activeEvents[i].startTime) ) // If we're about to begin
				{
					console.log(success("============ STARTING COUNTDOWN! ==============="));

					var timeLeft = mysqltime - activeEvents[i].startTime; // Diff between now and start time

					var message =
					{
						resultID: activeEvents[i].resultID,
						action: "countdown",
						countTo: timeLeft
					};

					sendResult("eventStatus", message, activeEvents[i].resultID); // Send start message to clients

					var count = timeLeft;
					var countDown;

					countDown = setInterval(function()
					{
						if (count <= 0)
						{
							console.log("BEGIN");
							clearInterval(countDown);
						}

						console.log(count);
						count--;
					}, 1000);

					setTimeout(function()
					{
						console.log(success("================ STARTED EVENT #"+activeEvents[i].resultID+" ================ "));

						var message =
						{
							resultID: activeEvents[i].resultID,
							action: "matchStart",
						};

						sendResult("eventStatus", message, activeEvents[i].resultID); // Send start message to clients
						sendAdmins("eventStatus", message);
					}, timeLeft);


				}
				else if (mysqltime < activeEvents[i].startTime) // If schedualed
				{
					if (debug.upcoming === true)
					{
						console.log(warning("================ EVENT SCHEDUALED: ================"));
						console.log(activeEvents[i]);
					}
				}
				else
				{
					if (debug.upcoming === true)
					{
						console.log(success("================ EVENT IN PROGRESS: ================"));
						console.log(activeEvents[i]);
					}
				}

				if (debug.upcoming === true)
				{
					if (activeEvents[i])
					{
						console.log("SRT: "+ activeEvents[i].startTime);
						console.log("END: "+ activeEvents[i].endTime);
						console.log("MAP: "+ mapStart);
						console.log("SUB: "+ subStart);
						console.log("NOW: "+ mysqltime);
					}
				}
			}
		});
		dbConnectionCheck.release();

		callback();
	});
}

function insertMapData(data, callback)
{
	var worldID = String(data.world);
	var zoneID = String(data.zone);
	var resultID = String(data.resultID);

	var options = {
		host: "85.159.214.60",
		port: 80,
		method: 'GET',
		path: '/apipulls.php?type=mapinit&world='+worldID+'&zone='+zoneID+'&resultID='+String(resultID)
	};

	console.log("FIRING SCRIPT: "+options.host+options.path);

	var statusMessage =
	{
		type: "map",
		id: resultID,
	}

	sendAdmins("eventStatus", statusMessage);

	var resultMessage =
	{
		id: resultID,
	}

	sendResult("eventStatus", resultMessage);

	http.request(options, function(err)
	{
		console.log("Map script fired");

		callback();
	}).end();
}

function insertMatch(data, dbConnectionIM, callback) // ServerSmash match insertion
{
	if (ServerSmash == 1)
	{
		console.log(data);
		var insert = {
			"status": 0,
			"type": data.type,
			"startTime": data.startTimeRaw,
			"endTime": data.endTime,
			"title": data.title,
			"description": data.description,
			"server": data.world,
			"continent": data.zone,
			"statsAvailable": 1
		};

		dbConnectionIM.query("INSERT INTO Matches set ?", insert, function(error, result)
		{
			if (error)
			{
				throw(error);
			}

			var resultID = result.insertId;

			console.log("NEW RESULT ID:"+resultID);

			var matchesServerSmashInsert = {
				"server1": data.server1,
				"server1Faction": data.server1Faction,
				"server2": data.server2,
				"server2Faction": data.server2Faction,
				"server3": data.server3,
				"server3Faction": data.server3Faction,
				"tournament": 0,
				"matchID": resultID,
			};

			dbConnectionIM.query("INSERT INTO MatchesServerSmash set ?", matchesServerSmashInsert, function(error, result)
			{
				if (error)
				{
					throw(error);
				}
			});

			var factionsInsert = {
				"killsVS": "0",
				"killsNC": "0",
				"killsTR": "0",
				"deathsVS": "0",
				"deathsNC": "0",
				"deathsTR": "0",
				"teamKillsVS": "0",
				"teamKillsNC": "0",
				"teamKillsTR": "0",
				"suicidesVS": "0",
				"suicidesNC": "0",
				"suicidesTR": "0",
				"totalKills": "0",
				"totalDeaths": "0",
				"totalSuicides": "0",
				"totalTKs": "0",
				"resultID": resultID
			}

			dbConnectionIM.query("INSERT INTO ws_factions set ?", factionsInsert, function(error, result)
			{
				if (error)
				{
					throw(error);
				}
			});

			var statusMessage =
			{
				type: "processed",
				id: resultID,
			}

			activeEvents[resultID] =
			{
				"resultID": resultID,
				"startTime": data.startTimeRaw, // For subscriptions function
				"endTime": data.endTime,
				"approved": data.approved,
				"processed": 1,
				"type": data.type,
				"map": 0,
				"subs": 0,
				"world": data.world,
				"zone": data.zone,
			}

			setMatchInstances(activeEvents[resultID], dbConnectionIM);

			sendAdmins("eventStatus", statusMessage);
			callback(resultID, data);
		});
	}
}

function endMatch(data, dbConnectionEM, callback)
{
	dbConnectionEM.query("UPDATE ws_events SET finished = 1 WHERE resultID = "+data.resultID, function(error, result)
	{
		if (error)
		{
			throw(error);
		}

		console.log("Events Record deleted!");
	});

	dbConnectionEM.query("DELETE FROM ws_instances WHERE resultID = "+data.resultID, function(error, result)
	{
		if (error)
		{
			throw(error);
		}

		console.log("Instance Record deleted!");
	});

	dbConnectionEM.query("UPDATE Matches SET status = 2 WHERE id = "+data.resultID, function(error, result)
	{
		if (error)
		{
			throw(error);
		}

		console.log("Match record updated");
	});

	fireSubscriptions(data, data.resultID, "unsubscribe", true);

	var statusMessage =
	{
		type: "matchEnd",
		id: data.resultID
	}

	sendAdmins("eventStatus", statusMessage);
	sendResult("eventStatus", statusMessage, data.resultID);

	callback();
}

var messagesRecieved = 0;
var messagesRecievedLast = 0;
var messagesRecievedSec = 0;
var forcedEndings = 0;

// Loop through instances checking that they're valid and are not due to be ending
function checkInstances(callback)
{
	var time = new Date().getTime() + 10;
	time = parseInt(time / 1000); // To convert to seconds

	Object.keys(instances).forEach(function(key)
	{
		var world = instances[key].world;
		var zone = instances[key].zone;
		var overtime = instances[key].endTime + 10; // If end + 10 seconds

		// See if the alert is overdue
		if (time > overtime && ServerSmash == 0) // If overdue
		{
			var resultID = instances[key].resultID;

			console.log(critical("====================== ALERT #"+resultID+" OVERDUE!!!====================="));
			console.log("CHECKING TIME: "+time);
			console.log("RESULT TIME: "+instances[key].endTime); // Alert Time

			var endmessage = // Fake the end message
			{
				world_id: world,
				zone_id: zone,
				end_time: instances[key].endTime
			}

			pool.getConnection(function(poolErr, dbConnection)
			{
				forcedEndings++;

				if (forcedEndings > 5)
				{
					resetScript();
					return "";
				}

				if (debug.metagame == true)
				{
					console.log(endmessage);
				}

				console.log(critical("FORCFULLY ENDED ALERT #"+resultID+" W: "+world+" - Z:"+zone));

				endAlert(endmessage, resultID, client, dbConnection, function(endedResult)
				{
					reportError(resultID, "Forced Ended alert #"+resultID);
					dbConnection.release();
				});
			});
		}
	});

	callback();
}

function fireSubscriptions(message, resultID, mode, event)
{
	var world = String(message.world_id);
	var zone = String(message.zone_id);
	var started = String(message.start_time);

	var array = '"5428345446431759873","5428345446433102865","5428345446433103153","5428345446432632897","5428345446431264225","5428345446431443089","5428345446431481217","5428345446430872929","5428345446430887393","5428345446432611489","5428345446433106721","5428069961271040657","5428345446432067233","5428077655426544305","5428064957364303057","5428345446431735521","5428345446433108545","5428345446433108929"';

	if (enable.combat == true)
	{
		var combatMessage = '{"action":"'+mode+'","event":"Combat","worlds":["'+world+'"]}';

		console.log(success(combatMessage));
		client.send(combatMessage, function(error)
		{
			if (error)
			{
				console.log(critical("ERROR SENDING "+mode+" MESSAGE"));
				reportError("Error "+mode+" from API Socket", "Combat Message")
				resetScript();
				return false;
			}
		});
	}

	if (enable.vehicledestroy == true)
	{
		var vehicleCombatMessage = '{"action":"'+mode+'","event":"VehicleDestroy","worlds":["'+world+'"]}';

		console.log(success(vehicleCombatMessage));
		client.send(vehicleCombatMessage, function(error)
		{
			if (error)
			{
				console.log(critical("ERROR SENDING "+mode+" MESSAGE"));
				reportError("Error "+mode+" from API Socket", "Vehicle Message")
				resetScript();
				return false;
			}
		});
	}

	if (enable.facilitycontrol == true)
	{
		var facilityMessage = '{"action":"'+mode+'","event":"FacilityControl","worlds":["'+world+'"]}';

		console.log(success(facilityMessage));
		client.send(facilityMessage, function(error)
		{
			if (error)
			{
				console.log(critical("ERROR SENDING "+mode+" MESSAGE"));
				reportError("Error "+mode+" from API Socket", "Facility Message")
				resetScript();
				return false;
			}
		});
	}

	if (enable.populationchange == true)
	{
		var populationChangeMessage = '{"action":"'+mode+'","event":"PopulationChange","worlds":["'+world+'"]}';

		console.log(success(populationChangeMessage));
		client.send(populationChangeMessage, function(error)
		{
			if (error)
			{
				console.log(critical("ERROR SENDING "+mode+" MESSAGE"));
				reportError("Error "+mode+" from API Socket", "Population Message")
				resetScript();
				return false;
			}
		})
	}

	if (enable.xpmessage == true)
	{
		var xpMessage = '{"action":"'+mode+'","event":"ExperienceEarned","worlds":["'+world+'"]}';

		console.log(success(xpMessage));
		client.send(xpMessage, function(error)
		{
			if (error)
			{
				console.log(critical("ERROR SENDING "+mode+" MESSAGE"));
				reportError("Error "+mode+" from API Socket", "XP Message")
				resetScript();
				return false;
			}
		})
	}

	if (event !== true)
	{
		setInstances(message, resultID, mode);
	}
}

function setInstances(message, resultID, mode)
{
	var endTime = calcEndTime(message.start_time, message.metagame_event_type_id);
	var type = message.metagame_event_type_id;
	var world = message.world_id;
	var zone = message.zone_id;

	if (mode == "subscribe")
	{
		instances[resultID]           = {};
		instances[resultID].status    = true;
		instances[resultID].resultID  = resultID;
		instances[resultID].startTime = message.start_time;
		instances[resultID].endTime   = endTime;
		instances[resultID].type      = type;
		instances[resultID].world     = world;
		instances[resultID].zone      = zone;
		instances[resultID].controlVS = message.control_vs;
		instances[resultID].controlNC = message.control_nc;
		instances[resultID].controlTR = message.control_tr;

		console.log(success("INSTANCE SUCCESSFULLY CREATED!"));
	}
	else if (mode == "unsubscribe")
	{
		delete instances[resultID];

		console.log(success("INSTANCE SUCCESSFULLY REMOVED FROM SCRIPT"));
	}
}

function restoreSubs(client, dbConnectionI, callback)
{
	instances = {}; // Clear object if reconnected or being rebuilt
	activeEvents = {};

	dbConnectionI.query('SELECT * FROM ws_instances', function(err, resultInstance)
	{
		console.log("INITIAL INSTANCE QUERY FIRED");

		if(err)
		{
			reportError(err, "Select Initial Actives");
			throw (err);
			var returned = false;
		}
		else
		{
			if (ServerSmash == 1) // Rebuild instances and activeEvents objects
			{
				dbConnectionI.query("SELECT * FROM ws_events WHERE approved = 1 AND processed = 1 AND finished = 0", function(err, resultEvent)
				{
					if (err) { throw(err); }

					for (var i = resultEvent.length - 1; i >= 0; i--)
					{
						var resultID = resultEvent[i].resultID;

						activeEvents[resultID] =
						{
							"resultID": resultID,
							"startTime": resultEvent[i].startTime, // For subscriptions function
							"endTime": resultEvent[i].endTime,
							"approved": resultEvent[i].approved,
							"processed": resultEvent[i].processed,
							"type": resultEvent[i].type,
							"map": resultEvent[i].map,
							"subs": 0,
							"world": resultEvent[i].world,
							"zone": resultEvent[i].zone,
						}

						setMatchInstances(activeEvents[resultID], dbConnectionI);
					};
				});
			}

			var time = new Date().getTime() / 1000;

			for (i = 0; i < resultInstance.length; i++) // Loop through result array
			{
				if (resultInstance[i].started < time) // If it requires a subscription now
				{
					var world     = String(resultInstance[i].world);
					var zone      = String(resultInstance[i].zone);
					var started   = String(resultInstance[i].started);
					var endtime   = String(resultInstance[i].endtime);
					var type      = String(resultInstance[i].type);
					var resultID  = resultInstance[i].resultID;
					var controlVS = resultInstance[i].controlVS;
					var controlNC = resultInstance[i].controlNC;
					var controlTR = resultInstance[i].controlTR;

					var message = {
						"world_id": world,
						"zone_id": zone,
						"start_time": started,
						"end_time": 0,
						"metagame_event_type_id": type,
						"control_vs": controlVS,
						"control_nc": controlNC,
						"control_tr": controlTR,
					};

					// Fake the message to send to the subscriptions function

					fireSubscriptions(message, resultID, "subscribe");
				}
				else
				{
					console.log(critical("Not firing subscription, before start of event."));
				}
			}

			callback();
		}
	});
}

// Emergancy reboot of the script.
function resetScript()
{
	instances = {};
	charFlags = {};
	charIDs = [];
	clearInterval(subWatcherInterval);
	clearInterval(conWatcherInterval);
	clearInterval(upcomingCheckInterval);
	messagesRecieved = 0;
	messagesRecievedLast = 0;
	forcedEndings = 0;

	wsClient = new persistentClient();

	reportError("CRITICAL: Script Restarted", "");
}

/* Weapon Grouping shizzle by Anioth */

var weaponMap = [];
var weapons;

function generate_weapons(callback)
{
    console.log("GENERATING WEAPONS!");

    cachePool.getConnection(function(poolErr, dbConnectionW)
    {
    	if (poolErr)
    	{
    		throw(poolErr)
    	}

        dbConnectionW.query("SELECT * FROM weapon_data", function(err, result)
        {
            if (err)
            {
                throw(err);
            }
            else
            {
                dbConnectionW.release();

				var weaponFilterMap = {};

			    for (var i = result.length - 1; i >= 0; i--)
			    {
			        //console.log(result[i]);
			        var weapon = result[i];
			        if (weaponFilterMap.hasOwnProperty(weapon.weaponName)) {
			            weaponMap[weapon.weaponID] = {"id": weaponFilterMap[weapon.weaponName]};
			        } else {
			            weaponFilterMap[weapon.weaponName] = weapon.weaponID;
			            weaponMap[weapon.weaponID] = {"id": weapon.weaponID};
			        }
			    }

	            // Use the map to find a group
	            //console.log("%j", weaponMap);

	            //console.log("Looking for weapon id 1 %j", weaponMap[1]);

	            callback();
            }
        });
    });
}

function recordMessage(messageData, dbConnection)
{
	var message;

	try // Check if the message we get is valid json.
	{
		message = JSON.parse(messageData);
	}
	catch(exception)
	{
		message = null;
	}

	if (message)
	{
		var JSONMessage = JSON.stringify(message);

		var array =
		{
			message: JSONMessage,
			type: message.messageType
		};

		dbConnection.query('INSERT INTO ws_recorded SET ?', array, function(err, result)
		{
			if (err)
			{
				throw(err)
			}
			else
			{
				//console.log("Inserted Message");
			}
		});
	}
}

function combatHistory() // Called by generateActives function to log active alert history
{
	var date = new Date();
	var time = date.getTime();
	time = time / 1000 // Convert to websocket / PHP times

	if (messagesRecieved > 1)
	{
		console.log(success("=========== GENERATING COMBAT HISTORY ==============="));

		pool.getConnection(function(poolErr, dbConnectionH)
		{
			if (poolErr)
			{
				throw(poolErr)
			}

			Object.keys(instances).forEach(function(key)
			{
				var resultID = instances[key].resultID;

				dbConnectionH.query("SELECT * FROM ws_factions WHERE resultID="+resultID, function(err, result)
				{
					if (result[0]) // If got a record
					{
						var post =
						{
							resultID: resultID,
							timestamp: time,
							killsVS: result[0].killsVS,
							killsNC: result[0].killsNC,
							killsTR: result[0].killsTR
						}

                        if (instances[key].startTime < time && instances[key].endTime > time) {
                            dbConnectionH.query("INSERT INTO ws_combat_history SET ?", post, function(err, result)
    						{
    							if (err)
    							{
    								if (err.errno != 1062) // If not a duplicate
    								{
    									reportError(err, "Insert Combat Hiustory Record");
    									throw(err)
    								}
    							}

    							if (debug.status == true)
    							{
    								console.log(success("Inserted combat history for Alert: "+resultID));
    							}
    						});
                        }
					}
					else
					{
						console.log(critical("UNABLE TO RETRIEVE KILL COMBAT HISTORY FOR ALERT #"+resultID));
					}
				});
			});

			console.log(notice("Combat History Logged - "+date));

			dbConnectionH.release();
		});
	}
}

function handleDeadlock(query, location, tries)
{
	tries = tries++;

	if (tries < 20)
	{
		var rand = Math.random() * (1000 - 250) + 250;

		setTimeout(function()
		{
			pool.getConnection(function(poolErr, dbConnectionLoop)
			{
				dbConnectionLoop.query(query, function(err, result)
				{
					dbConnectionLoop.release();
					if (err)
					{
						if (err.errno == 1213) // If deadlock
						{
							handleDeadlock(query, location, tries); // Call upon itself to try again.
						}
					}

					if (result)
					{
						if (result.affectedRows > 0)
						{
							console.log(warning("Deadlock / Duplicate handled! Location: "+location));
						}
					}
					else
					{
						reportError(query, "ERROR HANDLING DEADLOCK @ LOCATION: "+location);
					}
				});
			});
		}, rand)
	}
	else
	{
		console.log(critical("DEADLOCK WAS HANDLED, BUT ERRORED."));
		console.log(critical("LOCATION: "+location));
	}
}

function getMapSnapshot(world, zone)
{

}

/* Structure

messagesDuplicates =
{
	Combat :
	{
		123456789 : (timestamp)
		{
			54564545454545 (victim)
		}
	}
	Facility :
	{
		1 :(world ID)
		{
			2 : (zone)
			{
				200100 : (facilityID)
				{
					123456789
				}
			}
		}
	}
	VehicleDestroy :
	{
		123456789 : (timestamp)
		{
			5546454545454 (victim)
		}
	}
}

if VictimID exists within combat message, discard the message


SAMPLE MESSAGE

FacilityMessage:
{
	"facility_id":"254030",
	"facility_type_id":"6",
	"outfit_id":"0",
	"duration_held":"52",
	"new_faction_id":"1",
	"old_faction_id":"2",
	"is_capture":"0",
	"control_vs":"44",
	"control_nc":"54",
	"control_tr":"1",
	"timestamp":"1424620307",
	"zone_id":"8",
	"world_id":"19"
	"event_type":"FacilityControl"
}
*/

var messagesDuplicates = {};

setInterval(function()
{
	wipeDuplicateMessages();
}, 2000);

function wipeDuplicateMessages()
{
	messagesDuplicates = {};
}

function checkDuplicateMessages(message, callback)
{
	if (debug == 1)
	{
		console.log(warning("CHECKING FOR DUPLICATES START"));
		console.log(warning(JSON.stringify(message)));
	}

	if (messagesDuplicates["Combat"] == undefined)
	{
		messagesDuplicates["Combat"] = {};
	}
	if (messagesDuplicates["FacilityControl"] == undefined)
	{
		messagesDuplicates["FacilityControl"] = {};
	}
	if (messagesDuplicates["VehicleDestroy"] == undefined)
	{
		messagesDuplicates["VehicleDestroy"] = {};
	}
	if (messagesDuplicates["MetagameEvent"] == undefined)
	{
		messagesDuplicates["MetagameEvent"] = {};
	}
	if (messagesDuplicates["ExperienceEarned"] == undefined)
	{
		messagesDuplicates["ExperienceEarned"] = {};
	}

	if (message.event_type != undefined)
	{
		var eventType = message.event_type;

        if (debug.duplicates === true)
		{
			console.log(warning("CHECKING FOR DUPLICATES"));
		}

		var status = false; // Duplicate unless proven otherwise

		if (eventType == "Combat")
		{
			var timestamp = message.payload.timestamp;
			var victimID = message.payload.victim_character_id;

			if (debug.duplicates == true)
			{
				console.log(warning("Checking Victim: "+victimID+" for duplicates"));
			}

			if (messagesDuplicates.Combat[victimID] == undefined)
			{
				messagesDuplicates.Combat[victimID] =
				{
					timestamp: timestamp
				}

				status = true;
			}
			else if (messagesDuplicates.Combat[victimID].timestamp == timestamp) // If a duplicate based off timestamp
			{
				status = false;
			}
		}
		else if (eventType == "FacilityControl")
		{
			var timestamp = message.payload.timestamp;
			var facilityID = message.payload.facility_id;

			if (debug.duplicates == true)
			{
				console.log(warning("Checking Facility: "+facilityID+" for duplicates"));
			}

			if (messagesDuplicates.FacilityControl[facilityID] == undefined)
			{
				messagesDuplicates.FacilityControl[facilityID] =
				{
					timestamp: timestamp
				}

				status = true;
			}
			else if (messagesDuplicates.FacilityControl[facilityID].timestamp == timestamp) // If a duplicate based off timestamp
			{
				status = false;
			}

			// Temp override
			status = true;
		}
		else if (eventType == "VehicleDestroy")
		{
			var timestamp = message.payload.timestamp;
			var victimID = message.payload.victim_character_id;

            if (debug.duplicates === true)
			{
				console.log(warning("Checking Vehicle Victim: "+victimID+" for duplicates"));
			}

			if (messagesDuplicates.VehicleDestroy[victimID] == undefined)
			{
				messagesDuplicates.VehicleDestroy[victimID] =
				{
					timestamp: timestamp
				}

				status = true;
			}
			else if (messagesDuplicates.VehicleDestroy[victimID].timestamp == timestamp) // If a duplicate based off timestamp
			{
				status = false;
			}
		}
		else if (eventType == "MetagameEvent")
		{
			var timestamp = message.payload.timestamp;
			var worldID = message.payload.world_id;

            if (debug.duplicates === true)
			{
				console.log(warning("Checking Alerts for World "+worldID+" for duplicates"));
			}

			if (messagesDuplicates.MetagameEvent[worldID] == undefined)
			{
				messagesDuplicates.MetagameEvent[worldID] =
				{
					world: worldID
				}

				status = true;
			}
			else if (messagesDuplicates.MetagameEvent[worldID].world == worldID) // If a duplicate based off presense
			{
				status = false;
			}
		}
        else if (eventType == "ServiceStateChange")
        {
            status = true;
        }
        else if (eventType == "ExperienceEarned")
        {
            status = true;
        }
	}
	else // If the message doesn't have an event type, let it through.
	{
		status = true;
	}

	callback(status)
}

function calcEndTime(started, type) // Calculates estimated end time of an alert based off type and start time
{
	switch(type)
	{
		case "1":
		case "2":
		case "3":
		case "4":
		{
			var toAdd = 7200;
		}
	}

	var endtime = parseInt(started) + toAdd;

	return endtime;
}
