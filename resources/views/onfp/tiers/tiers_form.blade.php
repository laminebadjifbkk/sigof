@php
    $tiers = $tiers ?? null;
@endphp

<div class="row g-4">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-vcard text-primary me-2"></i>
                    Informations du tiers
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Nom <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="nom"
                        value="{{ old('nom', $tiers->nom ?? '') }}"
                        class="form-control @error('nom') is-invalid @enderror"
                        placeholder="Ex. Amadou Diallo" required>

                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Type
                    </label>

                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}"
                                @selected(old('type', $tiers->type ?? '') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Organisation
                    </label>

                    <input type="text" name="organisation"
                        value="{{ old('organisation', $tiers->organisation ?? '') }}"
                        class="form-control @error('organisation') is-invalid @enderror"
                        placeholder="Ex. PNUD, Ministère de la Santé...">

                    @error('organisation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Fonction
                    </label>

                    <input type="text" name="fonction"
                        value="{{ old('fonction', $tiers->fonction ?? '') }}"
                        class="form-control @error('fonction') is-invalid @enderror"
                        placeholder="Ex. Consultant senior">

                    @error('fonction')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Adresse
                    </label>

                    <textarea name="adresse" rows="2"
                        class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse', $tiers->adresse ?? '') }}</textarea>

                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div>

                    <label class="form-label fw-semibold">
                        Observation
                    </label>

                    <textarea name="observation" rows="3"
                        class="form-control @error('observation') is-invalid @enderror">{{ old('observation', $tiers->observation ?? '') }}</textarea>

                    @error('observation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-telephone text-primary me-2"></i>
                    Contact
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Téléphone
                    </label>

                    <input type="text" name="telephone"
                        value="{{ old('telephone', $tiers->telephone ?? '') }}"
                        class="form-control @error('telephone') is-invalid @enderror"
                        placeholder="Ex. +221 77 000 00 00">

                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>


                <div>

                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input type="email" name="email"
                        value="{{ old('email', $tiers->email ?? '') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Ex. contact@exemple.com">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-toggle-on text-primary me-2"></i>
                    État
                </h5>
            </div>

            <div class="card-body">

                <div class="form-check form-switch">

                    <input type="hidden" name="actif" value="0">

                    <input type="checkbox" name="actif" value="1" id="actifSwitch"
                        class="form-check-input"
                        @checked(old('actif', $tiers->actif ?? true))>

                    <label class="form-check-label" for="actifSwitch">
                        Tiers actif
                    </label>

                </div>

                <div class="form-text mt-1">
                    Un tiers inactif n'apparaît plus dans les listes de sélection
                    lors de l'ajout à une activité.
                </div>

            </div>

        </div>

    </div>

</div>
