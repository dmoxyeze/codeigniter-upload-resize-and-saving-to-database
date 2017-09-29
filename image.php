<html>
<head>
<title>Upload Form</title>
</head>
<body>

<?php if(isset($error)){?>
<center><?php echo $error; ?></center>
<?php } ?>

<?php echo form_open_multipart('image/do_upload');//codeigniter image upload form helper; image is the controller while do_upload is the method in the controller that does the processing?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>
<?php if(isset($image)) { //you can remove this as this is just a demo to show the just uploaded file; ?>
<img src="<?php echo base_url(); ?>uploads/<?php echo $image; ?>">
<?php } ?>
<?php //phpinfo(); ?>
</body>
</html>

