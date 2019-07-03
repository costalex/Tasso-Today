<!DOCTYPE html>
<html lang="fr">
<head>
    <title id="page_title">Facture - {{$facture['num_commande']}}</title>
</head>

<body>
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

    {{--Type de livraison--}}
    <p>Mode de retrait: {{ $facture['livraison'] ? "Livraison" : "Retrait en boutique" }}</p>

    {{--statut de la commande--}}
    @if($facture['statut'] == 'PAYE')
        <b>REGLEMENT EFFECTUE</b>
    @elseif($facture['statut'] == 'ANNULE')
        <b>FACTURE ANNULEE</b>
    @elseif($facture['statut'] == 'EN_ATTENTE')
        <b>FACTURE EN ATTENTE DE PAIEMENT</b>
    @else
        <b>FACTURE {{ $facture['statut'] }}</b>
    @endif

    {{--tableau--}}
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
                <td>{{ $facture['list_produits'][$index]['stocks']['prix'] }}</td>
                <td>{{ (int)$facture['list_produits'][$index]['stocks']['prix'] * (int)$facture['list_produits'][$index]['quantite'] }}</td>
            </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <td>Total TTC:</td>
            @if($facture['livraison'])
                <td>{{ $facture['total_facture'] - 2.90 }} €</td>
            @else
                <td>{{ $facture['total_facture'] }} €</td>
            @endif
        </tr>
    </table>
</body>

<footer>
    <p>Conditions génerales vendeur</p>
</footer>
</html>