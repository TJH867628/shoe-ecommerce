<?php

namespace App\Services\Builders\Builders;

use App\Models\Shoe;
use App\Models\ShoeVariations;
use App\Models\ShoeOption;
use App\Services\Builders\Interfaces\ShoeSkuBuilderInterface;
use Exception;

class AdminShoeSkuBuilder implements ShoeSkuBuilderInterface
{
    protected array $variations = [];

    public function reset()
    {
        $this->variations = [];
    }

    public function addSku(int $shoeId, array $attributes, int $stock): self
    {
        $allowedOptions = ShoeOption::where(
            'shoe_id',
            $shoeId
        )
            ->pluck('option_name')
            ->toArray();

        foreach (array_keys($attributes) as $attributeName) {
            if (
                !in_array(
                    $attributeName,
                    $allowedOptions
                )
            ) {
                throw new Exception(json_encode([
                    'message' =>
                        "Invalid option: {$attributeName}"
                ]));

            }

        }

        $existingVariation = ShoeVariations::where('shoe_id', $shoeId)
            ->where('attributes', json_encode($attributes))
            ->first();

        if ($existingVariation) {
            throw new Exception(json_encode([
                'message' => 'Variation already exists.',
                'existing_variation' => $existingVariation

            ]));
        }

        $shoe = Shoe::with('brand')->findOrFail($shoeId);

        $skuCode = self::generateSkuCode($shoe, $attributes);

        $this->variations[] = [
            'shoe_id' => $shoeId,
            'attributes' => $attributes,
            'stock' => $stock,
            'sku_code' => $skuCode
        ];

        return $this;
    }

    public function build(): array
    {
        return $this->variations;
    }

    public static function generateSkuCode(Shoe $shoe, array $attributes): string
    {
        $brand = strtoupper($shoe->brand->brand_name);

        $model = strtoupper(
            preg_replace('/\s+/', '', $shoe->shoe_name)
        );

        $attributeParts = [];

        foreach ($attributes as $value) {

            $attributeParts[] = strtoupper($value);
        }

        return "SH-" . $shoe->id . '-' .$brand . '-' . $model . '-' . implode('-', $attributeParts);
    }
}

