<?php

require_once("VoteSmart.php");

// Initialize the VoteSmart object
$obj = new VoteSmart(
        'CandidateBio.getBio', 
        Array(
                'candidateId' => 9026
        ));

// Get the SimpleXML object
$x = $obj->getXmlObj();

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- pwned -->
<html lang="en">
<body>';

// Check and make sure there is no error

if (isset($x->errorMessage)) { // If there is, let's handle it
        
        echo '
        <div>' . $x->errorMessage . '</div>';
        
} else { // If not, let's go ->
        
        // some quick and dirty assembly
        $candName = $x->candidate->firstName . ' ' . $x->candidate->middleName . ' ' . $x->candidate->lastName . ' ' . $x->candidate->suffix;
        if (!empty($x->candidate->photo)) $photo = '<img src="' . $x->candidate->photo . '" />';
        
        echo '
        <div>
                <a href="' . $x->generalInfo->linkBack . '">' . $x->generalInfo->title . '</a>
        </div>
        <br /><br />
        <table>
                <tr>
                        <td>Name</td><td>' . $candName . '</td>
                </tr><tr>
                        <td>Birth</td><td>' . $x->candidate->birthDate . ' (' . $x->candidate->birthPlace . ')</td>
                </tr><tr>
                        <td>Gender</td><td>' . $x->candidate->gender . '</td>
                </tr><tr>
                        <td>Photo</td><td>' . $photo . '</td>
                </tr><tr>
                        <td>Party</td><td>' . $x->office->parties . '</td>
                </tr><tr>
                        <td>Office</td><td>' . $x->office->name . '</td>
                </tr>
                ';
        
}

echo '
</body>
</html>';

?>