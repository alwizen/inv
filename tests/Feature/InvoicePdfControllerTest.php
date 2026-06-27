<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePdfControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_v3_receipt_pdf(): void
    {
        $company = Company::create([
            'name' => 'PT Contoh',
            'address' => 'Jl. Contoh No. 1',
            'phone' => '081234567890',
        ]);

        $invoice = Invoice::create([
            'company_id' => $company->id,
            'invoice_date' => now()->toDateString(),
            'invoice_number' => 'INV-TEST-001',
            'subtotal' => 100000,
            'use_ppn' => false,
            'ppn_percentage' => 0,
            'ppn_amount' => 0,
            'total' => 100000,
            'due_date' => now()->addDays(7)->toDateString(),
            'recipient_address' => 'Jl. Customer No. 2',
            'recipient' => 'Budi',
            'status' => 'unpaid',
            'note' => 'Test invoice',
        ]);

        $invoice->items()->create([
            'title' => 'Layanan',
            'quantity' => 1,
            'nominal' => 100000,
        ]);

        $response = $this->get(route('invoice.pdf3', $invoice));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
