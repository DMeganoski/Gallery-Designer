<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.0.18b2';

// Database
$Configuration['Database']['Name'] = 'tinsdirect';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'tins_bot';
$Configuration['Database']['Password'] = 'Giclee2518';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';

// EnabledPlugins
$Configuration['EnabledPlugins']['GettingStarted'] = 'GettingStarted';
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['CustomPages'] = TRUE;
$Configuration['EnabledPlugins']['FileUpload'] = TRUE;
$Configuration['EnabledPlugins']['Galleries'] = TRUE;

// Garden
$Configuration['Garden']['Title'] = 'Tins Direct';
$Configuration['Garden']['Cookie']['Salt'] = 'GXXRVNVP3R';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Registration']['ConfirmEmail'] = TRUE;
$Configuration['Garden']['Email']['SupportName'] = 'Tins Direct';
$Configuration['Garden']['Version'] = '2.0.18b2';
$Configuration['Garden']['RewriteUrls'] = TRUE;
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Installed'] = TRUE;
$Configuration['Garden']['Theme'] = 'TinsDirect';
$Configuration['Garden']['Logo'] = 'OLT02YSX8K1R.png';
$Configuration['Garden']['ErrorMaster'] = 'deverror.master.php';

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['GettingStarted']['Profile'] = '1';
$Configuration['Plugins']['FileUpload']['Enabled'] = TRUE;

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0.18b2';

// Last edited by Administrator (192.168.1.2)2011-07-14 21:50:30