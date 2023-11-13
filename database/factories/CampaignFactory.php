<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = \App\Models\Campaign::class;

    public function definition()
    {
        $categories = ['Technology', 'Social', 'Lifestyle', 'Business'];
        $offeringTypes = ['equity', 'product_crowdfunding', 'crowdfunding'];

        $offeringType = $this->faker->randomElement($offeringTypes);

        $assetType = null;
        $pricePerShare = null;
        $valuation = null;
        $minInvestment = null;

        if ($offeringType === 'equity') {
            $assetType = 'common_stock';
            $pricePerShare = $this->faker->randomFloat(2, 1, 100);
            $valuation = $this->faker->randomFloat(2, 300, 1000);
            $minInvestment = $this->faker->randomFloat(2, 10, 500);
        } elseif ($offeringType === 'product crowdfunding') {
            $assetTypes = ['commodities', 'intellectual property'];
            $assetType = $this->faker->randomElement($assetTypes);
        }

        return [
            'user_id' => function() {
                return \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory()->create()->id;
            },
            'ethereum_address' => $this->faker->regexify('0x[A-Fa-f0-9]{40}'),
            'title' => $this->faker->word,
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->paragraph,
            'target' => $this->faker->randomFloat(2, 1, 500),
            'deadline' => $this->faker->date,
            'offering_type' => $offeringType,
            'asset_type' => $assetType,
            'price_per_share' => $pricePerShare,
            'valuation' => $valuation,
            'min_investment' => $minInvestment,
            'suspended' => $this->faker->boolean,
            'created_at' => $this->faker->dateTimeBetween('-9 years', 'now'),
        ];
    }
}
