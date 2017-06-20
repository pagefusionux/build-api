# build-api (BAPI)

**Description**

This app queries our Jenkins build server to return commit information and build status
information.

**Setup**

To query this API from a remote source, the URL format and options are as follows:
````
http://[this.bapi.domain]?host=[window.location.host]&option=status`)
````

URL Parameter(s):
- host (required): taken programmatically from window.location.host from the remote source script
- option: 'status' (default) or 'commits'; specifies the information to return from the server

**Note**

The build-api requires a 'config.php' file which holds Jenkins API credentials ($api_user, 
$api_token). It is not included as this is saved in a public repository.
