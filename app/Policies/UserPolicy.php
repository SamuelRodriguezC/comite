<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier modelo.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user');
    }

    /**
     * Determina si el usuario puede ver un modelo específico.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->can('view_user');
    }

    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->can('update_user');
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->can('delete_user');
    }

    /**
     * Determina si el usuario puede borrar en bloque.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->can('force_delete_user');
    }

    /**
     * Determina si el usuario puede realizar eliminación masiva permanente.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user');
    }

    /**
     * Determina si el usuario puede restaurar.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->can('restore_user');
    }

    /**
     * Determina si el usuario puede realizar restauraciones masivas.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_user');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function replicate(User $user): bool
    {
        return $user->can('replicate_user');
    }

    /**
     * Determina si el usuario puede re-ordenar.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_user');
    }
}
