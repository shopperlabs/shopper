<?php

declare(strict_types=1);

return [

    'menu' => 'Campagnes',
    'single' => 'campagne',
    'title' => 'Gérer les campagnes marketing',
    'description' => 'Regroupez vos promotions dans une campagne avec un budget de dépense ou d\'utilisation optionnel.',

    'name' => 'Nom',
    'name_placeholder' => 'Soldes d\'été 2026',
    'currency' => 'Devise',
    'budget_type' => 'Type de budget',
    'budget_amount' => 'Montant du budget',
    'budget_count' => 'Budget d\'utilisation',
    'budget' => 'Budget',
    'promotions' => 'Promotions',
    'no_budget' => 'Aucun budget',
    'currency_frozen_helper' => 'La devise ne peut plus être modifiée une fois que la campagne a enregistré des mouvements de budget.',

    'create' => [
        'description' => 'Créez une nouvelle campagne. Le résumé à droite se met à jour à mesure que vous remplissez le formulaire.',
    ],

    'edit' => [
        'description' => 'Mettez à jour cette campagne et suivez la consommation de son budget.',
    ],

    'sections' => [
        'general' => 'Général',
        'general_description' => 'Nom, devise et visibilité de la campagne.',
        'budget' => 'Budget',
        'budget_description' => 'Plafonnez les dépenses de la campagne ou le nombre d\'utilisations.',
        'schedule' => 'Planification',
        'schedule_description' => 'Dates de début et de fin de la campagne.',
        'advanced' => 'Avancé',
        'advanced_description' => 'Métadonnées personnalisées attachées à la campagne.',
    ],

    'summary' => [
        'title' => 'Résumé de la campagne',
        'empty' => 'Remplissez le formulaire pour voir le résumé se mettre à jour en temps réel.',
        'rows' => [
            'name' => 'Nom',
            'currency' => 'Devise',
            'budget' => 'Budget',
            'schedule' => 'Planification',
            'visibility' => 'Visibilité',
        ],
        'visibility_public' => 'Active',
        'visibility_hidden' => 'Désactivée',
    ],

    'budget_panel' => [
        'title' => 'Consommation du budget',
        'spend' => 'Dépense',
        'count' => 'Utilisation',
        'none' => 'Cette campagne n\'a aucun plafond de budget.',
    ],

    'promotions_panel' => [
        'title' => 'Promotions associées',
        'empty' => 'Aucune promotion associée à cette campagne pour le moment.',
    ],

    'save' => 'Campagne :name enregistrée avec succès !',

];
