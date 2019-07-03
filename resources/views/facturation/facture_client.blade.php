<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <meta property="og:type" content="website">
    <meta property="og:image" content="og:image">
    <meta property="og:url" content="og:url">
    <meta name="description" content="description">
    <meta property="og:description" content="og:description">
    <title id="page_title">Facture - {{ $facture['num_commande'] }}</title>
</head>

<body>
    {{--titre = facture ou bon de commande--}}
    <h1>{{ $title }}</h1>

    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
        {{--informations TASSO--}}
        <h1>TASSO</h1>
        <p>1 rue Jean-François de Lapérouse</p>
        <p>33290, Blanquefort, France</p>
        {{--prevoir d'inserer le lien de l'entreprise pour que le client puisse y revenir--}}
        {{--<a href=""></a>--}}

        {{--informations client--}}
        <p> {{ $facture['client_infos']["nom"]}} {{ $facture['client_infos']["prenom"] }}</p>
        <p> {{ $facture['client_infos']['addresse_livraison_client'] }}</p>
        <p>Reference commande client: {{ $facture['num_commande'] }}</p>
    </div>


    @foreach($facture['entreprises'] as $entreprise)
        {{--informations entreprise--}}
        <h1>{{ $entreprise["entreprise_infos"]["nom_enseigne"] }}</h1>
        <p> {{ $entreprise["entreprise_infos"]["addresse_entreprise"]["commune"] }}</p>
        <p> {{ $entreprise["entreprise_infos"]["addresse_entreprise"]["code_postal"] }}</p>
        <p> Date: {{ $entreprise["entreprise_infos"]["date"] }}</p>
        @foreach($entreprise['paniers'] as $panier)
            {{--Type de livraison--}}
            <p>Mode de retrait: {{ $panier['livraison'] ? "Livraison" : "Retrait en boutique" }}</p>

            {{--statut de la commande--}}
            @if($panier['statut'] == 'PAYE')
                <b>REGLEMENT EFFECTUE</b>
            @elseif($panier['statut'] == 'ANNULE')
                <b>FACTURE ANNULEE</b>
            @elseif($panier['statut'] == 'EN_ATTENTE')
                <b>FACTURE EN ATTENTE DE PAIEMENT</b>
            @endif

            {{--informations facture--}}
            <p> Réference commande entreprise: {{ $panier["num_commande"] }}</p>

            {{--tableau--}}
            <table class="table-striped table-bordered">
                <tr>
                    <td>Quantité</td>
                    <td>Désignation</td>
                    <td>Prix unitaire TTC</td>
                    <td>Prix TTC</td>
                </tr>

                @foreach($panier['list_produits'] as $index => $produit)
                <tr align="right">
                    <td>{{ $panier['list_produits'][$index]['quantite'] }}</td>
                    <td>{{ $panier['list_produits'][$index]['nom'] }}</td>
                    <td>{{ $panier['list_produits'][$index]['stocks']['prix'] }}</td>
                    <td>{{ (int)$panier['list_produits'][$index]['stocks']['prix'] * (int)$panier['list_produits'][$index]['quantite'] }}</td>
                </tr>
                @endforeach
                </table>
        @endforeach
    @endforeach
    <table>
        <tr>
            <td>Sous-total TTC: </td>
            <td>{{ $facture['total'] -  $facture['total_livraison']}} €</td>
        </tr>
        <tr>
            <td>livraison: </td>
            @if($facture['livraison'])
                <td>{{ $facture['total_livraison'] }} €</td>
            @else
                <td>0.00 €</td>
            @endif
        </tr>
        <tr>
            <td>Total TTC: </td>
            <td>{{ $facture['total'] }} €</td>
        </tr>
    </table>
</body>

<footer>
        <p>Conditions génerales client</p>
</footer>
</html>