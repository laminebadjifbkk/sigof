@component('mail::message')
# Confirmation d'inscription

Bonjour {{ $formulaire->prenom }} {{ $formulaire->nom }},

Nous vous confirmons que votre demande de prise en charge a bien été enregistrée dans notre système.

**Détails de votre inscription :**

- **CIN :** {{ $formulaire->cin }}
- **Établissement :** {{ $formulaire->nom_etablissement }}
- **Région :** {{ $formulaire->region }}
- **Formation sollicitée :** {{ $formulaire->formation }}
- **Diplôme visé :** {{ $formulaire->diplome_vise }}
- **Montant inscription :** {{ $formulaire->montant_inscription }} FCFA
- **Montant mensualité :** {{ $formulaire->montant_mensualite }} FCFA
@if($formulaire->montant_unique)
- **Montant unique :** {{ $formulaire->montant_unique }} FCFA
@endif
- **Durée :** {{ $formulaire->duree }} année(s)

Nous vous remercions de votre confiance.

Cordialement,  
**L’équipe ONFP**

@endcomponent
