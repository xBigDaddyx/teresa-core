<?php

namespace Xbigdaddyx\HarmonyFlow\Contracts;

interface DesignationContract
{
    public static function findByName(string $name): self;

    /**
     * Find a role by its id and guard name.
     *
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findById(int|string $id): self;

    /**
     * Find or create a role by its name and guard name.
     */
    public static function findOrCreate(string $name): self;
}
