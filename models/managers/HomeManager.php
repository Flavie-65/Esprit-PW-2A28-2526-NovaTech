<?php

declare(strict_types=1);

/**
 * Home module persistence / domain data.
 * No SQL until a home-related table exists; then all queries stay here.
 */
class HomeManager extends BaseManager
{
    /**
     * @return array{pageTitle: string, message: string}
     */
    public function getFrontOfficeIndexData(): array
    {
        return [
            'pageTitle' => 'Gestion d\'équipement',
            'message' => 'Bienvenue. Utilisez le menu pour accéder aux modules.',
        ];
    }

    /**
     * @return array{pageTitle: string, message: string}
     */
    public function getBackOfficeHomeData(): array
    {
        return [
            'pageTitle' => 'Administration',
            'message' => 'Espace réservé à la gestion des données.',
        ];
    }
}
