<?php

declare(strict_types=1);

namespace App\Orliweb;

use Doctrine\DBAL\Statement;
use function Symfony\Component\String\u;

class CompositionImporter extends AbstractImporter
{
    public function getName(): string
    {
        return 'Composition';
    }

    public function createSQLStatement(): Statement
    {
        return $this
            ->connection
            ->prepare('
                -- Insertion/Mise à jour des matières
                INSERT INTO composition (id, name)
                VALUES (:id, :name)
                ON CONFLICT (id) DO UPDATE SET
                    name = excluded.name
            ');
    }

    public function isRowImportable(array $row): bool
    {
        return 'FR' === $row['LANGUE'];
    }

    public function bindValue(Statement $stmt, array $row): void
    {
        $stmt->bindValue(':id', $row['CODE_COMPO']);
        $stmt->bindValue(':name', u($row['LIBELLE'])->lower()->title());
    }

    public function getSavepointName(array $row): string
    {
        return 'sp_composition';
    }
}
