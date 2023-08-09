<?php

namespace App\Classes;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMeta;
use App\Models\ProductVariationAttribute;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Services\ProductService;
use App\Services\TermService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CSV
{
//    public $filePath;


    public function read($file)
    {
        // Read the Excel file using Maatwebsite\Excel package
        $excelData = Excel::toArray([], $file);

        // Retrieve the first sheet of the Excel file
        return $excelData[0];
    }

    public function write($sheetData)
    {

        // Get the header row (first row) to use as keys
        $headerRow = $sheetData[0];
        $headerCount = count($headerRow);

        // Initialize an array to store the data as JSON objects
        $jsonData = [];

        // Loop through each row (excluding the header row)
        for ($i = 1; $i < count($sheetData); $i++) {
            $rowData = $sheetData[$i];
            $dataObj = [];
            // Loop through each column and map it to the corresponding key
            for ($j = 0; $j < $headerCount; $j++) {
                $key = $headerRow[$j];
                $value = $rowData[$j];

                $dataObj[$key] = $value;
            }

            /**
             * -----------------------------------------------
             * ------- 1 - Add Product --------------
             * -----------------------------------------------
             */

            $attributes["post_author"] = auth()->user()->ID;
            $attributes["post_date"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
            $attributes["post_date_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
            $attributes["post_excerpt"] = "";;
            $attributes["ping_status"] = "closed";
            $attributes["post_password"] = "w";
            $attributes["post_name"] = $dataObj['Name'];
            // missing data
            $attributes["post_content"] = $dataObj['Desc'];
            $attributes['post_title'] = $dataObj['Desc'];
            $attributes["to_ping"] = "";
            $attributes["pinged"] = "";
            $attributes["post_modified"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
            $attributes["post_modified_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
            $attributes["post_content_filtered"] = "";
            $attributes["post_parent"] = "0";
            $attributes["guid"] = "0";
            $attributes["menu_order"] = "0";
            $attributes["post_type"] = "product";
            $attributes["post_mime_type"] = "";
            $attributes["comment_count"] = "0";


            $product = Product::query()->create($attributes);

            if ($product instanceof Product) {
                /**
                 * -----------------------------------------------
                 * ------- 2 - Add Colors and Sizes --------------
                 * -----------------------------------------------
                 */

                $colors = explode(', ', $dataObj['Colors']);
                $sizes = explode(', ', $dataObj['Sizes']);

                // Store colors attribute
                foreach ($colors as $color) {
                    if ($color == null || trim($color) === '') {
                        continue;
                    }

                    // call add color to term service
                    (new TermService())->addColorsToTerms($color, $product->ID);
                }

//                dd('success');

                foreach ($sizes as $size) {
                    if ($size == null || trim($size) === '') {
                        continue;
                    }

                    // call add size to term service
                    (new TermService())->addSizesToTerms($size, $product->ID);
                }
            }
            // Add the data object to the JSON array
            $jsonData[] = $dataObj;
        }

        return $jsonData;
    }

}
