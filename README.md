# build-api

**Description**

This app acts as an authentication proxy which uses cURL to query our Jenkins build server to 
return build status information.

**Setup**

The _build-status_ app (see https://github.com/pagefusionux/build-status) uses the
following URL structure to call this API:
````
http://[where.this.is.hosted]?host=[window.location.host]&option=status&pretty=0
````
(the above structure may change if we decide to use a different URL format)

URL Parameter(s):
- host (required): taken programmatically from window.location.host from the remote source script
- option: 'status-commits' (default) or 'status'; specifies the information to return from the server
- pretty: if 1, returns the json in a readable format (for testing)

**Note**

The build-api requires a 'config.php' file which holds Jenkins API credentials ($api_user, 
$api_token). It is not included as this is saved in a public repository. You may specify values
for the $api_user and $api_token in *index.php* if *config.php* is not available.
