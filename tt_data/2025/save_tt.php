<?php
// Get the raw POST data
$json = file_get_contents("php://input");

// Decode JSON data to PHP associative array
$data = json_decode($json, true);

// Check if decoding was successful
if ($data !== null) {
    // You can now access elements like $data['key']
    // For example, let's print the data
    echo "Received data:\n";
    //print_r($data);




$filename = "saved_tt.txt";
$file = fopen($filename, "w"); // "w" mode overwrites the file
$line = "This is a line of text.\n";

for($i=0;$i<$data['total'];$i++)
{
	$dat=$data[$i];
	$line="set	bind	".$dat['class']."	".$dat['teacher']."	".$dat['subject']."	".$dat['day']."	".$dat['period']."\n";
	fwrite($file, $line);
}

//fwrite($file,"autobind_1\nautobind_any_byclass_1\n");
echo($data["total"]);

//fwrite($file, $line);
fclose($file);





    // Optionally, save to a file or database
    // file_put_contents('data.json', $json);
} else {
    echo "Invalid JSON received";
}
?>

