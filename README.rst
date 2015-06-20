php-votesmart
=======================
A PHP library for use with the Vote Smart API (http://votesmart.org/share/api).

------------
Requirements
------------

The PHP libraries require PHP 5 with the SimpleXML extension and ``allow_url_fopen`` set to ``On`` in php.ini. 

------------
Usage
------------
First, you need your API token. Once you get that from VoteSmart.org, store the API token in a file which is loaded
by your application. It should be defined as::

    $_ENV['VOTESMART_API_KEY']

Using the libraries is fairly simple. You initialize the object, and call ``query()`` to make the call parse the
response from VoteSmart. If there is no response, or the request fails, the output is boolean ``false``::

    // Initialize the VoteSmart object. The default output type is XML.
    $obj = new VoteSmart();

    // You can also pass in optional to change the expected output type, and the location where the API token is located
    // inside the $_ENV global variable. In this case, your VoteSmart API key would be stored in $_ENV['SOME_KEY'].
    $obj = new VoteSmart('JSON', 'SOME_KEY');

    // Make the query with required parameters, with the name of one of the methods, and any required or optional
    // arguments in an array. Let's say you wanted to get information on a bill. For example:
    $x = $obj->query(
      'CandidateBio.getBio',
      Array(
        'candidateId' => 9026
      )
    );

    // Once a query has been made, you can also get the stored decoded response.
    // If your output type was XML, you could access the SimpleXMLElement object via
    $x = $obj->getXmlObj();

    // If your output type was JSON, you could access the array via
    $x = $obj->getJsonObj();

Now ``$xml_object`` is a SimpleXML object representative of the XML structure. Here's a small cut from the XML itself.::

    <?xml version="1.0" encoding="UTF-8"?>
    <bio>
      <generalInfo>
        <title>Project Vote Smart - Bio - Rep. Stephen Scalise</title>
        <linkBack>http://votesmart.org/bio.php?can_id=9026</linkBack>
      </generalInfo>
      <candidate>
        <candidateId>9026</candidateId>
        <fecId></fecId>
        <photo>http://votesmart.org/canphoto/9026.JPG</photo>
        <firstName>Stephen</firstName>
        <nickName>Steve</nickName>
        <middleName>J.</middleName>
        <lastName>Scalise</lastName>
        <suffix></suffix>
        <birthDate>10/06/1965</birthDate>
        <birthPlace></birthPlace>
        <pronunciation></pronunciation>
        <gender>Male</gender>
        [...]
      </candidate>
    </bio>

Let's say you wanted to get the candidateId of the candidate. You could simply access it like this::

    echo $x->candidate->candidateId;

And anything with repeating rows like say, committee memberships would just be treated as an array::

    echo $x->office->committee[0]->committeeName;

------------
Documentation and Support
------------

All documentation on our API can be found at http://api.votesmart.org/docs/

Any questions about the API or this library can be sent to webmaster@votesmart.org.