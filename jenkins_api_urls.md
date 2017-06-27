#Jenkins API URLs

This file contains research notes regarding the URL and parameters used to return information 
from the Jenkins server through its JSON API.

References:
- https://www.cloudbees.com/blog/taming-jenkins-json-api-depth-and-tree

**single branch (lastBuild):**

http://build.clearlink.com:8080/job/[project]/job/[branch]/lastBuild/api/json?tree=number,result,duration,timestamp,estimatedDuration

**commits (changeSets):** 

http://build.clearlink.com:8080/job/[project]/job/[branch]/lastBuild/api/json?tree=changeSets[*[*]]


**all jobs (unfiltered):**

http://build.clearlink.com:8080/job/[project]/api/json?pretty=true

- displayName (v2-west.frontier)
- name (v2-west.frontier)
- jobs[]


**all builds for branch (unfiltered):**

http://build.clearlink.com:8080/job/[project]/job/[branch]/api/json?pretty=true

- displayName (production)
- **name** (production)
- builds[]
- **lastBuild**
- lastCompletedBuild
- lastFailedBuild (null)
- lastStableBuild
- lastSuccessfulBuild
- lastUnstableBuild (null)
- lastUnsuccessfulBuild
- nextBuildNumber

**all jobs (filtered by name, lastBuild, and changeSets)**

http://build.clearlink.com:8080/job/v2-west.frontier/api/json?tree=jobs[name,lastBuild[number,duration,timestamp,result,changeSets[*[*]]]]&pretty=true

http://build.clearlink.com:8080/job/v2-west.frontier/api/json?tree=jobs[name,lastBuild[number,duration,timestamp,result,changeSets[items[msg,author[fullName]]]]]&pretty=true

Info we want for our *build-status* app:

- date
- commitId
- msg
- author[fullName]
- authorEmail
- affectedPaths[] (files changed)
- items[date,commitId,msg,authorEmail,affectedPaths[*]]

Full query:

http://build.clearlink.com:8080/job/v2-west.frontier/api/json?tree=jobs[name,lastBuild[number,duration,timestamp,result,estimatedDuration,changeSets[items[date,commitId,msg,author[fullName],authorEmail,affectedPaths[*]]]]]&pretty=true

**Queries required for build-status app:**

- 1st: (gets status and commit info for all branches) -- will filter for (dev, hotfix, release, production)
  - http://build.clearlink.com:8080/job/v2-west.frontier/api/json?tree=jobs[name,lastBuild[number,duration,timestamp,result,estimatedDuration,changeSets[items[date,commitId,msg,author[fullName],authorEmail,affectedPaths[*]]]]]&pretty=true

- 2nd: (just gets status info) -- will filter for (dev, hotfix, release, production)
  - http://build.clearlink.com:8080/job/v2-west.frontier/api/json?&tree=jobs[name,lastBuild[number,duration,timestamp,result,estimatedDuration]]&pretty=true
