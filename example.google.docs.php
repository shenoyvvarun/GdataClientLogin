<?php

# Arvin Castro, arvin@sudocode.net
# 27 June 2011
# http://sudocode.net/article/434/upload-a-file-to-a-google-docs-collection-in-php

include_once 'class.clientlogin.php';
include_once 'class.xhttp.php';

$email    = 'email@gmail.com'; # GMail or Google Apps account
$password = 'password';

$login = new clientlogin($email, $password, clientlogin::documents);

if($_FILES['document']['error'] === UPLOAD_ERR_OK and $_FILES['document']['tmp_name']) {

	$data['headers'] = array(
		'Authorization' => $login->toAuthorizationheader(),
		'GData-Version' => '3.0',
		'Slug' => rawurlencode($_FILES['document']['name']),
		'Content-Type' => $_FILES['document']['type'],
	);
	$data['post'] = file_get_contents($_FILES['document']['tmp_name']);

	$uploadresponse = xhttp::fetch($_POST['collection'], $data);

	if($uploadresponse['successful']) {
		$message = $_FILES['document']['name'].' uploaded successfully!';
	} else {
		$message = 'Error uploading file'.$response['body'];
	}
}

# List collections
$response = xhttp::fetch('https://docs.google.com/feeds/default/private/full/-/folder', array(
	'headers' => array(
		'Authorization' => $login->toAuthorizationHeader(),
		'GData-Version' => '3.0',
)));

$xml = new SimpleXMLElement($response['body']);
$collections['home'] = 'https://docs.google.com/feeds/default/private/full/folder%3Aroot/contents';
if($xml and is_array($xml->entry)) foreach($xml->entry as $collection) {
	$collections[(string) $collection->title] = $collection->content->attributes()->src;
}

?>
<html><body>
<h3><?php echo $message ?></h3>
<form method="POST" enctype="multipart/form-data">
	<strong>Upload</strong>
	<input type="file" name="document" maxlength="10485760" />
	<strong>to</strong>
	<select name="collection">
		<?php foreach($collections as $name => $collection) { echo "<option value=\"{$collection}\">{$name}</option>\n	"; } ?>
	</select>
	<input type="submit" name="upload" value="Go!" onclick="this.value='Uploading...'" />
</form>
</body></html>