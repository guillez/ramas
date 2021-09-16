<!DOCTYPE html>
<html>
	<head>
		<title><?php echo isset($title) ? $title : '' ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<link href='css/bootstrap.min.css' rel='stylesheet' media='screen'>
		<link href='css/encuesta-kolla.css' rel='stylesheet' media='screen'>
		<?php if (isset($estilo)) { ?>
            <link href='css/<?php echo $estilo; ?>.css' rel='stylesheet' media='screen'>
		<?php }?>
	</head>
<body>