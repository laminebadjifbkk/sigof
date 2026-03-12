<p>Bonjour,</p>

<p>L'activité suivante est en retard :</p>

<ul>
    <li><strong>Titre :</strong> {{ $activite->titre }}</li>
    <li><strong>Agent :</strong> {{ $activite->user->name }}</li>
    <li><strong>Date :</strong> {{ $activite->date_activite }}</li>
</ul>

<p>Merci de vérifier cette activité.</p>