<?php

namespace App\Http\Controllers;

use App\Models\OnfpActivite;
use App\Models\OnfpSousActivite;
use App\Models\OnfpTache;
use Illuminate\Http\Request;

class OnfpTacheActiviteController extends Controller
{
    public function index(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        //
    }

    public function create(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        //
    }

    public function store(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite
    ) {
        //
    }

    public function show(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        //
    }

    public function edit(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        //
    }

    public function update(
        Request $request,
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        //
    }

    public function destroy(
        OnfpActivite $activite,
        OnfpSousActivite $sousActivite,
        OnfpTache $tache
    ) {
        //
    }
}