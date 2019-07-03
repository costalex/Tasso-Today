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
    <title id="page_title">Facture - {{$facture['num_commande']}}</title>
</head>

<body>
    @if($impression == false)
        <form METHOD="get" action="{{ route('downloadFacture', $facture['num_commande'])}}">
            <button type="submit">Telecharger</button>
        </form>
    @endif
    {{--titre = facture ou bon de commande--}}
    <h1>{{ $facture["title"] }}</h1>

    {{--informations TASSO--}}
    <h1>TASSO</h1>
    <p>1 rue Jean-François de Lapérouse</p>
    <p>33290, Blanquefort, France</p>

    {{--informations entreprise--}}
    <h1>{{ $facture["entreprise_infos"]["nom_enseigne"] }}</h1>
    <p> {{ $facture["entreprise_infos"]["addresse_entreprise"]["commune"] }}</p>
    <p> {{ $facture["entreprise_infos"]["addresse_entreprise"]["code_postal"] }}</p>
    {{--prevoir d'inserer le lien de l'entreprise pour que le client puisse y revenir--}}
    {{--<a href=""></a>--}}

    {{--informations facture--}}
    <p> Réference: {{ $facture["num_commande"] }}</p>
    <p> Date: {{ $facture["date"] }}</p>

    {{--informations client--}}
    <p> {{ $facture['client_infos']["nom"]}} {{ $facture['client_infos']["prenom"] }}</p>
    <p> {{ $facture['addresse_livraison_client'] }}</p>
    <p> {{ $facture['client_infos']["code_postal"] }}</p>

    {{--statut de la commande--}}
    @if($facture['statut'] != 'EN_ATTENTE' && $facture['statut'] != 'ANNULE')
        <b>REGLEMENT EFFECTUE</b>
    @elseif($facture['statut'] == 'ANNULE')
        <b>FACTURE ANNULEE</b>
    @elseif($facture['statut'] == 'EN_ATTENTE')
        <b>FACTURE EN ATTENTE DE PAIEMENT</b>
    @endif

    <table>
    <tr>
        <td>Quantité</td>
        <td>Désignation</td>
        <td>Prix unitaire TTC</td>
        <td>Prix TTC</td>
    </tr>

    @foreach($facture['list_produits'] as $index => $produit)
    <tr>
        <td>{{ $facture['list_produits'][$index]['quantite'] }}</td>
        <td>{{ $facture['list_produits'][$index]['nom'] }}</td>
        <td>{{ $facture['list_produits'][$index]['prix_unit'] }}</td>
        <td>{{ (int)$facture['list_produits'][$index]['prix_unit'] * (int)$facture['list_produits'][$index]['quantite'] }}</td>
    </tr>
    @endforeach
    </table>

    <table>
        <tr>
            <td>Total TTC</td>
            <td>{{ $facture['total_facture'] }}</td>
        </tr>
    </table>
</body>

<footer>
    @if($facture['user_type'] == "Client")
        <p>Conditions génerales client</p>
    @else
        <p>Conditions génerales vendeur</p>
    @endif
</footer>
</html>