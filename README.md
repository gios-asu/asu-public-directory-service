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

###iSearch API notes:

1. Note that the XML and JSON responses from iSearch have a subtle structural difference: the XML child element `<doc>` is named 'docs' in the JSON response object.
