<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Option;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier modelo.
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_option');
    }

    /**
     * Determina si el usuario puede ver un modelo específico.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function view(User $user, Option $option): bool
    {
        return $user->can('view_option');
    }

    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->can('create_option');
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function update(User $user, Option $option): bool
    {
        return $user->can('update_option');
    }


    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function delete(User $user, Option $option): bool
    {
        return $user->can('delete_option');
    }

    /**
     * Determina si el usuario puede borrar en bloque.
     *
     * @param User $user
     * @return boolean
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_option');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function forceDelete(User $user, Option $option): bool
    {
        return $user->can('force_delete_option');
    }

    /**
     * Determina si el usuario puede realizar eliminación masiva permanente.
     *
     * @param User $user
     * @return boolean
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_option');
    }

    /**
     * Determina si el usuario puede restaurar.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function restore(User $user, Option $option): bool
    {
        return $user->can('restore_option');
    }

    /**
     * Determina si el usuario puede realizar restauraciones masivas.
     *
     * @param User $user
     * @return boolean
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_option');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param User $user
     * @param Option $option
     * @return boolean
     */
    public function replicate(User $user, Option $option): bool
    {
        return $user->can('replicate_option');
    }

    /**
     * Determina si el usuario puede re-ordenar.
     *
     * @param User $user
     * @return boolean
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_option');
    }
}
