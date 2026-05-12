<?php

declare(strict_types=1);

/**
 * Home module: validates nothing beyond routing; delegates to HomeManager; renders views.
 */
class HomeController extends BaseController
{
    private HomeManager $homeManager;

    public function __construct()
    {
        $this->homeManager = new HomeManager();
    }

    public function index(): void
    {
        $data = $this->homeManager->getFrontOfficeIndexData();
        $this->renderFrontOffice('home/index', $data);
    }

    /**
     * Optional demo route: ?module=home&action=backoffice
     * Shows backoffice view separation (no SQL).
     */
    public function backoffice(): void
    {
        $data = $this->homeManager->getBackOfficeHomeData();
        $this->renderBackOffice('home/index', $data);
    }

    public function dashboard(): void
    {
        $equipmentManager = new EquipmentManager();
        $assignmentManager = new AssignmentManager();

        $this->renderBackOffice('home/dashboard', [
            'pageTitle' => 'Dashboard',
            'equipmentStats' => $equipmentManager->getDashboardStats(),
            'assignmentStats' => $assignmentManager->getDashboardStats(),
            'healthSummary' => $equipmentManager->getHealthDashboardSummary(),
        ]);
    }
}
