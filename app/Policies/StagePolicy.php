<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stage;
use Illuminate\Auth\Access\HandlesAuthorization;

class StagePolicy
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
        return $user->can('view_any_stage');
    }

    /**
     * Determina si el usuario puede ver un modelo específico.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function view(User $user, Stage $stage): bool
    {
        return $user->can('view_stage');
    }

    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->can('create_stage');
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function update(User $user, Stage $stage): bool
    {
        return $user->can('update_stage');
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function delete(User $user, Stage $stage): bool
    {
        return $user->can('delete_stage');
    }

    /**
     * Determina si el usuario puede borrar en bloque.
     *
     * @param User $user
     * @return boolean
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_stage');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function forceDelete(User $user, Stage $stage): bool
    {
        return $user->can('force_delete_stage');
    }

    /**
     * Determina si el usuario puede realizar eliminación masiva permanente.
     *
     * @param User $user
     * @return boolean
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_stage');
    }

    /**
     * Determina si el usuario puede restaurar.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function restore(User $user, Stage $stage): bool
    {
        return $user->can('restore_stage');
    }

    /**
     * Determina si el usuario puede realizar restauraciones masivas..
     *
     * @param User $user
     * @return boolean
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_stage');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param User $user
     * @param Stage $stage
     * @return boolean
     */
    public function replicate(User $user, Stage $stage): bool
    {
        return $user->can('replicate_stage');
    }

    /**
     * Determina si el usuario puede re-ordenar.
     *
     * @param User $user
     * @return boolean
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_stage');
    }
}
