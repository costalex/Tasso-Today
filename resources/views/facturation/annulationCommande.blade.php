<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <meta property="og:type" content="website">
    <meta property="og:image" content="og:image">
    <meta property="og:url" content="og:url">
    <meta name="description" content="description">
    <meta property="og:description" content="og:description">

    <title id="page_title">Justificatif d’annulation.</title>

    <!-- Bootstrap import -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- Default CSS -->
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body>
    <h1>Tasso Store</h1>
    <h2>Justificatif d’annulation de la commande: <b>{{ $num_cmd }}</b></h2>
    <p>La commande effectuée le: {{ $date_creation }} a été annulé le {{ $date_annulation }} pour la raison suivante: {{ $annulation_commentaire }} </p>
</body>

<footer>
</footer>
</html>