# build-api (testing3)

**Description**

This app acts as an authentication proxy which uses cURL to query our Jenkins build server to 
return build status information.

**Setup**

The _build-status_ app (see https://github.com/pagefusionux/build-status) uses the
following URL format to call this API:
````
http://[this.bapi.domain]?host=[window.location.host]&option=status
````

URL Parameter(s):
- host (required): taken programmatically from window.location.host from the remote source script
- option: 'status' (default) or 'commits'; specifies the information to return from the server

**Note**

The build-api requires a 'config.php' file which holds Jenkins API credentials ($api_user, 
$api_token). It is not included as this is saved in a public repository.
