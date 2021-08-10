<?php

return [
    'index' => 'HomeController@index',
    'install' => 'HomeController@install',
    'load' => 'HomeController@load',

    'install_package' => 'PackageController@install',
    'runTask' => 'TaskController@runTask',
    'checkTask' => 'TaskController@checkTask',

    'get_listening_ports' => 'ListeningPortController@get',
    'get_lps_count' => 'ListeningPortController@getTotal',
    
    'enable_ufw' => 'FirewallController@enable', 
    'disable_ufw' => 'FirewallController@disable', 
    'fetch_ufw_status' => 'FirewallController@fetchStatus', 
    'get_firewall_rules' => 'FirewallController@get', 
    'get_ufw_rules_count' => 'FirewallController@getTotal',
    'add_custom_ufwRule' => 'FirewallController@addCustomRule',
    'delete_ufw_rule' => 'FirewallController@deleteRule',
    'allow_listening_port' => 'FirewallController@allowPort',
    'deny_listening_port' => 'FirewallController@denyPort',

    'get_firewall_logs' => 'FirewallController@getFirewallLogs'
];