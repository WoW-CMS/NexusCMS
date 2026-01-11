<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Donate\Domain\Models\DonationPlan;

class DonationPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Bronze',
                'description' => 'Perfect starter package',
                'amount' => 4.50,
                'dp_base' => 500,
                'extra_pct' => 0,
                'is_promo' => false,
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Silver',
                'description' => 'Great value package',
                'amount' => 10.00,
                'dp_base' => 1100,
                'extra_pct' => 10,
                'is_promo' => false,
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Gold',
                'description' => 'Premium package with bonus',
                'amount' => 25.50,
                'dp_base' => 3000,
                'extra_pct' => 20,
                'is_promo' => false,
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Platinum',
                'description' => 'Ultimate package with maximum bonus',
                'amount' => 50.00,
                'dp_base' => 6500,
                'extra_pct' => 30,
                'is_promo' => false,
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Diamond',
                'description' => 'Exclusive package with VIP benefits',
                'amount' => 95.25,
                'dp_base' => 15000,
                'extra_pct' => 50,
                'is_promo' => false,
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Weekend Special',
                'description' => 'Limited time offer! 50% extra points',
                'amount' => 15.00,
                'dp_base' => 1500,
                'extra_pct' => 50,
                'is_promo' => true,
                'active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($plans as $plan) {
            DonationPlan::create($plan);
        }
    }
}