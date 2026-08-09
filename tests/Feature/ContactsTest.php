<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\Contacts;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::first());
    }

    public function test_can_list_contacts()
    {
        Contact::create(['name' => 'Tester One', 'email' => 'tester1@example.com', 'phone' => '01600000001', 'source' => 'manual']);
        Contact::create(['name' => 'Tester Two', 'email' => 'tester2@example.com', 'phone' => '01600000002', 'source' => 'import']);

        Livewire::test(Contacts::class)
            ->assertSee('Tester One')
            ->assertSee('Tester Two');
    }

    public function test_search_filters_contacts()
    {
        Contact::create(['name' => 'Alpha Corp', 'email' => 'alpha@example.com', 'source' => 'manual']);
        Contact::create(['name' => 'Beta Ltd', 'email' => 'beta@example.com', 'source' => 'import']);

        Livewire::test(Contacts::class)
            ->set('search', 'alpha')
            ->assertSee('Alpha Corp')
            ->assertDontSee('Beta Ltd');
    }

    public function test_source_filter_filters_contacts()
    {
        Contact::create(['name' => 'Alpha Corp', 'email' => 'alpha@example.com', 'source' => 'manual']);
        Contact::create(['name' => 'Beta Ltd', 'email' => 'beta@example.com', 'source' => 'import']);

        Livewire::test(Contacts::class)
            ->set('source', 'import')
            ->assertSee('Beta Ltd')
            ->assertDontSee('Alpha Corp');
    }

    public function test_can_add_contact()
    {
        Livewire::test(Contacts::class)
            ->call('openAdd')
            ->set('name', 'New Person')
            ->set('email', 'newperson@example.com')
            ->set('phone', '01611111111')
            ->set('contactSource', 'website')
            ->call('save');

        $this->assertDatabaseHas('contacts', [
            'name' => 'New Person',
            'email' => 'newperson@example.com',
            'source' => 'website',
        ]);
    }

    public function test_can_edit_contact()
    {
        $contact = Contact::create(['name' => 'Old Name', 'email' => 'old@example.com', 'source' => 'manual']);

        Livewire::test(Contacts::class)
            ->call('openEdit', $contact->id)
            ->set('name', 'New Name')
            ->set('contactSource', 'import')
            ->call('save');

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'New Name', 'source' => 'import']);
    }

    public function test_can_delete_contact()
    {
        $contact = Contact::create(['name' => 'Doomed', 'email' => 'doomed@example.com']);

        Livewire::test(Contacts::class)
            ->call('delete', $contact->id);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_requires_email_or_phone()
    {
        Livewire::test(Contacts::class)
            ->call('openAdd')
            ->set('name', 'No Info')
            ->set('email', '')
            ->set('phone', '')
            ->call('save')
            ->assertHasErrors(['email', 'phone']);

        $this->assertDatabaseMissing('contacts', ['name' => 'No Info']);
    }
}
