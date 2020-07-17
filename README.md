ASU Public Directory Service
=========================

This is a helper library for interacting with the ASU iSearch public directory service to retrieve information on registered ASU network users, including students, faculty, and staff.

For instance, to display the public ASU iSearch profile for a user, you need their EID:

https://isearch.asu.edu/profile/{EID}

Since the ASU CAS service uses the ASURITE as the primary user identifier, that can be used to look up the user's EID to generate a link to their profile webpage (or get other directory details provided by iSearch.)

Profile data can retrieved as XML or JSON:

Using ASURITE:
* https://asudir-solr.asu.edu/asudir/directory/select?q=asuriteId:{ASURITE}&wt=json
* https://asudir-solr.asu.edu/asudir/directory/select?q=asuriteId:{ASURITE}&wt=xml

or by EID:
* https://asudir-solr.asu.edu/asudir/directory/select?q=eid:{EID}&wt=xml

As this iSearch interface is provided by Apache Solr, other queries are also available using other fields, such as all users with a given last name:
* https://asudir-solr.asu.edu/asudir/directory/select?q=lastName:{lastName}&wt=xml

More information available on the [Solr Standard Query Parser](https://cwiki.apache.org/confluence/display/solr/The+Standard+Query+Parser)

## Install Instructions

You can use Composer to install this package into your PHP project by adding the following to your project's composer.json:

```javascript
"repositories": [
    {
        "url": "https://github.com/asu-ke-web-services/asu-public-directory-service",
        "type": "git"
    }
],
"require": {
    "asu-ke-web-services/asu-public-directory-service": "dev-master"
},
```

## Usage

Include the class and its namespace in your project:

```php
use Asu_Research\AsuPublicDirectoryService\AsuDirectory;
require_once 'vendor/asu-ke-web-services/asu-public-directory-service/src/AsuDirectory.php';
```

Then when you need to get a user's directory information, you must already have their ASURITE login name. To retrieve their directory record:

```php
$directory_info = AsuDirectory::getDirectoryInfoByAsurite( $asurite );
```

and then get their email address and full name from that directory info:

```php
$email = AsuDirectory::getEmail( $directory_info );
$fullName = AsuDirectory::getDisplayName( $directory_info );
```

## All directory functions currently available

* AsuDirectory::getDirectoryInfoByAsurite( $asurite )  
* AsuDirectory::getEid( $info )  
* AsuDirectory::getAsurite( $info )  
* AsuDirectory::getDisplayName($info)  
* AsuDirectory::getLastName($info)  
* AsuDirectory::getFirstName($info)  
* AsuDirectory::getEmail($info)  
* AsuDirectory::isStudent( $info )  
* AsuDirectory::isFaculty( $info )  
* AsuDirectory::isStaff( $info )  
* AsuDirectory::getUserType( $info )  
    Get primary user status (in case of multiple classifications for enrolled students who are also employed by the university): Student > Faculty > Staff  
* AsuDirectory::hasSosPlan( $info )  
    Is student pursuing a degree major in the School of Sustainability?  

### iSearch API notes:

1. Note that the XML and JSON responses from iSearch have a subtle structural difference: the XML child element `<doc>` is named 'docs' in the JSON response object.
