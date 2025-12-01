<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="30"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= ($i18n_titleapp) ?></title>
    <base href="<?= ($SCHEME.'://'.$HOST.$BASE.'/') ?>">

  <!-- Favicons -->
  <!-- <link href="ui/img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link href="ui/img/xmart101.png" rel="icon">
  <link rel="icon" type="image/x-icon" href="ui/img/xmart101_64.ico">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
    <!--   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta24/dist/css/tabler.min.css">     -->  
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.css">
    <!-- Custom styles for this template -->
    <link href="ui/css/style.css" rel="stylesheet">
    
    <!-- Thumbnail for tasks pictures -->
<!-- Custom CSS -->


<style>
  .gallery-img {
    aspect-ratio: 4 / 3; /* Keeps consistent height */
    object-fit: cover; /* Ensures images look good */
    transition: transform 0.3s ease-in-out;
  }
  .gallery-img:hover {
    transform: scale(1.05);
  }
  .position-relative .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  .position-relative:hover .overlay {
    opacity: 1;
  }
  .overlay i {
    font-size: 2rem;
    color: #fff;
  }
</style>


    
    
    
    
    <style>
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>    
    <script>
        async function sendMessage() {
            const prompt = document.getElementById('prompt').value;
            const response = await fetch('admin/openai', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ prompt: prompt })
            });
            const data = await response.json();
            document.getElementById('response').innerText = data.response;
        }
    </script>    
</head>
<body>
    <div id="demo"></div>
    <!-- Navigation -->
    <?php if (isset($SESSION['logged_in']) && $SESSION['logged_in']): ?>
    
	    <?php echo $this->render('nav.htm',NULL,get_defined_vars(),0); ?>
	
    <?php endif; ?>

    <!-- Page Content -->