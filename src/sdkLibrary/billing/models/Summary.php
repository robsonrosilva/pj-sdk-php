<?php

namespace Inter\Sdk\sdkLibrary\billing\models;


/**
 * The Summary class represents a collection of SummaryItem objects.
 *
 * This class extends the built-in ArrayObject type, specifically for SummaryItem objects,
 * allowing it to behave like a list while providing a clear type hint for its contents.
 */
class Summary extends \ArrayObject
{
    public static function fromJson(mixed $data): self
    {
        /**
         * Create a Summary instance from a list of dictionaries.
         *
         * Args:
         *     data (array): A list of dictionaries, each containing data for a SummaryItem.
         *
         * Returns:
         *     Summary: An instance of Summary containing SummaryItem objects.
         */
        $summary = new self();
        foreach ($data as $item_data) {
            $summary[] = SummaryItem::fromJson($item_data);
        }

        return $summary;
    }
}