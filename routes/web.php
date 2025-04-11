<?php

use App\Http\Controllers\AntenneController;
use App\Http\Controllers\ArriveController;
use App\Http\Controllers\ArrondissementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CollectiveController;
use App\Http\Controllers\CollectivemoduleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommissionagrementController;
use App\Http\Controllers\CommissionmembreController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConventionController;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\DecretController;
use App\Http\Controllers\DemandeurController;
use App\Http\Controllers\DepartController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\DomaineController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailFormationController;
use App\Http\Controllers\EmargementcollectiveController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\EvaluateurController;
use App\Http\Controllers\FeuillepresencecollectiveController;
use App\Http\Controllers\FeuillepresenceController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IndemniteController;
use App\Http\Controllers\IndividuelleController;
use App\Http\Controllers\IngenieurController;
use App\Http\Controllers\InterneController;
use App\Http\Controllers\ListecollectiveController;
use App\Http\Controllers\LocaliteController;
use App\Http\Controllers\LoiController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NomminationController;
use App\Http\Controllers\OnfpevaluateurController;
use App\Http\Controllers\OperateurController;
use App\Http\Controllers\OperateureferenceController;
use App\Http\Controllers\OperateurequipementController;
use App\Http\Controllers\OperateurformateurController;
use App\Http\Controllers\OperateurlocaliteController;
use App\Http\Controllers\OperateurmoduleController;
use App\Http\Controllers\PchargeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\ProcesverbalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileOperateurController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ProjetlocaliteController;
use App\Http\Controllers\ProjetmoduleController;
use App\Http\Controllers\ReferentielController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationcollectiveController;
use App\Http\Controllers\ValidationformationController;
use App\Http\Controllers\ValidationIndividuelleController;
use App\Http\Controllers\ValidationmoduleController;
use App\Http\Controllers\ValidationoperateurController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['can:update,role'])->group(function () {
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});

Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
    ->middleware('can:delete,role')
    ->name('roles.destroy');

Route::middleware(['can:update,permission'])->group(function () {
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
});

Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
    ->middleware('can:delete,permission')
    ->name('permissions.destroy');

Route::group(['middleware' => ['XSS']], function () {
    Route::get('/', [UneController::class, 'unePage'])->name('accueil');

    Route::get('/login', [ProfileController::class, 'loginPage'])->name('login');
    Route::get('/register-page', [ProfileController::class, 'registerPage'])->name('register-page');

    Route::get('/register-operateur', [ProfileController::class, 'registerOperateur'])->name('register-operateur');
});
Route::group(['middleware' => ['XSS']], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profiles/{id}', [ProfileOperateurController::class, 'update'])->name('profile.updated');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/home', [UserController::class, 'homePage'])->name('home');
        Route::get('/profil', [ProfileController::class, 'profilePage'])->name('profil');
        Route::get('/user', [UserController::class, 'index'])->name('user.index');

        Route::get('/modal', [RegionController::class, 'modal'])->name('modal');
        Route::post('/updateRegion', [RegionController::class, 'updateRegion'])->name('updateRegion');
        Route::post('/rejeterIndividuelle', [IndividuelleController::class, 'rejeterIndividuelle'])->name('rejeterIndividuelle');
        Route::post('/addRegion', [RegionController::class, 'addRegion'])->name('addRegion');

        Route::post('sendWelcomeEmail', [EmailController::class, 'sendWelcomeEmail'])->name('sendWelcomeEmail');
        Route::post('sendWelcomeEmailCol', [EmailController::class, 'sendWelcomeEmailCol'])->name('sendWelcomeEmailCol');
        Route::post('sendFormationEmail', [EmailFormationController::class, 'sendFormationEmail'])->name('sendFormationEmail');
        Route::post('sendFormationEmailCol', [EmailFormationController::class, 'sendFormationEmailCol'])->name('sendFormationEmailCol');

        Route::post('sendFormationSMS', [SMSController::class, 'sendFormationSMS'])->name('sendFormationSMS');
        Route::post('sendWelcomeSMS', [SMSController::class, 'sendWelcomeSMS'])->name('sendWelcomeSMS');

        Route::put('/arrives/{arriveId}/delete', [ArriveController::class, 'destroy']);
        Route::put('/departs/{departId}/delete', [DepartController::class, 'destroy']);

        Route::get('/roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionsToRole']);
        Route::put('/roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionsToRole']);

        Route::get('/employes/{employeId}/give-lois', [EmployeController::class, 'addLoisToEmploye']);
        Route::put('/employes/{employeId}/give-lois', [EmployeController::class, 'giveLoisToEmploye']);

        Route::get('/employes/{employeId}/give-decrets', [EmployeController::class, 'addDecretToEmploye']);
        Route::put('/employes/{employeId}/give-decrets', [EmployeController::class, 'giveDecretToEmploye']);

        Route::get('/employes/{employeId}/give-procesverbals', [EmployeController::class, 'addProcesverbalToEmploye']);
        Route::put('/employes/{employeId}/give-procesverbals', [EmployeController::class, 'giveProcesverbalToEmploye']);

        Route::get('/employes/{employeId}/give-decisions', [EmployeController::class, 'addDecisionToEmploye']);
        Route::put('/employes/{employeId}/give-decisions', [EmployeController::class, 'giveDecisionToEmploye']);

        Route::get('/employes/{employeId}/give-articles', [EmployeController::class, 'addArticleToEmploye']);
        Route::put('/employes/{employeId}/give-articles', [EmployeController::class, 'giveArticleToEmploye']);

        Route::get('/employes/{employeId}/give-nomminations', [EmployeController::class, 'addNomminationToEmploye']);
        Route::put('/employes/{employeId}/give-nomminations', [EmployeController::class, 'giveNomminationToEmploye']);

        Route::get('/employes/{employeId}/give-indemnites', [EmployeController::class, 'addIndemniteToEmploye']);
        Route::put('/employes/{employeId}/give-indemnites', [EmployeController::class, 'giveIndemniteToEmploye']);

        Route::get('/roles/{roleName}/get-users', [RoleController::class, 'getUsersToRole']);

        Route::get('arrive-imputations/{id}', [ArriveController::class, 'arriveImputation'])->name('arrive-imputations');
        Route::post('/arrive/fetch', [ArriveController::class, 'fetch'])->name('arrive.fetch');

        Route::get('depart-imputations/{id}', [DepartController::class, 'departImputation'])->name('depart-imputations');
        Route::post('/depart/fetch', [DepartController::class, 'fetch'])->name('depart.fetch');

        Route::post('/comments/{courrier}', [CommentController::class, 'store'])->name('comments.store');
        Route::post('/commentReply/{comment}', [CommentController::class, 'storeCommentReply'])->name('comments.storeReply');

        Route::get('/showFromNotification/{courrier}/{notification}', [CourrierController::class, 'showFromNotification'])->name('courriers.showFromNotification');

        Route::post('couponArrive', [ArriveController::class, 'couponArrive'])->name('couponArrive');
        Route::post('couponDepart', [DepartController::class, 'couponDepart'])->name('couponDepart');
        Route::get('file-decision/{id}', [EmployeController::class, 'fileDecision'])->name('file-decision');

        Route::post('pvEvaluation', [FormationController::class, 'pvEvaluation'])->name('pvEvaluation');
        Route::post('pvVierge', [FormationController::class, 'pvVierge'])->name('pvVierge');
        Route::post('pvViergeCol', [FormationController::class, 'pvViergeCol'])->name('pvViergeCol');
        Route::post('ficheSuivi', [FormationController::class, 'ficheSuivi'])->name('ficheSuivi');
        Route::post('feuillePresence', [FormationController::class, 'feuillePresence'])->name('feuillePresence');
        Route::post('feuillePresenceCol', [FormationController::class, 'feuillePresenceCol'])->name('feuillePresenceCol');
        Route::post('feuillePresenceJour', [FormationController::class, 'feuillePresenceJour'])->name('feuillePresenceJour');
        Route::post('feuillePresenceColJour', [FormationController::class, 'feuillePresenceColJour'])->name('feuillePresenceColJour');
        Route::post('feuillePresenceTous', [FormationController::class, 'feuillePresenceTous'])->name('feuillePresenceTous');
        Route::post('feuillePresenceColTous', [FormationController::class, 'feuillePresenceColTous'])->name('feuillePresenceColTous');
        Route::post('feuillePresenceFinale', [FormationController::class, 'feuillePresenceFinale'])->name('feuillePresenceFinale');
        Route::post('feuillePresenceColFinale', [FormationController::class, 'feuillePresenceColFinale'])->name('feuillePresenceColFinale');
        Route::post('etatTransport', [FormationController::class, 'etatTransport'])->name('etatTransport');
        Route::post('etatTransportCol', [FormationController::class, 'etatTransportCol'])->name('etatTransportCol');

        Route::post('pvEvaluationCol', [FormationController::class, 'pvEvaluationCol'])->name('pvEvaluationCol');
        Route::post('ficheSuiviCol', [FormationController::class, 'ficheSuiviCol'])->name('ficheSuiviCol');

        Route::get('notifications/', [CourrierController::class, 'notifications'])->name('notifications');
        Route::post('validationmessage', [IndividuelleController::class, 'validationsRejetMessage'])->name('validationmessage');

        Route::get('modulelocalite/{idmodule}/{idlocalite}', [ModuleController::class, 'modulelocalite'])->name('modulelocalite');
        Route::get('modulelocalitestatut/{idmodule}/{idlocalite}/{statut}', [ModuleController::class, 'modulelocalitestatut'])->name('modulelocalitestatut');

        Route::get('modulestatut/{statut}/{idmodule}', [ModuleController::class, 'modulestatut'])->name('modulestatut');
        Route::get('modulestatutlocalite/{idlocalite}/{idmodule}/{statut}', [ModuleController::class, 'modulestatutlocalite'])->name('modulestatutlocalite');

        Route::get('formationdemandeurs/{idformation}/{idmodule}/{idlocalite}', [FormationController::class, 'addformationdemandeurs']);
        Route::put('formationdemandeurs/{idformation}/{idmodule}/{idlocalite}', [FormationController::class, 'giveformationdemandeurs']);

        Route::get('formationemargement', [EmargementController::class, 'formationemargement'])->name('formationemargement');
        Route::get('formationemargementcollective', [EmargementcollectiveController::class, 'formationemargementcollective'])->name('formationemargementcollective');

        Route::get('formationdemandeurscollectives/{idformation}/{idcollectivemodule}/{idlocalite}', [FormationController::class, 'addformationdemandeurscollectives']);
        Route::put('formationdemandeurscollectives/{idformation}/{idcollectivemodule}/{idlocalite}', [FormationController::class, 'giveformationdemandeurscollectives']);

        Route::get('formationoperateurs/{idformation}/{idmodule}/{idlocalite}', [FormationController::class, 'addformationoperateurs']);
        Route::put('formationoperateurs/{idformation}/{idmodule}/{idlocalite}', [FormationController::class, 'giveformationoperateurs']);

        Route::get('formationcollectiveoperateurs/{idformation}/{idcollectivemodule}/{idlocalite}', [FormationController::class, 'addformationcollectiveoperateurs']);
        Route::put('formationcollectiveoperateurs/{idformation}/{idcollectivemodule}/{idlocalite}', [FormationController::class, 'giveformationcollectiveoperateurs']);

        Route::get('formationmodules/{idformation}/{idlocalite}', [FormationController::class, 'addformationmodules']);
        Route::put('formationmodules/{idformation}', [FormationController::class, 'giveformationmodules']);

        Route::get('formationcollectivemodules/{idformation}/{idlocalite}', [FormationController::class, 'addformationcollectivemodules']);
        Route::put('formationcollectivemodules/{idformation}', [FormationController::class, 'giveformationcollectivemodules']);

        Route::get('formationingenieurs/{idformation}', [FormationController::class, 'addformationingenieurs']);
        Route::put('formationingenieurs/{idformation}', [FormationController::class, 'giveformationingenieurs']);

        Route::get('collectiveingenieurs/{id}', [CollectiveController::class, 'addcollectiveingenieurs'])->name('addcollectiveingenieurs');
        Route::put('collectiveingenieurs/{id}', [CollectiveController::class, 'givecollectiveingenieurs'])->name('givecollectiveingenieurs');

        Route::get('formationcollectives/{idformation}/{idlocalite}', [FormationController::class, 'addformationcollectives']);
        Route::put('formationcollectives/{idformation}', [FormationController::class, 'giveformationcollectives'])->name('formationcollectives');
        Route::put('retirermoduleformation/{id}', [FormationController::class, 'retirermoduleformation'])->name('retirermoduleformation');

        Route::get('moduleformationcollectives/{idformation}/{idlocalite}', [FormationController::class, 'addmoduleformationcollectives']);
        Route::put('moduleformationcollectives/{idformation}', [FormationController::class, 'givemoduleformationcollectives']);

        Route::get('moduleformations/{idformation}/{idlocalite}', [FormationController::class, 'addmoduleformations']);
        Route::get('collectivemoduleformations/{idformation}/{idlocalite}', [FormationController::class, 'addcollectivemoduleformations']);

        Route::get('collectiveformations/{idformation}/{idlocalite}', [FormationController::class, 'addcollectiveformations']);
        Route::put('collectiveformations/{idformation}/{idlocalite}', [FormationController::class, 'givecollectiveformations']);

        Route::put('indisponibles/{idformation}', [FormationController::class, 'giveindisponibles'])->name('giveindisponibles');
        Route::put('disponibles/{id}', [FormationController::class, 'givedisponibles'])->name('givedisponibles');
        Route::put('collectiveindisponibles/{idformation}', [FormationController::class, 'givecollectiveindisponibles']);

        Route::put('remiseAttestations/{idformation}', [FormationController::class, 'giveremiseAttestations']);

        Route::post('/addIndividuelle', [IndividuelleController::class, 'addIndividuelle'])->name('addIndividuelle');
        Route::post('/addCollective', [CollectiveController::class, 'addCollective'])->name('addCollective');

        Route::get('/showIndividuelle/{id}', [DemandeurController::class, 'showIndividuelle'])->name('showIndividuelle');
        Route::get('/showCollective/{id}', [DemandeurController::class, 'showCollective'])->name('showCollective');

        Route::get('/demandesIndividuelles', [IndividuelleController::class, 'demandesIndividuelle'])->name('demandesIndividuelle');
        Route::post('/showIndividuelleProjet', [IndividuelleController::class, 'showIndividuelleProjet'])->name('showIndividuelleProjet');

        Route::get('/projetsIndividuelle/{id}', [ProjetController::class, 'projetsIndividuelle'])->name('projetsIndividuelle');
        Route::put('/ouvrirProjet/{id}', [ProjetController::class, 'ouvrirProjet'])->name('ouvrirProjet');
        Route::put('/fermerProjet/{id}', [ProjetController::class, 'fermerProjet'])->name('fermerProjet');

        Route::post('/addModule', [ModuleController::class, 'addModule'])->name('addModule');

        Route::post('/addDomaine', [DomaineController::class, 'addDomaine'])->name('addDomaine');

        Route::post('/addcollectiveModule', [CollectivemoduleController::class, 'addcollectiveModule'])->name('addcollectiveModule');

        Route::get('/demandesCollectives', [CollectiveController::class, 'demandesCollective'])->name('demandesCollective');

        Route::get('/mesformations', [IndividuelleController::class, 'mesformations'])->name('mesformations');
        Route::get('/nouvellesformations', [IndividuelleController::class, 'nouvellesformations'])->name('nouvellesformations');
        Route::get('/mescourriers', [ArriveController::class, 'mescourriers'])->name('mescourriers');
        Route::get('/ingenieurformations', [UserController::class, 'ingenieurformations'])->name('ingenieurformations');

        Route::post('/autocomplete/fetch', [OperateurController::class, 'fetch'])->name('autocomplete.fetch');
        Route::post('/conventions/fetch', [ConventionController::class, 'fetch'])->name('conventions.fetch');
        Route::post('/autocomplete/fetchModuleOperateur', [OperateurController::class, 'fetchModuleOperateur'])->name('autocomplete.fetchModuleOperateur');

        Route::post('/formationTerminer', [FormationController::class, 'formationTerminer'])->name('formationTerminer');

        Route::post('/formationcollectiveTerminer', [FormationController::class, 'formationcollectiveTerminer'])->name('formationcollectiveTerminer');

        Route::put('notedemandeurs/{idformation}', [FormationController::class, 'givenotedemandeurs']);
        Route::put('notedemandeurscollectives/{idformation}', [FormationController::class, 'givenotedemandeursCollective']);

        Route::patch('/updateObservations', [FormationController::class, 'updateObservations'])->name('individuelles.updateObservations');
        Route::patch('/updateAgentSuivi', [FormationController::class, 'updateAgentSuivi'])->name('formations.updateAgentSuivi');
        Route::patch('/updateMembresJury', [FormationController::class, 'updateMembresJury'])->name('formations.updateMembresJury');
        Route::patch('/ajouterJours', [FormationController::class, 'ajouterJours'])->name('formations.ajouterJours');
        Route::patch('/ajouterJoursCol', [FormationController::class, 'ajouterJoursCol'])->name('formations.ajouterJoursCol');
        Route::patch('/updateObservationsCollective', [FormationController::class, 'updateObservationsCollective'])->name('listecollectives.updateObservationsCollective');
        Route::patch('/updateAttestations', [FormationController::class, 'updateAttestations'])->name('individuelles.updateAttestations');
        Route::patch('/updateAttestationsCol', [FormationController::class, 'updateAttestationsCol'])->name('individuelles.updateAttestationsCol');

        Route::post('/addProjet', [ProjetController::class, 'addProjet'])->name('addProjet');

        Route::get('/showLocalites/{id}', [ProjetlocaliteController::class, 'showLocalites'])->name('showLocalites');

        Route::get('/showReference/{id}', [OperateurController::class, 'showReference'])->name('showReference');
        Route::get('/showEquipement/{id}', [OperateurController::class, 'showEquipement'])->name('showEquipement');
        Route::get('/showFormateur/{id}', [OperateurController::class, 'showFormateur'])->name('showFormateur');
        Route::get('/showLocalite/{id}', [OperateurController::class, 'showLocalite'])->name('showLocalite');

        Route::put('/validateOperateur/{id}', [OperateurController::class, 'validateOperateur'])->name('validateOperateur');
        Route::put('/agreerOperateur/{id}', [OperateurController::class, 'agreerOperateur'])->name('agreerOperateur');
        Route::get('/agrement', [OperateurController::class, 'agrement'])->name('agrement');

        Route::get('commisionagrement/{idcommissionagrement}', [CommissionagrementController::class, 'addcommisionagrement']);
        Route::put('commisionagrement/{idcommissionagrement}', [CommissionagrementController::class, 'givecommisionagrement']);

        Route::get('/addopCommission/{id}', [CommissionagrementController::class, 'addopCommission'])->name('addopCommission');
        Route::get('/agrements/{id}', [OperateurController::class, 'agrements'])->name('agrements');
        Route::get('/showAgrement/{id}', [OperateurController::class, 'showAgrement'])->name('showAgrement');
        Route::put('/nonRetenu/{id}', [OperateurController::class, 'nonRetenu'])->name('nonRetenu');
        Route::get('/showAgreer/{id}', [CommissionagrementController::class, 'showAgreer'])->name('showAgreer');
        Route::get('/showReserve/{id}', [CommissionagrementController::class, 'showReserve'])->name('showReserve');
        Route::get('/showRejeter/{id}', [CommissionagrementController::class, 'showRejeter'])->name('showRejeter');
        Route::put('/retirerOperateur/{id}', [OperateurController::class, 'retirerOperateur'])->name('retirerOperateur');

        Route::get('/devenirOperateurs', [OperateurController::class, 'devenirOperateur'])->name('devenirOperateur');
        Route::post('/addOperateur', [OperateurController::class, 'addOperateur'])->name('addOperateur');

        Route::post('/renewOperateur', [OperateurController::class, 'renewOperateur'])->name('renewOperateur');

        Route::put('/Validatelistecollective/{id}', [ListecollectiveController::class, 'Validatelistecollective'])->name('Validatelistecollective');

        Route::get('regionsmodule/{idlocalite}', [RegionController::class, 'regionsmodule'])->name('regionsmodule');
        Route::get('regionstatut/{idlocalite}/{statut}', [RegionController::class, 'regionstatut'])->name('regionstatut');

        Route::get('directionAgent/{iddirection}', [DirectionController::class, 'adddirectionAgent']);
        Route::put('directionAgent/{iddirection}', [DirectionController::class, 'givedirectionAgent']);

        Route::get('directionChef/{iddirection}', [DirectionController::class, 'adddirectionChef']);
        Route::put('directionChef/{iddirection}', [DirectionController::class, 'givedirectionChef']);

        Route::post('/retirerEmploye', [DirectionController::class, 'retirerEmploye'])->name('retirerEmploye');

        Route::post('lettreEvaluation', [FormationController::class, 'lettreEvaluation'])->name('lettreEvaluation');
        Route::post('abeEvaluation', [FormationController::class, 'abeEvaluation'])->name('abeEvaluation');

        Route::post('/addCourrierOperateur', [ArriveController::class, 'addCourrierOperateur'])->name('addCourrierOperateur');

        Route::get('individuelles/rapports', [IndividuelleController::class, 'rapports'])->name('individuelles.rapport');
        Route::post('individuelles/rapports', [IndividuelleController::class, 'generateRapport']);

        Route::get('collectives/rapports', [CollectiveController::class, 'rapports'])->name('collectives.rapport');
        Route::post('collectives/rapports', [CollectiveController::class, 'generateRapport']);

        Route::get('arrives/rapports', [ArriveController::class, 'rapports'])->name('arrives.rapport');
        Route::post('arrives/rapports', [ArriveController::class, 'generateRapport']);

        Route::get('departs/rapports', [DepartController::class, 'rapports'])->name('departs.rapport');
        Route::post('departs/rapports', [DepartController::class, 'generateRapport']);

        Route::get('operateurs/rapports', [OperateurController::class, 'rapports'])->name('operateurs.rapport');
        Route::post('operateurs/rapports', [OperateurController::class, 'generateRapport']);

        Route::get('operateurmodules/rapports', [OperateurmoduleController::class, 'rapports'])->name('operateurmodules.rapport');
        Route::post('operateurmodules/rapports', [OperateurmoduleController::class, 'generateRapport']);

        Route::get('operateurs/index', [OperateurController::class, 'index'])->name('operateurs.report');
        Route::post('operateurs/index', [OperateurController::class, 'generateReport']);

        Route::get('/operateurs/agreer', [OperateurController::class, 'agreer'])->name('operateurs.agreer');
        Route::get('/operateurs/expirer', [OperateurController::class, 'expirer'])->name('operateurs.expirer');

        Route::get('modules/rapports', [ModuleController::class, 'rapports'])->name('modules.rapport');
        Route::post('modules/rapports', [ModuleController::class, 'generateRapport']);

        Route::get('formations/rapports', [FormationController::class, 'rapports'])->name('formations.rapport');
        Route::post('formations/rapports', [FormationController::class, 'generateRapport']);

        Route::get('users/rapports', [UserController::class, 'rapports'])->name('users.rapport');
        Route::post('users/rapports', [UserController::class, 'generateRapport']);

        Route::get('individuelles/index', [IndividuelleController::class, 'index'])->name('individuelles.report');
        Route::post('individuelles/index', [IndividuelleController::class, 'generateReport']);
        Route::get('listecollectives/index', [ListecollectiveController::class, 'index'])->name('listecollectives.report');
        Route::post('listecollectives/index', [ListecollectiveController::class, 'generateReport']);

        Route::get('users/index', [UserController::class, 'index'])->name('users.report');
        Route::post('users/index', [UserController::class, 'generateReport']);

        Route::get('arrives/index', [ArriveController::class, 'index'])->name('arrives.report');
        Route::post('arrives/index', [ArriveController::class, 'generateReport']);

        Route::get('departs/index', [DepartController::class, 'index'])->name('departs.report');
        Route::post('departs/index', [DepartController::class, 'generateReport']);

        Route::get('internes/index', [InterneController::class, 'index'])->name('internes.report');
        Route::post('internes/index', [InterneController::class, 'generateReport']);

        Route::get('formes/rapportsformes', [FormationController::class, 'rapportsformes'])->name('formes.rapport');
        Route::post('formes/rapportsformes', [FormationController::class, 'generateRapportFormes']);

        Route::get('formes/formesCollective', [FormationController::class, 'formesCollective'])->name('formesCollective.rapport');
        Route::post('formes/formesCollective', [FormationController::class, 'generateRapportFormesCollective']);

        Route::get('suivi/suiviformes', [FormationController::class, 'suiviformes'])->name('suiviformes.suivi-individuelle');
        Route::get('suivi/suiviformesCol', [FormationController::class, 'suiviformesCol'])->name('suiviformes.suivi-collective');
        Route::post('suivi/suiviformes', [FormationController::class, 'generateSuiviFormes']);

        Route::put('suivreformes/{id}', [FormationController::class, 'SuivreFormes'])->name('SuivreFormes');
        Route::post('formesuivi', [FormationController::class, 'FormeSuivi'])->name('FormeSuivi');
        Route::put('nepasSuivre/{id}', [FormationController::class, 'nepasSuivre'])->name('nepasSuivre');
        Route::put('suivreTous/{id}', [FormationController::class, 'suivreTous'])->name('suivreTous');

        Route::put('suivreformesCol/{id}', [FormationController::class, 'SuivreFormesCol'])->name('SuivreFormesCol');
        Route::post('formeColsuivi', [FormationController::class, 'FormeColSuivi'])->name('FormeColSuivi');
        Route::put('nepasSuivreCol/{id}', [FormationController::class, 'nepasSuivreCol'])->name('nepasSuivreCol');
        Route::put('suivretousCol/{id}', [FormationController::class, 'suivretousCol'])->name('suivretousCol');

        Route::get('regions/rapports', [RegionController::class, 'rapports'])->name('regions.rapports');
        Route::post('regions/rapports', [RegionController::class, 'generateRapport']);

        Route::put('/validerModuleCollective', [CollectivemoduleController::class, 'validerModuleCollective'])->name('validerModuleCollective');
        Route::put('/rejeterModuleCollective', [CollectivemoduleController::class, 'rejeterModuleCollective'])->name('rejeterModuleCollective');
        Route::put('/supprimerModuleCollective', [CollectivemoduleController::class, 'supprimerModuleCollective'])->name('supprimerModuleCollective');

        Route::post('/individuellesStore', [IndividuelleController::class, 'individuellesStore'])->name('individuellesStore');

        Route::get('/showprojetProgramme', [ProjetController::class, 'showprojetProgramme'])->name('showprojetProgramme');

        Route::get('formations/reports', [FormationController::class, 'reports'])->name('formations.reports');
        Route::post('formations/reports', [FormationController::class, 'generateReport']);

        Route::put('fileDestroy', [FileController::class, 'fileDestroy'])->name('fileDestroy');
        Route::get('projetsBeneficiaire/{id}', [ProjetController::class, 'projetsBeneficiaire'])->name('projetsBeneficiaire');
        Route::get('showConventions', [FormationController::class, 'showConventions'])->name('showConventions');
        Route::get('showMasculin', [IndividuelleController::class, 'showMasculin'])->name('showMasculin');
        Route::get('showFeminin', [IndividuelleController::class, 'showFeminin'])->name('showFeminin');

        Route::get('/postes/create', [PosteController::class, 'create'])->name('postes.create');
        Route::post('/postes', [PosteController::class, 'store'])->name('postes.store');
        Route::get('/postes/{poste}', [PosteController::class, 'show'])->name('postes.show');

        Route::put('/alaunes', [UneController::class, 'alaUne'])->name('alaunes');
        Route::put('/suprimeralaunes', [UneController::class, 'suprimeralaUne'])->name('suprimeralaunes');
        Route::put('/uneContacts', [ContactController::class, 'uneContacts'])->name('uneContacts');

        Route::put('observations/{id}', [OperateurController::class, 'observations'])->name('observations');

        Route::post('ficheSynthese', [OperateurController::class, 'ficheSynthese'])->name('ficheSynthese');
        Route::post('ficheSyntheseOperateur', [OperateurController::class, 'ficheSyntheseOperateur'])->name('ficheSyntheseOperateur');

        Route::post('lettreAgrement', [OperateurController::class, 'lettreAgrement'])->name('lettreAgrement');
        Route::post('lettreOperateur', [OperateurController::class, 'lettreOperateur'])->name('lettreOperateur');

        Route::get('arrivesop', [ArriveController::class, 'arrivesop'])->name('arrivesop');

        Route::patch('/operateurs/{id}', [OperateurController::class, 'updated'])->name('operateurs.updated');

        Route::put('/agreerAllModuleOperateur/{id}', [OperateurController::class, 'agreerAllModuleOperateur'])->name('agreerAllModuleOperateur');
        Route::put('/resetuserPassword/{id}', [UserController::class, 'resetuserPassword'])->name('resetuserPassword');

        Route::get('backup', [UserController::class, 'backup'])->name('backup');

        /* Route::get('demandesdg', [IndividuelleController::class, 'demandesdg'])->name('demandesdg');
        Route::get('demandesth', [IndividuelleController::class, 'demandesth'])->name('demandesth');
        Route::get('demandeszig', [IndividuelleController::class, 'demandeszig'])->name('demandeszig');
        Route::get('demandeskd', [IndividuelleController::class, 'demandeskd'])->name('demandeskd');
        Route::get('demandeskl', [IndividuelleController::class, 'demandeskl'])->name('demandeskl');
        Route::get('demandessl', [IndividuelleController::class, 'demandessl'])->name('demandessl');
        Route::get('demandeskg', [IndividuelleController::class, 'demandeskg'])->name('demandeskg');
        Route::get('demandesmt', [IndividuelleController::class, 'demandesmt'])->name('demandesmt');
        Route::get('demandesdl', [IndividuelleController::class, 'demandesdl'])->name('demandesdl'); */

        Route::post('/send-training-start-email/{trainingId}', [FormationController::class, 'sendTrainingStartEmail'])->name('send-training-start-email');

        Route::put('/confirmer/{id}', [IndividuelleController::class, 'confirmer'])->name('confirmer');
        Route::put('/decliner/{id}', [IndividuelleController::class, 'decliner'])->name('decliner');
        Route::get('/jurycommissionagrements/{id}', [CommissionagrementController::class, 'jury'])->name('jurycommissionagrements.jury');
        Route::patch('/addMembreJury/{id}', [CommissionagrementController::class, 'addMembreJury'])->name('addMembreJury');

        Route::get('/users/actifs', [UserController::class, 'actifs'])->name('users.actifs');
        Route::get('/users/inactifs', [UserController::class, 'inactifs'])->name('users.inactifs');
        Route::get('/users/corbeille', [UserController::class, 'corbeille'])->name('users.corbeille');
        Route::get('/users/restored', [UserController::class, 'restored'])->name('users.restored');
        Route::get('/users/online', [UserController::class, 'showOnlineUsers'])->name('users.online');
        Route::get('/users/demandeurs', [UserController::class, 'demandeursIndividuel'])->name('demandeurs.individuel');
        Route::get('/users/individuelle_collective', [UserController::class, 'individuelleCollective'])->name('users.individuelle_collective');
        Route::get('/demandeurs/{id}', [UserController::class, 'showDemandeur'])->name('demandeurs.show');

        Route::delete('/profile/image', [ProfileController::class, 'destroyImage'])->name('profile.image.destroy');

        Route::post('/collectives/fetch', [CollectiveController::class, 'fetch'])->name('collectives.fetch');
        Route::get('/get-objet-by-numero', [CollectiveController::class, 'getObjetByNumero'])->name('getObjetByNumero');

        Route::post('/import-arrives', [ImportController::class, 'importArrives'])->name('import.arrives');
        Route::get('/import-arrives', [ImportController::class, 'importA'])->name('importA');

        Route::post('/import-operateurs', [ImportController::class, 'importOperateurs'])->name('import.operateurs');
        Route::get('/import-operateurs', [ImportController::class, 'importO'])->name('importO');

        Route::get('/regiondemandeurs/{id}', [RegionController::class, 'demandeur'])->name('regiondemandeurs.show');

        /* Pour visualiser la page d'erreur */
        /* Route::get('/errors/restored', [UserController::class, 'errors'])->name('users.errors'); */

        /* Vues ressouces */
        Route::resource('/users', UserController::class);
        Route::resource('/permissions', PermissionController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/courriers', CourrierController::class);
        Route::resource('/arrives', ArriveController::class);
        Route::resource('/departs', DepartController::class);
        Route::resource('/internes', InterneController::class);
        Route::resource('/directions', DirectionController::class);
        Route::resource('/employes', EmployeController::class);
        Route::resource('/regions', RegionController::class);
        Route::resource('/departements', DepartementController::class);
        Route::resource('/arrondissements', ArrondissementController::class);
        Route::resource('/communes', CommuneController::class);
        Route::resource('/categories', CategorieController::class);
        Route::resource('/fonctions', FonctionController::class);
        Route::resource('/lois', LoiController::class);
        Route::resource('/decrets', DecretController::class);
        Route::resource('/procesverbals', ProcesverbalController::class);
        Route::resource('/decisions', DecisionController::class);
        Route::resource('/articles', ArticleController::class);
        Route::resource('/nomminations', NomminationController::class);
        Route::resource('/indemnites', IndemniteController::class);
        Route::resource('/demandeurs', DemandeurController::class);
        Route::resource('/individuelles', IndividuelleController::class);
        Route::resource('/collectives', CollectiveController::class);
        Route::resource('/pcharges', PchargeController::class);
        Route::resource('/validation-individuelles', ValidationIndividuelleController::class);
        Route::resource('/validation-collectives', ValidationcollectiveController::class);
        Route::resource('/validation-formations', ValidationformationController::class);
        Route::resource('/localites', LocaliteController::class);
        Route::resource('/modules', ModuleController::class);
        Route::resource('/formations', FormationController::class);
        Route::resource('/operateurs', OperateurController::class);
        Route::resource('/operateurmodules', OperateurmoduleController::class);
        Route::resource('/projetmodules', ProjetmoduleController::class);
        Route::resource('/validation-operateur-modules', ValidationmoduleController::class);
        Route::resource('/validation-operateur', ValidationoperateurController::class);
        Route::resource('/collectives', CollectiveController::class);
        Route::resource('/domaines', DomaineController::class);
        Route::resource('/secteurs', SecteurController::class);
        Route::resource('/collectivemodules', CollectivemoduleController::class);
        Route::resource('/listecollectives', ListecollectiveController::class);
        Route::resource('/ingenieurs', IngenieurController::class);
        Route::resource('/projets', ProjetController::class);
        Route::resource('/projetlocalites', ProjetlocaliteController::class);
        Route::resource('/operateureferences', OperateureferenceController::class);
        Route::resource('/operateurequipements', OperateurequipementController::class);
        Route::resource('/operateurformateurs', OperateurformateurController::class);
        Route::resource('/operateurlocalites', OperateurlocaliteController::class);
        Route::resource('/commissionagrements', CommissionagrementController::class);
        Route::resource('/evaluateurs', EvaluateurController::class);
        Route::resource('/onfpevaluateurs', OnfpevaluateurController::class);
        Route::resource('/files', FileController::class);
        Route::resource('/referentiels', ReferentielController::class);
        Route::resource('/conventions', ConventionController::class);
        Route::resource('/postes', PosteController::class);
        Route::resource('/unes', UneController::class);
        Route::resource('/antennes', AntenneController::class);
        Route::resource('/emargements', EmargementController::class);
        Route::resource('/emargementcollectives', EmargementcollectiveController::class);
        Route::resource('/feuillepresences', FeuillepresenceController::class);
        Route::resource('/feuillepresencecollectives', FeuillepresencecollectiveController::class);
        Route::resource('/commissionmembres', CommissionmembreController::class);

        Route::middleware('admin')->group(function () {
            Route::get('/manuels', [BookController::class, 'index'])->name('manuels.index');
            Route::get('/manuels/create', [BookController::class, 'create'])->name('manuels.create');
            Route::post('/manuels', [BookController::class, 'store'])->name('manuels.store');
            Route::delete('/manuels/{id}', [BookController::class, 'destroy'])->name('manuels.destroy');
            Route::get('manuels/{id}/edit', [BookController::class, 'edit'])->name('manuels.edit');
            Route::put('manuels/{id}', [BookController::class, 'update'])->name('manuels.update');

        });
    });
    Route::resource('/contacts', ContactController::class);
    Route::get('/services-details', [ContactController::class, 'servicesDetails'])->name('services.details');
    Route::get('nos-modules', [ContactController::class, 'nosModules'])->name('nos-modules');

    Route::get('/guide', function () {
        $path = public_path('guide.pdf');

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    });

});

/* Route::get('/book/view/{filename}', [BookController::class, 'show'])->name('book.view'); */
Route::get('/manuel/view/{filename}', [BookController::class, 'show'])->name('manuel.view');
Route::get('/manuels/default', [BookController::class, 'showDefault'])->name('manuels.showDefault');

require __DIR__ . '/auth.php';
