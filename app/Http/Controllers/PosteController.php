<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Poste;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class PosteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|COM|DG']);
        $this->middleware("permission:post-view", ["only" => ["index"]]);
        $this->middleware("permission:post-create", ["only" => ["create", "store"]]);
        $this->middleware("permission:post-update", ["only" => ["update", "edit"]]);
        $this->middleware("permission:post-show", ["only" => ["show"]]);
        $this->middleware("permission:post-delete", ["only" => ["destroy"]]);
        $this->middleware("permission:give-role-permissions", ["only" => ["givePermissionsToRole"]]);
    }
    public function index()
    {
        $postes = Poste::orderBy("created_at", "desc")->get();
        return view('postes.index', compact('postes'));
    }
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $image = $request->validated('image');

        /*  if (request('image')) {
            $imagePath = request('image')->store('posts', 'public');

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(1200, 1200);

            $image->save();
        } else {
            $imagePath = null;
        }

        $poste = Poste::create([
            'titre'    => $data['titre'],
            'name'     => $data['name'],
            'legende'  => $data['legende'],
            'users_id' => auth()->user()->id,
            'image'    => $imagePath,
        ]); */

        if (request()->hasFile('image')) {
            $imageFile = request()->file('image');
            $filename  = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('posts', $filename, 'public');

            // On enregistre l'image sans la redimensionner
            $image = Image::make(public_path("/storage/{$imagePath}"));
            $image->save();
        } else {
            $imagePath = null;
        }

        $poste = Poste::create([
            'titre'    => $data['titre'],
            'name'     => $data['name'],
            'legende'  => $data['legende'],
            'users_id' => auth()->user()->id,
            'image'    => $imagePath,
        ]);

        Alert::success("Succès !", "Le poste a été créé avec succès");

        return redirect()->back();
    }

    public function update(UpdatePostRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();

        $poste = Poste::findOrFail($id);

        /* $image = $request->validated('image');

        if (request('image') && $image->getError()) {
            Storage::disk('public')->delete($poste->image);
            $imagePath = request('image')->store('posts', 'public');

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(1200, 1200);

            $image->save();

        } elseif (request('image') && empty($poste->image)) {
            $imagePath = request('image')->store('posts', 'public');

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(1200, 1200);

            $image->save();

        } elseif (request('image') && ! empty($poste->image)) {
            $imagePath = request('image')->store('posts', 'public');

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(1200, 1200);

            $image->save();

        } else {
            $imagePath = $poste->image;
        }

        $poste->update([
            'titre'    => $data['titre'],
            'name'     => $data['name'],
            'legende'  => $data['legende'],
            'users_id' => auth()->user()->id,
            'image'    => $imagePath,
        ]);

        $poste->save();

        Alert::success("Succès !!!", "La modification a été effectuée avec succès !");

        return redirect()->back(); */

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Supprimer l'ancienne image s'il y en a une
            if (! empty($poste->image) && Storage::disk('public')->exists($poste->image)) {
                Storage::disk('public')->delete($poste->image);
            }

            $imageFile = $request->file('image');
            $filename  = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('posts', $filename, 'public');

            // Sauvegarde sans redimensionnement
            $image = Image::make(public_path("/storage/{$imagePath}"));
            $image->save();
        } else {
            $imagePath = $poste->image;
        }

        $poste->update([
            'titre'    => $data['titre'],
            'name'     => $data['name'],
            'legende'  => $data['legende'],
            'users_id' => auth()->user()->id,
            'image'    => $imagePath,
        ]);

        Alert::success("Succès !!!", "La modification a été effectuée avec succès !");
        return redirect()->back();

    }

    public function destroy($id)
    {

        $poste = Poste::find($id);

        Storage::disk('public')->delete($poste->image);

        $poste->delete();

        Alert::success('Succès !', 'Le poste a été supprimé avec succès !');

        return redirect()->back();
    }
}
