<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $user->can('view_any_role');
    }

    /**
     * Determina si el usuario puede ver un modelo específico.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('view_role');
    }

    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->can('create_role');
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can('update_role');
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->can('delete_role');
    }

    /**
     * Determina si el usuario puede borrar en bloque.
     *
     * @param User $user
     * @return boolean
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_role');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determina si el usuario puede realizar eliminación masiva permanente.
     *
     * @param User $user
     * @return boolean
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determina si el usuario puede restaurar.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determina si el usuario puede realizar restauraciones masivas.
     *
     * @param User $user
     * @return boolean
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function replicate(User $user, Role $role): bool
    {
        return $user->can('{{ Replicate }}');
    }


    /**
     * Determina si el usuario puede re-ordenar.
     *
     * @param User $user
     * @return boolean
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
}
