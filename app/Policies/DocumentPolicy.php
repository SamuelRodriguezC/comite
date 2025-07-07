<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     *  Determina si el usuario puede ver cualquier modelo.
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_document');
    }

    /**
     *  Determina si el usuario puede ver un modelo específico.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function view(User $user, Document $document): bool
    {
        return $user->can('view_document');
    }

    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->can('create_document');
    }

    /**
     *  Determina si el usuario puede actualizar el modelo.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function update(User $user, Document $document): bool
    {
        return $user->can('update_document');
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->can('delete_document');
    }

    /**
     * Determina si el usuario puede borrar en bloque.
     *
     * @param User $user
     * @return boolean
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_document');
    }

    /**
     *  Determina si el usuario puede eliminar permanentemente.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->can('force_delete_document');
    }

    /**
     *  Determina si el usuario puede realizar eliminación masiva permanente.
     *
     * @param User $user
     * @return boolean
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_document');
    }

    /**
     *  Determina si el usuario puede restaurar.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->can('restore_document');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param User $user
     * @return boolean
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_document');
    }

    /**
     * Determina si el usuario puede hacer copias.
     *
     * @param User $user
     * @param Document $document
     * @return boolean
     */
    public function replicate(User $user, Document $document): bool
    {
        return $user->can('replicate_document');
    }

    /**
     * Determina si el usuario puede re-ordenar.
     *
     * @param User $user
     * @return boolean
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_document');
    }
}
