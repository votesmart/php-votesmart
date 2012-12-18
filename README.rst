php-votesmart
=======================
A PHP library for use with the Vote Smart API(http://votesmart.org/share/api).

------------
Requirements
------------

The PHP libraries require PHP 5 with the SimpleXML extension and ``allow_url_fopen`` set to ``On`` in php.ini. 

------------
Usage
------------
Using the libraries is fairly simple. You initialize the object with the name one of the methods and any arguments needed in an array. Then a call go ``getXmlObj()`` can be made to retrieve a SimpleXML object to work with. Let's say you wanted to get information on a bill...::

    // Initialize the VoteSmart object
    $obj = new VoteSmart(
            'CandidateBio.getBio', 
            Array(
                    'candidateId' => 9026
            ));

    // Get the SimpleXML object
    $x = $obj->getXmlObj();

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

Any questions about the API or this library can be sent to webmaster@votesmart.org.