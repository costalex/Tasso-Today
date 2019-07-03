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

    <title id="page_title">Votre commande est prête.</title>

    <!-- Bootstrap import -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- Default CSS -->
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body>
<h1>Tasso</h1>
@if(isset($ref_ortoo) && !empty($ref_ortoo))
        <h2>Votre commande: <b>{{ $num_cmd }}</b> est prête à être livrée.</h2>
        <p>Référence de suivi du bon de commande: {{ $ref_ortoo }}</p>
    @else
        <h2>Votre commande: <b>{{ $num_cmd }}</b> est prête à être retirée.</h2>
    @endif
{{--<p>Retrouvez toutes les informations relative a votre commande à l'addresse: www.tasso-suivis-colis.com</p>--}}
</body>

<footer>
</footer>
</html>