<?php

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Préparation des données avant la validation.
     */
    protected function prepareForValidation()
    {
        // Vérifier si un utilisateur supprimé existe avec cet email
        $user = User::withTrashed()->where('email', $this->email)->orWhere('username', $this->username)->first();

        if ($user && $user->trashed()) {
            $user->restore(); // Restaurer l'utilisateur
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->whereNull('deleted_at')->ignore($this->user()->id)],
            'username'  => ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->whereNull('deleted_at')->ignore($this->user()->id)],
            'firstname' => ['required', 'string', 'max:150'],
            'name'      => ['required', 'string', 'max:25'],
            'image'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'telephone' => ['required', 'string', 'max:9', 'min:9'],
            'adresse'   => ['required', 'string', 'max:255'],
            'roles.*'   => ['string', 'max:255', 'nullable'],
        ];
    }
}
