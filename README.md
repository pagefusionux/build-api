# build-api (BAPI)

**Description**

This API queries our Jenkins build server to return commit information and build status
information.

**Setup**

To query this API from a remote source, the URL format and options are as follows:
````
http://[this.bapi.domain]?host=[window.location.host]`)
````

URL Parameter(s):
- host (required): taken programmatically from window.location.host from the remote source script

**Note**

The build-api requires a 'config.php' file which holds Jenkins API credentials ($api_user, 
$api_token). It is not included as this is saved in a public repository.
