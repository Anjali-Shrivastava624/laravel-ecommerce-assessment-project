<?php


namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    public $timeout = 3600;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $file = fopen($this->filePath, 'r');

        if (!$file) {
            Log::error('Could not open import file: ' . $this->filePath);
            return;
        }

        $header = fgetcsv($file);
        $chunkSize = 1000;
        $chunk = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;

            $data = array_combine($header, $row);

            if ($this->validateRowData($data, $rowNumber)) {
                $chunk[] = $this->prepareProductData($data);

                if (count($chunk) >= $chunkSize) {
                    $this->processChunk($chunk);
                    $chunk = [];
                }
            }
        }
        if (!empty($chunk)) {
            $this->processChunk($chunk);
        }

        fclose($file);

        unlink($this->filePath);

        Log::info("Product import completed. Processed {$rowNumber} rows.");
    }

    protected function validateRowData($data, $rowNumber)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning("Row {$rowNumber} validation failed: " . implode(', ', $validator->errors()->all()));
            return false;
        }

        return true;
    }

    protected function prepareProductData($data)
    {
        return [
            'name' => trim($data['name']),
            'description' => trim($data['description']),
            'price' => (float) $data['price'],
            'category' => trim($data['category']),
            'stock' => (int) $data['stock'],
            'image' => !empty($data['image']) ? trim($data['image']) : 'default-product.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    protected function processChunk($chunk)
    {
        try {
            Product::insert($chunk);
            Log::info('Processed chunk of ' . count($chunk) . ' products');
        } catch (\Exception $e) {
            Log::error('Error processing chunk: ' . $e->getMessage());
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Import job failed: ' . $exception->getMessage());
    }
}
