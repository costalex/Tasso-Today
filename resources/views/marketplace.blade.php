<!DOCTYPE html>
<html lang="fr">
<head>
    <meta
        http-equiv="Content-Type"
        content="text/html; charset=UTF-8"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <meta
        name="description"
        content="Tasso : Les meilleurs commerçants de votre ville à porté de clic, livrés chez vous en 1h. Ou retirez vos achats en magasin. Tous types de produits livrés quand et où vous voulez."
    >
    <meta
        name="google-site-verification"
        content="uzFeB7G9Vlkp953w7JISoWnQuyzf9wL3Jc1pNwtyauc"
    >

    <meta
        property="og:description"
        content="Tasso : Les meilleurs commerçants de votre ville à porté de clic, livrés chez vous en 1h. Ou retirez vos achats en magasin. Tous types de produits livrés quand et où vous voulez."
    >
    <meta
        property="og:type"
        content="website"
    >
    <meta
        property="og:title"
        content="Tasso : Alimentaire, Électronique, Mode, Déco - Tout, partout, quand vous voulez"
    >
    <meta
        property="og:url"
        content="{{ config('app.url') }}"
    >
    <meta
        property="og:image"
        content="{{ config('app.url') }}/storage/bobby_images/landing_page/tasso-share-image.jpg"
    >
    <meta
        property="og:image:width"
        content="1200"
    >
    <meta
        property="og:image:height"
        content="1200"
    >
    <meta
        property="og:image:alt"
        content="Tasso : le meilleur de vos commerçants, où et quand vous voulez."
    >

    <title>Tasso : Alimentaire, Électronique, Mode, Déco - Tout, partout, quand vous voulez</title>

    <link
        rel="shortcut icon"
        type="image/x-icon"
        href="{{ config('app.url') }}/favicon.ico"
    >

    <!-- Bootstrap import -->
    <link
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        rel="stylesheet"
        type="text/css"
    >
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"
    >

    <!-- Font awesome's import -->
    <link
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        rel="stylesheet"
        type="text/css"
    >

    <!-- Google autocomplete -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&libraries=places&language=fr"></script>

    @if(config('app.env') === 'production')
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128056496-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-128056496-1');
        </script>
    @endif
</head>
<body>
    <div id="body"></div>

    <footer>
        <script src="{{ mix('js/app.js')  }}"></script>
        <script type="text/javascript" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
        <script src="https://js.stripe.com/v3/"></script>
    </footer>
</body>
</html>