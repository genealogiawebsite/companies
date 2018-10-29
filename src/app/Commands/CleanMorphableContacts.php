<?php

namespace LaravelEnso\Companies\app\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\MenuManager\app\Models\Menu;
use LaravelEnso\PermissionManager\app\Models\Permission;

class CleanMorphableContacts extends Command
{
    protected $signature = 'enso:companies:clean-morphable-contacts';

    protected $description = 'Cleans the structures created by the morphable contacts package';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('The cleanup process has started');
        $this->clean();
        $this->info('The cleanup process was successful.');
    }

    private function clean()
    {
        DB::transaction(function () {
            $this->cleanMenuStructure();
            $this->cleanPermissions();
            $this->dropTable();
        });
    }

    private function cleanPermissions()
    {
        if (! $this->hasLegacyPermissions()) {
            $this->info('The permissions were already cleaned up');

            return $this;
        }

        $this->info('Cleaning up permissions');

        Permission::where('name', 'LIKE', 'core.contacts.%')->delete();

        $this->info('Permissions cleanup was successfully performed');

        return $this;
    }

    private function cleanMenuStructure()
    {
        if (! $this->hasContactMenu()) {
            $this->info('The menu structure was already updated');

            return $this;
        }

        $this->info('Cleaning the menu');

        Menu::where('name', 'Contacts')
            ->delete();

        $this->info('The menu was successfully cleaned up');

        return $this;
    }

    private function hasContactMenu()
    {
        return Menu::where('name', 'Contacts')
                ->count() > 0;
    }

    private function dropTable()
    {
        Schema::dropIfExists('contacts');
    }

    private function hasLegacyPermissions()
    {
        return
            Permission::where('name', 'LIKE', 'core.contacts.%')
                ->count() > 0;
    }
}
