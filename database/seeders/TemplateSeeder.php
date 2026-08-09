<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Seed default email and popup templates.
     *
     * @return void
     */
    public function run()
    {
        Template::resetDefaultTemplates();
        Template::resetPopupTemplates();
    }
}
