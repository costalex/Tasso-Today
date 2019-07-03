<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header( 'Access-Control-Allow-Headers: Authorization, Content-Type');

//Rafraichissement avant execution des requete de la session si elle existe
Route::middleware('session', 'refreshAuth')->group(function () {

/**
 * Routes USER: Connect
 */
Route::get("confirmMail/{mail_code}", "UserInformationsController@checkMailCode")->name('confirmMail');
Route::post('/login/{provider}/clientCallback', 'UserInformationsController@handleProviderCallback')->name("callback");
Route::post("createUser", "UserInformationsController@create")->name('create');
Route::post("login", "UserInformationsController@connect")->name('connection')->middleware('session');
Route::post("newPassword", "UserInformationsController@newPassword")->name('newPassword');

/**
 * Logout
 */
Route::get('logout', 'UserInformationsController@logout')->name('logout')->middleware('session');

/**
 * Routes USER/CLIENT/ENTREPRISE: Informations, Mise  a jour, softdelete(compte)
 * REDIRECTION CLIENT/ENTREPRISE
 */
Route::resource("userInfos", "UserInformationsController",
	[
		'only' => ['show', 'update', 'destroy']
	])->middleware(['session', 'redirectUserType']);

/**
 * USER
 */
Route::put("userStatusUpdate", "UserInformationsController@userStatusUpdate")->middleware(['session','auth:api', 'can:isAdmin,App\User']);
Route::put("userInfosUpdate", "UserInformationsController@userInfosUpdate")->middleware(['session','auth:api']);

/**
 * ENTREPRISE
*/
Route::get("getEntreprisesFamilles/{famille_id}", "EntrepriseInformationsController@getEntrepriseListOrderByFamilleOrCategoriesID");
Route::get("getEntreprisesCategories/{famille_id}", "EntrepriseInformationsController@getEntrepriseListOrderByFamilleOrCategoriesID");
Route::get("getShopOrder", "EntrepriseInformationsController@getShopOrder")->middleware(['session','auth:api']);
Route::get("getShopInfosFor/{ville}/{nom}", "EntrepriseInformationsController@getPublicEntrepriseInfosByName");
Route::get("countProduitsUtiliseDansEtageres", "EntrepriseInformationsController@countProduitsUtiliseDansEtageres")->middleware(['session','auth:api']);
Route::post("addProduitsEntreprise", "EntrepriseInformationsController@addProduitsEntreprise")->middleware(['session','auth:api','can:isAdmin,App\Entreprise']);
Route::post("deleteProduitsEntreprise", "EntrepriseInformationsController@deleteProduitsEntreprise")->middleware(['session','auth:api','can:isAdmin,App\Entreprise']);
Route::post("checkProduitsList", "EntrepriseInformationsController@checkProduitsList")->middleware(['session','auth:api','can:isAdmin,App\Entreprise']);
Route::post("entrepriseList", "EntrepriseInformationsController@getEntrepriseList")->middleware(['session','auth:api','can:isAdmin,App\Entreprise']);
Route::put("updateShopOrder", "EntrepriseInformationsController@updateShopOrder")->middleware(['session','auth:api']);
Route::put("updateProduitsEntreprise", "EntrepriseInformationsController@updateProduitsEntreprise")->middleware(['session','auth:api']);

/**
 * PRODUITS GENERIQUES
 */
Route::resource("produitsInfos", "ProduitController",
	['only' => ['store']
	])->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::get("getGenProduitInfos/{ref_produit}", "ProduitController@getGenProduitInfosByRef")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::post("produitsList", "ProduitController@produitList")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::post("deleteImageProduit", "ProduitController@deleteImageProduit")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::post("updateNewSizeForImagesGenProduits", "ProduitController@updateNewSizeForImagesGenProduits")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::post("updateImageUrl", "ProduitController@updateImageUrl")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::post("updateImageProductName", "ProduitController@updateImageProductName")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);
Route::put("updateGenProduit", "ProduitController@updateGeneriqueProduitInformations")->middleware(['session','auth:api', 'can:isAdmin,App\Produit']);

/**
 * FAMILLES de produits (voir si besoin d'une regle d'acces puisque accessible aux utilisateurs non connectés)
 */
Route::get("familleList", "FamilleProduitController@index");

/**
 * CATEGORIES de produits (voir si besoin d'une regle d'acces puisque accessible aux utilisateurs non connectés)
 */
Route::resource("categorieList", "CategorieProduitController",
	['only' => ['show']
	]);

/**
 * TYPES de produits
 */
Route::resource("typeList", "TypeProduitController",
	['only' => ['show']
	])->middleware(['session', 'auth:api']);

/**
 * MARQUES de produits
 */
Route::get("marqueList/{nom}", "MarqueProduitController@autoCompletion")->middleware(['session','auth:api', 'can:isAdmin,App\MarqueProduit']);
Route::post("marqueAdd", "MarqueProduitController@addMarque")->middleware(['session','auth:api', 'can:isAdmin,App\MarqueProduit']);

/**
 * GROUPES
 */
Route::resource("groupList", "GroupeController", ['only' => 'index'])->middleware('session','auth:api');

/**
 * TYPE ACTIVITES
 */
Route::get('typeActiviteList', "TypeActiviteController@typeActiviteList");

/**
 * TYPE ENTREPRISE
 */
Route::get('typeEntrepriseList', "TypeEntrepriseController@typeEntrepriseList");

/**
 * VILLES
 */
Route::get('villeList', "VilleController@villeList")->middleware(['session']);

/**
 * DEMANDES
 */
Route::post("demandeList", "DemandeController@demandeList")->middleware(['session','auth:api', 'can:isAdmin,App\Demande']);

/**
 * RAYON
 */
Route::get("getRayonList", "RayonController@getRayonList")->middleware(['session','auth:api']);
Route::post("createRayon", "RayonController@createRayon")->middleware(['session','auth:api']);
Route::put("updateRayonInformations", "RayonController@updateRayonInformations")->middleware(['session','auth:api']);
Route::delete("deleteRayon/{rayon_nom}", "RayonController@deleteRayon")->middleware(['session','auth:api']);

/**
 * SOUS-RAYON
 */
Route::get("getSousRayonList/{rayon_nom}", "SousRayonController@getSousRayonList")->middleware(['session','auth:api']);
Route::post("createSousRayon", "SousRayonController@createSousRayon")->middleware(['session','auth:api']);
Route::post("deleteSousRayon", "SousRayonController@deleteSousRayon")->middleware(['session','auth:api']);
Route::put("updateSousRayonInformations", "SousRayonController@updateSousRayonInformations")->middleware(['session','auth:api']);

/**
 * ETAGERES
 */
Route::get("getEtageresList", "EtagereController@getEtageresList")->middleware(['session','auth:api']);
Route::get("getShopFor/{ville}/{entreprise_nom}", "EtagereController@getShopFor");
Route::post("createEtagere", "EtagereController@createEtagere")->middleware(['session','auth:api']);
Route::post("addProduitToEtagere", "EtagereController@addProduitToEtagere")->middleware(['session','auth:api']);
Route::post("deleteProduitToEtagere", "EtagereController@deleteProduitToEtagere")->middleware(['session','auth:api']);
Route::post("deleteEtagere", "EtagereController@deleteEtagere")->middleware(['session','auth:api']);
Route::put("updateEtagere", "EtagereController@updateEtagere")->middleware(['session','auth:api']);

/**
 * Couleur
 */
Route::get("getCouleurList", "CouleurController@getCouleurList")->middleware(['session','auth:api']);

/**
 * Facturation
 */
Route::get("getFactureList", "FactureController@getFactureList")->middleware(['session','auth:api']);
Route::get("getCommandList", "FactureController@getCommandesList")->middleware(['session','auth:api']);
Route::get("displayFacture/{num_cmd}", "FactureController@displayHTMLFact")->middleware(['session','auth:api']);
Route::get("displayCommande/{num_cmd}", "FactureController@displayHTMLCMD")->middleware(['session','auth:api']);
Route::get("downloadFacture/{num_cmd}", "FactureController@downloadPDFFact")->middleware(['session','auth:api'])->name('downloadFacture');
Route::get("downloadJustifCmd/{num_cmd}", "FactureController@downloadJustifCmd")->middleware(['session','auth:api'])->name('downloadFacture');
Route::post("createFacture", "FactureController@createFacture")->middleware(['session','auth:api']);
Route::post("updateCommandeStatut", "FactureController@updateEntrepriseCommandeStatut")->middleware(['session','auth:api']);

/**
 * Panier
 */
Route::post("updatePanierInformations", "FactureController@updatePanierInformations");

/**
 * PAIEMENT (STRIPE)
 */
Route::get("getPaiementCardList", "PaiementController@getPaiementCardList")->middleware(['session','auth:api']);
Route::post("createCustomerAccount", "PaiementController@createCustomerAccount")->middleware(['session','auth:api']);
Route::post("pay", "PaiementController@paiement")->middleware(['session','auth:api']);
Route::post("changeDefaultCard", "PaiementController@changeDefaultCard")->middleware(['session','auth:api']);
Route::post("deleteCard", "PaiementController@deleteCard")->middleware(['session','auth:api']);

/**
 * ORTOO
 */
Route::get("getLivraisonStatut/{num_cmd}", "FactureController@getLivraisonStatut")->middleware(['session','auth:api']);

/**
	 * Abonnements
	 */
Route::get("getAbonnementList", "AbonnementController@getAbonnementList")->middleware(['session','auth:api']);

/**
 * TESTS
 */
Route::put("updateEntreprise", "EntrepriseInformationsController@updateEntreprise")->middleware(['session','auth:api']);

});


