<?php

namespace App\Http\Controllers;

use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationPriseEnChargeController extends Controller
{
    /** Année scolaire concernée (formats acceptés tels qu'ils peuvent être stockés en base). */
    private const ANNEE_SCOLAIRE = ['2026-2027'];

    /**
     * Vérifie le dossier d'un candidat à partir de son CIN/NIN, sa date de
     * naissance, son nom, son prénom et son email, puis affiche le résultat.
     */
    public function verifier(Request $request)
    {
        // Normalise le NIN : sans espaces ni tirets, en majuscules (ex. 2B01201100050)
        $request->merge([
            'cin' => strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $request->input('cin'))),
        ]);

        $data = $request->validate([
            'prenom'         => ['required', 'string', 'max:100'],
            'nom'            => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date', 'before:today'],
            /* 'cin'            => ['required', 'digits:13'], */
            /* 'cin'            => ['required', 'regex:/^[0-9A-Z]{13}$/'], */
            'cin' => ['required', 'regex:/^[0-9A-Z]{13,14}$/'],
            'email'          => ['required', 'email', 'max:150'],
        ], [
            /* 'cin.digits' => 'Le numéro de carte d\'identité (NIN) doit comporter 13 chiffres.', */
            'cin.regex' => 'Le numéro de carte d\'identité (NIN) doit comporter 13 ou 14 caractères (chiffres et lettres), par exemple 2B01201100050.',
        ]);

        // 1) Candidats probables : CIN + date de naissance (le plus récent d'abord)
        $candidats = Formulaire::query()
            ->where('cin', $data['cin'])
            ->whereIn('annee_scolaire', self::ANNEE_SCOLAIRE)
            ->whereDate('date_naissance', $data['date_naissance'])
            ->orderByDesc('id')
            ->get();

        // 2) Les autres informations doivent aussi correspondre (sans tenir
        //    compte de la casse, des accents ni des espaces superflus)
        $formulaire = $candidats->first(function (Formulaire $f) use ($data) {
            return $this->memeTexte($f->nom, $data['nom'])
                && $this->memeTexte($f->prenom, $data['prenom'])
                && $this->memeTexte($f->email, $data['email']);
        });

        // Message volontairement générique : on ne révèle pas quelle info est fausse
        if (! $formulaire) {
            return redirect(url()->previous() . '#verifierprisencharge')
                ->withInput($request->except('cin'))
                ->withErrors(['verification' => 'Aucun dossier ne correspond aux informations saisies. Vérifiez-les et réessayez.']);
        }

        return view('verification.resultat', [
            'formulaire' => $formulaire,
            'etat'       => $this->etat($formulaire->statut),
            'cinMasque'  => $this->masquer($formulaire->cin),
        ]);
    }

    /**
     * Convertit le statut brut en l'un des trois états : admis | rejete | attente.
     * ⚠ Adaptez les mots-clés aux valeurs réellement stockées dans `formulaires.statut`.
     */
    /* private function etat(?string $statut): string
    {
        $s = Str::of((string) $statut)->lower()->ascii()->trim()->toString();

        // Les refus sont testés en premier : "non admis" contient "admis"
        // ⚠ $s est déjà en minuscules et sans accents : les mots-clés doivent l'être aussi
        foreach ([
            'non conforme',
            'conforme',
            "liste d'attente",
            'liste attente',
            'non admis', 'non retenu', 'non selectionne', 'non valide', 'invalide',
            'rejet', 'refus', 'recale', 'ajourne',
        ] as $mot) {
            if (str_contains($s, $mot)) {
                return 'rejete';
            }
        }

        // Statuts considérés comme "admis" : Admis(e), Validé(e), Sélectionné(e), etc.
        foreach (['admis', 'valide', 'selectionne', 'accepte', 'retenu', 'approuve'] as $mot) {
            if (str_contains($s, $mot)) {
                return 'admis';
            }
        }

        return 'attente'; // en attente, en cours, vide, inconnu…
    } */

        private function etat(?string $statut): string
            {
                $s = Str::of((string) $statut)->lower()->ascii()->trim()->toString();

                // Refus testés en premier : "non conforme" contient "conforme", "non admis" contient "admis"
                foreach ([
                    'conforme',
                    'non conforme',
                    "liste d'attente",
                    'liste attente',
                    'non admis', 'non retenu', 'non selectionne', 'non valide', 'invalide',
                    'rejet', 'refus', 'recale', 'ajourne',
                ] as $mot) {
                    if (str_contains($s, $mot)) {
                        return 'rejete';
                    }
                }

                // Admis : "conforme" ajouté ici
                foreach (['admis', 'valide', 'selectionne', 'accepte', 'retenu', 'approuve'] as $mot) {
                    if (str_contains($s, $mot)) {
                        return 'admis';
                    }
                }

                return 'attente';
            }

    private function memeTexte(?string $a, ?string $b): bool
    {
        $norm = fn ($v) => Str::of((string) $v)->lower()->ascii()->squish()->toString();

        return $norm($a) !== '' && $norm($a) === $norm($b);
    }

    private function masquer(?string $cin): string
    {
        $cin = (string) $cin;

        return strlen($cin) > 5
            ? substr($cin, 0, 3) . str_repeat('•', strlen($cin) - 5) . substr($cin, -2)
            : $cin;
    }
}
